<?php
// مدل نظر محصول
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ProductReview extends Model {
 protected $fillable=['product_id','user_id','rating','title','body','status','verified_purchase'];
 protected $casts=['verified_purchase'=>'boolean'];
 public function product(): BelongsTo { return $this->belongsTo(Product::class); } // محصول
 public function user(): BelongsTo { return $this->belongsTo(User::class); } // نویسنده
}