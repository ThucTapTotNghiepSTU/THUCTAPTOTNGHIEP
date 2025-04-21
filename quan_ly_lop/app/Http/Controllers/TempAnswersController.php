<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempAnswers;

class TempAnswersController extends Controller
{
    public function saveTemp(Request $request)
    {
        // Validate dữ liệu gửi lên
        $data = $request->validate([
            'student_id' => 'required|string',
            'exam_id' => 'nullable|string',
            'assignment_id' => 'nullable|string',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|string',
            'answers.*.answer' => 'nullable|string',
        ]);

        // Lặp qua từng câu trả lời để cập nhật hoặc tạo mới
        foreach ($data['answers'] as $answerData) {
            // Tạo điều kiện tìm kiếm với student_id, exam_id hoặc assignment_id
            $conditions = [
                'student_id' => $data['student_id'],
                'question_id' => $answerData['question_id'],
            ];

            // Nếu có exam_id, thêm vào điều kiện tìm kiếm
            if ($data['exam_id']) {
                $conditions['exam_id'] = $data['exam_id'];
            }

            // Nếu không có exam_id nhưng có assignment_id, thêm vào điều kiện tìm kiếm
            elseif ($data['assignment_id']) {
                $conditions['assignment_id'] = $data['assignment_id'];
            }
            else {
                return response()->json(['error' => 'exam_id hoặc assignment_id là bắt buộc.'], 400);
            }

            // Tìm hoặc tạo mới đáp án
            TempAnswers::updateOrCreate(
                $conditions, // Điều kiện tìm kiếm linh động
                ['answer' => $answerData['answer']] // Cập nhật hoặc tạo mới đáp án
            );
        }

        return response()->json(['success' => true, 'message' => 'Đáp án tạm đã được lưu thành công.']);
    }


    public function getTempAnswers($id, $studentId)
    {
        $query = TempAnswers::where('student_id', $studentId);

        // Kiểm tra trước: tồn tại bản ghi với exam_id?
        $hasExam = TempAnswers::where('student_id', $studentId)
            ->where('exam_id', $id)
            ->exists();

        if ($hasExam) {
            $query->where('exam_id', $id);
        } else {
            // Nếu không có trong exam_id, thử với assignment_id
            $hasAssignment = TempAnswers::where('student_id', $studentId)
                ->where('assignment_id', $id)
                ->exists();

            if ($hasAssignment) {
                $query->where('assignment_id', $id);
            } else {
                return response()->json(['error' => 'Không tìm thấy dữ liệu với ID này'], 404);
            }
        }

        $answers = $query->get(['question_id', 'answer']);

        return response()->json($answers);
    }

    public function deleteTempAnswers(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|string',
            'id' => 'required|string',

        ]);

        $query = TempAnswers::where('student_id', $data['student_id']);

        if (!empty($data['id'])) {
            $query->where('exam_id', $data['id']);
        } elseif (!empty($data['id'])) {
            $query->where('assignment_id', $data['id']);
        } else {
            return response()->json(['message' => 'Cần có exam_id hoặc assignment_id'], 422);
        }

        $deletedCount = $query->delete();

        return response()->json(['message' => 'Đã xoá ' . $deletedCount . ' đáp án tạm.']);
    }
}
