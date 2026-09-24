<x-layouts.app title="Return Materials">

    <div class="max-w-7xl">

        {{-- Breadcrumb --}}
        <nav class="mb-5 flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.dashboard') }}"
                class="transition hover:text-slate-800">
                Dashboard
            </a>

            <i class="bx bx-chevron-right text-sm text-slate-400"></i>

            <a href="{{ route('goods-receipts.index') }}"
                class="transition hover:text-slate-800">
                Goods Receipts
            </a>

            <i class="bx bx-chevron-right text-sm text-slate-400"></i>

            <a href="{{ route('goods-receipts.show', $goodsReceipt) }}"
                class="transition hover:text-slate-800">
                {{ $goodsReceipt->grn_number }}
            </a>

            <i class="bx bx-chevron-right text-sm text-slate-400"></i>

            <span class="font-medium text-slate-700">
                Return Materials
            </span>
        </nav>


        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-end sm:justify-between">

            <div>
                <h1 class="text-xl font-semibold tracking-tight text-slate-900">
                    Return Materials
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Return received materials to the vendor from
                    {{ $goodsReceipt->grn_number }}.
                </p>
            </div>

            <a href="{{ route('goods-receipts.show', $goodsReceipt) }}"
                class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                <i class="bx bx-arrow-back"></i>
                Back to Receipt
            </a>

        </div>


        {{-- Receipt Information --}}
        <section class="mb-5 overflow-hidden rounded-lg border border-slate-200 bg-white">

            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">
                    Receipt Information
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">

                <div class="border-b border-slate-100 px-5 py-4 lg:border-b-0 lg:border-r">
                    <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                        GRN Number
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $goodsReceipt->grn_number }}
                    </p>
                </div>

                <div class="border-b border-slate-100 px-5 py-4 lg:border-b-0 lg:border-r">
                    <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                        Purchase Order
                    </p>

                    <a href="{{ route('purchase-orders.show', $goodsReceipt->purchaseOrder) }}"
                        class="mt-1 inline-block text-sm font-semibold text-slate-800 underline">
                        {{ $goodsReceipt->purchaseOrder->order_number }}
                    </a>
                </div>

                <div class="border-b border-slate-100 px-5 py-4 sm:border-b-0 lg:border-r">
                    <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                        Vendor
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $goodsReceipt->purchaseOrder->vendor->company_name ?: $goodsReceipt->purchaseOrder->vendor->name }}
                    </p>
                </div>

                <div class="px-5 py-4">
                    <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                        Received Date
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-900">
                        {{ $goodsReceipt->received_date?->format('d M Y') ?? '-' }}
                    </p>
                </div>

            </div>

        </section>


        <form id="returnForm">

            @csrf

            {{-- Return Information --}}
            <section class="mb-5 overflow-hidden rounded-lg border border-slate-200 bg-white">

                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Return Information
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500">
                        Enter the date and reason for this return.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                    <div>
                        <label for="return_date"
                            class="mb-1.5 block text-sm font-medium text-slate-700">
                            Return Date
                            <span class="text-red-500">*</span>
                        </label>

                        <input
                            type="date"
                            id="return_date"
                            name="return_date"
                            value="{{ now()->toDateString() }}"
                            class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                        <p class="error-return_date mt-1 text-xs text-red-600"></p>
                    </div>


                    <div>
                        <label for="reason"
                            class="mb-1.5 block text-sm font-medium text-slate-700">
                            Reason
                        </label>

                        <select
                            id="reason"
                            name="reason"
                            class="w-full rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                            <option value="">Select reason</option>
                            <option value="Damaged">Damaged</option>
                            <option value="Defective">Defective</option>
                            <option value="Wrong Material">Wrong Material</option>
                            <option value="Excess Quantity">Excess Quantity</option>
                            <option value="Quality Issue">Quality Issue</option>
                            <option value="Other">Other</option>

                        </select>

                        <p class="error-reason mt-1 text-xs text-red-600"></p>
                    </div>


                    <div class="md:col-span-2">
                        <label for="notes"
                            class="mb-1.5 block text-sm font-medium text-slate-700">
                            Notes
                        </label>

                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            placeholder="Additional details about this return..."
                            class="w-full resize-none rounded-md border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"></textarea>

                        <p class="error-notes mt-1 text-xs text-red-600"></p>
                    </div>

                </div>

            </section>


            {{-- Materials --}}
            <section class="mb-5 overflow-hidden rounded-lg border border-slate-200 bg-white">

                <div class="flex flex-col gap-2 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">
                            Materials
                        </h2>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Enter quantities you want to return.
                        </p>
                    </div>

                    <span class="text-xs font-medium text-slate-500">
                        {{ $goodsReceipt->items->count() }}
                        {{ Str::plural('item', $goodsReceipt->items->count()) }}
                    </span>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full min-w-[850px] text-left text-sm">

                        <thead class="border-b border-slate-200 bg-slate-50">
                            <tr class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">

                                <th class="px-5 py-3">
                                    Material
                                </th>

                                <th class="px-5 py-3 text-right">
                                    Received
                                </th>

                                <th class="px-5 py-3 text-right">
                                    Returned
                                </th>

                                <th class="px-5 py-3 text-right">
                                    Returnable
                                </th>

                                <th class="w-48 px-5 py-3">
                                    Return Qty
                                </th>

                            </tr>
                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @foreach($goodsReceipt->items as $index => $item)

                                @php

                                    $completedReturnItems = $item
                                        ->purchaseReturnItems
                                        ->filter(function ($returnItem) {
                                            return $returnItem->purchaseReturn?->status === 'completed';
                                        });

                                    /*
                                     * At the moment return items are stored using
                                     * the Goods Receipt item's unit.
                                     */
                                    $returnedQty = $completedReturnItems->sum('qty');

                                    $returnableQty = max(
                                        0,
                                        (float) $item->qty - (float) $returnedQty
                                    );

                                    $orderItem = $item->purchaseOrderItem;

                                @endphp


                                <tr>

                                    <td class="px-5 py-4">

                                        <input
                                            type="hidden"
                                            name="items[{{ $index }}][goods_receipt_item_id]"
                                            value="{{ $item->id }}">

                                        <div class="flex items-center gap-3">

                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-500">
                                                <i class="bx bx-package text-lg"></i>
                                            </div>

                                            <div>

                                                <p class="font-medium text-slate-900">
                                                    {{ $orderItem->rawMaterial->name ?? '-' }}
                                                </p>

                                                @if($orderItem->rawMaterial->sku ?? false)
                                                    <p class="mt-0.5 text-xs text-slate-400">
                                                        {{ $orderItem->rawMaterial->sku }}
                                                    </p>
                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    <td class="px-5 py-4 text-right">

                                        <span class="font-medium text-slate-900">
                                            {{ $item->qty }}
                                        </span>

                                        <span class="text-slate-500">
                                            {{ $item->unit->short_name ?? $item->unit->name }}
                                        </span>

                                    </td>


                                    <td class="px-5 py-4 text-right">

                                        <span class="font-medium text-slate-700">
                                            {{ number_format($returnedQty, 3) }}
                                        </span>

                                        <span class="text-slate-500">
                                            {{ $item->unit->short_name ?? $item->unit->name }}
                                        </span>

                                    </td>


                                    <td class="px-5 py-4 text-right">

                                        @if($returnableQty > 0)

                                            <span class="inline-flex rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">

                                                {{ number_format($returnableQty, 3) }}

                                                {{ $item->unit->short_name ?? $item->unit->name }}

                                            </span>

                                        @else

                                            <span class="inline-flex rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">
                                                Fully Returned
                                            </span>

                                        @endif

                                    </td>


                                    <td class="px-5 py-4">

                                        <div class="relative">

                                            <input
                                                type="number"
                                                name="items[{{ $index }}][qty]"
                                                min="0"
                                                max="{{ $returnableQty }}"
                                                step="0.001"
                                                value="0"
                                                {{ $returnableQty <= 0 ? 'disabled' : '' }}
                                                class="return-qty w-full rounded-md border border-slate-300 px-3 py-2 pr-14 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 disabled:cursor-not-allowed disabled:bg-slate-100"
                                                data-max="{{ $returnableQty }}">

                                            <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400">
                                                {{ $item->unit->short_name ?? $item->unit->name }}
                                            </span>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </section>


            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3">

                <a href="{{ route('goods-receipts.show', $goodsReceipt) }}"
                    class="rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                    Cancel
                </a>

                <button
                    type="submit"
                    id="submitReturn"
                    class="inline-flex items-center gap-2 rounded-md bg-slate-800 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900 disabled:cursor-not-allowed disabled:opacity-60">

                    <i class="bx bx-undo"></i>
                    Confirm Return

                </button>

            </div>

        </form>

    </div>


    @push('scripts')

        <script>

            $(document).ready(function () {

                $('#returnForm').on('submit', function (e) {

                    e.preventDefault();

                    $('.text-red-600[id^="error"], [class*="error-"]')
                        .text('');

                    let hasQuantity = false;

                    $('.return-qty').each(function () {

                        if (parseFloat($(this).val()) > 0) {
                            hasQuantity = true;
                        }

                    });

                    if (!hasQuantity) {

                        showToast(
                            'error',
                            'Enter a return quantity for at least one material.'
                        );

                        return;
                    }


                    const $button = $('#submitReturn');

                    $button
                        .prop('disabled', true)
                        .html(
                            '<i class="bx bx-loader-alt bx-spin"></i> Processing...'
                        );


                    $.ajax({

                        url: "{{ route('purchase-returns.store', $goodsReceipt) }}",

                        type: 'POST',

                        data: $(this).serialize(),

                        headers: {
                            Accept: 'application/json'
                        },

                        success: function (response) {

                            showToast(
                                'success',
                                response.message
                            );

                            setTimeout(function () {

                                window.location.href =
                                    response.redirect;

                            }, 500);

                        },

                        error: function (xhr) {

                            if (xhr.status === 422) {

                                const errors =
                                    xhr.responseJSON?.errors;

                                if (errors) {

                                    Object.keys(errors).forEach(function (key) {

                                        if (
                                            key === 'return_date' ||
                                            key === 'reason' ||
                                            key === 'notes'
                                        ) {

                                            $('.error-' + key)
                                                .text(errors[key][0]);

                                        }

                                    });

                                }

                            }

                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to process return.'
                            );

                            $button
                                .prop('disabled', false)
                                .html(
                                    '<i class="bx bx-undo"></i> Confirm Return'
                                );

                        }

                    });

                });

            });

        </script>

    @endpush

</x-layouts.app>