<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'fullname',
        'email',
        'email_verified_at',
        'password',
        'role',
        'is_premium',
        'department',
        'organization',
        'created_by',
        'bio',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_premium' => 'boolean',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(self::class, 'created_by');
    }

    public function createdUsers()
    {
        return $this->hasMany(self::class, 'created_by');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPremium(): bool
    {
        return (bool) $this->is_premium;
    }

    public function canManageOrganization(): bool
    {
        return $this->isAdmin() || $this->isPremium();
    }

    public function isVip(): bool
    {
        return $this->canManageOrganization();
    }

    public function todos()
    {
        return $this->hasMany(Todo::class);
    }

    public function assignedTodos()
    {
        return $this->hasMany(Todo::class, 'assigned_by');
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function requestedReports()
    {
        return $this->hasMany(Report::class, 'requested_by');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function organizationMembers()
    {
        return $this->hasMany(self::class, 'created_by');
    }
}
