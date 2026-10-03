<x-layouts.app title="Raw Material Details">

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                    <a href="{{ route('raw-materials.index') }}" class="hover:text-gray-700">
                        Raw Materials
                    </a>

                    <i class="bx bx-chevron-right text-lg"></i>

                    <span class="text-gray-700">
                        {{ $rawMaterial->name }}
                    </span>
                </div>

                <h1 class="text-2xl font-semibold text-gray-900">
                    Raw Material Details
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    View raw material information and stock movement history.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('raw-materials.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i class="bx bx-arrow-back"></i>
                    Back
                </a>

                <a href="{{ route('raw-materials.edit', $rawMaterial) }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-800">
                    <i class="bx bx-edit"></i>
                    Edit Material
                </a>
            </div>
        </div>


        {{-- Main Information --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

            {{-- Image --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <div class="aspect-square overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                    @if ($rawMaterial->image_path)
                        <img src="{{ asset('storage/' . $rawMaterial->image_path) }}" alt="{{ $rawMaterial->name }}"
                            class="h-full w-full object-contain">
                    @else
                        <div class="flex h-full items-center justify-center">
                            <div class="text-center">
                                <i class="bx bx-package text-6xl text-gray-300"></i>

                                <p class="mt-2 text-sm text-gray-400">
                                    No image available
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-4">
                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ $rawMaterial->name }}
                    </h2>

                    @if ($rawMaterial->sku)
                        <p class="mt-1 text-sm text-gray-500">
                            SKU: {{ $rawMaterial->sku }}
                        </p>
                    @endif
                </div>
            </div>


            {{-- Details --}}
            <div class="xl:col-span-2 rounded-xl border border-gray-200 bg-white">

                <div class="border-b border-gray-200 px-5 py-4">
                    <h2 class="font-semibold text-gray-900">
                        Material Information
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Basic information and pricing details.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-x-8 gap-y-6 p-5 sm:grid-cols-2">

                    {{-- Name --}}
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Name
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900">
                            {{ $rawMaterial->name }}
                        </p>
                    </div>

                    {{-- SKU --}}
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            SKU
                        </p>

                        <p class="mt-1 text-sm text-gray-900">
                            {{ $rawMaterial->sku ?: '—' }}
                        </p>
                    </div>

                    {{-- Category --}}
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Category
                        </p>

                        <p class="mt-1 text-sm text-gray-900">
                            {{ $rawMaterial->category?->name ?? '—' }}
                        </p>
                    </div>

                    {{-- Unit --}}
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Unit
                        </p>

                        <p class="mt-1 text-sm text-gray-900">
                            {{ $rawMaterial->unit?->name ?? '—' }}
                        </p>
                    </div>

                    {{-- Cost Price --}}
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Cost Price
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ number_format($rawMaterial->cost_price, 2) }}
                            <span class="font-normal text-gray-500">
                                / {{ $rawMaterial->unit?->name ?? 'unit' }}
                            </span>
                        </p>
                    </div>

                    {{-- Current Stock --}}
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Current Stock
                        </p>

                        @php
                            if ($rawMaterial->stock <= 0) {
                                $stockStatus = 'Out of Stock';
                                $stockClass = 'bg-red-50 text-red-700';
                            } elseif ($rawMaterial->stock <= $rawMaterial->minimum_stock) {
                                $stockStatus = 'Low Stock';
                                $stockClass = 'bg-amber-50 text-amber-700';
                            } else {
                                $stockStatus = 'In Stock';
                                $stockClass = 'bg-green-50 text-green-700';
                            }
                        @endphp

                        <div class="mt-1 flex flex-wrap items-center gap-2">
                            <span class="text-sm font-semibold text-gray-900">
                                {{ number_format($rawMaterial->stock, 3) }}
                                {{ $rawMaterial->unit?->name ?? '' }}
                            </span>

                            <span
                                class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium {{ $stockClass }}">
                                {{ $stockStatus }}
                            </span>
                        </div>
                    </div>

                    {{-- Minimum Stock --}}
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Minimum Stock
                        </p>

                        <p class="mt-1 text-sm font-medium text-gray-900">
                            {{ number_format($rawMaterial->minimum_stock, 3) }}
                            {{ $rawMaterial->unit?->name ?? '' }}
                        </p>
                    </div>

                    {{-- Created --}}
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Created
                        </p>

                        <p class="mt-1 text-sm text-gray-900">
                            {{ $rawMaterial->created_at?->format('d M Y, h:i A') ?? '—' }}
                        </p>
                    </div>

                </div>

                {{-- Description --}}
                @if ($rawMaterial->description)
                    <div class="border-t border-gray-200 px-5 py-5">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Description
                        </p>

                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-700">
                            {{ $rawMaterial->description }}
                        </p>
                    </div>
                @endif

            </div>
        </div>


        {{-- Stock Summary --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            {{-- Current Stock --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Current Stock
                        </p>

                        <p class="mt-2 text-2xl font-semibold text-gray-900">
                            {{ number_format($rawMaterial->stock, 3) }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            {{ $rawMaterial->unit?->name ?? 'Unit' }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-gray-100">
                        <i class="bx bx-package text-xl text-gray-600"></i>
                    </div>
                </div>
            </div>


            {{-- Minimum Stock --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Minimum Stock
                        </p>

                        <p class="mt-2 text-2xl font-semibold text-gray-900">
                            {{ number_format($rawMaterial->minimum_stock, 3) }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            Reorder level
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-gray-100">
                        <i class="bx bx-error-circle text-xl text-gray-600"></i>
                    </div>
                </div>
            </div>


            {{-- Movements --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Stock Movements
                        </p>

                        <p class="mt-2 text-2xl font-semibold text-gray-900">
                            {{ $rawMaterial->movements->count() }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            Recorded movements
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-gray-100">
                        <i class="bx bx-transfer-alt text-xl text-gray-600"></i>
                    </div>
                </div>
            </div>

        </div>


        {{-- Stock Movement History --}}
        <div class="rounded-xl border border-gray-200 bg-white">

            <div class="border-b border-gray-200 px-5 py-4">
                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-900">
                            Stock Movement History
                        </h2>

                        <p class="text-sm text-gray-500">
                            Complete inventory movement history for this raw material.
                        </p>
                    </div>

                    <span class="text-sm text-gray-500">
                        {{ $rawMaterial->movements->count() }} movements
                    </span>
                </div>
            </div>


            @if ($rawMaterial->movements->isNotEmpty())

                {{-- Desktop --}}
                <div class="hidden overflow-x-auto md:block">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Date
                                </th>

                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Movement
                                </th>

                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Direction
                                </th>

                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Quantity
                                </th>

                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Reference
                                </th>

                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Created By
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100 bg-white">

                            @foreach ($rawMaterial->movements as $movement)
                                @php
                                    $isIn = $movement->direction === 'in';

                                    $movementLabel = ucwords(str_replace('_', ' ', $movement->type ?? 'movement'));

                                    $directionClass = $isIn ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700';

                                    $directionLabel = $isIn ? 'IN' : 'OUT';
                                    $quantityPrefix = $isIn ? '+' : '-';

                                    $reference = null;

                                    if ($movement->reference_type && $movement->reference_id) {
                                        $reference =
                                            class_basename($movement->reference_type) . ' #' . $movement->reference_id;
                                    }
                                @endphp

                                <tr class="hover:bg-gray-50">

                                    {{-- Date --}}
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">
                                        <div>
                                            {{ $movement->created_at?->format('d M Y') }}
                                        </div>

                                        <div class="mt-0.5 text-xs text-gray-400">
                                            {{ $movement->created_at?->format('h:i A') }}
                                        </div>
                                    </td>

                                    {{-- Movement --}}
                                    <td class="px-5 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $movementLabel }}
                                        </div>

                                        @if ($movement->notes)
                                            <div class="mt-1 max-w-xs truncate text-xs text-gray-400"
                                                title="{{ $movement->notes }}">
                                                {{ $movement->notes }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Direction --}}
                                    <td class="px-5 py-4">
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold {{ $directionClass }}">
                                            <i class="bx {{ $isIn ? 'bx-down-arrow-alt' : 'bx-up-arrow-alt' }}"></i>
                                            {{ $directionLabel }}
                                        </span>
                                    </td>

                                    {{-- Quantity --}}
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span
                                            class="text-sm font-semibold {{ $isIn ? 'text-green-700' : 'text-red-700' }}">
                                            {{ $quantityPrefix }}{{ number_format($movement->qty, 3) }}
                                        </span>

                                        <span class="ml-1 text-xs text-gray-400">
                                            {{ $movement->unit?->name ?? ($rawMaterial->unit?->name ?? '') }}
                                        </span>
                                    </td>

                                    {{-- Reference --}}
                                    <td class="px-5 py-4 text-sm text-gray-600">
                                        {{ $reference ?? 'Manual' }}
                                    </td>

                                    {{-- Created By --}}
                                    <td class="px-5 py-4 text-sm text-gray-600">
                                        {{ $movement->creator?->name ?? 'System' }}
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>


                {{-- Mobile --}}
                <div class="divide-y divide-gray-100 md:hidden">

                    @foreach ($rawMaterial->movements as $movement)
                        @php
                            $isIn = $movement->direction === 'in';

                            $movementLabel = ucwords(str_replace('_', ' ', $movement->type ?? 'movement'));

                            $directionClass = $isIn ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700';

                            $directionLabel = $isIn ? 'IN' : 'OUT';

                            $quantityPrefix = $isIn ? '+' : '-';

                            $reference = null;

                            if ($movement->reference_type && $movement->reference_id) {
                                $reference = class_basename($movement->reference_type) . ' #' . $movement->reference_id;
                            }
                        @endphp

                        <div class="p-5">

                            <div class="flex items-start justify-between gap-4">

                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $movementLabel }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ $movement->created_at?->format('d M Y, h:i A') }}
                                    </p>
                                </div>

                                <span
                                    class="inline-flex shrink-0 items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold {{ $directionClass }}">
                                    <i class="bx {{ $isIn ? 'bx-down-arrow-alt' : 'bx-up-arrow-alt' }}"></i>
                                    {{ $directionLabel }}
                                </span>

                            </div>


                            <div class="mt-4 grid grid-cols-2 gap-4">

                                <div>
                                    <p class="text-xs text-gray-400">
                                        Quantity
                                    </p>

                                    <p
                                        class="mt-1 text-sm font-semibold {{ $isIn ? 'text-green-700' : 'text-red-700' }}">
                                        {{ $quantityPrefix }}{{ number_format($movement->qty, 3) }}
                                        {{ $movement->unit?->name ?? ($rawMaterial->unit?->name ?? '') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-400">
                                        Created By
                                    </p>

                                    <p class="mt-1 text-sm text-gray-700">
                                        {{ $movement->creator?->name ?? 'System' }}
                                    </p>
                                </div>

                                <div class="col-span-2">
                                    <p class="text-xs text-gray-400">
                                        Reference
                                    </p>

                                    <p class="mt-1 text-sm text-gray-700">
                                        {{ $reference ?? 'Manual' }}
                                    </p>
                                </div>

                            </div>


                            @if ($movement->notes)
                                <div class="mt-4 rounded-lg bg-gray-50 p-3">
                                    <p class="text-xs font-medium text-gray-500">
                                        Notes
                                    </p>

                                    <p class="mt-1 text-sm leading-5 text-gray-600">
                                        {{ $movement->notes }}
                                    </p>
                                </div>
                            @endif

                        </div>
                    @endforeach

                </div>
            @else
                {{-- Empty State --}}
                <div class="px-6 py-14 text-center">

                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                        <i class="bx bx-transfer-alt text-2xl text-gray-400"></i>
                    </div>

                    <h3 class="mt-4 text-sm font-semibold text-gray-900">
                        No stock movements
                    </h3>

                    <p class="mx-auto mt-1 max-w-sm text-sm text-gray-500">
                        There are no stock movements recorded for this raw material yet.
                    </p>

                </div>

            @endif

        </div>

    </div>

</x-layouts.app>
