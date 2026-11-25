<?php
// مدل پیام تیکت
namespace App\Models; use Illuminate\Database\Eloquent\Model;
class SupportMessage extends Model {
 protected $fillable=['ticket_id','user_id','body'];
 public function ticket(){return $this->belongsTo(SupportTicket::class,'ticket_id');}
}