<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTree extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'left_paid',
        'right_paid',
        'left_free',
        'right_free',
        'left_bv',
        'right_bv',
    ];
}
