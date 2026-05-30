@extends('components.layout')

@section('title', 'Registrar Doctor')

@section('content')
	<section class="mx-auto max-w-4xl py-6">
		<div class="mb-6">
			<h1 class="text-2xl font-semibold tracking-tight text-gray-900">
				{{ $doctor ? $doctor->first_name . ' ' . $doctor->last_name : 'Registrar Doctor' }}
			</h1>
			<p class="mt-1 text-sm text-gray-500">
				{{ $doctor ? 'Completa los datos para actualizar la información del doctor.' : 'Completa los datos para registrar un nuevo doctor en el sistema.' }}
			</p>
		</div>

		<div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6">
			@if ($errors->any())
				<div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
					<p class="font-semibold">No se pudo registrar el doctor.</p>
					<ul class="mt-1 list-disc pl-5">
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<form method="post" action="{{ route('doctors.store') }}" class="space-y-5">
				@csrf
				<input type="hidden" name="id" value="{{ $doctor->id ?? '' }}" />
				<div class="grid gap-4 sm:grid-cols-2">
					<div>
						<label for="first_name" class="mb-1 block text-sm font-medium text-gray-700">
							Nombres
							<span class="text-red-600">*</span>
						</label>
						<input id="first_name" name="first_name" type="text"
							value="{{ $doctor->first_name ?? old('first_name') }}" required
							class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
							placeholder="Ej: Ana María" />
					</div>

					<div>
						<label for="last_name" class="mb-1 block text-sm font-medium text-gray-700">
							Apellidos
							<span class="text-red-600">*</span>
						</label>
						<input id="last_name" name="last_name" type="text"
							value="{{ $doctor->last_name ?? old('last_name') }}" required
							class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
							placeholder="Ej: Pérez Gómez" />
					</div>

					<div>
						<label for="id_card" class="mb-1 block text-sm font-medium text-gray-700">
							Cédula
							<span class="text-red-600">*</span>
						</label>
						<input id="id_card" name="id_card" type="text" value="{{ $doctor->id_card ?? old('id_card') }}"
							required
							class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
							placeholder="Ej: 00123456789" />
					</div>

					<div>
						<label for="specialty" class="mb-1 block text-sm font-medium text-gray-700">Especialidad</label>
						<select id="specialty" name="specialty"
							class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800">
							<option value="">Seleccione una especialidad</option>
							@foreach ($specialties as $specialty)
								<option value="{{ $specialty->name }}" {{ ($doctor->specialty ?? old('specialty')) === $specialty->name ? 'selected' : '' }}>
									{{ $specialty->label() }}
								</option>
							@endforeach
						</select>
					</div>

					<div>
						<label for="phone" class="mb-1 block text-sm font-medium text-gray-700">
							Teléfono
							<span class="text-red-600">*</span>
						</label>
						<input id="phone" name="phone" type="text" value="{{ $doctor->phone ?? old('phone') }}" required
							class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
							placeholder="Ej: 8091234567" />
					</div>

					<div>
						<label for="email" class="mb-1 block text-sm font-medium text-gray-700">
							Correo
							<span class="text-red-600">*</span>
						</label>
						<input id="email" name="email" type="email" value="{{ $doctor->email ?? old('email') }}" required
							class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
							placeholder="doctor@correo.com" />
					</div>

					<div class="sm:col-span-2">
						<label for="address" class="mb-1 block text-sm font-medium text-gray-700">
							Dirección
							<span class="text-red-600">*</span>
						</label>
						<textarea id="address" name="address" rows="3" required
							class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800"
							placeholder="Calle, número, sector, ciudad">{{ $doctor?->metadata->firstWhere('meta_key', 'address')?->meta_value ?? old('address') }}</textarea>
					</div>
				</div>

				<div class="flex flex-col gap-2 border-t border-gray-100 pt-4 sm:flex-row sm:justify-end">
					<a href="{{ route('doctors') }}"
						class="inline-flex h-10 items-center justify-center rounded-lg border border-gray-300 px-5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
						Cancelar
					</a>
					<button type="submit"
						class="inline-flex h-10 items-center justify-center rounded-lg bg-gray-900 px-5 text-sm font-medium text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
						Guardar doctor
					</button>
				</div>
			</form>
		</div>
	</section>
@endsection