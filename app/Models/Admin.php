<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin';
    protected $primaryKey = 'id_admin';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'is_owner',
        'can_manage_products',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'password' => 'hashed',
        'is_owner' => 'boolean',
        'can_manage_products' => 'boolean',
    ];

    // Methods sesuai class diagram
    public function canManageProducts(): bool
    {
        return $this->can_manage_products || $this->is_owner;
    }

    public function isOwner(): bool
    {
        return $this->is_owner;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
