<x-layouts.app title="Compare Purchase Requests">

    <div class="space-y-5">

        {{-- ====================================================== --}}
        {{-- BREADCRUMB --}}
        {{-- ====================================================== --}}

        <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">

            <a href="{{ route('admin.dashboard') }}" class="transition hover:text-gray-900">
                Dashboard
            </a>

            <i class="bx bx-chevron-right text-gray-400"></i>

            <a href="{{ route('purchase-requests.index') }}" class="transition hover:text-gray-900">
                Purchase Requests
            </a>

            <i class="bx bx-chevron-right text-gray-400"></i>

            <a href="{{ route('purchase-requests.confirmation', $purchaseRequest) }}"
                class="transition hover:text-gray-900">
                {{ $purchaseRequest->request_number }}
            </a>

            <i class="bx bx-chevron-right text-gray-400"></i>

            <span class="font-medium text-gray-800">
                Compare
            </span>

        </div>


        {{-- ====================================================== --}}
        {{-- PAGE HEADER --}}
        {{-- ====================================================== --}}

        <div
            class="flex flex-col gap-4 rounded-xl border border-gray-200
                   bg-white px-5 py-5 shadow-sm
                   lg:flex-row lg:items-center lg:justify-between">

            <div class="flex items-start gap-3">

                <div
                    class="flex h-10 w-10 shrink-0 items-center
                           justify-center rounded-lg bg-gray-900 text-white">

                    <i class="bx bx-git-compare text-xl"></i>

                </div>

                <div>

                    <div class="flex flex-wrap items-center gap-2">

                        <h1 class="text-lg font-semibold text-gray-900">
                            Purchase Request Comparison
                        </h1>

                        <span
                            class="rounded-full bg-gray-100 px-2.5 py-1
                                   text-[10px] font-semibold text-gray-600">

                            {{ $purchaseRequests->count() }}
                            {{ Str::plural('Request', $purchaseRequests->count()) }}

                        </span>

                    </div>

                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Compare vendor quotations, select the preferred request
                        and record the reason for your procurement decision.
                    </p>

                </div>

            </div>


            <a href="{{ route('purchase-requests.confirmation', $purchaseRequest) }}"
                class="inline-flex items-center justify-center gap-2
                       rounded-lg border border-gray-300 bg-white
                       px-4 py-2.5 text-sm font-medium text-gray-700
                       transition hover:bg-gray-50">

                <i class="bx bx-arrow-back"></i>

                Back to Confirmation

            </a>

        </div>


        {{-- ====================================================== --}}
        {{-- INFORMATION --}}
        {{-- ====================================================== --}}

        <div class="rounded-xl border border-blue-200
                   bg-blue-50/60 px-4 py-3.5">

            <div class="flex items-start gap-3">

                <div
                    class="flex h-8 w-8 shrink-0 items-center
                           justify-center rounded-lg bg-blue-100
                           text-blue-600">

                    <i class="bx bx-info-circle text-lg"></i>

                </div>

                <div>

                    <p class="text-sm font-semibold text-blue-900">
                        Vendor Request Comparison
                    </p>

                    <p class="mt-1 text-xs leading-5 text-blue-700">
                        Select one purchase request to create the purchase order.
                        Other matching vendor requests will be cancelled after
                        confirmation.
                    </p>

                </div>

            </div>

        </div>


        {{-- ====================================================== --}}
        {{-- NOT ENOUGH REQUESTS --}}
        {{-- ====================================================== --}}

        @if ($purchaseRequests->count() < 2)

            <div
                class="rounded-xl border border-gray-200
                       bg-white px-6 py-14 text-center shadow-sm">

                <div
                    class="mx-auto flex h-12 w-12 items-center
                           justify-center rounded-full bg-gray-100
                           text-gray-500">

                    <i class="bx bx-search-alt text-2xl"></i>

                </div>

                <h2 class="mt-4 text-base font-semibold text-gray-900">
                    No other vendor requests available
                </h2>

                <p class="mx-auto mt-1 max-w-md text-sm leading-6 text-gray-500">
                    There are currently no other sent purchase requests
                    for the same material requirement.
                </p>

                <a href="{{ route('purchase-requests.confirmation', $purchaseRequest) }}"
                    class="mt-5 inline-flex items-center gap-2
                           rounded-lg bg-gray-900 px-4 py-2.5
                           text-sm font-medium text-white
                           transition hover:bg-gray-800">

                    <i class="bx bx-arrow-back"></i>

                    Back to Confirmation

                </a>

            </div>
        @else
            {{-- ====================================================== --}}
            {{-- VENDOR SELECTION --}}
            {{-- ====================================================== --}}

            <div class="overflow-hidden rounded-xl border
                       border-gray-200 bg-white shadow-sm">

                <div
                    class="flex flex-col gap-2 border-b border-gray-200
                           px-5 py-4 sm:flex-row sm:items-center
                           sm:justify-between">

                    <div>

                        <h2 class="text-sm font-semibold text-gray-900">
                            Select Vendor Request
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Choose the request you want to use for the purchase order.
                        </p>

                    </div>

                    <span
                        class="text-[11px] font-medium
                               uppercase tracking-wide text-gray-400">
                        One selection required
                    </span>

                </div>


                <div class="grid grid-cols-1 gap-3 p-4
                           md:grid-cols-2 xl:grid-cols-3">

                    @foreach ($purchaseRequests as $request)
                        <label
                            class="vendor-card group relative cursor-pointer
                                   rounded-xl border border-gray-200
                                   bg-white p-4 transition
                                   hover:border-gray-400 hover:shadow-sm"
                            data-request-id="{{ $request->id }}">

                            <input type="radio" name="selected_request" value="{{ $request->id }}"
                                class="peer sr-only" @checked($request->id === $purchaseRequest->id)>


                            <div class="flex items-start justify-between gap-3">

                                <div class="flex min-w-0 items-center gap-3">

                                    <div
                                        class="flex h-10 w-10 shrink-0
                                               items-center justify-center
                                               rounded-lg bg-gray-100
                                               text-gray-600">

                                        <i class="bx bx-store text-lg"></i>

                                    </div>

                                    <div class="min-w-0">

                                        <p
                                            class="truncate text-sm
                                                   font-semibold text-gray-900">

                                            {{ $request->vendor?->company_name ?? ($request->vendor?->name ?? 'Unknown Vendor') }}

                                        </p>

                                        <p class="mt-0.5 text-xs text-gray-500">
                                            {{ $request->request_number }}
                                        </p>

                                    </div>

                                </div>


                                <div
                                    class="vendor-radio flex h-5 w-5 shrink-0
                                           items-center justify-center
                                           rounded-full border border-gray-300">

                                    <i
                                        class="bx bx-check hidden
                                               text-xs text-white">
                                    </i>

                                </div>

                            </div>


                            <div
                                class="mt-4 grid grid-cols-2 gap-3
                                       border-t border-gray-100 pt-3">

                                <div>

                                    <p
                                        class="text-[10px] font-medium
                                               uppercase tracking-wide
                                               text-gray-400">
                                        Items
                                    </p>

                                    <p
                                        class="mt-1 text-sm font-semibold
                                               text-gray-900">
                                        {{ $request->items->count() }}
                                    </p>

                                </div>


                                <div>

                                    <p
                                        class="text-[10px] font-medium
                                               uppercase tracking-wide
                                               text-gray-400">
                                        Estimated Total
                                    </p>

                                    <p
                                        class="mt-1 text-sm font-semibold
                                               text-gray-900">

                                        {{ number_format($request->items->sum('total'), 2) }}

                                    </p>

                                </div>

                            </div>


                            {{-- Selected indicator --}}
                            <div
                                class="selected-label mt-3 hidden
                                       items-center gap-1.5
                                       border-t border-green-100 pt-3
                                       text-[11px] font-semibold
                                       text-green-700">

                                <i class="bx bx-check-circle"></i>

                                Selected for Purchase Order

                            </div>

                        </label>
                    @endforeach

                </div>

            </div>


            {{-- ====================================================== --}}
            {{-- COMPARISON TABLE --}}
            {{-- ====================================================== --}}

            <div class="overflow-hidden rounded-xl border
                       border-gray-200 bg-white shadow-sm">

                <div
                    class="flex flex-col gap-2 border-b border-gray-200
                           px-5 py-4 sm:flex-row sm:items-center
                           sm:justify-between">

                    <div>

                        <h2 class="text-sm font-semibold text-gray-900">
                            Detailed Comparison
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Compare quantities, units, unit costs and totals
                            for each material.
                        </p>

                    </div>

                    <div
                        class="inline-flex w-fit items-center gap-1.5
                               rounded-lg bg-gray-100 px-2.5 py-1.5
                               text-[11px] font-medium text-gray-600">

                        <i class="bx bx-table"></i>

                        {{ $purchaseRequest->items->count() }}
                        {{ Str::plural('Material', $purchaseRequest->items->count()) }}

                    </div>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full min-w-[1000px]
                               text-left text-sm">

                        <thead
                            class="border-b border-gray-200
                                   bg-gray-50 text-[10px]
                                   uppercase tracking-wide text-gray-500">

                            <tr>

                                <th
                                    class="sticky left-0 z-10 w-64
                                           bg-gray-50 px-5 py-3
                                           font-semibold">

                                    Material

                                </th>


                                @foreach ($purchaseRequests as $request)
                                    <th
                                        class="min-w-[230px]
                                               border-l border-gray-200
                                               px-5 py-3">

                                        <div class="normal-case tracking-normal">

                                            <p
                                                class="truncate text-xs
                                                       font-semibold text-gray-800">

                                                {{ $request->vendor?->company_name ?? ($request->vendor?->name ?? 'Unknown Vendor') }}

                                            </p>

                                            <p
                                                class="mt-0.5 text-[10px]
                                                       font-normal text-gray-400">

                                                {{ $request->request_number }}

                                            </p>

                                        </div>

                                    </th>
                                @endforeach

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @foreach ($purchaseRequest->items as $baseItem)
                                <tr class="align-top hover:bg-gray-50/50">

                                    {{-- Material --}}
                                    <td
                                        class="sticky left-0 z-10
                                               bg-white px-5 py-4">

                                        <div class="flex items-start gap-3">

                                            <div
                                                class="flex h-8 w-8 shrink-0
                                                       items-center justify-center
                                                       rounded-lg bg-gray-100
                                                       text-gray-500">

                                                <i class="bx bx-package"></i>

                                            </div>

                                            <div>

                                                <p
                                                    class="font-medium
                                                           text-gray-900">

                                                    {{ $baseItem->rawMaterial?->name }}

                                                </p>

                                                @if ($baseItem->rawMaterial?->sku)
                                                    <p
                                                        class="mt-0.5
                                                               text-[10px]
                                                               text-gray-400">

                                                        SKU:
                                                        {{ $baseItem->rawMaterial->sku }}

                                                    </p>
                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    @foreach ($purchaseRequests as $request)
                                        @php
                                            $item = $request->items->firstWhere(
                                                'raw_material_id',
                                                $baseItem->raw_material_id,
                                            );
                                        @endphp


                                        <td
                                            class="border-l border-gray-200
                                                   px-5 py-4">

                                            @if ($item)
                                                <div class="space-y-2.5">

                                                    <div
                                                        class="flex
                                                               justify-between
                                                               gap-4">

                                                        <span
                                                            class="text-xs
                                                                   text-gray-500">
                                                            Quantity
                                                        </span>

                                                        <span
                                                            class="text-xs
                                                                   font-semibold
                                                                   text-gray-900">
                                                            {{ $item->qty }}
                                                        </span>

                                                    </div>


                                                    <div
                                                        class="flex
                                                               justify-between
                                                               gap-4">

                                                        <span
                                                            class="text-xs
                                                                   text-gray-500">
                                                            Unit
                                                        </span>

                                                        <span
                                                            class="text-xs
                                                                   font-medium
                                                                   text-gray-800">

                                                            {{ $item->unit?->short_name ?? ($item->unit?->name ?? '-') }}

                                                        </span>

                                                    </div>


                                                    <div
                                                        class="flex
                                                               justify-between
                                                               gap-4">

                                                        <span
                                                            class="text-xs
                                                                   text-gray-500">
                                                            Unit Cost
                                                        </span>

                                                        <span
                                                            class="text-xs
                                                                   font-medium
                                                                   text-gray-800">

                                                            {{ number_format($item->unit_cost, 2) }}

                                                        </span>

                                                    </div>


                                                    <div
                                                        class="flex
                                                               justify-between
                                                               gap-4
                                                               border-t
                                                               border-gray-100
                                                               pt-2.5">

                                                        <span
                                                            class="text-xs
                                                                   font-medium
                                                                   text-gray-600">
                                                            Line Total
                                                        </span>

                                                        <span
                                                            class="text-sm
                                                                   font-semibold
                                                                   text-gray-900">

                                                            {{ number_format($item->total, 2) }}

                                                        </span>

                                                    </div>

                                                </div>
                                            @else
                                                <div
                                                    class="flex min-h-[100px]
                                                           items-center
                                                           justify-center">

                                                    <span
                                                        class="inline-flex
                                                               items-center
                                                               gap-1.5
                                                               rounded-full
                                                               bg-gray-100
                                                               px-2.5 py-1
                                                               text-[10px]
                                                               font-medium
                                                               text-gray-500">

                                                        <i class="bx bx-minus"></i>

                                                        Not quoted

                                                    </span>

                                                </div>
                                            @endif

                                        </td>
                                    @endforeach

                                </tr>
                            @endforeach

                        </tbody>


                        {{-- Totals --}}
                        <tfoot class="border-t border-gray-200
                                   bg-gray-50">

                            <tr>

                                <td
                                    class="sticky left-0 z-10
                                           bg-gray-50 px-5 py-4">

                                    <span
                                        class="text-sm font-semibold
                                               text-gray-900">

                                        Estimated Total

                                    </span>

                                </td>


                                @foreach ($purchaseRequests as $request)
                                    <td
                                        class="border-l border-gray-200
                                               px-5 py-4">

                                        <span
                                            class="text-base font-semibold
                                                   text-gray-900">

                                            {{ number_format($request->items->sum('total'), 2) }}

                                        </span>

                                    </td>
                                @endforeach

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>


            {{-- ====================================================== --}}
            {{-- PROCUREMENT DECISION --}}
            {{-- ====================================================== --}}

            <div class="overflow-hidden rounded-xl border
                       border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 px-5 py-4">

                    <div class="flex items-start gap-3">

                        <div
                            class="flex h-9 w-9 shrink-0 items-center
                                   justify-center rounded-lg
                                   bg-gray-100 text-gray-600">

                            <i class="bx bx-message-square-detail
                                       text-lg">
                            </i>

                        </div>

                        <div>

                            <h2 class="text-sm font-semibold
                                       text-gray-900">

                                Procurement Decision

                            </h2>

                            <p class="mt-1 text-xs
                                       text-gray-500">

                                Record why the selected vendor request
                                was preferred over the alternatives.

                            </p>

                        </div>

                    </div>

                </div>


                <div class="p-5">

                    <label for="selection_reason"
                        class="mb-2 block text-sm
                               font-medium text-gray-700">

                        Selection Reason
                        <sup>*</sup>

                    </label>


                    <textarea id="selection_reason" name="selection_reason" rows="4" maxlength="1000"
                        placeholder="Example: Selected because of lower overall cost, better delivery time and suitable payment terms."
                        class="block w-full resize-y rounded-lg
                               border border-gray-300 bg-white
                               px-3.5 py-3 text-sm text-gray-900
                               outline-none transition
                               placeholder:text-gray-400
                               focus:border-gray-500
                               focus:ring-2 focus:ring-gray-200"></textarea>


                    <div
                        class="mt-2 flex flex-col gap-1
                               sm:flex-row sm:items-center
                               sm:justify-between">

                        <p id="selectionReasonError" class="hidden text-xs text-red-600">
                        </p>

                        <p class="ml-auto text-[10px]
                                   text-gray-400">

                            <span id="selectionReasonCount">0</span>
                            / 1000

                        </p>

                    </div>

                </div>

            </div>


            {{-- ====================================================== --}}
            {{-- FINAL ACTION --}}
            {{-- ====================================================== --}}

            <div
                class="flex flex-col gap-4 rounded-xl
                       border border-gray-200 bg-white
                       p-4 shadow-sm
                       sm:flex-row sm:items-center
                       sm:justify-between">

                <div class="flex items-start gap-3">

                    <div
                        class="flex h-9 w-9 shrink-0 items-center
                               justify-center rounded-lg
                               bg-green-50 text-green-600">

                        <i class="bx bx-check-circle text-lg"></i>

                    </div>

                    <div>

                        <p class="text-sm font-semibold
                                   text-gray-900">
                            Ready to confirm?
                        </p>

                        <p class="mt-0.5 text-xs
                                   text-gray-500">

                            The selected vendor request will create
                            the purchase order and the decision reason
                            will be retained with the comparison.

                        </p>

                    </div>

                </div>


                <div class="flex shrink-0
                           items-center gap-2">

                    <a href="{{ route('purchase-requests.confirmation', $purchaseRequest) }}"
                        class="inline-flex items-center
                               justify-center rounded-lg
                               border border-gray-300 bg-white
                               px-4 py-2.5 text-sm font-medium
                               text-gray-700 transition
                               hover:bg-gray-50">

                        Cancel

                    </a>


                    <button type="button" id="confirmComparisonBtn"
                        class="inline-flex cursor-pointer items-center
                               justify-center gap-2 rounded-lg
                               bg-gray-900 px-5 py-2.5
                               text-sm font-medium text-white
                               transition hover:bg-gray-800
                               disabled:cursor-not-allowed
                               disabled:opacity-50">

                        <i class="bx bx-check"></i>

                        <span id="confirmComparisonText">
                            Confirm Selected Order
                        </span>

                    </button>

                </div>

            </div>

        @endif

    </div>


    @push('scripts')
        <script>
            $(document).ready(function() {


                /*
                |--------------------------------------------------------------------------
                | Vendor Card Selection
                |--------------------------------------------------------------------------
                */

                function updateSelectedVendorCard() {

                    $('.vendor-card')
                        .removeClass(
                            'border-gray-900 ring-1 ring-gray-900 bg-gray-50'
                        );

                    $('.vendor-card')
                        .find('.vendor-radio')
                        .removeClass('border-gray-900 bg-gray-900')
                        .addClass('border-gray-300');

                    $('.vendor-card')
                        .find('.vendor-radio i')
                        .addClass('hidden');

                    $('.vendor-card')
                        .find('.selected-label')
                        .removeClass('flex')
                        .addClass('hidden');


                    const selectedRadio =
                        $('input[name="selected_request"]:checked');


                    if (!selectedRadio.length) {
                        return;
                    }


                    const selectedCard =
                        selectedRadio.closest('.vendor-card');


                    selectedCard
                        .addClass(
                            'border-gray-900 ring-1 ring-gray-900 bg-gray-50'
                        );


                    selectedCard
                        .find('.vendor-radio')
                        .removeClass('border-gray-300')
                        .addClass('border-gray-900 bg-gray-900');


                    selectedCard
                        .find('.vendor-radio i')
                        .removeClass('hidden');


                    selectedCard
                        .find('.selected-label')
                        .removeClass('hidden')
                        .addClass('flex');

                }


                /*
                 * Show the initially checked request.
                 */
                updateSelectedVendorCard();


                $('.vendor-card').on('click', function() {

                    $(this)
                        .find('input[type="radio"]')
                        .prop('checked', true);


                    updateSelectedVendorCard();

                });


                /*
                |--------------------------------------------------------------------------
                | Selection Reason Counter
                |--------------------------------------------------------------------------
                */

                $('#selection_reason').on('input', function() {

                    const length = $(this).val().length;

                    $('#selectionReasonCount').text(length);


                    /*
                     * Clear frontend error when user starts typing.
                     */
                    if ($(this).val().trim()) {

                        $(this)
                            .removeClass(
                                'border-red-300 focus:border-red-500 focus:ring-red-100'
                            );

                        $('#selectionReasonError')
                            .addClass('hidden')
                            .text('');

                    }

                });


                /*
                |--------------------------------------------------------------------------
                | Confirm Comparison
                |--------------------------------------------------------------------------
                */

                $('#confirmComparisonBtn').on('click', function() {

                    const selectedRequest =
                        $('input[name="selected_request"]:checked').val();

                    const selectionReason =
                        $('#selection_reason').val().trim();


                    if (!selectedRequest) {

                        showToast(
                            'error',
                            'Please select a purchase request.'
                        );

                        return;

                    }


                    if (!selectionReason) {

                        $('#selection_reason')
                            .addClass(
                                'border-red-300 focus:border-red-500 focus:ring-red-100'
                            )
                            .trigger('focus');


                        $('#selectionReasonError')
                            .removeClass('hidden')
                            .text(
                                'Please provide a reason for selecting this purchase request.'
                            );


                        showToast(
                            'error',
                            'Please provide a selection reason.'
                        );

                        return;

                    }


                    const button = $(this);

                    const text =
                        $('#confirmComparisonText');


                    if (!confirm(
                            'Confirm this vendor request and create the purchase order? Other matching vendor requests will be cancelled.'
                        )) {
                        return;
                    }


                    button.prop('disabled', true);

                    text.text('Confirming...');


                    $.ajax({

                        url: "{{ route('purchase-requests.compare.confirm', $purchaseRequest) }}",

                        type: 'POST',

                        data: {

                            _token: "{{ csrf_token() }}",

                            selected_purchase_request: selectedRequest,

                            selection_reason: selectionReason

                        },

                        headers: {
                            Accept: 'application/json'
                        },


                        success: function(response) {

                            showToast(
                                'success',
                                response.message ||
                                'Purchase order created successfully.'
                            );


                            setTimeout(function() {

                                window.location.href =
                                    response.redirect;

                            }, 700);

                        },


                        error: function(xhr) {

                            button.prop('disabled', false);

                            text.text(
                                'Confirm Selected Order'
                            );


                            if (xhr.status === 422) {

                                const errors =
                                    xhr.responseJSON?.errors || {};


                                /*
                                 * Selection reason validation
                                 */
                                if (errors.selection_reason?.[0]) {

                                    $('#selection_reason')
                                        .addClass(
                                            'border-red-300 focus:border-red-500 focus:ring-red-100'
                                        );


                                    $('#selectionReasonError')
                                        .removeClass('hidden')
                                        .text(
                                            errors.selection_reason[0]
                                        );

                                }


                                const message =
                                    errors.selected_purchase_request?.[0] ||
                                    errors.selection_reason?.[0] ||
                                    xhr.responseJSON?.message ||
                                    'Unable to confirm the selected request.';


                                showToast(
                                    'error',
                                    message
                                );

                                return;

                            }


                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to create the purchase order.'
                            );

                        }

                    });

                });

            });
        </script>
    @endpush

</x-layouts.app>
