<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'sender_id',
        'title',
        'message',
        'data',
        'created_at',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
    ];

    // Override the default date serialization for created_at
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::createFromTimestamp($value);
    }

    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = $value instanceof \Carbon\Carbon
            ? $value->timestamp
            : \Carbon\Carbon::parse($value)->timestamp;
    }

    // Define a custom "read" status since read_at is missing
    public function getReadAtAttribute()
    {
        return isset($this->data['read']) && $this->data['read'] ? now() : null;
    }

    // Relationship to the user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper method to check if notification is read
    public function isRead()
    {
        return isset($this->data['read']) && $this->data['read'] === true;
    }

    // Helper method to mark notification as read
    public function markAsRead()
    {
        $data = $this->data ?? [];
        $data['read'] = true;
        $this->data = $data;
        return $this->save();
    }

    // Helper method to mark notification as unread
    public function markAsUnread()
    {
        $data = $this->data ?? [];
        $data['read'] = false;
        $this->data = $data;
        return $this->save();
    }
}