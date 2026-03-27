<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'ip_address',
        'attempts',
        'last_attempt',
    ];

    protected $casts = [
        'last_attempt' => 'datetime',
    ];
}
