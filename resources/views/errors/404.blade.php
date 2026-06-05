@extends('components.layout')

@section('title', 'Error 404 - Página no encontrada')

@section('content')
    <section class="mx-auto flex min-h-[70vh] max-w-3xl items-center py-10">
        <div class="w-full rounded-3xl border border-gray-200 bg-white p-8 shadow-sm sm:p-10">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Error 404</p>
                    <h1 class="mt-3 text-4xl font-semibold tracking-tight text-gray-900 sm:text-5xl">
                        Página no encontrada
                    </h1>
                    <p class="mt-4 text-base leading-7 text-gray-600">
                        La ruta que intentas abrir no existe, fue movida o no tienes acceso a ella.
                        Revisa la dirección e intenta nuevamente.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('home') }}"
                            class="inline-flex h-11 items-center justify-center rounded-lg bg-gray-900 px-5 text-sm font-medium text-white transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                            Volver al inicio
                        </a>
                        <button type="button" onclick="window.history.back()"
                            class="inline-flex h-11 items-center justify-center rounded-lg border border-gray-300 px-5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                            Regresar
                        </button>
                    </div>
                </div>

                <div class="flex shrink-0 items-center justify-center rounded-3xl bg-gray-50 p-8">
                    <div class="text-center">
                        <div class="text-8xl font-black tracking-tight text-gray-900 sm:text-9xl">404</div>
                        <p class="mt-2 text-sm font-medium uppercase tracking-[0.18em] text-gray-500">
                            Página no encontrada
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection