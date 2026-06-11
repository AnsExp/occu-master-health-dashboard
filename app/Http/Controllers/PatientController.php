<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Plan;
use App\Models\PlanDetail;
use App\Policies\PatientPolicy;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    private PatientPolicy $policy;

    public function __construct()
    {
        $this->policy = new PatientPolicy();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!$this->policy->viewAny(request()->user())) {
            abort(403, 'No tienes permiso para acceder a los pacientes.');
        }
        $sort = request('sort', 'first_name');
        $direction = request('direction', 'asc');
        $data = Patient::orderBy($sort, $direction)->paginate(10);
        return view('pages.patients', compact('data', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$this->policy->create(request()->user())) {
            abort(403, 'No tienes permiso para crear pacientes.');
        }
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
            'periodicity' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);
        $items = explode("\n", $request->input('items', ''));
        $plan = Plan::create($data);
        foreach ($items as $item) {
            $detail = new PlanDetail();
            $detail->plan_id = $plan->id;
            $detail->detail = trim($item);
            $detail->save();
        }
        return redirect()->route('plans')->with('status', 'Plan registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        if (!$this->policy->delete(request()->user(), $patient)) {
            abort(403, 'No tienes permiso para eliminar pacientes.');
        }
        $patient->delete();
        return redirect()->route('patients')->with('status', 'Paciente eliminado correctamente.');
    }
}
