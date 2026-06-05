<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateType;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PDFController extends Controller
{
    public function generate(Certificate $certificate)
    {
        return match ($certificate->type) {
            CertificateType::AUDIOLOGY => $this->document('documents.audiology', $certificate, 'audiology'),
            CertificateType::OPHTHALMOLOGY => $this->document('documents.ophthalmology', $certificate, 'ophthalmology'),
            CertificateType::OCCUPATIONAL => $this->document('documents.occupational', $certificate, 'occupational'),
            default => view('pages.home')
        };
    }

    private function document(string $view, Certificate $certificate, string $filename)
    {
        $certificate->load(['patient', 'doctor', 'order']);

        $pdf = PDF::loadView($view, ['certificate' => $certificate]);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '-' . $certificate->id . '.pdf"');
    }
}
