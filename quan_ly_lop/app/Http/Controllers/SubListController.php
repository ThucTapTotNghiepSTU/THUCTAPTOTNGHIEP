<?php

namespace App\Http\Controllers;

use App\Models\SubList;
use App\Models\ListQuestion;
use App\Models\SubListQuestion;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubListController extends Controller
{
    // Lấy danh sách tất cả SubList
    public function index()
    {
        return response()->json(SubList::all());
    }
    // Lấy thông tin chi tiết một SubList
    public function show($id)
    {
        $subList = SubList::find($id);
        if (!$subList) {
            return response()->json(['message' => 'Không tìm thấy SubList!'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($subList);
    }
    public function getAvailableQuestions($listQuestionId)
    {
        $listQuestion = ListQuestion::find($listQuestionId);
        if (!$listQuestion) {
            return response()->json(['message' => 'Không tìm thấy bộ câu hỏi'], Response::HTTP_NOT_FOUND);
        }
        $counts = [
            'all' => Question::where('list_question_id', $listQuestionId)->count(),
            'multiple_choice' => Question::where('list_question_id', $listQuestionId)->where('type', 'Trắc nghiệm')->count(),
            'short_answer' => Question::where('list_question_id', $listQuestionId)->where('type', 'Tự luận')->count(),
        ];

        return response()->json($counts);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string',
            'isShuffle' => 'required|boolean',
            'list_question_id' => 'required|exists:list_question,list_question_id',
            'number_of_questions' => 'required|integer|min:1',
            'question_type' => 'nullable|in:Trắc nghiệm,Tự luận', // Sửa ở đây
        ]);
        // B1: Lọc câu hỏi phù hợp
        $query = Question::where('list_question_id', $validatedData['list_question_id']);
        if (!empty($validatedData['question_type'])) {
            $query->where('type', $validatedData['question_type']);
        }
        $availableQuestions = $query->get();
        // B2: Kiểm tra đủ số lượng chưa
        if ($availableQuestions->count() < $validatedData['number_of_questions']) {
            return response()->json([
                'message' => 'Không đủ số lượng câu hỏi để tạo mã đề!',
                'available' => $availableQuestions->count(),
                'required' => $validatedData['number_of_questions'],
            ], Response::HTTP_BAD_REQUEST);
        }
        // B3: Random & chọn đúng số lượng
        $selectedQuestions = $availableQuestions->shuffle()->take($validatedData['number_of_questions']);
        // B4: Tạo SubList
        $subList = SubList::create([
            'title' => $validatedData['title'],
            'isShuffle' => $validatedData['isShuffle'],
            'list_question_id' => $validatedData['list_question_id'],
        ]);
        // B5: Gán câu hỏi vào SubListQuestion
        foreach ($selectedQuestions as $question) {
            SubListQuestion::create([
                'sub_list_id' => $subList->sub_list_id,
                'question_id' => $question->question_id,
            ]);
        }
        return response()->json([
            'message' => 'Tạo mã đề thành công!',
            'sub_list' => $subList,
            'total_questions' => $selectedQuestions->count(),
            'questions' => $selectedQuestions,
        ], Response::HTTP_CREATED);
    }
    public function getAllSublist($listQuestionId)
    {
        $listQuestion = ListQuestion::find($listQuestionId);
        if (!$listQuestion) {
            return response()->json(['message' => "Không tìm thấy bộ câu hỏi"], Response::HTTP_NOT_FOUND);
        }
        $subLists = SubList::whereHas('questions', function ($query) use ($listQuestionId) {
            $query->where('list_question_id', $listQuestionId);
        })->has('questions')->with('questions')->get();
        if ($subLists->isEmpty()) {
            return response()->json(['message' => "Không có mã đề nào cho bộ câu hỏi này"], Response::HTTP_NOT_FOUND);
        }
        $subLists = $subLists->map(function ($subList) {
            return [
                'sub_list_id' => $subList->sub_list_id,
                'title' => $subList->title,
                'is_shuffle' => $subList->isShuffle,
                'list_question_id' => $subList->list_question_id,
                'created_at' => $subList->created_at,
                'questions' => $subList->questions,
            ];
        });
        return response()->json([
            'sub_list' => $subLists,
        ]);
    }
    public function getAll($sublistsId)
    {
        $sublist = Sublist::find($sublistsId);
        if (!$sublist) {
            return response()->json(['message' => 'Mã đề không tồn tại'], 404);
        }
        $questions = SubListQuestion::where('sub_list_id', $sublistsId)
            ->with('question')
            ->get()
            ->pluck('question');
        return response()->json($questions);
    }

    // Cập nhật thông tin SubList
    public function update(Request $request, $id)
    {
        $subList = SubList::find($id);
        if (!$subList) {
            return response()->json(['message' => 'Không tìm thấy SubList!'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'title' => 'required|string',
            'isShuffle' => 'required|boolean',
        ]);

        $subList->update($validatedData);

        return response()->json($subList);
    }

    // Xóa một SubList
    public function destroy($id)
    {
        $subList = SubList::find($id);
        if (!$subList) {
            return response()->json(['message' => 'Không tìm thấy SubList!'], Response::HTTP_NOT_FOUND);
        }

        $subList->delete();

        return response()->json(['message' => 'SubList đã được xóa thành công!'], Response::HTTP_OK);
    }
}
