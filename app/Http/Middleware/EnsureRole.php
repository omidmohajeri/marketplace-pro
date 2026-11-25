<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// این میان‌افزار دسترسی مسیرها را بر اساس نقش کاربر محدود می‌کند.
class EnsureRole
{
    // نقش‌های مجاز را از پارامتر مسیر دریافت و نقش کاربر را بررسی می‌کند.
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // کاربر مهمان یا کاربری با نقش غیرمجاز اجازه ورود ندارد.
        abort_unless($request->user() && in_array($request->user()->role, $roles, true), 403);

        // در صورت مجاز بودن نقش، درخواست به کنترلر بعدی می‌رسد.
        return $next($request);
    }
}
