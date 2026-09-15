<div class="space-y-6">

    {{-- Purchase Order Information --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">

        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
            Purchase Order
        </h2>


        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            {{-- Order Number --}}
            <div>

                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Order Number
                </label>

                <input type="text" value="{{ $purchaseOrder->order_number }}" readonly
                    class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">

            </div>


            {{-- Vendor --}}
            <div>

                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Vendor
                </label>

                <input type="text" value="{{ $purchaseOrder->vendor->name ?? '-' }}" readonly
                    class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">

            </div>


            {{-- Order Date --}}
            <div>

                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Order Date
                </label>

                <input type="text" value="{{ optional($purchaseOrder->order_date)->format('d M Y') }}" readonly
                    class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">

            </div>

        </div>

    </div>


    {{-- Receipt Information --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">

        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
            Receipt Information
        </h2>


        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            {{-- Received Date --}}
            <div>

                <label for="received_date" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Received Date
                </label>

                <input type="date" id="received_date" name="received_date"
                    value="{{ old('received_date', now()->toDateString()) }}" required
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

                <span class="mt-1 hidden text-sm text-red-600" data-error="received_date"></span>

            </div>


            {{-- Notes --}}
            <div>

                <label for="notes" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Notes
                </label>

                <textarea id="notes" name="notes" rows="3" placeholder="Add any notes about this receipt"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('notes') }}</textarea>

                <span class="mt-1 hidden text-sm text-red-600" data-error="notes"></span>

            </div>

        </div>

    </div>


    {{-- Items --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">

        <div class="mb-4 flex items-center justify-between">

            <div>

                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Received Materials
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Enter the quantity actually received. You can receive materials partially.
                </p>

            </div>

        </div>


        <div id="receipt-items" class="space-y-4">

            @foreach ($purchaseOrder->items as $index => $orderItem)
                @php

                    /*
                     * Total quantity already received,
                     * converted to the purchase order item's unit.
                     */
                    $receivedQty = $orderItem->goodsReceiptItems->sum(function ($receiptItem) use ($orderItem) {
                        return ($receiptItem->qty * $receiptItem->unit->conversion_factor) /
                            $orderItem->unit->conversion_factor;
                    });

                    /*
                     * Quantity still remaining before this receipt.
                     */
                    $remainingQty = max(0, (float) $orderItem->qty - (float) $receivedQty);
                @endphp


                @if ($remainingQty > 0)
                <div
                    class="receipt-item rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                    data-index="{{ $index }}"
                    data-category-id="{{ $orderItem->unit->unit_category_id }}"
                >

                        <input type="hidden" name="items[{{ $index }}][purchase_order_item_id]"
                            value="{{ $orderItem->id }}">


                        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                            {{-- Material --}}
                            <div>

                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Raw Material
                                </label>

                                <input type="text" value="{{ $orderItem->rawMaterial->name ?? '-' }}" readonly
                                    class="w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">

                                <p class="mt-1 text-xs text-blue-600 dark:text-gray-400">

                                    Ordered:
                                    {{ $orderItem->qty }}
                                    {{ $orderItem->unit->short_name ?? '' }}

                                </p>

                            </div>


                            {{-- Remaining --}}
                            <div>

                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Remaining
                                </label>

                                <input type="text"
                                    value="{{ $remainingQty }} {{ $orderItem->unit->short_name ?? '' }}" readonly
                                    class="remaining-qty w-full rounded-lg border border-gray-300 bg-gray-100 px-4 py-2.5 text-sm text-gray-700 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">

                            </div>


                            {{-- Received Quantity --}}
                            <div>

                                <label for="qty_{{ $index }}"
                                    class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Received Quantity
                                </label>

                                <input type="number" id="qty_{{ $index }}"
                                    name="items[{{ $index }}][qty]" value="{{ old("items.$index.qty") }}"
                                    min="0.0001" step="any" placeholder="Enter received quantity" required
                                    class="received-qty w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

                                <span class="mt-1 hidden text-sm text-red-600"
                                    data-error="items.{{ $index }}.qty"></span>

                            </div>


                            {{-- Unit --}}
                            <div>

                                <label for="unit_{{ $index }}"
                                    class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Unit
                                </label>


                                <select id="unit_{{ $index }}" name="items[{{ $index }}][unit_id]"
                                    required
                                    class="receipt-unit w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

                                    <option value="">
                                        Select Unit
                                    </option>


                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}"
                                            data-category-id="{{ $unit->unit_category_id }}"
                                            {{ $unit->id == $orderItem->unit_id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                            ({{ $unit->short_name }})
                                        </option>
                                    @endforeach

                                </select>


                                <span class="mt-1 hidden text-sm text-red-600"
                                    data-error="items.{{ $index }}.unit_id"></span>

                            </div>

                        </div>

                    </div>
                @endif
            @endforeach

        </div>


        @if (
            $purchaseOrder->items->every(function ($item) {
                $receivedQty = $item->goodsReceiptItems->sum(function ($receiptItem) use ($item) {
                    return ($receiptItem->qty * $receiptItem->unit->conversion_factor) / $item->unit->conversion_factor;
                });
        
                return $receivedQty >= $item->qty;
            }))
            <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">

                All purchase order items have already been fully received.

            </div>
        @endif

    </div>


    {{-- Attachments --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">

        <h2 class="mb-1 text-lg font-semibold text-gray-900 dark:text-white">
            Attachments
        </h2>

        <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">
            Upload delivery photos, invoices, packing slips, or other receiving documents.
        </p>


        <input type="file" id="attachments" name="attachments[]" multiple
            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx"
            class="block w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-900 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:file:bg-gray-600">


        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
            Maximum 10 MB per file.
        </p>


        <span class="mt-1 hidden text-sm text-red-600" data-error="attachments"></span>

    </div>


</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            const container = $("#receipt-items");


            /*
            |--------------------------------------------------------------------------
            | Filter Units
            |--------------------------------------------------------------------------
            */

            function filterUnits(item) {

                const unitSelect = item.find(".receipt-unit");

                const categoryId = item.data("category-id");

                unitSelect.find("option").each(function () {

                    const option = $(this);

                    if (!option.val()) {
                        option.show();
                        return;
                    }

                    const optionCategoryId = option.data("category-id");

                    option.toggle(
                        categoryId &&
                        Number(optionCategoryId) === Number(categoryId)
                    );
                });

                const selectedOption = unitSelect.find("option:selected");

                if (
                    selectedOption.val() &&
                    Number(selectedOption.data("category-id")) !== Number(categoryId)
                ) {
                    unitSelect.val("");
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Initialize Existing Receipt Rows
            |--------------------------------------------------------------------------
            */

            container.find(".receipt-item").each(function() {

                filterUnits($(this));

            });

        });
    </script>
@endpush
