<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRating extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'rating',
        'page_url',
        'user_agent',
        'ip_address',
        'session_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
