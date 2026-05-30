<?php

namespace App\Http\Controllers;

use App\Models\Metadata;
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
        if (!Permission::has(Permission::READ_ORDERS)) {
            abort(403);
        }
        $filters = [
            'patient_id_card' => request('patient_id_card', null),
            'order_number' => request('order_number', null),
            'start_date' => request('start_date', null),
            'end_date' => request('end_date', null),
        ];

        // Query base
        $query = Order::with('patient');

        // Filtro por cédula del paciente
        if ($filters['patient_id_card']) {
            $query->whereHas('patient', function ($q) use ($filters) {
                $q->where('id_card', 'like', "%{$filters['patient_id_card']}%");
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

    public function edit(Request $request)
    {
        if (!Permission::has(Permission::WRITE_ORDERS)) {
            abort(403);
        }
        $idCard = $request->input('id_card', null);
        $patient = null;
        if ($idCard) {
            $patient = Patient::with('metadata')->where('id_card', '=', $idCard)->first();
            if ($patient) {
                $patient->date_of_birth = $patient->date_of_birth?->format('Y-m-d');
            }
        }
        return view('forms.orders', ['patient' => $patient]);
    }

    public function store(Request $request)
    {
        if (!Permission::has(Permission::WRITE_ORDERS)) {
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

        $order = new Order(['order_number' => $this->generate_number()]);

        $order->patient()->associate($patient);
        $order->save();

        foreach ($data as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'item' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        return redirect()->intended(route('orders.pdf', ['order' => $order]));
    }

    public function pdf(Order $order)
    {
        if (!Permission::has(Permission::READ_ORDERS)) {
            abort(403);
        }
        $order->load(['details', 'patient.metadata']);
        $pdf = PDF::loadView('documents.order', ['order' => $order]);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $order->id . '.pdf"');
    }

    private function generate_number()
    {
        $number = mt_rand(1000000, 9999999);
        $exists = Order::where('order_number', $number)->exists();
        if ($exists) {
            return $this->generate_number();
        }
        return $number;
    }
}
