<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Feedback extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'type', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //
}
