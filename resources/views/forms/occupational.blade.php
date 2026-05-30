@extends('components.layout')

@section('title', 'Formulario de salud ocupacional')

@php
    $genders = App\Models\Gender::cases();
    $declarationQuestionsPart1 = [
        'Problemas de los ojos visión',
        'Presión arterial alta',
        'Enfermedades cardíacas / vasculares',
        'Operación de corazón',
        'Venas varicosas / Hemorroides',
        'Asma / Bronquitis',
        'Trastornos de la sangre',
        'Diabetes',
        'Trastornos de la Tiroides',
        'Problemas digestivos',
        'Trastornos renales',
        'Trastornos de la piel',

        'Alergias',
        'Enfermedades infecciosas/contagiosas',
        'Hernias',
        'Problemas genitales',
        'Embarazo',
        'Problemas del sueño',
        '¿Fuma? ¿Ingiere alcohol? ¿Usa drogas?',
        'Operaciones / Cirugía',
        'Epilepsia / crisis parciales',
        'Mareos / desvanecimientos',
        'Pérdida de la conciencia',
        'Problemas psiquiátricos',

        'Depresión',
        'Intento de suicidio',
        'Pérdida de memoria',
        'Fuertes dolores de cabeza',
        'Problemas de oído (audición, tinnitus)/nariz, garganta',
        'Limitación de la movilidad',
        'Problemas de la espalda o articulaciones',
        'Amputaciones',
        'Fracturas / dislocaciones',
    ];
    $declarationQuestionsPart2 = [
        '¿Alguna vez le han dado de baja por enfermedad o le han enviado a su país de origen estando embarcado?',
        '¿Alguna vez lo han hospitalizado?',
        '¿Alguna vez le han declarado no apto para el trabajo en el mar?',
        '¿Alguna vez le han impuesto limitaciones a su certificado médico o se lo han revocado?',
        '¿Sabe si tiene algún problema médico, dolencias, enfermedades?',
        '¿Se siente saludable y en condiciones para desempeñar las tareas del puesto / ocupación que se le han designado?',
        '¿Es alérgico a alguna medicina?',
    ];
    $declarationQuestionsPart3 = [
        '¿Está tomando alguna medicina prescrita o que no requiere receta?',
    ];
@endphp

@section('content')
    <section class="mx-auto max-w-5xl py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Formulario de salud ocupacional</h1>
            <p class="mt-1 text-sm text-gray-500">Completa los datos del paciente para generar el certificado.</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
            <form method="GET" action="{{ route('form.occupational') }}">
                <div
                    class="flex flex-col gap-2 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm font-medium text-gray-800">Buscar orden de pago</p>
                    <p class="text-xs text-gray-500">Busca por numero de orden</p>
                </div>

                <div class="mt-4 flex gap-2">
                    <input required type="text" name="order_number" value="{{ $order?->order_number ?? '' }}"
                        autocomplete="off" placeholder="Número de orden…"
                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                    <button type="submit"
                        class="inline-flex h-10 shrink-0 items-center justify-center rounded-lg bg-gray-900 px-5 text-sm font-medium text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                        Buscar
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
            @if ($order)
                <form class="space-y-5" method="POST" action="{{ route('form.occupational.store') }}">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="order[id]" value="{{ $order->id }}">
                    <input type="hidden" name="order[order_number]" value="{{ $order->order_number }}">

                    <div
                        class="flex flex-col gap-2 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-wide text-gray-700">Evaluación de salud
                                ocupacional</p>
                            <p class="text-xs text-gray-500">Complete la declaración, los datos clínicos y la aptitud
                                ocupacional del paciente.</p>
                        </div>
                        <p class="text-xs text-gray-500"><span class="text-red-600">*</span> Campos obligatorios</p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-gray-50/70 p-4">
                        <label for="responsible_doctor_id" class="mb-1 block text-sm font-medium text-gray-700">
                            Médico responsable
                            <span class="text-red-600">*</span>
                        </label>
                        <select id="responsible_doctor_id" name="doctor[id]" required
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800">
                            <option value="" selected disabled>Selecciona un médico</option>
                            @foreach (App\Models\Doctor::orderBy('first_name')->get() as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }}
                                    ({{ $doctor->specialty }})</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Escoja al médico responsable de la orden.</p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-gray-50/70 p-4">
                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-600">
                            <span class="rounded-md bg-white px-2 py-1 ring-1 ring-gray-200"><strong>Orden:</strong>
                                {{ $order->order_number }}</span>
                            <span class="rounded-md bg-white px-2 py-1 ring-1 ring-gray-200"><strong>Paciente:</strong>
                                {{ $order?->patient?->first_name }} {{ $order?->patient?->last_name }}</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700">
                            Declaración personal del sujeto que se somete a examen
                        </h3>

                        <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4 sm:p-5">
                            <div class="mb-3 flex items-center justify-between gap-3 text-xs text-gray-500">
                                <p>Responda cada pregunta seleccionando Sí o No.</p>
                                <p class="hidden sm:block">Todas son obligatorias.</p>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($declarationQuestionsPart1 as $index => $question)
                                    <div class="rounded-lg border border-gray-200 bg-white p-3">
                                        <input type="hidden" name="medical_exam[declarations][0][questions][{{ $index }}][text]"
                                            value="{{ $question }}" />
                                        <p class="text-sm font-medium leading-5 text-gray-800">{{ $question }} <span
                                                class="text-red-600">*</span></p>
                                        <div class="mt-3 flex items-center gap-4">
                                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                                <input type="radio" name="medical_exam[declarations][0][questions][{{ $index }}][value]"
                                                    value="true" required
                                                    class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                                Sí
                                            </label>
                                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                                <input type="radio" name="medical_exam[declarations][0][questions][{{ $index }}][value]"
                                                    value="false" required
                                                    class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                                No
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4 sm:p-5">
                        <label class="mb-3 flex items-center justify-between gap-3 text-xs font-medium text-gray-500">Si la
                            respuesta a
                            cualquiera de las preguntas anteriores fue «sí», sírvase dar detalles.</label>
                        <textarea
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                            name="medical_exam[declarations][0][aclarations]" id="if-yes-questions-part-1" rows="5"></textarea>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4 sm:p-5">
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($declarationQuestionsPart2 as $index => $question)
                                <div class="rounded-lg border border-gray-200 bg-white p-3">
                                    <input type="hidden" name="medical_exam[declarations][1][questions][{{ $index }}][text]"
                                        value="{{ $question }}" />
                                    <p class="text-sm font-medium leading-5 text-gray-800">{{ $question }} <span
                                            class="text-red-600">*</span></p>
                                    <div class="mt-3 flex items-center gap-4">
                                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                            <input type="radio" name="medical_exam[declarations][1][questions][{{ $index }}][value]"
                                                value="true" required
                                                class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                            Sí
                                        </label>
                                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                            <input type="radio" name="medical_exam[declarations][1][questions][{{ $index }}][value]"
                                                value="false" required
                                                class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                            No
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4 sm:p-5">
                        <label class="mb-3 flex items-center justify-between gap-3 text-xs font-medium text-gray-500">Si la
                            respuesta a
                            cualquiera de las preguntas anteriores fue «sí», sírvase dar detalles.</label>
                        <textarea
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                            name="medical_exam[declarations][1][aclarations]" id="if-yes-questions-part-2" rows="5"></textarea>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4 sm:p-5">
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($declarationQuestionsPart3 as $index => $question)
                                <div class="rounded-lg border border-gray-200 bg-white p-3">
                                    <input type="hidden" name="medical_exam[declarations][2][questions][{{ $index }}][text]"
                                        value="{{ $question }}" />
                                    <p class="text-sm font-medium leading-5 text-gray-800">{{ $question }} <span
                                            class="text-red-600">*</span></p>
                                    <div class="mt-3 flex items-center gap-4">
                                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                            <input type="radio" name="medical_exam[declarations][2][questions][{{ $index }}][value]"
                                                value="true" required
                                                class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                            Sí
                                        </label>
                                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                            <input type="radio" name="medical_exam[declarations][2][questions][{{ $index }}][value]"
                                                value="false" required
                                                class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                            No
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="medical_exam[declarations][2][aclarations]" value="" />
                    </div>

                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700">
                        Datos Clínicos
                    </h3>

                    <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4 sm:p-5 space-y-5">
                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="flex items-center gap-2">
                                <label for="clinical-height" class="text-sm font-medium text-gray-700">Altura <span
                                        class="text-red-600">*</span></label>
                                <input required id="clinical-height" name="medical_exam[clinical_data][height]" type="text"
                                    class="w-20 rounded border border-gray-200 bg-white px-2 py-1 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                    placeholder="1.68" />
                                <span class="text-sm text-gray-700">m</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <label for="clinical-weight" class="text-sm font-medium text-gray-700">Peso <span
                                        class="text-red-600">*</span></label>
                                <input required id="clinical-weight" name="medical_exam[clinical_data][weight]" type="text"
                                    class="w-20 rounded border border-gray-200 bg-white px-2 py-1 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                    placeholder="85" />
                                <span class="text-sm text-gray-700">Kg</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <label for="clinical-pulse" class="text-sm font-medium text-gray-700">Pulso <span
                                        class="text-red-600">*</span></label>
                                <input required id="clinical-pulse" name="medical_exam[clinical_data][pulse]" type="text"
                                    class="w-20 rounded border border-gray-200 bg-white px-2 py-1 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                    placeholder="55" />
                                <span class="text-sm text-gray-700">lpm</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-gray-700">Presión arterial <span
                                        class="text-red-600">*</span></span>
                                <input required name="medical_exam[clinical_data][blood_pressure_systolic]" type="text"
                                    class="w-14 rounded border border-gray-200 bg-white px-2 py-1 text-center text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                    placeholder="126" />
                                <input required name="medical_exam[clinical_data][blood_pressure_diastolic]" type="text"
                                    class="w-14 rounded border border-gray-200 bg-white px-2 py-1 text-center text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                    placeholder="84" />
                                <span class="text-sm text-gray-700">mm/Hg</span>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="flex items-center gap-2 sm:col-span-2">
                                <label for="clinical-emo" class="text-sm font-medium text-gray-700">EMO <span
                                        class="text-red-600">*</span></label>
                                <input required id="clinical-emo" name="medical_exam[clinical_data][emo]" type="text"
                                    class="w-full max-w-64 rounded border border-gray-200 bg-white px-2 py-1 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                    placeholder="NO INFECCIOSO" />
                            </div>

                            <div class="flex items-center gap-2">
                                <label for="clinical-glucose" class="text-sm font-medium text-gray-700">Glucosa <span
                                        class="text-red-600">*</span></label>
                                <input required id="clinical-glucose" name="medical_exam[clinical_data][glucose]" type="text"
                                    class="w-28 rounded border border-gray-200 bg-white px-2 py-1 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                    placeholder="NORMAL" />
                            </div>

                            <div class="flex items-center gap-2">
                                <label for="clinical-protein" class="text-sm font-medium text-gray-700">Proteína <span
                                        class="text-red-600">*</span></label>
                                <input required id="clinical-protein" name="medical_exam[clinical_data][protein]" type="text"
                                    class="w-28 rounded border border-gray-200 bg-white px-2 py-1 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                    placeholder="NEGATIVO" />
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="flex items-center gap-2">
                                <label for="clinical-blood-type" class="text-sm font-medium text-gray-700">Tipo de
                                    sangre <span class="text-red-600">*</span></label>
                                <select required id="clinical-blood-type" name="medical_exam[clinical_data][blood_type]"
                                    class="w-20 rounded border border-gray-200 bg-white px-2 py-1 text-center text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800">
                                    <option value="" disabled selected>Seleccione...</option>
                                    @foreach (App\Models\BloodTypes::cases() as $bloodType)
                                        <option value="{{ $bloodType->value }}">{{ $bloodType->value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid gap-4 lg:grid-cols-3">
                            @php
                                $clinicalChecks = [
                                    ['key' => 'head', 'label' => 'Cabeza'],
                                    ['key' => 'sinuses', 'label' => 'Senos paranasales, nariz, garganta'],
                                    ['key' => 'mouth_teeth', 'label' => 'Boca / Dientes'],
                                    ['key' => 'ears', 'label' => 'Oídos (general)'],
                                    ['key' => 'tympanic_membrane', 'label' => 'Membrana timpánica'],
                                    ['key' => 'eyes', 'label' => 'Ojos'],
                                    ['key' => 'ophthalmoscopy', 'label' => 'Oftalmoscopía'],
                                    ['key' => 'pupils', 'label' => 'Pupilas'],
                                    ['key' => 'ocular_movement', 'label' => 'Movimiento ocular'],
                                    ['key' => 'lungs_thorax', 'label' => 'Pulmones y tórax'],
                                    ['key' => 'breast_exam', 'label' => 'Examen de mama'],
                                    ['key' => 'heart', 'label' => 'Corazón'],
                                    ['key' => 'skin', 'label' => 'Piel'],
                                    ['key' => 'varicose_veins', 'label' => 'Venas varicosas'],
                                    ['key' => 'vascular', 'label' => 'Vascular (inc. pulsos)'],
                                    ['key' => 'abdomen_viscera', 'label' => 'Abdomen y vísceras'],
                                    ['key' => 'hernias', 'label' => 'Hernias'],
                                    ['key' => 'anus', 'label' => 'Ano (excluye examen rectal)'],
                                    ['key' => 'genitourinary', 'label' => 'Sistema genitourinario'],
                                    ['key' => 'extremities', 'label' => 'Extremidades inferiores y superiores'],
                                    ['key' => 'spine', 'label' => 'Columna vertebral'],
                                    ['key' => 'neurological', 'label' => 'Neurológico'],
                                    ['key' => 'psychiatric', 'label' => 'Psiquiátrico'],
                                    ['key' => 'general_appearance', 'label' => 'Apariencia general'],
                                ];
                                $clinicalCheckChunks = array_chunk($clinicalChecks, (int) ceil(count($clinicalChecks) / 3));
                            @endphp

                            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
                                <table class="min-w-[320px] w-full border-collapse text-xs text-gray-800 sm:text-sm">
                                    <thead>
                                        <tr>
                                            <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-left">Evaluación</th>
                                            <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-center">Normal</th>
                                            <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-center">Anormal</th>
                                        </tr>
                                    </thead>
                                    @foreach ($clinicalCheckChunks as $chunkIndex => $chunk)
                                        <tbody>
                                            @foreach ($chunk as $item)
                                                @php
                                                    $checkIndex = $chunkIndex * count($chunk) + $loop->index;
                                                @endphp
                                                <tr>
                                                    <input type="hidden"
                                                        name="medical_exam[clinical_data][checks][{{ $checkIndex }}][test]"
                                                        value="{{ $item['label'] }}" />
                                                    <td class="border border-gray-200 px-2 py-2 font-medium">{{ $item['label'] }} <span
                                                            class="text-red-600">*</span></td>
                                                    <td class="border border-gray-200 text-center">
                                                        <input required type="radio"
                                                            name="medical_exam[clinical_data][checks][{{ $checkIndex }}][result]"
                                                            value="Normal"
                                                            class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                                    </td>
                                                    <td class="border border-gray-200 text-center">
                                                        <input required type="radio"
                                                            name="medical_exam[clinical_data][checks][{{ $checkIndex }}][result]"
                                                            value="Anormal"
                                                            class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="grid gap-2 sm:grid-cols-[140px_1fr] sm:items-center">
                                <label for="clinical-observations"
                                    class="text-sm font-medium text-gray-700">Observaciones</label>
                                <textarea id="clinical-observations" name="medical_exam[clinical_data][observations]" rows="5"
                                    class="w-full rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                    placeholder="Observaciones clínicas"></textarea>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[220px_140px_160px_1fr] lg:items-center">
                                <span class="text-sm font-medium text-gray-700">Radiografía de tórax <span
                                        class="text-red-600">*</span></span>
                                <div class="flex items-center gap-2">
                                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                        <input required type="radio" name="medical_exam[clinical_data][chest_xray][status]"
                                            value="Se hizo"
                                            class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                        Se hizo
                                    </label>
                                    <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                        <input required type="radio" name="medical_exam[clinical_data][chest_xray][status]"
                                            value="No se hizo"
                                            class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                        No se hizo
                                    </label>
                                </div>
                                <label for="clinical-chest-xray-date" class="text-sm font-medium text-gray-700">Fecha</label>
                                <input required id="clinical-chest-xray-date" name="medical_exam[clinical_data][chest_xray][date]" type="date"
                                    class="w-full rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                            </div>

                            <div class="grid gap-2 sm:grid-cols-[140px_1fr] sm:items-center">
                                <label for="clinical-result" class="text-sm font-medium text-gray-700">Resultado</label>
                                <input required id="clinical-result" name="medical_exam[clinical_data][result]" type="text"
                                    class="w-full rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                    placeholder="Ej: Hallazgos radiológicos normales" />
                            </div>
                        </div>
                    </div>

                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700">
                        Otras pruebas de diagnóstico y resultados
                    </h3>

                    <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4 sm:p-5 space-y-5">
                        @php
                            $diagnosticRows = [
                                ['key' => 'hematimetry', 'label' => 'Biometría hemática'],
                                ['key' => 'lipid_profile', 'label' => 'Perfil lipídico'],
                                ['key' => 'blood_chemistry', 'label' => 'Química sanguínea'],
                                ['key' => 'hepatic_profile', 'label' => 'Perfil hepático'],
                                ['key' => 'hiv', 'label' => 'HIV'],
                            ];
                        @endphp

                        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
                            <table class="min-w-[820px] w-full border-collapse text-xs text-gray-800 sm:text-sm">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-left">Prueba</th>
                                        <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-center">Normal</th>
                                        <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-center">Anormal</th>
                                        <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-left">Resultado / detalle
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($diagnosticRows as $row)
                                        <tr>
                                            <input type="hidden" name="medical_exam[other_tests][{{ $loop->index }}][test]"
                                                value="{{ $row['label'] }}" />
                                            <td class="border border-gray-200 bg-gray-50 px-2 py-2 font-medium">{{ $row['label'] }}
                                            </td>
                                            <td class="border border-gray-200 text-center">
                                                <input required type="radio" name="medical_exam[other_tests][{{ $loop->index }}][status]"
                                                    value="Normal"
                                                    class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                            </td>
                                            <td class="border border-gray-200 text-center">
                                                <input required type="radio" name="medical_exam[other_tests][{{ $loop->index }}][status]"
                                                    value="Anormal"
                                                    class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                            </td>
                                            <td class="border border-gray-200 p-1.5">
                                                <input required type="text" name="medical_exam[other_tests][{{ $loop->index }}][detail]"
                                                    class="w-full rounded border border-gray-200 bg-white px-2 py-1.5 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4 lg:items-center">
                            <label for="other-tests-ecg" class="text-sm font-medium text-gray-700">ECG</label>
                            <input required id="other-tests-ecg" name="medical_exam[ecg]" type="text"
                                class="rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                placeholder="Ej: Trazado normal" />
                            <label for="other-tests-koh" class="text-sm font-medium text-gray-700">KOH</label>
                            <input required id="other-tests-koh" name="medical_exam[koh]" type="text"
                                class="rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                placeholder="Ej: N/A" />
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[120px_160px_120px_1fr] lg:items-center">
                            <label for="other-tests-vdrl" class="text-sm font-medium text-gray-700">VDRL</label>
                            <input required id="other-tests-vdrl" name="medical_exam[vdrl]" type="text"
                                class="rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                placeholder="Ej: No reactivo" />
                            <label for="other-tests-copro" class="text-sm font-medium text-gray-700">Copro</label>
                            <input required id="other-tests-copro" name="medical_exam[copro]" type="text"
                                class="rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                placeholder="Ej: Normal" />
                        </div>

                        <div class="space-y-3">
                            <div class="grid gap-2 sm:grid-cols-[190px_1fr] sm:items-center">
                                <label for="other-tests-psychological" class="text-sm font-medium text-gray-700">Valoración
                                    psicológica</label>
                                <input required id="other-tests-psychological" name="medical_exam[psychological_assessment]"
                                    type="text"
                                    class="w-full rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                            </div>

                            <div class="grid gap-2 sm:grid-cols-[190px_1fr] sm:items-center">
                                <label for="other-tests-dental" class="text-sm font-medium text-gray-700">Valoración
                                    odontológica</label>
                                <input required id="other-tests-dental" name="medical_exam[dental_assessment]" type="text"
                                    class="w-full rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                            </div>

                            <div class="grid gap-2 sm:grid-cols-[190px_1fr] sm:items-center">
                                <label for="other-tests-extra" class="text-sm font-medium text-gray-700">Otras pruebas</label>
                                <input required id="other-tests-extra" name="medical_exam[extra_tests]" type="text"
                                    class="w-full rounded border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                            </div>
                        </div>
                    </div>

                    <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-700">
                        Evaluación de la aptitud para servicio en el mar
                    </h3>

                    <div class="rounded-xl border border-gray-200 bg-gray-50/60 p-4 sm:p-5 space-y-5">
                        <p class="text-xs text-gray-600 sm:text-sm">
                            Sobre la base de la declaración de la persona examinada, mi conocimiento clínico y los resultados de
                            las
                            pruebas de diagnóstico, declaro que la persona examinada es:
                        </p>

                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[220px_1fr] lg:items-center">
                            <span class="text-sm font-medium text-gray-700">Apto para el servicio de vigía</span>
                            <div class="flex flex-wrap items-center gap-6 rounded-lg border border-gray-200 bg-white px-3 py-2">
                                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                    <input required type="radio" name="medical_exam[aptitude_eval][watchkeeping]" value="Apto"
                                        class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                    Apto
                                </label>
                                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                    <input required type="radio" name="medical_exam[aptitude_eval][watchkeeping]" value="No apto"
                                        class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                    No apto
                                </label>
                            </div>
                        </div>

                        @php
                            $serviceColumns = [
                                'deck_service' => 'Servicio de cubierta',
                                'machine_service' => 'Servicio de máquinas',
                                'food_handling' => 'Manipulación de alimentos',
                                'radiocommunication' => 'Radiocomunicaciones',
                                'others' => 'Otros',
                            ];
                            $serviceLabels = array_values($serviceColumns);
                        @endphp

                        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
                            <table class="min-w-[760px] w-full border-collapse text-xs text-gray-800 sm:text-sm">
                                <thead>
                                    <tr>
                                        <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-left">Resultado</th>
                                        @foreach ($serviceColumns as $label)
                                            <th class="border border-gray-200 bg-gray-50 px-2 py-2 text-center">{{ $label }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-gray-200 bg-gray-50 px-2 py-2 font-medium">Apto</td>
                                        @foreach ($serviceLabels as $index => $label)
                                            <td class="border border-gray-200 text-center">
                                                <input type="hidden"
                                                    name="medical_exam[aptitude_eval][service_matrix][{{ $index }}][service]"
                                                    value="{{ $label }}" />
                                                <input required type="radio"
                                                    name="medical_exam[aptitude_eval][service_matrix][{{ $index }}][result]"
                                                    value="Apto"
                                                    class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td class="border border-gray-200 bg-gray-50 px-2 py-2 font-medium">No apto</td>
                                        @foreach ($serviceLabels as $index => $label)
                                            <td class="border border-gray-200 text-center">
                                                <input required type="radio"
                                                    name="medical_exam[aptitude_eval][service_matrix][{{ $index }}][result]"
                                                    value="No apto"
                                                    class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white px-3 py-2">
                                <span class="text-sm font-medium text-gray-700">Sin restricciones</span>
                                <input required type="radio" name="medical_exam[aptitude_eval][restrictions]" value="false"
                                    class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                            </div>
                            <div class="flex items-center gap-3 rounded-lg border border-gray-200 bg-white px-3 py-2">
                                <span class="text-sm font-medium text-gray-700">Con restricciones</span>
                                <input required type="radio" name="medical_exam[aptitude_eval][restrictions]" value="true"
                                    class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label for="aptitude-restriction-description"
                                    class="mb-1 block text-sm font-medium text-gray-700">Descripción de la restricción</label>
                                <textarea required id="aptitude-restriction-description"
                                    name="medical_exam[aptitude_eval][restriction_description]" rows="5"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                    placeholder="Detalle de restricciones aplicadas"></textarea>
                            </div>

                            <div>
                                <label for="aptitude-observations"
                                    class="mb-1 block text-sm font-medium text-gray-700">Observaciones y evaluación de la
                                    aptitud del médico</label>
                                <textarea required id="aptitude-observations" name="medical_exam[aptitude_eval][observations]" rows="5"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                    placeholder="Razones para cualquier limitación o conclusión clínica"></textarea>
                            </div>
                        </div>

                        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[280px_1fr] lg:items-center">
                            <span class="text-sm font-medium text-gray-700">Obligación de llevar lentes correctores</span>
                            <div class="flex flex-wrap items-center gap-6 rounded-lg border border-gray-200 bg-white px-3 py-2">
                                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                    <input required type="radio" name="medical_exam[aptitude_eval][corrective_lenses]" value="true"
                                        class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                    Sí
                                </label>
                                <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                    <input required type="radio" name="medical_exam[aptitude_eval][corrective_lenses]" value="false"
                                        class="h-4 w-4 border-gray-300 text-gray-900 focus:ring-gray-900" />
                                    No
                                </label>
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
                <p class="text-sm text-gray-500">Ingrese un número de orden válido para mostrar el formulario.</p>
            @endif
        </div>
    </section>
@endsection