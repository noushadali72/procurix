<x-layouts.app title="Goods Receipt">

    <div class="max-w-7xl">

        {{-- Breadcrumb --}}
        <nav class="mb-4 flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900 dark:hover:text-white">
                Dashboard
            </a>

            <i class="bx bx-chevron-right text-base"></i>

            <a href="{{ route('purchase-orders.show', $goodsReceipt->purchaseOrder) }}"
                class="hover:text-gray-900 dark:hover:text-white">
                {{ $goodsReceipt->purchaseOrder->order_number }}
            </a>

            <i class="bx bx-chevron-right text-base"></i>

            <span class="font-medium text-gray-900 dark:text-white">
                {{ $goodsReceipt->grn_number }}
            </span>
        </nav>


        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $goodsReceipt->grn_number }}
                    </h1>

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                        Received
                    </span>
                </div>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Goods receipt against
                    <a href="{{ route('purchase-orders.show', $goodsReceipt->purchaseOrder) }}"
                        class="font-medium text-gray-700 hover:text-gray-900 hover:underline dark:text-gray-300 dark:hover:text-white">
                        {{ $goodsReceipt->purchaseOrder->order_number }}
                    </a>
                </p>
            </div>

            <div class="flex items-center gap-2">

                <a href="{{ route('purchase-orders.show', $goodsReceipt->purchaseOrder) }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    <i class="bx bx-file text-base"></i>
                    Purchase Order
                </a>

                <button type="button"
                    id="delete-grn"
                    class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-3.5 py-2 text-sm font-medium text-white transition hover:bg-red-700">
                    <i class="bx bx-trash text-base"></i>
                    Delete
                </button>

            </div>
        </div>


        {{-- Receipt Summary --}}
        <div class="mb-6 overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">

            <div class="grid grid-cols-1 divide-y divide-gray-200 sm:grid-cols-3 sm:divide-x sm:divide-y-0 dark:divide-gray-700">

                <div class="px-5 py-4">
                    <p class="text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        GRN Number
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                        {{ $goodsReceipt->grn_number }}
                    </p>
                </div>

                <div class="px-5 py-4">
                    <p class="text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Purchase Order
                    </p>

                    <a href="{{ route('purchase-orders.show', $goodsReceipt->purchaseOrder) }}"
                        class="mt-1 inline-block text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400">
                        {{ $goodsReceipt->purchaseOrder->order_number }}
                    </a>
                </div>

                <div class="px-5 py-4">
                    <p class="text-[11px] font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Received Date
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                        {{ $goodsReceipt->received_date?->format('d M Y') ?? '-' }}
                    </p>
                </div>

            </div>
        </div>


        {{-- Receipt Information --}}
        <div class="mb-6 rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-700">
                <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                    Receipt Information
                </h2>
            </div>

            <div class="p-5">

                @if ($goodsReceipt->notes)
                    <div>
                        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Notes
                        </p>

                        <p class="text-sm leading-6 text-gray-700 dark:text-gray-300">
                            {{ $goodsReceipt->notes }}
                        </p>
                    </div>
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        No notes were added to this receipt.
                    </p>
                @endif

            </div>
        </div>


        {{-- Received Materials --}}
        <div class="mb-6 overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">

            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 dark:border-gray-700">

                <div>
                    <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                        Received Materials
                    </h2>

                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                        Materials included in this goods receipt
                    </p>
                </div>

                <span class="rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                    {{ $goodsReceipt->items->count() }} {{ Str::plural('Item', $goodsReceipt->items->count()) }}
                </span>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead class="border-b border-gray-200 bg-gray-50 text-xs font-medium uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-700/50 dark:text-gray-400">
                        <tr>
                            <th class="px-5 py-3.5">Material</th>
                            <th class="px-5 py-3.5">Ordered</th>
                            <th class="px-5 py-3.5">Received</th>
                            <th class="px-5 py-3.5">Remaining</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                        @forelse ($goodsReceipt->items as $item)

                            @php
                                $orderItem = $item->purchaseOrderItem;
                            @endphp

                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">

                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-300">
                                            <i class="bx bx-package text-lg"></i>
                                        </div>

                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">
                                                {{ $orderItem->rawMaterial->name ?? '-' }}
                                            </p>

                                            @if ($orderItem->rawMaterial->sku ?? false)
                                                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                                    SKU: {{ $orderItem->rawMaterial->sku }}
                                                </p>
                                            @endif
                                        </div>

                                    </div>

                                </td>

                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $orderItem->qty }}
                                    </span>
                                    {{ $orderItem->unit->short_name ?? '' }}
                                </td>

                                <td class="px-5 py-4">

                                    <span class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700 dark:bg-green-900/20 dark:text-green-400">
                                        {{ $item->qty }}
                                        {{ $item->unit->short_name ?? '' }}
                                    </span>

                                </td>

                                <td class="px-5 py-4 font-medium text-gray-700 dark:text-gray-300">
                                    {{ $item->remaining_qty }}
                                    {{ $orderItem->unit->short_name ?? '' }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center">

                                    <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-700">
                                        <i class="bx bx-package text-xl"></i>
                                    </div>

                                    <p class="mt-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        No materials received
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        No materials were included in this goods receipt.
                                    </p>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Attachments --}}
        <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-700">

                <div class="flex items-center justify-between">

                    <div>
                        <h2 class="text-sm font-semibold text-gray-900 dark:text-white">
                            Attachments
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                            Documents attached to this goods receipt
                        </p>
                    </div>

                    <span class="rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        {{ $goodsReceipt->attachments->count() }}
                    </span>

                </div>

            </div>

            <div class="p-5">

                @if ($goodsReceipt->attachments->count())

                    <div class="divide-y divide-gray-200 rounded-lg border border-gray-200 dark:divide-gray-700 dark:border-gray-700">

                        @foreach ($goodsReceipt->attachments as $attachment)

                            <div id="attachment-{{ $attachment->id }}"
                                class="flex items-center justify-between gap-4 px-4 py-3.5">

                                <a href="{{ Storage::url($attachment->file_path) }}"
                                    target="_blank"
                                    class="flex min-w-0 items-center gap-3">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-300">
                                        <i class="bx bx-file text-lg"></i>
                                    </div>

                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium text-gray-800 hover:text-blue-600 dark:text-gray-200 dark:hover:text-blue-400">
                                            {{ basename($attachment->file_path) }}
                                        </p>

                                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                            Click to view file
                                        </p>
                                    </div>

                                </a>

                                <button type="button"
                                    class="delete-attachment shrink-0 text-sm font-medium text-red-600 hover:text-red-700 hover:underline dark:text-red-400"
                                    data-url="{{ route('goods-receipt-attachments.destroy', $attachment) }}"
                                    data-id="{{ $attachment->id }}">
                                    Delete
                                </button>

                            </div>

                        @endforeach

                    </div>

                @else

                    <div class="rounded-lg border border-dashed border-gray-300 px-5 py-8 text-center dark:border-gray-600">

                        <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-700">
                            <i class="bx bx-paperclip text-xl"></i>
                        </div>

                        <p class="mt-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                            No attachments
                        </p>

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            No documents have been uploaded for this receipt.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>


    @push('scripts')
        <script>
            $(document).ready(function() {

                $('#delete-grn').on('click', function() {

                    if (!confirm('Are you sure you want to delete this goods receipt?')) {
                        return;
                    }

                    const $button = $(this);

                    $button
                        .prop('disabled', true)
                        .html('<i class="bx bx-loader-alt bx-spin"></i> Deleting...');

                    $.ajax({
                        url: "{{ route('goods-receipts.destroy', $goodsReceipt) }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },
                        headers: {
                            Accept: 'application/json'
                        },
                        success: function(response) {
                            showToast('success', response.message);

                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 500);
                        },
                        error: function(xhr) {
                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to delete goods receipt.'
                            );

                            $button
                                .prop('disabled', false)
                                .html('<i class="bx bx-trash"></i> Delete');
                        }
                    });
                });


                $('.delete-attachment').on('click', function() {

                    if (!confirm('Are you sure you want to delete this attachment?')) {
                        return;
                    }

                    const $button = $(this);
                    const url = $button.data('url');
                    const id = $button.data('id');

                    $button
                        .prop('disabled', true)
                        .text('Deleting...');

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },
                        headers: {
                            Accept: 'application/json'
                        },
                        success: function(response) {
                            showToast('success', response.message);

                            $('#attachment-' + id).fadeOut(200, function() {
                                $(this).remove();
                            });
                        },
                        error: function(xhr) {
                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to delete attachment.'
                            );

                            $button
                                .prop('disabled', false)
                                .text('Delete');
                        }
                    });
                });

            });
        </script>
    @endpush

</x-layouts.app>