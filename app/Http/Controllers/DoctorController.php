<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Metadata;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.doctors');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $existingDoctor = null;
        $id = $request->input('id');

        if ($id) {
            $existingDoctor = Doctor::find($id);
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'id_card' => [
                'required',
                'string',
                'max:255',
                Rule::unique('doctors', 'id_card')->ignore($existingDoctor?->id),
            ],
            'specialty' => ['nullable', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:255',
                Rule::unique('doctors', 'phone')->ignore($existingDoctor?->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('doctors', 'email')->ignore($existingDoctor?->id),
            ],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $doctor = $existingDoctor ?? new Doctor();
        $isUpdate = $doctor->exists;

        $doctor->fill($validated);
        $doctor->save();

        Metadata::updateOrCreate(
            ['meta_type' => 'doctor', 'meta_id' => $doctor->id, 'meta_key' => 'address'],
            ['meta_value' => $validated['address']]
        );

        return redirect()->route('doctors')->with(
            'status',
            $isUpdate ? 'Doctor actualizado correctamente.' : 'Doctor registrado correctamente.'
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $id = $request->input('id', null);
        $doctor = null;
        if ($id) {
            $doctor = Doctor::with('metadata')->find($id);
        }
        return view('forms.doctors', [
            'doctor' => $doctor,
            'specialties' => \App\Models\Specialty::cases(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        //
    }
}
