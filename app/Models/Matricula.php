<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matricula';
    public $timestamps = false;

    protected $fillable = [
        'codalumno',
        'codcurso',
        'seccurso',
        'semestre',
    ];

    // Compatibilidad camelCase
    public function getCodAlumnoAttribute() { return $this->attributes['codalumno'] ?? $this->attributes['codAlumno'] ?? null; }
    public function getCodCursoAttribute() { return $this->attributes['codcurso'] ?? $this->attributes['codCurso'] ?? null; }
    public function getSecCursoAttribute() { return $this->attributes['seccurso'] ?? $this->attributes['secCurso'] ?? null; }

    public function setCodAlumnoAttribute($val) { $this->attributes['codalumno'] = $val; }
    public function setCodCursoAttribute($val) { $this->attributes['codcurso'] = $val; }
    public function setSecCursoAttribute($val) { $this->attributes['seccurso'] = $val; }
}
