<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    use HasFactory;

    protected $fillable = array( 'name','img', 'min', 'max', 'fixed_fee', 'perc_fee', 'exchange','val1', 'val2', 'val3',  'currency', 'status');

}
