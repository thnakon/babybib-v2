<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'authors',
        'type',
        'year',
        'doi',
        'isbn',
        'url',
        'publisher',
        'journal_name',
        'volume',
        'issue',
        'pages',
        'edition',
        'abstract',
        'notes',
        'tags',
        'sort_order',
    ];

    protected $casts = [
        'authors' => 'array',
        'tags' => 'array',
    ];

    /**
     * Get the user that owns the reference.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the projects that contain this reference.
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class);
    }

    /**
     * Format authors as a string for display.
     */
    public function getFormattedAuthorsAttribute(): string
    {
        if (!$this->authors || count($this->authors) === 0) {
            return 'Unknown Author';
        }

        if (count($this->authors) === 1) {
            return $this->authors[0];
        }

        if (count($this->authors) === 2) {
            return $this->authors[0] . ' & ' . $this->authors[1];
        }

        return $this->authors[0] . ' et al.';
    }

    /**
     * Get the folders that contain this reference.
     */
    public function folders(): BelongsToMany
    {
        return $this->belongsToMany(Folder::class, 'folder_reference')->withTimestamps();
    }
}
