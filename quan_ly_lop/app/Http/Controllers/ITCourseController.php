<?php

namespace App\Http\Controllers;

use App\Models\ITCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ITCourseController extends Controller
{
    public function index()
    {
        // Cache kết quả trong 1 giờ
        $courses = Cache::remember('it_courses', 3600, function () {
            return ITCourse::where('status', 'active')
                          ->orderBy('created_at', 'desc')
                          ->get();
        });

        $categories = ITCourse::getCategories();

        return response()->json([
            'status' => 'success',
            'data' => [
                'courses' => $courses,
                'categories' => $categories
            ]
        ]);
    }

    public function search(Request $request)
    {
        $query = ITCourse::query();

        // Tìm kiếm theo từ khóa
        if ($request->has('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('name', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%")
                  ->orWhere('code', 'LIKE', "%{$keyword}%");
            });
        }

        // Lọc theo danh mục
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $courses = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $courses
        ]);
    }

    public function getRecommendedBooks($courseId)
    {
        $course = ITCourse::findOrFail($courseId);
        
        // Cache sách được đề xuất trong 1 ngày
        $books = Cache::remember("course_{$courseId}_books", 86400, function () use ($course) {
            return [
                [
                    'title' => 'Clean Code: A Handbook of Agile Software Craftsmanship',
                    'author' => 'Robert C. Martin',
                    'image' => 'path/to/clean-code.jpg',
                    'link' => 'https://example.com/clean-code'
                ],
                // Thêm sách khác tùy theo môn học
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }
} 