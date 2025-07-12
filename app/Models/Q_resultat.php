<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Q_resultat extends Model
{
    use HasFactory;

    protected $table = 'quizzes_results';

    protected $fillable = [
        'user_id',
        'quiz_id',
        'score',
        'time_taken',
        'results',
        'user_grade',
        'status',
        'created_at',
        
    
    ];

    public $timestamps = true;

    protected $casts = [
        'results' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'score' => 'integer',
        'time_taken' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(\App\Models\Quiz::class);
    }
    public function studentAnswers()
    {
        return $this->hasMany(\App\Models\StudentAnswer::class, 'quiz_id', 'quiz_id')
                    ->where('user_id', $this->user_id);
    }
    public function getCreatedAtAttribute($value)
    {
        // If $value is already a Carbon instance, use it; otherwise, parse the string
        $date = $value instanceof Carbon ? $value : Carbon::parse($value);
        return $date ? $date->format('Y-m-d H:i:s') : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        // If $value is already a Carbon instance, use it; otherwise, parse the string
        $date = $value instanceof Carbon ? $value : Carbon::parse($value);
        return $date ? $date->format('Y-m-d H:i:s') : null;
    }
}