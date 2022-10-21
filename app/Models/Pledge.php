<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pledge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'sent',
        'pack',
        'status'
    ];


    // All the Donations under this pledge
    public function donations()
    {
        return $this->hasMany(Donation::class, 'pl_id', 'id');
    }

    // This Pledge is made by
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
