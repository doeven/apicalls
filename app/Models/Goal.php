<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'goal',
        'cost',
        'image',
        'date'
    ];

    // The User It Belongs to
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
