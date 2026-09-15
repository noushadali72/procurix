<x-layouts.app title="Create Raw Material">

    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900">
            Create Raw Material
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Add a new raw material to your inventory.
        </p>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        <form id="createRawMaterialForm" method="POST" action="{{ route('raw-materials.store') }}">
            @csrf

            <div class="p-6">
                @include('raw_materials._form')
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">

                <a href="{{ route('raw-materials.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Cancel
                </a>

                <button type="submit" id="createRawMaterialBtn"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-60">
                    <i class="bx bx-plus text-lg"></i>
                    <span>Create Raw Material</span>
                </button>

            </div>

        </form>

    </div>


    @push('scripts')
        <script>
            // Cost price: positive whole numbers only (including 0)
            $('#cost_price').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });


            // Stock & minimum stock: positive decimal numbers only
            $('#stock, #minimum_stock').on('input', function() {

                // Remove letters, negative sign and other symbols
                this.value = this.value.replace(/[^0-9.]/g, '');

                // Allow only one decimal point
                const parts = this.value.split('.');

                if (parts.length > 2) {
                    this.value = parts[0] + '.' + parts.slice(1).join('');
                }

            });

            function clearRawMaterialErrors() {
                $(
                    "#nameErr, #skuErr, #unitIdErr, #costPriceErr, #stockErr, #minimumStockErr, #descriptionErr"
                ).text("");
            }


            function showRawMaterialErrors(errors) {
                $("#nameErr").text(errors.name || "");
                $("#skuErr").text(errors.sku || "");
                $("#unitIdErr").text(errors.unit_id || "");
                $("#costPriceErr").text(errors.cost_price || "");
                $("#stockErr").text(errors.stock || "");
                $("#minimumStockErr").text(errors.minimum_stock || "");
                $("#descriptionErr").text(errors.description || "");
            }


            function createRawMaterial() {

                const form = $('#createRawMaterialForm');
                const button = $('#createRawMaterialBtn');

                button.prop('disabled', true);

                $.ajax({
                    url: form.attr('action'),

                    type: 'POST',

                    data: form.serialize(),

                    headers: {
                        'Accept': 'application/json'
                    },

                    success: function(response) {

                        showToast('success', response.message);

                        setTimeout(function() {
                            window.location.href = "{{ route('raw-materials.index') }}";
                        }, 800);

                    },

                    error: function(xhr) {

                        if (xhr.status === 422) {
                            showRawMaterialErrors(xhr.responseJSON.errors);
                            return;
                        }

                        showToast(
                            'error',
                            xhr.responseJSON?.message || 'Unable to create raw material.'
                        );

                    },

                    complete: function() {
                        button.prop('disabled', false);
                    }

                });

            }


            $('#createRawMaterialForm').on('submit', function(e) {

                e.preventDefault();

                clearRawMaterialErrors();

                createRawMaterial();

            });
        </script>
    @endpush

</x-layouts.app>
