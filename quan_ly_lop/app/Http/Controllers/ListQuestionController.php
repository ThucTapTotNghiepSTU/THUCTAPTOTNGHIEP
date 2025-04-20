<?php

namespace App\Http\Controllers;

use App\Models\ListQuestion;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
class ListQuestionController extends Controller
{
    // Lấy danh sách câu hỏi của một môn học cụ thể
    public function index(Request $request)
    {
        $courses = Course::all();
        $listQuestions = ListQuestion::all();
        return view('lecturerViews.question_bank', compact('courses', 'listQuestions'));
    }
    public function getAllListQuestionsWithLecturer($lecturer_id)
    {
        try {
            $courseId = request()->query('course_id', 'null');
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
            $listQuestions = $query->orderByDesc('created_at')->get();
            return response()->json($listQuestions);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Đã xảy ra lỗi khi lấy danh sách bộ câu hỏi.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // Lấy thông tin chi tiết một danh sách câu hỏi
    public function show($id)
    {
        $listQuestion = ListQuestion::with(['course', 'questions.options'])->find($id);

        if (!$listQuestion) {
            return response()->json(['message' => 'Không tìm thấy danh sách câu hỏi!'], Response::HTTP_NOT_FOUND);
        }

        return view('modules.mod_lecturer.mod_createQuestionBank', compact('listQuestion'));
    }
    public function showDetailQuestion($list_question_id)
    {
        try {
            if (empty($list_question_id)) {
                return response()->json([
                    'message' => 'Thiếu list_question_id trong request!'
                ], 400);
            }

            $listQuestions = ListQuestion::with(['course', 'lecturer', 'questions.options'])
                ->where('list_question_id', $list_question_id)
                ->first();

            if (!$listQuestions) {
                return response()->json(['message' => 'Không tìm thấy danh sách câu hỏi!'], 404);
            }

            $formattedQuestions = $listQuestions->questions->map(function ($question) {
                return [
                    'question_id' => $question->question_id,
                    'title' => $question->title,
                    'content' => $question->content,
                    'type' => $question->type,
                    'correct_answer' => $question->correct_answer,
                    'options' => $question->options->pluck('option_text')->toArray(),
                ];
            });

            return response()->json([
                'data' => [
                    'course_id' => $listQuestions->course_id,
                    'course_name' => $listQuestions->course->course_name,
                    'questions' => $formattedQuestions
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'details' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            ], 500);
        }
    }

    public function storeBatch(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'list_question_id' => 'required|string|exists:list_questions,list_question_id',
            'questions' => 'required|array',
            'questions.*.title' => 'required|string',
            'questions.*.content' => 'required|string',
            'questions.*.type' => 'required|string|in:multiple_choice,short_answer', // Update to English values
            'questions.*.options' => 'required_if:questions.*.type,multiple_choice|array', // Update to match new type
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
                // Tạo câu hỏi
                $question = Question::create([
                    'list_question_id' => $request->list_question_id,
                    'title' => $questionData['title'],
                    'content' => $questionData['content'],
                    'type' => $questionData['type'],
                ]);

                // Xử lý các lựa chọn nếu câu hỏi là trắc nghiệm
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

        $courseId = $validated['course_id'];
        $topic = $validated['topic'];
        $lecturerId = $validated['lecturer_id'];

        $existingTopic = ListQuestion::where('topic', $topic)->first();
        $newTopicCreated = false;

        if (!$existingTopic) {
            $newTopicRecord = new ListQuestion();
            $newTopicRecord->topic = $topic;
            $newTopicRecord->course_id = null;
            $newTopicRecord->lecturer_id = null;
            $newTopicRecord->save();
            $newTopicCreated = true;
        }

        $listQuestion = new ListQuestion();
        $listQuestion->course_id = $courseId;
        $listQuestion->topic = $topic;
        $listQuestion->lecturer_id = $lecturerId;
        $listQuestion->save();

        return response()->json([
            'success' => true,
            'message' => 'List question created successfully',
            'list_question_id' => $listQuestion->list_question_id,
            'new_topic_created' => $newTopicCreated
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
    // Cập nhật danh sách câu hỏi
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

    // Xóa danh sách câu hỏi
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
