<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'password', 'role', 'locale'];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }
    public function sellerProfile(): HasOne { return $this->hasOne(Seller::class); }
    public function orders(): HasMany { return $this->hasMany(Order::class); }
    public function supportTickets(): HasMany { return $this->hasMany(SupportTicket::class); } // تیکت‌های پشتیبانی کاربر
}
