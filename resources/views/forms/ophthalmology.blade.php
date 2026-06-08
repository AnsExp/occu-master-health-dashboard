@extends('components.layout')

@section('title', 'Formulario de oftalmología')

@section('content')
    <section class="w-full py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Formulario de oftalmología</h1>
            <p class="mt-1 text-sm text-gray-500">Completa los datos del paciente para generar el certificado.</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
            <x-search-order :url="route('form.ophthalmology')" class="mt-6" />
        </div>

        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
            @if ($order)
                <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4">
                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600">
                        <span class="rounded-md bg-white px-2 py-1 ring-1 ring-gray-200"><strong>Orden:</strong>
                            {{ $order->order_number }}</span>
                        <span class="rounded-md bg-white px-2 py-1 ring-1 ring-gray-200"><strong>Paciente:</strong>
                            {{ $order?->patient?->first_name }} {{ $order?->patient?->last_name }}</span>
                    </div>
                </div>

                <form class="space-y-5" method="POST" action="{{ route('form.ophthalmology.store') }}">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="order[id]" value="{{ $order->id }}">
                    <input type="hidden" name="order[order_number]" value="{{ $order->order_number }}">

                    @if (App\Enums\RoleEnum::has(App\Enums\RoleEnum::DOCTOR))
                        <input type="hidden" name="doctor[id]" value="{{ auth()->user()->doctor->id }}">
                    @else
                        <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4">
                            <label for="responsible_doctor_id" class="mb-1 block text-sm font-medium text-gray-700">
                                Médico responsable
                                <span class="text-red-600">*</span>
                            </label>
                            <select id="responsible_doctor_id" name="doctor[id]" required
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800">
                                <option value="" selected disabled>Selecciona un médico</option>
                                @foreach (App\Models\User::role(App\Enums\RoleEnum::DOCTOR->code())->get() as $doctor)
                                    <option value="{{ $doctor->doctor->id }}">{{ $doctor->name }}
                                        ({{ $doctor->doctor?->specialty ?? 'Sin especialidad' }})</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Escoja al médico responsable de la orden.</p>
                        </div>
                    @endif

                    <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4">
                        <div class="grid gap-4 lg:grid-cols-2">
                            <div>
                                <label for="corrective_lenses_usage" class="mb-1 block text-sm font-medium text-gray-700">
                                    Uso de anteojos o lentes de contacto
                                    <span class="text-red-600">*</span>
                                </label>
                                <select id="corrective_lenses_usage" name="medical_exam[corrective_lenses][usage]" required
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800">
                                    <option value="" selected disabled>Selecciona una opción</option>
                                    <option value="No usa">No usa</option>
                                    <option value="Anteojos">Anteojos</option>
                                    <option value="Lentes de contacto">Lentes de contacto</option>
                                    <option value="Ambos">Ambos</option>
                                </select>
                            </div>

                            <div>
                                <label for="corrective_lenses_function" class="mb-1 block text-sm font-medium text-gray-700">
                                    Función de los lentes
                                </label>
                                <input id="corrective_lenses_function" name="medical_exam[corrective_lenses][function]"
                                    type="text"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                    placeholder="Ej: visión de lejos, lectura, uso permanente" />
                                <p class="mt-1 text-xs text-gray-500">Complete este campo si el paciente utiliza corrección
                                    visual.</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <h4 class="text-sm font-semibold tracking-wide text-gray-800">Agudeza visual</h4>
                        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
                            <table class="min-w-[760px] border-collapse text-xs text-gray-800 sm:text-sm">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-200 bg-gray-50 px-2 py-2"></th>
                                        <th colspan="3" class="border border-gray-200 bg-gray-50 px-2 py-2 text-center">
                                            Sin corrección
                                        </th>
                                        <th colspan="3" class="border border-gray-200 bg-gray-50 px-2 py-2 text-center">
                                            Con corrección
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="border border-gray-200 bg-gray-50 px-2 py-2"></th>
                                        <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-center">Ojo derecho</th>
                                        <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-center">Ojo izquierdo</th>
                                        <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-center">Ambos</th>
                                        <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-center">Ojo derecho</th>
                                        <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-center">Ojo izquierdo</th>
                                        <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-center">Ambos</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-200 bg-gray-50 px-2 py-2 font-medium">Distancia</td>
                                        <td class="border border-gray-200 p-1">
                                            <input required name="medical_exam[visual_acuity][distance][without][right]"
                                                type="text"
                                                class="w-full rounded-md border-gray-200 px-2 py-1.5 text-center text-sm focus:border-gray-700 focus:ring-gray-700" />
                                        </td>
                                        <td class="border border-gray-200 p-1">
                                            <input required name="medical_exam[visual_acuity][distance][without][left]"
                                                type="text"
                                                class="w-full rounded-md border-gray-200 px-2 py-1.5 text-center text-sm focus:border-gray-700 focus:ring-gray-700" />
                                        </td>
                                        <td class="border border-gray-200 p-1">
                                            <input required name="medical_exam[visual_acuity][distance][without][both]"
                                                type="text"
                                                class="w-full rounded-md border-gray-200 px-2 py-1.5 text-center text-sm focus:border-gray-700 focus:ring-gray-700" />
                                        </td>
                                        <td class="border border-gray-200 p-1">
                                            <input required name="medical_exam[visual_acuity][distance][with][right]"
                                                type="text"
                                                class="w-full rounded-md border-gray-200 px-2 py-1.5 text-center text-sm focus:border-gray-700 focus:ring-gray-700" />
                                        </td>
                                        <td class="border border-gray-200 p-1">
                                            <input required name="medical_exam[visual_acuity][distance][with][left]" type="text"
                                                class="w-full rounded-md border-gray-200 px-2 py-1.5 text-center text-sm focus:border-gray-700 focus:ring-gray-700" />
                                        </td>
                                        <td class="border border-gray-200 p-1">
                                            <input required name="medical_exam[visual_acuity][distance][with][both]" type="text"
                                                class="w-full rounded-md border-gray-200 px-2 py-1.5 text-center text-sm focus:border-gray-700 focus:ring-gray-700" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-200 bg-gray-50 px-2 py-2 font-medium">Cerca</td>
                                        <td class="border border-gray-200 p-1">
                                            <input required name="medical_exam[visual_acuity][near][without][right]" type="text"
                                                class="w-full rounded-md border-gray-200 px-2 py-1.5 text-center text-sm focus:border-gray-700 focus:ring-gray-700" />
                                        </td>
                                        <td class="border border-gray-200 p-1">
                                            <input required name="medical_exam[visual_acuity][near][without][left]" type="text"
                                                class="w-full rounded-md border-gray-200 px-2 py-1.5 text-center text-sm focus:border-gray-700 focus:ring-gray-700" />
                                        </td>
                                        <td class="border border-gray-200 p-1">
                                            <input required name="medical_exam[visual_acuity][near][without][both]" type="text"
                                                class="w-full rounded-md border-gray-200 px-2 py-1.5 text-center text-sm focus:border-gray-700 focus:ring-gray-700" />
                                        </td>
                                        <td class="border border-gray-200 p-1">
                                            <input required name="medical_exam[visual_acuity][near][with][right]" type="text"
                                                class="w-full rounded-md border-gray-200 px-2 py-1.5 text-center text-sm focus:border-gray-700 focus:ring-gray-700" />
                                        </td>
                                        <td class="border border-gray-200 p-1">
                                            <input required name="medical_exam[visual_acuity][near][with][left]" type="text"
                                                class="w-full rounded-md border-gray-200 px-2 py-1.5 text-center text-sm focus:border-gray-700 focus:ring-gray-700" />
                                        </td>
                                        <td class="border border-gray-200 p-1">
                                            <input required name="medical_exam[visual_acuity][near][with][both]" type="text"
                                                class="w-full rounded-md border-gray-200 px-2 py-1.5 text-center text-sm focus:border-gray-700 focus:ring-gray-700" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <div class="space-y-3 rounded-xl border border-gray-200 bg-white p-4">
                            <h4 class="text-sm font-semibold tracking-wide text-gray-800">Campos visuales</h4>
                            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
                                <table class="min-w-[300px] border-collapse text-xs text-gray-800 sm:text-sm">
                                    <thead>
                                        <tr>
                                            <th class="border border-gray-200 bg-gray-50 px-2 py-2"></th>
                                            <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-center">Normal</th>
                                            <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-center">Defectuosa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (['right' => 'Ojo derecho', 'left' => 'Ojo izquierdo'] as $eyeKey => $eyeLabel)
                                            <tr>
                                                <td class="border border-gray-200 bg-gray-50 px-2 py-2 font-medium">
                                                    {{ $eyeLabel }} <span class="text-red-600">*</span>
                                                </td>
                                                <td class="border border-gray-200 text-center">
                                                    <input required type="radio" name="medical_exam[visual_field][{{ $eyeKey }}]"
                                                        value="Normal"
                                                        class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                                </td>
                                                <td class="border border-gray-200 text-center">
                                                    <input required type="radio" name="medical_exam[visual_field][{{ $eyeKey }}]"
                                                        value="Defectuosa"
                                                        class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="space-y-3 rounded-xl border border-gray-200 bg-white p-4">
                            <h4 class="text-sm font-semibold tracking-wide text-gray-800">Visión cromática <span
                                    class="text-red-600">*</span></h4>
                            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
                                <table class="min-w-[260px] border-collapse text-xs text-gray-800 sm:text-sm">
                                    <tbody>
                                        @foreach (['Sin probar', 'Dudosa', 'Normal', 'Defectuosa'] as $index => $statusLabel)
                                            <tr>
                                                <td class="border border-gray-200 bg-gray-50 px-2 py-2 font-medium">
                                                    <label class="inline-block h-full w-full" for="color_vision_{{ $index }}">
                                                        {{ $statusLabel }}
                                                    </label>
                                                </td>
                                                <td class="border border-gray-200 text-center">
                                                    <input required id="color_vision_{{ $index }}" type="radio"
                                                        name="medical_exam[color_vision]" value="{{ $statusLabel }}"
                                                        class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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