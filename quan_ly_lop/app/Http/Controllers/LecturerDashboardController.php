<?php
// app/Http/Controllers/LecturerDashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LecturerDashboardController extends Controller
{
    public function index()
    {
        return view('lecturer.dashboard');
    }

    public function examDetail($examId)
    {
        return view('lecturer.exam_detail', ['examId' => $examId]);
    }

    public function assignmentDetail($assignmentId)
    {
        return view('lecturer.assignment_detail', ['assignmentId' => $assignmentId]);
    }
}