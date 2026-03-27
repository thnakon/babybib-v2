<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'visit_date',
        'visit_count',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];
}
