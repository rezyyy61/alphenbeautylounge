<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'date',
        'message',
        'show_day_before',
        'expires_at',
    ];

    protected $casts = [
        'date' => 'date',
        'expires_at' => 'date',
        'show_day_before' => 'boolean',
    ];
}
