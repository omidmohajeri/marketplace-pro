<?php
// تنظیمات عمومی قابل تغییر فروشگاه
return [
 'pagination'=>['products'=>24], // تعداد کالا در هر صفحه
 'checkout'=>['lock_seconds'=>10], // مقدار پیشنهادی قفل کوتاه در عملیات موجودی؛ در سرویس واقعی اعمال شود
 'maintenance'=>['show_status_page'=>env('MARKETPLACE_STATUS_PAGE',false)], // نمایش وضعیت سرویس
];