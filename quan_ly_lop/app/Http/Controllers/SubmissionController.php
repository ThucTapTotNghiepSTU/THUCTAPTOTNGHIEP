<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Assignment;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    // Lấy tất cả bài nộp
    public function index(Request $request)
    {
        $type = $request->query('type', 'assignment');
        $id = $request->query('id');

        if ($type === 'assignment') {
            $item = Assignment::findOrFail($id);
            $submissions = Submission::where('assignment_id', $id)
                ->with('student')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $item = Exam::findOrFail($id);
            $submissions = Submission::where('exam_id', $id)
                ->with('student')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('submissions.index', compact('submissions', 'item', 'type'));
    }

    // Lấy thông tin một bài nộp
    public function show()
    {
        // Không cần truyền dữ liệu vào view vì dữ liệu sẽ được lấy từ API bằng JavaScript
        return view('submissions.show');
    }

    // Nộp bài
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:student,student_id',
            'exam_id' => 'nullable|exists:exam,exam_id',
            'assignment_id' => 'nullable|exists:assignment,assignment_id',
            'answer_file' => 'required|file|mimes:pdf,doc,docx,zip|max:20480', // Tối đa 20MB
        ]);

        // Kiểm tra logic exam_id và assignment_id
        if (!empty($validatedData['exam_id']) && !empty($validatedData['assignment_id'])) {
            return response()->json([
                'message' => 'Bài nộp chỉ có thể thuộc về Exam hoặc Assignment, không thể cả hai.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if (empty($validatedData['exam_id']) && empty($validatedData['assignment_id'])) {
            return response()->json([
                'message' => 'Bài nộp phải thuộc về một Exam hoặc Assignment.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Lưu file lên storage
        $filePath = $request->file('answer_file')->store('submissions');

        $submission = Submission::create([
            'student_id' => $validatedData['student_id'],
            'exam_id' => $validatedData['exam_id'] ?? null,
            'assignment_id' => $validatedData['assignment_id'] ?? null,
            'answer_file' => $filePath,
            'is_late' => false, // Có thể cập nhật dựa vào thời hạn nộp
            'temporary_score' => null,
        ]);

        return response()->json($submission, Response::HTTP_CREATED);
    }

    // Cập nhật bài nộp
    public function update(Request $request, $id)
    {
        $submission = Submission::find($id);
        if (!$submission) {
            return response()->json(['message' => 'Không tìm thấy bài nộp!'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'temporary_score' => 'nullable|numeric|min:0|max:100',
        ]);

        $submission->update($validatedData);

        return response()->json($submission);
    }

    // Xóa bài nộp
    public function destroy($id)
    {
        $submission = Submission::find($id);
        if (!$submission) {
            return response()->json(['message' => 'Không tìm thấy bài nộp!'], Response::HTTP_NOT_FOUND);
        }

        // Xóa file khỏi storage nếu tồn tại
        if ($submission->answer_file) {
            Storage::delete($submission->answer_file);
        }

        $submission->delete();

        return response()->json(['message' => 'Bài nộp đã được xóa!'], Response::HTTP_OK);
    }
}
