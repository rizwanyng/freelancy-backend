<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'project_id',
        'title',
        'is_completed',
        'total_seconds',
        'is_running',
        'last_start_time',
        'daily_tracked',
        'status',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'is_running' => 'boolean',
        'total_seconds' => 'integer',
        'last_start_time' => 'integer',
        'daily_tracked' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
