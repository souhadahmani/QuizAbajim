<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMatiere extends Model
{
    protected $table = 'user_matiere';
    protected $fillable = ['teacher_id', 'matiere_id', 'level_id'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function matiere()
    {
        return $this->belongsTo(Materials::class, 'matiere_id');
    }

    public function level()
    {
        return $this->belongsTo(SchoolLevel::class, 'level_id');
    }
}