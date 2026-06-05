@php
    use App\Models\Permission;

    $currentUrl = url()->current();
    $items = [
        ['label' => 'Inicio', 'href' => route('home'), 'active' => $currentUrl === route('home'), 'allow' => true],
        ['label' => 'Planes', 'href' => route('plans'), 'active' => $currentUrl === route('plans'), 'allow' => Permission::has(Permission::READ_PLANS)],
        ['label' => 'Órdenes', 'href' => route('orders'), 'active' => $currentUrl === route('orders'), 'allow' => Permission::has(Permission::READ_ORDERS)],
        ['label' => 'Usuarios', 'href' => route('users'), 'active' => $currentUrl === route('users'), 'allow' => Permission::has(Permission::READ_USERS)],
        ['label' => 'Pacientes', 'href' => route('patients'), 'active' => $currentUrl === route('patients'), 'allow' => Permission::has(Permission::READ_PATIENTS)],
        ['label' => 'Formularios', 'href' => route('forms'), 'active' => $currentUrl === route('forms'), 'allow' => auth()->check()],
        ['label' => 'Cerrar sesión', 'href' => route('logout'), 'active' => false, 'allow' => auth()->check()],
        ['label' => 'Iniciar sesión', 'href' => route('login'), 'active' => $currentUrl === route('login'), 'allow' => !auth()->check()],
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
                    <a href="{{ $item['href'] }}"
                        class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium {{ $item['active'] ? 'text-white bg-slate-900 shadow-sm' : 'text-slate-600 transition hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="h-2 w-2 rounded-full {{ $item['active'] ? 'bg-emerald-400' : 'bg-slate-300' }}"></span>
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </nav>
    </details>
</header>

<aside class="hidden border-b border-slate-200 bg-slate-50/70 p-6 lg:block lg:border-b-0 lg:border-r">
    <div class="mb-6">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Navegación</p>
        <h2 class="mt-2 text-lg font-semibold text-slate-900">
            {{ auth()->check() ? auth()->user()->name : 'OccuMaster Health' }}</h2>
    </div>

    <nav class="space-y-2">
        @foreach ($items as $item)
            @if ($item['allow'])
                <a href="{{ $item['href'] }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium {{ $item['active'] ? 'text-white bg-slate-900 shadow-sm' : 'text-slate-600 transition hover:bg-slate-100 hover:text-slate-900' }}">
                    <span class="h-2 w-2 rounded-full {{ $item['active'] ? 'bg-emerald-400' : 'bg-slate-300' }}"></span>
                    {{ $item['label'] }}
                </a>
            @endif
        @endforeach
    </nav>
</aside>