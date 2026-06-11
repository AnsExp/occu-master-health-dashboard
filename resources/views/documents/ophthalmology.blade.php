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

        $visualAcuity = is_array($content['visual_acuity'] ?? null) ? $content['visual_acuity'] : [];
        $distanceWithout = $visualAcuity['distance']['without'] ?? [];
        $distanceWith = $visualAcuity['distance']['with'] ?? [];
        $nearWithout = $visualAcuity['near']['without'] ?? [];
        $nearWith = $visualAcuity['near']['with'] ?? [];

        $visualField = is_array($content['visual_field'] ?? null) ? $content['visual_field'] : [];
        $colorVision = $content['color_vision'] ?? 'N/D';

        $fieldText = static function ($value) {
            return $value !== null && $value !== '' ? $value : 'N/D';
        };
    @endphp

    <table class="header" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                <p class="title">{{ $certificate->title }}</p>
                <p class="subtitle">Resultado de evaluación oftalmológica</p>
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
                <td>{{ $doctor->specialty->name ?? 'N/D' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Agudeza visual</div>
    <table class="exam-table" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th rowspan="2" style="width: 14%;">Tipo</th>
                <th colspan="3" class="center">Sin corrección</th>
                <th colspan="3" class="center">Con corrección</th>
            </tr>
            <tr>
                <th class="center" style="width: 14%;">Ojo derecho</th>
                <th class="center" style="width: 14%;">Ojo izquierdo</th>
                <th class="center" style="width: 14%;">Ambos</th>
                <th class="center" style="width: 14%;">Ojo derecho</th>
                <th class="center" style="width: 14%;">Ojo izquierdo</th>
                <th class="center" style="width: 14%;">Ambos</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="info-label">Distancia</td>
                <td class="center">{{ $fieldText($distanceWithout['right'] ?? null) }}</td>
                <td class="center">{{ $fieldText($distanceWithout['left'] ?? null) }}</td>
                <td class="center">{{ $fieldText($distanceWithout['both'] ?? null) }}</td>
                <td class="center">{{ $fieldText($distanceWith['right'] ?? null) }}</td>
                <td class="center">{{ $fieldText($distanceWith['left'] ?? null) }}</td>
                <td class="center">{{ $fieldText($distanceWith['both'] ?? null) }}</td>
            </tr>
            <tr>
                <td class="info-label">Cerca</td>
                <td class="center">{{ $fieldText($nearWithout['right'] ?? null) }}</td>
                <td class="center">{{ $fieldText($nearWithout['left'] ?? null) }}</td>
                <td class="center">{{ $fieldText($nearWithout['both'] ?? null) }}</td>
                <td class="center">{{ $fieldText($nearWith['right'] ?? null) }}</td>
                <td class="center">{{ $fieldText($nearWith['left'] ?? null) }}</td>
                <td class="center">{{ $fieldText($nearWith['both'] ?? null) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Resultados complementarios</div>
    <table class="result-table" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th style="width: 60%;">Prueba</th>
                <th style="width: 40%;" class="center">Resultado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Campo visual - Ojo derecho</td>
                <td class="center">{{ $fieldText($visualField['right'] ?? null) }}</td>
            </tr>
            <tr>
                <td>Campo visual - Ojo izquierdo</td>
                <td class="center">{{ $fieldText($visualField['left'] ?? null) }}</td>
            </tr>
            <tr>
                <td>Visión cromática</td>
                <td class="center">{{ $fieldText($colorVision) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Documento generado automáticamente por el sistema de salud ocupacional.
    </div>
</body>

</html>