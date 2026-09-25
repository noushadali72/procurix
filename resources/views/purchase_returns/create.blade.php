<x-layouts.app title="Return Materials">

    <div class="max-w-7xl">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center gap-2 text-xs text-slate-500">

            <a href="{{ route('admin.dashboard') }}" class="transition hover:text-slate-900">
                Dashboard
            </a>

            <i class="bx bx-chevron-right text-slate-400"></i>

            <a href="{{ route('goods-receipts.index') }}" class="transition hover:text-slate-900">
                Goods Receipts
            </a>

            <i class="bx bx-chevron-right text-slate-400"></i>

            <a href="{{ route('goods-receipts.show', $goodsReceipt) }}" class="transition hover:text-slate-900">
                {{ $goodsReceipt->grn_number }}
            </a>

            <i class="bx bx-chevron-right text-slate-400"></i>

            <span class="font-medium text-slate-700">
                Return Materials
            </span>

        </nav>


        {{-- Page Header --}}
        <div class="mb-7 flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-start gap-3">

                <div
                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm">
                    <i class="bx bx-undo text-xl"></i>
                </div>

                <div>

                    <h1 class="text-xl font-semibold tracking-tight text-slate-900">
                        Return Materials
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Return received materials to the vendor from
                        <span class="font-medium text-slate-700">
                            {{ $goodsReceipt->grn_number }}
                        </span>.
                    </p>

                </div>

            </div>

            <a href="{{ route('goods-receipts.show', $goodsReceipt) }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-50">

                <i class="bx bx-arrow-back text-base"></i>

                Back to Receipt

            </a>

        </div>


        {{-- Receipt Status --}}
        @php
            $hasReturnedQty = false;
            $hasReturnableQty = false;

            foreach ($goodsReceipt->items as $receiptItem) {
                $returnedQty = $receiptItem->purchaseReturnItems
                    ->filter(fn($returnItem) => $returnItem->purchaseReturn?->status === 'completed')
                    ->sum('qty');

                $returnableQty = max(0, (float) $receiptItem->qty - (float) $returnedQty);

                if ($returnedQty > 0) {
                    $hasReturnedQty = true;
                }

                if ($returnableQty > 0) {
                    $hasReturnableQty = true;
                }
            }
        @endphp


        {{-- Receipt Summary --}}
        <section class="mb-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- Section Header --}}
            <div
                class="flex flex-col gap-4 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-center gap-3">

                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                        <i class="bx bx-receipt"></i>
                    </div>

                    <div>

                        <h2 class="text-sm font-semibold text-slate-900">
                            Receipt Information
                        </h2>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Details of the goods receipt being returned.
                        </p>

                    </div>

                </div>


                {{-- Return Status --}}
                @if ($hasReturnableQty && $hasReturnedQty)
                    <span
                        class="inline-flex w-fit items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700">

                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>

                        Partially Returned

                    </span>
                @elseif ($hasReturnableQty)
                    <span
                        class="inline-flex w-fit items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700">

                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                        Returnable

                    </span>
                @else
                    <span
                        class="inline-flex w-fit items-center gap-2 rounded-full border border-slate-200 bg-slate-100 px-3 py-1.5 text-xs font-semibold text-slate-600">

                        <i class="bx bx-check-circle text-sm"></i>

                        Fully Returned

                    </span>
                @endif

            </div>


            {{-- Receipt Details --}}
            <div
                class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-2 sm:divide-x sm:divide-y-0 lg:grid-cols-4">

                {{-- GRN --}}
                <div class="px-5 py-4">

                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                        GRN Number
                    </p>

                    <div class="mt-2 flex items-center gap-2">

                        <span class="flex h-7 w-7 items-center justify-center rounded-md bg-slate-100 text-slate-500">
                            <i class="bx bx-file"></i>
                        </span>

                        <p class="text-sm font-semibold text-slate-900">
                            {{ $goodsReceipt->grn_number }}
                        </p>

                    </div>

                </div>


                {{-- Purchase Order --}}
                <div class="px-5 py-4">

                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                        Purchase Order
                    </p>

                    <div class="mt-2 flex items-center gap-2">

                        <span class="flex h-7 w-7 items-center justify-center rounded-md bg-slate-100 text-slate-500">
                            <i class="bx bx-shopping-bag"></i>
                        </span>

                        <a href="{{ route('purchase-orders.show', $goodsReceipt->purchaseOrder) }}"
                            class="text-sm font-semibold text-slate-800 hover:text-slate-950 hover:underline">
                            {{ $goodsReceipt->purchaseOrder->order_number }}
                        </a>

                    </div>

                </div>


                {{-- Vendor --}}
                <div class="px-5 py-4">

                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                        Vendor
                    </p>

                    <div class="mt-2 flex items-center gap-2">

                        <span
                            class="flex h-7 w-7 shrink-0 items-center justify-center rounded-md bg-slate-100 text-slate-500">
                            <i class="bx bx-store"></i>
                        </span>

                        <p class="truncate text-sm font-semibold text-slate-900">
                            {{ $goodsReceipt->purchaseOrder->vendor->company_name ?: $goodsReceipt->purchaseOrder->vendor->name }}
                        </p>

                    </div>

                </div>


                {{-- Received Date --}}
                <div class="px-5 py-4">

                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                        Received Date
                    </p>

                    <div class="mt-2 flex items-center gap-2">

                        <span class="flex h-7 w-7 items-center justify-center rounded-md bg-slate-100 text-slate-500">
                            <i class="bx bx-calendar"></i>
                        </span>

                        <p class="text-sm font-semibold text-slate-900">
                            {{ $goodsReceipt->received_date?->format('d M Y') ?? '-' }}
                        </p>

                    </div>

                </div>

            </div>

        </section>


        <form id="returnForm">

            @csrf


            {{-- Return Details --}}
            <section class="mb-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-5 py-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                            <i class="bx bx-edit-alt"></i>
                        </div>

                        <div>

                            <h2 class="text-sm font-semibold text-slate-900">
                                Return Information
                            </h2>

                            <p class="mt-0.5 text-xs text-slate-500">
                                Provide the details for this material return.
                            </p>

                        </div>

                    </div>

                </div>


                <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                    {{-- Return Date --}}
                    <div>

                        <label for="return_date" class="mb-1.5 block text-sm font-medium text-slate-700">

                            Return Date
                            <span class="text-red-500">*</span>

                        </label>

                        <div class="relative">

                            <i
                                class="bx bx-calendar pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <input type="date" id="return_date" name="return_date"
                                value="{{ now()->toDateString() }}"
                                class="w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-10 pr-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                        </div>

                        <p class="error-return_date mt-1.5 text-xs text-red-600"></p>

                    </div>


                    {{-- Reason --}}
                    <div>

                        <label for="reason" class="mb-1.5 block text-sm font-medium text-slate-700">

                            Reason

                        </label>

                        <div class="relative">

                            <i
                                class="bx bx-list-ul pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>

                            <select id="reason" name="reason"
                                class="w-full appearance-none rounded-lg border border-slate-300 bg-white py-2.5 pl-10 pr-10 text-sm text-slate-700 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                                <option value="">
                                    Select reason
                                </option>

                                <option value="Damaged">
                                    Damaged
                                </option>

                                <option value="Defective">
                                    Defective
                                </option>

                                <option value="Wrong Material">
                                    Wrong Material
                                </option>

                                <option value="Excess Quantity">
                                    Excess Quantity
                                </option>

                                <option value="Quality Issue">
                                    Quality Issue
                                </option>

                                <option value="Other">
                                    Other
                                </option>

                            </select>

                            <i
                                class="bx bx-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></i>

                        </div>

                        <p class="error-reason mt-1.5 text-xs text-red-600"></p>

                    </div>


                    {{-- Notes --}}
                    <div class="md:col-span-2">

                        <label for="notes" class="mb-1.5 block text-sm font-medium text-slate-700">

                            Notes

                        </label>

                        <textarea id="notes" name="notes" rows="3" placeholder="Add any additional details about this return..."
                            class="w-full resize-none rounded-lg border border-slate-300 px-3 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-slate-500 focus:ring-2 focus:ring-slate-200"></textarea>

                        <p class="error-notes mt-1.5 text-xs text-red-600"></p>

                    </div>

                </div>

            </section>


            {{-- Materials --}}
            <section class="mb-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                {{-- Section Header --}}
                <div
                    class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-center gap-3">

                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                            <i class="bx bx-package"></i>
                        </div>

                        <div>

                            <h2 class="text-sm font-semibold text-slate-900">
                                Materials
                            </h2>

                            <p class="mt-0.5 text-xs text-slate-500">
                                Review received quantities and specify the quantity to return.
                            </p>

                        </div>

                    </div>


                    <div
                        class="inline-flex w-fit items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5">

                        <span
                            class="flex h-5 w-5 items-center justify-center rounded-full bg-white text-[10px] font-semibold text-slate-600 shadow-sm">

                            {{ $goodsReceipt->items->count() }}

                        </span>

                        <span class="text-xs font-medium text-slate-600">

                            {{ Str::plural('item', $goodsReceipt->items->count()) }}

                        </span>

                    </div>

                </div>


                {{-- Materials Table --}}
                <div class="overflow-x-auto">

                    <table class="w-full min-w-[900px] text-left text-sm">

                        <thead class="border-b border-slate-200 bg-slate-50">

                            <tr class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">

                                <th class="px-5 py-3.5">
                                    Material
                                </th>

                                <th class="px-5 py-3.5 text-right">
                                    Received
                                </th>

                                <th class="px-5 py-3.5 text-right">
                                    Returned
                                </th>

                                <th class="px-5 py-3.5 text-right">
                                    Returnable
                                </th>

                                <th class="w-52 px-5 py-3.5">
                                    Return Quantity
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @foreach ($goodsReceipt->items as $index => $item)
                                @php
                                    $completedReturnItems = $item->purchaseReturnItems->filter(
                                        fn($returnItem) => $returnItem->purchaseReturn?->status === 'completed',
                                    );

                                    $returnedQty = (float) $completedReturnItems->sum('qty');

                                    $returnableQty = max(0, (float) $item->qty - $returnedQty);

                                    $itemStatus = match (true) {
                                        $returnedQty <= 0 && $returnableQty > 0 => 'received',
                                        $returnedQty > 0 && $returnableQty > 0 => 'partial',
                                        default => 'returned',
                                    };

                                    $orderItem = $item->purchaseOrderItem;

                                    $unitName = $item->unit->short_name ?? ($item->unit->name ?? '-');
                                @endphp


                                <tr class="transition hover:bg-slate-50/70">

                                    {{-- Material --}}
                                    <td class="px-5 py-4">

                                        <input type="hidden"
                                            name="items[{{ $index }}][goods_receipt_item_id]"
                                            value="{{ $item->id }}">

                                        <div class="flex items-center gap-3">

                                            <div
                                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500">

                                                <i class="bx bx-package text-lg"></i>

                                            </div>

                                            <div class="min-w-0">

                                                <p class="truncate font-semibold text-slate-900">
                                                    {{ $orderItem->rawMaterial->name ?? '-' }}
                                                </p>

                                                @if ($orderItem->rawMaterial->sku ?? false)
                                                    <div class="mt-1 inline-flex items-center gap-1.5">

                                                        <span
                                                            class="text-[11px] uppercase tracking-wide text-slate-400">
                                                            SKU
                                                        </span>

                                                        <span class="font-mono text-xs text-slate-500">
                                                            {{ $orderItem->rawMaterial->sku }}
                                                        </span>

                                                    </div>
                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Received --}}
                                    <td class="px-5 py-4 text-right">

                                        <div>

                                            <span class="font-semibold text-slate-900">
                                                {{ $item->qty }}
                                            </span>

                                            <span class="ml-1 text-xs text-slate-500">
                                                {{ $unitName }}
                                            </span>

                                        </div>

                                    </td>


                                    {{-- Returned --}}
                                    <td class="px-5 py-4 text-right">

                                        <div>

                                            <span class="font-medium text-slate-700">
                                                {{ number_format($returnedQty, 3) }}
                                            </span>

                                            <span class="ml-1 text-xs text-slate-500">
                                                {{ $unitName }}
                                            </span>

                                        </div>

                                    </td>


                                    {{-- Returnable / Item Status --}}
                                    <td class="px-5 py-4 text-right">

                                        @if ($itemStatus === 'received')
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">

                                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>

                                                Received

                                            </span>

                                            <p class="mt-1 text-[11px] text-slate-400">

                                                {{ number_format($returnableQty, 3) }}
                                                {{ $unitName }}
                                                available

                                            </p>
                                        @elseif ($itemStatus === 'partial')
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">

                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>

                                                Partially Returned

                                            </span>

                                            <p class="mt-1 text-[11px] text-emerald-600">

                                                {{ number_format($returnableQty, 3) }}
                                                {{ $unitName }}
                                                still returnable

                                            </p>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-500">

                                                <i class="bx bx-check-circle text-sm"></i>

                                                Fully Returned

                                            </span>

                                            <p class="mt-1 text-[11px] text-slate-400">
                                                No quantity remaining
                                            </p>
                                        @endif

                                    </td>


                                    {{-- Return Quantity --}}
                                    <td class="px-5 py-4">

                                        <div class="relative">

                                            <input type="number" name="items[{{ $index }}][qty]"
                                                min="0" max="{{ $returnableQty }}" step="0.001"
                                                value="{{ $returnableQty }}"
                                                {{ $returnableQty <= 0 ? 'disabled' : '' }}
                                                class="return-qty w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 pr-16 text-sm font-medium text-slate-800 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400"
                                                data-max="{{ $returnableQty }}">

                                            <span
                                                class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs font-medium text-slate-400">

                                                {{ $unitName }}

                                            </span>

                                        </div>


                                        @if ($returnableQty > 0)
                                            <p class="mt-1.5 text-[11px] text-slate-400">

                                                Max:
                                                {{ number_format($returnableQty, 3) }}

                                            </p>
                                        @endif

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </section>


            {{-- Action Bar --}}
            @if ($hasReturnableQty)
                <div
                    class="flex flex-col-reverse gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-start gap-2 text-xs text-slate-500">

                        <i class="bx bx-info-circle mt-0.5 text-base text-slate-400"></i>

                        <p>
                            Only quantities greater than zero will be included in this return.
                        </p>

                    </div>


                    <div class="flex items-center justify-end gap-3">

                        <a href="{{ route('goods-receipts.show', $goodsReceipt) }}"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-50">

                            Cancel

                        </a>


                        <button type="submit" id="submitReturn"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-800 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-slate-900 disabled:cursor-not-allowed disabled:opacity-60">

                            <i class="bx bx-undo text-base"></i>

                            Confirm Return

                        </button>

                    </div>

                </div>
            @else
                {{-- Fully Returned State --}}
                <div class="rounded-xl border border-slate-200 bg-white p-8 text-center shadow-sm">

                    <div
                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-500">

                        <i class="bx bx-check-circle text-2xl"></i>

                    </div>

                    <h3 class="mt-4 text-sm font-semibold text-slate-900">
                        All Materials Fully Returned
                    </h3>

                    <p class="mx-auto mt-1.5 max-w-md text-sm text-slate-500">
                        There are no remaining quantities available to return from this goods receipt.
                    </p>

                    <a href="{{ route('goods-receipts.show', $goodsReceipt) }}"
                        class="mt-5 inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-50">

                        <i class="bx bx-arrow-back"></i>

                        Back to Receipt

                    </a>

                </div>
            @endif

        </form>

    </div>


    @push('scripts')
        <script>
            $(document).ready(function() {

                $('#returnForm').on('submit', function(e) {

                    e.preventDefault();

                    $('.error-return_date, .error-reason, .error-notes').text('');

                    let hasQuantity = false;

                    $('.return-qty:not(:disabled)').each(function() {

                        if (parseFloat($(this).val()) > 0) {
                            hasQuantity = true;
                            return false;
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


                        success: function(response) {

                            showToast(
                                'success',
                                response.message
                            );


                            setTimeout(function() {

                                window.location.href = response.redirect;

                            }, 500);

                        },


                        error: function(xhr) {

                            if (xhr.status === 422) {

                                const errors = xhr.responseJSON?.errors;

                                if (errors) {

                                    Object.keys(errors).forEach(function(key) {

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
