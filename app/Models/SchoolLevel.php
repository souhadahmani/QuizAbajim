<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section;
class SchoolLevel extends Model
{
    protected $table = 'school_levels';
    protected $fillable = ['name'];

    public function teachers()
    {
        return $this->hasMany(UserLevel::class, 'level_id');
    }
    public function matieres()
    {
        return $this->hasMany(UserMatiere::class, 'level_id');
    }
    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'school_level_id');
    }
    public function section()
    {
        return $this->hasMany(Section::class, 'level_id');
    }
}