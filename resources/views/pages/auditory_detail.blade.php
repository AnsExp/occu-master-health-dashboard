@extends('components.layout')

@section('title', 'Detalle del log')

@section('content')
    @php
        $changes = is_array($log->changes) ? $log->changes : [];
        $oldState = is_array($changes['old'] ?? null) ? $changes['old'] : [];
        $newState = is_array($changes['new'] ?? null) ? $changes['new'] : [];

        $levelLabel = App\Enums\LevelEnum::fromCode($log->level)?->label() ?? ucfirst((string) $log->level);
        $actionLabel = App\Enums\ActionEnum::fromCode($log->action)?->label() ?? ucfirst((string) $log->action);
        $tableLabel = App\Enums\TableEnum::fromCode($log->table_name)?->label() ?? ucfirst((string) $log->table_name);

        $badgeClass = match ($log->level) {
            App\Enums\LevelEnum::INFO->code() => 'bg-blue-100 text-blue-800',
            App\Enums\LevelEnum::WARNING->code() => 'bg-amber-100 text-amber-800',
            App\Enums\LevelEnum::ERROR->code() => 'bg-orange-100 text-orange-800',
            App\Enums\LevelEnum::CRITICAL->code() => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    @endphp

    <section class="mx-auto max-w-7xl space-y-6 py-6">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">Detalle del log #{{ $log->id }}</h1>
                <p class="mt-1 text-sm text-gray-600">Información completa del evento registrado en auditoría.</p>
            </div>
            <a href="{{ route('audit.index') }}"
                class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                Volver a logs
            </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm lg:col-span-2">
                <h2 class="text-lg font-semibold text-gray-900">Información del evento</h2>
                <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Fecha y hora</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $log->created_at?->format('d/m/Y H:i:s') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Actualizado</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $log->updated_at?->format('d/m/Y H:i:s') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Nivel</dt>
                        <dd class="mt-1 text-sm">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">
                                {{ $levelLabel }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Acción</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $actionLabel }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Módulo/Tabla</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tableLabel }} ({{ $log->table_name }})</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">ID del registro afectado</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $log->record_id }}</dd>
                    </div>
                </dl>
            </article>

            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Actor</h2>
                <dl class="mt-4 space-y-4">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Usuario</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $log->user?->name ?? 'Sistema' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">ID de usuario</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $log->user_id ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">IP</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $log->ip_address ?? '-' }}</dd>
                    </div>
                </dl>
            </article>
        </div>

        <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900">Agente (User Agent)</h2>
            <p class="mt-3 rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-800 break-all">
                {{ $log->user_agent ?? 'No disponible' }}
            </p>
        </article>

        <div class="grid gap-6 lg:grid-cols-2">
            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Estado anterior</h2>
                @if (!empty($oldState))
                    <pre class="mt-3 max-h-[420px] overflow-auto rounded-xl border border-gray-200 bg-gray-50 p-4 text-xs text-gray-800">{{ json_encode($oldState, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                @else
                    <p class="mt-3 text-sm text-gray-600">No se registró estado anterior para este evento.</p>
                @endif
            </article>

            <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Estado nuevo</h2>
                @if (!empty($newState))
                    <pre class="mt-3 max-h-[420px] overflow-auto rounded-xl border border-gray-200 bg-gray-50 p-4 text-xs text-gray-800">{{ json_encode($newState, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                @else
                    <p class="mt-3 text-sm text-gray-600">No se registró estado nuevo para este evento.</p>
                @endif
            </article>
        </div>

        <article class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900">Payload completo del cambio</h2>
            @if (!empty($changes))
                <pre class="mt-3 max-h-[460px] overflow-auto rounded-xl border border-gray-200 bg-gray-50 p-4 text-xs text-gray-800">{{ json_encode($changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
            @else
                <p class="mt-3 text-sm text-gray-600">No hay payload de cambios registrado.</p>
            @endif
        </article>
    </section>
@endsection