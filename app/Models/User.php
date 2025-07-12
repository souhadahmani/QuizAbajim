<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'role_name',
        'role_id',
        'status',
        'mobile',
        'affiliate',
        'timezone',
         'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
       // 'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'password' => 'hashed',
    ];

 /*
    public function setEmailVerifiedAtAttribute($value)
    {
        if ($value === null) {
            $this->attributes['email_verified_at'] = null;
        } else {
            $this->attributes['email_verified_at'] = Carbon::now();
        }
    }
 
    public function markEmailAsVerified()
    {
        $this->forceFill([
            'email_verified_at' => Carbon::now(),
        ])->save();

        return true;
    }
*/
    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function results()
    {
        return $this->hasMany(Q_resultat::class, 'user_id');
    }


    public function hasSelectedSubjects()
    {
        return $this->subjects()->exists();
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.Models.User.' . $this->id;
    }

    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class, 'user_id');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereRaw("JSON_EXTRACT(data, '$.read') IS NULL OR JSON_EXTRACT(data, '$.read') = ?", [false]);
    }

    public function levels()
    {
        return $this->belongsToMany(SchoolLevel::class, 'user_level', 'teacher_id', 'level_id')
                    ->withTimestamps();
    }

    public function materials()
    {
        return $this->belongsToMany(Materials::class, 'user_matiere', 'teacher_id', 'matiere_id')
                    ->withTimestamps();
    }
    public function badges()
    {
        return $this->hasMany(StudentBadge::class); 
    }

}
