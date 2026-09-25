<x-layouts.app title="Receive Materials">

    {{-- ====================================================== --}}
    {{-- BREADCRUMB --}}
    {{-- ====================================================== --}}

    <nav class="mb-5 flex items-center gap-2 text-xs text-gray-500">

        <a href="{{ route('admin.dashboard') }}" class="transition hover:text-gray-900">
            Dashboard
        </a>

        <i class="bx bx-chevron-right text-sm text-gray-400"></i>

        <a href="{{ route('purchase-orders.index') }}" class="transition hover:text-gray-900">
            Purchase Orders
        </a>

        <i class="bx bx-chevron-right text-sm text-gray-400"></i>

        <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="transition hover:text-gray-900">

            {{ $purchaseOrder->order_number }}
            
        </a>

        <i class="bx bx-chevron-right text-sm text-gray-400"></i>

        <span class="font-medium text-gray-700">
            Receive Materials
        </span>

    </nav>


    {{-- ====================================================== --}}
    {{-- HEADER --}}
    {{-- ====================================================== --}}

    <div
        class="mb-6 flex flex-col gap-4 border-b border-gray-200 pb-5
               lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="flex flex-wrap items-center gap-3">

                <h1 class="text-xl font-semibold tracking-tight text-gray-900">
                    Receive Materials
                </h1>

                <span
                    class="inline-flex items-center gap-1.5 rounded-full
                           border border-blue-200 bg-blue-50
                           px-2.5 py-1 text-xs font-medium text-blue-700">

                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>

                    {{ ucwords(str_replace('_', ' ', $purchaseOrder->status)) }}

                </span>

            </div>

            <p class="mt-1 text-sm text-gray-500">

                Record materials received against

                <span class="font-medium text-gray-700">
                    {{ $purchaseOrder->order_number }}
                </span>.

            </p>

        </div>


        <a href="{{ route('purchase-orders.show', $purchaseOrder) }}"
            class="inline-flex items-center justify-center gap-2
                   rounded-lg border border-gray-300 bg-white
                   px-3.5 py-2 text-sm font-medium text-gray-700
                   transition hover:bg-gray-50">

            <i class="bx bx-arrow-back"></i>

            Purchase Order

            
        </a>

    </div>


    {{-- ====================================================== --}}
    {{-- MAIN GRID --}}
    {{-- ====================================================== --}}

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">


        {{-- ================================================== --}}
        {{-- LEFT - RECEIVING FORM --}}
        {{-- ================================================== --}}

        <main class="xl:col-span-9">

            <form id="goods-receipt-form" method="POST" action="{{ route('goods-receipts.store', $purchaseOrder) }}"
                enctype="multipart/form-data" novalidate>

                @csrf


                {{-- Existing form --}}
                @include('goods_receipts._form')


                {{-- ================================================== --}}
                {{-- ACTIONS --}}
                {{-- ================================================== --}}

                <div
                    class="mt-6 flex flex-col gap-4 rounded-xl
                           border border-gray-200 bg-white p-4
                           sm:flex-row sm:items-center
                           sm:justify-between">

                    <div class="flex items-start gap-3">

                        <div
                            class="flex h-9 w-9 shrink-0 items-center
                                   justify-center rounded-lg bg-gray-100
                                   text-gray-500">

                            <i class="bx bx-info-circle text-lg"></i>

                        </div>

                        <div>

                            <p class="text-sm font-medium text-gray-900">
                                Confirm received materials
                            </p>

                            <p class="mt-0.5 text-xs text-gray-500">
                                Make sure quantities and units are correct
                                before saving the receipt.
                            </p>

                        </div>

                    </div>


                    <div class="flex flex-col-reverse gap-2
                               sm:flex-row sm:items-center">

                        <a href="{{ route('purchase-orders.show', $purchaseOrder) }}"
                            class="inline-flex items-center justify-center
                                   gap-2 rounded-lg border border-gray-300
                                   bg-white px-4 py-2.5 text-sm font-medium
                                   text-gray-700 transition hover:bg-gray-50">

                            <i class="bx bx-x"></i>

                            Cancel

                        </a>


                        <button type="submit" id="submit-button"
                            class="inline-flex items-center justify-center
                                   gap-2 rounded-lg bg-gray-900
                                   px-5 py-2.5 text-sm font-medium text-white
                                   transition hover:bg-gray-800
                                   disabled:cursor-not-allowed
                                   disabled:opacity-50">

                            <i class="bx bx-package"></i>

                            <span>
                                Receive Materials
                            </span>

                        </button>

                    </div>

                </div>

            </form>

        </main>


        {{-- ================================================== --}}
        {{-- RIGHT - ACTIVITY TIMELINE --}}
        {{-- ================================================== --}}

        <aside class="xl:col-span-3">

            <div
                class="overflow-hidden rounded-xl border border-gray-200
                       bg-white xl:sticky xl:top-20">

                {{-- Header --}}
                <div
                    class="flex items-center justify-between
                           border-b border-gray-200 px-4 py-3">

                    <div class="flex items-center gap-2.5">

                        <div
                            class="flex h-8 w-8 shrink-0 items-center
                                   justify-center rounded-lg bg-gray-100
                                   text-gray-500">

                            <i class="bx bx-history"></i>

                        </div>

                        <div>

                            <h3 class="text-sm font-semibold text-gray-900">
                                Activity
                            </h3>

                            <p class="text-[10px] text-gray-400">
                                Request timeline
                            </p>

                        </div>

                    </div>


                    <span id="activityCount"
                        class="rounded-full bg-gray-100 px-2 py-0.5
                               text-[10px] font-semibold text-gray-500">

                        {{ $purchaseOrder->purchaseRequest->activities->count() }}

                    </span>

                </div>


                {{-- Timeline --}}
                <div id="activityTimeline" class="max-h-[700px] overflow-y-auto px-4 py-3">

                    @forelse($purchaseOrder->purchaseRequest->activities
                        as $activity)
                        @php
                            $activityIcon = match ($activity->action) {
                                'created' => 'bx-plus',
                                'updated' => 'bx-edit',
                                'submitted' => 'bx-send',
                                'approved' => 'bx-check',
                                'rejected' => 'bx-x',
                                'rfq_sent' => 'bx-envelope',
                                'quotation_received' => 'bx-file',
                                'quotation_selected' => 'bx-check-square',
                                'purchase_order_created' => 'bx-cart',
                                'goods_received' => 'bx-package',
                                'materials_received' => 'bx-package',
                                'completed' => 'bx-check-double',
                                default => 'bx-history',
                            };

                            $activityClass = match ($activity->action) {
                                'approved', 'completed' => 'border-green-200 bg-green-50 text-green-600',

                                'rejected' => 'border-red-200 bg-red-50 text-red-600',

                                'submitted', 'rfq_sent' => 'border-blue-200 bg-blue-50 text-blue-600',

                                'quotation_received',
                                'quotation_selected'
                                    => 'border-violet-200 bg-violet-50 text-violet-600',

                                'purchase_order_created' => 'border-amber-200 bg-amber-50 text-amber-600',

                                'goods_received', 'materials_received' => 'border-teal-200 bg-teal-50 text-teal-600',

                                default => 'border-gray-200 bg-white text-gray-500',
                            };
                        @endphp


                        <div class="relative flex gap-2.5 pb-4 last:pb-0">


                            {{-- Connecting line --}}
                            @if (!$loop->last)
                                <span
                                    class="absolute left-[11px] top-6
                                           h-[calc(100%-0.25rem)] w-px
                                           bg-gray-200">
                                </span>
                            @endif


                            {{-- Icon --}}
                            <div
                                class="{{ $activityClass }}
                                       relative z-10 flex h-6 w-6
                                       shrink-0 items-center justify-center
                                       rounded-full border">

                                <i
                                    class="bx {{ $activityIcon }}
                                           text-[10px]">
                                </i>

                            </div>


                            {{-- Content --}}
                            <div class="min-w-0 flex-1 pt-0.5">

                                <div
                                    class="flex items-start
                                           justify-between gap-2">

                                    <p
                                        class="text-[11px] font-semibold
                                               leading-4 text-gray-900">

                                        {{ ucwords(str_replace('_', ' ', $activity->action ?? 'activity')) }}

                                    </p>


                                    <span
                                        class="shrink-0 whitespace-nowrap
                                               text-[9px] text-gray-400">

                                        {{ $activity->created_at->diffForHumans() }}

                                    </span>

                                </div>


                                {{-- Description --}}
                                @if ($activity->description)
                                    <p
                                        class="mt-0.5 text-[11px]
                                               leading-4 text-gray-500">

                                        {{ $activity->description }}

                                    </p>
                                @endif


                                {{-- Vendor --}}
                                @if ($activity->vendor)
                                    <div
                                        class="mt-1 flex items-center
                                               gap-1 text-[10px]
                                               text-gray-500">

                                        <i class="bx bx-store"></i>

                                        <span class="truncate">

                                            {{ $activity->vendor->company_name ?: $activity->vendor->name }}

                                        </span>

                                    </div>
                                @endif


                                {{-- User --}}
                                @if ($activity->user)
                                    <div
                                        class="mt-1 flex items-center
                                               gap-1 text-[10px]
                                               text-gray-400">

                                        <i class="bx bx-user"></i>

                                        <span class="truncate">
                                            {{ $activity->user->name }}
                                        </span>

                                    </div>
                                @endif

                            </div>

                        </div>


                    @empty

                        <div class="py-10 text-center">

                            <div
                                class="mx-auto flex h-9 w-9
                                       items-center justify-center
                                       rounded-full bg-gray-100
                                       text-gray-400">

                                <i class="bx bx-history text-lg"></i>

                            </div>

                            <p class="mt-2 text-xs font-medium
                                       text-gray-700">

                                No activity yet

                            </p>

                            <p class="mt-0.5 text-[10px]
                                       text-gray-400">

                                Request actions will appear here.

                            </p>

                        </div>
                    @endforelse

                </div>

            </div>

        </aside>

    </div>


    {{-- ====================================================== --}}
    {{-- EXISTING JS --}}
    {{-- ====================================================== --}}

    @push('scripts')
        <script>
            $(document).ready(function() {


                /*
                 * Quantity input sanitization
                 */
                $(document).on(
                    'input',
                    '.received-qty',
                    function() {

                        this.value =
                            this.value.replace(
                                /[^0-9.]/g,
                                ''
                            );


                        const parts =
                            this.value.split('.');


                        if (parts.length > 2) {

                            this.value =
                                parts[0] +
                                '.' +
                                parts.slice(1).join('');

                        }

                    }
                );


                const container =
                    $('#receipt-items');


                /*
                 * Filter units based on category
                 */
                function filterUnits(item) {

                    const unitSelect =
                        item.find('.receipt-unit');

                    const categoryId =
                        item.data('category-id');


                    unitSelect
                        .find('option')
                        .each(function() {

                            const option =
                                $(this);


                            if (!option.val()) {

                                option.show();

                                return;

                            }


                            const optionCategoryId =
                                option.data(
                                    'category-id'
                                );


                            option.toggle(

                                categoryId &&

                                Number(
                                    optionCategoryId
                                ) ===
                                Number(
                                    categoryId
                                )

                            );

                        });


                    const selectedOption =
                        unitSelect.find(
                            'option:selected'
                        );


                    if (
                        selectedOption.val() &&

                        Number(
                            selectedOption.data(
                                'category-id'
                            )
                        ) !==
                        Number(categoryId)
                    ) {

                        unitSelect.val('');

                    }

                }


                /*
                 * Initialize rows
                 */
                container
                    .find('.receipt-item')
                    .each(function() {

                        filterUnits(
                            $(this)
                        );

                    });


                /*
                 * Submit Goods Receipt
                 */
                $('#goods-receipt-form')
                    .on('submit', function(e) {

                        e.preventDefault();


                        const $form =
                            $(this);

                        const $button =
                            $('#submit-button');

                        const originalHtml =
                            $button.html();

                        const formData =
                            new FormData(this);


                        $button
                            .prop('disabled', true)
                            .html(`
                                <i class="bx bx-loader-alt bx-spin"></i>
                                <span>Receiving...</span>
                            `);


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


                                setTimeout(
                                    function() {

                                        window.location.href =
                                            response.redirect;

                                    },
                                    500
                                );

                            },


                            error: function(xhr) {

                                if (
                                    xhr.status === 422
                                ) {

                                    const errors =
                                        xhr.responseJSON
                                        ?.errors || {};


                                    $.each(
                                        errors,
                                        function(
                                            field,
                                            messages
                                        ) {

                                            showToast(
                                                'error',
                                                messages[0]
                                            );

                                            return false;

                                        }
                                    );


                                    return;

                                }


                                showToast(
                                    'error',
                                    xhr.responseJSON
                                    ?.message ||
                                    'Unable to create goods receipt.'
                                );

                            },


                            complete: function() {

                                $button
                                    .prop(
                                        'disabled',
                                        false
                                    )
                                    .html(
                                        originalHtml
                                    );

                            }

                        });

                    });

            });
        </script>
    @endpush

</x-layouts.app>
