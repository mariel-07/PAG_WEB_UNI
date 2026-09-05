<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';

    protected $primaryKey = 'id';

    protected $fillable = [
        'codHorario',
        'codDocente',
        'fecha',
        'codCurso',
        'secCurso',
        'teopra',
        'hora',
        'horEntrada',
        'horSalida',
        'semestre',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
        'horEntrada' => 'datetime:H:i',
        'horSalida' => 'datetime:H:i',
    ];
}