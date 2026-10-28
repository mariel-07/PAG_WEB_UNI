<div>
    {{-- Navegación y Acciones --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <a href="{{ route('messages.inbox') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Volver a la bandeja
        </a>

        <div class="btn-group btn-group-sm">
            @if($message->recipient_id === auth()->id())
                <a href="{{ route('messages.create', ['replyTo' => $message->id]) }}" class="btn btn-danger" style="background-color: var(--uni-maroon, #7A1015); border-color: var(--uni-maroon, #7A1015);">
                    <i class="fas fa-reply me-1"></i>Responder
                </a>
                <button type="button" wire:click="toggleRead" class="btn btn-outline-secondary">
                    <i class="fas {{ $message->isRead() ? 'fa-envelope' : 'fa-envelope-open' }} me-1"></i>
                    {{ $message->isRead() ? 'Marcar como no leído' : 'Marcar como leído' }}
                </button>
            @endif
            <button type="button" wire:click="deleteMessage" wire:confirm="¿Seguro que deseas eliminar este mensaje?" class="btn btn-outline-danger">
                <i class="fas fa-trash-alt me-1"></i>Eliminar
            </button>
        </div>
    </div>

    {{-- Notificaciones Flash --}}
    @if (session()->has('info'))
        <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tarjeta Principal del Mensaje --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white p-4 border-bottom">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                <div>
                    <h3 class="h5 fw-bold text-dark mb-2">{{ $message->subject }}</h3>
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center text-muted small gap-sm-3">
                        <span>
                            <strong class="text-dark">De:</strong> {{ $message->sender->name }} &lt;{{ $message->sender->email }}&gt;
                        </span>
                        <span>
                            <strong class="text-dark">Para:</strong> {{ $message->recipient->name }} &lt;{{ $message->recipient->email }}&gt;
                        </span>
                    </div>
                </div>
                <div class="text-muted small text-sm-end">
                    <div><i class="far fa-clock me-1"></i>{{ $message->created_at->format('d/m/Y H:i A') }}</div>
                    <div class="text-secondary">({{ $message->created_at->diffForHumans() }})</div>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <div class="p-2" style="font-size: 0.95rem; line-height: 1.7; color: #2d3748; white-space: pre-wrap;">{{ $message->body }}</div>
        </div>

        {{-- Sección de Archivos Adjuntos --}}
        @if($message->attachments->count() > 0)
            <div class="card-footer bg-light p-4 border-top">
                <h6 class="fw-bold text-dark mb-3">
                    <i class="fas fa-paperclip text-danger me-2"></i>Archivos Adjuntos ({{ $message->attachments->count() }})
                </h6>
                <div class="row g-3">
                    @foreach($message->attachments as $att)
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card h-100 border shadow-sm rounded-2 overflow-hidden bg-white">
                                @if($att->isImage())
                                    <a href="{{ $att->url }}" target="_blank" class="d-block text-center bg-light border-bottom overflow-hidden" style="height: 140px;">
                                        <img src="{{ $att->url }}" alt="{{ $att->original_name }}" class="w-100 h-100" style="object-fit: cover;">
                                    </a>
                                @endif
                                <div class="p-2 d-flex flex-column justify-content-between flex-grow-1">
                                    <div class="mb-2">
                                        <div class="small fw-semibold text-truncate text-dark" title="{{ $att->original_name }}">
                                            {{ $att->original_name }}
                                        </div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            {{ $att->formatted_size }}
                                        </div>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <a href="{{ $att->url }}" target="_blank" class="btn btn-sm btn-outline-secondary flex-grow-1" style="font-size: 0.75rem;">
                                            <i class="fas fa-eye me-1"></i>Ver
                                        </a>
                                        <a href="{{ route('messages.attachment.download', $att->id) }}" class="btn btn-sm btn-outline-danger flex-grow-1" style="font-size: 0.75rem;">
                                            <i class="fas fa-download me-1"></i>Descargar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Respuestas vinculadas si existen --}}
    @if($message->replies->count() > 0)
        <div class="mt-4">
            <h5 class="h6 text-muted fw-bold mb-3"><i class="fas fa-comments me-2"></i>Respuestas en este hilo:</h5>
            @foreach($message->replies as $reply)
                <div class="card border-0 shadow-sm rounded-3 mb-3 bg-light">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong class="text-dark small">{{ $reply->sender->name }}</strong>
                            <span class="text-muted small">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="small text-secondary mb-0" style="white-space: pre-wrap;">{{ $reply->body }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

