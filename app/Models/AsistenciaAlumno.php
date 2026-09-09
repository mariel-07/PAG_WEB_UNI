<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsistenciaAlumno extends Model
{
    protected $table = 'asistencia_alumnos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'codHorario',
        'codDocente',
        'codAlumno',
        'fecha',
        'codCurso',
        'secCurso',
        'semestre',
        'estado',
        'observacion',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];
}