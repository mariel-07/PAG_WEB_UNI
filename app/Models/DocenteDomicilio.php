<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocenteDomicilio extends Model
{
    use HasFactory;

    protected $table = 'docente_domicilios';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'docente_id',
        'pais',
        'departamento',
        'provincia',
        'distrito',
        'direccionExtranjera',
        'tipoVia',
        'nombreVia',
        'tipoInmueble',
        'numeroInmueble',
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
