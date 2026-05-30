<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $certificate->title }}</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 20px;
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #111827;
            font-size: 11px;
            line-height: 1.35;
        }

        .header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .header td {
            vertical-align: top;
        }

        .title {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }

        .subtitle {
            margin: 2px 0 0;
            color: #4b5563;
            font-size: 11px;
        }

        .meta {
            text-align: right;
            font-size: 11px;
            color: #374151;
        }

        .section-title {
            margin: 10px 0 6px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.25px;
        }

        .info-table,
        .result-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td,
        .result-table td,
        .result-table th {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            vertical-align: top;
        }

        .info-label,
        .result-table th {
            background: #f9fafb;
            font-weight: 700;
            color: #374151;
        }

        .center {
            text-align: center;
        }

        .footer {
            margin-top: 14px;
            padding-top: 8px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #4b5563;
            text-align: center;
        }
    </style>
</head>

<body>
    @php
        \Carbon\Carbon::setLocale('es');

        $patient = $certificate->patient;
        $doctor = $certificate->doctor;
        $order = $certificate->order;

        $patientName = $patient ? trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? '')) : 'N/D';
        $doctorName = $doctor ? trim(($doctor->first_name ?? '') . ' ' . ($doctor->last_name ?? '')) : 'N/D';
        $issuedAt = $certificate->created_at ? $certificate->created_at->translatedFormat('j \\d\\e F, Y') : 'N/D';
        $birthDateSource = $patient->birth_date ?? $patient->date_of_birth ?? null;
        $birthDate = $birthDateSource
            ? \Illuminate\Support\Carbon::parse($birthDateSource)->translatedFormat('j \\d\\e F, Y')
            : 'N/D';

        $contentRaw = $certificate->content;
        $content = is_array($contentRaw) ? $contentRaw : (json_decode($contentRaw ?? '', true) ?: []);

        $declarations = is_array($content['declarations'] ?? null) ? $content['declarations'] : [];
        $clinicalData = is_array($content['clinical_data'] ?? null) ? $content['clinical_data'] : [];
        $clinicalChecks = is_array($clinicalData['checks'] ?? null) ? $clinicalData['checks'] : [];
        $otherTests = is_array($content['other_tests'] ?? null) ? $content['other_tests'] : [];
        $aptitudeEval = is_array($content['aptitude_eval'] ?? null) ? $content['aptitude_eval'] : [];
        $serviceMatrix = is_array($aptitudeEval['service_matrix'] ?? null) ? $aptitudeEval['service_matrix'] : [];

        $fieldText = static function ($value) {
            return $value !== null && $value !== '' ? $value : 'N/D';
        };

        $boolText = static function ($value) {
            if (is_bool($value)) {
                return $value ? 'Sí' : 'No';
            }

            $normalized = strtolower(trim((string) $value));

            if (in_array($normalized, ['1', 'true', 'yes', 'si', 'sí'], true)) {
                return 'Sí';
            }

            if (in_array($normalized, ['0', 'false', 'no'], true)) {
                return 'No';
            }

            return $value !== null && $value !== '' ? (string) $value : 'N/D';
        };
    @endphp

    <table class="header" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <p class="title">{{ $certificate->title }}</p>
                <p class="subtitle">Resultado de evaluación de salud ocupacional</p>
            </td>
            <td class="meta">
                <div><strong>Fecha:</strong> {{ $issuedAt }}</div>
            </td>
        </tr>
    </table>

    <div class="section-title">Datos generales</div>
    <table class="info-table" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td class="info-label" style="width: 25%;">Paciente</td>
                <td style="width: 25%;">{{ $patientName !== '' ? $patientName : 'N/D' }}</td>
                <td class="info-label" style="width: 25%;">Cédula</td>
                <td style="width: 25%;">{{ $patient->id_card ?? 'N/D' }}</td>
            </tr>
            <tr>
                <td class="info-label">Fecha de nacimiento</td>
                <td>{{ $birthDate }}</td>
                <td class="info-label">Nacionalidad</td>
                <td>{{ $patient->nationality ?? 'N/D' }}</td>
            </tr>
            <tr>
                <td class="info-label">Médico responsable</td>
                <td>{{ $doctorName !== '' ? $doctorName : 'N/D' }}</td>
                <td class="info-label">Especialidad</td>
                <td>{{ $doctor->specialty ?? 'N/D' }}</td>
            </tr>
            <tr>
                <td class="info-label">Orden</td>
                <td>{{ $order->order_number ?? 'N/D' }}</td>
                <td class="info-label">Correo / Teléfono</td>
                <td>{{ $patient->email ?? 'N/D' }} / {{ $patient->phone ?? 'N/D' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Declaraciones</div>
    <table class="result-table" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th style="width: 8%;" class="center">Bloque</th>
                <th style="width: 68%;">Pregunta</th>
                <th style="width: 24%;" class="center">Respuesta</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($declarations as $blockIndex => $block)
                @php
                    $questions = is_array($block['questions'] ?? null) ? $block['questions'] : [];
                @endphp
                @forelse ($questions as $question)
                    <tr>
                        <td class="center">{{ $blockIndex + 1 }}</td>
                        <td>{{ $question['text'] ?? 'N/D' }}</td>
                        <td class="center">{{ $boolText($question['value'] ?? null) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="center">{{ $blockIndex + 1 }}</td>
                        <td colspan="2">Sin preguntas registradas.</td>
                    </tr>
                @endforelse
                <tr>
                    <td class="info-label center">{{ $blockIndex + 1 }}</td>
                    <td class="info-label">Aclaraciones</td>
                    <td>{{ $fieldText($block['aclarations'] ?? null) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No hay declaraciones registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Datos clínicos</div>
    <table class="info-table" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td class="info-label" style="width: 16%;">Altura</td>
                <td style="width: 17%;">{{ $fieldText($clinicalData['height'] ?? null) }}</td>
                <td class="info-label" style="width: 16%;">Peso</td>
                <td style="width: 17%;">{{ $fieldText($clinicalData['weight'] ?? null) }}</td>
                <td class="info-label" style="width: 16%;">Pulso</td>
                <td style="width: 18%;">{{ $fieldText($clinicalData['pulse'] ?? null) }}</td>
            </tr>
            <tr>
                <td class="info-label">PA sistólica</td>
                <td>{{ $fieldText($clinicalData['blood_pressure_systolic'] ?? null) }}</td>
                <td class="info-label">PA diastólica</td>
                <td>{{ $fieldText($clinicalData['blood_pressure_diastolic'] ?? null) }}</td>
                <td class="info-label">Tipo de sangre</td>
                <td>{{ $fieldText($clinicalData['blood_type'] ?? null) }}</td>
            </tr>
            <tr>
                <td class="info-label">EMO</td>
                <td>{{ $fieldText($clinicalData['emo'] ?? null) }}</td>
                <td class="info-label">Glucosa</td>
                <td>{{ $fieldText($clinicalData['glucose'] ?? null) }}</td>
                <td class="info-label">Proteína</td>
                <td>{{ $fieldText($clinicalData['protein'] ?? null) }}</td>
            </tr>
            <tr>
                <td class="info-label">Observaciones</td>
                <td colspan="3">{{ $fieldText($clinicalData['observations'] ?? null) }}</td>
                <td class="info-label">Resultado</td>
                <td>{{ $fieldText($clinicalData['result'] ?? null) }}</td>
            </tr>
            <tr>
                <td class="info-label">Radiografía de tórax</td>
                <td>{{ $fieldText($clinicalData['chest_xray']['status'] ?? null) }}</td>
                <td class="info-label">Fecha radiografía</td>
                <td>{{ $fieldText($clinicalData['chest_xray']['date'] ?? null) }}</td>
                <td class="info-label">Tipo</td>
                <td>{{ $certificate->type }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Evaluación clínica</div>
    <table class="result-table" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th style="width: 70%;">Prueba</th>
                <th style="width: 30%;" class="center">Resultado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($clinicalChecks as $check)
                <tr>
                    <td>{{ $check['test'] ?? 'N/D' }}</td>
                    <td class="center">{{ $fieldText($check['result'] ?? null) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">No hay evaluaciones clínicas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Otras pruebas diagnósticas</div>
    <table class="result-table" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th style="width: 38%;">Prueba</th>
                <th style="width: 20%;" class="center">Estado</th>
                <th style="width: 42%;">Detalle</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($otherTests as $test)
                <tr>
                    <td>{{ $test['test'] ?? 'N/D' }}</td>
                    <td class="center">{{ $fieldText($test['status'] ?? null) }}</td>
                    <td>{{ $fieldText($test['detail'] ?? null) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No hay otras pruebas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Pruebas complementarias</div>
    <table class="info-table" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td class="info-label" style="width: 25%;">ECG</td>
                <td style="width: 25%;">{{ $fieldText($content['ecg'] ?? null) }}</td>
                <td class="info-label" style="width: 25%;">KOH</td>
                <td style="width: 25%;">{{ $fieldText($content['koh'] ?? null) }}</td>
            </tr>
            <tr>
                <td class="info-label">VDRL</td>
                <td>{{ $fieldText($content['vdrl'] ?? null) }}</td>
                <td class="info-label">Copro</td>
                <td>{{ $fieldText($content['copro'] ?? null) }}</td>
            </tr>
            <tr>
                <td class="info-label">Valoración psicológica</td>
                <td>{{ $fieldText($content['psychological_assessment'] ?? null) }}</td>
                <td class="info-label">Valoración odontológica</td>
                <td>{{ $fieldText($content['dental_assessment'] ?? null) }}</td>
            </tr>
            <tr>
                <td class="info-label">Otras pruebas</td>
                <td colspan="3">{{ $fieldText($content['extra_tests'] ?? null) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Aptitud para servicio en el mar</div>
    <table class="info-table" cellspacing="0" cellpadding="0">
        <tbody>
            <tr>
                <td class="info-label" style="width: 25%;">Vigía</td>
                <td style="width: 25%;">{{ $fieldText($aptitudeEval['watchkeeping'] ?? null) }}</td>
                <td class="info-label" style="width: 25%;">Restricciones</td>
                <td style="width: 25%;">{{ $boolText($aptitudeEval['restrictions'] ?? null) }}</td>
            </tr>
            <tr>
                <td class="info-label">Lentes correctivos</td>
                <td>{{ $boolText($aptitudeEval['corrective_lenses'] ?? null) }}</td>
                <td class="info-label">Descripción restricción</td>
                <td>{{ $fieldText($aptitudeEval['restriction_description'] ?? null) }}</td>
            </tr>
            <tr>
                <td class="info-label">Observaciones</td>
                <td colspan="3">{{ $fieldText($aptitudeEval['observations'] ?? null) }}</td>
            </tr>
        </tbody>
    </table>

    <table class="result-table" cellspacing="0" cellpadding="0" style="margin-top: 6px;">
        <thead>
            <tr>
                <th style="width: 70%;">Servicio</th>
                <th style="width: 30%;" class="center">Resultado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($serviceMatrix as $service)
                <tr>
                    <td>{{ $service['service'] ?? 'N/D' }}</td>
                    <td class="center">{{ $fieldText($service['result'] ?? null) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2">No hay matriz de servicios registrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Documento generado automáticamente por el sistema de salud ocupacional.
    </div>
</body>

</html>