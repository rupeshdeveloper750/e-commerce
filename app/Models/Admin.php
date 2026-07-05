<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasRoles;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable([
    'name',
    'email',
    'password',
    'phone',
    'profile_photo',
    'status',
])]

#[Hidden([
    'password',
    'remember_token',
])]

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'status' => 'boolean',
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }
}