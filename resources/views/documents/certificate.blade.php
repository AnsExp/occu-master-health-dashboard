<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    @php
        $pdfCss = file_get_contents(public_path('css/pdf-certificate.css'));
    @endphp

    <style>
        {!! $pdfCss !!}
    </style>
</head>

<body>
    @php
        \Carbon\Carbon::setLocale('es');

        $patient = $certificate->patient;
        $patientName = $patient ? trim(($patient->first_name ?? '') . ' ' . ($patient->last_name ?? '')) : 'N/A';
        $issuedAt = $certificate->created_at ? $certificate->created_at->translatedFormat('j \\d\\e F, Y') : 'N/A';
        $birthDate = !empty($patient?->date_of_birth)
            ? \Illuminate\Support\Carbon::parse($patient->date_of_birth)->translatedFormat('j \\d\\e F, Y')
            : 'N/A';

        $contentRaw = $certificate->content;
        $content = is_array($contentRaw) ? $contentRaw : (json_decode($contentRaw ?? '', true) ?: []);
        $resumeLimitations = $content['resume-limitations'] ?? 'N/A';
        $ifYesDetails = $content['if-yes-details'] ?? 'N/A';
        $ifYesDetails = $ifYesDetails !== null && $ifYesDetails !== '' ? $ifYesDetails : 'N/A';
        $declarations = is_array($content['declarations'] ?? null) ? $content['declarations'] : [];
    @endphp

    <div class="document">
        <div class="document-header">
            <div class="header-title">{{ $certificate->title }}</div>
            <div class="header-subtitle">Reporte de ficha medica</div>
            <div class="header-meta">No. {{ $certificate->id }} | Fecha de emision: {{ $issuedAt }}</div>
        </div>

        <table class="info-table" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th colspan="2">Informacion del certificado</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="label">Paciente</td>
                    <td>{{ $patientName !== '' ? $patientName : 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Cedula</td>
                    <td>{{ $patient->id_card ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Fecha de nacimiento</td>
                    <td>{{ $birthDate }}</td>
                </tr>
                <tr>
                    <td class="label">Nacionalidad</td>
                    <td>{{ $patient->nationality ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Correo</td>
                    <td>{{ $patient->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Telefono</td>
                    <td>{{ $patient->phone ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>

        <table class="declarations-table" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>Declaraciones del examen</th>
                    <th class="result-col">Respuesta</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($declarations as $item)
                    <tr>
                        <td>{{ $item['question'] ?? 'N/A' }}</td>
                        <td class="result-col">
                            @if (array_key_exists('answer', $item))
                                {{ is_bool($item['answer']) ? ($item['answer'] ? 'SI' : 'NO') : $item['answer'] }}
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No hay declaraciones registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <table class="info-table" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th colspan="2">Contenido del examen medico</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="label">Detalles (si aplica)</td>
                    <td>{{ $ifYesDetails }}</td>
                </tr>
                <tr>
                    <td class="label">Resumen de limitaciones</td>
                    <td>{{ $resumeLimitations }}</td>
                </tr>
            </tbody>
        </table>

        <div class="document-footer">
            Documento generado automaticamente por el sistema de salud ocupacional.
        </div>
    </div>
</body>

</html>