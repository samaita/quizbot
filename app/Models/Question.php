<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'question_type_id',
        'language_id',
        'question',
        'answer',
        'additional_data',
    ];

    protected $casts = [
        'additional_data' => 'array',
    ];

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}