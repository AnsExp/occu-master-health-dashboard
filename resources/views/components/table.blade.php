@props([
	'headers' => [],
	'data' => [],
])

@php
	$renderHeader = function ($header) {
		if (is_array($header) && isset($header['view'])) {
			return view($header['view'], $header['data'] ?? []);
		}

		if (is_array($header) && isset($header['text'])) {
			return e($header['text']);
		}

		if (is_string($header) || is_numeric($header)) {
			return e((string) $header);
		}

		return '';
	};

	$renderCell = function ($cell) {
		if (is_array($cell) && array_key_exists('html', $cell)) {
			return $cell['html'];
		}

		if (is_array($cell) && array_key_exists('text', $cell)) {
			return e($cell['text']);
		}

		if (is_string($cell) || is_numeric($cell)) {
			return e((string) $cell);
		}

		return e($cell ?? '-');
	};
@endphp

<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
	<div class="overflow-x-auto">
		<table class="w-full divide-y divide-gray-200 text-sm">
			<thead class="bg-gray-50">
				<tr>
					@foreach ($headers as $header)
						<th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
							{!! $renderHeader($header) !!}
						</th>
					@endforeach
				</tr>
			</thead>
			<tbody class="divide-y divide-gray-100 bg-white">
				@forelse ($data as $row)
					<tr class="hover:bg-gray-50/70">
						@foreach ((array) $row as $cell)
							<td class="px-4 py-3 align-top text-gray-700">
								{!! $renderCell($cell) !!}
							</td>
						@endforeach
					</tr>
				@empty
					<tr>
						<td colspan="{{ max(count($headers), 1) }}" class="px-4 py-8 text-center text-sm text-gray-500">
							No hay registros disponibles.
						</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
</div>
