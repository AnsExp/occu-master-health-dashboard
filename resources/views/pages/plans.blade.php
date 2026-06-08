@extends('components.layout')

@section('title', 'Planes')

@php
    use App\Enums\PeriodicityEnum;
    use App\Enums\PermissionEnum;

    $headers = [
        ['label' => 'Nombre', 'href' => route('plans', ['sort' => 'name', 'direction' => $sort === 'name' && $direction === 'asc' ? 'desc' : 'asc'])],
        ['label' => 'Precio', 'href' => route('plans', ['sort' => 'price', 'direction' => $sort === 'price' && $direction === 'asc' ? 'desc' : 'asc'])],
    ];
@endphp

@section('content')
    <section class="mx-auto max-w-6xl py-6">
        @if (session('status'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="mb-6 flex items-start justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Planes</h1>
                <p class="mt-1 text-sm text-gray-500">Listado general de planes registrados en el sistema.</p>
            </div>
            <div>
                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                    {{ $data->total() }} registros
                </span>
                @can (PermissionEnum::STORE_PLANS->code())
                    <a href="{{ route('plans.create') }}"
                        class="ml-4 rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Crear</a>
                @endcan
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            @foreach ($headers as $link)
                                <th scope="col" class="text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
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
                        @foreach ($data as $plan)
                            <tr class="hover:bg-gray-50/70">
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $plan->name }}
                                </td>
                                <td class="px-4 py-3 align-top text-gray-700">
                                    {{ $plan->price }} / {{ PeriodicityEnum::fromCode($plan->periodicity)?->label() ?? 'N/D' }}
                                </td>
                                @can (PermissionEnum::UPDATE_PLANS->code())
                                    <td class="px-4 py-3 align-top text-right text-gray-700">
                                        <a href="{{ route('plans.edit', ['plan' => $plan->id]) }}" class="text-gray-700 hover:underline">Editar</a>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $data->links() }}
    </section>
@endsection