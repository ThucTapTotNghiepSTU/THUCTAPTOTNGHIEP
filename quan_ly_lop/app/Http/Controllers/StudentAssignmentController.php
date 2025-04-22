<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\Exam;
use App\Models\Submission;
use App\Models\Score;
use App\Models\Student;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StudentAssignmentController extends Controller
{
    /**
     * Get list of available assignments for the student
     */
    public function getAssignments($student_id)
    {
        // Kiểm tra sinh viên tồn tại
        $student = Student::find($student_id);
        if (!$student) {
            return response()->json(['message' => 'Không tìm thấy sinh viên với ID: ' . $student_id], 404);
        }

        // Get classes the student is enrolled in
        $studentClasses = $student->studentClasses()
            ->where('status', 'Active')
            ->pluck('class_id');

        if ($studentClasses->isEmpty()) {
            return response()->json(['message' => 'Sinh viên này không đăng ký lớp học nào hoặc không có lớp học đang hoạt động.'], 404);
        }

        // Get assignments for those classes
        $assignments = Assignment::whereIn('sub_list_id', function ($query) use ($studentClasses) {
            $query->select('sub_list_id')
                ->from('list_question')
                ->whereIn('course_id', function ($subQuery) use ($studentClasses) {
                    $subQuery->select('course_id')
                        ->from('classroom')
                        ->whereIn('class_id', $studentClasses);
                });
        })->get();

        return response()->json($assignments);
    }

    /**
     * Get list of available exams for the student
     */
    public function getExams($student_id)
    {
        // Kiểm tra sinh viên tồn tại
        $student = Student::find($student_id);
        if (!$student) {
            return response()->json(['message' => 'Không tìm thấy sinh viên với ID: ' . $student_id], 404);
        }

        // Get classes the student is enrolled in
        $studentClasses = $student->studentClasses()
            ->where('status', 'Active')
            ->pluck('class_id');

        if ($studentClasses->isEmpty()) {
            return response()->json(['message' => 'Sinh viên này không đăng ký lớp học nào hoặc không có lớp học đang hoạt động.'], 404);
        }

        // Get exams that are related to the student's courses
        $exams = Exam::whereIn('sub_list_id', function ($query) use ($studentClasses) {
            $query->select('sub_list_id')
                ->from('list_question')
                ->whereIn('course_id', function ($subQuery) use ($studentClasses) {
                    $subQuery->select('course_id')
                        ->from('classroom')
                        ->whereIn('class_id', $studentClasses);
                });
        })->get();

        return response()->json($exams);
    }

    /**
     * Submit an assignment or exam
     */
    public function submitWork(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student,student_id',
            'exam_id' => 'nullable|exists:exam,exam_id',
            'assignment_id' => 'nullable|exists:assignment,assignment_id',
            'answer_file' => 'required|file|max:10240',
        ]);

        // Check if only one of exam_id or assignment_id is provided
        if (
            (empty($request->exam_id) && empty($request->assignment_id)) ||
            (!empty($request->exam_id) && !empty($request->assignment_id))
        ) {
            return response()->json(['message' => 'A submission must belong to either an Exam or an Assignment, not both.'], 400);
        }

        // Check if student has already submitted this work
        $existingSubmission = Submission::where('student_id', $request->student_id)
            ->where(function ($query) use ($request) {
                if ($request->has('exam_id')) {
                    $query->where('exam_id', $request->exam_id);
                }
                if ($request->has('assignment_id')) {
                    $query->where('assignment_id', $request->assignment_id);
                }
            })->first();

        if ($existingSubmission) {
            return response()->json(['message' => 'You have already submitted this work!'], 400);
        }

        // Store the submitted file
        $filePath = $request->file('answer_file')->store('submissions');

        // Check if submission is late
        $isLate = false;
        $endTime = null;

        if ($request->has('exam_id') && $request->exam_id) {
            $exam = Exam::find($request->exam_id);
            $endTime = $exam->end_time;
        } elseif ($request->has('assignment_id') && $request->assignment_id) {
            $assignment = Assignment::find($request->assignment_id);
            $endTime = $assignment->end_time;
        }

        if ($endTime && now()->greaterThan($endTime)) {
            $isLate = true;
        }

        // Create the submission
        $submission = Submission::create([
            'submission_id' => Str::random(50), // Generating a unique ID
            'student_id' => $request->student_id,
            'exam_id' => $request->exam_id,
            'assignment_id' => $request->assignment_id,
            'answer_file' => $filePath,
            'is_late' => $isLate,
            'temporary_score' => null,
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'Submission successful!', 'submission' => $submission], 201);
    }

    /**
     * Submit answers for questions
     */
    public function submitAnswers(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:student,student_id',
            'exam_id' => 'nullable|exists:exam,exam_id',
            'assignment_id' => 'nullable|exists:assignment,assignment_id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:question,question_id',
            'answers.*.answer_content' => 'required|string',
            'is_final_submission' => 'boolean', // Thêm trường để xác định có phải nộp bài cuối cùng không
        ]);

        // Đảm bảo có ít nhất một trong hai: exam_id hoặc assignment_id
        if (!$request->exam_id && !$request->assignment_id) {
            return response()->json(['message' => 'Phải cung cấp exam_id hoặc assignment_id.'], 400);
        }

        // Kiểm tra sinh viên tồn tại
        $student = Student::find($request->student_id);
        if (!$student) {
            return response()->json(['message' => 'Không tìm thấy sinh viên.'], 404);
        }

        // Kiểm tra bài nộp đã tồn tại chưa
        $submission = Submission::where('student_id', $request->student_id)
            ->where(function ($query) use ($request) {
                if ($request->exam_id) {
                    $query->where('exam_id', $request->exam_id);
                } else {
                    $query->where('assignment_id', $request->assignment_id);
                }
            })->first();

        // Xử lý khác nhau cho bài kiểm tra và bài tập
        if ($submission) {
            // Với bài kiểm tra đã nộp, không cho chỉnh sửa
            if ($request->exam_id && $submission->exam_id) {
                $exam = Exam::find($request->exam_id);
                if ($exam->status === 'Completed') {
                    return response()->json(['message' => 'Bài kiểm tra đã được nộp và không thể chỉnh sửa.'], 403);
                }
            }
            // Với bài tập, kiểm tra thời hạn
            elseif ($request->assignment_id && $submission->assignment_id) {
                $assignment = Assignment::find($request->assignment_id);
                if (now() > $assignment->end_time) {
                    return response()->json(['message' => 'Đã quá thời hạn nộp bài tập.'], 403);
                }
            }
        }

        // Nếu bài nộp chưa tồn tại, tạo mới
        if (!$submission) {
            $submissionData = [
                'submission_id' => Str::random(50),
                'student_id' => $request->student_id,
                'created_at' => now()
            ];

            if ($request->exam_id) {
                $exam = Exam::find($request->exam_id);
                $submissionData['exam_id'] = $request->exam_id;
                $submissionData['is_late'] = now() > $exam->end_time;
            } else {
                $assignment = Assignment::find($request->assignment_id);
                $submissionData['assignment_id'] = $request->assignment_id;
                $submissionData['is_late'] = now() > $assignment->end_time;
            }

            $submission = Submission::create($submissionData);
        }

        // Lưu hoặc cập nhật từng câu trả lời
        foreach ($request->answers as $answerData) {
            $question = Question::find($answerData['question_id']);

            // Kiểm tra câu trả lời đã tồn tại chưa
            $existingAnswer = DB::table('answer')
                ->where('submission_id', $submission->submission_id)
                ->where('question_title', $question->title)
                ->first();

            if ($existingAnswer) {
                // Cập nhật câu trả lời đã tồn tại
                DB::table('answer')
                    ->where('answer_id', $existingAnswer->answer_id)
                    ->update([
                        'question_answer' => $answerData['answer_content']
                    ]);
            } else {
                // Tạo câu trả lời mới
                DB::table('answer')->insert([
                    'answer_id' => Str::random(50),
                    'submission_id' => $submission->submission_id,
                    'question_title' => $question->title,
                    'question_content' => $question->content,
                    'question_answer' => $answerData['answer_content'],
                ]);
            }
        }

        // Cập nhật thời gian nộp bài
        $submission->update([
            'update_at' => now()
        ]);

        // Nếu là bài nộp cuối cùng và là bài kiểm tra, cập nhật trạng thái
        if ($request->is_final_submission && $request->exam_id) {
            Exam::where('exam_id', $request->exam_id)
                ->update(['status' => 'Completed']);

            return response()->json([
                'message' => 'Nộp bài kiểm tra thành công! Bài làm của bạn đã được hoàn thành và không thể chỉnh sửa.',
                'submission_id' => $submission->submission_id
            ], 201);
        }

        // Phản hồi khác nhau cho bài tập và bài kiểm tra
        if ($request->exam_id) {
            return response()->json([
                'message' => 'Đã lưu câu trả lời bài kiểm tra. Vui lòng nộp bài khi hoàn thành.',
                'submission_id' => $submission->submission_id
            ], 200);
        } else {
            return response()->json([
                'message' => 'Đã lưu câu trả lời bài tập. Bạn có thể tiếp tục chỉnh sửa trong thời hạn nộp bài.',
                'submission_id' => $submission->submission_id
            ], 200);
        }
    }

    /**
     * Get submission status for a student
     */
    public function getSubmissionStatus($student_id)
    {
        $submissions = Submission::where('student_id', $student_id)
            ->get();

        foreach ($submissions as $submission) {
            if ($submission->exam_id) {
                $submission->type = 'Exam';
                $submission->title = Exam::find($submission->exam_id)->title;
            } else if ($submission->assignment_id) {
                $submission->type = 'Assignment';
                $submission->title = Assignment::find($submission->assignment_id)->title;
            }
        }

        return response()->json($submissions);
    }

    /**
     * Get scores for a student
     */
    public function getScores($student_id)
    {
        $scores = Score::where('student_id', $student_id)->get();

        foreach ($scores as $score) {
            $score->course_name = $score->course->course_name;
        }

        return response()->json($scores);
    }

    /**
     * Get questions for an exam or assignment
     */
    public function getQuestions(Request $request)
    {
        $request->validate([
            'exam_id' => 'nullable|exists:exam,exam_id',
            'assignment_id' => 'nullable|exists:assignment,assignment_id',
        ]);

        if (
            (empty($request->exam_id) && empty($request->assignment_id)) ||
            (!empty($request->exam_id) && !empty($request->assignment_id))
        ) {
            return response()->json(['message' => 'Please provide either an exam_id or assignment_id.'], 400);
        }

        $subListId = null;

        if ($request->exam_id) {
            $subListId = Exam::find($request->exam_id)->sub_list_id;
        } else {
            $subListId = Assignment::find($request->assignment_id)->sub_list_id;
        }

        // Get questions from the sub_list
        $questions = Question::whereIn('question_id', function ($query) use ($subListId) {
            $query->select('question_id')
                ->from('sub_list_question')
                ->where('sub_list_id', $subListId);
        })->get();

        return response()->json($questions);
    }
    // Lấy danh sách tất cả submission của sinh viên theo assignment/exam
    public function listSubmissions(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:assignment,exam',
            'target_id' => 'required|uuid', // assignment_id hoặc exam_id
        ]);

        $query = Submission::query();

        if ($validated['type'] === 'assignment') {
            $query->where('assignment_id', $validated['target_id']);
            $assignment = Assignment::findOrFail($validated['target_id']);
            return view('submissions.index', [
                'submissions' => $query->with(['student'])->get(),
                'assignment' => $assignment
            ]);
        } else {
            $query->where('exam_id', $validated['target_id']);
            $exam = Exam::findOrFail($validated['target_id']);
            return view('submissions.index', [
                'submissions' => $query->with(['student'])->get(),
                'exam' => $exam
            ]);
        }
    }

    // Tạo mới submission
    public function storeSubmission(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|uuid|exists:students,id',
            'assignment_id' => 'nullable|uuid|exists:assignments,id',
            'exam_id' => 'nullable|uuid|exists:exams,id',
            'file_path' => 'nullable|string',
            'status' => 'nullable|in:submitted,draft,late',
        ]);

        // Bắt buộc phải có 1 trong 2: assignment_id hoặc exam_id
        if (!$validated['assignment_id'] && !$validated['exam_id']) {
            return response()->json(['error' => 'assignment_id hoặc exam_id là bắt buộc'], 422);
        }

        $submission = Submission::create([
            'id' => Str::uuid(),
            'student_id' => $validated['student_id'],
            'assignment_id' => $validated['assignment_id'] ?? null,
            'exam_id' => $validated['exam_id'] ?? null,
            'file_path' => $validated['file_path'] ?? null,
            'status' => $validated['status'] ?? 'submitted',
        ]);

        return response()->json($submission, 201);
    }

    // Xem chi tiết submission
    public function showSubmission($id)
    {
        $submission = Submission::with(['student', 'assignment', 'exam'])->findOrFail($id);
        return response()->json($submission);
    }

    // Cập nhật submission
    public function updateSubmission(Request $request, $id)
    {
        $submission = Submission::findOrFail($id);

        $validated = $request->validate([
            'file_path' => 'nullable|string',
            'status' => 'nullable|in:submitted,draft,late',
        ]);

        $submission->update($validated);

        return response()->json($submission);
    }

    // Xoá submission
    public function deleteSubmission($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->delete();

        return response()->json(['message' => 'Xoá thành công']);
    }
    /**
     * Lưu tạm câu trả lời
     */
    public function saveTemporaryAnswer(Request $request)
    {
        // Lấy student_id từ request, cho phép truyền id để test
        $studentId = $request->student_id ?? 'ST001'; // Mặc định là 'ST001' nếu không có
        $assignmentId = $request->assignment_id;
        $examId = $request->exam_id;
        $answers = $request->answers; // Array of question_id and answer

        // Kiểm tra xem đã có submission tạm thời chưa
        $submission = Submission::where([
            'student_id' => $studentId,
        ])
            ->when($assignmentId, function ($query) use ($assignmentId) {
                return $query->where('assignment_id', $assignmentId);
            })
            ->when($examId, function ($query) use ($examId) {
                return $query->where('exam_id', $examId);
            })
            ->first();

        // Nếu chưa có, tạo mới
        if (!$submission) {
            $submission = Submission::create([
                'submission_id' => Str::uuid(),
                'student_id' => $studentId,
                'assignment_id' => $assignmentId,
                'exam_id' => $examId,
                'created_at' => now(),
                'is_late' => false,
            ]);
        }

        // Lưu hoặc cập nhật từng câu trả lời
        foreach ($answers as $questionData) {
            Answer::updateOrCreate(
                [
                    'submission_id' => $submission->submission_id,
                    'question_title' => $questionData['question_title'],
                ],
                [
                    'answer_id' => $questionData['answer_id'] ?? Str::uuid(),
                    'question_content' => $questionData['question_content'],
                    'question_answer' => $questionData['answer'],
                ]
            );
        }

        return response()->json([
            'message' => 'Đã lưu tạm thời',
            'submission_id' => $submission->submission_id,
            'student_id' => $studentId
        ]);
    }

    /**
     * Lấy bài làm tạm thời
     */
    public function getTemporarySubmission(Request $request)
    {
        // Lấy student_id từ request, cho phép truyền id để test
        $studentId = $request->student_id ?? 'ST001'; // Mặc định là 'ST001' nếu không có
        $assignmentId = $request->assignment_id;
        $examId = $request->exam_id;

        $query = Submission::where('student_id', $studentId);

        if ($assignmentId) {
            $query->where('assignment_id', $assignmentId);
        }

        if ($examId) {
            $query->where('exam_id', $examId);
        }

        $submission = $query->first();

        if (!$submission) {
            return response()->json([
                'message' => 'Không tìm thấy bài làm tạm thời',
                'student_id' => $studentId,
                'assignment_id' => $assignmentId,
                'exam_id' => $examId
            ], 404);
        }

        // Get answers separately - relationship access through a method is more reliable
        $answers = Answer::where('submission_id', $submission->submission_id)->get();

        return response()->json([
            'submission' => $submission,
            'answers' => $answers
        ]);
    }

    /**
     * Hoàn thành và nộp bài
     */
    public function finalizeSubmission(Request $request)
    {
        $submissionId = $request->submission_id;
        $submission = Submission::findOrFail($submissionId);

        // Kiểm tra thời gian
        if ($submission->assignment_id) {
            $assignment = Assignment::find($submission->assignment_id);
            if ($assignment) {
                $isLate = now() > $assignment->end_time;
                $submission->is_late = $isLate;
            }
        } elseif ($submission->exam_id) {
            $exam = Exam::find($submission->exam_id);
            if ($exam) {
                $isLate = now() > $exam->end_time;
                $submission->is_late = $isLate;
            }
        }

        // Cập nhật trạng thái - use update_at field from schema
        $submission->update_at = now();
        $submission->save();

        return response()->json([
            'message' => 'Đã nộp bài thành công',
            'submission' => $submission
        ]);
    }
}
