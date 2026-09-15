<x-layouts.app title="Manufacture Product">

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    Manufacture Product
                </h1>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Select a product and enter the quantity you want to manufacture.
                </p>
            </div>

            <a
                href="{{ route('manufacturing.records') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
            >
                <i class="bx bx-history text-lg"></i>
                Manufacturing Records
            </a>
        </div>


        {{-- Manufacture Form --}}
        <form
            id="manufacturingForm"
            action="{{ route('manufacturing.manufacture') }}"
            method="POST"
            class="space-y-6"
        >
            @csrf
      
            {{-- Product / Quantity --}}
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <h2 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">
                    Manufacturing Details
                </h2>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                    {{-- Product --}}
                    <div class="md:col-span-1">
                        <label
                            for="product_id"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Product
                        </label>

                        <select
                            id="product_id"
                            name="product_id"
                            class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-gray-500 focus:ring-1 focus:ring-gray-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-gray-400 dark:focus:ring-gray-400"
                        >
                            <option value="">Select product</option>

                            @foreach($products as $product)
                                <option
                                    value="{{ $product->id }}"
                                >
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>

                        <span
                            id="product_idErr"
                            class="mt-1 block text-sm text-red-500"
                        ></span>
                    </div>


                    {{-- Quantity --}}
                    <div>
                        <label
                            for="quantity"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Quantity
                        </label>

                        <input
                            type="text"
                            id="quantity"
                            name="quantity"
                            inputmode="decimal"
                            placeholder="Enter quantity"
                            disabled
                            class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-gray-500 focus:ring-1 focus:ring-gray-500 disabled:cursor-not-allowed disabled:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-gray-400 dark:focus:ring-gray-400 dark:disabled:bg-gray-900"
                        >

                        <span
                            id="quantityErr"
                            class="mt-1 block text-sm text-red-500"
                        ></span>
                    </div>


                    {{-- Unit --}}
                    <div>
                        <label
                            for="unit_id"
                            class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Unit
                        </label>

                        <select
                            id="unit_id"
                            name="unit_id"
                            disabled
                            class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-gray-500 focus:ring-1 focus:ring-gray-500 disabled:cursor-not-allowed disabled:bg-gray-100 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-gray-400 dark:focus:ring-gray-400 dark:disabled:bg-gray-900"
                        >
                            <option value="">Select unit</option>

                            @foreach($units as $unit)
                                <option
                                    value="{{ $unit->id }}"
                                    data-category-id="{{ $unit->unit_category_id }}"
                                >
                                    {{ $unit->name }} ({{ $unit->short_name }})
                                </option>
                            @endforeach
                        </select>

                        <span
                            id="unit_idErr"
                            class="mt-1 block text-sm text-red-500"
                        ></span>
                    </div>

                </div>


                {{-- No Formula --}}
                <div
                    id="noFormula"
                    class="mt-5 hidden rounded-lg border border-amber-300 bg-amber-50 p-4 dark:border-amber-700 dark:bg-amber-900/20"
                >
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        <div>
                            <p class="font-medium text-amber-800 dark:text-amber-300">
                                No manufacturing formula found.
                            </p>

                            <p class="mt-1 text-sm text-amber-700 dark:text-amber-400">
                                Create a formula for this product before manufacturing it.
                            </p>
                        </div>

                        <a
                            id="createFormulaBtn"
                            href="{{ route('manufacturing-formulas.create') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700"
                        >
                            <i class="bx bx-plus text-lg"></i>
                            Create Formula
                        </a>

                    </div>
                </div>

            </div>


            {{-- Required Materials --}}
            <div
                id="materialsSection"
                class="hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800"
            >

                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Required Raw Materials
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Required quantities are calculated from the selected product quantity.
                    </p>
                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-left text-sm">

                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-700/50 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3 font-medium">
                                    Raw Material
                                </th>

                                <th class="px-6 py-3 font-medium">
                                    Required
                                </th>

                                <th class="px-6 py-3 font-medium">
                                    Available Stock
                                </th>

                                <th class="px-6 py-3 font-medium">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody
                            id="materialsBody"
                            class="divide-y divide-gray-200 dark:divide-gray-700"
                        >
                        </tbody>

                    </table>

                </div>


                {{-- Manufacture Button --}}
                <div class="flex justify-end border-t border-gray-200 px-6 py-5 dark:border-gray-700">

                    <button
                        type="submit"
                        id="manufactureBtn"
                        disabled
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <i class="bx bx-cog text-lg"></i>
                        <span id="manufactureBtnText">
                            Manufacture Product
                        </span>
                    </button>

                </div>

            </div>

        </form>

    </div>


    @push('scripts')
        <script>
            $(document).ready(function () {

                const products = @json($products);

                const productSelect = $('#product_id');
                const quantityInput = $('#quantity');
                const unitSelect = $('#unit_id');

                const noFormula = $('#noFormula');
                const createFormulaBtn = $('#createFormulaBtn');

                const materialsSection = $('#materialsSection');
                const materialsBody = $('#materialsBody');

                const manufactureBtn = $('#manufactureBtn');
                const manufactureBtnText = $('#manufactureBtnText');

                let selectedProduct = null;


                /*
                |--------------------------------------------------------------------------
                | Helpers
                |--------------------------------------------------------------------------
                */

                function clearErrors() {
                    $('[id$="Err"]').text('');
                }


                function resetManufacturing() {
                    quantityInput.prop('disabled', true).val('');
                    unitSelect.prop('disabled', true).val('');

                    noFormula.addClass('hidden');
                    materialsSection.addClass('hidden');

                    materialsBody.empty();

                    manufactureBtn
                        .prop('disabled', true)
                        .removeClass('opacity-50');

                    selectedProduct = null;
                }


                function filterUnits(categoryId) {

                    unitSelect.find('option').each(function () {

                        const option = $(this);

                        if (!option.val()) {
                            option.show();
                            return;
                        }

                        const optionCategoryId = option.data('category-id');

                        option.toggle(
                            Number(optionCategoryId) === Number(categoryId)
                        );
                    });
                }


                function convertQuantity(quantity, fromUnit, toUnit) {

                    if (!fromUnit || !toUnit) {
                        return null;
                    }

                    if (
                        Number(fromUnit.unit_category_id) !==
                        Number(toUnit.unit_category_id)
                    ) {
                        return null;
                    }

                    return (
                        Number(quantity) *
                        Number(fromUnit.conversion_factor) /
                        Number(toUnit.conversion_factor)
                    );
                }


                function formatNumber(number) {

                    return Number(number)
                        .toFixed(4)
                        .replace(/\.?0+$/, '');
                }


                function getProductUnit() {

                    if (!selectedProduct || !selectedProduct.unit) {
                        return null;
                    }

                    return selectedProduct.unit;
                }


                /*
                |--------------------------------------------------------------------------
                | Product Change
                |--------------------------------------------------------------------------
                */

                productSelect.on('change', function () {

                    resetManufacturing();

                    const productId = $(this).val();

                    if (!productId) {
                        return;
                    }

                    selectedProduct = products.find(function (product) {
                        return Number(product.id) === Number(productId);
                    });

                    if (!selectedProduct) {
                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Product Has No Formula
                    |--------------------------------------------------------------------------
                    */

                    if (!selectedProduct.manufacturing_formula) {

                        noFormula.removeClass('hidden');

                        createFormulaBtn.attr(
                            'href',
                            "{{ route('manufacturing-formulas.create') }}" +
                            '?product_id=' +
                            selectedProduct.id
                        );

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Product Has Formula
                    |--------------------------------------------------------------------------
                    */

                    quantityInput.prop('disabled', false);
                    unitSelect.prop('disabled', false);

                    const productUnit = getProductUnit();

                    if (productUnit) {

                        filterUnits(productUnit.unit_category_id);

                        unitSelect.val(productUnit.id);
                    }

                    calculateMaterials();
                });


                /*
                |--------------------------------------------------------------------------
                | Quantity Input
                |--------------------------------------------------------------------------
                */

                quantityInput.on('input', function () {

                    let value = this.value;

                    value = value.replace(/[^0-9.]/g, '');

                    const parts = value.split('.');

                    if (parts.length > 2) {
                        value = parts[0] + '.' + parts.slice(1).join('');
                    }

                    this.value = value;

                    calculateMaterials();
                });


                /*
                |--------------------------------------------------------------------------
                | Unit Change
                |--------------------------------------------------------------------------
                */

                unitSelect.on('change', function () {
                    calculateMaterials();
                });


                /*
                |--------------------------------------------------------------------------
                | Calculate Materials
                |--------------------------------------------------------------------------
                */

                function calculateMaterials() {

                    materialsBody.empty();

                    manufactureBtn.prop('disabled', true);

                    if (!selectedProduct) {
                        materialsSection.addClass('hidden');
                        return;
                    }

                    const formula = selectedProduct.manufacturing_formula;

                    if (!formula) {
                        materialsSection.addClass('hidden');
                        return;
                    }

                    const quantity = Number(quantityInput.val());

                    if (!quantity || quantity <= 0) {
                        materialsSection.addClass('hidden');
                        return;
                    }

                    const manufacturingUnitId = unitSelect.val();

                    if (!manufacturingUnitId) {
                        materialsSection.addClass('hidden');
                        return;
                    }

                    const manufacturingUnit = @json($units).find(function (unit) {
                        return Number(unit.id) === Number(manufacturingUnitId);
                    });

                    if (!manufacturingUnit) {
                        materialsSection.addClass('hidden');
                        return;
                    }


                    let allAvailable = true;


                    formula.items.forEach(function (item) {

                        const rawMaterial = item.raw_material;

                        if (!rawMaterial || !rawMaterial.unit) {
                            allAvailable = false;
                            return;
                        }

                        const formulaUnit = item.unit;

                        if (!formulaUnit) {
                            allAvailable = false;
                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Required quantity for manufacturing quantity
                        |--------------------------------------------------------------------------
                        */

                        let requiredQuantity =
                            Number(item.quantity) * quantity;


                        /*
                        |--------------------------------------------------------------------------
                        | Convert manufacturing quantity into product unit
                        |--------------------------------------------------------------------------
                        */

                        const productUnit = getProductUnit();

                        if (
                            productUnit &&
                            Number(productUnit.id) !== Number(manufacturingUnit.id)
                        ) {

                            const convertedProductQuantity = convertQuantity(
                                quantity,
                                manufacturingUnit,
                                productUnit
                            );

                            if (convertedProductQuantity === null) {
                                allAvailable = false;
                                return;
                            }

                            requiredQuantity =
                                Number(item.quantity) *
                                convertedProductQuantity;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Convert required material quantity to stock unit
                        |--------------------------------------------------------------------------
                        */

                        const stockUnit = rawMaterial.unit;

                        const requiredStockQuantity =
                            convertQuantity(
                                requiredQuantity,
                                formulaUnit,
                                stockUnit
                            );


                        if (requiredStockQuantity === null) {
                            allAvailable = false;
                            return;
                        }


                        const availableStock = Number(rawMaterial.stock);

                        const isAvailable =
                            availableStock >= requiredStockQuantity;


                        if (!isAvailable) {
                            allAvailable = false;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Row
                        |--------------------------------------------------------------------------
                        */

                        const rowClass = isAvailable
                            ? 'bg-emerald-50/50 dark:bg-emerald-900/10'
                            : 'bg-white/50 dark:bg-white/10';


                        const statusClass = isAvailable
                            ? 'text-emerald-700 dark:text-emerald-400'
                            : 'text-red-700 dark:text-red-400';


                        const statusIcon = isAvailable
                            ? 'bx-check-circle'
                            : 'bx-x-circle';


                        const statusText = isAvailable
                            ? 'Available'
                            : 'Insufficient';


                        materialsBody.append(`
                            <tr class="${rowClass}">

                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        ${rawMaterial.name}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Stock unit: ${stockUnit.short_name}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        ${formatNumber(requiredStockQuantity)}
                                    </span>

                                    <span class="text-gray-500 dark:text-gray-400">
                                        ${stockUnit.short_name}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        ${formatNumber(availableStock)}
                                    </span>

                                    <span class="text-gray-500 dark:text-gray-400">
                                        ${stockUnit.short_name}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 font-medium ${statusClass}">
                                        <i class="bx ${statusIcon} text-lg"></i>
                                        ${statusText}
                                    </span>
                                </td>

                            </tr>
                        `);

                    });


                    materialsSection.removeClass('hidden');

                    manufactureBtn.prop('disabled', !allAvailable);
                }


                /*
                |--------------------------------------------------------------------------
                | Submit
                |--------------------------------------------------------------------------
                */

                $('#manufacturingForm').on('submit', function (e) {

                    e.preventDefault();

                    clearErrors();

                    if (!selectedProduct) {
                        showToast('error', 'Please select a product.');
                        return;
                    }

                    if (!selectedProduct.manufacturing_formula) {
                        showToast(
                            'warning',
                            'This product does not have a manufacturing formula.'
                        );
                        return;
                    }

                    const quantity = Number(quantityInput.val());

                    if (!quantity || quantity <= 0) {
                        showToast('warning', 'Please enter a valid quantity.');
                        quantityInput.focus();
                        return;
                    }

                    if (!unitSelect.val()) {
                        showToast('warning', 'Please select a unit.');
                        unitSelect.focus();
                        return;
                    }


                    manufactureBtn.prop('disabled', true);
                    manufactureBtnText.text('Manufacturing...');


                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: $(this).serialize(),
                        headers: {
                            Accept: 'application/json'
                        },

                        success: function (response) {

                            showToast(
                                'success',
                                response.message || 'Product manufactured successfully.'
                            );

                            setTimeout(function () {
                                window.location.reload();
                            }, 800);
                        },

                        error: function (xhr) {

                            manufactureBtn.prop('disabled', false);
                            manufactureBtnText.text('Manufacture Product');

                            if (xhr.status === 422) {

                                const errors =
                                    xhr.responseJSON?.errors || {};

                                Object.keys(errors).forEach(function (field) {

                                    const message = errors[field]?.[0];

                                    if ($('#' + field + 'Err').length) {
                                        $('#' + field + 'Err').text(message);
                                    }
                                });

                                showToast(
                                    'error',
                                    xhr.responseJSON?.message ||
                                    'Please check the form.'
                                );

                                return;
                            }

                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to manufacture product.'
                            );
                        }
                    });

                });

            });
        </script>
    @endpush

</x-layouts.app>