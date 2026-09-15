<x-layouts.app title="Edit Manufacturing Formula">

    <div class="mb-6">

        <h2 class="text-xl font-semibold text-gray-900">
            Edit Manufacturing Formula
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Update the manufacturing formula and raw materials.
        </p>

    </div>


    <div class="rounded-xl bg-white p-6 shadow-sm">

        <form
            id="editManufacturingFormulaForm"
            method="POST"
            action="{{ route('manufacturing-formulas.update', $manufacturingFormula) }}"
        >
            @csrf
            @method('PUT')

            @include('manufacturing_formulas._form')

            <div class="mt-6 flex justify-end gap-3">

                <a
                    href="{{ route('manufacturing-formulas.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    id="updateManufacturingFormulaBtn"
                    class="rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-800"
                >
                    Update Formula
                </button>

            </div>

        </form>

    </div>


    @push('scripts')

    <script>

    function updateManufacturingFormula() {

        const form = $("#editManufacturingFormulaForm");
        const button = $("#updateManufacturingFormulaBtn");

        $("#productIdErr").text("");
        $("#nameErr").text("");
        $("#descriptionErr").text("");

        button.prop("disabled", true);

        $.ajax({

            url: form.attr("action"),

            type: "POST",

            data: form.serialize(),

            headers: {
                "Accept": "application/json"
            },

            success: function (response) {

                showToast("success", response.message);

                setTimeout(function () {

                    window.location.href =
                        "{{ route('manufacturing-formulas.index') }}";

                }, 800);
            },

            error: function (xhr) {

                button.prop("disabled", false);

                if (xhr.status === 422) {

                    const errors = xhr.responseJSON?.errors || {};

                    $("#productIdErr")
                        .text(errors.product_id?.[0] || "");

                    $("#nameErr")
                        .text(errors.name?.[0] || "");

                    $("#descriptionErr")
                        .text(errors.description?.[0] || "");


                    const itemError = Object.keys(errors)
                        .find(key => key.startsWith("items."));

                    if (itemError) {

                        showToast(
                            "error",
                            errors[itemError][0]
                        );
                    }

                    return;
                }

                showToast(
                    "error",
                    xhr.responseJSON?.message ||
                    "Unable to update manufacturing formula."
                );
            }
        });
    }


    $(document).on(
        "submit",
        "#editManufacturingFormulaForm",
        function (event) {

            event.preventDefault();

            updateManufacturingFormula();
        }
    );

    </script>

    @endpush

</x-layouts.app>