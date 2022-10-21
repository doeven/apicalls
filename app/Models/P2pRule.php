<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P2pRule extends Model
{
    use HasFactory;

    protected $fillable = array( 
        'nplbn', 
        'nplbn_time', 
        'ref_deep_bonus', 
        'guiders_bonus', 
        'currency', 
        'symbol'
    );

}
