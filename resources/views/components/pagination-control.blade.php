@props([
    'current'  => 1,
    'total'    => 1,
    'pageKey'  => 'page',
])

@php
    $current  = (int) $current;
    $total    = (int) $total;
    $prevUrl  = $current > 1      ? request()->fullUrlWithQuery([$pageKey => $current - 1]) : null;
    $nextUrl  = $current < $total ? request()->fullUrlWithQuery([$pageKey => $current + 1]) : null;

    $delta   = 2;
    $pages   = [];
    $start   = max(1, $current - $delta);
    $end     = min($total, $current + $delta);

    if ($start > 1) {
        $pages[] = ['num' => 1, 'url' => request()->fullUrlWithQuery([$pageKey => 1])];
        if ($start > 2) {
            $pages[] = ['num' => '...', 'url' => null];
        }
    }

    for ($i = $start; $i <= $end; $i++) {
        $pages[] = ['num' => $i, 'url' => request()->fullUrlWithQuery([$pageKey => $i])];
    }

    if ($end < $total) {
        if ($end < $total - 1) {
            $pages[] = ['num' => '...', 'url' => null];
        }
        $pages[] = ['num' => $total, 'url' => request()->fullUrlWithQuery([$pageKey => $total])];
    }
@endphp

@if ($total > 1)
    <nav class="flex items-center justify-between gap-4 border-t border-gray-100 pt-4">
        <p class="text-xs text-gray-500">
            Página <span class="font-medium text-gray-800">{{ $current }}</span> de
            <span class="font-medium text-gray-800">{{ $total }}</span>
        </p>

        <div class="flex items-center gap-1">
            @if ($prevUrl)
                <a href="{{ $prevUrl }}"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-gray-200 text-gray-600 transition hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @else
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-gray-100 text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @endif

            @foreach ($pages as $page)
                @if ($page['url'] === null)
                    <span class="inline-flex h-8 w-8 items-center justify-center text-xs text-gray-400">…</span>
                @elseif ($page['num'] === $current)
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-gray-900 text-xs font-medium text-white">
                        {{ $page['num'] }}
                    </span>
                @else
                    <a href="{{ $page['url'] }}"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-gray-200 text-xs text-gray-600 transition hover:bg-gray-50">
                        {{ $page['num'] }}
                    </a>
                @endif
            @endforeach

            @if ($nextUrl)
                <a href="{{ $nextUrl }}"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-gray-200 text-gray-600 transition hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-md border border-gray-100 text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
