<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Exam;
use App\Models\SubList;
use App\Models\Question;
use App\Models\Option;
use App\Models\SubListQuestion;
use App\Models\Student;
use App\Models\Submission;
use App\Models\Answer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ExamControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();

        // Thiết lập kết nối MySQL
        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => env('DB_DATABASE', 'quan_ly_lop'),
            'database.connections.mysql.username' => env('DB_USERNAME', 'root'),
            'database.connections.mysql.password' => env('DB_PASSWORD', ''),
        ]);
    }

    /** @test */
    public function it_can_show_exam_details()
    {
        // Create test data
        $subList = SubList::factory()->create(['isShuffle' => true]);
        $question = Question::factory()->create();
        $options = Option::factory()->count(4)->create(['question_id' => $question->question_id]);
        SubListQuestion::factory()->create([
            'sub_list_id' => $subList->sub_list_id,
            'question_id' => $question->question_id
        ]);

        $exam = Exam::factory()->create([
            'sub_list_id' => $subList->sub_list_id,
            'type' => 'Trắc nghiệm'
        ]);

        // Make request
        $response = $this->getJson("/api/exams/{$exam->exam_id}");

        // Assert response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'exam_id',
                'title',
                'type',
                'start_time',
                'end_time',
                'isSimultaneous',
                'questions' => [
                    '*' => [
                        'question_id',
                        'content',
                        'choices'
                    ]
                ]
            ]);
    }

    /** @test */
    public function it_returns_404_when_exam_not_found()
    {
        $response = $this->getJson('/api/exams/non-existent-id');
        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_create_a_new_exam()
    {
        $subList = SubList::factory()->create();

        $examData = [
            'sub_list_id' => $subList->sub_list_id,
            'title' => 'Test Exam',
            'content' => 'Test Content',
            'type' => 'Trắc nghiệm',
            'isSimultaneous' => 1,
            'start_time' => now()->addDay(),
            'end_time' => now()->addDays(2),
            'status' => 'active'
        ];

        $response = $this->postJson('/api/exams', $examData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'exam' => [
                    'exam_id',
                    'title',
                    'content',
                    'type',
                    'isSimultaneous',
                    'start_time',
                    'end_time',
                    'status'
                ]
            ]);

        $this->assertDatabaseHas('exam', [
            'title' => 'Test Exam',
            'type' => 'Trắc nghiệm'
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_creating_exam()
    {
        $response = $this->postJson('/api/exams', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['sub_list_id', 'title', 'type', 'isSimultaneous', 'status']);
    }

    /** @test */
    public function it_can_update_an_exam()
    {
        $exam = Exam::factory()->create();

        $updateData = [
            'title' => 'Updated Title',
            'content' => 'Updated Content',
            'type' => 'Tự luận',
            'isSimultaneous' => 0,
            'status' => 'inactive'
        ];

        $response = $this->putJson("/api/exams/{$exam->exam_id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Bài thi đã được cập nhật!'
            ]);

        $this->assertDatabaseHas('exam', [
            'exam_id' => $exam->exam_id,
            'title' => 'Updated Title'
        ]);
    }

    /** @test */
    public function it_returns_404_when_updating_nonexistent_exam()
    {
        $response = $this->putJson('/api/exams/non-existent-id', [
            'title' => 'Updated Title'
        ]);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_delete_an_exam()
    {
        $exam = Exam::factory()->create();

        $response = $this->deleteJson("/api/exams/{$exam->exam_id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Bài thi đã bị xóa!'
            ]);

        $this->assertDatabaseMissing('exam', [
            'exam_id' => $exam->exam_id
        ]);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_exam()
    {
        $response = $this->deleteJson('/api/exams/non-existent-id');
        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_get_exam_detail_with_submissions()
    {
        // Create test data
        $exam = Exam::factory()->create();
        $student = Student::factory()->create();
        $submission = Submission::factory()->create([
            'exam_id' => $exam->exam_id,
            'student_id' => $student->student_id
        ]);
        $answer = Answer::factory()->create([
            'submission_id' => $submission->submission_id
        ]);

        $response = $this->getJson("/api/exams/{$exam->exam_id}/detail");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'exam',
                    'submission_count',
                    'submissions' => [
                        '*' => [
                            'student',
                            'answers'
                        ]
                    ]
                ]
            ]);
    }

    protected function tearDown(): void
    {
        // Clean up test data
        DB::table('exam')->truncate();
        DB::table('sub_list')->truncate();
        DB::table('question')->truncate();
        DB::table('option')->truncate();
        DB::table('sub_list_question')->truncate();
        DB::table('student')->truncate();
        DB::table('submission')->truncate();
        DB::table('answer')->truncate();

        parent::tearDown();
    }
}
