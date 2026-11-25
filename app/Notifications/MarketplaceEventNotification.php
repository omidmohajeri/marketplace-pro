<?php
// اعلان پایگاه‌داده‌ای عمومی برای رویدادهای فروشگاه
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
class MarketplaceEventNotification extends Notification {
 use Queueable;
 public function __construct(public string $title, public string $message, public array $data=[]) {}
 public function via(object $notifiable): array { return ['database']; } // ذخیره اعلان در دیتابیس
 public function toArray(object $notifiable): array {
  return ['title'=>$this->title,'message'=>$this->message,'data'=>$this->data]; // داده نمایشی اعلان
 }
}