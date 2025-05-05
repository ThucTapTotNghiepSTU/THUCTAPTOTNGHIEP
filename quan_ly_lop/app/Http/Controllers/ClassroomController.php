<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\StringHelper;
use Illuminate\Support\Str;
class ClassroomController extends Controller
{
    /**
     * Lấy danh sách tất cả lớp học
     */
    public function index()
    {
        return response()->json(Classroom::all(), Response::HTTP_OK);
    }

    /**
     * Lấy chi tiết một lớp học
     */
    public function show($id)
    {
        $classroom = Classroom::find($id);
        if (!$classroom) {
            return response()->json(['message' => 'Lớp học không tồn tại!'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($classroom, Response::HTTP_OK);
    }

    public function getStudentClasses($studentId)
    {
        // Lấy thông tin sinh viên từ bảng student
        $student = DB::table('student')->where('student_id', $studentId)->first();

        // Kiểm tra nếu sinh viên tồn tại
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        // Truy vấn các lớp học của sinh viên
        $classes = DB::table('classroom')
            ->join('student_class', 'classroom.class_id', '=', 'student_class.class_id')
            ->join('course', 'classroom.course_id', '=', 'course.course_id')
            ->join('lecturer', 'classroom.lecturer_id', '=', 'lecturer.lecturer_id')
            ->leftJoin('score', function ($join) {
                $join->on('score.student_id', '=', 'student_class.student_id')
                    ->on('score.course_id', '=', 'classroom.course_id');
            })
            ->where('student_class.student_id', $student->student_id) // Sử dụng student_id của đối tượng sinh viên
            ->select(
                'classroom.class_id',
                'classroom.class_code',
                'classroom.class_description',
                'classroom.class_duration',
                'course.course_name',
                'course.course_id',
                'lecturer.lecturer_id',
                'lecturer.fullname as lecturer_name',
                'lecturer.school_email',
                'lecturer.phone',
                'student_class.status',
                'student_class.final_score',
                'score.final_score as course_score'
            )
            ->distinct()
            ->get();

        return response()->json($classes);
    }



    /**
     * Thêm mới một lớp học
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'course_id' => 'required|exists:course,course_id',
            'lecturer_id' => 'required|exists:lecturer,lecturer_id',
            'class_code' => 'required|string|max:50|unique:classroom,class_code',
            'class_description' => 'nullable|string',
            'class_duration' => 'required|integer|min:1',
        ]);

        $classroom = Classroom::create($validatedData);
        return response()->json(['message' => 'Thêm lớp học thành công!', 'data' => $classroom], Response::HTTP_CREATED);
    }

    /**
     * Cập nhật thông tin lớp học
     */
    public function update(Request $request, $id)
    {
        $classroom = Classroom::find($id);
        if (!$classroom) {
            return response()->json(['message' => 'Lớp học không tồn tại!'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'course_id' => 'exists:course,course_id',
            'lecturer_id' => 'exists:lecturer,lecturer_id',
            'class_code' => 'string|max:50|unique:classroom,class_code,' . $id . ',class_id',
            'class_description' => 'nullable|string',
            'class_duration' => 'integer|min:1',
        ]);

        $classroom->update($validatedData);
        return response()->json(['message' => 'Cập nhật lớp học thành công!', 'data' => $classroom], Response::HTTP_OK);
    }

    /**
     * Xóa một lớp học
     */
    public function destroy($id)
    {
        $classroom = Classroom::find($id);
        if (!$classroom) {
            return response()->json(['message' => 'Lớp học không tồn tại!'], Response::HTTP_NOT_FOUND);
        }

        $classroom->delete();
        return response()->json(['message' => 'Xóa lớp học thành công!'], Response::HTTP_OK);
    }

    public function search(Request $request)
    {
        $query = Classroom::query()
            ->with(['course', 'lecturer']);

        // Tìm kiếm theo từ khóa
        if ($request->has('keyword') && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('class_code', 'LIKE', "%{$keyword}%")
                    ->orWhere('class_description', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('course', function ($q) use ($keyword) {
                        $q->where('course_name', 'LIKE', "%{$keyword}%");
                    })
                    ->orWhereHas('lecturer', function ($q) use ($keyword) {
                        $q->where('fullname', 'LIKE', "%{$keyword}%");
                    });
            });
        }

        // Lọc theo trạng thái
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Sắp xếp mặc định theo ngày tạo mới nhất
        $query->orderBy('created_at', 'desc');

        $classes = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $classes
        ]);
    }
    public function getAllLecturerTasksOfCourse($lecturerId, $courseId)
    {
        // 1. Kiểm tra xem giảng viên có phụ trách lớp học của môn học này không
        $isLecturer = DB::table('classroom')
            ->where('lecturer_id', $lecturerId)
            ->where('course_id', $courseId)
            ->select('classroom.class_id')
            ->first();

        if (!$isLecturer) {
            return response()->json([
                'message' => 'Giảng viên không phụ trách môn học này.'
            ], 403);
        }

        // 2. Lấy list_question_id của môn học do giảng viên phụ trách
        $listQuestionIds = DB::table('list_question')
            ->where('course_id', $courseId)
            ->where('lecturer_id', $lecturerId)
            ->pluck('list_question_id');

        if ($listQuestionIds->isEmpty()) {
            return response()->json([
                'message' => 'Không có danh sách câu hỏi nào cho môn học này.'
            ], 404);
        }

        // 3. Lấy question_id từ list_question
        $questionIds = DB::table('question')
            ->whereIn('list_question_id', $listQuestionIds)
            ->pluck('question_id');

        // 4. Lấy sub_list_id từ bảng sub_list_question
        $subListIds = DB::table('sub_list_question')
            ->whereIn('question_id', $questionIds)
            ->pluck('sub_list_id');

        // 5. Lấy bài kiểm tra (exam)
        $exams = DB::table('exam')
            ->join('sub_list', 'exam.sub_list_id', '=', 'sub_list.sub_list_id')
            ->join('sub_list_question', 'sub_list.sub_list_id', '=', 'sub_list_question.sub_list_id')
            ->join('question', 'sub_list_question.question_id', '=', 'question.question_id')
            ->join('list_question', 'question.list_question_id', '=', 'list_question.list_question_id')
            ->join('course', 'list_question.course_id', '=', 'course.course_id')
            ->whereIn('exam.sub_list_id', $subListIds)
            ->where('list_question.lecturer_id', $lecturerId)
            ->select(
                'exam.exam_id',
                'exam.sub_list_id as exam_sub_list_id',
                'exam.title',
                'exam.content',
                'exam.type',
                'exam.isSimultaneous',
                'exam.start_time',
                'exam.end_time',
                'exam.status',
                'course.course_name as course_name',
                DB::raw('NULL as temporary_score') // Giảng viên không cần temporary_score
            )
            ->groupBy(
                'exam.exam_id',
                'exam.sub_list_id',
                'exam.title',
                'exam.content',
                'exam.type',
                'exam.isSimultaneous',
                'exam.start_time',
                'exam.end_time',
                'exam.status',
                'course.course_name'
            )
            ->get();

        // 6. Lấy bài tập (assignment)
        $assignments = DB::table('assignment')
            ->join('sub_list', 'assignment.sub_list_id', '=', 'sub_list.sub_list_id')
            ->join('sub_list_question', 'sub_list.sub_list_id', '=', 'sub_list_question.sub_list_id')
            ->join('question', 'sub_list_question.question_id', '=', 'question.question_id')
            ->join('list_question', 'question.list_question_id', '=', 'list_question.list_question_id')
            ->join('course', 'list_question.course_id', '=', 'course.course_id')
            ->whereIn('assignment.sub_list_id', $subListIds)
            ->where('list_question.lecturer_id', $lecturerId)
            ->select(
                'assignment.assignment_id',
                'assignment.sub_list_id as assignment_sub_list_id',
                'assignment.title',
                'assignment.content',
                'assignment.type',
                'assignment.isSimultaneous',
                'assignment.start_time',
                'assignment.end_time',
                'assignment.status',
                'course.course_name',
                DB::raw('NULL as temporary_score') // Giảng viên không cần temporary_score
            )
            ->groupBy(
                'assignment.assignment_id',
                'assignment.sub_list_id',
                'assignment.title',
                'assignment.content',
                'assignment.type',
                'assignment.isSimultaneous',
                'assignment.start_time',
                'assignment.end_time',
                'assignment.status',
                'course.course_name'
            )
            ->get();

        return response()->json([
            'exams' => $exams,
            'assignments' => $assignments
        ], 200);
    }
}
