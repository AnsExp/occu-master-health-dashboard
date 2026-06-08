<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Plan;
use App\Models\PlanDetail;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!request()->user()->can('viewAny', Patient::class)) {
            abort(403);
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
        if (!request()->user()->can('create', Patient::class)) {
            abort(403);
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
    public function show(Plan $plan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Plan $plan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        if (!request()->user()->can('delete', Patient::class)) {
            abort(403);
        }
        $plan->delete();
        return redirect()->route('plans')->with('status', 'Plan eliminado correctamente.');
    }
}
