@extends('components.layout')

@section('title', 'Formulario de audiología')

@section('content')
    <section class="w-full py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Formulario de audiología</h1>
            <p class="mt-1 text-sm text-gray-500">Completa los datos del paciente para generar el certificado.</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
            <x-search-order :url="route('form.audiology')" class="mt-6" />
        </div>

        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($order)
                <form class="space-y-5" method="POST" action="{{ route('form.audiology.store') }}">
                    @csrf
                    @method('POST')

                    <div
                        class="flex flex-col gap-2 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide text-gray-700">Evaluación de audiología</p>
                            <p class="text-xs text-gray-500">Complete los resultados clínicos del paciente.</p>
                        </div>
                        <p class="text-xs text-gray-500"><span class="text-red-600">*</span> Campos obligatorios</p>
                    </div>

                    <input type="hidden" name="order[id]" value="{{ $order->id }}">
                    <input type="hidden" name="order[order_number]" value="{{ $order->order_number }}">

                    <div class="rounded-xl border border-gray-200 bg-gray-50/70 p-4">
                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600">
                            <span class="rounded-md bg-white px-2 py-1 ring-1 ring-gray-200"><strong>Orden:</strong>
                                {{ $order->order_number }}</span>
                            <span class="rounded-md bg-white px-2 py-1 ring-1 ring-gray-200"><strong>Paciente:</strong>
                                {{ $order?->patient?->first_name }} {{ $order?->patient?->last_name }}</span>
                        </div>
                    </div>

                    @if (auth()->user()->hasRole(App\Enums\RoleEnum::DOCTOR->code()))
                        <input type="hidden" name="doctor[id]" value="{{ auth()->user()->doctor->id }}">
                    @else
                        <div class="rounded-xl border border-gray-200 bg-gray-50/70 p-4">
                            <label for="responsible_doctor_id" class="mb-1 block text-sm font-medium text-gray-700">
                                Médico responsable
                                <span class="text-red-600">*</span>
                            </label>
                            <select id="responsible_doctor_id" name="doctor[id]" required
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800">
                                <option value="" selected disabled>Selecciona un médico</option>
                                @foreach (App\Models\User::role(App\Enums\RoleEnum::DOCTOR->code())->get() as $user)
                                    <option value="{{ $user->doctor->id }}">{{ $user->doctor->first_name }}
                                        {{ $user->doctor->last_name }}
                                        ({{ $user->doctor?->specialty?->name ?? 'Sin especialidad' }})</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Escoja al médico responsable de la orden.</p>
                        </div>
                    @endif

                    <div class="grid gap-4 xl:grid-cols-12">
                        <div class="space-y-3 rounded-xl border border-gray-200 bg-white p-4 xl:col-span-5">
                            <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-700">Audición</h4>
                            <div class="space-y-2 rounded-lg border border-gray-100 bg-gray-50/50 p-3">
                                <div class="grid gap-2 sm:grid-cols-[170px_1fr] sm:items-center">
                                    <label for="hearing-right" class="text-sm font-medium text-gray-700">Oído
                                        derecho <span class="text-red-600">*</span></label>
                                    <input required id="hearing-right" name="medical_exam[hearing][right]" type="text"
                                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                                </div>
                                <div class="grid gap-2 sm:grid-cols-[170px_1fr] sm:items-center">
                                    <label for="hearing-left" class="text-sm font-medium text-gray-700">Oído
                                        izquierdo <span class="text-red-600">*</span></label>
                                    <input required id="hearing-left" name="medical_exam[hearing][left]" type="text"
                                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4 xl:col-span-7">
                            <div class="rounded-xl border border-gray-200 bg-white p-4">
                                <h4 class="mb-2 text-sm font-semibold uppercase tracking-wide text-gray-700">Prueba del habla y
                                    del susurro
                                    (3 metros)</h4>
                                <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
                                    <table class="min-w-[360px] border-collapse text-xs text-gray-800 sm:text-sm">
                                        <thead>
                                            <tr>
                                                <th class="border border-gray-200 bg-gray-50 px-3 py-2"></th>
                                                <th class="border border-gray-200 bg-gray-50 px-3 py-2 text-center">
                                                    Normal
                                                </th>
                                                <th class="border border-gray-200 bg-gray-50 px-3 py-2 text-center">
                                                    Susurro
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (['right' => 'Oído derecho', 'left' => 'Oído izquierdo'] as $earKey => $earLabel)
                                                <tr>
                                                    <td class="border border-gray-200 bg-gray-50 px-3 py-2 font-medium">
                                                        {{ $earLabel }} <span class="text-red-600">*</span>
                                                    </td>
                                                    <td class="border border-gray-200 text-center">
                                                        <input required type="radio"
                                                            name="medical_exam[speech_whisper][{{ $earKey }}]" value="Normal"
                                                            class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                                    </td>
                                                    <td class="border border-gray-200 text-center">
                                                        <input required type="radio"
                                                            name="medical_exam[speech_whisper][{{ $earKey }}]" value="Susurro"
                                                            class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="rounded-xl border border-gray-200 bg-white p-4">
                                <h4 class="mb-2 text-sm font-semibold uppercase tracking-wide text-gray-700">
                                    Test de Ishihara
                                </h4>
                                <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
                                    <table class="min-w-[320px] border-collapse text-xs text-gray-800 sm:text-sm">
                                        <thead>
                                            <tr>
                                                <th class="border border-gray-200 bg-gray-50 px-3 py-2"></th>
                                                <th class="border border-gray-200 bg-gray-50 px-3 py-2 text-center">N</th>
                                                <th class="border border-gray-200 bg-gray-50 px-3 py-2 text-center">CP</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach (['yellow' => 'Amarillo', 'green' => 'Verde', 'red' => 'Rojo', 'blue' => 'Azul'] as $colorKey => $colorLabel)
                                                <tr>
                                                    <td class="border border-gray-200 bg-gray-50 px-3 py-2 font-medium">
                                                        {{ $colorLabel }} <span class="text-red-600">*</span>
                                                    </td>
                                                    <td class="border border-gray-200 text-center"><input required type="radio"
                                                            name="medical_exam[ishihara][{{ $colorKey }}]" value="N"
                                                            class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                                    </td>
                                                    <td class="border border-gray-200 text-center"><input required type="radio"
                                                            name="medical_exam[ishihara][{{ $colorKey }}]" value="CP"
                                                            class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end border-t border-gray-100 pt-4">
                        <button type="submit"
                            class="inline-flex h-10 w-full items-center justify-center rounded-lg bg-gray-900 px-5 text-sm font-medium text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 sm:w-auto sm:min-w-56">
                            Guardar evaluación médica
                        </button>
                    </div>
                </form>
            @else
                @if (session('error'))
                    <p class="text-sm text-red-500">{{ session('error') }}</p>
                @else
                    <p class="text-sm text-gray-500">Ingrese un número de orden válido para mostrar el formulario.</p>
                @endif
            @endif
        </div>
    </section>
@endsection