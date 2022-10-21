<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'to',
        'pl_id',
        'pl_rc_id',
        'amount',
        'pop',
        'status'
    ];


    // The Parent Plegde to a Donation
    public function pledge()
    {
        return $this->belongsTo(Pledge::class, 'pl_id', 'id');
    }

    // The Parent Plegde Receptor
    public function pledge_receptor()
    {
        return $this->belongsTo(PledgeReceiver::class, 'pl_rc_id', 'id');
    }

    // The Donation Receiver
    public function receiver()
    {
        return $this->belongsTo(User::class, 'to', 'id');
    }

    // The Donation Receiver Bank Details
    public function rec_bank()
    {
        return $this->belongsTo(Bank::class, 'to', 'user_id');
    }


    // The Donor
    public function donor()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
}
