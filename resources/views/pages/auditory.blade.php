@extends('components.layout')

@section('title', 'Logs del sistema')

@php
	use App\Enums\LevelEnum;
@endphp

@section('content')
	<section class="mx-auto max-w-6xl space-y-6 py-6">
		<div class="flex flex-col gap-2">
			<h1 class="text-2xl font-semibold tracking-tight text-gray-900">Logs del sistema</h1>
			<p class="text-sm text-gray-600">Consulta la actividad del sistema y filtra por rango de fechas, usuario y nivel
				del log.</p>
		</div>

		<div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
			<form method="get" class="space-y-4">
				<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
					<div>
						<label for="start_date" class="mb-2 block text-sm font-medium text-gray-700">Desde</label>
						<input id="start_date" name="start_date" type="date" value="{{ $filters['start_date'] }}"
							class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20" />
					</div>

					<div>
						<label for="end_date" class="mb-2 block text-sm font-medium text-gray-700">Hasta</label>
						<input id="end_date" name="end_date" type="date" value="{{ $filters['end_date'] }}"
							class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20" />
					</div>

					<div>
						<label for="user_id" class="mb-2 block text-sm font-medium text-gray-700">Usuario</label>
						<select id="user_id" name="user_id"
							class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
							<option value="">Todos</option>
							@foreach ($users as $user)
								<option value="{{ $user->id }}" @selected($filters['user_id'] === (string) $user->id)>
									{{ $user->name }}
								</option>
							@endforeach
						</select>
					</div>

					<div>
						<label for="level" class="mb-2 block text-sm font-medium text-gray-700">Nivel</label>
						<select id="level" name="level"
							class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm transition focus:border-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-900/20">
							<option value="">Todos</option>
							@foreach ($levels as $level)
								<option value="{{ $level }}" @selected($filters['level'] === $level)>
									{{ LevelEnum::fromCode($level)?->label() ?? ucfirst($level) }}
								</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="flex flex-wrap items-center gap-2 pt-2">
					<button type="submit"
						class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
						Filtrar logs
					</button>
					<a href="{{ url()->current() }}"
						class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
						Limpiar filtros
					</a>
				</div>
			</form>
		</div>

		@php
			$hasFilters = $filters['start_date'] || $filters['end_date'] || $filters['user_id'] || $filters['level'];
		@endphp

		<div class="rounded-xl border border-gray-200 bg-white shadow-sm">
			<div class="border-b border-gray-200 px-4 py-4">
				<div class="flex flex-wrap items-center justify-between gap-3">
					<div>
						<h2 class="text-base font-semibold text-gray-900">Resultados</h2>
						<p class="mt-1 text-sm text-gray-600">
							@if ($hasFilters)
								Se encontraron {{ $data->total() }} log(s) con los filtros aplicados.
							@else
								Mostrando los últimos logs del sistema.
							@endif
						</p>
					</div>
					<span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700">
						{{ $data->total() }} registros
					</span>
				</div>
			</div>

			@if ($data->count() > 0)
				<div class="overflow-x-auto">
					<table class="w-full divide-y divide-gray-200 text-sm">
						<thead class="bg-gray-50">
							<tr>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
									Fecha</th>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
									Usuario</th>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
									Nivel</th>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
									Acción</th>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">Tipo
								</th>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">IP
								</th>
								<th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
								</th>
							</tr>
						</thead>
						<tbody class="divide-y divide-gray-100">
							@foreach ($data as $log)
								<tr class="hover:bg-gray-50">
									<td class="whitespace-nowrap px-4 py-3 align-top text-gray-700">
										{{ format_datetime($log->created_at, false, true) }}</td>
									<td class="px-4 py-3 align-top text-gray-700">{{ $log->user?->name ?? 'Sistema' }}</td>
									<td class="px-4 py-3 align-top">
										@php
											$badgeClass = match ($log->level) {
												App\Enums\LevelEnum::INFO->code() => 'bg-blue-100 text-blue-800',
												App\Enums\LevelEnum::WARNING->code() => 'bg-amber-100 text-amber-800',
												App\Enums\LevelEnum::ERROR->code() => 'bg-orange-100 text-orange-800',
												App\Enums\LevelEnum::CRITICAL->code() => 'bg-red-100 text-red-800',
												default => 'bg-gray-100 text-gray-800',
											};
										@endphp
										<span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">
											{{ App\Enums\LevelEnum::fromCode($log->level)?->label() ?? 'No definido' }}
										</span>
									</td>
									<td class="px-4 py-3 align-top text-gray-700">
										{{ App\Enums\ActionEnum::fromCode($log->action)?->label() ?? 'No definido' }}</td>
									<td class="px-4 py-3 align-top text-gray-700">
										{{ App\Enums\TableEnum::fromCode($log->table_name)?->label() ?? 'No definido' }}</td>
									<td class="px-4 py-3 align-top text-gray-700">{{ $log->ip_address ?? '-' }}</td>
									<td class="px-4 py-3 align-top text-gray-700">
										<a href="{{ route('audit.detail', ['log' => $log->id]) }}"
											class="inline-flex items-center gap-1 rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 transition hover:bg-gray-200">
											Ver detalles
										</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>

				<div class="border-t border-gray-200 px-4 py-4">
					{{ $data->links() }}
				</div>
			@else
				<div class="px-6 py-12 text-center">
					<h3 class="text-sm font-medium text-gray-900">No se encontraron logs</h3>
					<p class="mt-2 text-sm text-gray-600">Ajusta los filtros para ampliar la búsqueda.</p>
				</div>
			@endif
		</div>
	</section>
@endsection