<x-layouts.app title="Purchase Order">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <a href="{{ route('purchase-orders.index') }}"
                class="inline-flex items-center text-sm font-medium text-gray-500 transition hover:text-gray-900">
                <i class="bx bx-arrow-back mr-1.5"></i>
                Back to Orders
            </a>

            <div class="mt-3">
                <div class="flex flex-wrap items-center gap-2">

                    <h2 class="text-xl font-semibold text-gray-900">
                        Purchase Order #{{ $purchaseOrder->order_number }}
                    </h2>

                    @php
                        $statusClass = match ($purchaseOrder->status) {
                            'received' => 'bg-green-50 text-green-700',
                            'partially_received' => 'bg-amber-50 text-amber-700',
                            'cancelled' => 'bg-red-50 text-red-700',
                            default => 'bg-gray-100 text-gray-700',
                        };

                        $statusDot = match ($purchaseOrder->status) {
                            'received' => 'bg-green-500',
                            'partially_received' => 'bg-amber-500',
                            'cancelled' => 'bg-red-500',
                            default => 'bg-gray-400',
                        };

                        $statusLabel = match ($purchaseOrder->status) {
                            'partially_received' => 'Partially Received',
                            default => ucfirst(str_replace('_', ' ', $purchaseOrder->status)),
                        };
                    @endphp

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                        {{ $statusLabel }}
                    </span>

                </div>

                <p class="mt-1 text-sm text-gray-500">
                    Review purchase order details, materials, and receiving records.
                </p>
            </div>
        </div>


        {{-- Header Action --}}
        @if (in_array($purchaseOrder->status, ['placed', 'partially_received']))
            <a href="{{ route('goods-receipts.create', $purchaseOrder) }}"
                class="rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">
                Receive Materials
            </a>
        @endif

    </div>


    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

            <p class="mb-2 text-sm font-medium text-red-700">
                Please review the following errors:
            </p>

            <ul class="list-inside list-disc space-y-1 text-sm text-red-700">

                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>
    @endif


    {{-- Order Information --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-4">

            <h3 class="font-semibold text-gray-900">
                Order Information
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Basic purchase order and vendor information.
            </p>

        </div>

        <div class="grid grid-cols-1 gap-x-8 gap-y-6 p-6 sm:grid-cols-2 lg:grid-cols-3">

            {{-- Order Number --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Order Number
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $purchaseOrder->order_number }}
                </p>
            </div>


            {{-- Vendor --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Vendor
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $purchaseOrder->vendor->company_name ?: $purchaseOrder->vendor->name }}
                </p>
            </div>


            {{-- Purchase Request --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Purchase Request
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    PR-{{ $purchaseOrder->quotation->purchaseRequest->request_number }}
                </p>
            </div>


            {{-- Order Date --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Order Date
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $purchaseOrder->order_date->format('d M Y') }}
                </p>
            </div>


            {{-- Status --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Status
                </p>

                <div class="mt-1.5">

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                        {{ $statusLabel }}
                    </span>

                </div>
            </div>


            {{-- Received Date --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Received Date
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $purchaseOrder->received_date?->format('d M Y') ?? '-' }}
                </p>
            </div>


            {{-- Notes --}}
            @if ($purchaseOrder->notes)
                <div class="border-t border-gray-100 pt-5 sm:col-span-2 lg:col-span-3">

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Notes
                    </p>

                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-700">
                        {{ $purchaseOrder->notes }}
                    </p>

                </div>
            @endif

        </div>

    </div>


    {{-- Order Items --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">

            <div>
                <h3 class="font-semibold text-gray-900">
                    Order Items
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Materials included in this purchase order.
                </p>
            </div>

            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                {{ $purchaseOrder->items->count() }} Items
            </span>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full min-w-[800px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">

                    <tr class="text-xs font-medium uppercase tracking-wide text-gray-500">

                        <th class="px-6 py-3.5">
                            Raw Material
                        </th>

                        <th class="px-6 py-3.5">
                            Ordered
                        </th>

                        <th class="px-6 py-3.5">
                            Received
                        </th>

                        <th class="px-6 py-3.5">
                            Unit
                        </th>

                        <th class="px-6 py-3.5">
                            Unit Price
                        </th>

                        <th class="px-6 py-3.5 text-right">
                            Total
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($purchaseOrder->items as $item)
                        <tr class="transition hover:bg-gray-50">

                            <td class="px-6 py-4">

                                <p class="font-medium text-gray-900">
                                    {{ $item->rawMaterial->name }}
                                </p>

                                @if ($item->rawMaterial->sku)
                                    <p class="mt-0.5 text-xs text-gray-500">
                                        {{ $item->rawMaterial->sku }}
                                    </p>
                                @endif

                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $item->qty }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $item->goodsReceiptItems->sum('qty')  }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $item->unit->short_name ?? $item->unit->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-700">
                                {{ number_format($item->price, 2) }}
                            </td>

                            <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                {{ number_format($item->total, 2) }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                                No order items found.
                            </td>
                        </tr>
                    @endforelse

                </tbody>


                <tfoot class="border-t border-gray-200 bg-gray-50">

                    <tr>

                        <td colspan="4" class="px-6 py-4 text-right text-sm font-semibold text-gray-700">
                            Grand Total
                        </td>

                        <td class="px-6 py-4 text-right">

                            <span class="text-lg font-bold text-gray-900">
                                {{ number_format($purchaseOrder->items->sum('total'), 2) }}
                            </span>

                        </td>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>


    {{-- Goods Receipts --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm mb-8">

        <div
            class="flex flex-col gap-3 border-b border-gray-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h3 class="font-semibold text-gray-900">
                    Goods Receipts
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Receiving records for this purchase order.
                </p>

            </div>


            <div class="flex items-center gap-2">

                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                    {{ $purchaseOrder->goodsReceipts->count() }} Receipts
                </span>

                @if (in_array($purchaseOrder->status, ['placed', 'partially_received']))
                    <a href="{{ route('goods-receipts.create', $purchaseOrder) }}"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                        New Receipt
                    </a>
                @endif

            </div>

        </div>


        <div class="overflow-x-auto">

            @if ($purchaseOrder->goodsReceipts->count())

                <table class="w-full min-w-[750px] text-left text-sm">

                    <thead class="border-b border-gray-200 bg-gray-50">

                        <tr class="text-xs font-medium uppercase tracking-wide text-gray-500">

                            <th class="px-6 py-3.5">
                                GRN Number
                            </th>

                            <th class="px-6 py-3.5">
                                Received Date
                            </th>

                            <th class="px-6 py-3.5">
                                Items
                            </th>

                             <th class="px-6 py-3.5">
                                Received Qty
                            </th>

                            <th class="px-6 py-3.5">
                                Notes
                            </th>

                            <th class="px-6 py-3.5 text-right">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @foreach ($purchaseOrder->goodsReceipts as $receipt)
                            <tr class="transition hover:bg-gray-50">

                                <td class="px-6 py-4">

                                    <p class="font-semibold text-gray-900">
                                        {{ $receipt->grn_number }}
                                    </p>

                                </td>


                                <td class="px-6 py-4 text-gray-600">
                                    {{ $receipt->received_date->format('d M Y') }}
                                </td>


                                <td class="px-6 py-4">

                                    <span
                                        class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                        {{ $receipt->items->count() }} Items
                                    </span>

                                </td>

                                 <td class="px-6 py-4">

                                    <span
                                        class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                        {{ $receipt->items->sum('qty') }}
                                    </span>

                                </td>

                                <td class="max-w-xs px-6 py-4 text-gray-600">

                                    @if ($receipt->notes)
                                        <span class="block truncate" title="{{ $receipt->notes }}">
                                            {{ $receipt->notes }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">
                                            -
                                        </span>
                                    @endif

                                </td>


                                <td class="px-6 py-4 text-right">

                                    <a href="{{ route('goods-receipts.show', $receipt) }}"
                                        class="rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-gray-50 transition hover:bg-gray-800">
                                        View
                                    </a>

                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>
            @else
                <div class="flex flex-col items-center justify-center px-6 py-12 text-center">

                    <div
                        class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                        <i class="bx bx-package text-2xl"></i>
                    </div>

                    <h3 class="font-medium text-gray-900">
                        No goods receipts yet
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Create a goods receipt when materials arrive.
                    </p>

                </div>

            @endif

        </div>

    </div>

</x-layouts.app>
