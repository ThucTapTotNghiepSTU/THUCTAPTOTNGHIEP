<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Options;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\ListQuestionController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $questions = Question::paginate(10);

        return response()->json([
            'success' => true,
            'data' => $questions
        ]);
    }


    // Lấy thông tin chi tiết một câu hỏi
    public function show($id)
    {
        $question = Question::with('options')->find($id);
        if (!$question) {
            return response()->json(['message' => 'Không tìm thấy câu hỏi!'], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'question_id' => $question->question_id,
            'list_question_id' => $question->list_question_id,
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
        ]);
    }


    // Tạo mới một câu hỏi
    public function store(Request $request)
    {
        try {
            // Ghi log dữ liệu đầu vào để debug
            \Log::debug('Dữ liệu gửi đến /api/questions/create: ', $request->all());

            $validator = Validator::make($request->all(), [
                'list_question_id' => 'required|exists:list_question,list_question_id',
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'type' => 'required|in:Trắc nghiệm,Tự luận',
                'correct_answer' => 'required_if:type,Trắc nghiệm|in:A,B,C,D',
                'options' => 'required_if:type,Trắc nghiệm|array|min:2',
                'options.*.text' => 'required_if:type,Trắc nghiệm|string|max:255',
            ]);

            if ($validator->fails()) {
                \Log::warning('Validation failed: ', $validator->errors()->toArray());
                return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
            }

            // Tạo câu hỏi
            $question = Question::create([
                'list_question_id' => $request->list_question_id,
                'title' => $request->title,
                'content' => $request->content,
                'type' => $request->type,
                'correct_answer' => $request->type === 'Trắc nghiệm' ? $request->correct_answer : null,
            ]);

            // Tạo các đáp án cho câu hỏi trắc nghiệm
            if ($request->type === 'Trắc nghiệm') {
                if (!is_array($request->options)) {
                    \Log::error('Options is not an array: ', [$request->options]);
                    return response()->json(['error' => 'Dữ liệu options không hợp lệ'], Response::HTTP_BAD_REQUEST);
                }

                foreach ($request->options as $index => $option) {
                    if (!isset($option['text']) || empty(trim($option['text']))) {
                        \Log::warning('Skipping empty option at index: ' . $index);
                        continue;
                    }

                    Options::create([
                        'question_id' => $question->question_id,
                        'option_text' => $option['text'],
                        'is_correct' => $request->correct_answer === ['A', 'B', 'C', 'D'][$index],
                    ]);
                }

                // Kiểm tra xem có đáp án nào được tạo không
                if (Options::where('question_id', $question->question_id)->count() < 2) {
                    \Log::error('Not enough valid options created for question: ' . $question->question_id);
                    $question->delete(); // Xóa câu hỏi nếu không đủ đáp án
                    return response()->json(['error' => 'Cần ít nhất 2 đáp án hợp lệ cho câu hỏi trắc nghiệm'], Response::HTTP_BAD_REQUEST);
                }
            }

            return response()->json(['message' => 'Tạo câu hỏi thành công!', 'question' => $question], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            \Log::error('Lỗi khi tạo câu hỏi: ' . $e->getMessage(), [
                'request' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Có lỗi xảy ra khi tạo câu hỏi: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function storeBatch(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'list_question_id' => 'required|string|exists:list_question,list_question_id',
            'questions' => 'required|array',
            'questions.*.title' => 'required|string',
            'questions.*.content' => 'required|string',
            'questions.*.type' => 'required|string|in:Trắc nghiệm,Tự luận',
            'questions.*.options' => 'required_if:questions.*.type,Trắc nghiệm|array',
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
                if ($questionData['type'] === 'Trắc nghiệm' && isset($questionData['options'])) {
                    foreach ($questionData['options'] as $index => $option) {
                        // Tạo option mới
                        $newOption = $question->options()->create([
                            'option_text' => $option['option_text'],
                            'is_correct' => $option['is_correct'],
                            'option_order' => $index
                        ]);

                        // Nếu là đáp án đúng, cập nhật correct_answer của câu hỏi
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
    // Cập nhật thông tin câu hỏi
    public function update(Request $request, $id)
    {
        $question = Question::find($id);
        if (!$question) {
            return response()->json(['message' => 'Không tìm thấy câu hỏi!'], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'list_question_id' => 'required|exists:list_questions,list_question_id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:Trắc nghiệm,Tự luận',
            'correct_answer' => 'required_if:type,Trắc nghiệm|in:A,B,C,D',
            'options' => 'required_if:type,Trắc nghiệm|array|min:2',
            'options.*.text' => 'required_if:type,Trắc nghiệm|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $question->update([
            'list_question_id' => $request->list_question_id,
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'correct_answer' => $request->type === 'Trắc nghiệm' ? $request->correct_answer : null,
        ]);

        if ($request->type === 'Trắc nghiệm') {
            // Xóa các options cũ
            $question->options()->delete();
            // Tạo options mới
            $options = $request->options;
            foreach ($options as $key => $option) {
                Options::create([
                    'question_id' => $question->question_id,
                    'option_text' => $option['text'],
                    'is_correct' => $key === $request->correct_answer,
                ]);
            }
        } else {
            // Xóa options nếu chuyển sang tự luận
            $question->options()->delete();
        }

        return response()->json(['message' => 'Cập nhật câu hỏi thành công!', 'question' => $question]);
    }



    // Xóa một câu hỏi
    public function destroy($id)
    {
        $question = Question::find($id);
        if (!$question) {
            return response()->json(['message' => 'Không tìm thấy câu hỏi!'], Response::HTTP_NOT_FOUND);
        }

        // Xóa câu hỏi
        $question->delete();

        // Trả về thông báo thành công kèm theo dữ liệu câu hỏi đã xóa
        return response()->json([
            'message' => 'Câu hỏi đã được xóa thành công!',
            'deleted_question' => $question,
        ], Response::HTTP_OK);
    }

}
