<x-layouts.app title="Products">

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Products
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Manage products, pricing, units, and inventory.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a
                href="{{ route('manufacturing.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-50"
            >
                <i class="bx bx-cog text-lg"></i>
                Manufacture
            </a>

            <a
                href="{{ route('products.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800"
            >
                <i class="bx bx-plus text-lg"></i>
                Add Product
            </a>
        </div>
    </div>


    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <i class="bx bx-check-circle text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="bx bx-error-circle text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif


    {{-- Products Card --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        {{-- Table Header --}}
        <div class="flex flex-col gap-3 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">
                    Product Inventory
                </h3>

                <p class="mt-0.5 text-xs text-gray-500">
                    {{ $products->total() }} {{ Str::plural('product', $products->total()) }} in inventory
                </p>
            </div>
        </div>


        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Product
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            SKU
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Unit
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Cost
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Sale Price
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Stock
                        </th>

                        <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Actions
                        </th>
                    </tr>
                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse ($products as $product)

                        <tr class="transition hover:bg-gray-50">

                            {{-- Product --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                                        <i class="bx bx-package text-lg"></i>
                                    </div>

                                    <div class="min-w-0">
                                        <div class="truncate font-medium text-gray-900">
                                            {{ $product->name }}
                                        </div>

                                        @if ($product->description)
                                            <div class="mt-0.5 max-w-xs truncate text-xs text-gray-500">
                                                {{ $product->description }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>


                            {{-- SKU --}}
                            <td class="px-5 py-4">
                                @if ($product->sku)
                                    <span class="rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                        {{ $product->sku }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>


                            {{-- Unit --}}
                            <td class="px-5 py-4">
                                <span class="text-gray-600">
                                    {{ $product->unit->name ?? '—' }}
                                </span>
                            </td>


                            {{-- Cost --}}
                            <td class="px-5 py-4">
                                <span class="font-medium text-gray-700">
                                    {{ number_format($product->cost_price ?? 0, 2) }}
                                </span>
                            </td>


                            {{-- Sale Price --}}
                            <td class="px-5 py-4">
                                <span class="font-medium text-gray-700">
                                    {{ number_format($product->sale_price ?? 0, 2) }}
                                </span>
                            </td>


                            {{-- Stock --}}
                            <td class="px-5 py-4">
                                @if ($product->stock <= $product->minimum_stock)
                                    <div class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                        {{ $product->stock }} {{ $product->unit->short_name ?? '' }}
                                        <span class="text-red-400">· Low</span>
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                        {{ $product->stock }} {{ $product->unit->short_name ?? '' }}
                                    </div>
                                @endif
                            </td>


                            {{-- Actions --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    <a
                                        href="{{ route('products.edit', $product) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white"
                                    >
                                        <i class="bx bx-edit-alt"></i>
                                        Edit
                                    </a>

                                    <button
                                        type="button"
                                        class="delete-product-btn inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-500 hover:text-white"
                                        data-url="{{ route('products.destroy', $product) }}"
                                        data-name="{{ $product->name }}"
                                    >
                                        <i class="bx bx-trash"></i>
                                        Delete
                                    </button>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">

                                <div class="mx-auto flex max-w-sm flex-col items-center">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                        <i class="bx bx-package text-2xl"></i>
                                    </div>

                                    <p class="mt-3 text-sm font-semibold text-gray-700">
                                        No products found
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Add your first product to start managing your inventory.
                                    </p>

                                    <a
                                        href="{{ route('products.create') }}"
                                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-800"
                                    >
                                        <i class="bx bx-plus"></i>
                                        Add Product
                                    </a>
                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>


        {{-- Pagination --}}
        @if ($products->hasPages())
            <div class="border-t border-gray-200 px-5 py-4">
                {{ $products->links() }}
            </div>
        @endif

    </div>


    @push('scripts')
        <script>
            function deleteProduct(button) {
                const url = button.data('url');
                const name = button.data('name');

                if (!confirm(`Are you sure you want to delete "${name}"?`)) {
                    return;
                }

                button.prop('disabled', true);

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    },

                    success: function (response) {
                        showToast(
                            'success',
                            response.message || 'Product deleted successfully.'
                        );

                        setTimeout(function () {
                            window.location.reload();
                        }, 800);
                    },

                    error: function (xhr) {
                        showToast(
                            'error',
                            xhr.responseJSON?.message || 'Unable to delete product.'
                        );

                        button.prop('disabled', false);
                    }
                });
            }

            $(document).on('click', '.delete-product-btn', function () {
                deleteProduct($(this));
            });
        </script>
    @endpush

</x-layouts.app>
