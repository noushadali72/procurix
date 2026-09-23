<div class="space-y-6">

    {{-- Purchase Request Information --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <h2 class="mb-5 text-lg font-semibold text-slate-800">
            Purchase Request Information
        </h2>

        <div class="grid gap-5 md:grid-cols-2 justify-start">

            {{-- Vendor --}}
            <div>
                <div class="mb-2 flex items-center justify-between">
                    <label class="block text-sm font-medium text-gray-700">
                        Vendor <span class="text-red-500">*</span>
                    </label>

                    <button type="button" id="openVendorModal"
                        class="inline-flex cursor-pointer items-center gap-1 text-xs font-medium text-gray-700 transition hover:text-gray-900">
                        <i class="bx bx-plus"></i>
                        Add Vendor
                    </button>
                </div>

                <select name="vendor_id" id="vendor_id"
                    class="block w-full rounded-lg border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                    <option value="">Select Vendor</option>

                    @foreach ($vendors as $vendor)
                        <option value="{{ $vendor->id }}"
                            {{ old('vendor_id', $purchaseRequest->vendor_id ?? '') == $vendor->id ? 'selected' : '' }}>
                            {{ $vendor->company_name ?: $vendor->name }}
                        </option>
                    @endforeach
                </select>

                <span id="vendorIdErr" class="mt-1.5 block text-xs text-red-600"></span>
            </div>


            {{-- Status --}}
            <div>

                <label for="status" class="mb-2 block text-sm font-medium text-slate-700">
                    Status
                    <span class="text-red-500">*</span>
                </label>

                <select name="status" id="status"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                    required>
                    <option value="">
                        Select Status
                    </option>

                    <option value="draft"
                        {{ old('status', $purchaseRequest->status ?? 'draft') === 'draft' ? 'selected' : '' }}>
                        Draft
                    </option>

                    <option value="active"
                        {{ old('status', $purchaseRequest->status ?? 'active') === 'active' ? 'selected' : '' }}>
                        Active
                    </option>


                    <option value="pending"
                        {{ old('status', $purchaseRequest->status ?? '') === 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="completed"
                        {{ old('status', $purchaseRequest->status ?? '') === 'completed' ? 'selected' : '' }}>
                        Completed
                    </option>
                </select>

                <span id="statusErr" class="mt-1 block text-xs text-red-600"></span>

            </div>

            {{-- address --}}
            <div>

                <label for="delivery_address" class="mb-2 block text-sm font-medium text-slate-700">
                    Delivery Address
                </label>

                <textarea name="delivery_address" id="delivery_address" rows="3" placeholder="Enter delivery address..."
                    class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">{{ old('delivery_address', $purchaseRequest->delivery_address ?? '') }}</textarea>
                <span id="deliveryAddressErr" class="mt-1 block text-xs text-red-600"></span>

            </div>


            {{-- Notes --}}
            <div>

                <label for="notes" class="mb-2 block text-sm font-medium text-slate-700">
                    Notes
                </label>

                <textarea name="notes" id="notes" rows="3" placeholder="Enter notes..."
                    class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">{{ old('notes', $purchaseRequest->notes ?? '') }}</textarea>

                <span id="notesErr" class="mt-1 block text-xs text-red-600"></span>

            </div>

        </div>

    </div>


    {{-- Items --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="mb-5 flex items-center justify-between gap-4">

            <div>

                <h2 class="text-lg font-semibold text-slate-800">
                    Raw Materials
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Add raw materials required in this purchase request.
                </p>

            </div>


            <button type="button" id="addItemBtn"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900">
                <i class="bx bx-plus text-lg"></i>

                Add Item
            </button>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full min-w-[850px]">

                <thead>

                    <tr class="border-b border-slate-200 bg-slate-50">

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Raw Material<sup>*</sup>
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Quantity<sup>*</sup>
                        </th>

                         <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Unit Cost<sup>*</sup>
                        </th>

                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Unit<sup>*</sup>
                        </th>

                        <th
                            class="w-20 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody id="itemsContainer">

                    @if (isset($purchaseRequest) && $purchaseRequest->items->count())

                        {{-- Existing Purchase Request Items --}}
                        @foreach ($purchaseRequest->items as $index => $item)
                            <tr class="item-row border-b border-slate-100">

                                {{-- Raw Material --}}
                                <td class="px-4 py-4">

                                    <select name="items[{{ $index }}][raw_material_id]"
                                        class="raw-material-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
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
                                <td class="px-4 py-4">

                                    <input type="text" inputmode="decimal" name="items[{{ $index }}][qty]"
                                        value="{{ $item->qty }}" placeholder="Quantity"
                                        class="qty-input w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                        required>

                                </td>

                                {{-- Unit Cost --}}
                                <td class="px-4 py-4">
                                    <input type="text" inputmode="decimal"
                                        name="items[{{ $index }}][unit_cost]" value="{{ $item->unit_cost }}"
                                        placeholder="Unit cost"
                                        class="cost-input w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                        required>
                                </td>


                                {{-- Unit --}}
                                <td class="px-4 py-4">

                                    <select name="items[{{ $index }}][unit_id]"
                                        class="unit-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
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


                                {{-- Remove --}}
                                <td class="px-4 py-4 text-center">

                                    <button type="button"
                                        class="remove-item inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 transition hover:bg-red-100">
                                        <i class="bx bx-trash text-lg"></i>
                                    </button>

                                </td>

                            </tr>
                        @endforeach
                    @else
                        {{-- First New Item --}}
                        <tr class="item-row border-b border-slate-100">

                            {{-- Raw Material --}}
                            <td class="px-4 py-4">

                                <select name="items[0][raw_material_id]"
                                    class="raw-material-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
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
                                            
                                            >
                                            {{ $rawMaterial->name }}

                                            @if ($rawMaterial->sku)
                                                - {{ $rawMaterial->sku }}
                                            @endif
                                        </option>
                                    @endforeach

                                </select>

                            </td>


                            {{-- Quantity --}}
                            <td class="px-4 py-4">
                                <input type="text" inputmode="decimal" name="items[0][qty]" value="1"
                                    placeholder="Quantity" 
                                    class="qty-input w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                    required>

                            </td>


                            {{-- Unit Cost --}}
                            <td class="px-4 py-4">
                                <input type="text" inputmode="decimal" name="items[0][unit_cost]"
                                    placeholder="Unit cost"
                                    class="cost-input w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                    required>
                            </td>

                            {{-- Unit --}}
                            <td class="px-4 py-4">

                                <select name="items[0][unit_id]"
                                    class="unit-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
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


                            {{-- Remove --}}
                            <td class="px-4 py-4 text-center">

                                <button type="button"
                                    class="remove-item inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 transition hover:bg-red-100">
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
<div class="mt-6 grid gap-4 sm:grid-cols-3">

    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
            Total Items
        </p>
        <p id="totalItems" class="mt-1 text-xl font-semibold text-slate-800">
            0
        </p>
    </div>

    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
            Total Quantity
        </p>
        <p id="totalQuantity" class="mt-1 text-xl font-semibold text-slate-800">
            0
        </p>
    </div>

    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4">
        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
            Estimated Total Cost
        </p>
        <p id="totalCost" class="mt-1 text-xl font-semibold text-slate-800">
            0.00
        </p>
    </div>

</div>

</div>

{{-- Dynamic Item Template --}}

<script type="text/template" id="itemRowTemplate">

    <tr class="item-row border-b border-slate-100">

        {{-- Raw Material --}}
        <td class="px-4 py-4">

            <select
                name="items[__INDEX__][raw_material_id]"
                class="raw-material-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
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
                class="qty-input w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                required
            >

        </td>

        {{-- Unit Cost --}}
        <td class="px-4 py-4">

            <input
                type="text"
                inputmode="decimal"
                name="items[__INDEX__][unit_cost]"
                placeholder="Unit cost"
                class="cost-input w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                required
            >

        </td>


        {{-- Unit --}}
        <td class="px-4 py-4">

            <select
                name="items[__INDEX__][unit_id]"
                class="unit-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
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
                class="remove-item inline-flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 transition hover:bg-red-100"
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


        // Clear invalid selected unit
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


            // Count only rows with a selected raw material
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

        // Set default unit
        const unitId = selected.data("unit-id");
        item.find(".unit-select").val(unitId);

        // Set material cost price
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

            // Allow digits and one decimal point
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

    // Filter units for existing rows
    container.find(".item-row").each(function () {
        filterUnits($(this));
    });


    // Hide already selected raw materials
    updateRawMaterials();


    // Calculate initial summary
    updateSummary();

});

</script>
@endpush
