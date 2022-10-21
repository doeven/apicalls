<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'img',
        'min',
        'max',
        'percent',
        'run',
        'time_hours',
        'bonus',
        'status'
    ];
}
