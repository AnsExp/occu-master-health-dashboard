@extends('components.layout')

@section('title', 'Editar Usuario')

@php
    use Spatie\Permission\Models\Role;
    use App\Models\Specialty;
@endphp

@section('content')
    <section class="mx-auto max-w-4xl py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Editar Usuario</h1>
            <p class="mt-1 text-sm text-gray-500">Actualiza el perfil y la contraseña de un usuario.</p>
        </div>

        <div class="grid gap-5 lg:grid-cols-2">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-600">Datos de usuario</h2>

                @if ($errors->profile->any())
                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->profile->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (!$user)
                    <form method="post" action="{{ route('users.store') }}" class="mt-4 space-y-4">
                        @csrf
                        @method('POST')
                        <div>
                            <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Nombre <span class="text-red-600">*</span></label>
                            <input id="name" name="name" type="text" required value="{{ $user?->name ?? old('name', '') }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                        </div>

                        <div>
                            <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Correo <span class="text-red-600">*</span></label>
                            <input id="email" name="email" type="email" required value="{{ $user?->email ?? old('email', '') }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                        </div>
                        <div>
                            <label for="role" class="mb-1 block text-sm font-medium text-gray-700">Rol de usuario <span class="text-red-600">*</span></label>
                            <select id="role" name="role"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800">
                                <option value="" selected disabled>Selecciona una opción</option>
                                @foreach (Role::all() as $role)
                                    <option value="{{ $role->id }}" {{ ($user?->role_id ?? old('role_id')) == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                                </option>
                            </select>
                        </div>
                        <div id="speciality-field" class="hidden">
                            <label for="speciality" class="mb-1 block text-sm font-medium text-gray-700">Especialidad médica <span class="text-red-600">*</span></label>
                            <select id="speciality" name="speciality"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800">
                                <option value="" selected disabled>Selecciona una opción</option>
                                @foreach (Specialty::cases() as $specialty)
                                    <option value="{{ $specialty->name }}">
                                        {{ $specialty->label() }}
                                    </option>
                                @endforeach
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Contraseña <span class="text-red-600">*</span></label>
                            <input id="password" name="password" type="password" required
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                placeholder="Mínimo 6 caracteres" />
                        </div>
                        <div>
                            <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700">Confirmar contraseña <span class="text-red-600">*</span></label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                        </div>
                        <div class="pt-2">
                            <button type="submit"
                                class="inline-flex h-10 items-center justify-center rounded-lg bg-gray-900 px-5 text-sm font-medium text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                                Guardar cambios
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            @if ($user)
                <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                        <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-600">Restablecer contraseña</h2>

                        @if ($errors->password->any())
                            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->password->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="post" action="{{ route('users.update.password', ['user' => $user->id]) }}"
                            class="mt-4 space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Nueva
                                    contraseña <span class="text-red-600">*</span></label>
                                <input id="password" name="password" type="password" required
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                    placeholder="Mínimo 6 caracteres" />
                            </div>

                            <div>
                                <label for="password_confirmation"
                                    class="mb-1 block text-sm font-medium text-gray-700">Confirmar
                                    contraseña <span class="text-red-600">*</span></label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                            </div>

                            <div class="pt-2">
                                <button type="submit"
                                    class="inline-flex h-10 items-center justify-center rounded-lg bg-gray-900 px-5 text-sm font-medium text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                                    Restablecer contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
    </section>
@endsection

@push('scripts')
    <script>
        var doctorRoleId = {{ Role::where('name', \App\Models\Role::DOCTOR)->first()->id }};
    </script>
    @vite('resources/js/users.js')
@endpush