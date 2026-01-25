<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'assigned_to',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'position',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments()
    {
        return $this->morphMany(ProjectComment::class, 'commentable');
    }

    public function checklists()
    {
        return $this->hasMany(TaskChecklist::class)->orderBy('position');
    }

    public function attachments()
    {
        return $this->hasMany(ProjectFile::class);
    }

    public function activities()
    {
        return $this->hasMany(TaskActivity::class)->latest();
    }
}
