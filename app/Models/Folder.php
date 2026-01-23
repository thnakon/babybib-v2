<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'name',
        'color',
    ];

    /**
     * Get the project that owns the folder.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user that owns the folder.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the references in this folder.
     */
    public function references()
    {
        return $this->belongsToMany(Reference::class, 'folder_reference')->withTimestamps();
    }
}
