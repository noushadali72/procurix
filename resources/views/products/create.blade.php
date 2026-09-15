<x-layouts.app title="Create Product">

    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900">
            Create Product
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Add a new product to your inventory.
        </p>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        <form
            id="createProductForm"
            method="POST"
            action="{{ route('products.store') }}"
        >
            @csrf

            <div class="p-6">
                @include('products._form')
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">

                <a
                    href="{{ route('products.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    id="createProductBtn"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800"
                >
                    <i class="bx bx-plus text-lg"></i>
                    <span>Create Product</span>
                </button>

            </div>

        </form>

    </div>

    @push('scripts')
        <script>
        

            function clearProductErrors() {
                $("#nameErr, #skuErr, #unitIdErr, #costPriceErr, #salePriceErr, #stockErr, #minimumStockErr, #descriptionErr")
                    .text("");
            }

            function showProductErrors(errors) {
                $("#nameErr").text(errors.name || "");
                $("#skuErr").text(errors.sku || "");
                $("#unitIdErr").text(errors.unit_id || "");
                $("#costPriceErr").text(errors.cost_price || "");
                $("#salePriceErr").text(errors.sale_price || "");
                $("#stockErr").text(errors.stock || "");
                $("#minimumStockErr").text(errors.minimum_stock || "");
                $("#descriptionErr").text(errors.description || "");
            }

            function createProduct() {
                const form = $('#createProductForm');
                const button = $('#createProductBtn');

                button.prop('disabled', true);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    headers: {
                        'Accept': 'application/json'
                    },

                    success: function (response) {
                        showToast('success', response.message);

                        setTimeout(function () {
                            window.location.href = "{{ route('products.index') }}";
                        }, 800);
                    },

                    error: function (xhr) {
                        if (xhr.status === 422) {
                            showProductErrors(xhr.responseJSON.errors);
                            return;
                        }

                        showToast(
                            'error',
                            xhr.responseJSON?.message || 'Unable to create product.'
                        );
                    },

                    complete: function () {
                        button.prop('disabled', false);
                    }
                });
            }

            $('#createProductForm').on('submit', function (e) {
                e.preventDefault();

                clearProductErrors();
                createProduct();
            });


            $(document).on(
                'input',
                '#cost_price, #sale_price, #stock, #minimum_stock',
                function () {
                    this.value = this.value.replace(/\D/g, '');
                }
            );
        </script>
    @endpush

</x-layouts.app>