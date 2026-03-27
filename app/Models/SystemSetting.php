<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    const CREATED_AT = null;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'description',
    ];
}
