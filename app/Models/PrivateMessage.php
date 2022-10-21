<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'status',
        'unread'
    ];

    // The Replies belonging to this Private Message
    public function replies()
    {
        return $this->hasMany(PrivateMessageReply::class, 'pm_id', 'id');
    }

    // The User It Belongs to
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
