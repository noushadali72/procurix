<x-layouts.app title="Goods Receipt">

    <div class="mb-6 flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                {{ $goodsReceipt->grn_number }}
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Goods received against
                {{ $goodsReceipt->purchaseOrder->order_number }}
            </p>
        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('purchase-orders.show', $goodsReceipt->purchaseOrder) }}"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
            >
                Purchase Order
            </a>

            <button
                type="button"
                id="delete-grn"
                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700"
            >
                Delete
            </button>

        </div>

    </div>


    {{-- GRN Information --}}
    <div class="mb-6 rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">

        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
            Receipt Information
        </h2>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    GRN Number
                </p>

                <p class="mt-1 font-medium text-gray-900 dark:text-white">
                    {{ $goodsReceipt->grn_number }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Purchase Order
                </p>

                <p class="mt-1 font-medium text-gray-900 dark:text-white">
                    {{ $goodsReceipt->purchaseOrder->order_number }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Received Date
                </p>

                <p class="mt-1 font-medium text-gray-900 dark:text-white">
                    {{ $goodsReceipt->received_date->format('d M Y') }}
                </p>
            </div>

        </div>

        @if($goodsReceipt->notes)
            <div class="mt-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Notes
                </p>

                <p class="mt-1 text-sm text-gray-900 dark:text-gray-200">
                    {{ $goodsReceipt->notes }}
                </p>
            </div>
        @endif

    </div>


    {{-- Received Items --}}
    <div class="mb-6 overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">

        <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                Received Materials
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">

                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-3">Raw Material</th>
                        <th class="px-6 py-3">Ordered</th>
                        <th class="px-6 py-3">Received</th>
                        <th class="px-6 py-3">Remaining</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                    @foreach($goodsReceipt->items as $item)

                        @php
                            $orderItem = $item->purchaseOrderItem;

                            $receivedQty = $orderItem->goodsReceiptItems
                                ->sum(function ($receiptItem) use ($orderItem) {
                                    return $receiptItem->qty
                                        * $receiptItem->unit->conversion_factor
                                        / $orderItem->unit->conversion_factor;
                                });

                            $remainingQty = max(
                                0,
                                $orderItem->qty - $receivedQty
                            );
                        @endphp

                        <tr>

                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $orderItem->rawMaterial->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $orderItem->qty }}
                                {{ $orderItem->unit->short_name ?? '' }}
                            </td>

                            <td class="px-6 py-4 font-medium text-green-600 dark:text-green-400">
                                {{ $item->qty }}
                                {{ $item->unit->short_name ?? '' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $remainingQty }}
                                {{ $orderItem->unit->short_name ?? '' }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    {{-- Attachments --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">

        <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
            Attachments
        </h2>

        @if($goodsReceipt->attachments->count())

            <div class="space-y-3">

                @foreach($goodsReceipt->attachments as $attachment)

                    <div
                        id="attachment-{{ $attachment->id }}"
                        class="flex items-center justify-between rounded-lg border border-gray-200 p-4 dark:border-gray-700"
                    >

                        <a
                            href="{{ Storage::url($attachment->file_path) }}"
                            target="_blank"
                            class="flex items-center gap-3 text-sm font-medium text-blue-600 hover:underline dark:text-blue-400"
                        >
                            <i class="bx bx-file text-xl"></i>

                            {{ basename($attachment->file_path) }}
                        </a>

                        <button
                            type="button"
                            class="delete-attachment text-sm font-medium text-red-600 hover:underline"
                            data-url="{{ route('goods-receipt-attachments.destroy', $attachment) }}"
                            data-id="{{ $attachment->id }}"
                        >
                            Delete
                        </button>

                    </div>

                @endforeach

            </div>

        @else

            <p class="text-sm text-gray-500 dark:text-gray-400">
                No attachments have been uploaded.
            </p>

        @endif

    </div>


    @push('scripts')
        <script>
            $(document).ready(function () {

                $('#delete-grn').on('click', function () {

                    if (!confirm('Are you sure you want to delete this goods receipt?')) {
                        return;
                    }

                    const $button = $(this);

                    $button
                        .prop('disabled', true)
                        .text('Deleting...');

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

                        success: function (response) {
                            showToast('success', response.message);

                            setTimeout(function () {
                                window.location.href = response.redirect;
                            }, 500);
                        },

                        error: function (xhr) {
                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to delete goods receipt.'
                            );

                            $button
                                .prop('disabled', false)
                                .text('Delete');
                        }
                    });

                });


                $('.delete-attachment').on('click', function () {

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

                        success: function (response) {
                            showToast('success', response.message);

                            $('#attachment-' + id).fadeOut(200, function () {
                                $(this).remove();
                            });
                        },

                        error: function (xhr) {
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

