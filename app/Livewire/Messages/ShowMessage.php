<?php

namespace App\Livewire\Messages;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Message;

#[Layout('layouts.app')]
class ShowMessage extends Component
{
    public Message $message;

    public function mount(int $id): void
    {
        $message = Message::with(['sender', 'recipient', 'replies.sender', 'attachments'])->findOrFail($id);
        $userId = auth()->id();

        // Seguridad: solo el remitente o el destinatario pueden leerlo
        if ($message->sender_id !== $userId && $message->recipient_id !== $userId) {
            abort(403, 'Acceso denegado: no tienes permisos para ver este mensaje.');
        }

        // Si fue eliminado por el usuario actual, devolver 404
        if (($message->sender_id === $userId && $message->deleted_by_sender) ||
            ($message->recipient_id === $userId && $message->deleted_by_recipient)) {
            abort(404, 'El mensaje ha sido eliminado.');
        }

        // Marcar como leído si el usuario actual es el destinatario
        if ($message->recipient_id === $userId && !$message->isRead()) {
            $message->markAsRead();
        }

        $this->message = $message;
    }

    public function toggleRead(): void
    {
        if ($this->message->recipient_id === auth()->id()) {
            $this->message->isRead() ? $this->message->markAsUnread() : $this->message->markAsRead();
            $this->message->refresh();
        }
    }

    public function deleteMessage()
    {
        $userId = auth()->id();

        if ($this->message->sender_id === $userId) {
            $this->message->update(['deleted_by_sender' => true]);
        }
        if ($this->message->recipient_id === $userId) {
            $this->message->update(['deleted_by_recipient' => true]);
        }
        if ($this->message->deleted_by_sender && $this->message->deleted_by_recipient) {
            $this->message->delete();
        }

        session()->flash('success', 'Mensaje eliminado.');
        return redirect()->route('messages.inbox');
    }

    public function render()
    {
        return view('livewire.messages.show-message', [
            'message' => $this->message,
        ]);
    }
}

