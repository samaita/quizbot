<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_telegram_id',
        'question_id',
        'correct_answer',
        'user_answer',
        'is_correct',
        'is_answered',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'is_answered' => 'boolean',
    ];

    public function userTelegram()
    {
        return $this->belongsTo(UserTelegram::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}