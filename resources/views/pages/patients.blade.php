@extends('components.layout')

@section('title', 'Pacientes')

@section('content')

    @php
        $headers = [
            ['label' => 'Paciente', 'href' => route('patients', ['sort' => 'first_name', 'direction' => $sort === 'first_name' && $direction === 'asc' ? 'desc' : 'asc'])],
            ['label' => 'Cédula', 'href' => route('patients', ['sort' => 'id_card', 'direction' => $sort === 'id_card' && $direction === 'asc' ? 'desc' : 'asc'])],
            ['label' => 'Género', 'href' => route('patients', ['sort' => 'gender', 'direction' => $sort === 'gender' && $direction === 'asc' ? 'desc' : 'asc'])],
            ['label' => 'Nacimiento', 'href' => route('patients', ['sort' => 'birth_date', 'direction' => $sort === 'birth_date' && $direction === 'asc' ? 'desc' : 'asc'])],
            ['label' => 'Correo', 'href' => route('patients', ['sort' => 'email', 'direction' => $sort === 'email' && $direction === 'asc' ? 'desc' : 'asc'])],
            ['label' => 'Teléfono', 'href' => route('patients', ['sort' => 'phone', 'direction' => $sort === 'phone' && $direction === 'asc' ? 'desc' : 'asc'])],
        ];
    @endphp

    <section class="mx-auto max-w-6xl py-6">
        <div class="mb-6 flex items-start justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Pacientes</h1>
                <p class="mt-1 text-sm text-gray-500">Listado general de pacientes registrados en el sistema.</p>
            </div>
            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                {{ $data->total() }} registros
            </span>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach ($headers as $link)
                                <th scope="col"
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
                                    <a href="{{ $link['href'] }}" class="w-full h-full px-4 py-3 inline-block">
                                        {{ $link['label'] }}
                                    </a>
                                </th>
                            @endforeach
                            <th scope="col"
                                class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600">Acciones
                            </th>
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
                                    {{ format_datetime($patient->birth_date) }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $patient->email }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $patient->phone }}
                                </td>
                                <td class="px-4 py-3 align-top text-right text-gray-700">
                                    <a href="{{ route('orders', ['id_card' => $patient->id_card]) }}" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:bg-gray-50">Certificados</a>
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