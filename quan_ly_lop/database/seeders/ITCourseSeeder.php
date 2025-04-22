<?php

namespace Database\Seeders;

use App\Models\ITCourse;
use Illuminate\Database\Seeder;

class ITCourseSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            [
                'name' => 'Nhập môn Lập trình Web',
                'code' => 'WEB101',
                'description' => 'Học về HTML, CSS, JavaScript và các công nghệ web cơ bản',
                'image_url' => '/images/courses/web-intro.jpg',
                'category' => 'web',
                'credits' => 3,
                'learning_outcomes' => json_encode([
                    'Hiểu về cấu trúc và hoạt động của web',
                    'Thành thạo HTML5 và CSS3',
                    'Lập trình JavaScript cơ bản',
                    'Xây dựng được website responsive'
                ]),
                'prerequisites' => null,
                'recommended_books' => json_encode([
                    [
                        'title' => 'Learning Web Design',
                        'author' => 'Jennifer Niederst Robbins',
                        'link' => 'https://example.com/book1'
                    ]
                ])
            ],
            [
                'name' => 'Lập trình Ứng dụng Di động',
                'code' => 'MOB201',
                'description' => 'Phát triển ứng dụng cho Android và iOS',
                'image_url' => '/images/courses/mobile-dev.jpg',
                'category' => 'mobile',
                'credits' => 4,
                'learning_outcomes' => json_encode([
                    'Thiết kế giao diện mobile',
                    'Lập trình Android với Kotlin',
                    'Phát triển ứng dụng đa nền tảng',
                    'Tối ưu hiệu năng ứng dụng'
                ]),
                'prerequisites' => 'OOP101',
                'recommended_books' => json_encode([
                    [
                        'title' => 'Android Programming: The Big Nerd Ranch Guide',
                        'author' => 'Bill Phillips',
                        'link' => 'https://example.com/book2'
                    ]
                ])
            ],
            // Thêm các môn học khác
        ];

        foreach ($courses as $course) {
            ITCourse::create($course);
        }
    }
} 