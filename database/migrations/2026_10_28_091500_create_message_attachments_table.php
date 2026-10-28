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
        if (!Schema::hasTable('message_attachments')) {
            Schema::create('message_attachments', function (Blueprint $table) {
                $table->id();

                // Mensaje al que pertenece el adjunto
                $table->foreignId('message_id')
                    ->constrained('messages')
                    ->cascadeOnDelete();

                // Metadatos del archivo
                $table->string('original_name');
                $table->string('file_path');
                $table->unsignedBigInteger('file_size'); // Tamaño en bytes
                $table->string('mime_type');

                $table->timestamps();

                // Índice para consultas por mensaje
                $table->index('message_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_attachments');
    }
};

