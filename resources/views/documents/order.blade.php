<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Pago</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #111827;
            font-size: 12px;
            line-height: 1.4;
            margin: 22px;
        }

        .header {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .header td {
            vertical-align: top;
        }

        .brand-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
        }

        .brand-subtitle {
            margin: 2px 0 0;
            color: #4b5563;
            font-size: 11px;
        }

        .document-title {
            text-align: right;
            font-size: 16px;
            font-weight: 700;
            margin: 0;
        }

        .document-meta {
            text-align: right;
            margin-top: 4px;
            color: #374151;
            font-size: 11px;
        }

        .section-title {
            margin: 14px 0 6px;
            padding: 6px 8px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .info-table,
        .items-table,
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: 1px solid #e5e7eb;
            padding: 7px 8px;
            vertical-align: top;
            width: 50%;
        }

        .label {
            display: block;
            color: #6b7280;
            font-size: 10px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .value {
            color: #111827;
            font-size: 12px;
            font-weight: 600;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #d1d5db;
            padding: 7px 8px;
        }

        .items-table th {
            background: #f9fafb;
            color: #374151;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .totals-wrapper {
            margin-top: 10px;
            width: 46%;
            margin-left: auto;
        }

        .totals-table td {
            border: 1px solid #d1d5db;
            padding: 7px 8px;
        }

        .totals-table .name {
            background: #f9fafb;
            font-weight: 700;
        }

        .totals-table .grand-total {
            font-size: 13px;
            font-weight: 700;
            background: #eef2ff;
        }

        .footer-note {
            margin-top: 18px;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            color: #4b5563;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    @php
        $patient = $order->patient;
        $patientMetadata = $patient?->metadata ?? collect();
        $address = optional($patientMetadata->firstWhere('meta_key', 'address'))->meta_value;
        $section = optional($patientMetadata->firstWhere('meta_key', 'section'))->meta_value;
        $hierarchy = optional($patientMetadata->firstWhere('meta_key', 'hierarchy'))->meta_value;
        $role = optional($patientMetadata->firstWhere('meta_key', 'role'))->meta_value;

        $subtotal = $order->details->sum(fn($detail) => $detail->quantity * $detail->price);
        $taxRate = 0.15;
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $tax;
    @endphp

    <table class="header">
        <tr>
            <td>
                <p class="brand-title">OCCU MASTER HEALTH</p>
                <p class="brand-subtitle">Centro de Evaluaciones y Certificaciones</p>
            </td>
            <td>
                <p class="document-title">ORDEN DE PAGO: {{ $order->order_number }}</p>
                <div class="document-meta">
                    <div><strong>Fecha:</strong> {{ $order->created_at?->format('d/m/Y H:i') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Datos del Paciente</div>
    <table class="info-table">
        <tr>
            <td>
                <span class="label">Paciente</span>
                <span class="value">{{ trim(($patient?->first_name ?? '') . ' ' . ($patient?->last_name ?? '')) ?: '-' }}</span>
            </td>
            <td>
                <span class="label">Cédula</span>
                <span class="value">{{ $patient?->id_card ?? '-' }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Correo</span>
                <span class="value">{{ $patient?->email ?? '-' }}</span>
            </td>
            <td>
                <span class="label">Teléfono</span>
                <span class="value">{{ $patient?->phone ?? '-' }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="label">Sección / Jerarquía</span>
                <span class="value">{{ $section ?: '-' }} / {{ $hierarchy ?: '-' }}</span>
            </td>
            <td>
                <span class="label">Cargo</span>
                <span class="value">{{ $role ?: '-' }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="label">Dirección</span>
                <span class="value">{{ $address ?: '-' }}</span>
            </td>
        </tr>
    </table>

    <div class="section-title">Detalle de Servicios</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 7%;">#</th>
                <th style="width: 49%;">Servicio</th>
                <th style="width: 12%;" class="text-center">Cantidad</th>
                <th style="width: 16%;" class="text-right">Precio Unitario</th>
                <th style="width: 16%;" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($order->details as $index => $detail)
                @php
                    $lineSubtotal = $detail->quantity * $detail->price;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $detail->item }}</td>
                    <td class="text-center">{{ $detail->quantity }}</td>
                    <td class="text-right">${{ number_format($detail->price, 2) }}</td>
                    <td class="text-right">${{ number_format($lineSubtotal, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay detalles registrados en esta orden.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totals-wrapper">
        <table class="totals-table">
            <tr>
                <td class="name">Subtotal</td>
                <td class="text-right">${{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td class="name">IVA ({{ number_format($taxRate * 100, 0) }}%)</td>
                <td class="text-right">${{ number_format($tax, 2) }}</td>
            </tr>
            <tr>
                <td class="name grand-total">Total</td>
                <td class="text-right grand-total">${{ number_format($total, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer-note">
        Documento generado automáticamente por el sistema OCCU MASTER HEALTH.
    </div>
</body>

</html>