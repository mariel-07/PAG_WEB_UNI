<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Ejecutar la migración.
     */
    public function up(): void {
        if (!Schema::hasTable('asistencia_alumnos')) {
            Schema::create('asistencia_alumnos', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('codHorario')->nullable();
                $table->string('codDocente', 20);
                $table->string('codAlumno', 20);
                $table->date('fecha');
                $table->string('codCurso', 50);
                $table->string('secCurso', 20);
                $table->string('semestre', 20);
                $table->string('estado', 30);
                $table->text('observacion')->nullable();
                $table->timestamps();

                $table->index(['codAlumno', 'fecha']);
                $table->index(['codCurso', 'secCurso', 'semestre']);
            });
        }
    }

    /**
     * Revertir la migración.
     */
    public function down(): void {
        Schema::dropIfExists('asistencia_alumnos');
    }
};
