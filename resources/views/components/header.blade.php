@php
    use App\Models\Permission;
@endphp

<header class="border-b border-gray-200 bg-white/90 backdrop-blur">
    <div class="container mx-auto flex h-16 items-center justify-between px-4">
        <a href="{{ route('home') }}" class="text-base font-semibold tracking-tight text-gray-900">
            {{ config('app.name', 'Laravel') }}
        </a>

        <nav class="hidden items-center gap-2 md:flex" aria-label="Main navigation">
            <a href="{{ route('home') }}" class="rounded-md px-3 py-2 text-sm font-medium transition">
                Inicio
            </a>
            @if (Permission::has(Permission::READ_USERS))
                <a href="{{ route('users') }}" class="rounded-md px-3 py-2 text-sm font-medium transition">
                    Usuarios
                </a>
            @endif
            @if (Permission::has(Permission::READ_PATIENTS))
                <a href="{{ route('patients') }}" class="rounded-md px-3 py-2 text-sm font-medium transition">
                    Pacientes
                </a>
            @endif
            @if (Permission::has(Permission::READ_ORDERS))
                <a href="{{ route('orders') }}" class="rounded-md px-3 py-2 text-sm font-medium transition">
                    Órdenes
                </a>
            @endif
            @auth
                <a href="{{ route('logout') }}" class="rounded-md px-3 py-2 text-sm font-medium transition">
                    Cerrar sesión
                </a>
            @endauth
        </nav>
        <details class="relative md:hidden">
            <summary
                class="list-none cursor-pointer rounded-md border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                Menu
            </summary>

            <nav class="absolute right-0 z-20 mt-2 w-44 rounded-lg border border-gray-200 bg-white p-2 shadow-lg"
                aria-label="Mobile navigation">
                <a href="{{ route('home') }}" class="block rounded-md px-3 py-2 text-sm font-medium transition">
                    Inicio
                </a>
                @if (Permission::has(Permission::READ_USERS))
                    <a href="{{ route('users') }}" class="block rounded-md px-3 py-2 text-sm font-medium transition">
                        Usuarios
                    </a>
                @endif
                @if (Permission::has(Permission::READ_PATIENTS))
                    <a href="{{ route('patients') }}" class="block rounded-md px-3 py-2 text-sm font-medium transition">
                        Pacientes
                    </a>
                @endif
                @if (Permission::has(Permission::READ_ORDERS))
                    <a href="{{ route('orders') }}" class="block rounded-md px-3 py-2 text-sm font-medium transition">
                        Órdenes
                    </a>
                @endif
                @auth
                    <a href="{{ route('logout') }}" class="block rounded-md px-3 py-2 text-sm font-medium transition">
                        Cerrar sesión
                    </a>
                @endauth
            </nav>
        </details>
    </div>
</header>