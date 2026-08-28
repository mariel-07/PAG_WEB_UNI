<?php

// database/migrations/2026_08_28_000001_create_docentes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('docentes', function (Blueprint $table) {
            $table->id();
            $table->string('codDocente', 20)->unique();
            $table->string('dni', 15)->unique();
            $table->string('apePaterno', 100);
            $table->string('apeMaterno', 100);
            $table->string('nombres', 100);
            $table->char('sexo', 1)->nullable();
            $table->date('fecNacimiento')->nullable();
            $table->string('telefono', 50)->nullable();
            $table->string('celular', 50)->nullable();
            $table->string('email', 225)->nullable();
            $table->string('ruc', 20)->nullable();
            $table->string('libMilitar', 50)->nullable();
            $table->string('breveteCategoria', 50)->nullable();
            $table->string('vehiculoInfo', 255)->nullable();
            $table->string('estadoCivil', 50)->nullable();
            $table->date('fecMatrimonio')->nullable();
            $table->string('grupoSanguineo', 10)->nullable();
            $table->string('paisNacimiento', 100)->default('Perú');
            $table->string('departamentoNacimiento', 100)->nullable();
            $table->string('provinciaNacimiento', 100)->nullable();
            $table->string('distritoNacimiento', 100)->nullable();
            $table->string('direccionExtranjeraNac', 255)->nullable();
            $table->string('depAcademico', 50)->nullable();
            $table->string('dedicacion', 50)->nullable();
            $table->string('condicion', 100)->nullable();
            $table->char('estado', 1)->default('1');
            $table->timestamps();
        });

        Schema::create('docente_domicilios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
            $table->string('pais', 100)->default('Perú');
            $table->string('departamento', 100)->nullable();
            $table->string('provincia', 100)->nullable();
            $table->string('distrito', 100)->nullable();
            $table->string('direccionExtranjera', 255)->nullable();
            $table->string('tipoVia', 50)->nullable();
            $table->string('nombreVia', 150)->nullable();
            $table->string('tipoInmueble', 50)->nullable();
            $table->string('numeroInmueble', 50)->nullable();
            $table->timestamps();
        });

        Schema::create('docente_familiares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
            $table->string('apellidosNombres', 200);
            $table->string('parentesco', 50);
            $table->date('fecNacimiento')->nullable();
            $table->string('estadoCivil', 50)->nullable();
            $table->string('nivelEstudios', 100)->nullable();
            $table->string('ocupacion', 100)->nullable();
            $table->string('celular', 50)->nullable();
            $table->enum('dependeEconomicamente', ['Si', 'No'])->default('No');
            $table->timestamps();
        });

        Schema::create('docente_formacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('cascade');
            $table->string('formacionProfesional', 100);
            $table->string('pais', 100)->default('Perú');
            $table->string('universidad', 200);
            $table->string('especialidad', 200);
            $table->date('fecInicio')->nullable();
            $table->date('fecFinal')->nullable();
            $table->string('gradoObtenido', 100)->nullable();
            $table->string('rutaDocumento', 255)->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('docente_formacions');
        Schema::dropIfExists('docente_familiares');
        Schema::dropIfExists('docente_domicilios');
        Schema::dropIfExists('docentes');
    }
};