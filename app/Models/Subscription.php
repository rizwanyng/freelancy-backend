<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'amount',
        'currency',
        'billing_cycle',
        'next_billing_date',
        'category',
        'is_active',
        'notes',
        'uid',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'next_billing_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
