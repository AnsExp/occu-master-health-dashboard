<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateType;
use App\Models\Order;
use App\Models\Permission;
use Illuminate\Http\Request;

class OccupationalController extends Controller
{
    public function index()
    {
        if (!Permission::has(Permission::READ_OCCUPATIONAL)) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        $order_number = request('order_number');
        $order = null;
        if ($order_number) {
            $order = Order::where('order_number', $order_number)->first();
        }
        return view('forms.occupational', ['order' => $order]);
    }

    public function store(Request $request)
    {
        if (!Permission::has(Permission::WRITE_OCCUPATIONAL)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $validated = $request->validate([
            'order.id' => ['required', 'exists:orders,id'],
            'order.order_number' => ['required', 'exists:orders,order_number'],
            'doctor.id' => ['required', 'exists:doctors,id'],
            'medical_exam' => ['required', 'array'],
        ]);

        $certificate_existing = Certificate::where('order_id', $validated['order']['id'])->where('type', CertificateType::OCCUPATIONAL)->exists();

        if ($certificate_existing) {
            return response()->json(['error' => 'Ya existe un certificado para esta orden.'], 422);
        }

        $certificate = Certificate::create([
            'title' => 'Certificado de Salud Ocupacional - Orden #' . $validated['order']['order_number'],
            'type' => CertificateType::OCCUPATIONAL,
            'order_id' => $validated['order']['id'],
            'doctor_id' => $validated['doctor']['id'],
            'patient_id' => Order::find($validated['order']['id'])->patient->id,
            'content' => json_encode($validated['medical_exam']),
        ]);

        return redirect()->route('certificates.pdf', ['certificate' => $certificate]);
    }
}
