<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'body', 'is_read', 'type', 'metadata'];

    protected $casts = [
        'is_read' => 'boolean',
        'metadata' => 'array',
    ];

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function broadcast($title, $body, $type = 'info')
    {
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            self::create([
                'user_id' => $user->id,
                'title' => $title,
                'body' => $body,
                'type' => $type,
            ]);
        }
    }
}