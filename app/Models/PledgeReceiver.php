<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PledgeReceiver extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pack',
        'to_receive',
        'received',
        'status',
        'created_at',
        'updated_at'
    ];


    // All the Donations made to this receiver
    public function donations()
    {
        return $this->hasMany(Donation::class, 'pl_rc_id', 'id');
    }

    // This Pledget is to be received by
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
