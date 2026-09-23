<div class="space-y-5">

    {{-- Purchase Order --}}
    <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-5 py-4">

            <h2 class="text-sm font-semibold text-slate-900">
                Purchase Order
            </h2>

            <p class="mt-0.5 text-xs text-slate-500">
                Order details for the materials being received.
            </p>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-3">

            {{-- Order Number --}}
            <div class="border-b border-slate-100 px-5 py-4 md:border-r">
                <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                    Order Number
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-900">
                    {{ $purchaseOrder->order_number }}
                </p>
            </div>


            {{-- Vendor --}}
            <div class="border-b border-slate-100 px-5 py-4 md:border-r">
                <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                    Vendor
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-900">
                    {{ $purchaseOrder->vendor->company_name ?: $purchaseOrder->vendor->name }}
                </p>
            </div>


            {{-- Order Date --}}
            <div class="border-b border-slate-100 px-5 py-4">
                <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                    Order Date
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-900">
                    {{ optional($purchaseOrder->order_date)->format('d M Y') }}
                </p>
            </div>

        </div>

    </section>


    {{-- Receipt Information --}}
    <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-5 py-4">

            <h2 class="text-sm font-semibold text-slate-900">
                Receipt Information
            </h2>

            <p class="mt-0.5 text-xs text-slate-500">
                Enter the date and any relevant receiving notes.
            </p>

        </div>


        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

            {{-- Received Date --}}
            <div>

                <label for="received_date"
                    class="mb-1.5 block text-xs font-medium text-slate-700">
                    Received Date
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="date"
                    id="received_date"
                    name="received_date"
                    value="{{ old('received_date', now()->toDateString()) }}"
                    required
                    class="w-full rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                >

                <span
                    class="mt-1 hidden text-xs text-red-600"
                    data-error="received_date">
                </span>

            </div>


            {{-- Notes --}}
            <div>

                <label for="notes"
                    class="mb-1.5 block text-xs font-medium text-slate-700">
                    Notes
                </label>

                <textarea
                    id="notes"
                    name="notes"
                    rows="1"
                    placeholder="Add notes about this receipt"
                    class="w-full resize-none rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                >{{ old('notes') }}</textarea>

                <span
                    class="mt-1 hidden text-xs text-red-600"
                    data-error="notes">
                </span>

            </div>

        </div>

    </section>


    {{-- Received Materials --}}
    <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-5 py-4">

            <h2 class="text-sm font-semibold text-slate-900">
                Received Materials
            </h2>

            <p class="mt-0.5 text-xs text-slate-500">
                Enter the quantity actually received. Partial receiving is supported.
            </p>

        </div>


        <div id="receipt-items" class="divide-y divide-slate-100">

            @foreach ($purchaseOrder->items as $index => $orderItem)

                @php
                    $receivedQty = $orderItem->goodsReceiptItems->sum(function ($receiptItem) use ($orderItem) {
                        return ($receiptItem->qty * $receiptItem->unit->conversion_factor)
                            / $orderItem->unit->conversion_factor;
                    });

                    $remainingQty = max(
                        0,
                        (float) $orderItem->qty - (float) $receivedQty
                    );
                @endphp


                @if ($remainingQty > 0)

                    <div
                        class="receipt-item px-5 py-4"
                        data-index="{{ $index }}"
                        data-category-id="{{ $orderItem->unit->unit_category_id }}"
                    >

                        <input
                            type="hidden"
                            name="items[{{ $index }}][purchase_order_item_id]"
                            value="{{ $orderItem->id }}"
                        >


                        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                            {{-- Material --}}
                            <div>

                                <label class="mb-1.5 block text-xs font-medium text-slate-600">
                                    Raw Material
                                </label>

                                <div class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2.5">

                                    <p class="text-sm font-medium text-slate-900">
                                        {{ $orderItem->rawMaterial->name ?? '-' }}
                                    </p>

                                    @if ($orderItem->rawMaterial->sku)
                                        <p class="mt-0.5 text-[11px] text-slate-400">
                                            {{ $orderItem->rawMaterial->sku }}
                                        </p>
                                    @endif

                                </div>

                                <p class="mt-1 text-[11px] text-slate-500">
                                    Ordered:
                                    <span class="font-medium text-slate-700">
                                        {{ $orderItem->qty }}
                                        {{ $orderItem->unit->short_name ?? '' }}
                                    </span>
                                </p>

                            </div>


                            {{-- Remaining --}}
                            <div>

                                <label class="mb-1.5 block text-xs font-medium text-slate-600">
                                    Remaining
                                </label>

                                <div class="rounded-md border border-amber-200 bg-amber-50 px-3 py-2.5">

                                    <p class="text-sm font-semibold text-amber-800">
                                        {{ $remainingQty }}
                                        {{ $orderItem->unit->short_name ?? '' }}
                                    </p>

                                </div>

                            </div>


                            {{-- Received Quantity --}}
                            <div>

                                <label
                                    for="qty_{{ $index }}"
                                    class="mb-1.5 block text-xs font-medium text-slate-700"
                                >
                                    Received Quantity
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="qty_{{ $index }}"
                                    inputmode="numeric"
                                    value="{{ $remainingQty }}"
                                    name="items[{{ $index }}][qty]"
                                    step="any"
                                    placeholder="Enter quantity"
                                    required
                                    class="received-qty w-full rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                >

                                <span
                                    class="mt-1 hidden text-xs text-red-600"
                                    data-error="items.{{ $index }}.qty">
                                </span>

                            </div>


                            {{-- Unit --}}
                            <div>

                                <label
                                    for="unit_{{ $index }}"
                                    class="mb-1.5 block text-xs font-medium text-slate-700"
                                >
                                    Unit
                                    <span class="text-red-500">*</span>
                                </label>

                                <select
                                    id="unit_{{ $index }}"
                                    name="items[{{ $index }}][unit_id]"
                                    required
                                    class="receipt-unit w-full rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                >

                                    <option value="">
                                        Select Unit
                                    </option>

                                    @foreach ($units as $unit)

                                        <option
                                            value="{{ $unit->id }}"
                                            data-category-id="{{ $unit->unit_category_id }}"
                                            {{ $unit->id == $orderItem->unit_id ? 'selected' : '' }}
                                        >
                                            {{ $unit->name }}
                                            ({{ $unit->short_name }})
                                        </option>

                                    @endforeach

                                </select>

                                <span
                                    class="mt-1 hidden text-xs text-red-600"
                                    data-error="items.{{ $index }}.unit_id">
                                </span>

                            </div>

                        </div>

                    </div>

                @endif

            @endforeach

        </div>


        {{-- All Received --}}
        @if (
            $purchaseOrder->items->every(function ($item) {

                $receivedQty = $item->goodsReceiptItems->sum(function ($receiptItem) use ($item) {
                    return ($receiptItem->qty * $receiptItem->unit->conversion_factor)
                        / $item->unit->conversion_factor;
                });

                return $receivedQty >= $item->qty;
            })
        )

            <div class="border-t border-emerald-200 bg-emerald-50 px-5 py-4">

                <div class="flex items-center gap-2 text-sm font-medium text-emerald-700">

                    <i class="bx bx-check-circle text-lg"></i>

                    All purchase order items have already been fully received.

                </div>

            </div>

        @endif

    </section>


    {{-- Attachments --}}
    <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-5 py-4">

            <h2 class="text-sm font-semibold text-slate-900">
                Attachments
            </h2>

            <p class="mt-0.5 text-xs text-slate-500">
                Upload delivery photos, invoices, packing slips, or receiving documents.
            </p>

        </div>


        <div class="p-5">

            <input
                type="file"
                id="attachments"
                name="attachments[]"
                multiple
                accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx"
                class="block w-full rounded-md border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-slate-700 hover:file:bg-slate-200"
            >

            <p class="mt-2 text-[11px] text-slate-500">
                Maximum 10 MB per file.
            </p>

            <span
                class="mt-1 hidden text-xs text-red-600"
                data-error="attachments">
            </span>

        </div>

    </section>

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
