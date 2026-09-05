<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración.
     */
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();

            // Datos del horario
            $table->unsignedBigInteger('codHorario')->nullable();

            // Datos del docente
            $table->string('codDocente', 20);

            // Datos de la asistencia
            $table->date('fecha');
            $table->string('codCurso', 50);
            $table->string('secCurso', 20);
            $table->string('teopra', 10)->nullable();
            $table->string('hora', 50)->nullable();

            // Hora de entrada y salida
            $table->time('horEntrada')->nullable();
            $table->time('horSalida')->nullable();

            // Semestre
            $table->string('semestre', 20);

            // Estado de asistencia
            $table->string('estado', 30);

            // Fechas de Laravel
            $table->timestamps();

            // Índices para mejorar las búsquedas
            $table->index('codHorario');
            $table->index('codDocente');
            $table->index('fecha');
            $table->index('semestre');
            $table->index('codCurso');
        });
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};