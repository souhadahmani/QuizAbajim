<?php

namespace App\Models;
use App\Models\Section;  
use Illuminate\Database\Eloquent\Model;

class Materials extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'section_id'];

    public function teachers()
    {
        return $this->hasMany(UserMatiere::class, 'matiere_id');
    }
    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'subject_id');
    }
    public function section()
     {
         return $this->belongsTo(Section::class);
     }

}