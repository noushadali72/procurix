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

            <a href="{{ route('manufacturing.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-50">

                <i class="bx bx-cog text-lg"></i>
                Manufacture
            </a>

            <a href="{{ route('products.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">

                <i class="bx bx-plus text-lg"></i>
                Add Product
            </a>

        </div>
    </div>


    {{-- Flash Messages --}}
    @if (session('success'))
        <div
            class="mb-5 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">

            <i class="bx bx-check-circle text-lg"></i>

            <span>
                {{ session('success') }}
            </span>
        </div>
    @endif

    @if (session('error'))
        <div
            class="mb-5 flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">

            <i class="bx bx-error-circle text-lg"></i>

            <span>
                {{ session('error') }}
            </span>
        </div>
    @endif


    {{-- Filters --}}
    <form method="GET" action="{{ route('products.index') }}" class="mb-4">

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-5">

            {{-- Search --}}
            <div class="sm:col-span-2">

                <label for="search" class="mb-1.5 block text-xs font-medium text-gray-600">
                    Search
                </label>

                <div class="relative">

                    <i class="bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-lg text-gray-400">
                    </i>

                    <input type="text" name="searchQuery" id="search" value="{{ request('searchQuery') }}" 
                        placeholder="Search product name, SKU..."
                        class="w-full rounded-lg border border-gray-300 bg-white py-2.5 pl-10 pr-3
                        text-sm text-gray-900 placeholder-gray-400 outline-none transition
                        focus:border-gray-500 focus:ring-2 focus:ring-gray-200">

                </div>
            </div>


            {{-- Category --}}
            <div>

                <label for="category_id" class="mb-1.5 block text-xs font-medium text-gray-600">
                    Category
                </label>

                <select name="category_id" id="category_id"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5
                    text-sm text-gray-900 outline-none transition
                    focus:border-gray-500 focus:ring-2 focus:ring-gray-200">

                    <option value="">
                        All Categories
                    </option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>

                            {{ $category->name }}

                        </option>
                    @endforeach

                </select>

            </div>


            {{-- Minimum Price --}}
            <div>

                <label for="min_price" class="mb-1.5 block text-xs font-medium text-gray-600">
                    Min Price
                </label>

                <input type="number" name="min_price" id="min_price" min="0" step="0.01"
                    value="{{ request('min_price') }}" placeholder="0.00"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5
                    text-sm text-gray-900 outline-none transition
                    focus:border-gray-500 focus:ring-2 focus:ring-gray-200">

            </div>


            {{-- Maximum Price --}}
            <div>

                <label for="max_price" class="mb-1.5 block text-xs font-medium text-gray-600">
                    Max Price
                </label>

                <input type="number" name="max_price" id="max_price" min="0" step="0.01"
                    value="{{ request('max_price') }}" placeholder="Any"
                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5
                    text-sm text-gray-900 outline-none transition
                    focus:border-gray-500 focus:ring-2 focus:ring-gray-200">

            </div>

        </div>


        {{-- Filter Actions --}}
        <div class="mt-3 flex items-center justify-end gap-2">

            @if (request()->filled('searchQuery') ||
                    request()->filled('category_id') ||
                    request()->filled('min_price') ||
                    request()->filled('max_price'))
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3.5 py-2 text-sm font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white">
                    <i class="bx bx-reset"></i>
                    Reset
                </a>
            @endif


            <button type="submit"
                class="inline-flex cursor-pointer items-center gap-2 rounded-lg
                bg-gray-900 px-4 py-2 text-sm font-medium text-white
                transition hover:bg-gray-800">

                <i class="bx bx-filter-alt text-lg"></i>

                Apply Filters

            </button>

        </div>

    </form>


    {{-- Products Card --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        {{-- Table Header --}}
        <div
            class="flex flex-col gap-3 border-b border-gray-200 px-5 py-4
            sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h3 class="text-sm font-semibold text-gray-900">
                    Product Inventory
                </h3>

                <p class="mt-0.5 text-xs text-gray-500">
                    {{ $products->total() }}
                    {{ Str::plural('product', $products->total()) }}
                    found
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
                            Category
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
                        <tr id="product-row-{{ $product->id }}" class="transition hover:bg-gray-50">

                            {{-- Product --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center
                                        rounded-lg bg-gray-100 text-gray-500">

                                        <i class="bx bx-package text-lg"></i>

                                    </div>


                                    <div class="min-w-0">

                                        <div class="truncate font-medium text-gray-900">
                                            {{ $product->name }}
                                        </div>

                                        <div class="mt-0.5 max-w-xs truncate text-xs text-gray-500">

                                            {{ $product->description ?? 'No description' }}

                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- Category --}}
                            <td class="px-5 py-4">

                                <span
                                    class="rounded-md bg-gray-100 px-2 py-1
                                    text-xs font-medium text-gray-600">

                                    {{ $product->category?->name ?? '-' }}

                                </span>

                            </td>


                            {{-- Unit --}}
                            <td class="px-5 py-4">

                                <span class="text-gray-600">
                                    {{ $product->unit?->short_name ?? '-' }}
                                </span>

                            </td>


                            {{-- Cost Price --}}
                            <td class="px-5 py-4">

                                <span class="font-medium text-gray-700">

                                    {{ $product->cost_price !== null ? number_format($product->cost_price, 2) : '-' }}

                                </span>

                            </td>


                            {{-- Sale Price --}}
                            <td class="px-5 py-4">

                                <span class="font-medium text-gray-700">

                                    {{ $product->sale_price !== null ? number_format($product->sale_price, 2) : '-' }}

                                </span>

                            </td>


                            {{-- Stock --}}
                            <td class="px-5 py-4">

                                @if ($product->stock <= $product->minimum_stock)
                                    <div
                                        class="inline-flex items-center gap-1.5 rounded-full
                                        bg-red-50 px-2.5 py-1 text-xs font-medium text-red-600">

                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500">
                                        </span>

                                        {{ $product->stock }}
                                        {{ $product->unit?->short_name }}

                                        <span class="text-red-400">
                                            · Low
                                        </span>

                                    </div>
                                @else
                                    <div
                                        class="inline-flex items-center gap-1.5 rounded-full
                                        bg-gray-100 px-2.5 py-1 text-xs font-medium
                                        text-green-700">

                                        <span class="h-1.5 w-1.5 rounded-full bg-green-400">
                                        </span>

                                        {{ $product->stock }}
                                        {{ $product->unit?->short_name }}

                                    </div>
                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('products.edit', $product) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg
                                        border border-gray-300 px-3 py-1.5 text-xs
                                        font-medium text-gray-700 transition
                                        hover:border-gray-900 hover:bg-gray-900
                                        hover:text-white">

                                        <i class="bx bx-edit-alt"></i>

                                        Edit

                                    </a>


                                    <button type="button"
                                        class="delete-product-btn inline-flex cursor-pointer
                                        items-center gap-1.5 rounded-lg border border-red-200
                                        px-3 py-1.5 text-xs font-medium text-red-600
                                        transition hover:bg-red-500 hover:text-white"
                                        data-url="{{ route('products.destroy', $product) }}"
                                        data-name="{{ $product->name }}">

                                        <i class="bx bx-trash"></i>

                                        Delete

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="px-5 py-12 text-center">

                                <div class="flex flex-col items-center">

                                    <div
                                        class="mb-3 flex h-11 w-11 items-center
                                        justify-center rounded-full bg-gray-100">

                                        <i class="bx bx-package text-xl text-gray-400">
                                        </i>

                                    </div>

                                    <h3 class="text-sm font-medium text-gray-900">
                                        No products found
                                    </h3>

                                    <p class="mt-1 text-xs text-gray-500">

                                        @if (request()->filled('search') ||
                                                request()->filled('category_id') ||
                                                request()->filled('min_price') ||
                                                request()->filled('max_price'))
                                            Try changing your search or filters.
                                        @else
                                            Add your first product to get started.
                                        @endif

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
            <div
                class="flex flex-col gap-3 border-t border-gray-200 px-5 py-4
                sm:flex-row sm:items-center sm:justify-between">

                <p class="text-sm text-gray-500">

                    Showing
                    {{ $products->firstItem() }}
                    to
                    {{ $products->lastItem() }}
                    of
                    {{ $products->total() }}
                    products

                </p>


                <div>
                    {{ $products->links() }}
                </div>

            </div>
        @endif

    </div>


    @push('scripts')
        <script>
            $(document).ready(function() {

                $(document).on('click', '.delete-product-btn', function() {

                    const button = $(this);
                    const url = button.data('url');
                    const name = button.data('name');


                    if (!confirm(
                            `Are you sure you want to delete "${name}"?`
                        )) {
                        return;
                    }


                    button.prop('disabled', true);


                    $.ajax({

                        url: url,

                        type: 'DELETE',

                        headers: {
                            'Accept': 'application/json'
                        },


                        success: function(response) {

                            showToast(
                                'success',
                                response.message ||
                                'Product deleted successfully.'
                            );


                            button.closest('tr').fadeOut(250, function() {
                                $(this).remove();
                            });

                        },


                        error: function(xhr) {

                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to delete product.'
                            );

                            button.prop('disabled', false);

                        }

                    });

                });

            });
        </script>
    @endpush

</x-layouts.app>
