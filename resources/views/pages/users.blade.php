@extends('components.layout')

@section('title', 'Usuarios')

@section('content')
    @php
        use App\Models\Permission;

        $order_by = request('order', 'name');
        $page = request('page', 1);
        $direction = function ($order_by) {
            $current_order = request('order');
            $current_direction = request('direction', 'asc');
            if ($current_order === $order_by) {
                return $current_direction === 'asc' ? 'desc' : 'asc';
            }
            return 'asc';
        };
        $data = App\Models\User::orderBy($order_by, request('direction', 'asc'))->paginate(10);
    @endphp

    <section class="mx-auto max-w-6xl py-6">

        <div class="mb-6 flex items-start justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Usuarios</h1>
                <p class="mt-1 text-sm text-gray-500">Listado general de usuarios registrados en el sistema.</p>
            </div>
            <div>
                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                    {{ $data->total() }} registros
                </span>
                @if (Permission::has(Permission::WRITE_USERS))
                <a href="{{ route('users.edit', ['user' => null]) }}" class="ml-4 rounded-md bg-emerald-500 px-4 py-2 text-sm font-semibold text-white">Crear</a>
                @endif
            </div>
        </div>

        @if (session('status'))
           <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
               {{ session('status') }}
           </div>
       @endif

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach ([
                                ['label' => 'Nombre','href'=>route('users', ['order' => 'name', 'direction' => $direction('name'), 'page' => $page,])],
                                ['label' => 'Correo','href'=>route('users', ['order' => 'email', 'direction' => $direction('email'), 'page' => $page,])],
                            ] as $link)
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                                    <a href="{{ $link['href'] }}"
                                        class="w-full h-full inline-block items-center">
                                        {{ $link['label'] }}
                                    </a>
                                </th>
                            @endforeach
                            <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Rol</th>
                            @if (Permission::has([Permission::WRITE_USERS, Permission::ERASE_USERS]))
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600"></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($data as $user)
                            <tr class="hover:bg-gray-50/70">
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $user->name }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $user->email }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ implode(', ', array_map(fn($role)=> App\Enums\RoleEnum::fromCode($role)->label(), $user->getRoleNames()->toArray())) }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    <div class="flex items-center gap-4 justify-end">
                                        @if (Permission::has(Permission::WRITE_USERS))
                                            <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="text-gray-700 hover:underline">Detalles</a>
                                        @endif
                                        @if (Permission::has(Permission::ERASE_USERS))
                                            <form action="{{ route('users.destroy', ['user' => $user->id]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline cursor-pointer">Eliminar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $data->links() }}
    </section>
@endsection