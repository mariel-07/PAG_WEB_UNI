<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsistenciaAlumno extends Model
{
    protected $table = 'asistencia_alumnos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'codhorario',
        'coddocente',
        'codalumno',
        'fecha',
        'codcurso',
        'seccurso',
        'semestre',
        'estado',
        'observacion',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Compatibilidad camelCase
    public function getCodHorarioAttribute() { return $this->attributes['codhorario'] ?? $this->attributes['codHorario'] ?? null; }
    public function getCodDocenteAttribute() { return $this->attributes['coddocente'] ?? $this->attributes['codDocente'] ?? null; }
    public function getCodAlumnoAttribute() { return $this->attributes['codalumno'] ?? $this->attributes['codAlumno'] ?? null; }
    public function getCodCursoAttribute() { return $this->attributes['codcurso'] ?? $this->attributes['codCurso'] ?? null; }
    public function getSecCursoAttribute() { return $this->attributes['seccurso'] ?? $this->attributes['secCurso'] ?? null; }

    public function setCodHorarioAttribute($val) { $this->attributes['codhorario'] = $val; }
    public function setCodDocenteAttribute($val) { $this->attributes['coddocente'] = $val; }
    public function setCodAlumnoAttribute($val) { $this->attributes['codalumno'] = $val; }
    public function setCodCursoAttribute($val) { $this->attributes['codcurso'] = $val; }
    public function setSecCursoAttribute($val) { $this->attributes['seccurso'] = $val; }
}
