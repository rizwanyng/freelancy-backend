<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailThread extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'subject',
        'from_email',
        'from_name',
        'to_email',
        'client_id',
        'project_id',
        'invoice_id',
        'snippet',
        'received_at',
        'is_read',
        'is_important',
        'labels',
        'uid',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_important' => 'boolean',
        'labels' => 'array',
        'received_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
