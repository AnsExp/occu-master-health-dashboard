@props([
	'tabs' => [],
	'activeTab' => null,
])

@php
	$activeTab ??= array_key_first($tabs) ?? null;
@endphp

<div class="space-y-4">
	<div class="flex flex-col gap-3 border-b border-gray-200 sm:flex-row sm:items-center sm:justify-between" data-tab-group data-active-tab="{{ $activeTab }}">
		<div class="flex flex-wrap gap-2">
			@foreach ($tabs as $tabKey => $tabLabel)
				<button
					type="button"
					class="px-4 py-3 text-sm font-medium transition {{ $activeTab === $tabKey ? 'border-b-2 border-b-gray-900 text-gray-900' : 'text-gray-600 hover:text-gray-900' }}"
					data-tab-button="{{ $tabKey }}">
					{{ $tabLabel }}
				</button>
			@endforeach
		</div>
	</div>

	<div class="tab-content">
		{{ $slot }}
	</div>
</div>
