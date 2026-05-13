<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Specialty extends Model
{
    use HasFactory;

    protected $table = 'specialties';

    protected $fillable = [
        'name',
        'descripcion'
    ];

    // Una specialty tiene muchos physiotherapists
    public function physiotherapists()
    {
        return $this->hasMany(Physiotherapist::class);
    }
}
