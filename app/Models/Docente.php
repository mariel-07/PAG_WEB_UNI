<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $table = 'docentes';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'codDocente',
        'dni',
        'apePaterno',
        'apeMaterno',
        'nombres',
        'sexo',
        'fecNacimiento',
        'telefono',
        'celular',
        'email',
        'ruc',
        'libMilitar',
        'breveteCategoria',
        'vehiculoInfo',
        'estadoCivil',
        'fecMatrimonio',
        'grupoSanguineo',
        'paisNacimiento',
        'departamentoNacimiento',
        'provinciaNacimiento',
        'distritoNacimiento',
        'direccionExtranjeraNac',
        'depAcademico',
        'dedicacion',
        'condicion',
        'estado',
    ];

    protected $casts = [
        'fecNacimiento' => 'date:Y-m-d',
        'fecMatrimonio' => 'date:Y-m-d',
    ];


    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    public function domicilios()
    {
        return $this->hasMany(
            DocenteDomicilio::class,
            'docente_id',
            'id'
        );
    }

    public function familiares()
    {
        return $this->hasMany(
            DocenteFamiliar::class,
            'docente_id',
            'id'
        );
    }

    public function formaciones()
    {
        return $this->hasMany(
            DocenteFormacion::class,
            'docente_id',
            'id'
        );
    }
}
