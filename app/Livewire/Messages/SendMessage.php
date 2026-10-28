<?php

namespace App\Livewire\Messages;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Livewire\Forms\MessageForm;
use App\Services\RecipientSearchService;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\UploadedFile;

#[Layout('layouts.app')]
class SendMessage extends Component
{
    use WithFileUploads;

    public MessageForm $form;

    /**
     * Archivos adjuntos temporales de Livewire
     * @var array<UploadedFile>
     */
    public $attachments = [];

    public ?int $replyToMessageId = null;

    /**
     * Opciones iniciales para el selector múltiple Select2
     */
    public array $groupedRecipients = [];

    public function mount(?int $replyTo = null, RecipientSearchService $service = null): void
    {
        $service = $service ?: app(RecipientSearchService::class);

        // Carga inicial limitada (máximo 6 por grupo) para no saturar la vista antes de buscar
        $this->groupedRecipients = $service->search(null, 6);

        // Prellenar si es respuesta a un mensaje
        if ($replyTo) {
            $original = Message::with('sender')->find($replyTo);

            if ($original && ($original->recipient_id === auth()->id() || $original->sender_id === auth()->id())) {
                $this->replyToMessageId = $original->id;
                $this->form->parent_id = $original->id;

                $targetUserId = $original->sender_id === auth()->id() 
                    ? $original->recipient_id 
                    : $original->sender_id;

                $this->form->recipients = [$targetUserId];
                $this->form->subject = str_starts_with($original->subject, 'Re: ') 
                    ? $original->subject 
                    : 'Re: ' . $original->subject;

                // Asegurar que el usuario a responder figure en las opciones del selector
                $targetUser = User::find($targetUserId);
                if ($targetUser) {
                    $existsInOptions = false;
                    foreach ($this->groupedRecipients as $group) {
                        foreach ($group as $item) {
                            if ($item['id'] === $targetUserId) {
                                $existsInOptions = true;
                                break 2;
                            }
                        }
                    }

                    if (!$existsInOptions) {
                        $this->groupedRecipients['usuarios'][] = [
                            'id'       => $targetUser->id,
                            'text'     => "[Usuario] {$targetUser->name} ({$targetUser->email})",
                            'name'     => $targetUser->name,
                            'email'    => $targetUser->email,
                            'category' => 'Usuario',
                        ];
                    }
                }
            }
        }
    }

    /**
     * Validación en tiempo real cuando cambian los campos del formulario
     */
    public function updated($propertyName): void
    {
        if (str_starts_with($propertyName, 'attachments')) {
            $this->validateAttachments();
        } else {
            $this->validateOnly($propertyName);
        }
    }

    /**
     * Valida las reglas de archivos adjuntos:
     * - Extensiones permitidas: .jpeg, .jpg, .png
     * - Cantidad máxima de archivos: 5
     * - Peso máximo por archivo: 5MB (5,242,880 bytes)
     * - Peso máximo total permitido: 25MB (26,214,400 bytes)
     */
    public function validateAttachments(): bool
    {
        $this->resetErrorBag('attachments');

        if (empty($this->attachments)) {
            return true;
        }

        // 1. Cantidad máxima de archivos
        if (count($this->attachments) > 5) {
            $this->addError('attachments', 'Solo se permite adjuntar un máximo de 5 archivos.');
            return false;
        }

        $totalBytes = 0;
        $allowedExtensions = ['jpeg', 'jpg', 'png'];
        $maxBytesPerFile = 5 * 1024 * 1024; // 5MB
        $maxTotalBytes   = 25 * 1024 * 1024; // 25MB

        foreach ($this->attachments as $index => $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            // Validar extensión
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, $allowedExtensions, true)) {
                $this->addError(
                    'attachments',
                    "El archivo '{$file->getClientOriginalName()}' tiene una extensión no permitida. Solo se aceptan: .jpeg, .jpg, .png."
                );
                return false;
            }

            // Validar peso por archivo individual (máx 5MB)
            $size = $file->getSize();
            if ($size > $maxBytesPerFile) {
                $this->addError(
                    'attachments',
                    "El archivo '{$file->getClientOriginalName()}' pesa más de 5MB permitidos."
                );
                return false;
            }

            $totalBytes += $size;
        }

        // Validar peso total combinado (máx 25MB)
        if ($totalBytes > $maxTotalBytes) {
            $this->addError(
                'attachments',
                'El peso total combinado de todos los archivos adjuntos supera el límite de 25MB.'
            );
            return false;
        }

        return true;
    }

    /**
     * Elimina un archivo adjunto de la lista antes de enviar
     */
    public function removeAttachment(int $index): void
    {
        if (isset($this->attachments[$index])) {
            unset($this->attachments[$index]);
            $this->attachments = array_values($this->attachments);
            $this->validateAttachments();
        }
    }

    /**
     * Procesar y enviar el mensaje a todos los destinatarios seleccionados
     */
    public function send()
    {
        // Validar adjuntos
        if (!$this->validateAttachments()) {
            return;
        }

        try {
            // Guardar archivos adjuntos físicamente en storage
            $attachmentsData = [];
            if (!empty($this->attachments)) {
                foreach ($this->attachments as $file) {
                    $path = $file->store('attachments/messages', 'public');
                    $attachmentsData[] = [
                        'original_name' => $file->getClientOriginalName(),
                        'file_path'     => $path,
                        'file_size'     => $file->getSize(),
                        'mime_type'     => $file->getMimeType() ?: ('image/' . $file->getClientOriginalExtension()),
                    ];
                }
            }

            // Guardar mensajes mediante el Form Object
            $messages = $this->form->store($attachmentsData);
            $count = count($messages);

            $this->attachments = [];

            $flashText = $count > 1 
                ? "¡Mensaje enviado exitosamente a los {$count} destinatarios seleccionados!"
                : "¡Mensaje enviado exitosamente!";

            session()->flash('success', $flashText);

            $this->dispatch('swal:alert', [
                'type'  => 'success',
                'title' => '¡Mensaje Enviado!',
                'text'  => $count > 1 
                    ? "Se enviaron copias individuales a {$count} destinatarios." 
                    : "El mensaje ha sido enviado correctamente."
            ]);

            return redirect()->route('messages.inbox', ['carpeta' => 'sent']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $th) {
            session()->flash('error', 'Ocurrió un error al enviar el mensaje: ' . $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.messages.send-message');
    }
}
