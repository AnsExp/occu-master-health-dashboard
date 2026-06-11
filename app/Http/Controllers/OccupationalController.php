<?php

namespace App\Http\Controllers;

use App\Http\Requests\OccupationalRequest;
use App\Models\Certificate;
use App\Models\CertificateType;
use App\Models\Order;
use App\Policies\OccupationalPolicy;

class OccupationalController extends Controller
{
    private OccupationalPolicy $policy;

    public function __construct()
    {
        $this->policy = new OccupationalPolicy();
    }

    public function index()
    {
        if (!$this->policy->viewAny(auth()->user())) {
            abort(403, 'No tienes permiso para acceder a los certificados de salud ocupacional.');
        }
        $filter = CertificateController::filter(CertificateType::OCCUPATIONAL);
        return view('pages.certificate', [
            'filters' => $filter['filters'],
            'certificates' => $filter['certificates'],
        ])->with('title', 'Salud Ocupacional');
    }

    public function create()
    {
        if (!$this->policy->create(auth()->user())) {
            abort(403, 'No tienes permiso para crear certificados de salud ocupacional.');
        }
        $order_number = request('order_number');
        $order = null;
        if ($order_number) {
            $order = Order::where('order_number', $order_number)->first();
            if (!$order) {
                session()->flash('error', 'Número de orden no encontrado. Por favor, ingrese un número de orden válido.');
                return redirect()->route('occupational.index');
            }
            $certificate_existing = Certificate::where('order_id', $order->id)->where('type', CertificateType::OCCUPATIONAL)->exists();
            if ($certificate_existing) {
                session()->flash('error', 'Ya existe un certificado de salud ocupacional para esta orden. Por favor, ingrese otro número de orden.');
                return redirect()->route('occupational.index');
            }
        }
        return view('forms.occupational', ['order' => $order]);
    }

    public function store(OccupationalRequest $request)
    {
        if (!$this->policy->create(auth()->user())) {
            abort(403, 'No tienes permiso para crear certificados de salud ocupacional.');
        }

        $validated = $request->validated();

        $certificate = Certificate::create([
            'title' => 'Certificado de Salud Ocupacional - Orden #' . $validated['order']['order_number'],
            'type' => CertificateType::OCCUPATIONAL,
            'order_id' => $validated['order']['id'],
            'doctor_id' => $validated['doctor']['id'],
            'certificate_number' => Certificate::generate_number(),
            'content' => $validated['medical_exam'],
        ]);

        return redirect()->route('certificates.pdf', ['certificate' => $certificate]);
    }
}
