<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureSetting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'label',
        'is_enabled',
        'category',
        'description',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];
}
