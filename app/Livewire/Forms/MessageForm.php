<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class MessageForm extends Form
{
    /**
     * Lista de IDs de usuarios destinatarios seleccionados con Select2
     * @var array<int>
     */
    public array $recipients = [];

    public string $subject = '';
    public string $body = '';
    public ?int $parent_id = null;

    /**
     * Reglas de validación declarativas
     */
    public function rules(): array
    {
        return [
            'recipients'   => ['required', 'array', 'min:1'],
            'recipients.*' => [
                'required',
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ((int)$value === (int)auth()->id()) {
                        $fail('No puedes enviarte un mensaje a ti mismo.');
                    }
                },
            ],
            'subject'   => ['required', 'string', 'min:3', 'max:150'],
            'body'      => ['required', 'string', 'min:10', 'max:5000'],
            'parent_id' => ['nullable', 'exists:messages,id'],
        ];
    }

    /**
     * Mensajes de error personalizados
     */
    public function messages(): array
    {
        return [
            'recipients.required' => 'Debe seleccionar al menos un destinatario.',
            'recipients.min'      => 'Debe seleccionar al menos un destinatario.',
            'recipients.*.exists' => 'Uno de los destinatarios seleccionados no existe en el sistema.',
            'subject.required'    => 'El asunto del mensaje es obligatorio.',
            'subject.min'         => 'El asunto debe tener al menos :min caracteres.',
            'subject.max'         => 'El asunto no debe superar los :max caracteres.',
            'body.required'       => 'El contenido del mensaje no puede estar vacío.',
            'body.min'            => 'El mensaje debe tener al menos :min caracteres.',
            'body.max'            => 'El contenido excede el límite permitido.',
        ];
    }

    /**
     * Nombres amigables de atributos
     */
    public function validationAttributes(): array
    {
        return [
            'recipients'   => 'destinatarios',
            'recipients.*' => 'destinatario',
            'subject'      => 'asunto',
            'body'         => 'mensaje',
        ];
    }

    /**
     * Almacena el mensaje para cada destinatario seleccionado y adjunta los archivos si existen.
     *
     * @param array $attachmentsData Metadatos de archivos subidos
     * @return array<Message> Lista de mensajes creados
     */
    public function store(array $attachmentsData = []): array
    {
        $this->validate();

        $createdMessages = [];
        $uniqueRecipients = array_unique(array_map('intval', $this->recipients));

        DB::transaction(function () use ($uniqueRecipients, $attachmentsData, &$createdMessages) {
            foreach ($uniqueRecipients as $recipientId) {
                $message = Message::create([
                    'sender_id'    => auth()->id(),
                    'recipient_id' => $recipientId,
                    'subject'      => strip_tags(trim($this->subject)),
                    'body'         => trim($this->body),
                    'parent_id'    => $this->parent_id,
                ]);

                // Asociar cada archivo adjunto a este mensaje
                foreach ($attachmentsData as $attachment) {
                    $message->attachments()->create([
                        'original_name' => $attachment['original_name'],
                        'file_path'     => $attachment['file_path'],
                        'file_size'     => $attachment['file_size'],
                        'mime_type'     => $attachment['mime_type'],
                    ]);
                }

                $createdMessages[] = $message;
            }
        });

        $this->reset(['recipients', 'subject', 'body', 'parent_id']);

        return $createdMessages;
    }
}
