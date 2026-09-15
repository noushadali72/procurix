<x-layouts.app title="Goods Receipts">

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            Goods Receipts
        </h1>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            View materials received against purchase orders.
        </p>
    </div>
</div>


@if(session('success'))
    <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
        <div class="flex items-center gap-2">
            <i class="bx bx-check-circle text-base"></i>
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
        <div class="flex items-center gap-2">
            <i class="bx bx-error-circle text-base"></i>
            {{ session('error') }}
        </div>
    </div>
@endif


<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">

    {{-- Card Header --}}
    <div class="flex flex-col gap-2 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700">
        <div>
            <h2 class="flex items-center gap-2 text-base font-semibold text-gray-900 dark:text-white">
                <i class="bx bx-package text-lg text-gray-500 dark:text-gray-400"></i>
                Goods Receipt List
            </h2>

            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                {{ $goodsReceipts->total() }} {{ Str::plural('receipt', $goodsReceipts->total()) }} found
            </p>
        </div>
    </div>


    {{-- Table --}}
    <div class="overflow-x-auto">

        <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">

            <thead class="border-b border-gray-200 bg-gray-50 text-xs font-medium uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-700/50 dark:text-gray-400">
                <tr>
                    <th class="px-5 py-3.5">GRN Number</th>
                    <th class="px-5 py-3.5">Purchase Order</th>
                    <th class="px-5 py-3.5">Vendor</th>
                    <th class="px-5 py-3.5">Items</th>
                    <th class="px-5 py-3.5">Received Date</th>
                    <th class="px-5 py-3.5 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                @forelse($goodsReceipts as $goodsReceipt)

                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">

                        {{-- GRN --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                    <i class="bx bx-receipt text-lg"></i>
                                </div>

                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $goodsReceipt->grn_number }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                        Goods Receipt
                                    </p>
                                </div>

                            </div>
                        </td>


                        {{-- Purchase Order --}}
                        <td class="px-5 py-4">
                            @if($goodsReceipt->purchaseOrder)
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $goodsReceipt->purchaseOrder->order_number }}
                                    </p>

                                    @if($goodsReceipt->purchaseOrder->quotation)
                                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                            Quotation:
                                            {{ $goodsReceipt->purchaseOrder->quotation->quotation_number }}
                                        </p>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>


                        {{-- Vendor --}}
                        <td class="px-5 py-4">
                            @if($goodsReceipt->purchaseOrder?->vendor)
                                <div class="flex items-center gap-2">

                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-300">
                                        <i class="bx bx-store"></i>
                                    </div>

                                    <span class="font-medium text-gray-700 dark:text-gray-200">
                                        {{ $goodsReceipt->purchaseOrder->vendor->name }}
                                    </span>

                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>


                        {{-- Items --}}
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                <i class="bx bx-package"></i>
                                {{ $goodsReceipt->items->count() }}
                                {{ Str::plural('item', $goodsReceipt->items->count()) }}
                            </span>
                        </td>


                        {{-- Received Date --}}
                        <td class="px-5 py-4">
                            @if($goodsReceipt->received_date)
                                <div>
                                    <p class="font-medium text-gray-700 dark:text-gray-200">
                                        {{ $goodsReceipt->received_date->format('d M Y') }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                        Received
                                    </p>
                                </div>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">
                                    Not received
                                </span>
                            @endif
                        </td>


                        {{-- Actions --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-end gap-2">

                                <a
                                    href="{{ route('goods-receipts.show', $goodsReceipt) }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white dark:border-gray-600 dark:text-gray-300 dark:hover:border-gray-500 dark:hover:bg-gray-700 dark:hover:text-white"
                                >
                                    <i class="bx bx-show"></i>
                                    View
                                </a>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="px-5 py-14">

                            <div class="flex flex-col items-center justify-center text-center">

                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500">
                                    <i class="bx bx-package text-2xl"></i>
                                </div>

                                <h3 class="mt-4 text-sm font-semibold text-gray-900 dark:text-white">
                                    No goods receipts found
                                </h3>

                                <p class="mt-1 max-w-sm text-sm text-gray-500 dark:text-gray-400">
                                    Goods receipts will appear here once materials are received against purchase orders.
                                </p>

                            </div>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}
    @if($goodsReceipts->hasPages())
        <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700">
            {{ $goodsReceipts->links() }}
        </div>
    @endif

</div>

</x-layouts.app>