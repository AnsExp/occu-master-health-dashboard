<?php

namespace App\Http\Controllers;

use App\Http\Requests\AudiologyRequest;
use App\Models\Certificate;
use App\Models\CertificateType;
use App\Models\Order;
use App\Policies\AudiologyPolicy;

class AudiologyController extends Controller
{
    private AudiologyPolicy $policy;

    public function __construct()
    {
        $this->policy = new AudiologyPolicy();
    }

    public function index()
    {
        if (!$this->policy->viewAny(auth()->user())) {
            abort(403, 'No tienes permiso ver los certificados de audiología.');
        }
        $filter = CertificateController::filter(CertificateType::AUDIOLOGY);
        return view('pages.certificate', [
            'filters' => $filter['filters'],
            'certificates' => $filter['certificates'],
        ])->with('title', 'Audiología');
    }

    public function create()
    {
        if (!$this->policy->create(auth()->user())) {
            abort(403, 'No tienes permiso para crear certificados de audiología.');
        }
        $order_number = request('order_number');
        $order = null;
        if ($order_number) {
            $order = Order::where('order_number', $order_number)->first();
            if (!$order) {
                return view('forms.audiology')->with('error', 'Número de orden no encontrado. Por favor, ingrese un número de orden válido.');
            }
            $certificate_existing = Certificate::where('order_id', $order->id)->where('type', CertificateType::AUDIOLOGY)->exists();
            if ($certificate_existing) {
                return view('forms.audiology')->with('error', 'Ya existe un certificado de audiología para esta orden. Por favor, ingrese otro número de orden.');
            }
        }
        return view('forms.audiology', ['order' => $order]);
    }

    public function store(AudiologyRequest $request)
    {
        if (!$this->policy->create(auth()->user())) {
            abort(403, 'No tienes permiso para crear certificados de audiología.');
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

    public function show(Certificate $certificate)
    {
        if (!$this->policy->view(auth()->user(), $certificate)) {
            abort(403, 'No tienes permiso para acceder a este certificado.');
        }
        return view('documents.audiology', ['certificate' => $certificate]);
    }

    public function destroy(Certificate $certificate)
    {
        if (!$this->policy->delete(auth()->user(), $certificate)) {
            abort(403, 'No tienes permiso para eliminar certificados de audiología.');
        }
        $certificate->delete();
        return $this->index()->with('success', 'Certificado de audiología eliminado exitosamente.');
    }
}