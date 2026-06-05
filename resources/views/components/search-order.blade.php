@props([
	'url' => null,
	'name' => 'order_number'
])

<form method="GET" action="{{ $url ?? url()->current() }}">
	<div class="flex flex-col gap-2 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
		<p class="text-sm font-medium text-gray-800">Buscar orden de pago</p>
		<p class="text-xs text-gray-500">Busca por número de orden</p>
	</div>

	<div class="mt-4 flex gap-2">
		<input required type="text" name="{{ $name }}" value="{{ request($name) ?? '' }}" autocomplete="on"
			placeholder="Número de orden…"
			class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
		<button type="submit"
			class="inline-flex h-10 shrink-0 items-center justify-center rounded-lg bg-gray-900 px-5 text-sm font-medium text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
			Buscar
		</button>
	</div>
</form>