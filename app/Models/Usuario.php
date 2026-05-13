<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'phone',
        'role_id'
    ];

    protected $hidden = ['password'];

    // Un user pertenece a un role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // Si este user es physiotherapist
    public function physiotherapist()
    {
        return $this->hasOne(Physiotherapist::class);
    }
}
