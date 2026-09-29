<x-layouts.app title="Dashboard">

    {{-- Page Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">
            Dashboard
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Overview of your inventory, procurement, finance, and manufacturing operations.
        </p>
    </div>

    {{-- Session Messages --}}
    @if (session('success'))
        <div
            class="mb-6 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <i class="bx bx-check-circle text-lg"></i>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div
            class="mb-6 flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="bx bx-error-circle text-lg"></i>
            {{ session('error') }}
        </div>
    @endif


    {{-- ========================================================= --}}
    {{-- KEY STATISTICS --}}
    {{-- ========================================================= --}}

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        {{-- Products --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Products
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        {{ $productsCount }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                    <i class="bx bx-package text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Raw Materials --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Raw Materials
                    </p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        {{ $rawMaterialCount }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                    <i class="bx bx-cube text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Purchase Requests --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Purchase Requests
                    </p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        {{ $purchaseRequestsCount }}
                    </p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-50 text-purple-600">
                    <i class="bx bx-file text-xl"></i>
                </div>
            </div>
        </div>


        {{-- Purchase Orders --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Purchase Orders
                    </p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        {{ $purchaseOrdersCount }}
                    </p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-50 text-orange-600">
                    <i class="bx bx-cart text-xl"></i>
                </div>
            </div>
        </div>


        {{-- Outstanding Bills --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Outstanding Bills
                    </p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        {{ $outstandingBillsCount }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 text-red-600">
                    <i class="bx bx-receipt text-xl"></i>
                </div>
            </div>
        </div>


        {{-- Low Stock --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Low Stock
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        {{ $lowStockCount }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 text-red-600">
                    <i class="bx bx-error-circle text-xl"></i>
                </div>
            </div>
        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- PROCUREMENT + FINANCE --}}
    {{-- ========================================================= --}}

    <div class="mt-6 grid gap-6 lg:grid-cols-2">

        {{-- Procurement Overview --}}
        <section class="rounded-xl border border-gray-200 bg-white">

            <div class="border-b border-gray-200 px-5 py-4">
                <h3 class="text-base font-semibold text-gray-900">
                    Procurement Overview
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Current procurement activity
                </p>
            </div>

            <div class="grid grid-cols-2 divide-x divide-y divide-gray-100">

                <a
                    href="{{ route('purchase-requests.index') }}"
                    class="p-5 transition hover:bg-gray-50"
                >
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Purchase Requests
                    </p>

                    <p class="mt-2 text-xl font-semibold text-gray-900">
                        {{ $purchaseRequestsCount }}
                    </p>
                </a>


                <a
                    href="{{ route('purchase-orders.index') }}"
                    class="p-5 transition hover:bg-gray-50"
                >
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Purchase Orders
                    </p>

                    <p class="mt-2 text-xl font-semibold text-gray-900">
                        {{ $purchaseOrdersCount }}
                    </p>
                </a>


                <a
                    href="{{ route('goods-receipts.index') }}"
                    class="p-5 transition hover:bg-gray-50"
                >
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Goods Receipts
                    </p>

                    <p class="mt-2 text-xl font-semibold text-gray-900">
                        {{ $goodsReceiptsCount }}
                    </p>
                </a>


                <a
                    href="{{ route('purchase-returns.index') }}"
                    class="p-5 transition hover:bg-gray-50"
                >
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Purchase Returns
                    </p>

                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-xl font-semibold text-gray-900">
                            {{ $purchaseReturnsCount }}
                        </span>

                        @if ($pendingPurchaseReturnsCount)
                            <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-medium text-amber-700">
                                {{ $pendingPurchaseReturnsCount }} draft
                            </span>
                        @endif
                    </div>
                </a>

            </div>

        </section>


        {{-- Finance Overview --}}
        <section class="rounded-xl border border-gray-200 bg-white">

            <div class="border-b border-gray-200 px-5 py-4">
                <h3 class="text-base font-semibold text-gray-900">
                    Finance Overview
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Vendor liabilities and available credits
                </p>
            </div>

            <div class="divide-y divide-gray-100">

                <a
                    href="{{ route('vendor-bills.index') }}"
                    class="flex items-center justify-between px-5 py-4 transition hover:bg-gray-50"
                >
                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600">
                            <i class="bx bx-receipt"></i>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                Outstanding Bills
                            </p>

                            <p class="mt-0.5 text-xs text-gray-500">
                                Bills with an outstanding balance
                            </p>
                        </div>

                    </div>

                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $outstandingBillsCount }}
                        </p>

                        <p class="mt-0.5 text-xs text-gray-500">
                            {{ number_format($outstandingBillsAmount, 2) }}
                        </p>
                    </div>
                </a>


                <a
                    href="{{ route('vendor-credits.index') }}"
                    class="flex items-center justify-between px-5 py-4 transition hover:bg-gray-50"
                >
                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                            <i class="bx bx-credit-card"></i>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                Available Vendor Credits
                            </p>

                            <p class="mt-0.5 text-xs text-gray-500">
                                Credits not fully settled
                            </p>
                        </div>

                    </div>

                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900">
                            {{ $availableCreditsCount }}
                        </p>

                        <p class="mt-0.5 text-xs text-gray-500">
                            {{ number_format($availableCreditsAmount, 2) }}
                        </p>
                    </div>
                </a>


                <a
                    href="{{ route('vendor-payments.index') }}"
                    class="flex items-center justify-between px-5 py-4 transition hover:bg-gray-50"
                >
                    <div class="flex items-center gap-3">

                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <i class="bx bx-money"></i>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                Vendor Payments
                            </p>

                            <p class="mt-0.5 text-xs text-gray-500">
                                Manage bill payments
                            </p>
                        </div>

                    </div>

                    <i class="bx bx-chevron-right text-lg text-gray-400"></i>
                </a>

            </div>

        </section>

    </div>


    {{-- ========================================================= --}}
    {{-- LOW STOCK + QUICK ACTIONS --}}
    {{-- ========================================================= --}}

    <div class="mt-6 grid gap-6 lg:grid-cols-3">

        {{-- Low Stock --}}
        <section class="rounded-xl border border-gray-200 bg-white lg:col-span-2">
            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">
                        Low Stock
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Raw materials that need attention
                    </p>
                </div>
                <a
                    href="{{ route('raw-materials.index') }}"
                    class="text-sm font-medium text-gray-600 hover:text-gray-900"
                >
                    View all
                </a>

            </div>


            @if ($lowStockMaterials->count())

                <div class="divide-y divide-gray-100">

                    @foreach ($lowStockMaterials as $material)

                        <div class="flex items-center justify-between px-5 py-4">

                            <div class="min-w-0">
                                <p class="truncate text-sm font-medium text-gray-900">
                                    {{ $material->name }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    Minimum:
                                    {{ rtrim(rtrim(number_format($material->minimum_stock, 4), '0'), '.') }}
                                    {{ $material->unit?->short_name }}
                                </p>
                            </div>

                            <div class="ml-4 text-right">

                                <p class="text-sm font-semibold text-red-600">
                                    {{ rtrim(rtrim(number_format($material->stock, 4), '0'), '.') }}
                                    {{ $material->unit?->short_name }}
                                </p>

                                <p class="mt-1 text-xs text-red-500">
                                    Low stock
                                </p>

                            </div>

                        </div>

                    @endforeach
                </div>

            @else

                <div class="flex min-h-48 flex-col items-center justify-center px-5 py-8 text-center">

                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-green-50 text-green-600">
                        <i class="bx bx-check text-2xl"></i>
                    </div>
                    <p class="mt-3 text-sm font-medium text-gray-700">
                        Stock levels look good
                    </p>
                    <p class="mt-1 text-sm text-gray-500">
                        No raw materials are currently below their minimum stock level.
                    </p>

                </div>

            @endif
        </section>


        {{-- Quick Actions --}}
        <section class="rounded-xl border border-gray-200 bg-white">
            <div class="border-b border-gray-200 px-5 py-4">
                <h3 class="text-base font-semibold text-gray-900">
                    Quick Actions
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    Common operations
                </p>
            </div>


            <div class="space-y-1 p-3">

                <a
                    href="{{ route('purchase-requests.create') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-purple-50 text-purple-600">
                        <i class="bx bx-file text-lg"></i>
                    </span>
                    <span>New Purchase Request</span>
                    <i class="bx bx-chevron-right ml-auto text-lg text-gray-400"></i>
                </a>


                <a
                    href="{{ route('products.create') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                        <i class="bx bx-package text-lg"></i>
                    </span>
                    <span>Add Product</span>
                    <i class="bx bx-chevron-right ml-auto text-lg text-gray-400"></i>
                </a>


                <a
                    href="{{ route('raw-materials.create') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                        <i class="bx bx-cube text-lg"></i>
                    </span>
                    <span>Add Raw Material</span>
                    <i class="bx bx-chevron-right ml-auto text-lg text-gray-400"></i>
                </a>


                <a
                    href="{{ route('materials.receive') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <i class="bx bx-download text-lg"></i>
                    </span>
                    <span>Receive Materials</span>
                    <i class="bx bx-chevron-right ml-auto text-lg text-gray-400"></i>
                </a>


                <a
                    href="{{ route('manufacturing.index') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                        <i class="bx bx-cog text-lg"></i>
                    </span>
                    <span>Manufacture Product</span>
                    <i class="bx bx-chevron-right ml-auto text-lg text-gray-400"></i>
                </a>
            </div>
        </section>

    </div>


    {{-- ========================================================= --}}
    {{-- RECENT PROCUREMENT ACTIVITY --}}
    {{-- ========================================================= --}}

    <section class="mt-6 rounded-xl border border-gray-200 bg-white">
        <div class="border-b border-gray-200 px-5 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">
                        Recent Procurement Activity
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Latest activity across the procurement lifecycle
                    </p>
                </div>
                <i class="bx bx-history text-xl text-gray-400"></i>

            </div>

        </div>


        @if ($recentActivities->count())

            <div class="divide-y divide-gray-100">

                @foreach ($recentActivities as $activity)

                    @php
                        $activityClass = match ($activity->action) {
                            'approved',
                            'completed',
                            'goods_receipt_created'
                                => 'bg-green-50 text-green-600',

                            'rejected'
                                => 'bg-red-50 text-red-600',

                            'submitted',
                            'rfq_sent'
                                => 'bg-blue-50 text-blue-600',

                            'quotation_received',
                            'quotation_selected'
                                => 'bg-violet-50 text-violet-600',

                            'purchase_order_created',
                            'vendor_bill_created'
                                => 'bg-amber-50 text-amber-600',

                            'vendor_payment_created',
                            'vendor_credit_created'
                                => 'bg-emerald-50 text-emerald-600',

                            'purchase_return_created'
                                => 'bg-orange-50 text-orange-600',

                            default
                                => 'bg-gray-100 text-gray-500',
                        };
                    @endphp

                    <div class="flex items-center gap-4 px-5 py-4">

                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $activityClass }}">
                            <i class="bx bx-history text-lg"></i>
                        </div>


                        <div class="min-w-0 flex-1">

                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1">

                                <p class="text-sm font-medium text-gray-900">
                                    {{ ucwords(str_replace('_', ' ', $activity->action ?? 'Activity')) }}
                                </p>

                                @if ($activity->purchaseRequest)
                                    <a
                                        href="{{ route('purchase-requests.show', $activity->purchaseRequest) }}"
                                        class="text-xs font-medium text-gray-500 hover:text-gray-900"
                                    >
                                        PR #{{ $activity->purchaseRequest->id }}
                                    </a>
                                @endif

                            </div>

                            @if ($activity->description)
                                <p class="mt-0.5 truncate text-xs text-gray-500">
                                    {{ $activity->description }}
                                </p>
                            @endif

                        </div>


                        <div class="hidden shrink-0 text-right sm:block">

                            @if ($activity->user)
                                <p class="text-xs font-medium text-gray-600">
                                    {{ $activity->user->name }}
                                </p>
                            @endif

                            <p class="mt-0.5 text-[11px] text-gray-400">
                                {{ $activity->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else

            <div class="flex min-h-40 flex-col items-center justify-center px-5 py-8 text-center">

                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                    <i class="bx bx-history text-xl"></i>
                </div>

                <p class="mt-3 text-sm font-medium text-gray-700">
                    No procurement activity yet
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Procurement activity will appear here as transactions are processed.
                </p>

            </div>

        @endif

    </section>


    {{-- ========================================================= --}}
    {{-- RECENT MANUFACTURING --}}
    {{-- ========================================================= --}}

    <section class="mt-6 rounded-xl border border-gray-200 bg-white">

        <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">

            <div>
                <h3 class="text-base font-semibold text-gray-900">
                    Recent Manufacturing
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Latest products manufactured
                </p>
            </div>

            <a
                href="{{ route('manufacturing.records') }}"
                class="text-sm font-medium text-gray-600 hover:text-gray-900"
            >
                View records
            </a>
        </div>


        @if ($manufacturingRecords->count())

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm">

                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-5 py-3 font-medium">
                                Product
                            </th>
                            <th class="px-5 py-3 font-medium">
                                Quantity
                            </th>
                            <th class="px-5 py-3 font-medium">
                                Formula
                            </th>

                            <th class="px-5 py-3 font-medium">
                                Manufactured At
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @foreach ($manufacturingRecords as $record)

                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 font-medium text-gray-900">
                                    {{ $record->product->name }}
                                </td>

                                <td class="px-5 py-4 text-gray-700">
                                    {{ rtrim(rtrim(number_format($record->quantity, 4), '0'), '.') }}
                                    {{ $record->unit?->short_name }}
                                </td>

                                <td class="px-5 py-4 text-gray-600">
                                    {{ $record->manufacturingFormula->name }}
                                </td>

                                <td class="px-5 py-4 text-gray-500">
                                    {{ $record->manufactured_at?->format('d M Y, h:i A') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="flex min-h-40 flex-col items-center justify-center px-5 py-8 text-center">

                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                    <i class="bx bx-cog text-xl"></i>
                </div>

                <p class="mt-3 text-sm font-medium text-gray-700">
                    No manufacturing activity yet
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Completed manufacturing operations will appear here.
                </p>

            </div>

        @endif

    </section>

</x-layouts.app>