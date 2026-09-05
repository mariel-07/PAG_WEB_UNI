<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocenteFormacion extends Model
{
    use HasFactory;

    protected $table = 'docente_formacions';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'docente_id',
        'formacionProfesional',
        'pais',
        'universidad',
        'especialidad',
        'fecInicio',
        'fecFinal',
        'gradoObtenido',
        'rutaDocumento',
        'observacion',
    ];

    protected $casts = [
        'fecInicio' => 'date:Y-m-d',
        'fecFinal' => 'date:Y-m-d',
    ];

    public function docente()
    {
        return $this->belongsTo(
            Docente::class,
            'docente_id',
            'id'
        );
    }
}

