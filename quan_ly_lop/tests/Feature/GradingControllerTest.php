<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Submission;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Options;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GradingControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_grade_multiple_choice_submission_correctly()
    {
        // Create a question
        $question = Question::create([
            'title' => 'Test Question',
            'content' => 'What is 2+2?',
            'type' => 'Trắc nghiệm'
        ]);

        // Create correct and incorrect options
        Options::create([
            'question_id' => $question->question_id,
            'option_text' => '4',
            'is_correct' => true
        ]);

        Options::create([
            'question_id' => $question->question_id,
            'option_text' => '5',
            'is_correct' => false
        ]);

        // Create a submission
        $submission = Submission::create([
            'student_id' => 'test-student-id',
            'quiz_id' => 'test-quiz-id'
        ]);

        // Create an answer with correct response
        Answer::create([
            'submission_id' => $submission->id,
            'question_title' => 'Test Question',
            'question_content' => 'What is 2+2?',
            'question_answer' => '4'
        ]);

        // Make request to grade submission
        $response = $this->postJson("/api/submissions/{$submission->id}/grade");

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'total_score' => 10.0,
                'max_score' => 10.0,
                'graded_answers' => [
                    [
                        'question' => 'Test Question',
                        'student_answer' => '4',
                        'correct_answer' => '4',
                        'score' => 10.0,
                        'is_correct' => true
                    ]
                ]
            ]);

        // Assert submission was updated
        $this->assertEquals(10.0, $submission->fresh()->temporary_score);
    }

    /** @test */
    public function it_can_grade_multiple_choice_submission_with_incorrect_answer()
    {
        // Create a question
        $question = Question::create([
            'title' => 'Test Question',
            'content' => 'What is 2+2?',
            'type' => 'Trắc nghiệm'
        ]);

        // Create correct option
        Options::create([
            'question_id' => $question->question_id,
            'option_text' => '4',
            'is_correct' => true
        ]);

        // Create a submission
        $submission = Submission::create([
            'student_id' => 'test-student-id',
            'quiz_id' => 'test-quiz-id'
        ]);

        // Create an answer with incorrect response
        Answer::create([
            'submission_id' => $submission->id,
            'question_title' => 'Test Question',
            'question_content' => 'What is 2+2?',
            'question_answer' => '5'
        ]);

        // Make request to grade submission
        $response = $this->postJson("/api/submissions/{$submission->id}/grade");

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'total_score' => 0.0,
                'max_score' => 10.0,
                'graded_answers' => [
                    [
                        'question' => 'Test Question',
                        'student_answer' => '5',
                        'correct_answer' => '4',
                        'score' => 0.0,
                        'is_correct' => false
                    ]
                ]
            ]);

        // Assert submission was updated
        $this->assertEquals(0.0, $submission->fresh()->temporary_score);
    }

    /** @test */
    public function it_handles_essay_questions_correctly()
    {
        // Create an essay question
        $question = Question::create([
            'title' => 'Essay Question',
            'content' => 'Explain your answer',
            'type' => 'Tự luận'
        ]);

        // Create a submission
        $submission = Submission::create([
            'student_id' => 'test-student-id',
            'quiz_id' => 'test-quiz-id'
        ]);

        // Create an answer
        Answer::create([
            'submission_id' => $submission->id,
            'question_title' => 'Essay Question',
            'question_content' => 'Explain your answer',
            'question_answer' => 'This is my explanation'
        ]);

        // Make request to grade submission
        $response = $this->postJson("/api/submissions/{$submission->id}/grade");

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'total_score' => 0.0,
                'max_score' => 10.0,
                'graded_answers' => [
                    [
                        'question' => 'Essay Question',
                        'student_answer' => 'This is my explanation',
                        'score' => null
                    ]
                ]
            ]);

        // Assert submission was updated
        $this->assertEquals(0.0, $submission->fresh()->temporary_score);
    }

    /** @test */
    public function it_handles_mixed_question_types_correctly()
    {
        // Create multiple choice question
        $mcQuestion = Question::create([
            'title' => 'MC Question',
            'content' => 'What is 2+2?',
            'type' => 'Trắc nghiệm'
        ]);

        Options::create([
            'question_id' => $mcQuestion->question_id,
            'option_text' => '4',
            'is_correct' => true
        ]);

        // Create essay question
        $essayQuestion = Question::create([
            'title' => 'Essay Question',
            'content' => 'Explain your answer',
            'type' => 'Tự luận'
        ]);

        // Create a submission
        $submission = Submission::create([
            'student_id' => 'test-student-id',
            'quiz_id' => 'test-quiz-id'
        ]);

        // Create answers
        Answer::create([
            'submission_id' => $submission->id,
            'question_title' => 'MC Question',
            'question_content' => 'What is 2+2?',
            'question_answer' => '4'
        ]);

        Answer::create([
            'submission_id' => $submission->id,
            'question_title' => 'Essay Question',
            'question_content' => 'Explain your answer',
            'question_answer' => 'This is my explanation'
        ]);

        // Make request to grade submission
        $response = $this->postJson("/api/submissions/{$submission->id}/grade");

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'total_score' => 5.0, // Only MC question is graded
                'max_score' => 10.0,
                'graded_answers' => [
                    [
                        'question' => 'MC Question',
                        'student_answer' => '4',
                        'correct_answer' => '4',
                        'score' => 5.0,
                        'is_correct' => true
                    ],
                    [
                        'question' => 'Essay Question',
                        'student_answer' => 'This is my explanation',
                        'score' => null
                    ]
                ]
            ]);

        // Assert submission was updated
        $this->assertEquals(5.0, $submission->fresh()->temporary_score);
    }

    /** @test */
    public function it_can_update_manual_grades_for_essay_questions()
    {
        // Create essay question
        $question = Question::create([
            'title' => 'Essay Question',
            'content' => 'Explain your answer',
            'type' => 'Tự luận'
        ]);

        // Create a submission
        $submission = Submission::create([
            'student_id' => 'test-student-id',
            'quiz_id' => 'test-quiz-id',
            'temporary_score' => 5.0 // Previous score from MC questions
        ]);

        // Create an answer
        Answer::create([
            'submission_id' => $submission->id,
            'question_title' => 'Essay Question',
            'question_content' => 'Explain your answer',
            'question_answer' => 'This is my explanation'
        ]);

        // Make request to update manual grades
        $response = $this->postJson("/api/submissions/{$submission->id}/manual-grade", [
            'grades' => [
                [
                    'question_title' => 'Essay Question',
                    'score' => 8.0
                ]
            ]
        ]);

        // Assert response
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Grades updated successfully',
                'submission' => [
                    'temporary_score' => 6.5 // Average of MC (5.0) and essay (8.0)
                ]
            ]);

        // Assert submission was updated
        $this->assertEquals(6.5, $submission->fresh()->temporary_score);
    }

    /** @test */
    public function it_validates_manual_grades_request()
    {
        // Create a submission
        $submission = Submission::create([
            'student_id' => 'test-student-id',
            'quiz_id' => 'test-quiz-id'
        ]);

        // Make request with invalid data
        $response = $this->postJson("/api/submissions/{$submission->id}/manual-grade", [
            'grades' => [
                [
                    'question_title' => '', // Empty title
                    'score' => 11 // Score > 10
                ]
            ]
        ]);

        // Assert validation errors
        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'grades.0.question_title',
                'grades.0.score'
            ]);
    }

    /** @test */
    public function it_returns_404_for_non_existent_submission()
    {
        $response = $this->postJson("/api/submissions/999/grade");
        $response->assertStatus(404);

        $response = $this->postJson("/api/submissions/999/manual-grade", [
            'grades' => [
                [
                    'question_title' => 'Test Question',
                    'score' => 8.0
                ]
            ]
        ]);
        $response->assertStatus(404);
    }
}
