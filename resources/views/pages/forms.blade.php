@extends('components.layout')

@section('title', 'Formularios')

@php
	use App\Enums\PermissionEnum;
@endphp

@section('content')
	<div class="relative flex flex-col rounded-lg bg-white shadow-sm border border-slate-200">
		<nav class="flex min-w-[240px] flex-col gap-1 p-1.5">
			@if (PermissionEnum::can(PermissionEnum::VIEW_OCCUPATIONAL))
				<a href="{{ route('form.occupational') }}" class="text-initial">
					<div role="button"
						class="text-slate-800 flex w-full items-center rounded-md p-3 transition-all hover:bg-slate-100 focus:bg-slate-100 active:bg-slate-100">
						Médicina Ocupacional
					</div>
				</a>
			@endif
			@if (PermissionEnum::can(PermissionEnum::VIEW_AUDIOLOGY))
				<a href="{{ route('form.audiology') }}" class="text-initial">
					<div role="button"
						class="text-slate-800 flex w-full items-center rounded-md p-3 transition-all hover:bg-slate-100 focus:bg-slate-100 active:bg-slate-100">
						Audiología
					</div>
				</a>
			@endif
			@if (PermissionEnum::can(PermissionEnum::VIEW_OPHTHALMOLOGY))
				<a href="{{ route('form.ophthalmology') }}" class="text-initial">
					<div role="button"
						class="text-slate-800 flex w-full items-center rounded-md p-3 transition-all hover:bg-slate-100 focus:bg-slate-100 active:bg-slate-100">
						Oftalmología
					</div>
				</a>
			@endif
		</nav>
	</div>
@endsection