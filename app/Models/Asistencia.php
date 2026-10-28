<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';

    protected $primaryKey = 'id';

    protected $fillable = [
        'codhorario',
        'coddocente',
        'fecha',
        'codcurso',
        'seccurso',
        'teopra',
        'hora',
        'horentrada',
        'horsalida',
        'semestre',
        'semana_id',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Compatibilidad camelCase para frontend / controladores
    public function getCodHorarioAttribute() { return $this->attributes['codhorario'] ?? $this->attributes['codHorario'] ?? null; }
    public function getCodDocenteAttribute() { return $this->attributes['coddocente'] ?? $this->attributes['codDocente'] ?? null; }
    public function getCodCursoAttribute() { return $this->attributes['codcurso'] ?? $this->attributes['codCurso'] ?? null; }
    public function getSecCursoAttribute() { return $this->attributes['seccurso'] ?? $this->attributes['secCurso'] ?? null; }
    public function getHorEntradaAttribute() { return $this->attributes['horentrada'] ?? $this->attributes['horEntrada'] ?? null; }
    public function getHorSalidaAttribute() { return $this->attributes['horsalida'] ?? $this->attributes['horSalida'] ?? null; }

    public function setCodHorarioAttribute($val) { $this->attributes['codhorario'] = $val; }
    public function setCodDocenteAttribute($val) { $this->attributes['coddocente'] = $val; }
    public function setCodCursoAttribute($val) { $this->attributes['codcurso'] = $val; }
    public function setSecCursoAttribute($val) { $this->attributes['seccurso'] = $val; }
    public function setHorEntradaAttribute($val) { $this->attributes['horentrada'] = $val; }
    public function setHorSalidaAttribute($val) { $this->attributes['horsalida'] = $val; }
}
