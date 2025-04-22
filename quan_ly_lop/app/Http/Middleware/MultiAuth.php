<?php

// app/Http/Middleware/MultiAuth.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MultiAuth
{
    public function handle($request, Closure $next)
    {
        $guards = ['students', 'lecturer'];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Gắn người dùng hiện tại vào auth()->user()
                Auth::shouldUse($guard);
                break;
            }
        }

        return $next($request);
    }
}

?>
