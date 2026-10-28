<div>
    {{-- Encabezado --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="h4 fw-bold text-dark mb-1">
                <i class="fas fa-edit text-danger me-2"></i>{{ $replyToMessageId ? 'Responder Mensaje' : 'Redactar Nuevo Mensaje' }}
            </h2>
            <p class="text-muted small mb-0">Selecciona los destinatarios y adjunta imágenes según las reglas institucionales.</p>
        </div>
        <a href="{{ route('messages.inbox') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Volver a la bandeja
        </a>
    </div>

    {{-- Notificaciones de Error del Servidor --}}
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-4">
            <form wire:submit.prevent="send">

                {{-- Campo: Destinatarios (Select2 con Selección Múltiple y Búsqueda en 3 Tablas) --}}
                <div class="mb-4">
                    <label for="recipients-select" class="form-label fw-semibold text-dark d-flex justify-content-between align-items-center">
                        <span>
                            Destinatarios <span class="text-danger">*</span>
                            <small class="text-muted fw-normal ms-1">(Búsqueda en Docentes, Alumnos y Usuarios)</small>
                        </span>
                        <span class="badge bg-light text-secondary border fw-normal" style="font-size: 0.75rem;">
                            <i class="fas fa-users me-1"></i>Selección múltiple disponible
                        </span>
                    </label>

                    {{-- Contenedor con Alpine y Select2 protegido de re-renders con wire:ignore --}}
                    <div wire:ignore x-data="{}" x-init="
                        const $select = $('#recipients-select').select2({
                            theme: 'bootstrap-5',
                            placeholder: 'Buscar destinatario por apellidos, nombres, código o correo...',
                            allowClear: true,
                            width: '100%',
                            language: 'es',
                            ajax: {
                                url: '{{ route('messages.search-recipients') }}',
                                dataType: 'json',
                                delay: 300,
                                data: function (params) {
                                    return { q: params.term };
                                },
                                processResults: function (data) {
                                    return { results: data.results };
                                },
                                cache: true
                            }
                        });

                        $select.on('change', function () {
                            const values = $(this).val() || [];
                            $wire.set('form.recipients', values);
                        });
                    ">
                        <select id="recipients-select" 
                                class="form-select select2-multiple" 
                                multiple="multiple" 
                                style="width: 100%;">
                            {{-- Grupos iniciales priorizados para no saturar la vista --}}
                            @if(!empty($groupedRecipients['docentes']))
                                <optgroup label="Docentes (Prioridad 1)">
                                    @foreach($groupedRecipients['docentes'] as $doc)
                                        <option value="{{ $doc['id'] }}" {{ in_array($doc['id'], $form->recipients) ? 'selected' : '' }}>
                                            {{ $doc['text'] }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif

                            @if(!empty($groupedRecipients['alumnos']))
                                <optgroup label="Alumnos (Prioridad 2)">
                                    @foreach($groupedRecipients['alumnos'] as $alu)
                                        <option value="{{ $alu['id'] }}" {{ in_array($alu['id'], $form->recipients) ? 'selected' : '' }}>
                                            {{ $alu['text'] }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif

                            @if(!empty($groupedRecipients['usuarios']))
                                <optgroup label="Usuarios del Sistema (Prioridad 3)">
                                    @foreach($groupedRecipients['usuarios'] as $usr)
                                        <option value="{{ $usr['id'] }}" {{ in_array($usr['id'], $form->recipients) ? 'selected' : '' }}>
                                            {{ $usr['text'] }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endif
                        </select>
                    </div>

                    {{-- Feedback de validación para destinatarios --}}
                    @error('form.recipients')
                        <div class="text-danger small mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                    @error('form.recipients.*')
                        <div class="text-danger small mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Campo: Asunto --}}
                <div class="mb-3">
                    <label for="subject" class="form-label fw-semibold text-dark">
                        Asunto <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           id="subject" 
                           wire:model.blur="form.subject" 
                           class="form-control @error('form.subject') is-invalid @enderror" 
                           placeholder="Ej. Solicitud de información académica / Reunión de coordinación"
                           maxlength="150">

                    @error('form.subject')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Campo: Contenido / Mensaje --}}
                <div class="mb-4">
                    <label for="body" class="form-label fw-semibold text-dark">
                        Mensaje <span class="text-danger">*</span>
                    </label>
                    <textarea id="body" 
                              wire:model.blur="form.body" 
                              rows="7" 
                              class="form-control @error('form.body') is-invalid @enderror" 
                              placeholder="Escribe detalladamente tu mensaje aquí..."></textarea>

                    @error('form.body')
                        <div class="invalid-feedback d-block">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                    <div class="form-text small text-muted">Mínimo 10 caracteres.</div>
                </div>

                {{-- Sección: Adjuntar Archivos con Reglas Institucionales --}}
                <div class="mb-4 p-3 rounded-3 bg-light border">
                    <label class="form-label fw-semibold text-dark d-flex justify-content-between align-items-center mb-1">
                        <span>
                            <i class="fas fa-paperclip text-danger me-1"></i>Adjuntar Archivos (Opcional)
                        </span>
                        <span class="badge bg-white text-dark border">
                            {{ count($attachments) }} / 5 archivos
                        </span>
                    </label>

                    {{-- Reglas explicadas claramente --}}
                    <div class="text-muted small mb-3" style="font-size: 0.85rem;">
                        <ul class="mb-0 ps-3">
                            <li><strong>Extensiones permitidas:</strong> <code>.jpeg</code>, <code>.jpg</code>, <code>.png</code></li>
                            <li><strong>Cantidad máxima:</strong> Hasta 5 archivos</li>
                            <li><strong>Peso máximo por archivo:</strong> 5MB</li>
                            <li><strong>Peso máximo total permitido:</strong> 25MB</li>
                        </ul>
                    </div>

                    {{-- Input de archivos con subida Livewire --}}
                    <div class="mb-2">
                        <input type="file" 
                               wire:model="attachments" 
                               multiple 
                               accept=".jpeg,.jpg,.png" 
                               class="form-control @error('attachments') is-invalid @enderror"
                               id="attachments-input">
                    </div>

                    {{-- Indicador de carga en progreso --}}
                    <div wire:loading wire:target="attachments" class="text-primary small mt-2">
                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        Procesando y cargando archivos adjuntos...
                    </div>

                    {{-- Errores de validación de adjuntos --}}
                    @error('attachments')
                        <div class="text-danger small mt-2">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror

                    @error('attachments.*')
                        <div class="text-danger small mt-2">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror

                    {{-- Lista visual de archivos adjuntos cargados con vista previa y botón eliminar --}}
                    @if(!empty($attachments) && count($attachments) > 0)
                        <div class="mt-3">
                            <h6 class="small fw-bold text-dark mb-2">Archivos seleccionados para enviar:</h6>
                            <div class="row g-2">
                                @foreach($attachments as $index => $file)
                                    <div class="col-md-6 col-lg-4" wire:key="attachment-{{ $index }}">
                                        <div class="card border shadow-sm p-2 bg-white rounded-2 h-100">
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $isImg = in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'jpeg', 'png']);
                                                @endphp

                                                @if($isImg)
                                                    <img src="{{ $file->temporaryUrl() }}" 
                                                         alt="Vista previa" 
                                                         class="rounded border me-2 flex-shrink-0" 
                                                         style="width: 44px; height: 44px; object-fit: cover;">
                                                @else
                                                    <div class="rounded border me-2 d-flex align-items-center justify-content-center bg-light flex-shrink-0" style="width: 44px; height: 44px;">
                                                        <i class="fas fa-file-image text-danger fa-lg"></i>
                                                    </div>
                                                @endif

                                                <div class="flex-grow-1 text-truncate me-2">
                                                    <div class="small fw-semibold text-truncate text-dark" title="{{ $file->getClientOriginalName() }}">
                                                        {{ $file->getClientOriginalName() }}
                                                    </div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">
                                                        {{ number_format($file->getSize() / 1024 / 1024, 2) }} MB
                                                    </div>
                                                </div>

                                                <button type="button" 
                                                        wire:click="removeAttachment({{ $index }})" 
                                                        class="btn btn-sm btn-outline-danger border-0 p-1 rounded-circle flex-shrink-0" 
                                                        title="Quitar este archivo">
                                                    <i class="fas fa-times-circle fa-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Acciones del Formulario --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('messages.inbox') }}" class="btn btn-light px-4">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="btn btn-danger px-4" 
                            wire:loading.attr="disabled"
                            style="background-color: var(--uni-maroon, #7A1015); border-color: var(--uni-maroon, #7A1015);">
                        <span wire:loading.remove wire:target="send, attachments">
                            <i class="fas fa-paper-plane me-2"></i>Enviar Mensaje
                        </span>
                        <span wire:loading wire:target="send, attachments">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Procesando...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
