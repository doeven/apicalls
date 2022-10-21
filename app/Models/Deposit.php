<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = array( 'user_id','gateway_id', 'amount', 'status', 'tx_id', 'address','btc_amount', 'fee', 'custom', 'try');

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withDefault();
    }

}
