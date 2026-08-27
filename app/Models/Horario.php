<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';
    protected $primaryKey = 'codHorario';
    public $incrementing = true;
    
    // Desactivamos los timestamps por defecto de Laravel para mapear tus campos exactos
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

    protected $casts = [
        'fecRegistrado' => 'datetime',
        'fecModificacion' => 'datetime',
        'codAula' => 'integer',
        'tope' => 'integer',
        'estado' => 'integer',
    ];
}