<div class="space-y-6">

{{-- Purchase Request Information --}}
<div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

    <h2 class="mb-5 text-lg font-semibold text-slate-800">
        Purchase Request Information
    </h2>

    <div class="grid gap-5 md:grid-cols-2 justify-start">

        {{-- Status --}}
        <div>

            <label
                for="status"
                class="mb-2 block text-sm font-medium text-slate-700"
            >
                Status
                <span class="text-red-500">*</span>
            </label>

            <select
                name="status"
                id="status"
                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                required
            >
                <option value="">
                    Select Status
                </option>

                <option
                    value="active"
                    {{ old('status', $purchaseRequest->status ?? 'active') === 'active' ? 'selected' : '' }}
                >
                    Active
                </option>

                <option
                    value="pending"
                    {{ old('status', $purchaseRequest->status ?? '') === 'pending' ? 'selected' : '' }}
                >
                    Pending
                </option>

                <option
                    value="completed"
                    {{ old('status', $purchaseRequest->status ?? '') === 'completed' ? 'selected' : '' }}
                >
                    Completed
                </option>
            </select>

            <span
                id="statusErr"
                class="mt-1 block text-xs text-red-600"
            ></span>

        </div>


        {{-- Notes --}}
        <div>

            <label
                for="notes"
                class="mb-2 block text-sm font-medium text-slate-700"
            >
                Notes
            </label>

            <textarea
                name="notes"
                id="notes"
                rows="3"
                placeholder="Enter notes..."
                class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
            >{{ old('notes', $purchaseRequest->notes ?? '') }}</textarea>

            <span
                id="notesErr"
                class="mt-1 block text-xs text-red-600"
            ></span>

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


        <button
            type="button"
            id="addItemBtn"
            class="inline-flex items-center gap-2 rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900"
        >
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
                        Unit<sup>*</sup>
                    </th>

                    <th class="w-20 px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">
                        Action
                    </th>

                </tr>

            </thead>


            <tbody id="itemsContainer">

                @if(isset($purchaseRequest) && $purchaseRequest->items->count())

                    {{-- Existing Purchase Request Items --}}
                    @foreach($purchaseRequest->items as $index => $item)

                        <tr class="item-row border-b border-slate-100">

                            {{-- Raw Material --}}
                            <td class="px-4 py-4">

                                <select
                                    name="items[{{ $index }}][raw_material_id]"
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
                                            {{ $item->raw_material_id == $rawMaterial->id ? 'selected' : '' }}
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
                                    name="items[{{ $index }}][qty]"
                                    value="{{ $item->qty }}"
                                    placeholder="Quantity"
                                    class="qty-input w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                    required
                                >

                            </td>


                            {{-- Unit --}}
                            <td class="px-4 py-4">

                                <select
                                    name="items[{{ $index }}][unit_id]"
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
                                            {{ $item->unit_id == $unit->id ? 'selected' : '' }}
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

                    @endforeach

                @else

                    {{-- First New Item --}}
                    <tr class="item-row border-b border-slate-100">

                        {{-- Raw Material --}}
                        <td class="px-4 py-4">

                            <select
                                name="items[0][raw_material_id]"
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
                                name="items[0][qty]"
                                placeholder="Quantity"
                                class="qty-input w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
                                required
                            >

                        </td>


                        {{-- Unit --}}
                        <td class="px-4 py-4">

                            <select
                                name="items[0][unit_id]"
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

                @endif

            </tbody>

        </table>

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
                placeholder="Quantity"
                class="qty-input w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 placeholder-slate-400 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"
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


    // Filter units based on raw material category
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


    // Hide already selected raw materials from other rows
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


    // Raw material changed
    container.on("change", ".raw-material-select", function () {

        const item = $(this).closest(".item-row");

        filterUnits(item);

        // Select raw material's own stock unit by default
        if (!item.find(".unit-select").val()) {

            const unitId = $(this)
                .find("option:selected")
                .data("unit-id");

            item.find(".unit-select").val(unitId);
        }

        updateRawMaterials();
    });


    // Filter units for existing rows
    container.find(".item-row").each(function () {
        filterUnits($(this));
    });


    // Add item
    $("#addItemBtn").on("click", function () {

        let template = $("#itemRowTemplate").html();

        template = template.replaceAll(
            "__INDEX__",
            itemIndex
        );

        container.append(template);

        itemIndex++;

        updateRawMaterials();
    });


    // Remove item
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
    });


    // Quantity: decimal numbers only
    $(document).on("input", ".qty-input", function () {

        this.value = this.value.replace(/[^0-9.]/g, "");

        const parts = this.value.split(".");

        if (parts.length > 2) {
            this.value =
                parts[0] + "." + parts.slice(1).join("");
        }
    });


    // Initial raw material filtering
    updateRawMaterials();

});



</script>

@endpush
