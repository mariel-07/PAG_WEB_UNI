<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MessageAttachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'message_id',
        'original_name',
        'file_path',
        'file_size',
        'mime_type',
    ];

    /**
     * Mensaje al que pertenece el archivo adjunto
     */
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    /**
     * URL pública para visualización o descarga directa
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    /**
     * Tamaño formateado legible (KB, MB)
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = (int)$this->file_size;

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }

    /**
     * Verifica si el archivo es una imagen
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }
}

