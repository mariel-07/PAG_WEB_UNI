<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $table = 'docentes'; // Asegúrate de que coincida con el nombre de tu tabla en MySQL

    protected $guarded = []; // Esto permite la asignación masiva sin restricciones

    // Relaciones
    public function domicilios()
    {
        return $this->hasMany(DocenteDomicilio::class, 'docente_id');
    }

    public function familiares()
    {
        return $this->hasMany(DocenteFamiliar::class, 'docente_id');
    }

    public function formaciones()
    {
        return $this->hasMany(DocenteFormacion::class, 'docente_id');
    }
}