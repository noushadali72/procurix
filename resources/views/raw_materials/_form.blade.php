<div class="grid grid-cols-1 gap-5 md:grid-cols-2">

    {{-- Name --}}
    <div>
        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700">
            Raw Material Name<sup>*</sup>
        </label>

        <input type="text" id="name" name="name" value="{{ old('name', $rawMaterial->name ?? '') }}"
            placeholder="Enter raw material name"
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200">

        <span id="nameErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>


    {{-- SKU --}}
    <div>
        <label for="sku" class="mb-1.5 block text-sm font-medium text-gray-700">
            SKU
        </label>

        <input type="text" id="sku" name="sku" value="{{ old('sku', $rawMaterial->sku ?? '') }}"
            placeholder="Enter SKU"
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200">

        <span id="skuErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>


    {{-- Unit --}}
    <div>
        <label for="unit_id" class="mb-1.5 block text-sm font-medium text-gray-700">
            Unit<sup>*</sup>
        </label>

        <select id="unit_id" name="unit_id"
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
            <option value="">Select unit</option>

            @foreach ($units as $unit)
                <option value="{{ $unit->id }}" @selected(old('unit_id', $rawMaterial->unit_id ?? '') == $unit->id)>
                    {{ $unit->name }}
                </option>
            @endforeach
        </select>

        <span id="unitIdErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>


    {{-- Category --}}
    <div>
        <div class="mb-1.5 flex items-center justify-between">
            <label for="category_id" class="block text-sm font-medium text-gray-700">
                Category
            </label>

            <button type="button" id="openCategoryModal"
                class="inline-flex cursor-pointer items-center gap-1 text-xs font-medium text-gray-700 transition hover:text-gray-900">
                <i class="bx bx-plus"></i>
                Add Category
            </button>
        </div>

        <select id="category_id" name="category_id"
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200">
            <option value="">Select Category</option>

            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $rawMaterial->category_id ?? '') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>

        <span id="categoryIdErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>


    {{-- Image --}}
    <div class="md:col-span-2">
        <label for="image" class="mb-1.5 block text-sm font-medium text-gray-700">
            Raw Material Image
        </label>

        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp"
            class="block w-full cursor-pointer rounded-lg border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:border-r file:border-gray-300 file:bg-gray-50 file:px-4 file:py-2.5 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-100">

        <p class="mt-1.5 text-xs text-gray-500">
            JPG, JPEG, PNG or WEBP. Maximum size 2MB.
        </p>

        <span id="imageErr" class="mt-1.5 block text-xs text-red-600"></span>

        {{-- Image Preview --}}
        <div id="imagePreviewWrapper" class="{{ !empty($rawMaterial->image_path ?? null) ? '' : 'hidden' }} mt-4">
            <div class="relative h-32 w-32 overflow-hidden rounded-xl border border-gray-200 bg-gray-50">
                <img id="imagePreview"
                    src="{{ !empty($rawMaterial->image_path ?? null) ? asset('storage/' . $rawMaterial->image_path) : '' }}"
                    alt="Raw material image preview" class="h-full w-full object-cover">
            </div>
        </div>
    </div>


    {{-- Cost Price --}}
    <div>
        <label for="cost_price" class="mb-1.5 block text-sm font-medium text-gray-700">
            Cost Price<sup>*</sup>
        </label>

        <input type="text" inputmode="numeric" id="cost_price" name="cost_price"
            value="{{ old('cost_price', $rawMaterial->cost_price ?? '') }}" placeholder="Enter cost price"
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200">

        <span id="costPriceErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>


    {{-- Stock --}}
    <div>
        <label for="stock" class="mb-1.5 block text-sm font-medium text-gray-700">
            Stock<sup>*</sup>
        </label>

        <input type="text" inputmode="decimal" id="stock" name="stock"
            value="{{ old('stock', $rawMaterial->stock ?? 0) }}" placeholder="Enter stock quantity"
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200">

        <span id="stockErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>


    {{-- Minimum Stock --}}
    <div>
        <label for="minimum_stock" class="mb-1.5 block text-sm font-medium text-gray-700">
            Minimum Stock<sup>*</sup>
        </label>

        <input type="text" inputmode="decimal" id="minimum_stock" name="minimum_stock"
            value="{{ old('minimum_stock', $rawMaterial->minimum_stock ?? 5) }}"
            placeholder="Enter minimum stock level"
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200">

        <span id="minimumStockErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>


    {{-- Description --}}
    <div class="md:col-span-2">
        <label for="description" class="mb-1.5 block text-sm font-medium text-gray-700">
            Description
        </label>

        <textarea id="description" name="description" rows="4" placeholder="Enter raw material description"
            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200">{{ old('description', $rawMaterial->description ?? '') }}</textarea>

        <span id="descriptionErr" class="mt-1.5 block text-xs text-red-600"></span>
    </div>

</div>

@push('scripts')
    <script>
        $('#image').on('change', function(event) {

            const file = event.target.files[0];

            if (!file) {
                return;
            }

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/jpg',
                'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {

                $('#imageErr').text('Please select a valid image file.');

                $(this).val('');

                return;
            }

            $('#imageErr').text('');

            const reader = new FileReader();

            reader.onload = function(e) {

                $('#imagePreview')
                    .attr('src', e.target.result);

                $('#imagePreviewWrapper')
                    .removeClass('hidden');
            };

            reader.readAsDataURL(file);
        });
    </script>
@endpush
