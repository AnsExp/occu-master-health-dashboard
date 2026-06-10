<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Http\Requests\AudiologyRequest;
use App\Models\Certificate;
use App\Models\CertificateType;
use App\Models\Order;
use function Laravel\Prompts\error;

class AudiologyController extends Controller
{
    public function create()
    {
        if (!PermissionEnum::can(PermissionEnum::VIEW_AUDIOLOGY)) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }
        $order_number = request('order_number');
        $order = null;
        if ($order_number) {
            $order = Order::where('order_number', $order_number)->first();
            if (!$order) {
                error('Número de orden no encontrado. Por favor, ingrese un número de orden válido.');
                return redirect()->route('form.audiology');
            }
            $certificate_existing = Certificate::where('order_id', $order->id)->where('type', CertificateType::AUDIOLOGY)->exists();
            if ($certificate_existing) {
                error('Ya existe un certificado de audiología para esta orden. Por favor, ingrese otro número de orden.');
                return redirect()->route('form.audiology');
            }
        }
        return view('forms.audiology', ['order' => $order]);
    }

    public function store(AudiologyRequest $request)
    {
        if (!PermissionEnum::can(PermissionEnum::STORE_AUDIOLOGY)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $validated = $request->validated();

        $certificate = Certificate::create([
            'title' => 'Certificado de Audiología - Orden #' . $validated['order']['order_number'],
            'type' => CertificateType::AUDIOLOGY,
            'order_id' => $validated['order']['id'],
            'doctor_id' => $validated['doctor']['id'],
            'certificate_number' => Certificate::generate_number(),
            'content' => $validated['medical_exam'],
        ]);

        return redirect()->route('certificates.pdf', ['certificate' => $certificate]);
    }
}