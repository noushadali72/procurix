<x-layouts.app title="Dashboard">

    {{-- Page Header --}}
    <div class="mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">
                Dashboard
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Overview of your inventory, procurement, and manufacturing operations.
            </p>
        </div>
    </div>


    {{-- Session Messages --}}
    @if (session('success'))
        <div class="mb-6 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <i class="bx bx-check-circle text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="bx bx-error-circle text-lg"></i>
            {{ session('error') }}
        </div>
    @endif


    {{-- Statistics --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">

        {{-- Products --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Products
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        {{ $productsCount ?? 0 }}
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
                        {{ $rawMaterialCount ?? 0 }}
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
                        {{ $purchaseRequestsCount ?? 0 }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-purple-50 text-purple-600">
                    <i class="bx bx-file text-xl"></i>
                </div>

            </div>
        </div>


        {{-- Quotations --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Active Quotations
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        {{ $quotationsCount ?? 0 }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-50 text-green-600">
                    <i class="bx bx-receipt text-xl"></i>
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
                        {{ $purchaseOrdersCount ?? 0 }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-50 text-orange-600">
                    <i class="bx bx-cart text-xl"></i>
                </div>

            </div>
        </div>


        {{-- Manufacturing --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <div class="flex items-start justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-500">
                        Manufactured
                    </p>

                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        {{ $manufacturingCount ?? 0 }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <i class="bx bx-cog text-xl"></i>
                </div>

            </div>
        </div>

    </div>


    {{-- Main Dashboard Content --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-3">

        {{-- Quick Actions --}}
        <div class="rounded-xl border border-gray-200 bg-white lg:col-span-1">

            <div class="border-b border-gray-200 px-5 py-4">
                <h3 class="text-base font-semibold text-gray-900">
                    Quick Actions
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Common operations
                </p>
            </div>

            <div class="space-y-2 p-4">

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

        </div>


        {{-- Low Stock --}}
        <div class="rounded-xl border border-gray-200 bg-white lg:col-span-2">

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


            @if(isset($lowStockMaterials) && $lowStockMaterials->count())

                <div class="divide-y divide-gray-100">

                    @foreach($lowStockMaterials as $material)

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

        </div>

    </div>


    {{-- Recent Manufacturing --}}
    <div class="mt-6 rounded-xl border border-gray-200 bg-white">

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


        @if(isset($manufacturingRecords) && $manufacturingRecords->count())

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

                        @foreach($manufacturingRecords as $record)

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
                                    {{ $record->manufactured_at->format('d M Y, h:i A') }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="flex min-h-48 flex-col items-center justify-center px-5 py-8 text-center">

                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                    <i class="bx bx-cog text-2xl"></i>
                </div>

                <p class="mt-3 text-sm font-medium text-gray-700">
                    No manufacturing activity yet
                </p>

                <p class="mt-1 text-sm text-gray-500">
                    Completed manufacturing operations will appear here.
                </p>

                <a
                    href="{{ route('manufacturing.index') }}"
                    class="mt-4 inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-700"
                >
                    <i class="bx bx-cog"></i>
                    Manufacture Product
                </a>

            </div>

        @endif

    </div>

</x-layouts.app>
