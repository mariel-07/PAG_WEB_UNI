<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'subject',
        'body',
        'read_at',
        'deleted_by_sender',
        'deleted_by_recipient',
        'parent_id',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'deleted_by_sender' => 'boolean',
            'deleted_by_recipient' => 'boolean',
        ];
    }

    // ==========================================
    // RELACIONES ELOQUENT
    // ==========================================

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Message::class, 'parent_id')->oldest();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(MessageAttachment::class, 'message_id');
    }

    // ==========================================
    // SCOPES (CONSULTAS REUTILIZABLES)
    // ==========================================

    /**
     * Mensajes en bandeja de entrada para un usuario
     */
    public function scopeInbox(Builder $query, int $userId): Builder
    {
        return $query->where('recipient_id', $userId)
            ->where('deleted_by_recipient', false);
    }

    /**
     * Mensajes enviados por un usuario
     */
    public function scopeSent(Builder $query, int $userId): Builder
    {
        return $query->where('sender_id', $userId)
            ->where('deleted_by_sender', false);
    }

    /**
     * Solo mensajes no leídos
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->whereNull('read_at');
    }

    // ==========================================
    // MÉTODOS DE NEGOCIO Y ESTADO
    // ==========================================

    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    public function markAsRead(): bool
    {
        if (!$this->isRead()) {
            return $this->update(['read_at' => now()]);
        }
        return false;
    }

    public function markAsUnread(): bool
    {
        if ($this->isRead()) {
            return $this->update(['read_at' => null]);
        }
        return false;
    }
}

