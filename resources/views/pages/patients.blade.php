@extends('components.layout')

@section('title', 'Pacientes')

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
        $data = App\Models\Patient::orderBy($order_by, request('direction', 'asc'))->paginate(10);
    @endphp

    <section class="mx-auto max-w-6xl py-6">
        <div class="mb-6 flex items-start justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Pacientes</h1>
                <p class="mt-1 text-sm text-gray-500">Listado general de pacientes registrados en el sistema.</p>
            </div>
            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                {{ count($data) }} registros
            </span>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach ([
                                ['label' => 'Paciente','href'=>route('patients', ['order' => 'first_name', 'direction' => $direction('first_name'), 'page' => $page,])],
                                ['label' => 'Cédula','href'=>route('patients', ['order' => 'id_card', 'direction' => $direction('id_card'), 'page' => $page,])],
                                ['label' => 'Género','href'=>route('patients', ['order' => 'gender', 'direction' => $direction('gender'), 'page' => $page,])],
                                ['label' => 'Nacimiento','href'=>route('patients', ['order' => 'date_of_birth', 'direction' => $direction('date_of_birth'), 'page' => $page,])],
                                ['label' => 'Correo','href'=>route('patients', ['order' => 'email', 'direction' => $direction('email'), 'page' => $page,])],
                                ['label' => 'Teléfono','href'=>route('patients', ['order' => 'phone', 'direction' => $direction('phone'), 'page' => $page,])],
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
                        @foreach ($data as $patient)
                            <tr class="hover:bg-gray-50/70">
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $patient->first_name }} {{ $patient->last_name }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $patient->id_card }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ App\Enums\GenderEnum::fromCode($patient->gender)?->label() ?? 'Desconocido' }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $patient->birth_date->format('F d, Y') }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $patient->email }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $patient->phone }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    <a href="#">Historial</a>
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