<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('horarios', function (Blueprint $table) {
        $table->id('codHorario');
        $table->string('semestre');
        $table->string('dia'); // LU, MA, MI, JU, VI, SA, DO
        $table->string('hora'); // Ejm: "07-11"
        $table->string('codCurso');
        $table->string('secCurso');
        $table->string('teopra'); // T, P, L
        $table->string('codAula')->nullable();
        $table->integer('tope')->default(30);
        $table->string('codDocente');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
