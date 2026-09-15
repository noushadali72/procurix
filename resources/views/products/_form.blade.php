<div class="grid grid-cols-1 gap-5 md:grid-cols-2">

    {{-- Name --}}
    <div>
        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700">
            Product Name<sup>*</sup>
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $product->name ?? '') }}"
            placeholder="Enter product name"
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
            
        >

        <span id="nameErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>


    {{-- SKU --}}
    <div>
        <label for="sku" class="mb-1.5 block text-sm font-medium text-gray-700">
            SKU
        </label>

        <input
            type="text"
            id="sku"
            name="sku"
            value="{{ old('sku', $product->sku ?? '') }}"
            placeholder="Enter SKU"
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
        >

        <span id="skuErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>


    {{-- Unit --}}
    <div>
        <label for="unit_id" class="mb-1.5 block text-sm font-medium text-gray-700">
            Unit<sup>*</sup>
        </label>

        <select
            id="unit_id"
            name="unit_id"
            
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
        >
            <option value="">Select unit</option>

            @foreach ($units as $unit)
                <option
                    value="{{ $unit->id }}"
                    @selected(old('unit_id', $product->unit_id ?? '') == $unit->id)
                >
                    {{ $unit->name }}
                </option>
            @endforeach
        </select>

        <span id="unitIdErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>


    {{-- Cost Price --}}
    <div>
        <label for="cost_price" class="mb-1.5 block text-sm font-medium text-gray-700">
            Cost Price
        </label>

        <input
            type="number"
            step="0.01"
            min="0"
            id="cost_price"
            name="cost_price"
            value="{{ old('cost_price', $product->cost_price ?? '') }}"
            placeholder="Enter cost price"
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
        >

        <span id="costPriceErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>


    {{-- Sale Price --}}
    <div>
        <label for="sale_price" class="mb-1.5 block text-sm font-medium text-gray-700">
            Sale Price
        </label>

        <input
            type="number"
            step="0.01"
            min="0"
            id="sale_price"
            name="sale_price"
            value="{{ old('sale_price', $product->sale_price ?? '') }}"
            placeholder="Enter sale price"
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
        >

        <span id="salePriceErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>


    {{-- Stock --}}
    <div>
        <label for="stock" class="mb-1.5 block text-sm font-medium text-gray-700">
            Stock<sup>*</sup>
        </label>

        <input
            type="number"
            min="0"
            id="stock"
            name="stock"
            value="{{ old('stock', $product->stock ?? 0) }}"
            placeholder="Enter stock quantity"
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
            
        >

        <span id="stockErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>


    {{-- Minimum Stock --}}
    <div>
        <label for="minimum_stock" class="mb-1.5 block text-sm font-medium text-gray-700">
            Minimum Stock<sup>*</sup>
        </label>

        <input
            type="number"
            min="0"
            id="minimum_stock"
            name="minimum_stock"
            value="{{ old('minimum_stock', $product->minimum_stock ?? 5) }}"
            placeholder="Enter minimum stock level"
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
            
        >

        <span id="minimumStockErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>


    {{-- Description --}}
    <div class="md:col-span-2">
        <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700">
            Description
        </label>

        <textarea
            id="description"
            name="description"
            rows="4"
            placeholder="Enter product description"
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
        >{{ old('description', $product->description ?? '') }}</textarea>

        <span id="descriptionErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>

</div>