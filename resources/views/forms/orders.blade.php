@extends('components.layout')

@section('title', 'Crear Orden de Pago')

@section('content')
	@php
		$orderItems = App\Models\Order::$STACK;
		$taxRate = config('app.tax_rate');
	@endphp

	<section class="mx-auto max-w-6xl py-6">
		<div class="mb-6">
			<h1 class="text-2xl font-semibold tracking-tight text-gray-900">Crear Orden de Pago</h1>
			<p class="mt-1 text-sm text-gray-500">Busca un paciente y calcula la orden automáticamente. El envío se
				implementará después.</p>
		</div>

		<div class="grid gap-6 lg:grid-cols-[360px_1fr]">
			<div class="space-y-6">
				<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
					<form method="get" action="{{ route('orders.edit', ['patient' => $patient?->id ?? null]) }}"
						class="space-y-4">
						<div
							class="flex flex-col gap-2 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
							<div>
								<p class="text-sm font-medium text-gray-800">Buscar paciente</p>
								<p class="text-xs text-gray-500">Busca por cédula para cargar sus datos.</p>
							</div>
						</div>

						<div class="flex gap-2">
							<input required type="text" name="id_card" value="{{ $patient?->id_card ?? '' }}" autocomplete="off"
								placeholder="Cédula…"
								class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
							<button type="submit"
								class="inline-flex h-10 shrink-0 items-center justify-center rounded-lg bg-gray-900 px-5 text-sm font-medium text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
								Buscar
							</button>
						</div>
					</form>
				</div>
			</div>

			<div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
				<form id="order-form" class="space-y-6" method="post" action="{{ route('orders.store') }}">
					@csrf
					@method('POST')
					<input type="hidden" name="patient[id]" value="{{ $patient?->id ?? '' }}" />
					<div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4 sm:p-5">
						<div class="mb-4 flex items-center justify-between gap-3">
							<h2 class="text-sm font-semibold uppercase tracking-wide text-gray-600">Datos del paciente</h2>
						</div>

						<div class="grid gap-4 grid-cols-1 sm:grid-cols-2">
							<div class="space-y-4">
								<div>
									<label for="first_name" class="mb-1 block text-sm font-medium text-gray-700">
										Nombres
										<span class="text-red-600">*</span>
									</label>
									<input id="first_name" name="patient[first_name]" type="text" required
										autocomplete="given-name"
										class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-base text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 sm:text-sm"
										placeholder="Ej: Juan Carlos" value="{{ $patient?->first_name ?? '' }}" />
								</div>

								<div>
									<label for="last_name" class="mb-1 block text-sm font-medium text-gray-700">
										Apellidos
										<span class="text-red-600">*</span>
									</label>
									<input id="last_name" name="patient[last_name]" type="text" required
										autocomplete="family-name"
										class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-base text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 sm:text-sm"
										placeholder="Ej: Pérez Gómez" value="{{ $patient?->last_name ?? '' }}" />
								</div>

								<div>
									<label for="id_card" class="mb-1 block text-sm font-medium text-gray-700">
										Cédula
										<span class="text-red-600">*</span>
									</label>
									<input id="id_card" name="patient[id_card]" type="text" required
										class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-base text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 sm:text-sm"
										placeholder="Ej: 00123456789" value="{{ $patient?->id_card ?? '' }}" />
								</div>

								<div>
									<label for="nationality" class="mb-1 block text-sm font-medium text-gray-700">
										Nacionalidad
										<span class="text-red-600">*</span>
									</label>
									<select id="nationality" name="patient[nationality]" required
										class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-base text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 sm:text-sm"
										placeholder="Ej: Venezuela" value="{{ $patient?->nationality ?? '' }}">
										<option value="" selected disabled>Selecciona una opción</option>
										@foreach (get_countries() as $name)
											<option value="{{ $name }}" {{ $patient ? ($patient->nationality === $name ? 'selected' : '') : '' }}>
												{{ $name }}
											</option>
										@endforeach
									</select>
								</div>

								<div>
									<label for="birth_date" class="mb-1 block text-sm font-medium text-gray-700">
										Fecha de nacimiento
										<span class="text-red-600">*</span>
									</label>
									<input id="birth_date" name="patient[birth_date]" type="date" required
										class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-base text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 sm:text-sm"
										value="{{ $patient ? $patient->birth_date->format('Y-m-d') : '' }}" />
								</div>

								<div>
									<label for="gender" class="mb-1 block text-sm font-medium text-gray-700">
										Género
										<span class="text-red-600">*</span>
									</label>
									<select id="gender" name="patient[gender]" required
										class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-base text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 sm:text-sm"
										value="{{ $patient?->gender ?? '' }}">
										<option value="" selected disabled>Selecciona una opción</option>
										@foreach (App\Models\Gender::cases() as $gender)
											<option value="{{ $gender->name }}" {{ $patient?->gender === $gender->name ? 'selected' : '' }}>{{ $gender->label() }}</option>
										@endforeach
									</select>
								</div>
							</div>

							<div class="space-y-4">
								<div>
									<label for="email" class="mb-1 block text-sm font-medium text-gray-700">
										Correo
										<span class="text-red-600">*</span>
									</label>
									<input id="email" name="patient[email]" type="email" required autocomplete="email"
										class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-base text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 sm:text-sm"
										placeholder="ejemplo@correo.com" value="{{ $patient?->email ?? '' }}" />
								</div>

								<div>
									<label for="phone" class="mb-1 block text-sm font-medium text-gray-700">
										Teléfono
										<span class="text-red-600">*</span>
									</label>
									<input id="phone" name="patient[phone]" type="tel" required autocomplete="tel"
										class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-base text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 sm:text-sm"
										placeholder="Ej: +34 600 123 456" value="{{ $patient?->phone ?? '' }}" />
								</div>

								<div>
									<label for="section" class="mb-1 block text-sm font-medium text-gray-700">
										Sección
										<span class="text-red-600">*</span>
									</label>
									<select id="section" name="patient[section]" required
										class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-base text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 sm:text-sm"
										value="{{ $patient?->metadata->firstWhere('meta_key', 'section')->meta_value ?? '' }}">
										<option value="" selected disabled>Selecciona una opción</option>
										@foreach (App\Models\Hierarchy::$STACK as $section)
											<option value="{{ $section['section'] }}">
												{{ $section['section'] }}
											</option>
										@endforeach
									</select>
								</div>

								<div>
									<label for="hierarchy" class="mb-1 block text-sm font-medium text-gray-700">
										Jerarquía
										<span class="text-red-600">*</span>
									</label>
									<select id="hierarchy" name="patient[hierarchy]" required
										class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-base text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 sm:text-sm"
										value="{{ $patient?->metadata->firstWhere('meta_key', 'hierarchy')->meta_value ?? '' }}">
										<option value="" selected disabled>Selecciona una opción</option>
										@foreach (App\Models\Hierarchy::$STACK as $section)
											@foreach ($section['classifications'] as $classification)
												<option data-section="{{ $section['section'] }}"
													value="{{ $classification['name'] }}">
													{{ $classification['name'] }}
												</option>
											@endforeach
										@endforeach
									</select>
								</div>

								<div>
									<label for="role" class="mb-1 block text-sm font-medium text-gray-700">
										Cargo
										<span class="text-red-600">*</span>
									</label>
									<select id="role" name="patient[role]" required
										class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-base text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 sm:text-sm"
										value="{{ $patient?->metadata->firstWhere('meta_key', 'role')->meta_value ?? '' }}">
										<option value="" selected disabled>Selecciona una opción</option>
										@foreach (App\Models\Hierarchy::$STACK as $section)
											@foreach ($section['classifications'] as $classification)
												@foreach ($classification['roles'] as $role)
													<option data-classification="{{ $classification['name'] }}"
														data-section="{{ $section['section'] }}">
														{{ $role }}
													</option>
												@endforeach
											@endforeach
										@endforeach
									</select>
								</div>
							</div>
						</div>

						<div class="mt-4">
							<label for="address" class="mb-1 block text-sm font-medium text-gray-700">
								Dirección
								<span class="text-red-600">*</span>
							</label>
							<textarea id="address" name="patient[address]" rows="3" required autocomplete="street-address"
								class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-base text-gray-900 shadow-sm focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800 sm:text-sm"
								placeholder="Calle, número, sector, ciudad">{{ $patient ? optional($patient->metadata->firstWhere('meta_key', 'address'))->meta_value : '' }}</textarea>
						</div>
					</div>
					<div
						class="flex flex-col gap-2 border-b border-gray-100 pb-4 sm:flex-row sm:items-center sm:justify-between">
						<div>
							<p class="text-sm font-medium text-gray-800">Orden de servicios</p>
							<p class="text-xs text-gray-500">Ajusta las cantidades y el sistema calculará los totales
								automáticamente.</p>
						</div>
						<p class="text-xs text-gray-500"><span class="text-red-600">*</span> Campos obligatorios</p>
					</div>

					<div class="overflow-hidden rounded-xl border border-gray-200">
						<div class="overflow-x-auto">
							<table class="w-full divide-y divide-gray-200 text-sm">
								<thead class="bg-gray-50">
									<tr>
										<th
											class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
											Sel.</th>
										<th
											class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
											Servicio</th>
										<th
											class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
											Cantidad</th>
										<th
											class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
											Valor unitario</th>
										<th
											class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600">
											Subtotal</th>
									</tr>
								</thead>
								<tbody class="divide-y divide-gray-100 bg-white">
									@foreach ($orderItems as $index => $item)
										<tr id="order-row-{{ $index }}">
											<td class="px-4 py-3 align-top text-gray-700">
												<input type="checkbox" name="order[details][{{ $index }}][selected]"
													id="order_details_{{ $index }}_selected" value="1" checked
													data-order-selected data-row-index="{{ $index }}"
													class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900" />
											</td>
											<td class="px-4 py-3 align-top text-gray-700">
												<input type="hidden" name="order[details][{{ $index }}][name]"
													value="{{ $item['name'] }}" />
												<div class="font-medium text-gray-900">{{ $item['name'] }}</div>
											</td>
											<td class="px-4 py-3 align-top text-gray-700">
												<input type="number" name="order[details][{{ $index }}][quantity]"
													id="order_details_{{ $index }}_quantity" min="1" value="1" required
													data-order-quantity data-row-index="{{ $index }}"
													data-price="{{ $item['price'] }}"
													class="w-24 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 focus:border-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-800" />
											</td>
											<td class="px-4 py-3 align-top text-gray-700">
												<input type="hidden" name="order[details][{{ $index }}][price]"
													value="{{ $item['price'] }}" />
												<span
													data-unit-price="{{ $item['price'] }}">${{ number_format($item['price'], 2) }}</span>
											</td>
											<td class="px-4 py-3 align-top text-gray-900">
												<span
													id="order-row-subtotal-{{ $index }}">${{ number_format($item['price'], 2) }}</span>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>

					<div class="grid gap-3 sm:grid-cols-3">
						<div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
							<p class="text-xs font-medium uppercase tracking-wide text-gray-500">Subtotal</p>
							<p id="order-subtotal" class="mt-1 text-lg font-semibold text-gray-900">$0.00</p>
						</div>
						<div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
							<p class="text-xs font-medium uppercase tracking-wide text-gray-500">IVA ({{ $taxRate * 100 }}%)
							</p>
							<p id="order-tax" class="mt-1 text-lg font-semibold text-gray-900">$0.00</p>
						</div>
						<div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3">
							<p class="text-xs font-medium uppercase tracking-wide text-gray-500">Total</p>
							<p id="order-total" class="mt-1 text-lg font-semibold text-gray-900">$0.00</p>
						</div>
					</div>

					<div class="flex justify-end border-t border-gray-100 pt-4">
						<button type="submit"
							class="inline-flex h-10 w-full items-center justify-center rounded-lg bg-gray-900 px-5 text-sm font-medium text-white shadow-sm transition hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 sm:w-auto sm:min-w-56">
							Guardar orden (pendiente)
						</button>
					</div>
				</form>
			</div>
		</div>
	</section>
@endsection

@push('scripts')
	<script>
		document.addEventListener('DOMContentLoaded', () => {
			const orderForm = document.getElementById('order-form');
			if (!orderForm) {
				return;
			}

			const sectionSelect = document.getElementById('section');
			const hierarchySelect = document.getElementById('hierarchy');
			const roleSelect = document.getElementById('role');

			const hierarchyOptions = hierarchySelect
				? Array.from(hierarchySelect.querySelectorAll('option[data-section]'))
				: [];
			const roleOptions = roleSelect
				? Array.from(roleSelect.querySelectorAll('option[data-section][data-classification]'))
				: [];

			const resetSelect = (select) => {
				if (!select) {
					return;
				}

				select.value = '';
			};

			const filterRoles = () => {
				if (!sectionSelect || !hierarchySelect || !roleSelect) {
					return;
				}

				const selectedSection = sectionSelect.value;
				const selectedHierarchy = hierarchySelect.value;

				roleOptions.forEach((option) => {
					const matchesSection = option.dataset.section === selectedSection;
					const matchesHierarchy = option.dataset.classification === selectedHierarchy;
					const shouldShow = Boolean(selectedSection && selectedHierarchy && matchesSection && matchesHierarchy);

					option.hidden = !shouldShow;
					option.disabled = !shouldShow;
				});
			};

			const filterHierarchies = () => {
				if (!sectionSelect || !hierarchySelect) {
					return;
				}

				const selectedSection = sectionSelect.value;

				hierarchyOptions.forEach((option) => {
					const shouldShow = Boolean(selectedSection && option.dataset.section === selectedSection);

					option.hidden = !shouldShow;
					option.disabled = !shouldShow;
				});

				resetSelect(hierarchySelect);
				resetSelect(roleSelect);
				filterRoles();
			};

			if (sectionSelect && hierarchySelect && roleSelect) {
				sectionSelect.addEventListener('change', filterHierarchies);
				hierarchySelect.addEventListener('change', () => {
					resetSelect(roleSelect);
					filterRoles();
				});

				filterHierarchies();
			}

			const taxRate = {{ $taxRate }};
			const moneyFormatter = new Intl.NumberFormat('es-DO', {
				minimumFractionDigits: 2,
				maximumFractionDigits: 2,
			});

			const subtotalElement = document.getElementById('order-subtotal');
			const taxElement = document.getElementById('order-tax');
			const totalElement = document.getElementById('order-total');
			const quantityInputs = Array.from(orderForm.querySelectorAll('[data-order-quantity]'));
			const selectedInputs = Array.from(orderForm.querySelectorAll('[data-order-selected]'));

			const formatMoney = (value) => '$' + moneyFormatter.format(value || 0);

			const normalizeQuantity = (input) => {
				const quantity = Number.parseInt(input.value, 10);
				if (Number.isNaN(quantity) || quantity < 1) {
					input.value = 1;
					return 1;
				}
				return quantity;
			};

			const recalculate = () => {
				let subtotal = 0;

				quantityInputs.forEach((input) => {
					const rowIndex = input.dataset.rowIndex;
					const selectedInput = selectedInputs.find((checkbox) => checkbox.dataset.rowIndex === rowIndex);
					const isSelected = Boolean(selectedInput?.checked);
					const quantity = normalizeQuantity(input);
					const price = Number.parseFloat(input.dataset.price || '0');
					const rowSubtotal = isSelected ? quantity * price : 0;
					const rowSubtotalElement = document.getElementById(`order-row-subtotal-${rowIndex}`);
					const rowElement = document.getElementById(`order-row-${rowIndex}`);

					if (isSelected) {
						subtotal += rowSubtotal;
					}

					input.disabled = !isSelected;
					if (rowElement) {
						rowElement.classList.toggle('opacity-50', !isSelected);
					}

					if (rowSubtotalElement) {
						rowSubtotalElement.textContent = formatMoney(rowSubtotal);
					}
				});

				const tax = subtotal * taxRate;
				const total = subtotal + tax;

				if (subtotalElement) {
					subtotalElement.textContent = formatMoney(subtotal);
				}

				if (taxElement) {
					taxElement.textContent = formatMoney(tax);
				}

				if (totalElement) {
					totalElement.textContent = formatMoney(total);
				}
			};

			quantityInputs.forEach((input) => {
				input.addEventListener('input', recalculate);
				input.addEventListener('change', recalculate);
			});

			selectedInputs.forEach((input) => {
				input.addEventListener('change', recalculate);
			});

			for (option of sectionSelect.options) {
				if (option.value === sectionSelect.getAttribute('value')) {
					option.selected = true;
				}
			};
			for (option of hierarchySelect.options) {
				if (option.value === hierarchySelect.getAttribute('value')) {
					option.selected = true;
				}
			};
			for (option of roleSelect.options) {
				if (option.value === roleSelect.getAttribute('value')) {
					option.selected = true;
				}
			};
			recalculate();
		});
	</script>
@endpush