@extends('components.layout')

@section('title', 'Home')

@section('content')
	@auth
		<div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
			Bienvenido, {{ auth()->user()->name }}. Explora las funcionalidades disponibles en el panel de administración.
		</div>
	@endauth
	<section
		class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-50 via-white to-cyan-50 p-6 sm:p-10">
		<div class="absolute -right-16 -top-16 h-56 w-56 rounded-full bg-cyan-200/40 blur-3xl"></div>
		<div class="absolute -bottom-20 -left-20 h-64 w-64 rounded-full bg-slate-300/30 blur-3xl"></div>

		<div class="relative grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
			<div>
				<span
					class="inline-flex items-center rounded-full border border-slate-300 bg-white px-3 py-1 text-xs font-medium text-slate-600">
					Plataforma administrativa
				</span>

				<h1 class="mt-4 max-w-xl text-3xl font-semibold leading-tight tracking-tight text-slate-900 sm:text-5xl">
					Controla tu portada como si fuera un producto vivo
				</h1>

				<p class="mt-4 max-w-xl text-sm leading-6 text-slate-600 sm:text-base">
					Gestiona contenido, planes y estructura de la pagina principal desde un dashboard claro,
					con cambios rapidos y una experiencia visual consistente para tu equipo.
				</p>

				<div class="mt-7 flex flex-wrap gap-3">
					<a href="{{ route('plans') }}"
						class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-slate-700">
						Gestionar planes
					</a>
					<a href="{{ route('login') }}"
						class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
						Acceder al panel
					</a>
				</div>
			</div>

			<div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
				<div class="mb-4 flex items-center justify-between">
					<p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Estado general</p>
					<span
						class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700">
						<span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
						En linea
					</span>
				</div>

				<div class="grid grid-cols-2 gap-3">
					<div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
						<p class="text-xs text-slate-500">Modulos</p>
						<p class="mt-1 text-2xl font-semibold text-slate-900">12</p>
					</div>
					<div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
						<p class="text-xs text-slate-500">Actualizaciones</p>
						<p class="mt-1 text-2xl font-semibold text-slate-900">34</p>
					</div>
					<div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
						<p class="text-xs text-slate-500">Planes activos</p>
						<p class="mt-1 text-2xl font-semibold text-slate-900">8</p>
					</div>
					<div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
						<p class="text-xs text-slate-500">Version</p>
						<p class="mt-1 text-sm font-semibold text-slate-900">v{{ app()->version() }}</p>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="mt-6 grid gap-4 md:grid-cols-3">
		<article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
			<p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Editor visual</p>
			<h2 class="mt-2 text-lg font-semibold text-slate-900">Bloques configurables</h2>
			<p class="mt-2 text-sm leading-6 text-slate-600">
				Administra secciones principales sin tocar codigo: hero, destacados, llamadas a la accion y mas.
			</p>
		</article>

		<article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
			<p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Planes</p>
			<h2 class="mt-2 text-lg font-semibold text-slate-900">Control comercial</h2>
			<p class="mt-2 text-sm leading-6 text-slate-600">
				Crea planes con nombre, precio, periodicidad e items incluidos para mantener una oferta clara.
			</p>
		</article>

		<article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
			<p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Operaciones</p>
			<h2 class="mt-2 text-lg font-semibold text-slate-900">Monitoreo rapido</h2>
			<p class="mt-2 text-sm leading-6 text-slate-600">
				Visualiza el estado del sistema y la actividad reciente para detectar cambios relevantes al instante.
			</p>
		</article>
	</section>
@endsection