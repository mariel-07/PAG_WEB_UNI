<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';
    protected $primaryKey = 'codHorario';
    public $timestamps = false;

    protected $fillable = [
        'semestre',
        'dia',
        'hora',
        'codCurso',
        'secCurso',
        'teopra',
        'codAula',
        'tope',
        'codDocente',
        'estado',
        'fecRegistrado',
        'fecModificacion',
    ];

    // Relación con las matrículas asociadas a este curso y sección
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'codCurso', 'codCurso')
                    ->where('secCurso', $this->secCurso)
                    ->where('semestre', $this->semestre);
    }
}