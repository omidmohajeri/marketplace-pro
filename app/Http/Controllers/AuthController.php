<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

// این کنترلر ثبت‌نام، ورود و خروج کاربران را مدیریت می‌کند.
class AuthController extends Controller
{
    // فرم ورود را نمایش می‌دهد.
    public function loginForm() { return view('auth.login'); }

    // اطلاعات ورود را اعتبارسنجی و نشست کاربر را ایجاد می‌کند.
    public function login(Request $request)
    {
        // ایمیل یا شماره تلفن و رمز عبور باید معتبر باشند.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // تلاش برای ورود با اطلاعات معتبر؛ نشست در صورت موفقیت بازتولید می‌شود.
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'اطلاعات ورود صحیح نیست.'])->onlyInput('email');
        }

        // جلوگیری از حمله تثبیت نشست پس از ورود موفق.
        $request->session()->regenerate();

        // کاربر پس از ورود به صفحه اصلی منتقل می‌شود.
        return redirect()->intended(route('home'));
    }

    // فرم ثبت‌نام را نمایش می‌دهد.
    public function registerForm() { return view('auth.register'); }

    // حساب کاربری جدید ایجاد می‌کند.
    public function register(Request $request)
    {
        // داده‌های ارسالی از نظر قالب، یکتایی و قدرت رمز عبور بررسی می‌شوند.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(10)->letters()->numbers()],
        ]);

        // حساب جدید با نقش مشتری ساخته می‌شود؛ کاربر نمی‌تواند نقش مدیر تعیین کند.
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'customer',
            'locale' => app()->getLocale(),
        ]);

        // کاربر بلافاصله پس از ثبت‌نام وارد حساب خود می‌شود.
        Auth::login($user);

        // نشست جدید برای حساب تازه‌وارد ایجاد می‌شود.
        $request->session()->regenerate();

        // انتقال به صفحه اصلی پس از ثبت‌نام موفق.
        return redirect()->route('home');
    }

    // کاربر را از حساب خارج می‌کند.
    public function logout(Request $request)
    {
        // نشست احراز هویت فعلی خاتمه داده می‌شود.
        Auth::logout();

        // توکن نشست و داده‌های نشست پاک‌سازی می‌شوند.
        $request->session()->invalidate();

        // توکن CSRF جدید ساخته می‌شود.
        $request->session()->regenerateToken();

        // کاربر به صفحه اصلی بازمی‌گردد.
        return redirect()->route('home');
    }
}
