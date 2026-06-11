<?php

namespace App\Http\Controllers;

use App\Models\Certificate;

class CertificateController extends Controller
{
    public static function filter(string $type = null)
    {
        $filters = [
            'patient_id_card' => request('patient_id_card', null),
            'certificate_number' => request('certificate_number', null),
            'start_date' => request('start_date', null),
            'end_date' => request('end_date', null),
        ];
        $query = Certificate::with('order.patient');
        if ($filters['patient_id_card']) {
            $query->whereHas('order.patient', function ($q) use ($filters) {
                $q->where('id_card', 'like', "%{$filters['patient_id_card']}%");
            });
        }
        if ($filters['certificate_number']) {
            $query->where('title', 'like', "%{$filters['certificate_number']}%");
        }
        if ($filters['start_date'] && $filters['end_date']) {
            $query->whereDate('created_at', '>=', $filters['start_date'])
                ->whereDate('created_at', '<=', $filters['end_date']);
        } elseif ($filters['start_date']) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        } elseif ($filters['end_date']) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }
        if ($type) {
            $query->where('type', $type);
        }
        $certificates = $query->orderByDesc('created_at')->paginate(10);
        return [
            'filters' => $filters,
            'certificates' => $certificates,
        ];
    }
}