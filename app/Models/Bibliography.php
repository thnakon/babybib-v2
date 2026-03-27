<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bibliography extends Model
{
    protected $fillable = [
        'user_id',
        'resource_type_id',
        'project_id',
        'data',
        'bibliography_text',
        'citation_parenthetical',
        'citation_narrative',
        'language',
        'author_sort_key',
        'year',
        'year_suffix',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resourceType()
    {
        return $this->belongsTo(ResourceType::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
