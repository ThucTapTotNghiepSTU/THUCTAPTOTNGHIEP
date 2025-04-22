<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (Auth::guard('students')->check()) {
            return route('homeLoggedIn'); // Đối với sinh viên
        }
        if (Auth::guard('lecturer')->check()) {
            return route('homeLecturer'); // Đối với giảng viên
        }
        return $request->expectsJson() ? null : route('Showlogin');
    }

}
