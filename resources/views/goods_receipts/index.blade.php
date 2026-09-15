<x-layouts.app title="Goods Receipts">

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
            Goods Receipts
        </h1>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            View materials received against purchase orders.
        </p>
    </div>


    @if(session('success'))
        <div class="mb-4">
            <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4">
            <div class="rounded-lg bg-red-50 p-4 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-400">
                {{ session('error') }}
            </div>
        </div>
    @endif


    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">

                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-3">GRN Number</th>
                        <th class="px-6 py-3">Purchase Order</th>
                        <th class="px-6 py-3">Vendor</th>
                        <th class="px-6 py-3">Received Date</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                    @forelse($goodsReceipts as $goodsReceipt)

                        <tr>

                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $goodsReceipt->grn_number }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $goodsReceipt->purchaseOrder->order_number ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $goodsReceipt->purchaseOrder->vendor->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $goodsReceipt->received_date->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 text-right">

                                <a
                                    href="{{ route('goods-receipts.show', $goodsReceipt) }}"
                                    class="font-medium text-blue-600 hover:underline dark:text-blue-400"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="5"
                                class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400"
                            >
                                No goods receipts found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($goodsReceipts->hasPages())
            <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                {{ $goodsReceipts->links() }}
            </div>
        @endif

    </div>

</x-layouts.app>

