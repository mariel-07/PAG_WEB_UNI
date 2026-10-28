<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\MapsPostgresAttributes;

class Docente extends Model
{
    use HasFactory, MapsPostgresAttributes;

    protected $table = 'docentes';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'coddocente',
        'codDocente',
        'dni',
        'apepaterno',
        'apePaterno',
        'apematerno',
        'apeMaterno',
        'nombres',
        'sexo',
        'fecnacimiento',
        'fecNacimiento',
        'telefono',
        'celular',
        'email',
        'ruc',
        'libmilitar',
        'libMilitar',
        'brevetecategoria',
        'breveteCategoria',
        'vehiculoinfo',
        'vehiculoInfo',
        'estadocivil',
        'estadoCivil',
        'fecmatrimonio',
        'fecMatrimonio',
        'gruposanguineo',
        'grupoSanguineo',
        'paisnacimiento',
        'paisNacimiento',
        'departamentonacimiento',
        'departamentoNacimiento',
        'provincianacimiento',
        'provinciaNacimiento',
        'distritonacimiento',
        'distritoNacimiento',
        'direccionextranjeranac',
        'direccionExtranjeraNac',
        'depacademico',
        'depAcademico',
        'dedicacion',
        'condicion',
        'estado',
    ];

    protected $casts = [
        'fecnacimiento' => 'date:Y-m-d',
        'fecmatrimonio' => 'date:Y-m-d',
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
