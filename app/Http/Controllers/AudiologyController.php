<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateType;
use App\Models\Order;
use App\Models\Permission;
use Illuminate\Http\Request;

class AudiologyController extends Controller
{
    public function index()
    {
        if (!Permission::has(Permission::READ_AUDIOLOGY)) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        $order_number = request('order_number');
        $order = null;
        if ($order_number) {
            $order = Order::where('order_number', $order_number)->first();
        }
        return view('forms.audiology', ['order' => $order]);
    }

    public function store(Request $request)
    {
        if (!Permission::has(Permission::WRITE_AUDIOLOGY)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $validated = $request->validate([
            'order.id' => ['required', 'exists:orders,id'],
            'order.order_number' => ['required', 'exists:orders,order_number'],
            'doctor.id' => ['required', 'exists:doctors,id'],
            'medical_exam.hearing' => ['required', 'array'],
            'medical_exam.hearing.*' => ['required', 'string'],
            'medical_exam.ishihara' => ['required', 'array'],
            'medical_exam.ishihara.*' => ['required', 'string', 'in:N,CP'],
            'medical_exam.speech_whisper' => ['required', 'array'],
            'medical_exam.speech_whisper.*' => ['required', 'string', 'in:Susurro,Normal'],
        ]);
        $certificate_existing = Certificate::where('order_id', $validated['order']['id'])->where('type', CertificateType::AUDIOLOGY)->exists();
        if ($certificate_existing) {
            session()->flash('error', 'Ya existe un certificado de audiología para esta orden.');
            return redirect()->route('form.audiology');
        }
        $order = Order::find($validated['order']['id']);
        $certificate = Certificate::create([
            'title' => 'Certificado de Audiología - Orden #' . $validated['order']['order_number'],
            'type' => CertificateType::AUDIOLOGY,
            'order_id' => $validated['order']['id'],
            'doctor_id' => $validated['doctor']['id'],
            'patient_id' => $order->patient->id,
            'content' => json_encode($validated['medical_exam']),
        ]);
        return redirect()->route('certificates.pdf', ['certificate' => $certificate]);
    }
}