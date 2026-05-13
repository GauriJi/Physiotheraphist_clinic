<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'nombre_rol',
        'descripcion'
    ];

    // Un role puede tener muchos users
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
