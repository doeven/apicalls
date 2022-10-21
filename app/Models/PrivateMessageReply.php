<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivateMessageReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'pm_id',
        'user_id',
        'reply'
    ];


    public function message()
    {
        return $this->belongsTo(PrivateMessage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
