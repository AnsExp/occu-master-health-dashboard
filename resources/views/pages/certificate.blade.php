@extends('components.layout')

@section('title', 'Certificados')

@section('content')
	<section class="mx-auto max-w-6xl space-y-6 py-6">
		<!-- Header -->
		<div class="flex flex-col gap-2">
			<div class="flex items-center justify-between">
				<div>
					<h1 class="text-2xl font-semibold tracking-tight text-gray-900">Certificados médicos</h1>
					<p class="mt-2 text-sm text-gray-600">Busca y filtra certificados por número, cédula del paciente o
						rango de fechas.</p>
				</div>
			</div>
		</div>

		<!-- Filter Card -->
		<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
			<form method="get" class="space-y-4">
				<!-- Row 1: Cédula y Número de Certificado -->
				<div class="grid gap-4 sm:grid-cols-2">
					<div>
						<label for="search_by_id_card" class="mb-2 block text-sm font-medium text-gray-700">Cédula del
							paciente</label>
						<input id="search_by_id_card" name="patient_id_card" type="text" placeholder="Ej: 00123456789"
							class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20"
							value="{{ request('patient_id_card', '') }}" />
					</div>
					<div>
						<label for="search_by_certificate_number"
							class="mb-2 block text-sm font-medium text-gray-700">Número del certificado</label>
						<input id="search_by_certificate_number" name="certificate_number" type="text"
							placeholder="Ej: CM-2026-00124"
							class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20"
							value="{{ request('certificate_number', '') }}" />
					</div>
				</div>

				<!-- Row 2: Rango de fechas -->
				<div class="grid gap-4 sm:grid-cols-2">
					<div>
						<label for="start_date" class="mb-2 block text-sm font-medium text-gray-700">Desde</label>
						<input id="start_date" name="start_date" type="date"
							class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20"
							value="{{ request('start_date', '') }}" />
					</div>
					<div>
						<label for="end_date" class="mb-2 block text-sm font-medium text-gray-700">Hasta</label>
						<input id="end_date" name="end_date" type="date"
							class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20"
							value="{{ request('end_date', '') }}" />
					</div>
				</div>

				<!-- Action Buttons -->
				<div class="flex gap-2 pt-2">
					<button type="submit"
						class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
						<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
								d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
						</svg>
						Buscar
					</button>
					<a href="{{ url()->current() }}"
						class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
						Limpiar
					</a>
				</div>
			</form>
		</div>

		<!-- Results Section -->
		<div class="rounded-xl border border-gray-200 bg-white shadow-sm mt-6">
			<!-- Filter Summary -->
			@php
				$hasFilters = $filters['patient_id_card'] || $filters['certificate_number'] || $filters['start_date'] || $filters['end_date'];
			@endphp

			@if($hasFilters)
				<div class="border-b border-gray-200 px-6 py-4 bg-blue-50">
					<h3 class="text-sm font-semibold text-gray-900 mb-3">Filtros aplicados:</h3>
					<div class="flex flex-wrap gap-2">
						@if($filters['patient_id_card'])
							<span
								class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-900">
								Cédula: {{ $filters['patient_id_card'] }}
							</span>
						@endif
						@if($filters['certificate_number'])
							<span
								class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-900">
								Certificado: {{ $filters['certificate_number'] }}
							</span>
						@endif
						@if($filters['start_date'] && $filters['end_date'])
							<span
								class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-900">
								Del {{ $filters['start_date'] }} al {{ $filters['end_date'] }}
							</span>
						@elseif($filters['start_date'])
							<span
								class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-900">
								Desde {{ $filters['start_date'] }}
							</span>
						@elseif($filters['end_date'])
							<span
								class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-900">
								Hasta {{ $filters['end_date'] }}
							</span>
						@endif
					</div>
				</div>
			@endif

			<!-- Results Table Header -->
			<div class="border-b border-gray-200 px-4 py-4">
				<div class="flex items-center justify-between">
					<div>
						<h2 class="text-base font-semibold text-gray-900">Resultados</h2>
						<p class="mt-1 text-sm text-gray-600">
							@if($hasFilters)
								Se encontraron {{ $certificates->total() }} certificado(s)
							@else
								Ingresa los filtros y presiona "Buscar"
							@endif
						</p>
					</div>
					@if($certificates->total() > 0)
						<span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
							{{ $certificates->total() }} registros
						</span>
					@endif
				</div>
			</div>

			<!-- Results Table -->
			@if($certificates->count() > 0)
				<div class="overflow-x-auto">
					<table class="w-full divide-y divide-gray-200 text-sm">
						<thead class="bg-gray-50">
							<tr>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
									Título</th>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
									Paciente</th>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
									Cédula</th>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
									Fecha</th>
								<th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600">
									Acciones</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-gray-100">
							@foreach($certificates as $certificate)
								<tr class="hover:bg-gray-50">
									<td class="px-4 py-3 align-top text-gray-700">{{ $certificate->title }}</td>
									<td class="px-4 py-3 align-top text-gray-700">{{ $certificate->order->patient->first_name }} {{ $certificate->order->patient->last_name }}</td>
									<td class="px-4 py-3 align-top text-gray-700">{{ $certificate->order->patient->id_card }}</td>
									<td class="px-4 py-3 align-top text-gray-700">{{ format_datetime($certificate->created_at, false, true) }}</td>
									<td class="px-4 py-3 align-top text-right text-gray-700">
										<div class="flex justify-end gap-2">
											<a href="{{ route('certificates.pdf', ['certificate' => $certificate->certificate_number]) }}"
												target="_blank" rel="noopener"
												class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:bg-gray-50">Ver</a>
										</div>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>

					<!-- Paginación -->
					<div class="border-t border-gray-200 px-4 py-4">
						{{ $certificates->links() }}
					</div>
				</div>
			@else
				<div class="px-6 py-12 text-center">
					<h3 class="mt-4 text-sm font-medium text-gray-900">No hay resultados</h3>
					<p class="mt-2 text-sm text-gray-600">
						@if($hasFilters)
							No se encontraron certificados con los filtros especificados. Intenta con otros parámetros.
						@else
							Utiliza los filtros anteriores para buscar certificados.
						@endif
					</p>
				</div>
			@endif
		</div>
	</section>
@endsection