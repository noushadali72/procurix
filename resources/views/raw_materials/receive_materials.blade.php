
<x-layouts.app title="Receive Materials">

    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    Receive Materials
                </h1>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Receive materials against placed and partially received purchase orders.
                </p>
            </div>
        </div>

        {{-- Purchase Orders --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-700">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                    Purchase Orders
                </h2>
            </div>

            @if($purchaseOrders->count())

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-5 py-3 font-medium text-gray-600 dark:text-gray-300">
                                    Order Number
                                </th>

                                <th class="px-5 py-3 font-medium text-gray-600 dark:text-gray-300">
                                    Vendor
                                </th>

                                <th class="px-5 py-3 font-medium text-gray-600 dark:text-gray-300">
                                    Order Date
                                </th>

                                <th class="px-5 py-3 font-medium text-gray-600 dark:text-gray-300">
                                    Status
                                </th>

                                <th class="px-5 py-3 text-right font-medium text-gray-600 dark:text-gray-300">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                            @foreach($purchaseOrders as $purchaseOrder)
                                <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">

                                    {{-- Order Number --}}
                                    <td class="px-5 py-4">
                                        <a href="{{ route('purchase-orders.show',$purchaseOrder) }}" class="font-medium text-blue-900 dark:text-white underline">
                                            {{ $purchaseOrder->order_number }}
                                        </a>
                                    </td>

                                    {{-- Vendor --}}
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                                        {{ $purchaseOrder->vendor->name }}
                                    </td>

                                    {{-- Order Date --}}
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">
                                        {{ $purchaseOrder->order_date?->format('d M Y') }}
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-5 py-4">
                                        @if($purchaseOrder->status === 'partially_received')
                                            <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                                Partially Received
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                Placed
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Action --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">

                                            <a
                                                href="{{ route('goods-receipts.create', $purchaseOrder) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white dark:border-gray-600 dark:text-gray-300 dark:hover:border-gray-500 dark:hover:bg-gray-700 dark:hover:text-white"
                                            >
                                                <i class="bx bx-package text-base"></i>
                                                Receive Materials
                                            </a>

                                        </div>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($purchaseOrders->hasPages())
                    <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700">
                        {{ $purchaseOrders->links() }}
                    </div>
                @endif

            @else

                {{-- Empty State --}}
                <div class="px-5 py-12 text-center">

                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                        <i class="bx bx-package text-2xl text-gray-400 dark:text-gray-500"></i>
                    </div>

                    <h3 class="mt-4 text-sm font-semibold text-gray-900 dark:text-white">
                        No purchase orders available
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        There are currently no purchase orders available for material receiving.
                    </p>

                </div>

            @endif

        </div>

    </div>

</x-layouts.app>