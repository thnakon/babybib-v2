<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceType extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name_th',
        'name_en',
        'category',
        'icon',
        'fields_config',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'fields_config' => 'array',
        'is_active' => 'boolean',
    ];

    public function bibliographies()
    {
        return $this->hasMany(Bibliography::class);
    }
}
