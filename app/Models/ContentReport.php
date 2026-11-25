<?php
// مدل گزارش تخلف با رابطه چندریختی به محتوای گزارش‌شده
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
class ContentReport extends Model {
 protected $fillable=['reporter_id','reportable_type','reportable_id','reason','details','status'];
 public function reportable(): MorphTo { return $this->morphTo(); } // نظر یا پرسش گزارش‌شده
}