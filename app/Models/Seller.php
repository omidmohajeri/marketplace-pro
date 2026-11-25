<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends Model
{
    protected $fillable = ['user_id','store_name','slug','description','status','commission_rate'];
    protected function casts(): array { return ['commission_rate' => 'decimal:2']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function products(): HasMany { return $this->hasMany(Product::class); }
}
