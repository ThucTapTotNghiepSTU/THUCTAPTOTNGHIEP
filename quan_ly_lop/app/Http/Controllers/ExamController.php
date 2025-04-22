<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Exam;
use App\Models\SubList;
use App\Models\StudentClass;
use App\Models\Classroom;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class ExamController extends Controller
{
    public function index()
    {
        return view('todopage');
    }
    public function show($id)
    {
        $exam = Exam::with([
            'subList.subListQuestions.question.options'
        ])->find($id);

        if (!$exam) {
            return response()->json(['message' => 'Bài thi không tồn tại!'], Response::HTTP_NOT_FOUND);
        }

        // Kiểm tra thuộc tính isShuffle
        $isShuffle = $exam->subList->isShuffle;

        // Lấy câu hỏi từ subListQuestions
        $questions = $exam->subList->subListQuestions->map(function ($item) use ($exam, $isShuffle) {
            $question = $item->question;

            if ($exam->type === 'Trắc nghiệm') {
                // Nếu là bài thi trắc nghiệm, xáo trộn các lựa chọn câu hỏi nếu isShuffle là true hoặc 1
                $choices = $question->options->map(function ($opt) {
                    return $opt->option_text;
                });

                // Nếu isShuffle là true hoặc 1, xáo trộn các lựa chọn câu hỏi
                if ($isShuffle == 1 || $isShuffle === true) {
                    $choices = $choices->shuffle(); // Xáo trộn lựa chọn câu hỏi
                }

                return [
                    'question_id' => $question->question_id,
                    'content'     => $question->content,
                    'choices'     => $choices,
                ];
            } else {
                // Nếu không phải bài thi trắc nghiệm
                return [
                    'question_id' => $question->question_id,
                    'content'     => $question->content,
                ];
            }
        });

        // Nếu isShuffle là true hoặc 1, xáo trộn thứ tự các câu hỏi trong bài thi
        if ($isShuffle == 1 || $isShuffle === true) {
            $questions = $questions->shuffle(); // Xáo trộn câu hỏi
        }

        return response()->json([
            'exam_id'   => $exam->exam_id,
            'title'     => $exam->title,
            'type'      => $exam->type,
            'start_time'      => $exam->start_time,
            'end_time'      => $exam->end_time,
            'isSimultaneous' => $exam->isSimultaneous,
            'questions' => $questions,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'sub_list_id' => 'required|string|max:100',
            'title' => 'required|string|max:100',
            'content' => 'nullable|string|max:100',
            'type' => 'required|string|in:' . implode(',', Exam::getAllowedTypes()),
            'isSimultaneous' => 'required|integer|in:0,1',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'status' => 'required|string|in:' . implode(',', Exam::getAllowedStatuses()),
        ]);

        $exam = Exam::create([
            'exam_id' => Str::uuid(),
            'sub_list_id' => $request->sub_list_id,
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'isSimultaneous' => $request->isSimultaneous,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status,
        ]);
        $students = DB::table('exam')
            ->join('sub_list', 'exam.sub_list_id', '=', 'sub_list.sub_list_id')
            ->join('sub_list_question', 'sub_list.sub_list_id', '=', 'sub_list_question.sub_list_id')
            ->join('question', 'sub_list_question.question_id', '=', 'question.question_id')
            ->join('list_question', 'question.list_question_id', '=', 'list_question.list_question_id')
            ->join('classroom', 'list_question.course_id', '=', 'classroom.course_id')
            ->join('student_class', 'classroom.class_id', '=', 'student_class.class_id')
            ->join('student', 'student_class.student_id', '=', 'student.student_id')
            ->where('exam.exam_id', $exam->exam_id)
            ->select('student.full_name', 'student.school_email')
            ->distinct()
            ->get();

        $mailFailed = false;

        foreach ($students as $student) {
            $to = $student->school_email;
            $subject = 'Thông báo bài thi mới';
            $message = "Chào {$student->full_name},\n\n";
            $message .= "Bạn có bài thi mới: {$exam->title}\n";
            $message .= "Loại: {$exam->type}\n";
            $message .= "Bắt đầu: {$exam->start_time}\n";
            $message .= "Kết thúc: {$exam->end_time}\n\n";
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
                'exam' => $exam
            ], 201);
        }

        return response()->json([
            'message' => 'Bài thi đã được tạo thành công!',
            'exam' => $exam
        ], 201);
    }



    //  Cập nhật bài thi
    public function update(Request $request, $id)
    {
        $exam = Exam::find($id);
        if (!$exam) {
            return response()->json(['message' => 'Bài thi không tồn tại!'], Response::HTTP_NOT_FOUND);
        }

        $exam->update($request->all());

        return response()->json([
            'message' => 'Bài thi đã được cập nhật!',
            'exam' => $exam
        ]);
    }

    //  Xóa bài thi
    public function destroy($id)
    {
        $exam = Exam::find($id);
        if (!$exam) {
            return response()->json(['message' => 'Bài thi không tồn tại!'], Response::HTTP_NOT_FOUND);
        }

        $exam->delete();

        return response()->json(['message' => 'Bài thi đã bị xóa!']);
    }

    /**
     * Lấy chi tiết bài kiểm tra kèm số lượng sinh viên đã nộp bài
     */
    public function getExamDetail($examId)
    {
        // Tìm bài kiểm tra theo ID
        $exam = Exam::with([
            'subList.subListQuestions.question.options',
        ])->findOrFail($examId);

        // Đếm số lượng bài nộp
        $submissionCount = Submission::where('exam_id', $examId)->count();

        // Lấy danh sách bài nộp kèm thông tin sinh viên và câu trả lời
        $submissions = Submission::with('student', 'answers')
            ->where('exam_id', $examId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'exam' => $exam,
                'submission_count' => $submissionCount,
                'submissions' => $submissions
            ]
        ]);
    }
}
