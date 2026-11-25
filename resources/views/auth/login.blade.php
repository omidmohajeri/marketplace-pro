@extends('layouts.app')
@section('title','ورود')
@section('content')
<div class="max-w-md mx-auto bg-white rounded-2xl border p-7">
<h1 class="text-2xl font-black mb-6">ورود به حساب</h1>
@if($errors->any())<div class="bg-rose-50 text-rose-700 p-3 rounded-xl mb-4">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('login.store') }}" class="space-y-4">
@csrf
<label class="block text-sm">ایمیل<input name="email" type="email" value="{{ old('email') }}" required class="mt-2 w-full border rounded-xl p-3"></label>
<label class="block text-sm">رمز عبور<input name="password" type="password" required class="mt-2 w-full border rounded-xl p-3"></label>
<label class="flex gap-2 text-sm"><input name="remember" type="checkbox" value="1"> مرا به خاطر بسپار</label>
<button class="w-full bg-slate-900 text-white rounded-xl p-3 font-bold">ورود</button>
</form>
<a href="{{ route('register') }}" class="block mt-4 text-rose-600">ایجاد حساب جدید</a>
</div>
@endsection
