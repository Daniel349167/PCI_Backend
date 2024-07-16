<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'time',
        'image',
        'type',
        'severity',
        'quanity',
        'sample_id',
    ];

    protected $hidden = [
        'sample_id',
    ];

    protected $casts = [
        'time' => 'datetime',
    ];
}
