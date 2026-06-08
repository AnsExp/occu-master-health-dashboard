<?php

namespace App\Http\Controllers;

use App\Enums\ActionEnum;
use App\Enums\TableEnum;
use App\Models\Metadata;
use App\Models\Certificate;
use App\Models\CertificateType;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Patient;
use App\Models\Permission;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class OrderController extends Controller
{
    public function index()
    {
        if (!request()->user()->can('viewAny', Order::class)) {
            abort(403);
        }
        $filters = [
            'id_card' => request('id_card', null),
            'order_number' => request('order_number', null),
            'start_date' => request('start_date', null),
            'end_date' => request('end_date', null),
        ];

        // Query base
        $query = Order::with(['patient', 'details']);

        // Filtro por cédula del paciente
        if ($filters['id_card']) {
            $query->whereHas('patient', function ($q) use ($filters) {
                $q->where('id_card', 'like', "%{$filters['id_card']}%");
            });
        }

        // Filtro por número de orden
        if ($filters['order_number']) {
            $query->where('order_number', 'like', "%{$filters['order_number']}%");
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
        $ordersPaginated = $query->orderByDesc('created_at')->paginate($perPage);

        return view('pages.orders', ['filters' => $filters, 'orders' => $ordersPaginated]);
    }

    public function create()
    {
        if (!request()->user()->can('create', Order::class)) {
            abort(403);
        }
        $idCard = request('id_card', null);
        $patient = null;
        if ($idCard) {
            $patient = Patient::with('metadata')->where('id_card', '=', $idCard)->first();
            if ($patient) {
                $patient->date_of_birth = $patient->date_of_birth?->format('Y-m-d');
            }
        }
        return view('forms.orders', ['patient' => $patient]);
    }

    public function edit(Order $order)
    {
        // This method is not implemented as per the provided routes and views. If needed, it can be implemented similarly to the create method, but loading the existing order data for editing.
    }

    public function store(Request $request)
    {
        if (!request()->user()->can('create', Order::class)) {
            abort(403);
        }
        $details = $request->input('order.details', []);
        $data = [];
        foreach ($details as $detail) {
            if (isset($detail['selected']) && boolval($detail['selected'])) {
                $data[] = $detail;
            }
        }
        $data = array_map(function ($item) {
            return [
                'name' => $item['name'] ?? '',
                'price' => floatval($item['price'] ?? 0),
                'quantity' => intval($item['quantity'] ?? 0),
            ];
        }, $data);

        $patient = Patient::find($request->input('patient.id')) ?? new Patient();
        $patient->fill($request->input('patient', []));
        $patient->save();
        foreach ([
            'role' => $request->input('patient.role'),
            'section' => $request->input('patient.section'),
            'address' => $request->input('patient.address'),
            'hierarchy' => $request->input('patient.hierarchy'),
        ] as $key => $value) {
            Metadata::updateOrCreate(
                [
                    'meta_type' => 'patient',
                    'meta_id' => $patient->id,
                    'meta_key' => $key,
                ],
                [
                    'meta_value' => $value,
                ]
            );
        }

        $order = new Order(['order_number' => Order::generate_number()]);

        $order->patient()->associate($patient);
        $order->save();

        foreach ($data as $item) {
            $order->details()->create([
                'item' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        $order->load(['patient', 'details']);

        return redirect()->intended(route('orders.pdf', ['order' => $order->order_number]));
    }

    public function pdf(Order $order)
    {
        if (!request()->user()->can('view', $order)) {
            abort(403);
        }

        $order->load(['details', 'patient.metadata']);

        $documents = [];

        $orderPdf = PDF::loadView('documents.order', ['order' => $order]);
        $orderPdf->setPaper('A4', 'portrait');
        $orderPdf->render();
        $documents[] = $orderPdf->output();

        $certificates = $order->certificates()
            ->orderBy('created_at')
            ->get();

        foreach ($certificates as $certificate) {
            $certificatePdf = $this->buildCertificatePdf($certificate);
            if ($certificatePdf !== null) {
                $documents[] = $certificatePdf;
            }
        }

        $mergedPdf = $this->mergePdfDocuments($documents);

        return response($mergedPdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="order-' . $order->order_number . '.pdf"');
    }

    private function buildCertificatePdf(Certificate $certificate): ?string
    {
        $view = match ($certificate->type) {
            CertificateType::AUDIOLOGY => 'documents.audiology',
            CertificateType::OPHTHALMOLOGY => 'documents.ophthalmology',
            CertificateType::OCCUPATIONAL => 'documents.occupational',
            default => null,
        };

        if ($view === null) {
            return null;
        }

        $certificate->loadMissing(['doctor', 'order']);

        $pdf = PDF::loadView($view, ['certificate' => $certificate]);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf->output();
    }

    private function mergePdfDocuments(array $pdfDocuments): string
    {
        $fpdiClass = '\\setasign\\Fpdi\\Fpdi';
        $streamReaderClass = '\\setasign\\Fpdi\\PdfParser\\StreamReader';

        if (!class_exists($fpdiClass) || !class_exists($streamReaderClass)) {
            throw new \RuntimeException('FPDI library is not available.');
        }

        $fpdi = new $fpdiClass();

        foreach ($pdfDocuments as $pdfContent) {
            $pageCount = $fpdi->setSourceFile($streamReaderClass::createByString($pdfContent));

            for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
                $templateId = $fpdi->importPage($pageNumber);
                $size = $fpdi->getTemplateSize($templateId);
                $orientation = $size['width'] > $size['height'] ? 'L' : 'P';

                $fpdi->AddPage($orientation, [$size['width'], $size['height']]);
                $fpdi->useTemplate($templateId);
            }
        }

        return $fpdi->Output('S');
    }
}
