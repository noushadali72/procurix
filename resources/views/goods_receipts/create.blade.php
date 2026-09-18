<x-layouts.app title="Receive Materials">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                Receive Materials
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Record materials received against purchase order
                {{ $purchaseOrder->order_number }}.
            </p>
        </div>

        <a
            href="{{ route('materials.receive') }}"
            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
        >
            Back
        </a>
    </div>


    <form
        id="goods-receipt-form"
        method="POST"
        action="{{ route('goods-receipts.store', $purchaseOrder) }}"
        enctype="multipart/form-data"
        novalidate
    >
        @csrf

        @include('goods_receipts._form')

        <div class="mt-6 flex justify-end gap-3">

            <a
                href="{{ route('purchase-orders.show', $purchaseOrder) }}"
                class="rounded-lg border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
            >
                Cancel
            </a>

            <button
                type="submit"
                id="submit-button"
                class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
            >
                Receive Materials
            </button>

        </div>
    </form>


    @push('scripts')
        <script>

            $('.received-qty').on('input', function() {

                // Remove letters, negative sign and other symbols
                this.value = this.value.replace(/[^0-9.]/g, '');

                // Allow only one decimal point
                const parts = this.value.split('.');

                if (parts.length > 2) {
                    this.value = parts[0] + '.' + parts.slice(1).join('');
                }

            });
            $(document).ready(function () {

                $('#goods-receipt-form').on('submit', function (e) {
                    e.preventDefault();

                    const $form = $(this);
                    const $button = $('#submit-button');
                    const originalText = $button.text();

                    const formData = new FormData(this);

                    $button
                        .prop('disabled', true)
                        .text('Receiving...');

                    $.ajax({
                        url: $form.attr('action'),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            Accept: 'application/json'
                        },

                        success: function (response) {
                            showToast('success', response.message);

                            setTimeout(function () {
                                window.location.href = response.redirect;
                            }, 500);
                        },

                        error: function (xhr) {
                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON?.errors || {};

                                $.each(errors, function (field, messages) {
                                    showToast('error', messages[0]);
                                    return false;
                                });

                                return;
                            }

                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to create goods receipt.'
                            );
                        },

                        complete: function () {
                            $button
                                .prop('disabled', false)
                                .text(originalText);
                        }
                    });
                });

            });
        </script>
    @endpush

</x-layouts.app>
