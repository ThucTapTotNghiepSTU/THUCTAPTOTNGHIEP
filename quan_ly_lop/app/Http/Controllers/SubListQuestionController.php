<?php

namespace App\Http\Controllers;

use App\Models\SubListQuestion;
use App\Models\SubList;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubListQuestionController extends Controller
{
    // Lấy tất cả SubListQuestion
    public function index()
    {
        return response()->json(SubListQuestion::all());
    }
    public function getAll($sublistsId)
    {
        $sublist = Sublist::find($sublistsId);
        if (!$sublist) {
            return response()->json(['message' => 'Mã đề không tồn tại'], 404);
        }
        $questions = $sublist->questions;
        return response()->json($questions);
    }

    // Lấy thông tin chi tiết một SubListQuestion
    public function show($id)
    {
        $subListQuestion = SubListQuestion::find($id);
        if (!$subListQuestion) {
            return response()->json(['message' => 'Không tìm thấy bản ghi!'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($subListQuestion);
    }

    // Thêm câu hỏi vào SubList
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'sub_list_id' => 'required|exists:sub_list,sub_list_id',
            'question_id' => 'required|exists:question,question_id',
        ]);

        $subListQuestion = SubListQuestion::create($validatedData);

        return response()->json($subListQuestion, Response::HTTP_CREATED);
    }

    // Cập nhật SubListQuestion
    public function update(Request $request, $id)
    {
        $subListQuestion = SubListQuestion::find($id);
        if (!$subListQuestion) {
            return response()->json(['message' => 'Không tìm thấy bản ghi!'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'sub_list_id' => 'required|exists:sub_list,sub_list_id',
            'question_id' => 'required|exists:question,question_id',
        ]);

        $subListQuestion->update($validatedData);

        return response()->json($subListQuestion);
    }

    // Xóa câu hỏi khỏi SubList
    public function destroy($id)
    {
        $subListQuestion = SubListQuestion::find($id);
        if (!$subListQuestion) {
            return response()->json(['message' => 'Không tìm thấy bản ghi!'], Response::HTTP_NOT_FOUND);
        }

        $subListQuestion->delete();

        return response()->json(['message' => 'Bản ghi đã được xóa thành công!'], Response::HTTP_OK);
    }
}
