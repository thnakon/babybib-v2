<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'citation_style',
        'color',
        'icon',
        'sort_order',
        'status',
        'priority',
        'due_date',
        'progress',
        'visibility',
        'invite_token',
        'invite_role',
    ];

    /**
     * Get the user that owns the project.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the references in this project.
     */
    public function references(): BelongsToMany
    {
        return $this->belongsToMany(Reference::class)->withTimestamps();
    }

    /**
     * Get the folders in this project.
     */
    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    /**
     * Get the tasks in this project.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class)->orderBy('position');
    }

    /**
     * Get the members of this project.
     */
    public function members()
    {
        return $this->hasMany(ProjectMember::class);
    }

    /**
     * Get the files in this project.
     */
    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }

    /**
     * Get the comments for this project.
     */
    public function comments()
    {
        return $this->morphMany(ProjectComment::class, 'commentable');
    }

    /**
     * Get the count of references in this project.
     */
    public function getReferenceCountAttribute(): int
    {
        return $this->references()->count();
    }

    /**
     * Calculate project progress based on tasks.
     */
    public function calculateProgress(): int
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) return 0;

        $completedTasks = $this->tasks()->where('status', 'done')->count();
        return (int) (($completedTasks / $totalTasks) * 100);
    }
}
