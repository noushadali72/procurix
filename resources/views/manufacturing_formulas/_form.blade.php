{{-- Formula Information --}}

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    {{-- Product --}}
    <div>

        <label for="product_id" class="mb-1 block text-sm font-medium text-gray-700">
            Product
        </label>

        <select
            id="product_id"
            name="product_id"
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
        >
            <option value="">Select Product</option>

            @foreach ($products as $product)
                <option
                    value="{{ $product->id }}"
                    @selected(old('product_id', $manufacturingFormula->product_id ?? '') == $product->id)
                >
                    {{ $product->name }}
                </option>
            @endforeach

        </select>

        <span id="productIdErr" class="mt-1 block text-sm text-red-600"></span>

    </div>


    {{-- Name --}}
    <div>

        <label for="name" class="mb-1 block text-sm font-medium text-gray-700">
            Formula Name
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $manufacturingFormula->name ?? '') }}"
            placeholder="Enter formula name"
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
        >

        <span id="nameErr" class="mt-1 block text-sm text-red-600"></span>

    </div>


    {{-- Description --}}
    <div class="md:col-span-2">

        <label for="description" class="mb-1 block text-sm font-medium text-gray-700">
            Description
        </label>

        <textarea
            id="description"
            name="description"
            rows="3"
            placeholder="Enter formula description (optional)"
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
        >{{ old('description', $manufacturingFormula->description ?? '') }}</textarea>

        <span id="descriptionErr" class="mt-1 block text-sm text-red-600"></span>

    </div>

</div>


{{-- Raw Materials --}}

<div class="mt-8 border-t pt-6">

    <div class="mb-4 flex items-center justify-between">

        <div>
            <h3 class="text-base font-semibold text-gray-900">
                Raw Materials
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Add the raw materials and quantities required for this formula.
            </p>
        </div>

        <button
            type="button"
            id="add-item"
            class="rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-800"
        >
            + Add Material
        </button>

    </div>


    @php

        $oldItems = old('items');

        if ($oldItems) {

            $formItems = $oldItems;

        } elseif (isset($manufacturingFormula)) {

            $formItems = $manufacturingFormula->items
                ->map(function ($item) {
                    return [
                        'raw_material_id' => $item->raw_material_id,
                        'quantity' => $item->quantity,
                        'unit_id' => $item->unit_id,
                    ];
                })
                ->toArray();

        } else {

            $formItems = [
                [
                    'raw_material_id' => '',
                    'quantity' => 1,
                    'unit_id' => '',
                ],
            ];

        }

    @endphp


    <div id="items-container" class="space-y-3">

        @foreach ($formItems as $index => $item)

            @php
                $selectedRawMaterial = $rawMaterials->firstWhere(
                    'id',
                    $item['raw_material_id'] ?? null
                );
            @endphp

            <div class="formula-item rounded-lg border border-gray-200 bg-gray-50 p-4">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">

                    {{-- Raw Material --}}
                    <div class="md:col-span-5">

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Raw Material
                        </label>

                        <select
                            name="items[{{ $index }}][raw_material_id]"
                            class="raw-material w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                        >

                            <option value="">
                                Select Raw Material
                            </option>

                            @foreach ($rawMaterials as $rawMaterial)

                                <option
                                    value="{{ $rawMaterial->id }}"
                                    data-unit-id="{{ $rawMaterial->unit_id }}"
                                    data-unit-name="{{ $rawMaterial->unit->name }}"
                                    data-category-id="{{ $rawMaterial->unit->unit_category_id }}"
                                    @selected(($item['raw_material_id'] ?? '') == $rawMaterial->id)
                                >
                                    {{ $rawMaterial->name }}
                                    ({{ $rawMaterial->unit->short_name }})
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Quantity --}}
                    <div class="md:col-span-3">

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Quantity
                        </label>

                        <input
                            type="number"
                            name="items[{{ $index }}][quantity]"
                            value="{{ $item['quantity'] ?? 1 }}"
                            min="0"
                            step="any"
                            class="w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                        >

                    </div>


                    {{-- Unit --}}
                    <div class="md:col-span-3">

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Unit
                        </label>

                        <select
                            name="items[{{ $index }}][unit_id]"
                            class="unit-select w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                        >

                            <option value="">
                                Select Unit
                            </option>

                            @foreach ($units as $unit)

                                <option
                                    value="{{ $unit->id }}"
                                    data-category-id="{{ $unit->unit_category_id }}"
                                    @selected(($item['unit_id'] ?? '') == $unit->id)
                                >
                                    {{ $unit->name }} ({{ $unit->short_name }})
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Remove --}}
                    <div class="flex items-end justify-end md:col-span-1">

                        <button
                            type="button"
                            class="remove-item rounded-lg border border-red-200 px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50"
                        >
                            Remove
                        </button>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>


{{-- Dynamic Item Template --}}

<template id="item-template">

    <div class="formula-item rounded-lg border border-gray-200 bg-gray-50 p-4">

        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">

            {{-- Raw Material --}}
            <div class="md:col-span-5">

                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Raw Material
                </label>

                <select
                    name="items[INDEX][raw_material_id]"
                    class="raw-material w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                >

                    <option value="">
                        Select Raw Material
                    </option>

                    @foreach ($rawMaterials as $rawMaterial)

                        <option
                            value="{{ $rawMaterial->id }}"
                            data-unit-id="{{ $rawMaterial->unit_id }}"
                            data-unit-name="{{ $rawMaterial->unit->name }}"
                            data-category-id="{{ $rawMaterial->unit->unit_category_id }}"
                        >
                            {{ $rawMaterial->name }}
                            ({{ $rawMaterial->unit->short_name }})
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Quantity --}}
            <div class="md:col-span-3">

                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Quantity
                </label>

                <input
                    type="number"
                    name="items[INDEX][quantity]"
                    value="1"
                    min="0"
                    step="any"
                    class="w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                >

            </div>


            {{-- Unit --}}
            <div class="md:col-span-3">

                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Unit
                </label>

                <select
                    name="items[INDEX][unit_id]"
                    class="unit-select w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                >

                    <option value="">
                        Select Unit
                    </option>

                    @foreach ($units as $unit)

                        <option
                            value="{{ $unit->id }}"
                            data-category-id="{{ $unit->unit_category_id }}"
                        >
                            {{ $unit->name }} ({{ $unit->short_name }})
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Remove --}}
            <div class="flex items-end justify-end md:col-span-1">

                <button
                    type="button"
                    class="remove-item rounded-lg border border-red-200 px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50"
                >
                    Remove
                </button>

            </div>

        </div>

    </div>

</template>


@push('scripts')

<script>
$(document).ready(function () {

    const container = $("#items-container");
    const template = $("#item-template");
    const addButton = $("#add-item");

    let index = {{ count($formItems) }};


    // Filter units according to raw material category
    function filterUnits(item) {

        const rawMaterial = item.find(".raw-material");
        const unitSelect = item.find(".unit-select");

        const categoryId = rawMaterial
            .find("option:selected")
            .data("category-id");

        const selectedUnit = unitSelect.val();

        unitSelect.find("option").each(function () {

            const option = $(this);

            if (!option.val()) {
                option.show();
                return;
            }

            const optionCategoryId = option.data("category-id");

            option.toggle(
                categoryId && Number(optionCategoryId) === Number(categoryId)
            );
        });

        // Clear selected unit if it doesn't belong to the category
        const selectedOption = unitSelect.find("option:selected");

        if (
            selectedOption.val() &&
            Number(selectedOption.data("category-id")) !== Number(categoryId)
        ) {
            unitSelect.val("");
        }
    }


    // Set units when raw material changes
    container.on("change", ".raw-material", function () {

        const item = $(this).closest(".formula-item");

        filterUnits(item);

        // If no unit is selected, use raw material's own unit
        if (!item.find(".unit-select").val()) {

            const rawMaterialOption = $(this)
                .find("option:selected");

            const rawMaterialUnitId = rawMaterialOption.data("unit-id");

            item.find(".unit-select")
                .val(rawMaterialUnitId);
        }

    });


    // Filter units on existing items
    container.find(".formula-item").each(function () {

        filterUnits($(this));

    });


    // Add material
    addButton.on("click", function () {

        const html = template
            .html()
            .replaceAll("INDEX", index);

        container.append(html);

        index++;
    });


    // Remove material
    container.on("click", ".remove-item", function () {

        if (container.find(".formula-item").length <= 1) {

            showToast(
                "warning",
                "At least one raw material is required."
            );

            return;
        }

        $(this).closest(".formula-item").remove();
    });

});
</script>

@endpush