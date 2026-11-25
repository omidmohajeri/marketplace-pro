<?php
// مدل صفحه یا بنر سایت
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SiteContent extends Model {
 protected $fillable=['type','title','slug','body','image_path','target_url','starts_at','ends_at','is_active'];
 protected $casts=['starts_at'=>'datetime','ends_at'=>'datetime','is_active'=>'boolean'];
}