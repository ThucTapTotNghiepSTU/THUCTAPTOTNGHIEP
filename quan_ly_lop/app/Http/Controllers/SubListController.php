<?php

namespace App\Http\Controllers;

use App\Models\SubList;
use App\Models\ListQuestion;
use App\Models\SubListQuestion;
use App\Models\Question;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubListController extends Controller
{
    // Lấy danh sách tất cả SubList
    public function index()
    {
        $courses = Course::all();
        return view('lecturerViews.mod_createQuestionBank', compact('courses'));
    }
    // Lấy thông tin chi tiết một SubList
    public function show($sub_list_id)
    {
        $sublist = SubList::with([
            'questions.listQuestion.course',
            'questions.listQuestion.lecturer',
            'questions.options'
        ])->findOrFail($sub_list_id);

        return view('lecturerViews.chi_tiet_ma_de', compact('sublist'));
    }

    public function getAllByLecturer($lecturerId, Request $request)
    {
        try {
            $courseId = $request->query('course_id');

            $query = SubList::whereHas('questions.listQuestion', function ($q) use ($lecturerId) {
                $q->where('lecturer_id', $lecturerId);
            });

            if ($courseId) {
                $query->whereHas('questions.listQuestion', function ($q) use ($courseId) {
                    $q->where('course_id', $courseId);
                });
            }

            $subLists = $query->with(['questions.listQuestion.course'])->get();

            if ($subLists->isEmpty()) {
                return response()->json(['sub_list' => [], 'message' => 'Chưa có mã đề nào cho giảng viên này'], 200);
            }

            return response()->json(['sub_list' => $subLists], 200);
        } catch (\Exception $e) {
            \Log::error('Lỗi khi lấy SubList: ' . $e->getMessage());
            return response()->json(['message' => 'Lỗi server khi lấy mã đề', 'error' => $e->getMessage()], 500);
        }
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
            /* 'list_question_id' => 'required|exists:list_question,list_question_id', */
            'question_ids' => 'required|array', // Danh sách question_id
            'question_ids.*' => 'exists:question,question_id', // Kiểm tra từng ID
        ]);

        // Tạo SubList
        $subList = SubList::create([
            'title' => $validatedData['title'],
            'isShuffle' => $validatedData['isShuffle'],
        ]);

        // Gán câu hỏi vào SubListQuestion
        foreach ($validatedData['question_ids'] as $questionId) {
            SubListQuestion::create([
                'sub_list_id' => $subList->sub_list_id,
                'question_id' => $questionId,
            ]);
        }

        return response()->json([
            'message' => 'Tạo mã đề thành công!',
            'sub_list' => $subList,
            'total_questions' => count($validatedData['question_ids']),
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
