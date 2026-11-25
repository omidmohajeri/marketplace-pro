@extends('layouts.app')
@section('title','ثبت‌نام')
@section('content')
<div class="max-w-md mx-auto bg-white rounded-2xl border p-7">
<h1 class="text-2xl font-black mb-6">ایجاد حساب کاربری</h1>
@if($errors->any())<div class="bg-rose-50 text-rose-700 p-3 rounded-xl mb-4">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('register.store') }}" class="space-y-4">
@csrf
<label class="block text-sm">نام<input name="name" value="{{ old('name') }}" required class="mt-2 w-full border rounded-xl p-3"></label>
<label class="block text-sm">ایمیل<input name="email" type="email" value="{{ old('email') }}" required class="mt-2 w-full border rounded-xl p-3"></label>
<label class="block text-sm">رمز عبور<input name="password" type="password" required class="mt-2 w-full border rounded-xl p-3"></label>
<label class="block text-sm">تکرار رمز عبور<input name="password_confirmation" type="password" required class="mt-2 w-full border rounded-xl p-3"></label>
<button class="w-full bg-rose-600 text-white rounded-xl p-3 font-bold">ثبت‌نام</button>
</form>
</div>
@endsection
