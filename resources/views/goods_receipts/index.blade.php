<x-layouts.app title="Goods Receipts">

    <div class="max-w-7xl">

        {{-- Breadcrumb --}}
        <nav class="mb-6 flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">

            <a href="{{ route('admin.dashboard') }}" class="transition hover:text-slate-900 dark:hover:text-white">
                Dashboard
            </a>

            <i class="bx bx-chevron-right text-slate-400"></i>

            <span class="text-slate-500 dark:text-slate-400">
                Receiving
            </span>

            <i class="bx bx-chevron-right text-slate-400"></i>

            <span class="font-medium text-slate-700 dark:text-slate-200">
                Goods Receipts
            </span>

        </nav>


        {{-- Page Header --}}
        <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300">

                        <i class="bx bx-package text-xl"></i>

                    </div>

                    <div>

                        <h1 class="text-xl font-semibold tracking-tight text-slate-900 dark:text-white">
                            Goods Receipts
                        </h1>

                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            View materials received against purchase orders.
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- Success Message --}}
        @if (session('success'))
            <div
                class="mb-5 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400">

                <i class="bx bx-check-circle mt-0.5 text-base"></i>

                <span>
                    {{ session('success') }}
                </span>

            </div>
        @endif


        {{-- Error Message --}}
        @if (session('error'))
            <div
                class="mb-5 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">

                <i class="bx bx-error-circle mt-0.5 text-base"></i>

                <span>
                    {{ session('error') }}
                </span>

            </div>
        @endif


        {{-- Main Card --}}
        <section
            class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">

            {{-- Card Header --}}
            <div
                class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-700">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">

                        <i class="bx bx-receipt text-lg"></i>

                    </div>

                    <div>

                        <h2 class="text-sm font-semibold text-slate-900 dark:text-white">
                            Goods Receipt List
                        </h2>

                        <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">
                            {{ $goodsReceipts->total() }}
                            {{ Str::plural('receipt', $goodsReceipts->total()) }}
                            found
                        </p>

                    </div>

                </div>


                {{-- Record Count --}}
                <div
                    class="inline-flex w-fit items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 dark:border-slate-700 dark:bg-slate-700/50">

                    <span
                        class="flex h-5 w-5 items-center justify-center rounded-full bg-white text-[10px] font-semibold text-slate-600 shadow-sm dark:bg-slate-600 dark:text-slate-200">

                        {{ $goodsReceipts->total() }}

                    </span>

                    <span class="text-xs font-medium text-slate-600 dark:text-slate-300">
                        {{ Str::plural('receipt', $goodsReceipts->total()) }}
                    </span>

                </div>

            </div>


            {{-- Table --}}
            <div class="overflow-x-auto">

                <table class="w-full min-w-[900px] text-left text-sm">

                    {{-- Table Header --}}
                    <thead class="border-b border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-700/40">

                        <tr
                            class="text-[11px] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">

                            <th class="px-5 py-3.5">
                                GRN Number
                            </th>

                            <th class="px-5 py-3.5">
                                Purchase Order
                            </th>

                            <th class="px-5 py-3.5">
                                Vendor
                            </th>

                            <th class="px-5 py-3.5">
                                Items
                            </th>

                            <th class="px-5 py-3.5">
                                Received Date
                            </th>

                            <th class="px-5 py-3.5 text-right">
                                Action
                            </th>

                        </tr>

                    </thead>


                    {{-- Table Body --}}
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">

                        @forelse($goodsReceipts as $goodsReceipt)

                            <tr class="group transition hover:bg-slate-50/80 dark:hover:bg-slate-700/30">


                                {{-- GRN --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3">

                                        <div
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-500 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300">

                                            <i class="bx bx-receipt text-lg"></i>

                                        </div>

                                        <div class="min-w-0">

                                            <a href="{{ route('goods-receipts.show', $goodsReceipt) }}"
                                                class="font-semibold text-slate-900 transition hover:text-slate-600 hover:underline dark:text-white dark:hover:text-slate-300">

                                                {{ $goodsReceipt->grn_number }}

                                            </a>

                                            <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">
                                                Goods Receipt
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                {{-- Purchase Order --}}
                                <td class="px-5 py-4">

                                    @if ($goodsReceipt->purchaseOrder)
                                        <div class="min-w-0">

                                            <a href="{{ route('purchase-orders.show', $goodsReceipt->purchaseOrder) }}"
                                                class="inline-flex items-center gap-1.5 font-medium text-slate-800 transition hover:text-slate-950 hover:underline dark:text-slate-200 dark:hover:text-white">

                                                <span>
                                                    {{ $goodsReceipt->purchaseOrder->order_number }}
                                                </span>

                                                <i class="bx bx-link text-xs text-slate-400"></i>

                                            </a>


                                            @if ($goodsReceipt->purchaseOrder->quotation)
                                                <div class="mt-1 flex items-center gap-1.5 text-xs">

                                                    <span class="text-slate-400 dark:text-slate-500">
                                                        Quotation
                                                    </span>

                                                    <span class="text-slate-300 dark:text-slate-600">
                                                        /
                                                    </span>

                                                    <a href="{{ route('quotations.show', $goodsReceipt->purchaseOrder->quotation) }}"
                                                        class="font-medium text-slate-600 hover:text-slate-900 hover:underline dark:text-slate-400 dark:hover:text-white">

                                                        {{ $goodsReceipt->purchaseOrder->quotation->quotation_number }}

                                                    </a>

                                                </div>
                                            @endif

                                        </div>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500">
                                            -
                                        </span>
                                    @endif

                                </td>


                                {{-- Vendor --}}
                                <td class="px-5 py-4">

                                    @if ($goodsReceipt->purchaseOrder?->vendor)
                                        <div class="flex min-w-0 items-center gap-2.5">

                                            <div
                                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-300">

                                                <i class="bx bx-store"></i>

                                            </div>

                                            <span class="truncate font-medium text-slate-700 dark:text-slate-200">

                                                {{ $goodsReceipt->purchaseOrder->vendor->name }}

                                            </span>

                                        </div>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-500">
                                            -
                                        </span>
                                    @endif

                                </td>


                                {{-- Items --}}
                                <td class="px-5 py-4">

                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-600 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300">

                                        <i class="bx bx-package text-sm"></i>

                                        {{ $goodsReceipt->items->count() }}

                                        {{ Str::plural('item', $goodsReceipt->items->count()) }}

                                    </span>

                                </td>


                                {{-- Received Date --}}
                                <td class="px-5 py-4">

                                    @if ($goodsReceipt->received_date)
                                        <div class="flex items-center gap-2">

                                            <div
                                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-300">

                                                <i class="bx bx-calendar"></i>

                                            </div>

                                            <div>

                                                <p class="font-medium text-slate-700 dark:text-slate-200">
                                                    {{ $goodsReceipt->received_date->format('d M Y') }}
                                                </p>

                                                <p class="mt-0.5 text-xs text-slate-400 dark:text-slate-500">
                                                    Received
                                                </p>

                                            </div>

                                        </div>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-500 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-400">

                                            <i class="bx bx-time-five"></i>

                                            Not received

                                        </span>
                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center justify-end">

                                        <a href="{{ route('goods-receipts.show', $goodsReceipt) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-medium text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-slate-500 dark:hover:bg-slate-700">

                                            <i class="bx bx-show text-base"></i>

                                            View

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            {{-- Empty State --}}
                            <tr>

                                <td colspan="6" class="px-5 py-16">

                                    <div class="flex flex-col items-center justify-center text-center">

                                        <div
                                            class="flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400 dark:bg-slate-700 dark:text-slate-500">

                                            <i class="bx bx-package text-2xl"></i>

                                        </div>

                                        <h3 class="mt-4 text-sm font-semibold text-slate-900 dark:text-white">
                                            No goods receipts found
                                        </h3>

                                        <p class="mt-1 max-w-sm text-sm text-slate-500 dark:text-slate-400">
                                            Goods receipts will appear here once materials are received against purchase
                                            orders.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}
            @if ($goodsReceipts->hasPages())
                <div
                    class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-slate-700">

                    <p class="text-xs text-slate-500 dark:text-slate-400">

                        Showing
                        <span class="font-medium text-slate-700 dark:text-slate-300">
                            {{ $goodsReceipts->firstItem() }}
                        </span>

                        to

                        <span class="font-medium text-slate-700 dark:text-slate-300">
                            {{ $goodsReceipts->lastItem() }}
                        </span>

                        of

                        <span class="font-medium text-slate-700 dark:text-slate-300">
                            {{ $goodsReceipts->total() }}
                        </span>

                        receipts

                    </p>

                    <div>
                        {{ $goodsReceipts->links() }}
                    </div>

                </div>
            @endif

        </section>

    </div>

</x-layouts.app>
