<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('homeLoggedIn');
        }
        return view('auth.login');
    }
    protected function redirectTo()
    {
        if (Auth::check()) {
            if (Auth::user()->lecturer_id) {
                return route('homeLecturer');
            } elseif (Auth::user()->student_id) {
                return route('homeLoggedIn');
            }
        }

        return '/'; // fallback
    }
    public function login(Request $request)
    {
        try {
            $request->validate([
                'school_email' => 'required|email',
                'password' => 'required',
            ]);

            // Tìm người dùng theo email
            $student = Student::where('school_email', $request->school_email)->first();
            $lecturer = Lecturer::where('school_email', $request->school_email)->first();

            if (!$student && !$lecturer) {
                return back()
                    ->withInput($request->except('password'))
                    ->withErrors(['school_email' => 'Email không tồn tại trong hệ thống.']);
            }

            // Nếu là sinh viên
            if ($student) {
                if (!Hash::check($request->password, $student->password)) {
                    return back()
                        ->withInput($request->except('password'))
                        ->withErrors(['password' => 'Mật khẩu không chính xác.']);
                }
                Auth::guard('students')->login($student, $request->filled('remember'));
                $request->session()->regenerate();

                // Redirect đến trang chủ sinh viên
                return redirect()->intended('/home')->with('success', 'Xin chào ' . $student->full_name . '!');
            }

            // Nếu là giảng viên
            if ($lecturer) {
                if (!Hash::check($request->password, $lecturer->password)) {
                    return back()
                        ->withInput($request->except('password'))
                        ->withErrors(['password' => 'Mật khẩu không chính xác.']);
                }
                Auth::guard('lecturer')->login($lecturer);
                $request->session()->regenerate();

                // Redirect đến trang chủ giảng viên
                return redirect()->intended('/homeLecturer')->with('success', 'Xin chào ' . $lecturer->fullname . '!');
            }

        } catch (\Exception $e) {
            // Ghi log lỗi
            dd($e->getMessage(), $e->getTraceAsString());
            return back()
                ->withInput($request->except('password'))
                ->withErrors(['error' => 'Có lỗi xảy ra khi đăng nhập. Vui lòng thử lại.']);
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Đăng xuất thành công.');
    }
}
