<?php

namespace App\Models;
use App\Models\Quiz;
use App\Models\Questions;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $table = 'student_answers';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = ['user_id', 'quiz_id', 'question_id', 'selected_answer', 'is_correct'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function question()
    {
        return $this->belongsTo(Questions::class, 'question_id');
    }
}