<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return view('pages.users');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'string', Rule::exists('roles', 'id')],
            'password_confirmation' => ['required', 'string', 'min:6', 'same:password'],
        ]);
        // return response()->json($request->all());
        return response()->json($validated);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = bcrypt($validated['password']);
        $user->role = $validated['role'];
        $user->save();

        return redirect()->route('users')->with('status', 'Usuario registrado correctamente.');
    }

    public function edit(Request $request)
    {
        $id = $request->input('id', null);
        $user = null;
        if ($id) {
            $user = User::find($id);
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

        return redirect()->route('users')->with('status', 'Contraseña restablecida correctamente.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users')->with('status', 'Usuario eliminado correctamente.');
    }
}
