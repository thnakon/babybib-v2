<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    protected $fillable = [
        'visit_date',
        'visit_count',
        'unique_visitors',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];
}
