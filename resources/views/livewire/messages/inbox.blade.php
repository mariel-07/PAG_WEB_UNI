<div>
    {{-- Encabezado --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">
                <i class="fas fa-inbox text-danger me-2"></i>Bandeja de Mensajería
            </h2>
            <p class="text-muted small mb-0">Gestiona y revisa tus mensajes internos con la comunidad universitaria.</p>
        </div>
        <a href="{{ route('messages.create') }}" class="btn btn-danger px-4 py-2 shadow-sm" style="background-color: var(--uni-maroon, #7A1015); border-color: var(--uni-maroon, #7A1015);">
            <i class="fas fa-pen me-2"></i>Redactar Mensaje
        </a>
    </div>

    {{-- Notificaciones Flash --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-info-circle me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Barra lateral de carpetas --}}
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-3">
                    <div class="list-group list-group-flush">
                        <button type="button" 
                                wire:click="setFolder('inbox')" 
                                class="list-group-item list-group-item-action border-0 rounded-2 d-flex justify-content-between align-items-center py-2 px-3 {{ $folder === 'inbox' ? 'active text-white' : 'text-dark' }}"
                                style="{{ $folder === 'inbox' ? 'background-color: var(--uni-maroon, #7A1015); border-color: var(--uni-maroon, #7A1015);' : '' }}">
                            <span><i class="fas fa-envelope-open-text me-2"></i>Recibidos</span>
                            @if($unreadCount > 0)
                                <span class="badge {{ $folder === 'inbox' ? 'bg-white text-danger' : 'bg-danger' }} rounded-pill">
                                    {{ $unreadCount }}
                                </span>
                            @else
                                <span class="badge bg-light text-muted rounded-pill">{{ $inboxTotal }}</span>
                            @endif
                        </button>

                        <button type="button" 
                                wire:click="setFolder('sent')" 
                                class="list-group-item list-group-item-action border-0 rounded-2 d-flex justify-content-between align-items-center py-2 px-3 mt-1 {{ $folder === 'sent' ? 'active text-white' : 'text-dark' }}"
                                style="{{ $folder === 'sent' ? 'background-color: var(--uni-maroon, #7A1015); border-color: var(--uni-maroon, #7A1015);' : '' }}">
                            <span><i class="fas fa-paper-plane me-2"></i>Enviados</span>
                            <span class="badge bg-secondary rounded-pill">{{ $sentTotal }}</span>
                        </button>
                    </div>

                    @if($folder === 'inbox')
                        <hr class="my-3 text-muted">
                        <div class="px-2">
                            <label class="form-label small text-muted text-uppercase fw-semibold mb-2">Filtrar estado</label>
                            <select wire:model.live="statusFilter" class="form-select form-select-sm">
                                <option value="all">Todos los recibidos</option>
                                <option value="unread">Solo no leídos</option>
                                <option value="read">Solo leídos</option>
                            </select>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Lista de Mensajes --}}
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm rounded-3">
                {{-- Barra de búsqueda superior --}}
                <div class="card-header bg-white border-bottom p-3">
                    <div class="row align-items-center g-2">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" 
                                       wire:model.live.debounce.300ms="search" 
                                       class="form-control bg-light border-start-0" 
                                       placeholder="Buscar por asunto, contenido o remitente/destinatario...">
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end text-muted small">
                            <span wire:loading class="spinner-border spinner-border-sm text-danger me-2" role="status"></span>
                            Mostrando {{ $messages->count() }} de {{ $messages->total() }} mensajes
                        </div>
                    </div>
                </div>

                {{-- Tabla / Listado --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light small text-uppercase text-muted">
                            <tr>
                                <th style="width: 40px;"></th>
                                <th>{{ $folder === 'sent' ? 'Destinatario' : 'Remitente' }}</th>
                                <th>Asunto</th>
                                <th class="text-end" style="width: 140px;">Fecha</th>
                                <th class="text-center" style="width: 110px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messages as $msg)
                                <tr class="{{ !$msg->isRead() && $folder === 'inbox' ? 'fw-bold table-light' : '' }}" wire:key="msg-{{ $msg->id }}">
                                    {{-- Indicador de lectura --}}
                                    <td class="text-center">
                                        @if($folder === 'inbox')
                                            <button type="button" 
                                                    wire:click="toggleRead({{ $msg->id }})" 
                                                    class="btn btn-sm btn-link p-0 text-decoration-none" 
                                                    title="{{ $msg->isRead() ? 'Marcar como no leído' : 'Marcar como leído' }}">
                                                <i class="fas fa-circle {{ $msg->isRead() ? 'text-muted opacity-25' : 'text-danger' }}" style="font-size: 0.65rem;"></i>
                                            </button>
                                        @else
                                            <i class="fas fa-check-double text-muted" style="font-size: 0.75rem;"></i>
                                        @endif
                                    </td>

                                    {{-- Remitente / Destinatario --}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2 text-danger fw-bold border flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.85rem;">
                                                {{ strtoupper(substr($folder === 'sent' ? $msg->recipient->name : $msg->sender->name, 0, 1)) }}
                                            </div>
                                            <div class="text-truncate" style="max-width: 180px;">
                                                <div class="text-dark">{{ $folder === 'sent' ? $msg->recipient->name : $msg->sender->name }}</div>
                                                <div class="text-muted small fw-normal">{{ $folder === 'sent' ? $msg->recipient->email : $msg->sender->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Asunto y resumen --}}
                                    <td>
                                        <a href="{{ route('messages.show', $msg->id) }}" class="text-decoration-none text-dark d-block">
                                            <span class="{{ !$msg->isRead() && $folder === 'inbox' ? 'fw-bold text-dark' : 'text-secondary' }}">{{ $msg->subject }}</span>
                                            <span class="text-muted small fw-normal ms-1">- {{ Str::limit(strip_tags($msg->body), 55) }}</span>
                                        </a>
                                    </td>

                                    {{-- Fecha --}}
                                    <td class="text-end text-muted small">
                                        {{ $msg->created_at->isToday() ? $msg->created_at->format('H:i') : $msg->created_at->format('d/m/Y') }}
                                    </td>

                                    {{-- Botones de acción --}}
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('messages.show', $msg->id) }}" class="btn btn-light text-primary" title="Ver mensaje">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" 
                                                    wire:click="deleteMessage({{ $msg->id }})" 
                                                    wire:confirm="¿Seguro que deseas eliminar este mensaje de tu bandeja?"
                                                    class="btn btn-light text-danger" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-folder-open fa-3x mb-3 text-secondary opacity-50"></i>
                                            <h6 class="fw-bold">No se encontraron mensajes</h6>
                                            <p class="small mb-0">Esta carpeta está vacía o no coincide con los filtros aplicados.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if($messages->hasPages())
                    <div class="card-footer bg-white border-top p-3">
                        {{ $messages->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

