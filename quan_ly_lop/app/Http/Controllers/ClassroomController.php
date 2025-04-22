<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\StringHelper;

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
            ->leftJoin('score', function($join) {
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
            'course_id'         => 'required|exists:course,course_id',
            'lecturer_id'       => 'required|exists:lecturer,lecturer_id',
            'class_code'        => 'required|string|max:50|unique:classroom,class_code',
            'class_description' => 'nullable|string',
            'class_duration'    => 'required|integer|min:1',
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
            'course_id'         => 'exists:course,course_id',
            'lecturer_id'       => 'exists:lecturer,lecturer_id',
            'class_code'        => 'string|max:50|unique:classroom,class_code,' . $id . ',class_id',
            'class_description' => 'nullable|string',
            'class_duration'    => 'integer|min:1',
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
            $query->where(function($q) use ($keyword) {
                $q->where('class_code', 'LIKE', "%{$keyword}%")
                  ->orWhere('class_description', 'LIKE', "%{$keyword}%")
                  ->orWhereHas('course', function($q) use ($keyword) {
                      $q->where('course_name', 'LIKE', "%{$keyword}%");
                  })
                  ->orWhereHas('lecturer', function($q) use ($keyword) {
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
}
