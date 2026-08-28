<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matricula'; // O 'matriculas' según la nombraste en la base de datos
    public $timestamps = false;

    protected $fillable = [
        'codAlumno',
        'codCurso',
        'secCurso',
        'semestre',
    ];
}