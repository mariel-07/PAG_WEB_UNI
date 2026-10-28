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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // Relaciones de participantes
            $table->foreignId('sender_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('recipient_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Asunto y contenido
            $table->string('subject', 150);
            $table->text('body');

            // Estado de lectura
            $table->timestamp('read_at')->nullable();

            // Eliminación independiente (Bandeja / Enviados)
            $table->boolean('deleted_by_sender')->default(false);
            $table->boolean('deleted_by_recipient')->default(false);

            // Hilo de mensajes / Respuestas
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('messages')
                ->nullOnDelete();

            $table->timestamps();

            // Índices para optimizar consultas frecuentes
            $table->index(['recipient_id', 'read_at', 'deleted_by_recipient']);
            $table->index(['sender_id', 'deleted_by_sender']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};

