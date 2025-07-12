<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    use HasFactory;

    protected $table = 'quizzes_questions'; 

    protected $fillable = [
        'quiz_id', 'creator_id', 'grade', 'type', 'image', 'video','order', 'question', 'created_at', 'updated_at','explanation'
    ];

    public $timestamps = false; // Disable automatic timestamps

    protected $casts = [
        'created_at' => 'integer',
        'updated_at' => 'integer',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class,'quiz_id');
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function answers()
    {
        return $this->hasMany(Questions_answers::class, 'question_id');
    }
    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'question_id');
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