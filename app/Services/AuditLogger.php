<?php
// سرویس ثبت رخدادهای امنیتی و مدیریتی
namespace App\Services;
use App\Models\AuditLog; use Illuminate\Support\Facades\Auth;
class AuditLogger {
 public function record(string $event, ?object $subject=null, array $metadata=[]): AuditLog {
  return AuditLog::create([
   'user_id'=>Auth::id(),'event'=>$event,
   'subject_type'=>$subject?get_class($subject):null,'subject_id'=>$subject?->getKey(),
   'metadata'=>$metadata,'ip_address'=>request()->ip(),'user_agent'=>substr((string)request()->userAgent(),0,1000)
  ]);
 }
}