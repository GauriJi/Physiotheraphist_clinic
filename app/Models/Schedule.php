<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'physiotherapist_id',
        'dia',
        'disponible',
        'hora_inicio',
        'hora_fin',
    ];

    // Relación con Physiotherapist
    public function physiotherapist()
    {
        return $this->belongsTo(Physiotherapist::class);
    }
}
