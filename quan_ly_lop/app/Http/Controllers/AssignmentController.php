<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\ClassModel;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
class AssignmentController extends Controller
{
    /**
     * Lấy danh sách tất cả bài tập
     */
    public function index()
    {
        $assignments = Assignment::with('classes')->get();
        return view('assignments.index', compact('assignments'));
    }

    /**
     * Lấy chi tiết một bài tập
     */
    public function show($id)
    {
        // Load assignment with nested relationships for questions and options
        $assignments = Assignment::with([
            'subList.subListQuestions.question.options'
        ])->find($id);

        // Check if the assignment exists
        if (!$assignments) {
            return response()->json(['message' => 'Bài thi không tồn tại!'], Response::HTTP_NOT_FOUND);
        }

        // Map questions based on the assignment type (Multiple choice or others)
        $questions = $assignments->subList->subListQuestions->map(function ($item) use ($assignments) {
            $question = $item->question;

            // If it's a multiple-choice question
            if ($assignments->type === 'Trắc nghiệm') {
                return [
                    'question_id' => $question->question_id,
                    'content' => $question->content,
                    'choices' => $question->options->map(function ($opt) {
                        return $opt->option_text;
                    }),
                ];
            } else {
                // If it's not a multiple-choice question (e.g., essay or short answer)
                return [
                    'question_id' => $question->question_id,
                    'content' => $question->content,
                ];
            }
        });

        // Return the assignment data with questions
        return response()->json([
            'assignment_id' => $assignments->assignment_id,
            'title' => $assignments->title,
            'type' => $assignments->type,
            'start_time' => $assignments->start_time,
            'end_time' => $assignments->end_time,
            'isSimultaneous' => $assignments->isSimultaneous,
            'questions' => $questions,
        ]);
    }


    /**
     * Hiển thị form tạo mới bài tập
     */
    public function create()
    {
        $classes = ClassModel::all();
        return view('assignments.form', compact('classes'));
    }

    /**
     * Thêm mới một bài tập
     */
    public function store(Request $request)
    {
        try {
            // Xác thực dữ liệu
            $validated = $request->validate([
                'sub_list_id' => 'required|exists:sub_list,sub_list_id',
                'title' => 'required|string|max:255',
                'content' => 'nullable|string',
                'type' => 'required|string|in:' . implode(',', Assignment::getAllowedTypes()),
                'isSimultaneous' => 'boolean',
                'show_result' => 'boolean',
                'start_time' => 'nullable|date',
                'end_time' => 'nullable|date|after_or_equal:start_time',
                'status' => 'required|string|in:' . implode(',', Assignment::getAllowedStatuses()),
            ]);
            // Tạo bài tập
            $assignment = Assignment::create([
                'assignment_id' => Str::uuid(),
                'sub_list_id' => $validated['sub_list_id'],
                'title' => $validated['title'],
                'content' => $validated['content'],
                'type' => $validated['type'],
                'isSimultaneous' => $validated['isSimultaneous'] ?? false,
                'show_result' => $validated['show_result'] ?? false,
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'status' => $validated['status'],
            ]);

            return response()->json([
                'message' => 'Tạo bài tập thành công',
                'assignment' => $assignment
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Lỗi xác thực dữ liệu tạo bài tập:', ['errors' => $e->errors()]);
            return response()->json([
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Lỗi khi tạo bài tập: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Lỗi khi tạo bài tập',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function storeAss(Request $request)
    {
        // Xác thực dữ liệu đầu vào từ request với các quy tắc sau:
        $request->validate([
            'sub_list_id' => 'required|string|max:100',
            'title' => 'required|string|max:100',
            'content' => 'nullable|string|max:100',
            'type' => 'required|string|in:' . implode(',', Assignment::getAllowedTypes()),
            'isSimultaneous' => 'required|integer|in:0,1',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'show_result' => 'required|boolean',
            'status' => 'required|string|in:' . implode(',', Assignment::getAllowedStatuses()),
        ]);

        $assignment = Assignment::create([
            'assignment_id' => Str::uuid(),
            'sub_list_id' => $request->sub_list_id,
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'isSimultaneous' => $request->isSimultaneous,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'show_result' => $request->show_result,
            'status' => $request->status,
        ]);
        $students = DB::table('assignment')
            ->join('sub_list', 'assignment.sub_list_id', '=', 'sub_list.sub_list_id')
            ->join('sub_list_question', 'sub_list.sub_list_id', '=', 'sub_list_question.sub_list_id')
            ->join('question', 'sub_list_question.question_id', '=', 'question.question_id')
            ->join('list_question', 'question.list_question_id', '=', 'list_question.list_question_id')
            ->join('classroom', 'list_question.course_id', '=', 'classroom.course_id')
            ->join('student_class', 'classroom.class_id', '=', 'student_class.class_id')
            ->join('student', 'student_class.student_id', '=', 'student.student_id')
            ->where('assignment.assignment_id', $assignment->assignment_id)
            ->select('student.full_name', 'student.school_email')
            ->distinct()
            ->get();

        $mailFailed = false;

        foreach ($students as $student) {
            $to = $student->school_email;
            $subject = 'Thông báo bài tập mới';
            $message = "Chào {$student->full_name},\n\n";
            $message .= "Bạn có bài tập mới: {$assignment->title}\n";
            $message .= "Loại: {$assignment->type}\n";
            $message .= "Bắt đầu: {$assignment->start_time}\n";
            $message .= "Kết thúc: {$assignment->end_time}\n\n";
            $message .= "Vui lòng kiểm tra hệ thống để biết thêm chi tiết.\n\n";
            $message .= "Trân trọng!";

            try {
                Mail::raw($message, function ($msg) use ($to, $subject) {
                    $msg->to($to)->subject($subject);
                });
            } catch (\Exception $e) {
                $mailFailed = true;
            }
        }

        if ($mailFailed) {
            return response()->json([
                'message' => 'Bài thi đã được tạo, nhưng một số email không gửi được.',
                'exam' => $assignment
            ], 201);
        }

        return response()->json([
            'message' => 'Bài thi đã được tạo thành công!',
            'exam' => $assignment
        ], 201);
    }

    /**
     * Hiển thị form cập nhật bài tập
     */
    public function edit(Assignment $assignment)
    {
        $classes = ClassModel::all();
        return view('assignments.form', compact('assignment', 'classes'));
    }

    /**
     * Cập nhật một bài tập
     */
    public function update(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:assignment,exam',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after:start_time',
            'is_simultaneous' => 'boolean',
            'class_ids' => 'required|array',
            'class_ids.*' => 'exists:classes,id'
        ]);

        $assignment->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'is_simultaneous' => $validated['is_simultaneous'] ?? false
        ]);

        $assignment->classes()->sync($validated['class_ids']);

        return redirect()->route('assignments.index')
            ->with('success', 'Assignment updated successfully.');
    }

    /**
     * Xóa một bài tập
     */
    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('assignments.index')
            ->with('success', 'Assignment deleted successfully.');
    }

    public function getAllAssignments()
    {
        try {
            $assignments = DB::table('assignment')
                ->select(
                    'assignment_id',
                    'sub_list_id',
                    'title',
                    'content',
                    'type',
                    'isSimultaneous',
                    'start_time',
                    'end_time',
                    'show_result',
                    'status',
                    'created_at'
                )
                ->get();

            return response()->json([
                'success' => true,
                'data' => $assignments,
                'message' => 'Đã lấy danh sách tất cả bài tập thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi lấy dữ liệu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Lấy tất cả bài thi
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllExams()
    {
        try {
            $exams = DB::table('exam')
                ->select(
                    'exam_id',
                    'sub_list_id',
                    'title',
                    'content',
                    'type',
                    'isSimultaneous',
                    'start_time',
                    'end_time',
                    'status',
                    'created_at'
                )
                ->get();

            return response()->json([
                'success' => true,
                'data' => $exams,
                'message' => 'Đã lấy danh sách tất cả bài thi thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi lấy dữ liệu',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Lấy chi tiết bài tập kèm các câu hỏi và tùy chọn trả lời
     */
    public function getAssignmentDetail($assignmentId)
    {
        // Tìm bài tập theo ID
        $assignment = Assignment::with([
            'subList.subListQuestions.question.options',
        ])->findOrFail($assignmentId);

        // Đếm số lượng bài nộp
        $submissionCount = Submission::where('assignment_id', $assignmentId)->count();

        // Lấy danh sách bài nộp kèm thông tin sinh viên và câu trả lời
        $submissions = Submission::with('student', 'answers')
            ->where('assignment_id', $assignmentId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'assignment' => $assignment,
                'submission_count' => $submissionCount,
                'submissions' => $submissions
            ]
        ]);
    }
}
