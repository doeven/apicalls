<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kyc extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'front',
        'back',
        'type',
        'number',
        'status'
    ];

    // The User It Belongs to
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}