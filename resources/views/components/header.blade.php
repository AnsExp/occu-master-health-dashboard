@php
    use App\Enums\PermissionEnum;

    $currentUrl = url()->current();

    $items = [
        [
            'label' => 'Inicio',
            'href' => route('home'),
            'active' => request()->is('/'),
            'allow' => true
        ],
        [
            'label' => 'Planes',
            'href' => route('plans'),
            'active' => request()->is('plans') || request()->is('plans/*'),
            'allow' => PermissionEnum::can(PermissionEnum::VIEW_PLANS)
        ],
        [
            'label' => 'Órdenes',
            'href' => route('orders'),
            'active' => request()->is('orders') || request()->is('orders/*'),
            'allow' => PermissionEnum::can(PermissionEnum::VIEW_ORDERS)
        ],
        [
            'label' => 'Usuarios',
            'href' => route('users'),
            'active' => request()->is('users') || request()->is('users/*'),
            'allow' => PermissionEnum::can(PermissionEnum::VIEW_USERS)
        ],
        [
            'label' => 'Pacientes',
            'href' => route('patients'),
            'active' => request()->is('patients') || request()->is('patients/*'),
            'allow' => PermissionEnum::can(PermissionEnum::VIEW_PATIENTS)
        ],
        [
            'label' => 'Especialidades',
            'active' => request()->is('audiology') || request()->is('occupational') || request()->is('ophthalmology'),
            'allow' => auth()->check(),
            'children' => [
                ['label' => 'Audiología', 'href' => route('audiology.index'), 'active' => request()->is('audiology'), 'allow' => PermissionEnum::can(PermissionEnum::VIEW_AUDIOLOGY)],
                ['label' => 'Ocupacional', 'href' => route('occupational.index'), 'active' => request()->is('occupational'), 'allow' => PermissionEnum::can(PermissionEnum::VIEW_OCCUPATIONAL)],
                ['label' => 'Oftalmología', 'href' => route('ophthalmology.index'), 'active' => request()->is('ophthalmology'), 'allow' => PermissionEnum::can(PermissionEnum::VIEW_OPHTHALMOLOGY)],
            ],
        ],
        [
            'label' => 'Auditoría',
            'href' => route('audit.index'),
            'active' => request()->is('audit') || request()->is('audit/*'),
            'allow' => PermissionEnum::can(PermissionEnum::VIEW_LOGS)
        ],
        [
            'label' => 'Cerrar sesión',
            'href' => route('logout'),
            'active' => false,
            'allow' => auth()->check()
        ],
        [
            'label' => 'Iniciar sesión',
            'href' => route('login'),
            'active' => request()->is('login') || request()->is('login/*'),
            'allow' => !auth()->check()
        ],
    ];
@endphp

<header class="border-b border-slate-200 bg-slate-50/70 p-4 lg:hidden">
    <details class="group">
        <summary
            class="flex cursor-pointer list-none items-center justify-between rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 shadow-sm">
            <span>{{ auth()->check() ? auth()->user()->name : 'OccuMaster Health' }}</span>
            <span class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500 group-open:hidden">Menú</span>
            <span
                class="hidden text-xs font-semibold uppercase tracking-[0.16em] text-slate-500 group-open:inline">Cerrar</span>
        </summary>

        <nav class="mt-3 space-y-2 rounded-lg border border-slate-200 bg-white p-3 shadow-sm">
            @foreach ($items as $item)
                @if ($item['allow'])
                    @if (!empty($item['children']))
                        <details class="group rounded-xl border border-slate-200">
                            <summary
                                class="flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl px-4 py-3 text-sm font-medium {{ $item['active'] ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 transition hover:bg-slate-100 hover:text-slate-900' }}">
                                <span class="flex items-center gap-3">
                                    <span
                                        class="h-2 w-2 rounded-full {{ $item['active'] ? 'bg-emerald-400' : 'bg-slate-300' }}"></span>
                                    {{ $item['label'] }}
                                </span>
                                <span
                                    class="text-xs font-semibold uppercase tracking-[0.16em] text-inherit group-open:rotate-180">▾</span>
                            </summary>

                            <div class="space-y-1 border-t border-slate-200 p-2">
                                @foreach ($item['children'] as $child)
                                    @if ($child['allow'])
                                        <a href="{{ $child['href'] }}"
                                            class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium {{ $child['active'] ? 'bg-slate-100 text-slate-900' : 'text-slate-600 transition hover:bg-slate-50 hover:text-slate-900' }}">
                                            <span
                                                class="h-1.5 w-1.5 rounded-full {{ $child['active'] ? 'bg-emerald-400' : 'bg-slate-300' }}"></span>
                                            {{ $child['label'] }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </details>
                    @else
                        <a href="{{ $item['href'] }}"
                            class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium {{ $item['active'] ? 'text-white bg-slate-900 shadow-sm' : 'text-slate-600 transition hover:bg-slate-100 hover:text-slate-900' }}">
                            <span class="h-2 w-2 rounded-full {{ $item['active'] ? 'bg-emerald-400' : 'bg-slate-300' }}"></span>
                            {{ $item['label'] }}
                        </a>
                    @endif
                @endif
            @endforeach
        </nav>
    </details>
</header>

<aside class="hidden border-b border-slate-200 bg-slate-50/70 p-6 lg:block lg:border-b-0 lg:border-r">
    <div class="mb-6">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Navegación</p>
        <h2 class="mt-2 text-lg font-semibold text-slate-900">
            {{ auth()->check() ? auth()->user()->name : 'OccuMaster Health' }}
        </h2>
    </div>

    <nav class="space-y-2">
        @foreach ($items as $item)
            @if ($item['allow'])
                @if (!empty($item['children']))
                    <details class="group rounded-xl border border-slate-200 bg-white shadow-sm">
                        <summary
                            class="flex cursor-pointer list-none items-center justify-between gap-3 rounded-xl px-4 py-3 text-sm font-medium {{ $item['active'] ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 transition hover:bg-slate-100 hover:text-slate-900' }}">
                            <span class="flex items-center gap-3">
                                <span class="h-2 w-2 rounded-full {{ $item['active'] ? 'bg-emerald-400' : 'bg-slate-300' }}"></span>
                                {{ $item['label'] }}
                            </span>
                            <span
                                class="text-xs font-semibold uppercase tracking-[0.16em] text-inherit group-open:rotate-180">▾</span>
                        </summary>

                        <div class="space-y-1 border-t border-slate-200 p-2">
                            @foreach ($item['children'] as $child)
                                @if ($child['allow'])
                                    <a href="{{ $child['href'] }}"
                                        class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium {{ $child['active'] ? 'bg-slate-100 text-slate-900' : 'text-slate-600 transition hover:bg-slate-50 hover:text-slate-900' }}">
                                        <span
                                            class="h-1.5 w-1.5 rounded-full {{ $child['active'] ? 'bg-emerald-400' : 'bg-slate-300' }}"></span>
                                        {{ $child['label'] }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </details>
                @else
                    <a href="{{ $item['href'] }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium {{ $item['active'] ? 'text-white bg-slate-900 shadow-sm' : 'text-slate-600 transition hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="h-2 w-2 rounded-full {{ $item['active'] ? 'bg-emerald-400' : 'bg-slate-300' }}"></span>
                        {{ $item['label'] }}
                    </a>
                @endif
            @endif
        @endforeach
    </nav>
</aside>