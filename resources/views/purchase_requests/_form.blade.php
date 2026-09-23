

    {{-- Request Details --}}
    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-semibold text-slate-900">
                Request Details
            </h2>

            <p class="mt-1 text-xs text-slate-500">
                Enter the basic information for this purchase request.
            </p>
        </div>


        <div class="grid grid-cols-1 gap-x-8 gap-y-5 p-6 md:grid-cols-2 lg:grid-cols-3">

            {{-- Vendor --}}
            <div>
                <div class="mb-1.5 flex items-center justify-between">

                    <label for="vendor_id"
                        class="text-sm font-medium text-slate-700">
                        Vendor <span class="text-red-500">*</span>
                    </label>

                    <button type="button"
                        id="openVendorModal"
                        class="text-xs font-medium text-slate-600 hover:text-slate-900">
                        + Add Vendor
                    </button>

                </div>

                <select name="vendor_id"
                    id="vendor_id"
                    class="h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                    <option value="">
                        Select Vendor
                    </option>

                    @foreach ($vendors as $vendor)

                        <option value="{{ $vendor->id }}"
                            {{ old('vendor_id', $purchaseRequest->vendor_id ?? '') == $vendor->id ? 'selected' : '' }}>

                            {{ $vendor->company_name ?: $vendor->name }}

                        </option>

                    @endforeach

                </select>

                <span id="vendorIdErr"
                    class="mt-1 block text-xs text-red-600"></span>
            </div>


            {{-- Due Date --}}
            <div>

                <label for="due_date"
                    class="mb-1.5 block text-sm font-medium text-slate-700">

                    Due Date

                </label>

                <input type="date"
                    name="due_date"
                    id="due_date"
                    value="{{ old('due_date', $purchaseRequest->due_date ?? '') }}"
                    class="h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

            </div>


            {{-- Delivery Address --}}
            <div>

                <label for="delivery_address"
                    class="mb-1.5 block text-sm font-medium text-slate-700">

                    Delivery Address

                </label>

                <input type="text"
                    name="delivery_address"
                    id="delivery_address"
                    value="{{ old('delivery_address', $purchaseRequest->delivery_address ?? '') }}"
                    placeholder="Enter delivery location"
                    class="h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                <span id="deliveryAddressErr"
                    class="mt-1 block text-xs text-red-600"></span>

            </div>


            {{-- Notes --}}
            <div class="md:col-span-2 lg:col-span-3">

                <label for="notes"
                    class="mb-1.5 block text-sm font-medium text-slate-700">

                    Notes

                </label>

                <textarea name="notes"
                    id="notes"
                    rows="2"
                    placeholder="Add any additional information or instructions..."
                    class="w-full resize-none rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm leading-5 text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">{{ old('notes', $purchaseRequest->notes ?? '') }}</textarea>

                <span id="notesErr"
                    class="mt-1 block text-xs text-red-600"></span>

            </div>

        </div>

    </div>


    {{-- Items Workspace --}}
    <div class="mt-6 overflow-hidden rounded-lg border border-slate-200 bg-white">

        {{-- Items Header --}}
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">

            <div>
                <h2 class="text-base font-semibold text-slate-900">
                    Request Items
                </h2>

                <p class="mt-1 text-xs text-slate-500">
                    Add the raw materials required for this purchase.
                </p>
            </div>


            <button type="button"
                id="addItemBtn"
                class="inline-flex h-9 items-center gap-1.5 rounded-md bg-slate-900 px-3.5 text-sm font-medium text-white transition hover:bg-slate-800">

                <i class="bx bx-plus"></i>

                Add Item

            </button>

        </div>


        {{-- Items Table --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px]">

                <thead>

                    <tr class="border-b border-slate-200 bg-slate-50">

                        <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600">
                            Raw Material
                        </th>

                        <th class="w-40 px-4 py-3 text-left text-xs font-semibold text-slate-600">
                            Quantity
                        </th>

                        <th class="w-44 px-4 py-3 text-left text-xs font-semibold text-slate-600">
                            Unit Cost
                        </th>

                        <th class="w-48 px-4 py-3 text-left text-xs font-semibold text-slate-600">
                            Unit
                        </th>

                        <th class="w-16 px-4 py-3 text-center text-xs font-semibold text-slate-600">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody id="itemsContainer">

                    @if (isset($purchaseRequest) && $purchaseRequest->items->count())

                        @foreach ($purchaseRequest->items as $index => $item)

                            <tr class="item-row border-b border-slate-100 last:border-0 hover:bg-slate-50">

                                {{-- Raw Material --}}
                                <td class="px-6 py-3">

                                    <select name="items[{{ $index }}][raw_material_id]"
                                        class="raw-material-select h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                        required>

                                        <option value="">
                                            Select Raw Material
                                        </option>

                                        @foreach ($rawMaterials as $rawMaterial)

                                            <option value="{{ $rawMaterial->id }}"
                                                data-unit-id="{{ $rawMaterial->unit_id }}"
                                                data-unit-name="{{ $rawMaterial->unit->name }}"
                                                data-category-id="{{ $rawMaterial->unit->unit_category_id }}"
                                                data-cost-price="{{ $rawMaterial->cost_price }}"
                                                {{ $item->raw_material_id == $rawMaterial->id ? 'selected' : '' }}>

                                                {{ $rawMaterial->name }}

                                                @if ($rawMaterial->sku)
                                                    - {{ $rawMaterial->sku }}
                                                @endif

                                            </option>

                                        @endforeach

                                    </select>

                                </td>


                                {{-- Quantity --}}
                                <td class="px-4 py-3">

                                    <input type="text"
                                        inputmode="decimal"
                                        name="items[{{ $index }}][qty]"
                                        value="{{ $item->qty }}"
                                        placeholder="0"
                                        class="qty-input h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                        required>

                                </td>


                                {{-- Unit Cost --}}
                                <td class="px-4 py-3">

                                    <input type="text"
                                        inputmode="decimal"
                                        name="items[{{ $index }}][unit_cost]"
                                        value="{{ $item->unit_cost }}"
                                        placeholder="0.00"
                                        class="cost-input h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                        required>

                                </td>


                                {{-- Unit --}}
                                <td class="px-4 py-3">

                                    <select name="items[{{ $index }}][unit_id]"
                                        class="unit-select h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                        required>

                                        <option value="">
                                            Select Unit
                                        </option>

                                        @foreach ($units as $unit)

                                            <option value="{{ $unit->id }}"
                                                data-category-id="{{ $unit->unit_category_id }}"
                                                {{ $item->unit_id == $unit->id ? 'selected' : '' }}>

                                                {{ $unit->name }}
                                                ({{ $unit->short_name }})

                                            </option>

                                        @endforeach

                                    </select>

                                </td>


                                {{-- Action --}}
                                <td class="px-4 py-3 text-center">

                                    <button type="button"
                                        class="remove-item inline-flex h-9 w-9 items-center justify-center rounded-md text-slate-400 transition hover:bg-red-50 hover:text-red-600"
                                        title="Remove item">

                                        <i class="bx bx-trash text-lg"></i>

                                    </button>

                                </td>

                            </tr>

                        @endforeach

                    @else

                        {{-- First Item --}}
                        <tr class="item-row border-b border-slate-100 hover:bg-slate-50">

                            <td class="px-6 py-3">

                                <select name="items[0][raw_material_id]"
                                    class="raw-material-select h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                    required>

                                    <option value="">
                                        Select Raw Material
                                    </option>

                                    @foreach ($rawMaterials as $rawMaterial)

                                        <option value="{{ $rawMaterial->id }}"
                                            data-unit-id="{{ $rawMaterial->unit_id }}"
                                            data-unit-name="{{ $rawMaterial->unit->name }}"
                                            data-category-id="{{ $rawMaterial->unit->unit_category_id }}"
                                            data-cost-price="{{ $rawMaterial->cost_price }}">

                                            {{ $rawMaterial->name }}

                                            @if ($rawMaterial->sku)
                                                - {{ $rawMaterial->sku }}
                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                            </td>


                            <td class="px-4 py-3">

                                <input type="text"
                                    inputmode="decimal"
                                    name="items[0][qty]"
                                    value="1"
                                    placeholder="0"
                                    class="qty-input h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                    required>

                            </td>


                            <td class="px-4 py-3">

                                <input type="text"
                                    inputmode="decimal"
                                    name="items[0][unit_cost]"
                                    placeholder="0.00"
                                    class="cost-input h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                    required>

                            </td>


                            <td class="px-4 py-3">

                                <select name="items[0][unit_id]"
                                    class="unit-select h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                    required>

                                    <option value="">
                                        Select Unit
                                    </option>

                                    @foreach ($units as $unit)

                                        <option value="{{ $unit->id }}"
                                            data-category-id="{{ $unit->unit_category_id }}">

                                            {{ $unit->name }}
                                            ({{ $unit->short_name }})

                                        </option>

                                    @endforeach

                                </select>

                            </td>


                            <td class="px-4 py-3 text-center">

                                <button type="button"
                                    class="remove-item inline-flex h-9 w-9 items-center justify-center rounded-md text-slate-400 transition hover:bg-red-50 hover:text-red-600"
                                    title="Remove item">

                                    <i class="bx bx-trash text-lg"></i>

                                </button>

                            </td>

                        </tr>

                    @endif

                </tbody>

            </table>

        </div>


       

    </div>


    {{-- Summary --}}
    <div class="mt-4 flex flex-col items-stretch justify-between gap-4 rounded-lg border border-slate-200 bg-white px-6 py-4 sm:flex-row sm:items-center">

        <div class="flex flex-wrap items-center gap-x-8 gap-y-3">

            <div>
                <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">
                    Items
                </p>

                <p id="totalItems"
                    class="mt-0.5 text-lg font-semibold text-slate-900">
                    0
                </p>
            </div>


            <div>
                <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">
                    Quantity
                </p>

                <p id="totalQuantity"
                    class="mt-0.5 text-lg font-semibold text-slate-900">
                    0
                </p>
            </div>

        </div>


        <div class="border-t border-slate-200 pt-3 sm:border-l sm:border-t-0 sm:pl-8 sm:pt-0">

            <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">
                Estimated Total Cost
            </p>

            <p id="totalCost"
                class="mt-0.5 text-xl font-semibold text-slate-900">
                0.00
            </p>

        </div>

    </div>




{{-- Dynamic Item Template --}}
<script type="text/template" id="itemRowTemplate">

    <tr class="item-row border-b border-slate-100 hover:bg-slate-50">

        <td class="px-6 py-3">

            <select
                name="items[__INDEX__][raw_material_id]"
                class="raw-material-select h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                required
            >

                <option value="">
                    Select Raw Material
                </option>

                @foreach($rawMaterials as $rawMaterial)

                    <option
                        value="{{ $rawMaterial->id }}"
                        data-unit-id="{{ $rawMaterial->unit_id }}"
                        data-unit-name="{{ $rawMaterial->unit->name }}"
                        data-category-id="{{ $rawMaterial->unit->unit_category_id }}"
                        data-cost-price="{{ $rawMaterial->cost_price }}"
                    >

                        {{ $rawMaterial->name }}

                        @if($rawMaterial->sku)
                            - {{ $rawMaterial->sku }}
                        @endif

                    </option>

                @endforeach

            </select>

        </td>


        <td class="px-4 py-3">

            <input
                type="text"
                inputmode="decimal"
                name="items[__INDEX__][qty]"
                value="1"
                placeholder="0"
                class="qty-input h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                required
            >

        </td>


        <td class="px-4 py-3">

            <input
                type="text"
                inputmode="decimal"
                name="items[__INDEX__][unit_cost]"
                placeholder="0.00"
                class="cost-input h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                required
            >

        </td>


        <td class="px-4 py-3">

            <select
                name="items[__INDEX__][unit_id]"
                class="unit-select h-10 w-full rounded-md border border-slate-300 bg-white px-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                required
            >

                <option value="">
                    Select Unit
                </option>

                @foreach($units as $unit)

                    <option
                        value="{{ $unit->id }}"
                        data-category-id="{{ $unit->unit_category_id }}"
                    >

                        {{ $unit->name }}
                        ({{ $unit->short_name }})

                    </option>

                @endforeach

            </select>

        </td>


        <td class="px-4 py-3 text-center">

            <button
                type="button"
                class="remove-item inline-flex h-9 w-9 items-center justify-center rounded-md text-slate-400 transition hover:bg-red-50 hover:text-red-600"
                title="Remove item"
            >

                <i class="bx bx-trash text-lg"></i>

            </button>

        </td>

    </tr>

</script>


{{-- =============================================================
    Dynamic Item Template
============================================================= --}}
<script type="text/template" id="itemRowTemplate">

    <tr class="item-row group transition hover:bg-slate-50/70">

        {{-- Raw Material --}}
        <td class="px-6 py-4">

            <select
                name="items[__INDEX__][raw_material_id]"
                class="raw-material-select block h-11 w-full rounded-lg border border-slate-300 bg-white px-3.5 text-sm text-slate-900 shadow-sm outline-none transition hover:border-slate-400 focus:border-slate-700 focus:ring-2 focus:ring-slate-200"
                required
            >

                <option value="">
                    Select Raw Material
                </option>

                @foreach($rawMaterials as $rawMaterial)

                    <option
                        value="{{ $rawMaterial->id }}"
                        data-unit-id="{{ $rawMaterial->unit_id }}"
                        data-unit-name="{{ $rawMaterial->unit->name }}"
                        data-category-id="{{ $rawMaterial->unit->unit_category_id }}"
                        data-cost-price="{{ $rawMaterial->cost_price }}"
                    >

                        {{ $rawMaterial->name }}

                        @if($rawMaterial->sku)
                            - {{ $rawMaterial->sku }}
                        @endif

                    </option>

                @endforeach

            </select>

        </td>


        {{-- Quantity --}}
        <td class="px-4 py-4">

            <input
                type="text"
                inputmode="decimal"
                name="items[__INDEX__][qty]"
                value="1"
                placeholder="Quantity"
                class="qty-input h-11 w-full rounded-lg border border-slate-300 bg-white px-3.5 text-sm text-slate-900 shadow-sm outline-none transition hover:border-slate-400 focus:border-slate-700 focus:ring-2 focus:ring-slate-200"
                required
            >

        </td>


        {{-- Unit Cost --}}
        <td class="px-4 py-4">

            <div class="relative">

                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-xs font-medium text-slate-400">
                    —
                </span>

                <input
                    type="text"
                    inputmode="decimal"
                    name="items[__INDEX__][unit_cost]"
                    placeholder="0.00"
                    class="cost-input h-11 w-full rounded-lg border border-slate-300 bg-white pl-7 pr-3.5 text-sm text-slate-900 shadow-sm outline-none transition hover:border-slate-400 focus:border-slate-700 focus:ring-2 focus:ring-slate-200"
                    required
                >

            </div>

        </td>


        {{-- Unit --}}
        <td class="px-4 py-4">

            <select
                name="items[__INDEX__][unit_id]"
                class="unit-select block h-11 w-full rounded-lg border border-slate-300 bg-white px-3.5 text-sm text-slate-900 shadow-sm outline-none transition hover:border-slate-400 focus:border-slate-700 focus:ring-2 focus:ring-slate-200"
                required
            >

                <option value="">
                    Select Unit
                </option>

                @foreach($units as $unit)

                    <option
                        value="{{ $unit->id }}"
                        data-category-id="{{ $unit->unit_category_id }}"
                    >

                        {{ $unit->name }}
                        ({{ $unit->short_name }})

                    </option>

                @endforeach

            </select>

        </td>


        {{-- Remove --}}
        <td class="px-4 py-4 text-center">

            <button
                type="button"
                class="remove-item inline-flex h-10 w-10 items-center justify-center rounded-lg border border-transparent text-slate-400 transition hover:border-red-100 hover:bg-red-50 hover:text-red-600"
                title="Remove item"
            >

                <i class="bx bx-trash text-lg"></i>

            </button>

        </td>

    </tr>

</script>


@push('scripts')

<script>
$(document).ready(function () {

    const container = $("#itemsContainer");

    let itemIndex = container.find(".item-row").length;


    // -----------------------------------------
    // Filter units based on raw material category
    // -----------------------------------------
    function filterUnits(item) {

        const rawMaterial = item.find(".raw-material-select");
        const unitSelect = item.find(".unit-select");

        const categoryId = rawMaterial
            .find("option:selected")
            .data("category-id");

        unitSelect.find("option").each(function () {

            const option = $(this);

            if (!option.val()) {
                option.show();
                return;
            }

            const optionCategoryId = option.data("category-id");

            option.toggle(
                categoryId &&
                Number(optionCategoryId) === Number(categoryId)
            );
        });


        const selectedOption = unitSelect.find("option:selected");

        if (
            selectedOption.val() &&
            Number(selectedOption.data("category-id")) !== Number(categoryId)
        ) {
            unitSelect.val("");
        }
    }


    // -----------------------------------------
    // Hide already selected raw materials
    // -----------------------------------------
    function updateRawMaterials() {

        const selected = [];

        $(".raw-material-select").each(function () {

            const value = $(this).val();

            if (value) {
                selected.push(value);
            }
        });


        $(".raw-material-select").each(function () {

            const currentValue = $(this).val();

            $(this).find("option").each(function () {

                const option = $(this);

                if (!option.val()) {
                    return;
                }

                option.toggle(
                    option.val() === currentValue ||
                    !selected.includes(option.val())
                );
            });
        });
    }


    // -----------------------------------------
    // Update summary
    // -----------------------------------------
    function updateSummary() {

        let totalItems = 0;
        let totalQuantity = 0;
        let totalCost = 0;


        container.find(".item-row").each(function () {

            const row = $(this);

            const rawMaterial = row
                .find(".raw-material-select")
                .val();

            const qty = parseFloat(
                row.find(".qty-input").val()
            ) || 0;

            const unitCost = parseFloat(
                row.find(".cost-input").val()
            ) || 0;


            if (rawMaterial) {
                totalItems++;
            }


            totalQuantity += qty;
            totalCost += qty * unitCost;
        });


        $("#totalItems").text(totalItems);

        $("#totalQuantity").text(
            totalQuantity.toLocaleString(undefined, {
                maximumFractionDigits: 2
            })
        );

        $("#totalCost").text(
            totalCost.toLocaleString(undefined, {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            })
        );
    }


    // -----------------------------------------
    // Raw material changed
    // -----------------------------------------
    container.on("change", ".raw-material-select", function () {

        const item = $(this).closest(".item-row");
        const selected = $(this).find("option:selected");

        filterUnits(item);

        const unitId = selected.data("unit-id");

        item.find(".unit-select").val(unitId);

        const costPrice = selected.data("cost-price");

        item.find(".cost-input").val(costPrice ?? "");

        updateRawMaterials();
        updateSummary();
    });


    // -----------------------------------------
    // Quantity / Unit Cost changed
    // -----------------------------------------
    container.on(
        "input",
        ".qty-input, .cost-input",
        function () {

            this.value = this.value.replace(/[^0-9.]/g, "");

            const parts = this.value.split(".");

            if (parts.length > 2) {
                this.value =
                    parts[0] +
                    "." +
                    parts.slice(1).join("");
            }

            updateSummary();
        }
    );


    // -----------------------------------------
    // Add item
    // -----------------------------------------
    $("#addItemBtn").on("click", function () {

        let template = $("#itemRowTemplate").html();

        template = template.replaceAll(
            "__INDEX__",
            itemIndex
        );


        container.append(template);

        itemIndex++;

        updateRawMaterials();
        updateSummary();
    });


    // -----------------------------------------
    // Remove item
    // -----------------------------------------
    container.on("click", ".remove-item", function () {

        if (container.find(".item-row").length <= 1) {

            showToast(
                "warning",
                "At least one raw material is required."
            );

            return;
        }


        $(this)
            .closest(".item-row")
            .remove();


        updateRawMaterials();
        updateSummary();
    });


    // -----------------------------------------
    // Initial setup
    // -----------------------------------------

    container.find(".item-row").each(function () {
        filterUnits($(this));
    });

    updateRawMaterials();

    updateSummary();

});
</script>

@endpush