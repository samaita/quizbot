<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTelegram extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'users_telegram';

    protected $fillable = [
        'name',
        'telegram_chat_id',
    ];

    protected $dates = ['deleted_at'];

    // If you want to establish a relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}