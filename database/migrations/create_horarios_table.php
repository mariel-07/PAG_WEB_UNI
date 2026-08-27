<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->integer('codHorario')->autoIncrement();
            $table->string('semestre', 10);
            $table->string('dia', 2);
            $table->string('hora', 5);
            $table->string('codCurso', 5);
            $table->string('secCurso', 20);
            $table->string('teopra', 1);
            $table->integer('codAula');
            $table->integer('tope');
            $table->string('codDocente', 10);
            $table->integer('estado');
            $table->dateTime('fecRegistrado');
            $table->dateTime('fecModificacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};