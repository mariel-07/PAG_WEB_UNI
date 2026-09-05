<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocenteFamiliar extends Model
{
    use HasFactory;

    protected $table = 'docente_familiares';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'docente_id',
        'apellidosNombres',
        'parentesco',
        'fecNacimiento',
        'estadoCivil',
        'nivelEstudios',
        'ocupacion',
        'celular',
        'dependeEconomicamente',
    ];

    protected $casts = [
        'fecNacimiento' => 'date:Y-m-d',
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
