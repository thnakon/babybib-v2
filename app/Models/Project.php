<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'color',
        'bibliography_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bibliographies()
    {
        return $this->hasMany(Bibliography::class);
    }
}
