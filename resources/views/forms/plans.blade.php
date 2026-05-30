@extends('components.layout')

@section('title', 'Editar Plan')

@section('content')
    <section class="mx-auto max-w-4xl py-6">
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
                            placeholder="Ej: Ana María" />
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
                            @foreach (App\Models\Periodicity::cases() as $option)
                                <option value="{{ $option->value }}" {{ (isset($plan) && $plan->periodicity === $option->value) || old('periodicity') === $option->value ? 'selected' : '' }}>
                                    {{ ucfirst($option->value) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="phone" class="mb-1 block text-sm font-medium text-gray-700">
                            Teléfono
                            <span class="text-red-600">*</span>
                        </label>
                        <input id="phone" name="phone" type="text" value="{{ $doctor->phone ?? old('phone') }}" required
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                            placeholder="Ej: 8091234567" />
                    </div>

                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-gray-700">
                            Correo
                            <span class="text-red-600">*</span>
                        </label>
                        <input id="email" name="email" type="email" value="{{ $doctor->email ?? old('email') }}" required
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                            placeholder="doctor@correo.com" />
                    </div>

                    <div class="sm:col-span-2">
                        <label for="address" class="mb-1 block text-sm font-medium text-gray-700">
                            Dirección
                            <span class="text-red-600">*</span>
                        </label>
                        <textarea id="address" name="address" rows="3" required
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                            placeholder="Calle, número, sector, ciudad">{{ $doctor->address ?? old('address') }}</textarea>
                    </div>
                </div>

                <div class="flex flex-col gap-2 border-t border-gray-100 pt-4 sm:flex-row sm:justify-end">
                    <a href="{{ route('doctors') }}"
                        class="inline-flex h-10 items-center justify-center rounded-lg border border-gray-300 px-5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="inline-flex h-10 items-center justify-center rounded-lg bg-gray-900 px-5 text-sm font-medium text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                        Guardar doctor
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection