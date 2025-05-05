<?php

namespace App\Http\Controllers;

use App\Models\ListQuestion;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Question;
use Illuminate\Support\Facades\Validator;

class ListQuestionController extends Controller
{
    // Lấy danh sách câu hỏi của một môn học cụ thể
    public function index(Request $request)
    {
        $courses = Course::all();
        $listQuestions = ListQuestion::all();
        return view('lecturerViews.question_bank', compact('courses', 'listQuestions'));
    }

    public function getAllListQuestionsWithLecturer($lecturer_id, Request $request)
    {
        try {
            $courseId = $request->query('course_id', 'null');
            $topic = $request->query('topic', 'null');

            $query = ListQuestion::with([
                'lecturer' => function ($query) {
                    $query->select('lecturer_id', 'fullname');
                },
                'course' => function ($query) {
                    $query->select('course_id', 'course_name');
                }
            ])->where('lecturer_id', $lecturer_id);

            if ($courseId !== 'null' && $courseId !== 'all') {
                $query->where('course_id', $courseId);
            }

            if ($topic !== 'null' && $topic !== 'all') {
                $decodedTopic = urldecode($topic);
                $query->where('topic', $decodedTopic);
            }

            $listQuestions = $query->orderByDesc('created_at')->get();
            return response()->json($listQuestions);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Đã xảy ra lỗi khi lấy danh sách bộ câu hỏi.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function getQuestionsByCourseAndTopic(Request $request)
    {
        $courseId = $request->query('course_id');
        $topic = $request->query('topic');
        $questionType = $request->query('type');
        $lecturerId = $request->query('lecturer_id'); // Optional, for authorization

        // Validate input
        if (!$courseId || !$topic || !$questionType) {
            return response()->json(['error' => 'Missing required parameters'], 400);
        }

        // Query questions
        $questions = Question::whereHas('listQuestion', function ($query) use ($courseId, $topic, $lecturerId) {
            $query->where('course_id', $courseId)
                ->where('topic', $topic);
            if ($lecturerId) {
                $query->where('lecturer_id', $lecturerId);
            }
        })
            ->where('type', $questionType)
            ->with([
                'listQuestion' => function ($query) {
                    $query->select('list_question_id', 'course_id', 'topic');
                }
            ])
            ->get(['question_id', 'list_question_id', 'title', 'content', 'type']);

        return response()->json([
            'success' => true,
            'questions' => $questions
        ]);
    }
    public function show($id)
    {
        $listQuestion = ListQuestion::with(['course', 'questions.options'])->find($id);

        if (!$listQuestion) {
            return response()->json(['message' => 'Không tìm thấy danh sách câu hỏi!'], Response::HTTP_NOT_FOUND);
        }

        return view('modules.mod_lecturer.mod_createQuestionBank', compact('listQuestion'));
    }

    public function showDetailQuestion($id)
    {
        $listQuestions = ListQuestion::with(['questions.options'])->find($id);
        if (!$listQuestions) {
            return response()->json(['message' => 'Không tìm thấy danh sách câu hỏi!'], 404);
        }

        $course = $listQuestions->course;
        $formattedQuestions = $listQuestions->questions->map(function ($question) {
            return [
                'question_id' => $question->question_id,
                'title' => $question->title,
                'content' => $question->content,
                'type' => $question->type,
                'correct_answer' => $question->correct_answer,
                'options' => $question->options->map(function ($option) {
                    return [
                        'option_text' => $option->option_text,
                        'is_correct' => $option->is_correct,
                    ];
                })->toArray(),
            ];
        });

        return response()->json([
            'data' => [
                'list_question_id' => $listQuestions->list_question_id,
                'course_id' => $course->course_id,
                'course_name' => $course->course_name,
                'topic' => $listQuestions->topic,
                'questions' => $formattedQuestions,
            ],
        ]);
    }

    public function storeBatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'list_question_id' => 'required|string|exists:list_questions,list_question_id',
            'questions' => 'required|array',
            'questions.*.title' => 'required|string',
            'questions.*.content' => 'required|string',
            'questions.*.type' => 'required|string|in:multiple_choice,short_answer',
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array',
            'questions.*.options.*.option_text' => 'required|string',
            'questions.*.options.*.is_correct' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();
        try {
            $createdQuestions = [];
            foreach ($request->questions as $questionData) {
                $question = Question::create([
                    'list_question_id' => $request->list_question_id,
                    'title' => $questionData['title'],
                    'content' => $questionData['content'],
                    'type' => $questionData['type'],
                ]);

                if ($questionData['type'] === 'multiple_choice' && isset($questionData['options'])) {
                    foreach ($questionData['options'] as $index => $option) {
                        $newOption = $question->options()->create([
                            'option_text' => $option['option_text'],
                            'is_correct' => $option['is_correct'],
                            'option_order' => $index
                        ]);

                        if ($option['is_correct']) {
                            $question->correct_answer = $option['option_text'];
                            $question->save();
                        }
                    }
                }

                $createdQuestions[] = $question;
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Đã lưu thành công ' . count($createdQuestions) . ' câu hỏi',
                'data' => $createdQuestions
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lưu câu hỏi',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:course,course_id',
            'topic' => 'required|string|max:255',
            'lecturer_id' => 'required|exists:lecturer,lecturer_id'
        ]);

        $listQuestion = new ListQuestion();
        $listQuestion->course_id = $validated['course_id'];
        $listQuestion->topic = $validated['topic'];
        $listQuestion->lecturer_id = $validated['lecturer_id'];
        $listQuestion->save();

        return response()->json([
            'success' => true,
            'message' => 'List question created successfully',
            'list_question_id' => $listQuestion->list_question_id
        ], 201);
    }

    public function getTopicsByCourse($course_id)
    {
        try {
            $topics = ListQuestion::where('course_id', $course_id)
                ->distinct()
                ->pluck('topic')
                ->filter()
                ->values();

            return response()->json([
                'topics' => $topics
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Đã xảy ra lỗi khi lấy danh sách chủ đề.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $listQuestion = ListQuestion::find($id);
        if (!$listQuestion) {
            return response()->json(['message' => 'Không tìm thấy danh sách câu hỏi!'], Response::HTTP_NOT_FOUND);
        }

        $validatedData = $request->validate([
            'course_id' => 'required|string|exists:course,course_id',
        ]);

        $listQuestion->update($validatedData);
        return response()->json(['message' => 'Cập nhật danh sách câu hỏi thành công!', 'data' => $listQuestion], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $listQuestion = ListQuestion::find($id);
        if (!$listQuestion) {
            return response()->json(['message' => 'Không tìm thấy danh sách câu hỏi!'], Response::HTTP_NOT_FOUND);
        }

        $listQuestion->delete();
        return response()->json(['message' => 'Danh sách câu hỏi đã được xóa thành công!'], Response::HTTP_OK);
    }
}
