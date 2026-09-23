<x-layouts.app title="Purchase Order">

    <div class=" max-w-7xl">

        {{-- Breadcrumb --}}
        <nav class="mb-5 flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.dashboard') }}" class="transition hover:text-slate-800">
                Dashboard
            </a>

            <i class="bx bx-chevron-right text-sm text-slate-400"></i>

            <a href="{{ route('purchase-orders.index') }}" class="transition hover:text-slate-800">
                Purchase Orders
            </a>

            <i class="bx bx-chevron-right text-sm text-slate-400"></i>

            <span class="font-medium text-slate-700">
                {{ $purchaseOrder->order_number }}
            </span>
        </nav>


        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 border-b border-slate-200 pb-5 lg:flex-row lg:items-end lg:justify-between">

            <div>
                <div class="flex flex-wrap items-center gap-3">

                    <h1 class="text-xl font-semibold tracking-tight text-slate-900">
                        Purchase Order #{{ $purchaseOrder->order_number }}
                    </h1>

                    @php
                        $statusClass = match ($purchaseOrder->status) {
                            'received' => 'bg-emerald-50 text-emerald-700',
                            'partially_received' => 'bg-amber-50 text-amber-700',
                            'cancelled' => 'bg-red-50 text-red-700',
                            default => 'bg-slate-100 text-slate-700',
                        };

                        $statusDot = match ($purchaseOrder->status) {
                            'received' => 'bg-emerald-500',
                            'partially_received' => 'bg-amber-500',
                            'cancelled' => 'bg-red-500',
                            default => 'bg-slate-400',
                        };

                        $statusLabel = match ($purchaseOrder->status) {
                            'partially_received' => 'Partially Received',
                            default => ucfirst(str_replace('_', ' ', $purchaseOrder->status)),
                        };
                    @endphp

                    <span class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1 text-[11px] font-semibold {{ $statusClass }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                        {{ $statusLabel }}
                    </span>

                </div>

                <p class="mt-1 text-sm text-slate-500">
                    Review purchase order details, materials, and receiving records.
                </p>
            </div>


            {{-- Header Actions --}}
            <div class="flex flex-wrap items-center gap-2">

                @if (in_array($purchaseOrder->status, ['placed', 'partially_received']))
                    <a href="{{ route('goods-receipts.create', $purchaseOrder) }}"
                        class="inline-flex items-center gap-2 rounded-md bg-slate-800 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900">
                        <i class="bx bx-package"></i>
                        Receive Materials
                    </a>
                @endif

                @if ($purchaseOrder->vendorBill)
                    <a href="{{ route('vendor-bills.show', $purchaseOrder->vendorBill) }}"
                        class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        <i class="bx bx-receipt"></i>
                        View Vendor Bill
                    </a>
                @elseif ($purchaseOrder->status === 'received')
                    <button type="button"
                        id="generateBill"
                        data-url="{{ route('vendor-bills.generate', $purchaseOrder) }}"
                        class="inline-flex items-center gap-2 rounded-md bg-slate-800 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900 disabled:cursor-not-allowed disabled:opacity-60">
                        <i class="bx bx-receipt"></i>
                        Generate Bill
                    </button>
                @endif

            </div>

        </div>


        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-5 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 rounded-md border border-red-200 bg-red-50 p-4">

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


        <div class="space-y-5">

            {{-- Order Information --}}
            <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Order Information
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500">
                        Basic purchase order and vendor information.
                    </p>
                </div>


                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">

                    {{-- Order Number --}}
                    <div class="border-b border-slate-100 px-5 py-4 lg:border-r">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                            Order Number
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $purchaseOrder->order_number }}
                        </p>
                    </div>


                    {{-- Vendor --}}
                    <div class="border-b border-slate-100 px-5 py-4 lg:border-r">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                            Vendor
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $purchaseOrder->vendor->company_name ?: $purchaseOrder->vendor->name }}
                        </p>
                    </div>


                    {{-- Purchase Request --}}
                    <div class="border-b border-slate-100 px-5 py-4">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                            Purchase Request
                        </p>

                        <a href="{{ route('purchase-requests.show', $purchaseOrder->purchaseRequest) }}"
                            class="mt-1 inline-flex text-sm font-semibold text-slate-800 underline decoration-slate-300 underline-offset-2 transition hover:decoration-slate-700">
                            {{ $purchaseOrder->purchaseRequest->request_number }}
                        </a>
                    </div>


                    {{-- Order Date --}}
                    <div class="border-b border-slate-100 px-5 py-4 lg:border-r">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                            Order Date
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $purchaseOrder->order_date->format('d M Y') }}
                        </p>
                    </div>


                    {{-- Status --}}
                    <div class="border-b border-slate-100 px-5 py-4 lg:border-r">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                            Status
                        </p>

                        <div class="mt-1">
                            <span class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-[11px] font-semibold {{ $statusClass }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>


                    {{-- Received Date --}}
                    <div class="border-b border-slate-100 px-5 py-4">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                            Received Date
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $purchaseOrder->received_date?->format('d M Y') ?? '—' }}
                        </p>
                    </div>


                    {{-- Notes --}}
                    @if ($purchaseOrder->notes)
                        <div class="px-5 py-4 md:col-span-2 lg:col-span-3">

                            <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                                Notes
                            </p>

                            <p class="mt-1 text-sm leading-6 text-slate-700">
                                {{ $purchaseOrder->notes }}
                            </p>

                        </div>
                    @endif

                </div>

            </section>


            {{-- Order Items --}}
            <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">

                <div class="flex flex-col gap-2 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">
                            Order Items
                        </h2>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Materials included in this purchase order.
                        </p>
                    </div>

                    <span class="text-xs font-medium text-slate-500">
                        {{ $purchaseOrder->items->count() }}
                        {{ Str::plural('item', $purchaseOrder->items->count()) }}
                    </span>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full min-w-[800px] text-left text-sm">

                        <thead class="border-b border-slate-200 bg-slate-50">

                            <tr class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">

                                <th class="px-5 py-3">
                                    Raw Material
                                </th>

                                <th class="px-5 py-3 text-right">
                                    Ordered
                                </th>

                                <th class="px-5 py-3 text-right">
                                    Received
                                </th>

                                <th class="px-5 py-3">
                                    Unit
                                </th>

                                <th class="px-5 py-3 text-right">
                                    Unit Cost
                                </th>

                                <th class="px-5 py-3 text-right">
                                    Total
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @forelse ($purchaseOrder->items as $item)

                                <tr class="transition hover:bg-slate-50">

                                    <td class="px-5 py-3.5">

                                        <p class="font-medium text-slate-900">
                                            {{ $item->rawMaterial->name }}
                                        </p>

                                        @if ($item->rawMaterial->sku)
                                            <p class="mt-0.5 text-xs text-slate-400">
                                                {{ $item->rawMaterial->sku }}
                                            </p>
                                        @endif

                                    </td>


                                    <td class="px-5 py-3.5 text-right font-medium text-slate-900">
                                        {{ $item->qty }}
                                    </td>


                                    <td class="px-5 py-3.5 text-right font-medium text-slate-900">
                                        {{ $item->goodsReceiptItems->sum('qty') }}
                                    </td>


                                    <td class="px-5 py-3.5 text-slate-600">
                                        {{ $item->unit->short_name ?? $item->unit->name }}
                                    </td>


                                    <td class="px-5 py-3.5 text-right text-slate-700">
                                        {{ number_format($item->unit_cost, 2) }}
                                    </td>


                                    <td class="px-5 py-3.5 text-right font-semibold text-slate-900">
                                        {{ number_format($item->total, 2) }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center text-sm text-slate-500">
                                        No order items found.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Amount Summary --}}
                <div class="grid grid-cols-3 border-t border-slate-200 bg-slate-50">

                    <div class="px-5 py-3">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">
                            Items
                        </p>

                        <p class="mt-0.5 text-sm font-semibold text-slate-900">
                            {{ $purchaseOrder->items->count() }}
                        </p>
                    </div>


                    <div class="border-l border-slate-200 px-5 py-3">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">
                            Total Quantity
                        </p>

                        <p class="mt-0.5 text-sm font-semibold text-slate-900">
                            {{ $purchaseOrder->items->sum('qty') }}
                        </p>
                    </div>


                    <div class="border-l border-slate-200 px-5 py-3 text-right">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">
                            Grand Total
                        </p>

                        <p class="mt-0.5 text-base font-bold text-slate-900">
                            {{ number_format($purchaseOrder->total, 2) }}
                        </p>
                    </div>

                </div>

            </section>


            {{-- Goods Receipts --}}
            <section class="mb-8 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">

                <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">
                            Goods Receipts
                        </h2>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Receiving records for this purchase order.
                        </p>
                    </div>


                    <div class="flex items-center gap-2">

                        <span class="text-xs font-medium text-slate-500">
                            {{ $purchaseOrder->goodsReceipts->count() }}
                            {{ Str::plural('receipt', $purchaseOrder->goodsReceipts->count()) }}
                        </span>

                        @if (in_array($purchaseOrder->status, ['placed', 'partially_received']))
                            <a href="{{ route('goods-receipts.create', $purchaseOrder) }}"
                                class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                <i class="bx bx-plus"></i>
                                New Receipt
                            </a>
                        @endif

                    </div>

                </div>


                <div class="overflow-x-auto">

                    @if ($purchaseOrder->goodsReceipts->count())

                        <table class="w-full min-w-[750px] text-left text-sm">

                            <thead class="border-b border-slate-200 bg-slate-50">

                                <tr class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">

                                    <th class="px-5 py-3">
                                        GRN Number
                                    </th>

                                    <th class="px-5 py-3">
                                        Received Date
                                    </th>

                                    <th class="px-5 py-3">
                                        Items
                                    </th>

                                    <th class="px-5 py-3">
                                        Received Qty
                                    </th>

                                    <th class="px-5 py-3">
                                        Notes
                                    </th>

                                    <th class="px-5 py-3 text-right">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-slate-100">

                                @foreach ($purchaseOrder->goodsReceipts as $receipt)

                                    <tr class="transition hover:bg-slate-50">

                                        <td class="px-5 py-3.5 font-semibold text-slate-900">
                                            {{ $receipt->grn_number }}
                                        </td>


                                        <td class="px-5 py-3.5 text-slate-600">
                                            {{ $receipt->received_date->format('d M Y') }}
                                        </td>


                                        <td class="px-5 py-3.5 text-slate-600">
                                            {{ $receipt->items->count() }}
                                        </td>


                                        <td class="px-5 py-3.5 font-medium text-slate-700">
                                            {{ $receipt->items->sum('qty') }}
                                        </td>


                                        <td class="max-w-xs px-5 py-3.5 text-slate-600">

                                            @if ($receipt->notes)
                                                <span class="block truncate" title="{{ $receipt->notes }}">
                                                    {{ $receipt->notes }}
                                                </span>
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif

                                        </td>


                                        <td class="px-5 py-3.5 text-right">

                                            <a href="{{ route('goods-receipts.show', $receipt) }}"
                                                class="inline-flex items-center gap-1.5 rounded-md bg-slate-800 px-3 py-2 text-xs font-medium text-white transition hover:bg-slate-900">
                                                View
                                                <i class="bx bx-right-arrow-alt"></i>
                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    @else

                        <div class="flex flex-col items-center justify-center px-6 py-12 text-center">

                            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-md bg-slate-100 text-slate-400">
                                <i class="bx bx-package text-xl"></i>
                            </div>

                            <h3 class="text-sm font-semibold text-slate-900">
                                No goods receipts yet
                            </h3>

                            <p class="mt-1 text-xs text-slate-500">
                                Create a goods receipt when materials arrive.
                            </p>

                        </div>

                    @endif

                </div>

            </section>

        </div>

    </div>


    @push('scripts')

        <script>
            $(document).ready(function() {

                $('#generateBill').on('click', function() {

                    const button = $(this);
                    const url = button.data('url');

                    if (!confirm('Do you want to generate the vendor bill?')) {
                        return;
                    }

                    button
                        .prop('disabled', true)
                        .html('<i class="bx bx-loader-alt bx-spin"></i> Generating...');

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
                                .html('<i class="bx bx-receipt"></i> Generate Bill');

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