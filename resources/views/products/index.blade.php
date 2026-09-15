<x-layouts.app title="Products">

    {{-- Page Header --}}
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Products
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Manage your products and inventory.
            </p>
        </div>

        <div>
        <a
            href="{{ route('products.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800"
        >
            <i class="bx bx-plus text-lg"></i>
            Add Product
        </a>

          <a
            href="{{ route('manufacturing.index') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-800"
        >
            <i class="bx bx-plus text-lg"></i>
            Manufacture Product
        </a>
        </div>
    </div>


    {{-- Success Message --}}
    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <i class="bx bx-check-circle text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif


    {{-- Error Message --}}
    @if (session('error'))
        <div class="mb-5 flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="bx bx-error-circle text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif


    {{-- Products Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Product
                        </th>

                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            SKU
                        </th>

                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Unit
                        </th>

                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Cost Price
                        </th>

                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Sale Price
                        </th>

                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Stock
                        </th>

                        <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Actions
                        </th>
                    </tr>
                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse ($products as $product)

                        <tr class="transition hover:bg-gray-50">

                            {{-- Product --}}
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    {{ $product->name }}
                                </div>

                                @if ($product->description)
                                    <div class="mt-1 max-w-xs truncate text-xs text-gray-500">
                                        {{ $product->description }}
                                    </div>
                                @endif
                            </td>


                            {{-- SKU --}}
                            <td class="px-6 py-4">
                                <span class="text-gray-600">
                                    {{ $product->sku ?? '-' }}
                                </span>
                            </td>


                            {{-- Unit --}}
                            <td class="px-6 py-4 text-gray-600">
                                {{ $product->unit->name ?? '-' }}
                            </td>


                            {{-- Cost Price --}}
                            <td class="px-6 py-4 font-medium text-gray-700">
                                {{ number_format($product->cost_price ?? 0, 2) }}
                            </td>


                            {{-- Sale Price --}}
                            <td class="px-6 py-4 font-medium text-gray-700">
                                {{ number_format($product->sale_price ?? 0, 2) }}
                            </td>


                            {{-- Stock --}}
                            <td class="px-6 py-4">
                                @if ($product->stock <= $product->minimum_stock)
                                    <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-600">
                                        {{ $product->stock }} · Low
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                        {{ $product->stock }}
                                    </span>
                                @endif
                            </td>


                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    <a
                                        href="{{ route('products.edit', $product) }}"
                                        class="inline-flex items-center gap-1 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white"
                                    >
                                        <i class="bx bx-edit-alt"></i>
                                        Edit
                                    </a>

                                <button
                                    type="button"
                                    class="delete-product-btn inline-flex cursor-pointer items-center gap-1 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-500 hover:text-white"
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
                            <td colspan="7" class="px-6 py-14 text-center">

                                <div class="flex flex-col items-center">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                        <i class="bx bx-package text-2xl"></i>
                                    </div>

                                    <p class="mt-3 text-sm font-medium text-gray-700">
                                        No products found
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Add your first product to get started.
                                    </p>
                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>
            </table>
        </div>


        {{-- Pagination --}}
        @if ($products->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
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
                    showToast('success', response.message);
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