@extends('components.layout')

@section('title', 'Home')

@section('content')
	@php
		use App\Enums\PermissionEnum;

		$isLogged = auth()->check();
		$loggedUser = auth()->user();
		$roleName = $isLogged ? ($loggedUser->getRoleNames()->first() ?? 'Sin rol asignado') : 'Invitado';
	@endphp

	<section
		class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-50 via-white to-cyan-50 p-6 sm:p-10">
		<div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-cyan-200/40 blur-3xl"></div>
		<div class="absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-slate-300/30 blur-3xl"></div>

		<div class="relative grid gap-8 xl:grid-cols-[1.25fr_0.75fr] xl:items-start">
			<div>
				<span
					class="inline-flex items-center rounded-full border border-slate-300 bg-white px-3 py-1 text-xs font-medium text-slate-600">
					Dashboard administrativo
				</span>

				<h1 class="mt-4 max-w-2xl text-3xl font-semibold leading-tight tracking-tight text-slate-900 sm:text-5xl">
					Centro de control de OccuMaster Health
				</h1>

				<p class="mt-4 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">
					Supervisa operaciones, valida el estado de tu sesión y accede a los módulos clave del sistema desde
					una vista central.
				</p>

				<div class="mt-7 flex flex-wrap gap-3">
					@if ($isLogged)
						<a href="{{ route('logout') }}"
							class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-slate-700">
							Cerrar sesión
						</a>
					@else
						<a href="{{ route('login') }}"
							class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-slate-700">
							Iniciar sesión
						</a>
					@endif

					@if (PermissionEnum::can(PermissionEnum::VIEW_ORDERS))
						<a href="{{ route('orders') }}"
							class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
							Ver órdenes
						</a>
					@endif
				</div>

				<div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
					<article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
						<p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Sesión</p>
						<p class="mt-2 text-base font-semibold text-slate-900">{{ $isLogged ? 'Activa' : 'No iniciada' }}
						</p>
						<p class="mt-1 text-sm text-slate-600">
							{{ $isLogged ? 'Usuario autenticado correctamente.' : 'Debes iniciar sesión para operar el sistema.' }}
						</p>
					</article>

					<article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
						<p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rol actual</p>
						<p class="mt-2 text-base font-semibold text-slate-900">{{ $roleName }}</p>
						<p class="mt-1 text-sm text-slate-600">Permisos aplicados según el perfil autenticado.</p>
					</article>

					<article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:col-span-2 xl:col-span-1">
						<p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Entorno</p>
						<p class="mt-2 text-base font-semibold text-slate-900">{{ strtoupper(app()->environment()) }}</p>
						<p class="mt-1 text-sm text-slate-600">Versión framework: v{{ app()->version() }}</p>
					</article>
				</div>
			</div>

			<div class="space-y-4">
				@if ($isLogged)
					<article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
						<div class="flex items-center justify-between">
							<p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Estado del sistema</p>
							<span
								class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700">
								<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
								Operativo
							</span>
						</div>
						<p class="mt-3 text-sm leading-6 text-slate-600">
							Los módulos principales están disponibles para este usuario y listos para operar.
						</p>
					</article>
					<article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
						<p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Indicadores rápidos</p>
						<div class="mt-4 grid grid-cols-2 gap-3">
							<div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
								<p class="text-xs text-slate-500">Pacientes</p>
								<p class="mt-1 text-2xl font-semibold text-slate-900">{{ \App\Models\Patient::count() }}</p>
							</div>
							<div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
								<p class="text-xs text-slate-500">Médicos</p>
								<p class="mt-1 text-2xl font-semibold text-slate-900">{{ \App\Models\Doctor::count() }}</p>
							</div>
							<div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
								<p class="text-xs text-slate-500">Órdenes</p>
								<p class="mt-1 text-2xl font-semibold text-slate-900">{{ \App\Models\Order::count() }}</p>
							</div>
							<div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
								<p class="text-xs text-slate-500">Certificados</p>
								<p class="mt-1 text-2xl font-semibold text-slate-900">{{ \App\Models\Certificate::count() }}</p>
							</div>
						</div>
					</article>
				@endif
			</div>
		</div>
	</section>
@endsection