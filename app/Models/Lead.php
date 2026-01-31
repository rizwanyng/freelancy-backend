<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'email',
        'phone',
        'company',
        'source',
        'status',
        'estimated_value',
        'notes',
        'last_contact_date',
        'uid',
        'updated_at',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'last_contact_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
