<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'package_id',
        'receive',
        'next_due',
        'run',
        'status'
    ];

    // The User It Belongs to
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // The User It Belongs to
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
