<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::guard('students')->check()) {
            $student = Auth::guard('students')->user();
            return view('homepage', compact('student'));
        } elseif (Auth::guard('lecturer')->check()) {
            $lecturer = Auth::guard('lecturer')->user();
            return view('modules.mod_lecturer.mod_trang_chu_lecturer', compact('lecturer'));
        } else {
            abort(403, 'Không xác định người dùng');
        }
    }
}
