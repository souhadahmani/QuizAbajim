<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions_answers extends Model
{
    use HasFactory;

    protected $table = 'quizzes_questions_answers'; 

    protected $fillable = [
        'id', 'creator_id', 'question_id', 'answer', 'correct', 'created_at', 'updated_at'
    ];

    public $timestamps = false; // Disable automatic timestamps

    protected $casts = [
        'created_at' => 'integer',
        'updated_at' => 'integer',
    ];

    public function question()
    {
        return $this->belongsTo(Questions::class, 'question_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::createFromTimestamp($value)->toDateTimeString() : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::createFromTimestamp($value)->toDateTimeString() : null;
    }

    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = $value ? \Carbon\Carbon::parse($value)->timestamp : null;
    }

    public function setUpdatedAtAttribute($value)
    {
        $this->attributes['updated_at'] = $value ? \Carbon\Carbon::parse($value)->timestamp : null;
    }
}