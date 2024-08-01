<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'time',
        'image',
        'from_km',
        'from_m',
        'to_km',
        'to_m',
        'section',
        'project_id',
    ];

    protected $hidden = [
        'project_id',
    ];

    protected $casts = [
        'time' => 'datetime',
    ];
}
