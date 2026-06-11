<?php

namespace App\Http\Controllers;

use App\Http\Requests\OphthalmologyRequest;
use App\Models\Certificate;
use App\Models\Order;
use App\Models\CertificateType;
use App\Policies\OphthalmologyPolicy;

class OphthalmologyController extends Controller
{
    private OphthalmologyPolicy $policy;

    public function __construct()
    {
        $this->policy = new OphthalmologyPolicy();
    }

    public function index()
    {
        if (!$this->policy->viewAny(auth()->user())) {
            abort(403, 'No tienes permiso para acceder a los certificados de oftalmología.');
        }
        $filter = CertificateController::filter(CertificateType::OPHTHALMOLOGY);
        return view('pages.certificate', [
            'filters' => $filter['filters'],
            'certificates' => $filter['certificates'],
        ])->with('title', 'Oftalmología');
    }

    public function create()
    {
        if (!$this->policy->create(auth()->user())) {
            abort(403, 'No tienes permiso para crear certificados de oftalmología.');
        }
        $order_number = request('order_number');
        $order = null;
        if ($order_number) {
            $order = Order::where('order_number', $order_number)->first();
            if (!$order) {
                session()->flash('error', 'Número de orden no encontrado. Por favor, ingrese un número de orden válido.');
                return redirect()->route('ophthalmology.index');
            }
            $certificate_existing = Certificate::where('order_id', $order->id)->where('type', CertificateType::OPHTHALMOLOGY)->exists();
            if ($certificate_existing) {
                session()->flash('error', 'Ya existe un certificado de oftalmología para esta orden. Por favor, ingrese otro número de orden.');
                return redirect()->route('ophthalmology.index');
            }
        }
        return view('forms.ophthalmology', ['order' => $order]);
    }

    public function store(OphthalmologyRequest $request)
    {
        if (!$this->policy->create(auth()->user())) {
            abort(403, 'No tienes permiso para crear certificados de oftalmología.');
        }
        $validated = $request->validated();
        $certificate = Certificate::create([
            'title' => 'Certificado de Oftalmología - Orden #' . $validated['order']['order_number'],
            'type' => CertificateType::OPHTHALMOLOGY,
            'order_id' => $validated['order']['id'],
            'doctor_id' => $validated['doctor']['id'],
            'certificate_number' => Certificate::generate_number(),
            'content' => $validated['medical_exam'],
        ]);
        return redirect()->route('certificates.pdf', ['certificate' => $certificate]);
    }
}
