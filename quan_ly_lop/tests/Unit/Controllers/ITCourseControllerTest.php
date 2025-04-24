<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\ITCourseController;
use App\Models\ITCourse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ITCourseControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $controller;

    public function setUp(): void
    {
        parent::setUp();
        $this->controller = new ITCourseController();
    }

    /** @test */
    public function it_can_get_active_courses()
    {
        // Tạo các khóa học
        $activeCourse = ITCourse::factory()->create(['status' => 'active']);
        $inactiveCourse = ITCourse::factory()->create(['status' => 'inactive']);

        // Gọi phương thức index
        $response = $this->controller->index();

        // Kiểm tra kết quả
        $responseData = $response->getData();
        $this->assertEquals('success', $responseData->status);
        $this->assertCount(1, $responseData->data->courses);
        $this->assertEquals($activeCourse->id, $responseData->data->courses[0]->id);
    }

    /** @test */
    public function it_can_search_courses_by_keyword()
    {
        // Tạo khóa học với từ khóa cụ thể
        $course = ITCourse::factory()->create([
            'name' => 'Lập trình PHP',
            'description' => 'Khóa học về PHP',
            'code' => 'PHP101'
        ]);

        // Tạo request với từ khóa
        $request = new Request(['keyword' => 'PHP']);

        // Gọi phương thức search
        $response = $this->controller->search($request);

        // Kiểm tra kết quả
        $responseData = $response->getData();
        $this->assertEquals('success', $responseData->status);
        $this->assertCount(1, $responseData->data);
        $this->assertEquals($course->id, $responseData->data[0]->id);
    }

    /** @test */
    public function it_can_filter_courses_by_category()
    {
        // Tạo khóa học với category cụ thể
        $course = ITCourse::factory()->create(['category' => 'Web Development']);

        // Tạo request với category
        $request = new Request(['category' => 'Web Development']);

        // Gọi phương thức search
        $response = $this->controller->search($request);

        // Kiểm tra kết quả
        $responseData = $response->getData();
        $this->assertEquals('success', $responseData->status);
        $this->assertCount(1, $responseData->data);
        $this->assertEquals($course->id, $responseData->data[0]->id);
    }

    /** @test */
    public function it_can_get_recommended_books()
    {
        // Tạo khóa học
        $course = ITCourse::factory()->create();

        // Gọi phương thức getRecommendedBooks
        $response = $this->controller->getRecommendedBooks($course->id);

        // Kiểm tra kết quả
        $responseData = $response->getData();
        $this->assertEquals('success', $responseData->status);
        $this->assertIsArray($responseData->data);
        $this->assertNotEmpty($responseData->data);
    }

    /** @test */
    public function it_returns_error_for_nonexistent_course_books()
    {
        // Gọi phương thức với ID không tồn tại
        $response = $this->controller->getRecommendedBooks(999);

        // Kiểm tra response
        $this->assertEquals(404, $response->status());
    }

    /** @test */
    public function it_caches_courses_data()
    {
        // Tạo khóa học
        $course = ITCourse::factory()->create(['status' => 'active']);

        // Gọi phương thức index
        $this->controller->index();

        // Kiểm tra cache
        $this->assertTrue(Cache::has('it_courses'));
        $cachedCourses = Cache::get('it_courses');
        $this->assertCount(1, $cachedCourses);
        $this->assertEquals($course->id, $cachedCourses[0]->id);
    }

    /** @test */
    public function it_caches_recommended_books()
    {
        // Tạo khóa học
        $course = ITCourse::factory()->create();

        // Gọi phương thức getRecommendedBooks
        $this->controller->getRecommendedBooks($course->id);

        // Kiểm tra cache
        $cacheKey = "course_{$course->id}_books";
        $this->assertTrue(Cache::has($cacheKey));
        $cachedBooks = Cache::get($cacheKey);
        $this->assertIsArray($cachedBooks);
        $this->assertNotEmpty($cachedBooks);
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
