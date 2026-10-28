<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';
    protected $primaryKey = 'codhorario';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'codhorario',
        'semestre',
        'dia',
        'hora',
        'codcurso',
        'seccurso',
        'teopra',
        'codaula',
        'tope',
        'coddocente',
        'estado',
        'fecregistrado',
        'fecmodificacion',
    ];

    // Compatibilidad camelCase para frontend / JSON
    public function getCodHorarioAttribute() { return $this->attributes['codhorario'] ?? $this->attributes['codHorario'] ?? null; }
    public function getCodCursoAttribute() { return $this->attributes['codcurso'] ?? $this->attributes['codCurso'] ?? null; }
    public function getSecCursoAttribute() { return $this->attributes['seccurso'] ?? $this->attributes['secCurso'] ?? null; }
    public function getCodAulaAttribute() { return $this->attributes['codaula'] ?? $this->attributes['codAula'] ?? null; }
    public function getCodDocenteAttribute() { return $this->attributes['coddocente'] ?? $this->attributes['codDocente'] ?? null; }
    public function getFecRegistradoAttribute() { return $this->attributes['fecregistrado'] ?? $this->attributes['fecRegistrado'] ?? null; }
    public function getFecModificacionAttribute() { return $this->attributes['fecmodificacion'] ?? $this->attributes['fecModificacion'] ?? null; }

    // Mutadores para aceptar asignación en camelCase
    public function setCodHorarioAttribute($val) { $this->attributes['codhorario'] = $val; }
    public function setCodCursoAttribute($val) { $this->attributes['codcurso'] = $val; }
    public function setSecCursoAttribute($val) { $this->attributes['seccurso'] = $val; }
    public function setCodAulaAttribute($val) { $this->attributes['codaula'] = $val; }
    public function setCodDocenteAttribute($val) { $this->attributes['coddocente'] = $val; }
    public function setFecRegistradoAttribute($val) { $this->attributes['fecregistrado'] = $val; }
    public function setFecModificacionAttribute($val) { $this->attributes['fecmodificacion'] = $val; }

    // Relación con las matrículas asociadas a este curso y sección
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'codcurso', 'codcurso');
    }
}
