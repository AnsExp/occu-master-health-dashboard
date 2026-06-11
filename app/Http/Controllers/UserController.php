<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Services\UserStoreService;
use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private UserPolicy $policy;

    public function __construct()
    {
        $this->policy = new UserPolicy();
    }

    public function index()
    {
        if (!$this->policy->viewAny(request()->user())) {
            abort(403);
        }
        $sort = request('sort', 'name');
        $direction = request('direction', 'asc');
        $data = User::orderBy($sort, $direction)->paginate(10);
        return view('pages.users', compact('data', 'sort', 'direction'));
    }

    public function store(UserRequest $request, UserStoreService $service)
    {
        if (!$this->policy->create(request()->user())) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $validated = $request->validated();
        $user = $service->store($validated);
        if (!$user) {
            return redirect()->route('users.edit')->with('error', 'Ha ocurrido un error al registrar el usuario.');
        }
        return redirect()->route('users.edit', ['user' => $user])->with('status', 'Usuario registrado correctamente.');
    }

    public function create()
    {
        if (!$this->policy->create(request()->user())) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        return view('forms.users', ['user' => null]);
    }

    public function edit(User $user)
    {
        if (!$this->policy->update(request()->user(), $user)) {
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
        if (!$this->policy->delete(request()->user(), $user)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }
        $user->delete();
        return redirect()->route('users')->with('status', 'Usuario eliminado correctamente.');
    }
}
