<x-layouts.app title="Purchase Order">

    @php
        $statusClass = match ($purchaseOrder->status) {
            'received' => 'bg-green-50 text-green-700 border-green-200',
            'partially_received' => 'bg-amber-50 text-amber-700 border-amber-200',
            'cancelled' => 'bg-red-50 text-red-700 border-red-200',
            default => 'bg-gray-50 text-gray-700 border-gray-200',
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


    {{-- Breadcrumb --}}
    <nav class="mb-5 flex items-center gap-2 text-xs text-gray-500">

        <a href="{{ route('admin.dashboard') }}" class="transition hover:text-gray-900">
            Dashboard
        </a>

        <i class="bx bx-chevron-right text-sm text-gray-400"></i>

        <a href="{{ route('purchase-orders.index') }}" class="transition hover:text-gray-900">
            Purchase Orders
        </a>

        <i class="bx bx-chevron-right text-sm text-gray-400"></i>

        <span class="font-medium text-gray-700">
            {{ $purchaseOrder->order_number }}
        </span>

    </nav>


    {{-- ====================================================== --}}
    {{-- HEADER --}}
    {{-- ====================================================== --}}

    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="flex flex-wrap items-center gap-3">

                <h1 class="text-xl font-semibold text-gray-900">
                    Purchase Order #{{ $purchaseOrder->order_number }}
                </h1>

                <span
                    class="{{ $statusClass }}
                        inline-flex items-center gap-1.5 rounded-full
                        border px-2.5 py-1 text-xs font-medium">

                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}">
                    </span>

                    {{ $statusLabel }}

                </span>

            </div>

            <p class="mt-1 text-sm text-gray-500">
                Review order details, materials and receiving records.
            </p>

        </div>


        {{-- Header Actions --}}
        <div class="flex flex-wrap items-center gap-2">

            @if (in_array($purchaseOrder->status, ['placed', 'partially_received']))
                <a href="{{ route('goods-receipts.create', $purchaseOrder) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">

                    <i class="bx bx-package text-lg"></i>

                    Receive Materials

                </a>
            @endif


            @if ($purchaseOrder->vendorBill)
                <a href="{{ route('vendor-bills.show', $purchaseOrder->vendorBill) }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">

                    <i class="bx bx-receipt text-lg"></i>

                    View Vendor Bill

                </a>
            @elseif($purchaseOrder->status === 'received')
                <button type="button" id="generateBill" data-url="{{ route('vendor-bills.generate', $purchaseOrder) }}"
                    class="inline-flex cursor-pointer items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-60">

                    <i class="bx bx-receipt text-lg"></i>

                    Generate Bill

                </button>
            @endif

        </div>

    </div>


    {{-- ====================================================== --}}
    {{-- FLASH MESSAGES --}}
    {{-- ====================================================== --}}

    @if (session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">

            {{ session('success') }}

        </div>
    @endif


    @if (session('error'))
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">

            {{ session('error') }}

        </div>
    @endif


    @if ($errors->any())

        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4">

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


    {{-- ====================================================== --}}
    {{-- MAIN GRID --}}
    {{-- ====================================================== --}}

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">


        {{-- ================================================== --}}
        {{-- LEFT --}}
        {{-- ================================================== --}}

        <main class="space-y-6 xl:col-span-9">


            {{-- ================================================== --}}
            {{-- ORDER INFORMATION --}}
            {{-- ================================================== --}}

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white">

                <div class="border-b border-gray-200 px-5 py-4">

                    <h2 class="text-sm font-semibold text-gray-900">
                        Order Information
                    </h2>

                    <p class="mt-0.5 text-xs text-gray-500">
                        Purchase order and vendor information.
                    </p>

                </div>


                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">


                    {{-- Order Number --}}
                    <div class="border-b border-gray-100 p-5 sm:border-r">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">

                                <i class="bx bx-receipt text-xl"></i>

                            </div>

                            <div>

                                <p class="text-xs font-medium text-gray-500">
                                    Order Number
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ $purchaseOrder->order_number }}
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Vendor --}}
                    <div class="border-b border-gray-100 p-5 lg:border-r">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">

                                <i class="bx bx-store text-xl"></i>

                            </div>

                            <div class="min-w-0">

                                <p class="text-xs font-medium text-gray-500">
                                    Vendor
                                </p>

                                <p class="mt-1 truncate text-sm font-semibold text-gray-900">

                                    {{ $purchaseOrder->vendor->company_name ?: $purchaseOrder->vendor->name }}

                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Purchase Request --}}
                    <div class="border-b border-gray-100 p-5">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">

                                <i class="bx bx-file text-xl"></i>

                            </div>

                            <div>

                                <p class="text-xs font-medium text-gray-500">
                                    Purchase Request
                                </p>

                                <a href="{{ route('purchase-requests.show', $purchaseOrder->purchaseRequest) }}"
                                    class="mt-1 inline-flex items-center gap-1 text-sm font-semibold text-gray-900 hover:underline">

                                    {{ $purchaseOrder->purchaseRequest->request_number }}

                                     <i class="bx bx-link text-xs text-slate-400"></i>


                                </a>

                            </div>

                        </div>

                    </div>


                    {{-- Order Date --}}
                    <div class="border-b border-gray-100 p-5 sm:border-r lg:border-b-0">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">

                                <i class="bx bx-calendar text-xl"></i>

                            </div>

                            <div>

                                <p class="text-xs font-medium text-gray-500">
                                    Order Date
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-900">

                                    {{ $purchaseOrder->order_date->format('d M Y') }}

                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="border-b border-gray-100 p-5 lg:border-b-0 lg:border-r">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">

                                <i class="bx bx-check-circle text-xl"></i>

                            </div>

                            <div>

                                <p class="text-xs font-medium text-gray-500">
                                    Status
                                </p>

                                <span
                                    class="{{ $statusClass }}
                                        mt-1 inline-flex items-center gap-1.5
                                        rounded-full border px-2.5 py-1
                                        text-xs font-medium">

                                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}">
                                    </span>

                                    {{ $statusLabel }}

                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- Received Date --}}
                    <div class="p-5">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">

                                <i class="bx bx-package text-xl"></i>

                            </div>

                            <div>

                                <p class="text-xs font-medium text-gray-500">
                                    Received Date
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-900">

                                    {{ $purchaseOrder->received_date?->format('d M Y') ?? '—' }}

                                </p>

                            </div>

                        </div>

                    </div>

                </div>


                @if ($purchaseOrder->notes)
                    <div class="border-t border-gray-200 px-5 py-4">

                        <div class="flex items-start gap-3">

                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">

                                <i class="bx bx-note text-lg"></i>

                            </div>

                            <div>

                                <p class="text-xs font-medium text-gray-500">
                                    Notes
                                </p>

                                <p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-700">
                                    {{ $purchaseOrder->notes }}
                                </p>

                            </div>

                        </div>

                    </div>
                @endif

            </section>


            {{-- ================================================== --}}
            {{-- ORDER ITEMS --}}
            {{-- ================================================== --}}

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white">

                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">

                    <div>

                        <h2 class="text-sm font-semibold text-gray-900">
                            Order Items
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Materials included in this purchase order.
                        </p>

                    </div>

                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">

                        {{ $purchaseOrder->items->count() }}

                        {{ Str::plural('item', $purchaseOrder->items->count()) }}

                    </span>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full min-w-[800px] text-left text-sm">

                        <thead
                            class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wide text-gray-500">

                            <tr>

                                <th class="px-5 py-3.5 font-medium">
                                    Raw Material
                                </th>

                                <th class="px-5 py-3.5 text-right font-medium">
                                    Ordered
                                </th>

                                <th class="px-5 py-3.5 text-right font-medium">
                                    Received
                                </th>

                                <th class="px-5 py-3.5 text-right font-medium">
                                    Remaining
                                </th>

                                <th class="px-5 py-3.5 font-medium">
                                    Unit
                                </th>

                                <th class="px-5 py-3.5 text-right font-medium">
                                    Unit Cost
                                </th>

                                <th class="px-5 py-3.5 text-right font-medium">
                                    Total
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse($purchaseOrder->items as $item)
                                @php
                                    $receivedQty = $item->goodsReceiptItems->sum('qty');

                                    $remainingQty = max($item->qty - $receivedQty, 0);
                                @endphp


                                <tr class="transition hover:bg-gray-50">

                                    <td class="px-5 py-4">

                                        <div class="flex items-center gap-3">

                                            <div
                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">

                                                <i class="bx bx-package text-lg"></i>

                                            </div>

                                            <div>

                                                <p class="font-medium text-gray-900">
                                                    {{ $item->rawMaterial->name }}
                                                </p>

                                                @if ($item->rawMaterial->sku)
                                                    <p class="mt-0.5 text-xs text-gray-400">
                                                        {{ $item->rawMaterial->sku }}
                                                    </p>
                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    <td class="px-5 py-4 text-right font-medium text-gray-900">
                                        {{ $item->qty }}
                                    </td>


                                    <td class="px-5 py-4 text-right">

                                        <span
                                            class="{{ $receivedQty >= $item->qty ? 'text-green-700' : 'text-gray-900' }}
                                                font-semibold">

                                            {{ $receivedQty }}

                                        </span>

                                    </td>


                                    <td class="px-5 py-4 text-right">

                                        @if ($remainingQty > 0)
                                            <span
                                                class="rounded-md bg-amber-50 px-2 py-1 text-xs font-semibold text-amber-700">

                                                {{ $remainingQty }}

                                            </span>
                                        @else
                                            <span
                                                class="rounded-md bg-green-50 px-2 py-1 text-xs font-semibold text-green-700">

                                                Complete

                                            </span>
                                        @endif

                                    </td>


                                    <td class="px-5 py-4 text-gray-600">

                                        {{ $item->unit->short_name ?? $item->unit->name }}

                                    </td>


                                    <td class="px-5 py-4 text-right text-gray-700">

                                        {{ number_format($item->unit_cost, 2) }}

                                    </td>


                                    <td class="px-5 py-4 text-right font-semibold text-gray-900">

                                        {{ number_format($item->total, 2) }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="px-5 py-12 text-center">

                                        <div
                                            class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-400">

                                            <i class="bx bx-package text-xl"></i>

                                        </div>

                                        <p class="mt-3 text-sm font-medium text-gray-700">
                                            No order items found.
                                        </p>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Amount Summary --}}
                <div class="grid grid-cols-1 border-t border-gray-200 bg-gray-50 sm:grid-cols-3">

                    <div class="px-5 py-4">

                        <p class="text-xs font-medium text-gray-500">
                            Items
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $purchaseOrder->items->count() }}
                        </p>

                    </div>


                    <div class="border-t border-gray-200 px-5 py-4 sm:border-l sm:border-t-0">

                        <p class="text-xs font-medium text-gray-500">
                            Total Quantity
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $purchaseOrder->items->sum('qty') }}
                        </p>

                    </div>


                    <div class="border-t border-gray-200 px-5 py-4 sm:border-l sm:border-t-0 sm:text-right">

                        <p class="text-xs font-medium text-gray-500">
                            Grand Total
                        </p>

                        <p class="mt-1 text-lg font-semibold text-gray-900">

                            {{ number_format($purchaseOrder->total, 2) }}

                        </p>

                    </div>

                </div>

            </section>


            {{-- ================================================== --}}
            {{-- GOODS RECEIPTS --}}
            {{-- ================================================== --}}

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white">

                <div
                    class="flex flex-col gap-3 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h2 class="text-sm font-semibold text-gray-900">
                            Goods Receipts
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Receiving records for this purchase order.
                        </p>

                    </div>


                    <div class="flex items-center gap-3">

                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">

                            {{ $purchaseOrder->goodsReceipts->count() }}

                            {{ Str::plural('receipt', $purchaseOrder->goodsReceipts->count()) }}

                        </span>


                        @if (in_array($purchaseOrder->status, ['placed', 'partially_received']))
                            <a href="{{ route('goods-receipts.create', $purchaseOrder) }}"
                                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">

                                <i class="bx bx-plus"></i>

                                New Receipt

                            </a>
                        @endif

                    </div>

                </div>


                @if ($purchaseOrder->goodsReceipts->count())

                    <div class="overflow-x-auto">

                        <table class="w-full min-w-[750px] text-left text-sm">

                            <thead
                                class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wide text-gray-500">

                                <tr>

                                    <th class="px-5 py-3.5 font-medium">
                                        GRN Number
                                    </th>

                                    <th class="px-5 py-3.5 font-medium">
                                        Received Date
                                    </th>

                                    <th class="px-5 py-3.5 font-medium">
                                        Items
                                    </th>

                                    <th class="px-5 py-3.5 font-medium">
                                        Received Qty
                                    </th>

                                    <th class="px-5 py-3.5 font-medium">
                                        Notes
                                    </th>

                                    <th class="px-5 py-3.5 text-right font-medium">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100">

                                @foreach ($purchaseOrder->goodsReceipts as $receipt)
                                    <tr class="transition hover:bg-gray-50">

                                        <td class="px-5 py-4">

                                            <div class="flex items-center gap-2">

                                                <div
                                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500">

                                                    <i class="bx bx-package"></i>

                                                </div>

                                                <span class="font-semibold text-gray-900">

                                                    {{ $receipt->grn_number }}

                                                </span>

                                            </div>

                                        </td>


                                        <td class="px-5 py-4 text-gray-600">

                                            {{ $receipt->received_date->format('d M Y') }}

                                        </td>


                                        <td class="px-5 py-4 text-gray-600">

                                            {{ $receipt->items->count() }}

                                        </td>


                                        <td class="px-5 py-4 font-semibold text-gray-900">

                                            {{ $receipt->items->sum('qty') }}

                                        </td>


                                        <td class="max-w-xs px-5 py-4 text-gray-600">

                                            @if ($receipt->notes)
                                                <span class="block truncate" title="{{ $receipt->notes }}">

                                                    {{ $receipt->notes }}

                                                </span>
                                            @else
                                                <span class="text-gray-400">
                                                    —
                                                </span>
                                            @endif

                                        </td>


                                        <td class="px-5 py-4 text-right">

                                            <a href="{{ route('goods-receipts.show', $receipt) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 transition hover:bg-gray-50">

                                                View

                                                <i class="bx bx-right-arrow-alt"></i>

                                            </a>

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>
                @else
                    <div class="flex flex-col items-center justify-center px-6 py-12 text-center">

                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-400">

                            <i class="bx bx-package text-xl"></i>

                        </div>

                        <h3 class="mt-3 text-sm font-semibold text-gray-900">
                            No goods receipts yet
                        </h3>

                        <p class="mt-1 text-xs text-gray-500">
                            Create a goods receipt when materials arrive.
                        </p>

                    </div>

                @endif

            </section>

        </main>


        {{-- ================================================== --}}
        {{-- RIGHT - PURCHASE REQUEST ACTIVITY --}}
        {{-- ================================================== --}}

        <aside class="xl:col-span-3">

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white xl:sticky xl:top-20">

                {{-- Header --}}
                <div class="border-b border-gray-200 px-4 py-3">

                    <div class="flex items-center justify-between gap-2">

                        <div class="flex items-center gap-2">

                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">

                                <i class="bx bx-history"></i>

                            </div>

                            <div>

                                <h3 class="text-sm font-semibold text-gray-900">
                                    Activity
                                </h3>

                                <p class="text-[11px] text-gray-500">
                                    Purchase request history
                                </p>

                            </div>

                        </div>


                        <span id="activityCount"
                            class="rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-500">

                            {{ $purchaseOrder->purchaseRequest->activities->count() }}

                        </span>

                    </div>

                </div>


                {{-- Timeline --}}
                <div id="activityTimeline" class="max-h-[700px] overflow-y-auto px-4 py-4">

                    @forelse($purchaseOrder->purchaseRequest->activities as $activity)
                        @php
                            $activityIcon = match ($activity->action) {
                                'created' => 'bx-plus',
                                'updated' => 'bx-edit',
                                'submitted' => 'bx-send',
                                'approved' => 'bx-check',
                                'rejected' => 'bx-x',
                                'rfq_sent' => 'bx-envelope',
                                'quotation_received' => 'bx-file',
                                'quotation_selected' => 'bx-check-square',
                                'purchase_order_created' => 'bx-cart',
                                'completed' => 'bx-check-double',
                                default => 'bx-history',
                            };

                            $activityClass = match ($activity->action) {
                                'approved', 'completed' => 'border-green-200 bg-green-50 text-green-600',

                                'rejected' => 'border-red-200 bg-red-50 text-red-600',

                                'submitted', 'rfq_sent' => 'border-blue-200 bg-blue-50 text-blue-600',

                                'quotation_received',
                                'quotation_selected'
                                    => 'border-violet-200 bg-violet-50 text-violet-600',

                                'purchase_order_created' => 'border-amber-200 bg-amber-50 text-amber-600',

                                default => 'border-gray-200 bg-white text-gray-500',
                            };
                        @endphp


                        <div class="relative flex gap-3 pb-5 last:pb-0">

                            @if (!$loop->last)
                                <div class="absolute left-[13px] top-7 h-[calc(100%-0.5rem)] w-px bg-gray-200">
                                </div>
                            @endif


                            {{-- Icon --}}
                            <div
                                class="{{ $activityClass }}
                                    relative z-10 flex h-7 w-7 shrink-0
                                    items-center justify-center rounded-full border">

                                <i class="bx {{ $activityIcon }} text-xs"></i>

                            </div>


                            <div class="min-w-0 flex-1">

                                <p class="text-xs font-semibold leading-5 text-gray-900">

                                    {{ ucwords(str_replace('_', ' ', $activity->action ?? 'activity')) }}

                                </p>


                                @if ($activity->description)
                                    <p class="mt-0.5 text-xs leading-4 text-gray-500">

                                        {{ $activity->description }}

                                    </p>
                                @endif


                                @if ($activity->vendor)
                                    <div class="mt-1 flex items-center gap-1 text-[11px] text-gray-500">

                                        <i class="bx bx-store"></i>

                                        <span class="truncate">

                                            {{ $activity->vendor->company_name ?: $activity->vendor->name }}

                                        </span>

                                    </div>
                                @endif


                                <div class="mt-1 flex flex-wrap items-center gap-x-2 text-[10px] text-gray-400">

                                    @if ($activity->user)
                                        <span class="flex items-center gap-1">

                                            <i class="bx bx-user"></i>

                                            {{ $activity->user->name }}

                                        </span>
                                    @endif


                                    <span>
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="py-8 text-center">

                            <div
                                class="mx-auto flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-gray-400">

                                <i class="bx bx-history text-lg"></i>

                            </div>

                            <p class="mt-2 text-xs font-medium text-gray-700">
                                No activity yet
                            </p>

                        </div>
                    @endforelse

                </div>

            </div>

        </aside>

    </div>


    {{-- ====================================================== --}}
    {{-- SCRIPTS --}}
    {{-- ====================================================== --}}

    @push('scripts')
        <script>
            $(document).ready(function() {


                /*
                 * Generate Vendor Bill
                 * Existing logic unchanged
                 */
                $('#generateBill').on('click', function() {

                    const button = $(this);

                    const url = button.data('url');


                    if (!confirm(
                            'Do you want to generate the vendor bill?'
                        )) {

                        return;

                    }


                    button
                        .prop('disabled', true)
                        .html(
                            '<i class="bx bx-loader-alt bx-spin"></i> Generating...'
                        );


                    $.ajax({

                        url: url,

                        type: 'POST',

                        data: {
                            _token: '{{ csrf_token() }}'
                        },

                        headers: {
                            'Accept': 'application/json'
                        },


                        success: function(response) {

                            showToast(
                                'success',
                                response.message
                            );


                            setTimeout(function() {

                                window.location.reload();

                            }, 800);

                        },


                        error: function(xhr) {

                            button
                                .prop('disabled', false)
                                .html(
                                    '<i class="bx bx-receipt"></i> Generate Bill'
                                );


                            showToast(
                                'error',
                                xhr.responseJSON?.message ??
                                'Unable to generate vendor bill.'
                            );

                        }

                    });

                });

            });
        </script>
    @endpush

</x-layouts.app>
