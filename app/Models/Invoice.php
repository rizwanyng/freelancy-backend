<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'client_id',
        'project_id',
        'client_name',
        'amount',
        'date',
        'due_date',
        'status',
        'is_external',
        'currency',
        'is_gst_enabled',
        'gst_percentage',
        'description',
        'uid',
        'updated_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gst_percentage' => 'decimal:2',
        'date' => 'datetime',
        'due_date' => 'datetime',
        'is_external' => 'boolean',
        'is_gst_enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
