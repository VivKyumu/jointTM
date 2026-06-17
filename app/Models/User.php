<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Task;
use App\Models\Status;
use Illuminate\Support\Facades\Hash;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'avatar',
        'photo',
        'role',
        'password',
        'is_admin',
        'status',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'status' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->is_admin || $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff' || $this->role === 'user' || !$this->role;
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }

    // In User.php
    public function status()
{
    return $this->belongsTo(Status::class);
}
 
public function getGravatarUrlAttribute(): string
{
    $email = strtolower(trim($this->email));
    $hash = md5($email);
    return "https://www.gravatar.com/avatar/{$hash}?s=200&d=identicon";
}
}
