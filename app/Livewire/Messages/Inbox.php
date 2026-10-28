<?php

namespace App\Livewire\Messages;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Layout;
use App\Models\Message;

#[Layout('layouts.app')]
class Inbox extends Component
{
    use WithPagination;

    #[Url(as: 'carpeta')]
    public string $folder = 'inbox'; // 'inbox' o 'sent'

    #[Url(as: 'buscar')]
    public string $search = '';

    #[Url(as: 'estado')]
    public string $statusFilter = 'all'; // 'all', 'unread', 'read'

    public int $perPage = 10;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFolder(): void
    {
        $this->resetPage();
        $this->statusFilter = 'all';
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function setFolder(string $folder): void
    {
        $this->folder = in_array($folder, ['inbox', 'sent']) ? $folder : 'inbox';
        $this->resetPage();
    }

    /**
     * Alternar estado de lectura del mensaje
     */
    public function toggleRead(int $messageId): void
    {
        $message = Message::where('recipient_id', auth()->id())->find($messageId);

        if ($message) {
            if ($message->isRead()) {
                $message->markAsUnread();
                session()->flash('info', 'Mensaje marcado como no leído.');
            } else {
                $message->markAsRead();
                session()->flash('info', 'Mensaje marcado como leído.');
            }
        }
    }

    /**
     * Eliminar mensaje (borrado independiente por participante)
     */
    public function deleteMessage(int $messageId): void
    {
        $message = Message::where(function ($query) {
            $query->where('sender_id', auth()->id())
                  ->orWhere('recipient_id', auth()->id());
        })->find($messageId);

        if (!$message) {
            session()->flash('error', 'El mensaje no existe o no tienes autorización.');
            return;
        }

        if ($message->sender_id === auth()->id()) {
            $message->update(['deleted_by_sender' => true]);
        }

        if ($message->recipient_id === auth()->id()) {
            $message->update(['deleted_by_recipient' => true]);
        }

        // Si ambos usuarios lo descartaron, se purga definitivamente
        if ($message->deleted_by_sender && $message->deleted_by_recipient) {
            $message->delete();
        }

        session()->flash('success', 'Mensaje eliminado.');
        $this->dispatch('swal:alert', [
            'type' => 'success',
            'title' => 'Eliminado',
            'text' => 'El mensaje fue eliminado de tu bandeja.'
        ]);
    }

    public function render()
    {
        $userId = auth()->id();

        $query = $this->folder === 'sent'
            ? Message::sent($userId)->with('recipient')
            : Message::inbox($userId)->with('sender');

        if (!empty($this->search)) {
            $operator = \Illuminate\Support\Facades\DB::connection()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $searchTerm = '%' . $this->search . '%';
            $query->where(function ($q) use ($searchTerm, $operator) {
                $q->where('subject', $operator, $searchTerm)
                  ->orWhere('body', $operator, $searchTerm)
                  ->orWhereHas($this->folder === 'sent' ? 'recipient' : 'sender', function ($sub) use ($searchTerm, $operator) {
                      $sub->where('name', $operator, $searchTerm)
                          ->orWhere('email', $operator, $searchTerm);
                  });
            });
        }

        if ($this->folder === 'inbox') {
            if ($this->statusFilter === 'unread') {
                $query->unread();
            } elseif ($this->statusFilter === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        $messages = $query->latest()->paginate($this->perPage);

        return view('livewire.messages.inbox', [
            'messages'    => $messages,
            'unreadCount' => Message::inbox($userId)->unread()->count(),
            'inboxTotal'  => Message::inbox($userId)->count(),
            'sentTotal'   => Message::sent($userId)->count(),
        ]);
    }
}

