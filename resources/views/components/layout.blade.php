<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    @include('components.header')
    <main class="container mx-auto p-4">
        @yield('content')
    </main>
    @include('components.footer')
    @stack('scripts')
</body>

</html>