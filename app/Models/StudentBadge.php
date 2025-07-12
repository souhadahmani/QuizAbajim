<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentBadge extends Model
{
    protected $fillable = [
        'user_id',
        'badge_type',
        'badge_name',
        'description',
        'awarded_at',
        'result_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function result()
    {
        return $this->belongsTo(Q_resultat::class, 'result_id');
    }
}