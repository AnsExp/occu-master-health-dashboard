<?php

namespace App\Http\Controllers;

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
        return view('pages.patients');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        $plan->delete();
        return redirect()->route('plans')->with('status', 'Plan eliminado correctamente.');
    }
}
