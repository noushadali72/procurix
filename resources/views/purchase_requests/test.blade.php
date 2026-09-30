@push('scripts')
<script>
$(document).ready(function () {

    const container = $("#itemsContainer");
    let itemIndex = container.find(".item-row").length;

    // Helper to initialize Select2 on raw material select elements
    function initSelect2(element) {
        $(element).select2({
            placeholder: "Select Raw Material",
            allowClear: true,
            width: '100%'
        });
    }

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
            const $select = $(this);
            const currentValue = $select.val();

            $select.find("option").each(function () {
                const option = $(this);
                if (!option.val()) return;

                const isSelected = option.val() === currentValue;
                const isTaken = selected.includes(option.val());

                // Disable options already chosen in other dropdowns
                option.prop("disabled", !isSelected && isTaken);
            });

            // Re-render Select2 options state
            $select.trigger("change.select2");
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

            const rawMaterial = row.find(".raw-material-select").val();
            const qty = parseFloat(row.find(".qty-input").val()) || 0;
            const unitCost = parseFloat(row.find(".cost-input").val()) || 0;

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
    container.on("input", ".qty-input, .cost-input", function () {
        this.value = this.value.replace(/[^0-9.]/g, "");

        const parts = this.value.split(".");
        if (parts.length > 2) {
            this.value = parts[0] + "." + parts.slice(1).join("");
        }

        updateSummary();
    });

    // -----------------------------------------
    // Add item
    // -----------------------------------------
    $("#addItemBtn").on("click", function () {
        let templateHtml = $("#itemRowTemplate").html();

        templateHtml = templateHtml.replaceAll("__INDEX__", itemIndex);

        // 1. Append HTML to DOM first
        const $newRow = $(templateHtml);
        container.append($newRow);

        // 2. Initialize Select2 on the newly inserted row's select
        initSelect2($newRow.find(".raw-material-select"));

        itemIndex++;

        updateRawMaterials();
        updateSummary();
    });

    // -----------------------------------------
    // Remove item
    // -----------------------------------------
    container.on("click", ".remove-item", function () {
        if (container.find(".item-row").length <= 1) {
            if (typeof showToast === "function") {
                showToast("warning", "At least one raw material is required.");
            }
            return;
        }

        const $row = $(this).closest(".item-row");

        // Destroy Select2 instance before removing DOM node to prevent memory leaks
        $row.find(".raw-material-select").select2("destroy");
        $row.remove();

        updateRawMaterials();
        updateSummary();
    });

    // -----------------------------------------
    // Initial setup on page load
    // -----------------------------------------
    container.find(".item-row").each(function () {
        const $row = $(this);
        initSelect2($row.find(".raw-material-select"));
        filterUnits($row);
    });

    updateRawMaterials();
    updateSummary();

});
</script>
@endpush