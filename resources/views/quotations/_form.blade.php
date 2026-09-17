<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

    {{-- Quotation Information --}}
    <div class="border-b bg-gray-50 px-6 py-4">
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-900 text-white">
                <i class="bx bx-receipt text-xl"></i>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900">
                    Quotation Information
                </h3>

                <p class="text-sm text-gray-500">
                    {{ isset($quotation) ? 'Update vendor and quotation details.' : 'Enter vendor and quotation details.' }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

        {{-- Vendor --}}
        <div>
            <div class="mb-2 flex items-center justify-between">
                <label class="block text-sm font-medium text-gray-700">
                    Vendor <span class="text-red-500">*</span>
                </label>

                <button type="button" id="openVendorModal"
                    class="inline-flex cursor-pointer items-center gap-1 text-xs font-medium text-gray-700 transition hover:text-gray-900">
                    <i class="bx bx-plus"></i>
                    Add Vendor
                </button>
            </div>

            <select name="vendor_id" id="vendor_id"
                class="block w-full rounded-lg border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                <option value="">Select Vendor</option>

                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id }}"
                        {{ old('vendor_id', $quotation->vendor_id ?? '') == $vendor->id ? 'selected' : '' }}>
                        {{ $vendor->company_name ?: $vendor->name }}
                    </option>
                @endforeach
            </select>

            <span id="vendorIdErr" class="mt-1.5 block text-xs text-red-600"></span>
        </div>

        {{-- Quotation Number --}}
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Quotation Number
            </label>

            <input type="number" name="quotation_number" id="quotation_number"
                value="{{ old('quotation_number', $quotation->quotation_number ?? '') }}"
                placeholder="Enter quotation number"
                class="block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-gray-900">

            <span id="quotationNumberErr" class="mt-1.5 block text-xs text-red-600"></span>
        </div>

        {{-- Quotation Date --}}
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Quotation Date <span class="text-red-500">*</span>
            </label>

            <input type="date" name="quotation_date" id="quotation_date"
                value="{{ old(
                    'quotation_date',
                    isset($quotation) ? $quotation->quotation_date->format('Y-m-d') : now()->format('Y-m-d'),
                ) }}"
                class="block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-gray-900">

            <span id="quotationDateErr" class="mt-1.5 block text-xs text-red-600"></span>
        </div>

        {{-- Valid Until --}}
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Valid Until
            </label>

            <input type="date" name="valid_until" id="valid_until"
                value="{{ old('valid_until', isset($quotation) ? $quotation->valid_until?->format('Y-m-d') : '') }}"
                class="block w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-gray-900">

            <span id="validUntilErr" class="mt-1.5 block text-xs text-red-600"></span>
        </div>



        {{-- Status --}}
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Status <span class="text-red-500">*</span>
            </label>

            <select name="status" id="status"
                class="block w-full rounded-lg border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                <option value="pending"
                    {{ old('status', $quotation->status ?? 'pending') === 'pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="accepted" {{ old('status', $quotation->status ?? '') === 'accepted' ? 'selected' : '' }}>
                    Accepted
                </option>

                <option value="expired" {{ old('status', $quotation->status ?? '') === 'expired' ? 'selected' : '' }}>
                    Expired
                </option>

            </select>

            <span id="statusErr" class="mt-1.5 block text-xs text-red-600"></span>
        </div>

        {{-- Notes --}}
        <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Notes
            </label>

            <textarea name="notes" id="notes" rows="3" placeholder="Enter any notes..."
                class="block w-full resize-none rounded-lg border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-gray-900">{{ old('notes', $quotation->notes ?? '') }}</textarea>

            <span id="notesErr" class="mt-1.5 block text-xs text-red-600"></span>
        </div>

    </div>

    {{-- Items --}}
    <div class="border-t">

        <div class="border-b bg-gray-50 px-6 py-4">
            <h3 class="font-semibold text-gray-900">
                Quotation Items
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Enter the price offered by the vendor. You can remove materials that are not included in the quotation.
            </p>
        </div>

        <div class="p-6">

            <div class="overflow-hidden rounded-lg border border-gray-200">

                <div class="overflow-x-auto">

                    <table class="w-full text-left text-sm">

                        <thead class="border-b bg-gray-50">
                            <tr class="text-xs uppercase tracking-wide text-gray-500">
                                <th class="px-4 py-3 font-medium">Raw Material</th>
                                <th class="px-4 py-3 font-medium">Quantity</th>
                                <th class="px-4 py-3 font-medium">Unit</th>
                                <th class="px-4 py-3 font-medium">Unit Price</th>
                                <th class="px-4 py-3 text-right font-medium">Total</th>
                                <th class="px-4 py-3 text-right font-medium">Action</th>
                            </tr>
                        </thead>

                        <tbody id="quotation-items" class="divide-y divide-gray-100">

                            @foreach ($purchaseRequest->items as $index => $requestItem)
                                @php
                                    $quotationItem = isset($quotationItems)
                                        ? $quotationItems->get($requestItem->raw_material_id)
                                        : null;
                                @endphp

                                <tr class="quotation-item">

                                    {{-- Material --}}
                                    <td class="px-4 py-4">

                                        <p class="font-medium text-gray-900">
                                            {{ $requestItem->rawMaterial->name }}
                                        </p>

                                        <p class="mt-0.5 text-xs text-gray-500">
                                            {{ $requestItem->rawMaterial->sku }}
                                        </p>

                                        <input type="hidden" name="items[{{ $index }}][raw_material_id]"
                                            value="{{ $requestItem->raw_material_id }}">

                                    </td>

                                    {{-- Quantity --}}
                                    <td class="px-4 py-4">

                                        <input type="number" step="0.1" min="0.1"
                                            name="items[{{ $index }}][qty]"
                                            value="{{ old("items.$index.qty", $quotationItem?->qty ?? $requestItem->qty) }}"
                                            class="item-qty w-28 rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-gray-900">

                                    </td>

                                    {{-- Unit --}}
                                    <td class="px-4 py-4 text-gray-600">

                                        {{ $requestItem->unit->short_name ?? $requestItem->unit->name }}

                                        <input type="hidden" name="items[{{ $index }}][unit_id]"
                                            value="{{ $requestItem->unit_id }}">

                                    </td>

                                    {{-- Price --}}
                                    <td class="px-4 py-4">

                                        <input type="number" step="0.1" min="0"
                                            name="items[{{ $index }}][price]"
                                            value="{{ old("items.$index.price", $quotationItem?->price ?? '') }}"
                                            placeholder="0.00"
                                            class="item-price w-32 rounded-lg border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-gray-900">

                                    </td>

                                    {{-- Total --}}
                                    <td class="px-4 py-4 text-right">

                                        <span class="item-total font-semibold text-gray-900">
                                            0.00
                                        </span>

                                    </td>

                                    {{-- Remove --}}
                                    <td class="px-4 py-4 text-right">

                                        <button type="button"
                                            class="remove-item inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-50">
                                            <i class="bx bx-trash text-lg"></i>
                                            Remove
                                        </button>

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                        <tfoot class="border-t bg-gray-50">
                            <tr>

                                <td colspan="5" class="px-4 py-4 text-right font-semibold text-gray-700">
                                    Grand Total
                                </td>

                                <td class="px-4 py-4 text-right">

                                    <span id="grand-total" class="text-lg font-bold text-gray-900">
                                        0.00
                                    </span>

                                </td>

                            </tr>
                        </tfoot>

                    </table>

                </div>

            </div>

            <div id="no-items"
                class="mt-4 hidden rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-3 text-sm text-yellow-700">
                Please add at least one quotation item.
            </div>

        </div>

    </div>

    {{-- Footer --}}
    <div class="flex items-center justify-end gap-3 border-t bg-gray-50 px-6 py-4">

        <a href="{{ route('quotations.index') }}"
            class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
            Cancel
        </a>

        <button type="submit" id="submitBtn"
            class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-60">
            <i class="bx bx-save"></i>

            <span id="submitBtnText">
                {{ isset($quotation) ? 'Update Quotation' : 'Save Quotation' }}
            </span>
        </button>

    </div>

</div>


@push('scripts')
    <script>
        $(document).ready(function() {

            function calculateTotals() {

                let grandTotal = 0;

                $('#quotation-items .quotation-item').each(function() {

                    const row = $(this);

                    const qty =
                        parseFloat(
                            row.find('.item-qty').val()
                        ) || 0;

                    const price =
                        parseFloat(
                            row.find('.item-price').val()
                        ) || 0;

                    const total =
                        qty * price;

                    row.find('.item-total')
                        .text(total.toFixed(2));

                    grandTotal += total;
                });

                $('#grand-total')
                    .text(grandTotal.toFixed(2));

                if (
                    $('#quotation-items .quotation-item')
                    .length === 0
                ) {
                    $('#no-items')
                        .removeClass('hidden');
                } else {
                    $('#no-items')
                        .addClass('hidden');
                }

            }


            $(document).on(
                'input',
                '.item-price, .item-qty',
                calculateTotals
            );


            $(document).on(
                'click',
                '.remove-item',
                function() {

                    $(this)
                        .closest('.quotation-item')
                        .remove();

                    calculateTotals();

                }
            );


            calculateTotals();

        });
    </script>
@endpush
