<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'ticket_id',
        'title',
        'priority',
        'message',
        'status',
        'unread',
        'a_unread'
    ];

    // The Category Ticket Belongs to 
    public function category()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    // The Comments belonging to this ticket
    public function comments()
    {
        return $this->hasMany(TicketComment::class);
    }

    // The User It Belongs to
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
