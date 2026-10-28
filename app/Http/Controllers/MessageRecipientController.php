<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RecipientSearchService;
use App\Models\MessageAttachment;
use Illuminate\Support\Facades\Storage;

class MessageRecipientController extends Controller
{
    /**
     * Endpoint AJAX para Select2 con búsqueda en las 3 tablas con prioridad.
     */
    public function search(Request $request, RecipientSearchService $service)
    {
        $term = $request->query('q', '');
        $grouped = $service->search($term, 10);
        $results = $service->formatForSelect2($grouped);

        return response()->json([
            'results' => $results,
        ]);
    }

    /**
     * Descarga segura de archivos adjuntos (verifica que el usuario sea emisor o receptor).
     */
    public function downloadAttachment(int $id)
    {
        $attachment = MessageAttachment::with('message')->findOrFail($id);
        $message = $attachment->message;
        $userId = auth()->id();

        // Control de autorización: solo participantes del mensaje pueden descargar
        if ($message->sender_id !== $userId && $message->recipient_id !== $userId) {
            abort(403, 'No tienes autorización para acceder a este archivo adjunto.');
        }

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404, 'El archivo solicitado ya no existe en el servidor.');
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->original_name);
    }
}

