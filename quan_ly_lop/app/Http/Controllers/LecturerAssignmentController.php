<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Exam;
use App\Models\ListQuestion;
use App\Models\Question;
use App\Models\SubList;
use App\Models\SubListQuestion;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LecturerAssignmentController extends Controller
{
    /**
     * Lấy ID giảng viên từ người dùng đăng nhập hoặc từ tham số truyền vào
     */
    private function getLecturerId(Request $request)
    {
        // Ưu tiên lấy từ tham số truyền vào
        if ($request->has('lecturer_id')) {
            return $request->lecturer_id;
        }

        // Nếu không có tham số, lấy từ người dùng đăng nhập
        if (Auth::check()) {
            return Auth::id();
        }

        // Nếu không có cả hai, trả về null
        return null;
    }

    /**
     * Lấy danh sách bài tập mà giảng viên đã tạo với bộ lọc
     */
    public function getAssignments(Request $request)
    {
        $lecturerId = $this->getLecturerId($request);

        if (!$lecturerId) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng cung cấp lecturer_id hoặc đăng nhập'
            ], 400);
        }

        $query = Assignment::query()
            ->join('sub_list', 'assignment.sub_list_id', '=', 'sub_list.sub_list_id')
            ->join('sub_list_question', 'sub_list.sub_list_id', '=', 'sub_list_question.sub_list_id')
            ->join('question', 'sub_list_question.question_id', '=', 'question.question_id')
            ->join('list_question', 'question.list_question_id', '=', 'list_question.list_question_id')
            ->join('course', 'list_question.course_id', '=', 'course.course_id')
            ->join('classroom', 'course.course_id', '=', 'classroom.course_id')
            ->where('classroom.lecturer_id', $lecturerId)
            ->select('assignment.*')
            ->distinct();

        // Lọc theo tiêu đề bài tập
        if ($request->has('title')) {
            $query->where('assignment.title', 'like', '%' . $request->title . '%');
        }

        // Lọc theo loại bài tập (Trắc nghiệm, Tự luận)
        if ($request->has('type')) {
            $query->where('assignment.type', $request->type);
        }

        // Lọc theo trạng thái
        if ($request->has('status')) {
            $query->where('assignment.status', $request->status);
        }

        // Lọc theo khoảng thời gian bắt đầu
        if ($request->has('start_from') && $request->has('start_to')) {
            $query->whereBetween('assignment.start_time', [$request->start_from, $request->start_to]);
        } elseif ($request->has('start_from')) {
            $query->where('assignment.start_time', '>=', $request->start_from);
        } elseif ($request->has('start_to')) {
            $query->where('assignment.start_time', '<=', $request->start_to);
        }

        // Lọc theo khoảng thời gian kết thúc
        if ($request->has('end_from') && $request->has('end_to')) {
            $query->whereBetween('assignment.end_time', [$request->end_from, $request->end_to]);
        } elseif ($request->has('end_from')) {
            $query->where('assignment.end_time', '>=', $request->end_from);
        } elseif ($request->has('end_to')) {
            $query->where('assignment.end_time', '<=', $request->end_to);
        }

        // Lọc theo khóa học liên quan
        if ($request->has('course_id')) {
            $query->where('list_question.course_id', $request->course_id);
        }

        // Sắp xếp
        $sortField = $request->sort_field ?? 'created_at';
        $sortDirection = $request->sort_direction ?? 'desc';
        $query->orderBy("assignment.$sortField", $sortDirection);

        $assignments = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $assignments
        ]);
    }

    /**
     * Lấy danh sách bài kiểm tra mà giảng viên đã tạo với bộ lọc
     */
    public function getExams(Request $request)
    {
        $lecturerId = $this->getLecturerId($request);

        if (!$lecturerId) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng cung cấp lecturer_id hoặc đăng nhập'
            ], 400);
        }

        $query = Exam::query()
            ->join('sub_list', 'exam.sub_list_id', '=', 'sub_list.sub_list_id')
            ->join('sub_list_question', 'sub_list.sub_list_id', '=', 'sub_list_question.sub_list_id')
            ->join('question', 'sub_list_question.question_id', '=', 'question.question_id')
            ->join('list_question', 'question.list_question_id', '=', 'list_question.list_question_id')
            ->join('course', 'list_question.course_id', '=', 'course.course_id')
            ->join('classroom', 'course.course_id', '=', 'classroom.course_id')
            ->where('classroom.lecturer_id', $lecturerId)
            ->select('exam.*')
            ->distinct();

        // Lọc theo tiêu đề bài kiểm tra
        if ($request->has('title')) {
            $query->where('exam.title', 'like', '%' . $request->title . '%');
        }

        // Lọc theo loại bài kiểm tra (Trắc nghiệm, Tự luận)
        if ($request->has('type')) {
            $query->where('exam.type', $request->type);
        }

        // Lọc theo trạng thái
        if ($request->has('status')) {
            $query->where('exam.status', $request->status);
        }

        // Lọc theo khoảng thời gian bắt đầu
        if ($request->has('start_from') && $request->has('start_to')) {
            $query->whereBetween('exam.start_time', [$request->start_from, $request->start_to]);
        } elseif ($request->has('start_from')) {
            $query->where('exam.start_time', '>=', $request->start_from);
        } elseif ($request->has('start_to')) {
            $query->where('exam.start_time', '<=', $request->start_to);
        }

        // Lọc theo khoảng thời gian kết thúc
        if ($request->has('end_from') && $request->has('end_to')) {
            $query->whereBetween('exam.end_time', [$request->end_from, $request->end_to]);
        } elseif ($request->has('end_from')) {
            $query->where('exam.end_time', '>=', $request->end_from);
        } elseif ($request->has('end_to')) {
            $query->where('exam.end_time', '<=', $request->end_to);
        }

        // Lọc theo khóa học liên quan
        if ($request->has('course_id')) {
            $query->where('list_question.course_id', $request->course_id);
        }

        // Sắp xếp
        $sortField = $request->sort_field ?? 'created_at';
        $sortDirection = $request->sort_direction ?? 'desc';
        $query->orderBy("exam.$sortField", $sortDirection);

        $exams = $query->paginate($request->per_page ?? 15);

        return response()->json([
            'success' => true,
            'data' => $exams
        ]);
    }

    /**
     * Lấy chi tiết bài tập kèm số lượng sinh viên đã nộp bài
     */
    /**
     * Lấy chi tiết bài tập kèm số lượng sinh viên đã nộp bài
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


    /**
     * Lấy danh sách tất cả bài tập và bài kiểm tra mà giảng viên đã tạo theo từng khóa học
     */
    public function getAllAssignmentsAndExamsByCourse(Request $request)
    {
        $lecturerId = $this->getLecturerId($request);

        if (!$lecturerId) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng cung cấp lecturer_id hoặc đăng nhập'
            ], 400);
        }

        // Lấy danh sách khóa học mà giảng viên phụ trách
        $courses = DB::table('course')
            ->join('classroom', 'course.course_id', '=', 'classroom.course_id')
            ->where('classroom.lecturer_id', $lecturerId)
            ->select('course.course_id', 'course.course_name')
            ->distinct()
            ->get();

        $result = [];

        foreach ($courses as $course) {
            // Lấy bài tập theo khóa học
            $assignments = Assignment::query()
                ->join('sub_list', 'assignment.sub_list_id', '=', 'sub_list.sub_list_id')
                ->join('sub_list_question', 'sub_list.sub_list_id', '=', 'sub_list_question.sub_list_id')
                ->join('question', 'sub_list_question.question_id', '=', 'question.question_id')
                ->join('list_question', 'question.list_question_id', '=', 'list_question.list_question_id')
                ->join('course', 'list_question.course_id', '=', 'course.course_id')
                ->join('classroom', 'course.course_id', '=', 'classroom.course_id')
                ->where('classroom.lecturer_id', $lecturerId)
                ->where('course.course_id', $course->course_id)
                ->select('assignment.*')
                ->distinct()
                ->get();

            // Lấy bài kiểm tra theo khóa học
            $exams = Exam::query()
                ->join('sub_list', 'exam.sub_list_id', '=', 'sub_list.sub_list_id')
                ->join('sub_list_question', 'sub_list.sub_list_id', '=', 'sub_list_question.sub_list_id')
                ->join('question', 'sub_list_question.question_id', '=', 'question.question_id')
                ->join('list_question', 'question.list_question_id', '=', 'list_question.list_question_id')
                ->join('course', 'list_question.course_id', '=', 'course.course_id')
                ->join('classroom', 'course.course_id', '=', 'classroom.course_id')
                ->where('classroom.lecturer_id', $lecturerId)
                ->where('course.course_id', $course->course_id)
                ->select('exam.*')
                ->distinct()
                ->get();

            $result[] = [
                'course_id' => $course->course_id,
                'course_name' => $course->course_name,
                'assignments' => $assignments,
                'exams' => $exams
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Lấy danh sách bài tập/bài kiểm tra theo trạng thái nộp của sinh viên
     */
    public function getSubmissionStats(Request $request)
    {
        $lecturerId = $this->getLecturerId($request);

        if (!$lecturerId) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng cung cấp lecturer_id hoặc đăng nhập'
            ], 400);
        }

        $type = $request->type ?? 'assignment'; // 'assignment' hoặc 'exam'
        $id = $request->id; // ID của assignment hoặc exam

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'ID không được cung cấp'
            ], 400);
        }

        // Kiểm tra quyền truy cập sử dụng query mới
        $isOwner = false;

        if ($type === 'assignment') {
            $isOwner = Assignment::query()
                ->join('sub_list', 'assignment.sub_list_id', '=', 'sub_list.sub_list_id')
                ->join('sub_list_question', 'sub_list.sub_list_id', '=', 'sub_list_question.sub_list_id')
                ->join('question', 'sub_list_question.question_id', '=', 'question.question_id')
                ->join('list_question', 'question.list_question_id', '=', 'list_question.list_question_id')
                ->join('course', 'list_question.course_id', '=', 'course.course_id')
                ->join('classroom', 'course.course_id', '=', 'classroom.course_id')
                ->where('classroom.lecturer_id', $lecturerId)
                ->where('assignment.assignment_id', $id)
                ->exists();
        } else {
            $isOwner = Exam::query()
                ->join('sub_list', 'exam.sub_list_id', '=', 'sub_list.sub_list_id')
                ->join('sub_list_question', 'sub_list.sub_list_id', '=', 'sub_list_question.sub_list_id')
                ->join('question', 'sub_list_question.question_id', '=', 'question.question_id')
                ->join('list_question', 'question.list_question_id', '=', 'list_question.list_question_id')
                ->join('course', 'list_question.course_id', '=', 'course.course_id')
                ->join('classroom', 'course.course_id', '=', 'classroom.course_id')
                ->where('classroom.lecturer_id', $lecturerId)
                ->where('exam.exam_id', $id)
                ->exists();
        }

        if (!$isOwner) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền truy cập'
            ], 403);
        }

        // Lấy danh sách sinh viên và trạng thái nộp bài
        $students = DB::table('student')
            ->join('student_class', 'student.student_id', '=', 'student_class.student_id')
            ->join('classroom', 'student_class.class_id', '=', 'classroom.class_id')
            ->join('course', 'classroom.course_id', '=', 'course.course_id')
            ->join('list_question', 'course.course_id', '=', 'list_question.course_id')
            ->where('classroom.lecturer_id', $lecturerId) // Thay đổi để kiểm tra lecturer từ classroom
            ->select(
                'student.student_id',
                'student.student_code',
                'student.full_name',
                'student.school_email'
            )
            ->distinct()
            ->get();

        $result = [];

        foreach ($students as $student) {
            $submission = null;

            if ($type === 'assignment') {
                $submission = Submission::where('student_id', $student->student_id)
                    ->where('assignment_id', $id)
                    ->first();
            } else {
                $submission = Submission::where('student_id', $student->student_id)
                    ->where('exam_id', $id)
                    ->first();
            }

            $result[] = [
                'student_id' => $student->student_id,
                'student_code' => $student->student_code,
                'full_name' => $student->full_name,
                'school_email' => $student->school_email,
                'submission_status' => $submission ? 'Đã nộp' : 'Chưa nộp',
                'submission_time' => $submission ? $submission->created_at : null,
                'is_late' => $submission ? $submission->is_late : null,
                'temporary_score' => $submission ? $submission->temporary_score : null
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}
