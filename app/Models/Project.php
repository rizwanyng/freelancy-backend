<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'client_id',
        'name',
        'client_name',
        'budget',
        'status',
        'deadline',
        'estimated_hours',
        'currency',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'deadline' => 'datetime',
        'estimated_hours' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
