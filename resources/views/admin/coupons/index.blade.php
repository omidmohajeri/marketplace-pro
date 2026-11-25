{{-- فهرست کمپین‌های تخفیف --}}
@extends('admin.layout')
@section('content')
<div class="container" dir="rtl">
    <h1>مدیریت کدهای تخفیف</h1>
    <a href="{{ route('admin.coupons.create') }}">ایجاد کمپین جدید</a>
    @if(session('success'))<p>{{ session('success') }}</p>@endif
    <table>
        <thead><tr><th>کد</th><th>عنوان</th><th>نوع</th><th>مقدار</th><th>مصرف</th><th>وضعیت</th><th>عملیات</th></tr></thead>
        <tbody>
        @foreach($coupons as $coupon)
            <tr>
                <td>{{ $coupon->code }}</td><td>{{ $coupon->name }}</td>
                <td>{{ $coupon->type === 'percentage' ? 'درصدی' : 'مبلغ ثابت' }}</td>
                <td>{{ $coupon->value }}</td><td>{{ $coupon->used_count }}</td>
                <td>{{ $coupon->is_active ? 'فعال' : 'غیرفعال' }}</td>
                <td><a href="{{ route('admin.coupons.edit',$coupon) }}">ویرایش</a>
                <form method="POST" action="{{ route('admin.coupons.destroy',$coupon) }}">@csrf @method('DELETE')<button>غیرفعال‌سازی</button></form></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $coupons->links() }}
</div>
@endsection