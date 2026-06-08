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
            font-size: 12px;
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
        .exam-table,
        .result-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td,
        .exam-table td,
        .exam-table th,
        .result-table td,
        .result-table th {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
        }

        .info-label,
        .exam-table th,
        .result-table th {
            background: #f9fafb;
            font-weight: 700;
            color: #374151;
        }

        .muted {
            color: #6b7280;
            font-size: 10px;
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
        use App\Enums\SpecialtyEnum;
        \Carbon\Carbon::setLocale('es');

        $patient = $certificate->order->patient;
        $doctor = $certificate->doctor;
        $order = $certificate->order;
        $patientName = $patient ? trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? '')) : 'N/D';
        $doctorName = $doctor ? trim(($doctor->first_name ?? '') . ' ' . ($doctor->last_name ?? '')) : 'N/D';
        $issuedAt = $certificate->created_at ? $certificate->created_at->translatedFormat('j \\d\\e F, Y') : 'N/A';
        $birthDate = !empty($patient?->birth_date)
            ? \Illuminate\Support\Carbon::parse($patient->birth_date)->translatedFormat('j \\d\\e F, Y')
            : 'N/D';

        $contentRaw = $certificate->content;
        $content = is_array($contentRaw) ? $contentRaw : (json_decode($contentRaw ?? '', true) ?: []);

        $hearing = is_array($content['hearing'] ?? null) ? $content['hearing'] : [];
        $speechWhisper = is_array($content['speech_whisper'] ?? null) ? $content['speech_whisper'] : [];
        $ishihara = is_array($content['ishihara'] ?? null) ? $content['ishihara'] : [];

        $fieldText = static function ($value) {
            return $value !== null && $value !== '' ? $value : 'N/D';
        };

        $ishiharaLabels = [
            'amarillo' => 'Amarillo',
            'verde' => 'Verde',
            'rojo' => 'Rojo',
            'azul' => 'Azul',
        ];
    @endphp

    <table class="header" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <p class="title">{{ $certificate->title }}</p>
                <p class="subtitle">Resultado de evaluación de audiología</p>
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
                <td>{{ SpecialtyEnum::fromCode($doctor->specialty->name)?->label() ?? 'N/D' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Audición</div>
    <table class="exam-table" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th style="width: 40%;">Tipo</th>
                <th style="width: 30%;" class="center">Oído derecho</th>
                <th style="width: 30%;" class="center">Oído izquierdo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="info-label">Resultado de audición</td>
                <td class="center">{{ $fieldText($hearing['right'] ?? null) }}</td>
                <td class="center">{{ $fieldText($hearing['left'] ?? null) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Prueba del habla y del susurro (3 metros)</div>
    <table class="result-table" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th style="width: 40%;">Oído</th>
                <th style="width: 60%;" class="center">Resultado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Derecho</td>
                <td class="center">{{ $fieldText($speechWhisper['right'] ?? null) }}</td>
            </tr>
            <tr>
                <td>Izquierdo</td>
                <td class="center">{{ $fieldText($speechWhisper['left'] ?? null) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Test de Ishihara</div>
    <table class="result-table" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th style="width: 40%;">Color</th>
                <th style="width: 60%;" class="center">Resultado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ishiharaLabels as $key => $label)
                @php
                    $value = $ishihara[$key] ?? $ishihara[$label] ?? null;
                @endphp
                <tr>
                    <td>{{ $label }}</td>
                    <td class="center">{{ strtoupper($fieldText($value)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Documento generado automáticamente por el sistema de salud ocupacional.
    </div>
</body>

</html>