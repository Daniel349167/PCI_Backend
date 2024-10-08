<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Damage extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'time',
        'image',
        'type',
        'severity',
        'amount',
        'sample_id',
    ];

    protected $hidden = [
        'sample_id',
    ];

    protected $casts = [
        'time' => 'datetime',
    ];

    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }
}
