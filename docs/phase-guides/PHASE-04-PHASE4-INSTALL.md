# راهنمای فاز چهارم پنل فروشندگان

## محتویات
این بسته کنترلرها، مسیرها و نماهای پنل فروشنده را اضافه می‌کند:
- داشبورد و آمار فروشگاه
- فهرست، ثبت، ویرایش و حذف کالاهای خود فروشنده
- مدیریت موجودی
- مشاهده سفارش‌های دارای اقلام فروشنده و نمایش فقط اقلام خودش

## پیش‌نیازها
- پروژه Laravel 12 طبق راهنمای فاز اول
- فازهای مدل‌ها، احراز هویت و نقش‌ها
- مدل `User` دارای رابطه `seller()`
- مدل `Seller` و ستون‌های `user_id`, `store_name`, `status`
- مدل `Product` با ستون‌های `seller_id`, `name`, `description`, `category_id`, `sku`, `slug`, `price`, `compare_at_price`, `stock`, `status`
- مدل `Order` دارای روابط `user()` و `items()`
- مدل `OrderItem` دارای رابطه `product()` و ستون‌های `order_id`, `product_id`, `quantity`, `unit_price`
- مدل `Category` دارای ستون‌های `is_active`, `sort_order`, `name`

## ادغام
1. پوشه‌های `app/` و `resources/` را با پروژه ادغام کنید.
2. محتوای `routes/seller-phase4.snippet.php` را به انتهای `routes/web.php` منتقل کنید. خود فایل snippet را به‌عنوان route file جداگانه include نکنید، مگر اینکه روش بارگذاری routeها را جداگانه تنظیم کرده باشید.
3. اطمینان حاصل کنید alias نقش `role` در `bootstrap/app.php` ثبت شده است:
   ```php
   $middleware->alias([
       'role' => \App\Http\Middleware\EnsureRole::class,
   ]);
   ```
4. اطمینان حاصل کنید کاربر فروشنده دارای `role=seller` و رکورد متناظر در جدول `sellers` است.
5. کش‌ها را پاک و مسیرها را بررسی کنید:
   ```bash
   php artisan optimize:clear
   php artisan route:list --path=seller
   ```

## رابطه‌های موردنیاز
در مدل `User`:
```php
public function seller() { return $this->hasOne(\App\Models\Seller::class); }
```
در مدل `Order`:
```php
public function items() { return $this->hasMany(\App\Models\OrderItem::class); }
public function user() { return $this->belongsTo(\App\Models\User::class); }
```
در مدل `OrderItem`:
```php
public function product() { return $this->belongsTo(\App\Models\Product::class); }
```

## نکات مهم امنیتی و عملیاتی
- فروشنده فقط کالاهای دارای `seller_id` خودش را می‌تواند مشاهده یا ویرایش کند.
- سفارش‌های دیگر فروشندگان در صفحه جزئیات نمایش داده نمی‌شوند؛ با این حال ساختار سفارش همچنان یک سفارش کلی است.
- ثبت و ویرایش کالا، وضعیت را `pending` می‌کند تا مدیر آن را بررسی کند.
- فروشنده امکان تغییر وضعیت پرداخت یا وضعیت کلی سفارش را ندارد.
- این بسته فرض می‌کند نام ستون قیمت در ردیف سفارش `unit_price` است. اگر پروژه شما از نام دیگری استفاده می‌کند باید کوئری‌ها و نماها با اسکیمای واقعی هماهنگ شوند.
- اگر `name` و `description` در دیتابیس JSON هستند، cast آن‌ها در مدل‌ها باید `array` باشد.
- رابط فعلی برای سرعت از CDN Tailwind استفاده می‌کند؛ برای محیط عملیاتی فایل‌های asset را با Vite build و از CDN مستقل کنید.
- این بسته به‌تنهایی پروژه کامل نیست و هنوز نیازمند تست یکپارچه با اسکیمای واقعی فازهای قبلی، درگاه پرداخت، محاسبه سهم فروشنده، تسویه‌حساب و گردش کامل ارسال/مرجوعی است.

## فرمان‌های بررسی
```bash
php artisan optimize:clear
php artisan route:list --path=seller
php artisan view:cache
```
