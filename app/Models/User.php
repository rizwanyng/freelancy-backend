<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, SoftDeletes;

    public function canAccessFilament(): bool
    {
        // ONLY you (the owner) can access the dashboard.
        // Add your email here.
        $owners = [
            'rizzpathan2@gmail.com', // Your email
            'admin@admin.com',       // Any other admin email you use
        ];

        return in_array($this->email, $owners);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'plan',
        'plan_expires_at',
        'stripe_link',
        'paypal_email',
        'upi_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'plan_expires_at' => 'datetime',
    ];

    /**
     * Get the clients for the user.
     */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    /**
     * Get the projects for the user.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get the tasks for the user.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the invoices for the user.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
