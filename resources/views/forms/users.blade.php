@extends('components.layout')

@section('title', 'Editar Usuario')

@section('content')
    <section class="w-full py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold tracking-tight text-gray-900">Editar Usuario</h1>
            <p class="mt-1 text-sm text-gray-500">Actualiza el perfil y la contraseña de un usuario.</p>
        </div>

        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if ((!$user && $errors->any()) || ($user && $errors->profile->any()))
            <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ((!$user ? $errors->all() : $errors->profile->all()) as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-5 {{ $user ? 'lg:grid-cols-2' : '' }}">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                <h2 class="text-sm font-semibold uppercase tracking-wide text-gray-600">Datos de usuario</h2>

                <form method="post" action="{{ $user ? route('users.update.profile', $user) : route('users.store') }}"
                    class="mt-4 space-y-4">
                    @csrf
                    @method($user ? 'PATCH' : 'POST')

                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Nombre <span
                                class="text-red-600">*</span></label>
                        <input id="name" name="name" type="text" required value="{{ $user?->name ?? old('name', '') }}"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                    </div>

                    <div class="doctor-fields hidden">
                        <div>
                            <label for="last_name" class="mb-1 block text-sm font-medium text-gray-700">Apellido <span
                                    class="text-red-600">*</span></label>
                            <input id="last_name" name="last_name" type="text" required
                                value="{{ $user?->last_name ?? old('last_name', '') }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                        </div>
                    </div>

                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Correo <span
                                class="text-red-600">*</span></label>
                        <input id="email" name="email" type="email" required value="{{ $user?->email ?? old('email', '') }}"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                    </div>

                    <div>
                        <label for="role" class="mb-1 block text-sm font-medium text-gray-700">Rol de usuario <span
                                class="text-red-600">*</span></label>
                        <select id="role" name="role"
                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800">
                            <option value="" selected disabled>Selecciona una opción</option>
                            @foreach (App\Enums\RoleEnum::cases() as $role)
                                <option value="{{ $role->code() }}" {{ ($user?->role ?? old('role')) == $role->code() ? 'selected' : '' }}>
                                    {{ $role->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="doctor-fields hidden space-y-3">
                        <div>
                            <label for="specialty" class="mb-1 block text-sm font-medium text-gray-700">Especialidad médica
                                <span class="text-red-600">*</span></label>
                            <select id="specialty" name="specialty"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800">
                                <option value="" selected disabled>Selecciona una opción</option>
                                @foreach (App\Enums\SpecialtyEnum::cases() as $specialty)
                                    <option value="{{ $specialty->code() }}" {{ ($user?->doctor?->specialty ?? old('specialty')) == $specialty->code() ? 'selected' : '' }}>
                                        {{ $specialty->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="phone" class="mb-1 block text-sm font-medium text-gray-700">Teléfono <span
                                    class="text-red-600">*</span></label>
                            <input id="phone" name="phone" type="text" value="{{ $user?->phone ?? old('phone', '') }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                        </div>

                        <div>
                            <label for="id_card" class="mb-1 block text-sm font-medium text-gray-700">Cédula <span
                                    class="text-red-600">*</span></label>
                            <input id="id_card" name="id_card" type="text"
                                value="{{ $user?->id_card ?? old('id_card', '') }}"
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                        </div>
                    </div>

                    @if (!$user)
                        <div>
                            <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Contraseña <span
                                    class="text-red-600">*</span></label>
                            <input id="password" name="password" type="password" required
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                placeholder="Mínimo 6 caracteres" />
                        </div>

                        <div>
                            <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700">Confirmar
                                contraseña <span class="text-red-600">*</span></label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
                        </div>
                    @endif

                    <div class="pt-2">
                        <button type="submit"
                            class="inline-flex h-10 items-center justify-center rounded-lg bg-gray-900 px-5 text-sm font-medium text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>

            @if ($user)
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
                            <label for="password-update" class="mb-1 block text-sm font-medium text-gray-700">
                                Nueva contraseña <span class="text-red-600">*</span>
                            </label>
                            <input id="password-update" name="password" type="password" required
                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
                                placeholder="Mínimo 6 caracteres" />
                        </div>

                        <div>
                            <label for="password-confirmation-update" class="mb-1 block text-sm font-medium text-gray-700">
                                Confirmar contraseña <span class="text-red-600">*</span>
                            </label>
                            <input id="password-confirmation-update" name="password_confirmation" type="password" required
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
            @endif
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        var doctorRoleId = '{{ App\Enums\RoleEnum::DOCTOR->code() }}';
    </script>
    @vite('resources/js/users.js')
@endpush