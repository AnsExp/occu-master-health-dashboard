<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.ico') }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="grid min-h-[620px] grid-cols-1 lg:grid-cols-[260px_minmax(0,1fr)]">
        @include('components.header')
        <div class="p-6 sm:p-8 lg:p-10">
            @yield('content')
        </div>
    </div>
    @include('components.footer')
    @stack('scripts')
</body>

</html>