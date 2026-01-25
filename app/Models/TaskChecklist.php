<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskChecklist extends Model
{
    protected $fillable = [
        'task_id',
        'title',
        'is_completed',
        'position',
        'assigned_to'
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
