@extends('components.layout')

@section('title', 'Editar Plan')

@section('content')
    <section class="w-full py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">
                {{ $plan ? $plan->name : 'Registrar Plan' }}
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                {{ $plan ? 'Completa los datos para actualizar la información del plan.' : 'Completa los datos para registrar un nuevo plan en el sistema.' }}
            </p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold">No se pudo registrar el plan.</p>
                    <ul class="mt-1 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ $plan ? route('plans.update', ['plan' => $plan->id]) : route('plans.store') }}"
                class="space-y-5">
                @csrf
                @method($plan ? 'PUT' : 'POST')
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-gray-700">
                            Nombre
                            <span class="text-red-600">*</span>
                        </label>
                        <input id="name" name="name" type="text" value="{{ $plan->name ?? old('name') }}" required
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                            placeholder="Ej: Ana María" autocomplete="name" />
                    </div>

                    <div>
                        <label for="price" class="mb-1 block text-sm font-medium text-gray-700">
                            Precio
                            <span class="text-red-600">*</span>
                        </label>
                        <input id="price" name="price" type="text" value="{{ $plan->price ?? old('price') }}" required
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                            placeholder="Ej: $1000" />
                    </div>

                    <div>
                        <label for="periodicity" class="mb-1 block text-sm font-medium text-gray-700">
                            Periodicidad
                            <span class="text-red-600">*</span>
                        </label>
                        <select id="periodicity" name="periodicity" required
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800">
                            <option value="" disabled {{ !isset($plan) ? 'selected' : '' }}>Selecciona una opción</option>
                            @foreach (App\Enums\PeriodicityEnum::cases() as $option)
                                <option value="{{ $option->code() }}" {{ (isset($plan) && $plan->periodicity === $option->code()) || old('periodicity') === $option->code() ? 'selected' : '' }}>
                                    {{ ucfirst($option->label()) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="items" class="mb-1 block text-sm font-medium text-gray-700">
                            Items incluidos en el plan (uno por línea)
                        </label>
                        <textarea id="items" name="items" rows="5"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                            placeholder="Item 1&#10;Item 2&#10;Item 3">{{ isset($plan) ? implode("\n", $plan->details->pluck('detail')->toArray()) : old('items') }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="mb-1 block text-sm font-medium text-gray-700">
                            Descripción
                            <span class="text-red-600">*</span>
                        </label>
                        <textarea id="description" name="description" rows="5" required
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                            placeholder="Descripción del plan">{{ $plan->description ?? old('description') }}</textarea>
                    </div>
                </div>

                <div class="flex flex-col gap-2 border-t border-gray-100 pt-4 sm:flex-row sm:justify-end">
                    <button type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-lg bg-gray-900 px-5 text-sm font-medium text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                        Guardar Plan
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection