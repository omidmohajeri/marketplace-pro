{{-- فرم ساخت یا ویرایش کمپین --}}
@extends('admin.layout')
@section('content')
<div class="container" dir="rtl">
<h1>{{ $coupon->exists ? 'ویرایش کمپین' : 'کمپین جدید' }}</h1>
<form method="POST" action="{{ $coupon->exists ? route('admin.coupons.update',$coupon) : route('admin.coupons.store') }}">
@csrf @if($coupon->exists) @method('PUT') @endif
<label>کد تخفیف <input name="code" required maxlength="64" value="{{ old('code',$coupon->code) }}"></label>
<label>عنوان <input name="name" required value="{{ old('name',$coupon->name) }}"></label>
<label>توضیحات <textarea name="description">{{ old('description',$coupon->description) }}</textarea></label>
<label>نوع <select name="type"><option value="percentage">درصدی</option><option value="fixed">مبلغ ثابت</option></select></label>
<label>مقدار <input type="number" step="1" min="1" name="value" required value="{{ old('value',$coupon->value) }}"></label>
<label>حداقل مبلغ سفارش <input type="number" step="1" name="minimum_order_amount" value="{{ old('minimum_order_amount',$coupon->minimum_order_amount ?? 0) }}"></label>
<label>سقف تخفیف <input type="number" step="1" name="maximum_discount_amount" value="{{ old('maximum_discount_amount',$coupon->maximum_discount_amount) }}"></label>
<label>سقف کل استفاده <input type="number" name="usage_limit" value="{{ old('usage_limit',$coupon->usage_limit) }}"></label>
<label>سقف استفاده هر کاربر <input type="number" name="usage_limit_per_user" value="{{ old('usage_limit_per_user',$coupon->usage_limit_per_user) }}"></label>
<label>شروع <input type="datetime-local" name="starts_at" value="{{ old('starts_at',$coupon->starts_at?->format('Y-m-d\TH:i')) }}"></label>
<label>پایان <input type="datetime-local" name="expires_at" value="{{ old('expires_at',$coupon->expires_at?->format('Y-m-d\TH:i')) }}"></label>
<label><input type="checkbox" name="is_active" value="1" @checked(old('is_active',$coupon->is_active ?? true))> فعال</label>
<button type="submit">ذخیره</button>
</form>
</div>
@endsection