@extends('components.layout')

@section('title', 'Órdenes de pago')

@php
	use App\Enums\PermissionEnum;
@endphp

@section('content')
	<section class="mx-auto max-w-6xl space-y-6 py-6">
		<div class="flex flex-col gap-2">
			<div class="flex items-center justify-between">
				<div>
					<h1 class="text-2xl font-semibold tracking-tight text-gray-900">Órdenes de pago</h1>
					<p class="mt-2 text-sm text-gray-600">Busca y filtra órdenes por número, cédula del paciente o
						rango de fechas.</p>
				</div>
				<div>
					<span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
						{{ $orders->total() }} registros
					</span>
					@can (PermissionEnum::STORE_ORDERS->code())
						<a href="{{ route('orders.create') }}"
							class="ml-4 rounded-md bg-gray-900 px-4 py-2 text-sm font-semibold text-white">Crear</a>
					@endcan
				</div>
			</div>
		</div>

		<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
			<form method="get" class="space-y-4">
				<div class="grid gap-4 sm:grid-cols-2">
					<div>
						<label for="search_by_id_card" class="mb-2 block text-sm font-medium text-gray-700">Cédula del
							paciente</label>
						<input id="search_by_id_card" name="id_card" type="text" placeholder="Ej: 00123456789"
							class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20"
							value="{{ request('id_card', '') }}" />
					</div>
					<div>
						<label for="search_by_order_number" class="mb-2 block text-sm font-medium text-gray-700">Número del
							orden</label>
						<input id="search_by_order_number" name="order_number" type="text" placeholder="Ej: 9999999"
							class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm transition placeholder:text-gray-400 focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20"
							value="{{ request('order_number', '') }}" />
					</div>
				</div>

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
		<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
			<div class="overflow-x-auto">
				<table class="w-full divide-y divide-gray-200 text-sm">
					<thead class="bg-gray-50">
						<tr>
							<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
								Nro.
							</th>
							<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
								Paciente
							</th>
							<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
								Cédula
							</th>
							<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
								Fecha
							</th>
						</tr>
					</thead>
					<tbody class="divide-y divide-gray-100">
						@foreach($orders as $order)
							<tr class="hover:bg-gray-50">
								<td class="px-4 py-3 align-top text-gray-700">
									{{ $order->order_number }}
								</td>
								<td class="px-4 py-3 align-top text-gray-700">
									{{ $order->patient->first_name . ' ' . $order->patient->last_name }}
								</td>
								<td class="px-4 py-3 align-top text-gray-700">
									{{ $order->patient->id_card }}
								</td>
								<td class="px-4 py-3 align-top text-gray-700">
									{{ format_datetime($order->created_at, false, true) }}
								</td>
								<td class="px-4 py-3 align-top text-right text-gray-700">
									<div class="flex justify-end gap-2">
										<a href="{{ route('orders.show', ['order' => $order->order_number]) }}" target="_blank"
											rel="noopener"
											class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:bg-gray-50">Ver</a>
										<a href="{{ route('orders.pdf', ['order' => $order->order_number]) }}" target="_blank"
											rel="noopener"
											class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:bg-gray-50">Todos</a>
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>

			<!-- Paginación -->
			<div class="border-t border-gray-200 px-4 py-4">
				{{ $orders->links() }}
			</div>
		</div>
	</section>
@endsection