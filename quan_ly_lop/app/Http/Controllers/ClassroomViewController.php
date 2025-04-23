<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use stdClass;

class ClassroomViewController extends Controller
{
    public function show()
    {
        // Tạo dữ liệu giả đơn giản
        $classroom = new stdClass();
        $classroom->id = 1;
        $classroom->name = 'Lớp lập trình cơ bản';
        $classroom->code = 'LTC001-01';
        
        // Tạo dữ liệu giả cho thông báo
        $announcements = [];
        $teacher = new stdClass();
        $teacher->id = 1;
        $teacher->name = 'Nguyễn Văn A';
        $teacher->avatar = 'https://ui-avatars.com/api/?name=Nguyen+Van+A&background=random';
        
        for ($i = 1; $i <= 3; $i++) {
            $announcement = new stdClass();
            $announcement->id = $i;
            $announcement->teacher = $teacher;
            $announcement->content = "Thông báo quan trọng số $i cho lớp học.";
            $announcement->created_at = Carbon::now()->subDays($i);
            $announcements[] = $announcement;
        }
        
        // Tạo dữ liệu giả cho bài tập
        $assignments = [];
        for ($i = 1; $i <= 3; $i++) {
            $assignment = new stdClass();
            $assignment->id = $i;
            $assignment->title = "Bài tập $i: Chương trình đơn giản";
            $assignment->description = "Mô tả chi tiết về bài tập $i";
            $assignment->due_date = Carbon::now()->addDays($i * 2);
            $assignments[] = $assignment;
        }
        
        // Tạo dữ liệu giả cho bài kiểm tra
        $exams = [];
        for ($i = 1; $i <= 2; $i++) {
            $exam = new stdClass();
            $exam->id = $i;
            $exam->title = $i == 1 ? "Kiểm tra giữa kỳ" : "Kiểm tra cuối kỳ";
            $exam->description = "Nội dung kiểm tra " . ($i == 1 ? "giữa kỳ" : "cuối kỳ");
            $exam->start_time = Carbon::now()->addDays($i * 10);
            $exam->end_time = Carbon::now()->addDays($i * 10)->addHours(2);
            $exams[] = $exam;
        }
        
        // Tạo dữ liệu giả cho học sinh
        $students = [];
        for ($i = 1; $i <= 5; $i++) {
            $student = new stdClass();
            $student->id = $i;
            $student->name = "Học sinh $i";
            $student->email = "hocsinh$i@example.com";
            $student->avatar = "https://ui-avatars.com/api/?name=Hoc+Sinh+$i&background=random";
            $student->pivot = new stdClass();
            $student->pivot->created_at = Carbon::now()->subDays($i);
            $students[] = $student;
        }
        
        // Trả về view với dữ liệu giả
        return view('lecturer.classroom.show', compact('classroom', 'announcements', 'assignments', 'exams', 'students'));
    }
}