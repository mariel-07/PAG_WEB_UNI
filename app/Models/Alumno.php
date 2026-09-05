<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $table = 'alumnos';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'codAlumno',
        'apePaterno',
        'apeMaterno',
        'nombres',
        'especialidad',
        'celular',
        'email',
    ];
}

