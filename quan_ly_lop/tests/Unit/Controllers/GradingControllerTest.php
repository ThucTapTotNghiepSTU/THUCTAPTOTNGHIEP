<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\GradingController;
use App\Models\Submission;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Options;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\Request;

class GradingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    public function setUp(): void
    {
        parent::setUp();
        $this->controller = new GradingController();
    }

    /** @test */
    public function it_can_grade_multiple_choice_submission_correctly()
    {
        // Tạo bài nộp
        $submission = Submission::factory()->create();

        // Tạo câu hỏi trắc nghiệm
        $question = Question::factory()->create([
            'type' => 'Trắc nghiệm',
            'title' => 'Câu hỏi trắc nghiệm',
            'content' => 'Nội dung câu hỏi'
        ]);

        // Tạo các lựa chọn
        $correctOption = Options::factory()->create([
            'question_id' => $question->question_id,
            'option_text' => 'Đáp án đúng',
            'is_correct' => true
        ]);

        Options::factory()->create([
            'question_id' => $question->question_id,
            'option_text' => 'Đáp án sai',
            'is_correct' => false
        ]);

        // Tạo câu trả lời của sinh viên
        Answer::factory()->create([
            'submission_id' => $submission->submission_id,
            'question_title' => $question->title,
            'question_content' => $question->content,
            'question_answer' => 'Đáp án đúng'
        ]);

        // Gọi phương thức chấm điểm
        $response = $this->controller->gradeSubmission(new Request(), $submission->submission_id);

        // Kiểm tra kết quả
        $responseData = $response->getData();
        $this->assertEquals(10, $responseData->total_score);
        $this->assertEquals(10, $responseData->max_score);
        $this->assertCount(1, $responseData->graded_answers);
        $this->assertTrue($responseData->graded_answers[0]->is_correct);
    }

    /** @test */
    public function it_can_grade_multiple_choice_submission_incorrectly()
    {
        // Tạo bài nộp
        $submission = Submission::factory()->create();

        // Tạo câu hỏi trắc nghiệm
        $question = Question::factory()->create([
            'type' => 'Trắc nghiệm',
            'title' => 'Câu hỏi trắc nghiệm',
            'content' => 'Nội dung câu hỏi'
        ]);

        // Tạo các lựa chọn
        $correctOption = Options::factory()->create([
            'question_id' => $question->question_id,
            'option_text' => 'Đáp án đúng',
            'is_correct' => true
        ]);

        Options::factory()->create([
            'question_id' => $question->question_id,
            'option_text' => 'Đáp án sai',
            'is_correct' => false
        ]);

        // Tạo câu trả lời sai của sinh viên
        Answer::factory()->create([
            'submission_id' => $submission->submission_id,
            'question_title' => $question->title,
            'question_content' => $question->content,
            'question_answer' => 'Đáp án sai'
        ]);

        // Gọi phương thức chấm điểm
        $response = $this->controller->gradeSubmission(new Request(), $submission->submission_id);

        // Kiểm tra kết quả
        $responseData = $response->getData();
        $this->assertEquals(0, $responseData->total_score);
        $this->assertEquals(10, $responseData->max_score);
        $this->assertCount(1, $responseData->graded_answers);
        $this->assertFalse($responseData->graded_answers[0]->is_correct);
    }

    /** @test */
    public function it_handles_essay_questions_correctly()
    {
        // Tạo bài nộp
        $submission = Submission::factory()->create();

        // Tạo câu hỏi tự luận
        $question = Question::factory()->create([
            'type' => 'Tự luận',
            'title' => 'Câu hỏi tự luận',
            'content' => 'Nội dung câu hỏi'
        ]);

        // Tạo câu trả lời của sinh viên
        Answer::factory()->create([
            'submission_id' => $submission->submission_id,
            'question_title' => $question->title,
            'question_content' => $question->content,
            'question_answer' => 'Câu trả lời tự luận'
        ]);

        // Gọi phương thức chấm điểm
        $response = $this->controller->gradeSubmission(new Request(), $submission->submission_id);

        // Kiểm tra kết quả
        $responseData = $response->getData();
        $this->assertEquals(0, $responseData->total_score);
        $this->assertEquals(10, $responseData->max_score);
        $this->assertCount(1, $responseData->graded_answers);
        $this->assertNull($responseData->graded_answers[0]->score);
    }

    /** @test */
    public function it_handles_multiple_questions_correctly()
    {
        // Tạo bài nộp
        $submission = Submission::factory()->create();

        // Tạo câu hỏi trắc nghiệm 1
        $question1 = Question::factory()->create([
            'type' => 'Trắc nghiệm',
            'title' => 'Câu hỏi 1',
            'content' => 'Nội dung câu hỏi 1'
        ]);

        // Tạo câu hỏi trắc nghiệm 2
        $question2 = Question::factory()->create([
            'type' => 'Trắc nghiệm',
            'title' => 'Câu hỏi 2',
            'content' => 'Nội dung câu hỏi 2'
        ]);

        // Tạo các lựa chọn cho câu hỏi 1
        Options::factory()->create([
            'question_id' => $question1->question_id,
            'option_text' => 'Đáp án đúng 1',
            'is_correct' => true
        ]);

        // Tạo các lựa chọn cho câu hỏi 2
        Options::factory()->create([
            'question_id' => $question2->question_id,
            'option_text' => 'Đáp án đúng 2',
            'is_correct' => true
        ]);

        // Tạo câu trả lời đúng cho câu hỏi 1
        Answer::factory()->create([
            'submission_id' => $submission->submission_id,
            'question_title' => $question1->title,
            'question_content' => $question1->content,
            'question_answer' => 'Đáp án đúng 1'
        ]);

        // Tạo câu trả lời sai cho câu hỏi 2
        Answer::factory()->create([
            'submission_id' => $submission->submission_id,
            'question_title' => $question2->title,
            'question_content' => $question2->content,
            'question_answer' => 'Đáp án sai'
        ]);

        // Gọi phương thức chấm điểm
        $response = $this->controller->gradeSubmission(new Request(), $submission->submission_id);

        // Kiểm tra kết quả
        $responseData = $response->getData();
        $this->assertEquals(5, $responseData->total_score);
        $this->assertEquals(10, $responseData->max_score);
        $this->assertCount(2, $responseData->graded_answers);
    }
}
