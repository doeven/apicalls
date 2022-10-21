<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationPack extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'img',
        'min',
        'max',
        'percent',
        'time_days',
        'bonus',
        'status'
    ];
}
