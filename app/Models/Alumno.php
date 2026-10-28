<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'alumnos';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'codAlumno',
        'apePaterno',
        'apeMaterno',
        'nombres',
        'especialidad',
        'celular',
        'email',
    ];

    /*
    |--------------------------------------------------------------------------
    | ACCESORES COMPATIBILIDAD POSTGRESQL (SUPABASE) / MYSQL
    |--------------------------------------------------------------------------
    */
    public function getApePaternoAttribute($value)
    {
        return $value ?? $this->attributes['apepaterno'] ?? $this->attributes['apePaterno'] ?? null;
    }

    public function getApeMaternoAttribute($value)
    {
        return $value ?? $this->attributes['apematerno'] ?? $this->attributes['apeMaterno'] ?? null;
    }

    public function getCodAlumnoAttribute($value)
    {
        return $value ?? $this->attributes['codalumno'] ?? $this->attributes['codAlumno'] ?? null;
    }
}

