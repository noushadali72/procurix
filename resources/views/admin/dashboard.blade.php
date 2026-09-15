
<x-layouts.app title="Dashboard">

    {{-- Page Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-900">
            Dashboard
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Overview of your inventory and procurement operations.
        </p>
    </div>



    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-6 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <i class="bx bx-check-circle text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
        <div class="mb-6 flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="bx bx-error-circle text-lg"></i>
            {{ session('error') }}
        </div>
    @endif

    
    {{-- Statistics --}}
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">

        {{-- Products --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Products</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        {{ $productsCount ?? 0 }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                    <i class="bx bx-package text-xl"></i>
                </div>
            </div>
        </div>


        {{-- Raw Materials --}}
        <div class="rounded-xl border border-gray-200 bg-white p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Raw Materials</p>
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
                    <p class="text-sm font-medium text-gray-500">Purchase Requests</p>
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
                    <p class="text-sm font-medium text-gray-500">Purchase Orders</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">
                        {{ $purchaseOrdersCount ?? 0 }}
                    </p>
                </div>

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 text-red-600">
                    <i class="bx bx-cart text-xl"></i>
                </div>
            </div>
        </div>

    </div>


    {{-- Recent Activity --}}
    <div class="mt-6 overflow-hidden rounded-xl border border-gray-200 bg-white">

        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="text-base font-semibold text-gray-900">
                Recent Activity
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Latest activity across your inventory and procurement operations.
            </p>
        </div>

        <div class="flex min-h-56 flex-col items-center justify-center px-6 py-10 text-center">

            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                <i class="bx bx-history text-2xl"></i>
            </div>

            <p class="mt-4 text-sm font-medium text-gray-700">
                No recent activity
            </p>

            <p class="mt-1 max-w-sm text-sm text-gray-500">
                Recent procurement and inventory activity will appear here.
            </p>

        </div>

    </div>

</x-layouts.app>