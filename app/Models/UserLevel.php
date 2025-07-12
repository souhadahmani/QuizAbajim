<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    protected $table = 'user_level';
    protected $fillable = ['teacher_id', 'level_id'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function level()
    {
        return $this->belongsTo(SchoolLevel::class, 'level_id');
    }
}
