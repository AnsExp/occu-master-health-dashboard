@extends('components.layout')

@section('title', 'Doctores')

@section('content')
    @php
        $order_by = request('order', 'first_name');
        $page = request('page', 1);
        $direction = function ($order_by) {
            $current_order = request('order');
            $current_direction = request('direction', 'asc');
            if ($current_order === $order_by) {
                return $current_direction === 'asc' ? 'desc' : 'asc';
            }
            return 'asc';
        };
        $data = App\Models\Doctor::orderBy($order_by, request('direction', 'asc'))->paginate(10);
    @endphp

    <section class="mx-auto max-w-6xl py-6">
        @if (session('status'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="mb-6 flex items-start justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Doctores</h1>
                <p class="mt-1 text-sm text-gray-500">Listado general de doctores registrados en el sistema.</p>
            </div>
            <div>
                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                    {{ count($data) }} registros
                </span>
                <a href="{{ route('doctors.edit') }}" class="ml-4 rounded-md bg-emerald-500 px-4 py-2 text-sm font-semibold text-white">Crear</a>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach ([
                                ['label' => 'Doctor','href' => route('doctors', ['order' => 'first_name', 'direction' => $direction('first_name'), 'page' => $page,])],
                                ['label' => 'Cédula','href' => route('doctors', ['order' => 'id_card', 'direction' => $direction('id_card'), 'page' => $page,])],
                                ['label' => 'Especialidad','href' => route('doctors', ['order' => 'specialty', 'direction' => $direction('specialty'), 'page' => $page,])],
                                ['label' => 'Teléfono','href' => route('doctors', ['order' => 'phone', 'direction' => $direction('phone'), 'page' => $page,])],
                                ['label' => 'Correo','href' => route('doctors', ['order' => 'email', 'direction' => $direction('email'), 'page' => $page,])],
                            ] as $link)
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                                    <a href="{{ $link['href'] }}"
                                        class="w-full h-full inline-block items-center">
                                        {{ $link['label'] }}
                                    </a>
                                </th>
                                @endforeach
                                <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($data as $doctor)
                            <tr class="hover:bg-gray-50/70">
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $doctor->first_name }} {{ $doctor->last_name }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $doctor->id_card }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $doctor->specialty }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $doctor->phone }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $doctor->email }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    <a href="{{ route('doctors.edit', ['id' => $doctor->id]) }}">Editar</a>
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