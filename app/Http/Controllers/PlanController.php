<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Plan;
use App\Models\PlanDetail;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!request()->user()->can('viewAny', Plan::class)) {
            abort(403);
        }
        $sort = request('sort', 'name');
        $direction = request('direction', 'asc');
        $data = Plan::orderBy($sort, $direction)->paginate(10);
        return view('pages.plans', compact('data', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!request()->user()->can('create', Plan::class)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        return view('forms.plans', ['plan' => null]);
    }

    public function json()
    {
        return response()->json(Plan::with('details')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!request()->user()->can('create', Plan::class)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
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
        if (!request()->user()->can('update', $plan)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        return view('forms.plans', ['plan' => $plan]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        if (!request()->user()->can('update', $plan)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
            'periodicity' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);
        $items = explode("\n", $request->input('items', ''));
        $plan->update($data);
        $plan->details()->delete();
        foreach ($items as $item) {
            $detail = new PlanDetail();
            $detail->plan_id = $plan->id;
            $detail->detail = trim($item);
            $detail->save();
        }
        return redirect()->route('plans')->with('status', 'Plan actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        if (!request()->user()->can('delete', $plan)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $plan->delete();
        return redirect()->route('plans')->with('status', 'Plan eliminado correctamente.');
    }
}
