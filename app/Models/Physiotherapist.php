<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Physiotherapist extends Model
{
    use HasFactory;

    protected $table = 'physiotherapists';

    protected $fillable = [
        'user_id',
        'name',
        'last_name',
        'phone',
        'email',
        'specialty_id',
        'numero_colegiado',
        'password',
        'horario_inicio',
        'horario_fin',
    ];

    protected $hidden = ['password'];


    // Relación con user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con specialty
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    // Un physiotherapist tiene muchas appointments públicas
    public function citasPublicas()
    {
        return $this->hasMany(CitaPublica::class);
    }

    // Un physiotherapist tiene muchas appointments (antiguo sistema)
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    // Un physiotherapist tiene muchos schedules
public function schedules()
{
    return $this->hasMany(Schedule::class);
}

}
