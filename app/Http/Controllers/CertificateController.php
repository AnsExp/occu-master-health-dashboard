<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class CertificateController extends Controller
{
    public function index()
    {
        $filters = [
            'patient_id_card' => request('patient_id_card', null),
            'certificate_number' => request('certificate_number', null),
            'start_date' => request('start_date', null),
            'end_date' => request('end_date', null),
        ];

        // Query base
        $query = Certificate::with('patient');

        // Filtro por cédula del paciente
        if ($filters['patient_id_card']) {
            $query->whereHas('patient', function ($q) use ($filters) {
                $q->where('id_card', 'like', "%{$filters['patient_id_card']}%");
            });
        }

        // Filtro por número de certificado
        if ($filters['certificate_number']) {
            $query->where('title', 'like', "%{$filters['certificate_number']}%");
        }

        // Filtro por rango de fechas
        if ($filters['start_date'] && $filters['end_date']) {
            // Ambas fechas (between inclusive)
            $query->whereDate('created_at', '>=', $filters['start_date'])
                  ->whereDate('created_at', '<=', $filters['end_date']);
        } elseif ($filters['start_date']) {
            // Solo desde
            $query->whereDate('created_at', '>=', $filters['start_date']);
        } elseif ($filters['end_date']) {
            // Solo hasta
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        // Ejecutar query con paginación
        $perPage = 10;
        $certificatesPaginated = $query->orderByDesc('created_at')->paginate($perPage);

        // Formatear resultados
        $certificates = $certificatesPaginated->getCollection()->map(function ($cert) {
            return [
                'number' => $cert->id,
                'title' => $cert->title,
                'patient' => $cert->patient ? $cert->patient->first_name . ' ' . $cert->patient->last_name : 'N/A',
                'id_card' => $cert->patient ? $cert->patient->id_card : 'N/A',
                'issued_at' => $cert->created_at->format('Y-m-d'),
            ];
        });

        $certificatesPaginated->setCollection($certificates);

        return view('pages.certificate', [
            'filters' => $filters,
            'certificates' => $certificatesPaginated,
        ]);
    }

    public function pdf(Certificate $certificate)
    {
        $certificate->loadMissing('patient');

        $pdf = PDF::loadView('pdf.certificate', ['certificate' => $certificate]);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $certificate->id . '.pdf"');
    }
}