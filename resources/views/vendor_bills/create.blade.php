<x-layouts.app title="Create Vendor Bill">

    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="mb-2 flex items-center gap-2 text-sm text-gray-500">
                    <i class="bx bx-receipt text-lg"></i>
                    <span>Purchasing</span>
                    <i class="bx bx-chevron-right"></i>
                    <a href="{{ route('vendor-bills.index') }}" class="hover:text-gray-900">
                        Vendor Bills
                    </a>
                </div>

                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">
                    Create Vendor Bill
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Create a bill from a purchase order or directly against a vendor.
                </p>
            </div>

            <a href="{{ route('vendor-bills.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                <i class="bx bx-arrow-back text-lg"></i>
                Back to Bills
            </a>
        </div>

        <form action="{{ route('vendor-bills.store') }}" method="POST" id="vendorBillForm">
            @csrf

            {{-- Bill Information --}}
            <div class="overflow-hidden rounded-md border border-gray-200 bg-white">

                <div class="border-b border-gray-200 px-5 py-4">
                    <h2 class="text-base font-semibold text-gray-900">
                        Bill Information
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Select a purchase order if applicable, or create a direct vendor bill.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2 xl:grid-cols-3">

                    {{-- Purchase Order --}}
                    <div>
                        <label for="purchase_order_id" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Purchase Order
                            <span class="font-normal text-gray-400">(Optional)</span>
                        </label>

                        <select name="purchase_order_id" id="purchase_order_id"
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-100">

                            <option value="">Direct Vendor Bill</option>

                            @foreach ($purchaseOrders as $purchaseOrder)
                                <option value="{{ $purchaseOrder->id }}" @selected(old('purchase_order_id') == $purchaseOrder->id)>
                                    {{ $purchaseOrder->po_number ?? 'PO-' . $purchaseOrder->id }}
                                    — {{ $purchaseOrder->vendor->company_name ?: $purchaseOrder->vendor->name }}
                                </option>
                            @endforeach
                        </select>

                        <p id="purchaseOrderIdErr" class="mt-1 text-sm text-red-600"></p>

                        <p class="mt-1.5 text-xs text-gray-500">
                            Selecting a PO automatically fills its vendor and items.
                        </p>
                    </div>

                    {{-- Vendor --}}
                    <div>
                        <label for="vendor_id" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Vendor <span class="text-red-500">*</span>
                        </label>

                        <select name="vendor_id" id="vendor_id" required
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-100">

                            <option value="">Select Vendor</option>

                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}" @selected(old('vendor_id') == $vendor->id)>
                                    {{ $vendor->company_name ?: $vendor->name }}
                                </option>
                            @endforeach
                        </select>

                        <p id="vendorIdErr" class="mt-1 text-sm text-red-600"></p>

                        <p id="vendorHelp" class="mt-1.5 text-xs text-gray-500">
                            Required for every vendor bill.
                        </p>
                    </div>

                    {{-- Bill Date --}}
                    <div>
                        <label for="bill_date" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Bill Date
                        </label>

                        <input type="date" name="bill_date" id="bill_date"
                            value="{{ old('bill_date', now()->toDateString()) }}"
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-100">

                        <p id="billDateErr" class="mt-1 text-sm text-red-600"></p>
                    </div>

                    {{-- Due Date --}}
                    <div>
                        <label for="due_date" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Due Date
                        </label>

                        <input type="date" name="due_date" id="due_date"
                            value="{{ old('due_date', now()->addDays(3)->toDateString()) }}"
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-100">

                        <p id="dueDateErr" class="mt-1 text-sm text-red-600"></p>
                    </div>

                    {{-- Tax --}}
                    <div>
                        <label for="tax" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Tax Amount
                        </label>

                        <input type="number" name="tax" id="tax" value="{{ old('tax', 0) }}" min="0"
                            step="0.01" placeholder="0.00"
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-100">

                        <p id="taxErr" class="mt-1 text-sm text-red-600"></p>
                    </div>

                    {{-- Notes --}}
                    <div class="md:col-span-2 xl:col-span-3">
                        <label for="notes" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Notes
                        </label>

                        <textarea name="notes" id="notes" rows="3" maxlength="255" placeholder="Add any additional information..."
                            class="w-full rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-800 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-100">{{ old('notes') }}</textarea>

                        <p id="notesErr" class="mt-1 text-sm text-red-600"></p>
                    </div>

                </div>
            </div>

            {{-- Bill Items --}}
            <div class="mt-6 overflow-hidden rounded-md border border-gray-200 bg-white">

                <div
                    class="flex flex-col gap-3 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">Bill Items</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Add materials and adjust quantities or unit prices as needed.
                        </p>
                    </div>

                    <button type="button" id="addItem"
                        class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        <i class="bx bx-plus text-lg"></i>
                        Add Item
                    </button>
                </div>

                <div id="poNotice" class="hidden border-b border-blue-100 bg-blue-50 px-5 py-3 text-sm text-blue-800">
                    <i class="bx bx-info-circle mr-1"></i>
                    Items were populated from the selected purchase order. Changes here apply to this bill; the original
                    PO will not be modified automatically.
                </div>

                <div id="directBillNotice" class="border-b border-gray-100 bg-gray-50 px-5 py-3 text-sm text-gray-600">
                    <i class="bx bx-info-circle mr-1"></i>
                    You can create a bill without a purchase order by selecting a vendor and adding items below.
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[850px]">
                        <thead class="border-b border-gray-200 bg-gray-50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <th class="w-12 px-5 py-3">#</th>
                                <th class="min-w-64 px-3 py-3">Raw Material</th>
                                <th class="w-36 px-3 py-3">Quantity</th>
                                <th class="w-44 px-3 py-3">Unit</th>
                                <th class="w-40 px-3 py-3">Unit Price</th>
                                <th class="w-40 px-3 py-3 text-right">Line Total</th>
                                <th class="w-16 px-5 py-3 text-center">Action</th>
                            </tr>
                        </thead>

                        <tbody id="billItems"></tbody>
                    </table>
                </div>

                <div id="emptyItems" class="hidden px-5 py-12 text-center">
                    <i class="bx bx-package text-4xl text-gray-300"></i>

                    <p class="mt-2 text-sm font-medium text-gray-700">No items added</p>
                    <p class="mt-1 text-sm text-gray-500">
                        Add at least one raw material to continue.
                    </p>

                    <button type="button" id="addFirstItem"
                        class="mt-4 inline-flex items-center gap-2 rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                        <i class="bx bx-plus text-lg"></i>
                        Add First Item
                    </button>
                </div>

                {{-- Totals --}}
                <div
                    class="flex flex-col gap-6 border-t border-gray-200 bg-gray-50 p-5 sm:flex-row sm:items-start sm:justify-between">
                    <p class="max-w-md text-xs leading-5 text-gray-500">
                        Verify the quantities and prices before saving. Line totals, subtotal, and grand total are
                        calculated automatically.
                    </p>

                    <div class="w-full space-y-3 sm:max-w-xs">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span id="subtotalDisplay" class="font-medium tabular-nums text-gray-900">0.00</span>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Tax</span>
                            <span id="taxDisplay" class="font-medium tabular-nums text-gray-900">0.00</span>
                        </div>

                        <div class="flex items-center justify-between border-t border-gray-200 pt-3">
                            <span class="text-base font-semibold text-gray-900">Total</span>
                            <span id="totalDisplay"
                                class="text-xl font-semibold tabular-nums text-gray-900">0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('vendor-bills.index') }}"
                    class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Cancel
                </a>

                <button type="submit" id="saveBill"
                    class="inline-flex items-center justify-center gap-2 rounded-md bg-gray-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">
                    <i class="bx bx-save text-lg"></i>
                    Create Vendor Bill
                </button>
            </div>
        </form>
    </div>

    @push('styles')
        <style>
            .bill-input {
                width: 100%;
                border: 1px solid #d1d5db;
                border-radius: 0.375rem;
                padding: 0.55rem 0.65rem;
                font-size: 0.875rem;
                color: #1f2937;
                outline: none;
            }

            .bill-input:focus {
                border-color: #6b7280;
                box-shadow: 0 0 0 2px #f3f4f6;
            }

            .bill-input.is-invalid,
            .is-invalid {
                border-color: #dc2626;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(function() {
                const purchaseOrders =
                    {{ Illuminate\Support\Js::from(
                        $purchaseOrders->map(
                                fn($po) => [
                                    'id' => $po->id,
                                    'vendor_id' => $po->vendor_id,
                                    'items' => $po->items->map(
                                            fn($item) => [
                                                'raw_material_id' => $item->raw_material_id,
                                                'qty' => $item->qty,
                                                'unit_id' => $item->unit_id,
                                                'unit_cost' => $item->unit_cost ?? 0,
                                            ],
                                        )->values(),
                                ],
                            )->values(),
                    ) }};

                const rawMaterials =
                    {{ Illuminate\Support\Js::from(
                        $rawMaterials->map(
                                fn($material) => [
                                    'id' => $material->id,
                                    'name' => $material->name,
                                    'sku' => $material->sku,
                                    'unit_id' => $material->unit_id,
                                    'cost_price' => $material->cost_price,
                                ],
                            )->values(),
                    ) }};

                const units =
                    {{ Illuminate\Support\Js::from(
                        $units->map(
                                fn($unit) => [
                                    'id' => $unit->id,
                                    'name' => $unit->name,
                                    'short_name' => $unit->short_name,
                                ],
                            )->values(),
                    ) }};

                let itemIndex = 0;
                let originalPoVendorId = null;
                let submitting = false;

                const money = value => Number(value || 0).toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                const escapeHtml = value => $('<div>').text(value ?? '').html();

                function materialOptions(selectedId = '') {
                    let html = '<option value="">Select Raw Material</option>';

                    rawMaterials.forEach(material => {
                        const selected = String(material.id) === String(selectedId) ? 'selected' : '';
                        const label = material.sku ?
                            `${material.name} (${material.sku})` :
                            material.name;

                        html += `<option value="${material.id}" ${selected}>${escapeHtml(label)}</option>`;
                    });

                    return html;
                }

                function unitOptions(selectedId = '') {
                    let html = '<option value="">Select Unit</option>';

                    units.forEach(unit => {
                        const selected = String(unit.id) === String(selectedId) ? 'selected' : '';
                        const label = unit.short_name ?
                            `${unit.name} (${unit.short_name})` :
                            unit.name;

                        html += `<option value="${unit.id}" ${selected}>${escapeHtml(label)}</option>`;
                    });

                    return html;
                }

                function addItem(item = {}) {
                    const index = itemIndex++;

                    $('#billItems').append(`
                        <tr class="bill-item border-b border-gray-100">
                            <td class="row-number px-5 py-4 text-sm text-gray-400"></td>

                            <td class="px-3 py-3">
                                <select name="items[${index}][raw_material_id]" class="bill-input raw-material-select" required>
                                    ${materialOptions(item.raw_material_id)}
                                </select>
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" name="items[${index}][qty]"
                                    class="bill-input item-qty" min="0.01" step="0.01"
                                    value="${item.qty ?? 1}" required>
                            </td>

                            <td class="px-3 py-3">
                                <select name="items[${index}][unit_id]" class="bill-input item-unit" required>
                                    ${unitOptions(item.unit_id)}
                                </select>
                            </td>

                            <td class="px-3 py-3">
                                <input type="number" name="items[${index}][unit_cost]"
                                    class="bill-input item-price" min="0.01" step="0.01"
                                    value="${item.unit_cost ?? ''}" placeholder="0.00" required>
                            </td>

                            <td class="px-3 py-3 text-right">
                                <span class="line-total text-sm font-medium tabular-nums text-gray-800">0.00</span>
                            </td>

                            <td class="px-5 py-3 text-center">
                                <button type="button" class="remove-item inline-flex h-8 w-8 items-center justify-center rounded-md text-gray-400 transition hover:bg-red-50 hover:text-red-600" title="Remove item">
                                    <i class="bx bx-trash text-lg"></i>
                                </button>
                            </td>
                        </tr>
                    `);

                    const $row = $('#billItems tr').last();

                    if (item.raw_material_id && !item.unit_id) {
                        const material = rawMaterials.find(
                            material => String(material.id) === String(item.raw_material_id)
                        );

                        if (material) {
                            $row.find('.item-unit').val(material.unit_id);
                        }
                    }

                    updateRows();
                    updateTotals();
                }

                function updateRows() {
                    $('#billItems tr').each(function(index) {
                        $(this).find('.row-number').text(index + 1);
                    });

                    const hasItems = $('#billItems tr').length > 0;

                    $('#emptyItems').toggleClass('hidden', hasItems);
                    $('#billItems').toggleClass('hidden', !hasItems);
                }

                function updateTotals() {
                    let subtotal = 0;

                    $('#billItems tr').each(function() {
                        const qty = parseFloat($(this).find('.item-qty').val()) || 0;
                        const price = parseFloat($(this).find('.item-price').val()) || 0;
                        const lineTotal = qty * price;

                        subtotal += lineTotal;
                        $(this).find('.line-total').text(money(lineTotal));
                    });

                    const tax = Math.max(0, parseFloat($('#tax').val()) || 0);

                    $('#subtotalDisplay').text(money(subtotal));
                    $('#taxDisplay').text(money(tax));
                    $('#totalDisplay').text(money(subtotal + tax));
                }

                function populatePurchaseOrder(poId) {
                    const po = purchaseOrders.find(po => String(po.id) === String(poId));

                    if (!po) {
                        originalPoVendorId = null;
                        $('#poNotice').addClass('hidden');
                        $('#directBillNotice').removeClass('hidden');
                        return;
                    }

                    originalPoVendorId = String(po.vendor_id);
                    $('#vendor_id').val(po.vendor_id);
                    $('#billItems').empty();
                    itemIndex = 0;

                    po.items.forEach(item => addItem(item));

                    $('#poNotice').removeClass('hidden');
                    $('#directBillNotice').addClass('hidden');

                    updateRows();
                    updateTotals();
                }

                function clearErrors() {
                    $('#purchaseOrderIdErr, #vendorIdErr, #billDateErr, #dueDateErr, #taxErr, #notesErr')
                        .text('');

                    $('.bill-input, #vendor_id, #purchase_order_id')
                        .removeClass('is-invalid border-red-500');
                }

                $('#addItem, #addFirstItem').on('click', function() {
                    addItem();
                });

                $('#purchase_order_id').on('change', function() {
                    if ($(this).val()) {
                        populatePurchaseOrder($(this).val());
                        return;
                    }

                    originalPoVendorId = null;
                    $('#poNotice').addClass('hidden');
                    $('#directBillNotice').removeClass('hidden');
                });

                $('#vendor_id').on('change', function() {
                    const vendorId = String($(this).val() || '');

                    if ($('#purchase_order_id').val() &&
                        originalPoVendorId &&
                        vendorId !== originalPoVendorId) {

                        $('#purchase_order_id').val('');
                        originalPoVendorId = null;

                        $('#poNotice').addClass('hidden');
                        $('#directBillNotice').removeClass('hidden');

                        $('#vendorHelp').text(
                            'Direct vendor bill: the purchase order association has been removed.');
                    } else {
                        $('#vendorHelp').text('Required for every vendor bill.');
                    }
                });

                $(document).on('change', '.raw-material-select', function() {
                    const material = rawMaterials.find(
                        material => String(material.id) === String($(this).val())
                    );

                    if (!material) return;

                    const $row = $(this).closest('tr');

                    $row.find('.item-unit').val(material.unit_id);
                    $row.find('.item-price').val(material.cost_price ?? '');

                    updateTotals();
                });

                $(document).on('input change', '.item-qty, .item-price, #tax', updateTotals);

                $(document).on('click', '.remove-item', function() {
                    $(this).closest('tr').remove();
                    updateRows();
                    updateTotals();
                });

                $('#vendorBillForm').on('submit', function(e) {
                    e.preventDefault();

                    if (submitting) return;

                    clearErrors();

                    let valid = true;

                    if (!$('#vendor_id').val()) {
                        $('#vendor_id').addClass('border-red-500');
                        $('#vendorIdErr').text('Vendor is required.');
                        valid = false;
                    }

                    if ($('#billItems tr').length === 0) {
                        showToast('error', 'Add at least one item to the vendor bill.');
                        valid = false;
                    }

                    $('#billItems tr').each(function() {
                        const $row = $(this);
                        const qty = parseFloat($row.find('.item-qty').val());
                        const price = parseFloat($row.find('.item-price').val());

                        if (!$row.find('.raw-material-select').val()) {
                            $row.find('.raw-material-select').addClass('is-invalid');
                            valid = false;
                        }

                        if (!Number.isFinite(qty) || qty <= 0) {
                            $row.find('.item-qty').addClass('is-invalid');
                            valid = false;
                        }

                        if (!$row.find('.item-unit').val()) {
                            $row.find('.item-unit').addClass('is-invalid');
                            valid = false;
                        }

                        if (!Number.isFinite(price) || price <= 0) {
                            $row.find('.item-price').addClass('is-invalid');
                            valid = false;
                        }
                    });

                    if (!valid) {
                        showToast('error', 'Please complete all required fields correctly.');
                        return;
                    }

                    submitting = true;

                    const $button = $('#saveBill');

                    $button.prop('disabled', true)
                        .addClass('cursor-not-allowed opacity-60')
                        .html('<i class="bx bx-loader-alt bx-spin text-lg"></i> Creating...');

                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: $(this).serialize(),
                        dataType: 'json',
                        headers: {
                            Accept: 'application/json'
                        },

                        success: function(response) {
                            showToast('success', response.message ||
                                'Vendor bill created successfully.');

                            setTimeout(function() {
                                window.location.href = response.redirect ||
                                    "{{ route('vendor-bills.index') }}";
                            }, 500);
                        },

                        error: function(xhr) {
                            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                                const errors = xhr.responseJSON.errors;

                                $('#purchaseOrderIdErr').text(errors.purchase_order_id?.[0] || '');
                                $('#vendorIdErr').text(errors.vendor_id?.[0] || '');
                                $('#billDateErr').text(errors.bill_date?.[0] || '');
                                $('#dueDateErr').text(errors.due_date?.[0] || '');
                                $('#taxErr').text(errors.tax?.[0] || '');
                                $('#notesErr').text(errors.notes?.[0] || '');

                                const itemErrors = Object.entries(errors)
                                    .filter(([field]) => field === 'items' || field.startsWith(
                                        'items.'))
                                    .flatMap(([, messages]) => messages);

                                if (itemErrors.length) {
                                    showToast('error', [...new Set(itemErrors)].join(' '));
                                }

                                const otherErrors = Object.entries(errors)
                                    .filter(([field]) => ![
                                        'purchase_order_id',
                                        'vendor_id',
                                        'bill_date',
                                        'due_date',
                                        'tax',
                                        'notes'
                                    ].includes(field) && field !== 'items' && !field.startsWith(
                                        'items.'))
                                    .flatMap(([, messages]) => messages);

                                if (otherErrors.length) {
                                    showToast('error', [...new Set(otherErrors)].join(' '));
                                }

                                return;
                            }

                            showToast('error', xhr.responseJSON?.message ||
                                'Unable to create vendor bill. Please try again.');
                        },

                        complete: function() {
                            submitting = false;

                            $button.prop('disabled', false)
                                .removeClass('cursor-not-allowed opacity-60')
                                .html('<i class="bx bx-save text-lg"></i> Create Vendor Bill');
                        }
                    });
                });

                // Restore previously submitted values.
                const oldItems = @json(old('items', []));

                if (oldItems.length) {
                    oldItems.forEach(item => addItem(item));
                } else {
                    const selectedPoId = $('#purchase_order_id').val();

                    if (selectedPoId) {
                        populatePurchaseOrder(selectedPoId);
                    } else {
                        addItem();
                    }
                }

                updateRows();
                updateTotals();
            });
        </script>
    @endpush

</x-layouts.app>
