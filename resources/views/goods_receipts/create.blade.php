<x-layouts.app title="Receive Materials">

    <div class="max-w-7xl">

        {{-- Breadcrumb --}}
        <nav class="mb-5 flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.dashboard') }}" class="transition hover:text-slate-800">
                Dashboard
            </a>

            <i class="bx bx-chevron-right text-sm text-slate-400"></i>

            <a href="{{ route('purchase-orders.index') }}" class="transition hover:text-slate-800">
                Purchase Orders
            </a>

            <i class="bx bx-chevron-right text-sm text-slate-400"></i>

            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="transition hover:text-slate-800">
                {{ $purchaseOrder->order_number }}
            </a>

            <i class="bx bx-chevron-right text-sm text-slate-400"></i>

            <span class="font-medium text-slate-700">
                Receive Materials
            </span>
        </nav>


        {{-- Header --}}
        <div
            class="mb-6 flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-end sm:justify-between">

            <div>
                <h1 class="text-xl font-semibold tracking-tight text-slate-900">
                    Receive Materials
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Record materials received against purchase order
                    <span class="font-medium text-slate-700">
                        {{ $purchaseOrder->order_number }}
                    </span>.
                </p>
            </div>

            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}"
                class="inline-flex items-center justify-center gap-2 rounded-md border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">

                <i class="bx bx-arrow-back"></i>
                Purchase Order

            </a>

        </div>


        <form id="goods-receipt-form" method="POST" action="{{ route('goods-receipts.store', $purchaseOrder) }}"
            enctype="multipart/form-data" novalidate>

            @csrf

            @include('goods_receipts._form')


            {{-- Actions --}}
            <div class="mt-6 flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">

                <a href="{{ route('purchase-orders.show', $purchaseOrder) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">

                    <i class="bx bx-x"></i>
                    Cancel

                </a>

                <button type="submit" id="submit-button"
                    class="inline-flex items-center justify-center gap-2 rounded-md bg-slate-800 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900 disabled:cursor-not-allowed disabled:opacity-50">

                    <i class="bx bx-package"></i>

                    Receive Materials

                </button>

            </div>

        </form>

    </div>


    @push('scripts')
        <script>
            $(document).ready(function() {

                $(document).on('input', '.received-qty', function() {

                    this.value = this.value.replace(/[^0-9.]/g, '');

                    const parts = this.value.split('.');

                    if (parts.length > 2) {
                        this.value = parts[0] + '.' + parts.slice(1).join('');
                    }

                });


                const container = $('#receipt-items');


                function filterUnits(item) {

                    const unitSelect = item.find('.receipt-unit');
                    const categoryId = item.data('category-id');

                    unitSelect.find('option').each(function() {

                        const option = $(this);

                        if (!option.val()) {
                            option.show();
                            return;
                        }

                        const optionCategoryId = option.data('category-id');

                        option.toggle(
                            categoryId &&
                            Number(optionCategoryId) === Number(categoryId)
                        );

                    });

                    const selectedOption = unitSelect.find('option:selected');

                    if (
                        selectedOption.val() &&
                        Number(selectedOption.data('category-id')) !== Number(categoryId)
                    ) {
                        unitSelect.val('');
                    }

                }


                container.find('.receipt-item').each(function() {
                    filterUnits($(this));
                });


                $('#goods-receipt-form').on('submit', function(e) {

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

                        success: function(response) {

                            showToast(
                                'success',
                                response.message
                            );

                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 500);

                        },

                        error: function(xhr) {

                            if (xhr.status === 422) {

                                const errors =
                                    xhr.responseJSON?.errors || {};

                                $.each(errors, function(field, messages) {

                                    showToast(
                                        'error',
                                        messages[0]
                                    );

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

                        complete: function() {

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
