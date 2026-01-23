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
     * Get the count of references in this project.
     */
    public function getReferenceCountAttribute(): int
    {
        return $this->references()->whereDoesntHave('folders')->count();
    }
}
