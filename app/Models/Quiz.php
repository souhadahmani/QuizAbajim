<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $table = 'quizzes';

    protected $fillable = [
        'creator_id', 'chapter_id', 'title','type','time', 'attempt', 'pass_mark','subject_id','school_level_id',
        'status', 'total_mark', 'certificate', 'created_at', 'updated_at', 'level_id', 'matiere_id','difficulty_level'
    ];

    public $timestamps = false; // Disable automatic timestamp management

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Cast created_at and updated_at as integers
    protected $casts = [
        'created_at' => 'integer',
        'updated_at' => 'integer',
    ];

    public function questions()
    {
        return $this->hasMany(Questions::class, 'quiz_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function answers()
    {
        return $this->hasMany(Questions_answers::class);
    }
    public function schoolLevel()
    {
        return $this->belongsTo(SchoolLevel::class, 'school_level_id');
    }
    public function level()
    {
        return $this->belongsTo(Schoollevel::class, 'level_id',);
    }
    public function subject()
    {
        return $this->belongsTo(Materials::class, 'subject_id');
    }
    public function matiere()
    {
        return $this->belongsTo(Materials::class, 'matiere_id');
    }

    public function materials()
    {
        return $this->belongsToMany(Materials::class, 'material_quiz', 'quiz_id', 'material_id')
                    ->withTimestamps();
                    
    }

    public function results()
    {
        return $this->hasMany(Q_resultat::class, 'quiz_id');
    }
    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class, 'quiz_id');
    }
    // Accessor to convert created_at to a readable date
    public function getCreatedAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::createFromTimestamp($value)->toDateTimeString() : null;
    }

    // Accessor to convert updated_at to a readable date
    public function getUpdatedAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::createFromTimestamp($value)->toDateTimeString() : null;
    }

    // Mutator to set created_at as a Unix timestamp
    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = $value ? \Carbon\Carbon::parse($value)->timestamp : null;
    }

    // Mutator to set updated_at as a Unix timestamp
    public function setUpdatedAtAttribute($value)
    {
        $this->attributes['updated_at'] = $value ? \Carbon\Carbon::parse($value)->timestamp : null;
    }
}