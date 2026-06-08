<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Enums\SpecialtyEnum;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        if (!request()->user()->can('viewAny', User::class)) {
            abort(403);
        }
        $sort = request('sort', 'name');
        $direction = request('direction', 'asc');
        $data = User::orderBy($sort, $direction)->paginate(10);
        return view('pages.users', compact('data', 'sort', 'direction'));
    }

    private function storeUser(array $validated)
    {
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = bcrypt($validated['password']);
        $user->assignRole($validated['role']);
        if (!$user->save()) {
            return null;
        }
        return $user;
    }

    private function storeDoctor(array $validated, User $user)
    {
        $doctor = new Doctor([
            'first_name' => $user->name,
            'last_name' => $validated['last_name'],
            'id_card' => $validated['id_card'],
            'phone' => $validated['phone']
        ]);
        $specialty = Specialty::where('name', $validated['specialty'])->first();
        switch ($validated['specialty']) {
            case SpecialtyEnum::AUDIOLOGY->code():
                $user->givePermissionTo([
                    PermissionEnum::VIEW_AUDIOLOGY->code(),
                    PermissionEnum::STORE_AUDIOLOGY->code()
                ]);
                break;
            case SpecialtyEnum::OCCUPATIONAL->code():
                $user->givePermissionTo([
                    PermissionEnum::VIEW_OCCUPATIONAL->code(),
                    PermissionEnum::STORE_OCCUPATIONAL->code()
                ]);
                break;
            case SpecialtyEnum::OPHTHALMOLOGY->code():
                $user->givePermissionTo([
                    PermissionEnum::VIEW_OPHTHALMOLOGY->code(),
                    PermissionEnum::STORE_OPHTHALMOLOGY->code()
                ]);
                break;
        }
        $doctor->specialty()->associate($specialty);
        $doctor->user()->associate($user);
        $doctor->save();
    }

    public function store(Request $request)
    {
        if (!request()->user()->can('create', User::class)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'role' => ['required', 'string', Rule::exists('roles', 'name')],
            'password' => ['required', 'string', 'min:6'],
            'password_confirmation' => ['required', 'string', 'min:6', 'same:password'],
        ]);

        $validated['name'] = $validated['name'] . ($request->input('last_name') ? ' ' . $request->input('last_name') : '');

        $user = $this->storeUser($validated);

        if (!$user) {
            return redirect()->route('users.edit')->with('error', 'Ha ocurrido un error al registrar el usuario. stack: ' . json_encode($user->getErrors()));
        }

        if ($validated['role'] === RoleEnum::DOCTOR->code()) {
            $doctorValidated = $request->validate([
                'last_name' => ['required', 'string', 'max:255'],
                'id_card' => ['required', 'string', 'max:255', Rule::unique('doctors', 'id_card')],
                'phone' => ['required', 'string', 'max:255', Rule::unique('doctors', 'phone')],
                'specialty' => ['required', 'string', Rule::exists('specialties', 'name')],
            ]);
            $this->storeDoctor($doctorValidated, $user);
        }

        return redirect()->route('users.edit', ['user' => $user])->with('status', 'Usuario registrado correctamente.');
    }

    public function create()
    {
        if (!auth()->user()->can('create', User::class)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        return view('forms.users', ['user' => null]);
    }

    public function edit(User $user)
    {
        if (!request()->user()->can('update', $user)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        if (!$user->exists) {
            $user = null;
        }
        return view('forms.users', ['user' => $user]);
    }

    public function updateProfile(Request $request, User $user)
    {
        $validated = $request->validateWithBag('profile', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'profile_option' => ['nullable', 'string', 'max:255'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return redirect()->route('users')->with('status', 'Perfil actualizado correctamente.');
    }

    public function updatePassword(Request $request, User $user)
    {
        $validated = $request->validateWithBag('password', [
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user->password = bcrypt($validated['password']);
        $user->save();

        return redirect()->route('users')->with('status', 'Contraseña restablecida correctamente. Nuevamente contraseña: ' . $validated['password']);
    }

    public function destroy(User $user)
    {
        if (!request()->user()->can('delete', $user)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $user->delete();
        return redirect()->route('users')->with('status', 'Usuario eliminado correctamente.');
    }
}
