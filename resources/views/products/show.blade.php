<x-layouts.app title="Product Details">

    @php
        $stock = (float) $product->stock;
        $minimumStock = (float) $product->minimum_stock;

        if ($stock <= 0) {
            $stockStatus = 'Out of Stock';
            $stockStatusClass = 'bg-red-50 text-red-600 border-red-200';
        } elseif ($stock <= $minimumStock) {
            $stockStatus = 'Low Stock';
            $stockStatusClass = 'bg-amber-50 text-amber-600 border-amber-200';
        } else {
            $stockStatus = 'In Stock';
            $stockStatusClass = 'bg-green-50 text-green-600 border-green-200';
        }
    @endphp

    <div class="max-w-7xl">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

            <div>
                <div class="flex items-center gap-2 text-xs text-gray-400 mb-2">
                    <a href="{{ route('products.index') }}" class="hover:text-gray-700 transition">
                        Products
                    </a>

                    <i class="bx bx-chevron-right text-sm"></i>

                    <span class="text-gray-500 truncate max-w-[220px]">
                        {{ $product->name }}
                    </span>
                </div>

                <h1 class="text-xl font-semibold text-gray-900">
                    Product Details
                </h1>

                <p class="text-xs text-gray-400 mt-1">
                    View product information and stock movement history.
                </p>
            </div>

            <div class="flex items-center gap-2">

                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 px-3 py-2
                          text-sm font-medium text-gray-600 bg-white
                          border border-gray-200 rounded-lg
                          hover:bg-gray-50 transition">
                    <i class="bx bx-arrow-back text-base"></i>
                    Back
                </a>

                <a href="{{ route('products.edit', $product) }}"
                   class="inline-flex items-center gap-2 px-3 py-2
                          text-sm font-medium text-white bg-gray-900
                          rounded-lg hover:bg-gray-800 transition">
                    <i class="bx bx-edit text-base"></i>
                    Edit Product
                </a>

            </div>
        </div>


        {{-- Product Overview --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-5">

            {{-- Product Image --}}
            <div class="bg-white border border-gray-200 rounded-xl p-5">

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">
                            Product Image
                        </h2>
                        <p class="text-[11px] text-gray-400 mt-0.5">
                            Product preview
                        </p>
                    </div>

                    <span class="inline-flex items-center gap-1.5 px-2 py-1
                                 rounded-md border text-[10px] font-semibold
                                 {{ $stockStatusClass }}">
                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                        {{ $stockStatus }}
                    </span>
                </div>

                <div class="w-full h-64 rounded-xl border border-gray-200
                            bg-gray-50 overflow-hidden flex items-center justify-center">

                    @if ($product->image_path)

                        <img
                            src="{{ asset('storage/' . $product->image_path) }}"
                            alt="{{ $product->name }}"
                            class="h-full w-full object-contain"
                        >

                    @else

                        <div class="flex flex-col items-center justify-center text-gray-300">
                            <i class="bx bx-package text-6xl"></i>

                            <span class="text-xs text-gray-400 mt-2">
                                No image available
                            </span>
                        </div>

                    @endif

                </div>

            </div>


            {{-- Product Information --}}
            <div class="xl:col-span-2 bg-white border border-gray-200 rounded-xl p-5">

                <div class="flex items-start justify-between gap-4 mb-5">

                    <div class="min-w-0">
                        <h2 class="text-lg font-semibold text-gray-900 truncate">
                            {{ $product->name }}
                        </h2>

                        <div class="flex flex-wrap items-center gap-2 mt-2">

                            @if ($product->sku)
                                <span class="inline-flex items-center gap-1.5
                                             px-2 py-1 rounded-md bg-gray-100
                                             text-gray-600 text-[11px]">
                                    <i class="bx bx-barcode"></i>
                                    {{ $product->sku }}
                                </span>
                            @endif

                            @if ($product->category)
                                <span class="inline-flex items-center gap-1.5
                                             px-2 py-1 rounded-md bg-gray-100
                                             text-gray-600 text-[11px]">
                                    <i class="bx bx-category"></i>
                                    {{ $product->category->name }}
                                </span>
                            @endif

                        </div>
                    </div>

                </div>


                <div class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-5">

                    {{-- Unit --}}
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
                            Unit
                        </p>

                        <p class="text-sm font-medium text-gray-800 mt-1">
                            {{ $product->unit?->name ?? '—' }}
                        </p>
                    </div>


                    {{-- Cost Price --}}
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
                            Cost Price
                        </p>

                        <p class="text-sm font-semibold text-gray-800 mt-1">
                            {{ $product->cost_price !== null
                                ? number_format((float) $product->cost_price, 2)
                                : '—' }}
                        </p>
                    </div>


                    {{-- Sale Price --}}
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
                            Sale Price
                        </p>

                        <p class="text-sm font-semibold text-gray-800 mt-1">
                            {{ $product->sale_price !== null
                                ? number_format((float) $product->sale_price, 2)
                                : '—' }}
                        </p>
                    </div>


                    {{-- Current Stock --}}
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
                            Current Stock
                        </p>

                        <p class="text-sm font-semibold mt-1
                            {{ $stock <= 0
                                ? 'text-red-600'
                                : ($stock <= $minimumStock ? 'text-amber-600' : 'text-green-600') }}">
                            {{ number_format($stock, 2) }}
                            <span class="text-[11px] font-normal text-gray-400">
                                {{ $product->unit?->name }}
                            </span>
                        </p>
                    </div>


                    {{-- Minimum Stock --}}
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
                            Minimum Stock
                        </p>

                        <p class="text-sm font-medium text-gray-800 mt-1">
                            {{ number_format($minimumStock, 2) }}
                            <span class="text-[11px] font-normal text-gray-400">
                                {{ $product->unit?->name }}
                            </span>
                        </p>
                    </div>


                    {{-- Created --}}
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400">
                            Created
                        </p>

                        <p class="text-sm font-medium text-gray-800 mt-1">
                            {{ $product->created_at->format('d M Y') }}
                        </p>
                    </div>

                </div>


                {{-- Description --}}
                @if ($product->description)

                    <div class="mt-6 pt-5 border-t border-gray-100">

                        <p class="text-[10px] font-semibold uppercase tracking-wide text-gray-400 mb-2">
                            Description
                        </p>

                        <p class="text-sm leading-6 text-gray-600 whitespace-pre-line">
                            {{ $product->description }}
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- Stock Summary --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">

            {{-- Current Stock --}}
            <div class="bg-white border border-gray-200 rounded-xl p-4">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-xs text-gray-400">
                            Current Stock
                        </p>

                        <p class="text-xl font-semibold text-gray-900 mt-1">
                            {{ number_format($stock, 2) }}
                        </p>

                        <p class="text-[10px] text-gray-400 mt-0.5">
                            {{ $product->unit?->name }}
                        </p>
                    </div>

                    <div class="h-10 w-10 rounded-lg bg-gray-100
                                flex items-center justify-center">
                        <i class="bx bx-package text-xl text-gray-600"></i>
                    </div>

                </div>

            </div>


            {{-- Minimum Stock --}}
            <div class="bg-white border border-gray-200 rounded-xl p-4">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-xs text-gray-400">
                            Minimum Stock
                        </p>

                        <p class="text-xl font-semibold text-gray-900 mt-1">
                            {{ number_format($minimumStock, 2) }}
                        </p>

                        <p class="text-[10px] text-gray-400 mt-0.5">
                            Reorder threshold
                        </p>
                    </div>

                    <div class="h-10 w-10 rounded-lg bg-amber-50
                                flex items-center justify-center">
                        <i class="bx bx-low-vision text-xl text-amber-500"></i>
                    </div>

                </div>

            </div>


            {{-- Movement Count --}}
            <div class="bg-white border border-gray-200 rounded-xl p-4">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-xs text-gray-400">
                            Stock Movements
                        </p>

                        <p class="text-xl font-semibold text-gray-900 mt-1">
                            {{ $product->movements->count() }}
                        </p>

                        <p class="text-[10px] text-gray-400 mt-0.5">
                            Recorded movements
                        </p>
                    </div>

                    <div class="h-10 w-10 rounded-lg bg-blue-50
                                flex items-center justify-center">
                        <i class="bx bx-transfer text-xl text-blue-500"></i>
                    </div>

                </div>

            </div>

        </div>


        {{-- Stock Movement History --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-gray-200">

                <div class="flex flex-col sm:flex-row sm:items-center
                            sm:justify-between gap-3">

                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">
                            Stock Movement History
                        </h2>

                        <p class="text-[11px] text-gray-400 mt-0.5">
                            Complete stock ledger for this product.
                        </p>
                    </div>

                    <span class="inline-flex items-center gap-1.5
                                 px-2.5 py-1 rounded-md bg-gray-100
                                 text-gray-600 text-[11px] font-medium">
                        <i class="bx bx-transfer-alt"></i>
                        {{ $product->movements->count() }} movements
                    </span>

                </div>

            </div>


            @if ($product->movements->isNotEmpty())

                {{-- Desktop Table --}}
                <div class="hidden md:block overflow-x-auto">

                    <table class="w-full">

                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">

                                <th class="px-5 py-3 text-left text-[10px]
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Date
                                </th>

                                <th class="px-5 py-3 text-left text-[10px]
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Movement
                                </th>

                                <th class="px-5 py-3 text-left text-[10px]
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Direction
                                </th>

                                <th class="px-5 py-3 text-right text-[10px]
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Quantity
                                </th>

                                <th class="px-5 py-3 text-left text-[10px]
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Reference
                                </th>

                                <th class="px-5 py-3 text-left text-[10px]
                                           font-semibold uppercase tracking-wide
                                           text-gray-500">
                                    Created By
                                </th>

                            </tr>
                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @foreach ($product->movements as $movement)

                                @php
                                    $isIn = $movement->direction === 'in';

                                    $movementLabel = ucwords(
                                        str_replace('_', ' ', $movement->type ?? 'movement')
                                    );
                                @endphp

                                <tr class="hover:bg-gray-50 transition">

                                    {{-- Date --}}
                                    <td class="px-5 py-3.5 whitespace-nowrap">

                                        <p class="text-xs font-medium text-gray-700">
                                            {{ $movement->created_at->format('d M Y') }}
                                        </p>

                                        <p class="text-[10px] text-gray-400 mt-0.5">
                                            {{ $movement->created_at->format('h:i A') }}
                                        </p>

                                    </td>


                                    {{-- Movement --}}
                                    <td class="px-5 py-3.5">

                                        <div class="flex items-center gap-2.5">

                                            <div class="h-8 w-8 shrink-0 rounded-lg
                                                        flex items-center justify-center
                                                        {{ $isIn
                                                            ? 'bg-green-50 text-green-600'
                                                            : 'bg-red-50 text-red-600' }}">

                                                <i class="bx
                                                    {{ $isIn
                                                        ? 'bx-down-arrow-alt'
                                                        : 'bx-up-arrow-alt' }}
                                                    text-lg">
                                                </i>

                                            </div>

                                            <div class="min-w-0">

                                                <p class="text-xs font-medium text-gray-800">
                                                    {{ $movementLabel }}
                                                </p>

                                                @if ($movement->notes)

                                                    <p class="text-[10px] text-gray-400
                                                              max-w-[220px] truncate">
                                                        {{ $movement->notes }}
                                                    </p>

                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Direction --}}
                                    <td class="px-5 py-3.5">

                                        @if ($isIn)

                                            <span class="inline-flex items-center gap-1
                                                         px-2 py-1 rounded-md
                                                         bg-green-50 text-green-600
                                                         border border-green-100
                                                         text-[10px] font-semibold">
                                                <i class="bx bx-plus"></i>
                                                IN
                                            </span>

                                        @else

                                            <span class="inline-flex items-center gap-1
                                                         px-2 py-1 rounded-md
                                                         bg-red-50 text-red-600
                                                         border border-red-100
                                                         text-[10px] font-semibold">
                                                <i class="bx bx-minus"></i>
                                                OUT
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Quantity --}}
                                    <td class="px-5 py-3.5 text-right whitespace-nowrap">

                                        <span class="text-sm font-semibold
                                            {{ $isIn ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $isIn ? '+' : '-' }}{{ number_format((float) $movement->qty, 2) }}
                                        </span>

                                        <span class="text-[10px] text-gray-400 ml-1">
                                            {{ $movement->unit?->name ?? $product->unit?->name }}
                                        </span>

                                    </td>


                                    {{-- Reference --}}
                                    <td class="px-5 py-3.5">

                                        @if ($movement->reference)

                                            <div class="flex items-center gap-1.5 text-xs text-gray-700">
                                                <i class="bx bx-link text-gray-400"></i>

                                                {{ class_basename($movement->reference_type) }}
                                                #{{ $movement->reference_id }}
                                            </div>

                                        @else

                                            <span class="text-xs text-gray-400">
                                                Manual
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Created By --}}
                                    <td class="px-5 py-3.5">

                                        <div class="flex items-center gap-2">

                                            <div class="h-7 w-7 rounded-full bg-gray-100
                                                        flex items-center justify-center">
                                                <i class="bx bx-user text-sm text-gray-500"></i>
                                            </div>

                                            <div>
                                                <p class="text-xs font-medium text-gray-700">
                                                    {{ $movement->creator?->name ?? 'System' }}
                                                </p>

                                                <p class="text-[10px] text-gray-400">
                                                    {{ $movement->created_at->diffForHumans() }}
                                                </p>
                                            </div>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Mobile --}}
                <div class="md:hidden divide-y divide-gray-100">

                    @foreach ($product->movements as $movement)

                        @php
                            $isIn = $movement->direction === 'in';

                            $movementLabel = ucwords(
                                str_replace('_', ' ', $movement->type ?? 'movement')
                            );
                        @endphp

                        <div class="p-4">

                            <div class="flex items-start justify-between gap-3">

                                <div class="flex items-center gap-3 min-w-0">

                                    <div class="h-9 w-9 shrink-0 rounded-lg
                                                flex items-center justify-center
                                                {{ $isIn
                                                    ? 'bg-green-50 text-green-600'
                                                    : 'bg-red-50 text-red-600' }}">
                                        <i class="bx
                                            {{ $isIn
                                                ? 'bx-down-arrow-alt'
                                                : 'bx-up-arrow-alt' }}
                                            text-xl">
                                        </i>
                                    </div>

                                    <div class="min-w-0">

                                        <p class="text-sm font-medium text-gray-800 truncate">
                                            {{ $movementLabel }}
                                        </p>

                                        <p class="text-[10px] text-gray-400 mt-0.5">
                                            {{ $movement->created_at->format('d M Y, h:i A') }}
                                        </p>

                                    </div>

                                </div>


                                <div class="text-right shrink-0">

                                    <p class="text-sm font-semibold
                                        {{ $isIn ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $isIn ? '+' : '-' }}{{ number_format((float) $movement->qty, 2) }}
                                    </p>

                                    <p class="text-[10px] text-gray-400">
                                        {{ $movement->unit?->name ?? $product->unit?->name }}
                                    </p>

                                </div>

                            </div>


                            <div class="grid grid-cols-2 gap-4 mt-4 ml-12">

                                <div>
                                    <p class="text-[10px] uppercase tracking-wide text-gray-400">
                                        Direction
                                    </p>

                                    <p class="text-xs font-medium mt-1
                                        {{ $isIn ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $isIn ? 'Stock In' : 'Stock Out' }}
                                    </p>
                                </div>


                                <div>
                                    <p class="text-[10px] uppercase tracking-wide text-gray-400">
                                        Reference
                                    </p>

                                    <p class="text-xs font-medium text-gray-700 mt-1 truncate">

                                        @if ($movement->reference)
                                            {{ class_basename($movement->reference_type) }}
                                            #{{ $movement->reference_id }}
                                        @else
                                            Manual
                                        @endif

                                    </p>
                                </div>

                            </div>


                            @if ($movement->notes)

                                <div class="mt-3 ml-12 rounded-lg bg-gray-50 p-3">

                                    <p class="text-[10px] uppercase tracking-wide
                                              text-gray-400 mb-1">
                                        Notes
                                    </p>

                                    <p class="text-xs text-gray-600 leading-5">
                                        {{ $movement->notes }}
                                    </p>

                                </div>

                            @endif


                            <div class="flex items-center gap-2 mt-3 ml-12">

                                <div class="h-6 w-6 rounded-full bg-gray-100
                                            flex items-center justify-center">
                                    <i class="bx bx-user text-xs text-gray-500"></i>
                                </div>

                                <span class="text-[11px] text-gray-500">
                                    {{ $movement->creator?->name ?? 'System' }}
                                </span>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                {{-- Empty State --}}
                <div class="py-16 text-center">

                    <div class="h-14 w-14 mx-auto rounded-xl bg-gray-100
                                flex items-center justify-center mb-4">
                        <i class="bx bx-transfer text-3xl text-gray-400"></i>
                    </div>

                    <h3 class="text-sm font-semibold text-gray-700">
                        No stock movements yet
                    </h3>

                    <p class="text-xs text-gray-400 mt-1">
                        Stock movement history will appear here when stock changes.
                    </p>

                </div>

            @endif

        </div>

    </div>

</x-layouts.app>