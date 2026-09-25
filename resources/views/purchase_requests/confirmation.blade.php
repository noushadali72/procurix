<x-layouts.app title="Confirm Purchase Request">


    @php
        $status = $purchaseRequest->status;

        $statusClass = match ($status) {
            'completed', 'active' =>
                'bg-green-50 text-green-700 border-green-200',

            'pending' =>
                'bg-amber-50 text-amber-700 border-amber-200',

            default =>
                'bg-gray-50 text-gray-600 border-gray-200',
        };

        $statusDot = match ($status) {
            'completed', 'active' => 'bg-green-500',
            'pending' => 'bg-amber-500',
            default => 'bg-gray-400',
        };
    @endphp


    {{-- Breadcrumb --}}
    <nav class="mb-5 flex items-center gap-2 text-xs text-gray-500">

        <a
            href="{{ route('admin.dashboard') }}"
            class="hover:text-gray-900">
            Dashboard
        </a>

        <i class="bx bx-chevron-right text-sm text-gray-400"></i>

        <a
            href="{{ route('purchase-requests.index') }}"
            class="hover:text-gray-900">
            Purchase Requests
        </a>

        <i class="bx bx-chevron-right text-sm text-gray-400"></i>

        <span class="font-medium text-gray-700">
            {{ $purchaseRequest->request_number ?? '#' . $purchaseRequest->id }}
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
                    Confirm Purchase Request
                </h1>

                <span
                    class="{{ $statusClass }}
                           inline-flex items-center gap-1.5 rounded-full
                           border px-2.5 py-1 text-xs font-medium">

                    <span
                        class="h-1.5 w-1.5 rounded-full {{ $statusDot }}">
                    </span>

                    {{ ucwords(str_replace('_', ' ', $status)) }}

                </span>

            </div>

            <p class="mt-1 text-sm text-gray-500">
                Review vendor, materials and request details before
                creating the purchase order.
            </p>

        </div>


        <a
            href="{{ route('purchase-requests.index') }}"
            class="inline-flex items-center justify-center gap-2
                   rounded-lg border border-gray-300 bg-white
                   px-3.5 py-2 text-sm font-medium text-gray-700
                   transition hover:bg-gray-50">

            <i class="bx bx-arrow-back"></i>

            Back

        </a>

    </div>


    {{-- ====================================================== --}}
    {{-- MAIN --}}
    {{-- ====================================================== --}}

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">


        {{-- ================================================== --}}
        {{-- LEFT --}}
        {{-- ================================================== --}}

        <main class="space-y-5 xl:col-span-9">


            {{-- ================================================== --}}
            {{-- REQUEST OVERVIEW --}}
            {{-- ================================================== --}}

            <section
                class="overflow-hidden rounded-xl border border-gray-200 bg-white">

                <div
                    class="flex items-center justify-between
                           border-b border-gray-200 px-5 py-3.5">

                    <div>

                        <h2 class="text-sm font-semibold text-gray-900">
                            Request Overview
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Vendor and delivery information
                        </p>

                    </div>

                    <div
                        class="flex h-8 w-8 items-center justify-center
                               rounded-lg bg-gray-100 text-gray-500">

                        <i class="bx bx-file text-lg"></i>

                    </div>

                </div>


                {{-- Top important information --}}
                <div
                    class="grid grid-cols-1 divide-y divide-gray-100
                           md:grid-cols-3 md:divide-x md:divide-y-0">

                    {{-- Request --}}
                    <div class="p-5">

                        <div class="mb-2 flex items-center gap-2 text-gray-500">

                            <i class="bx bx-hash"></i>

                            <span class="text-xs font-medium">
                                Request Number
                            </span>

                        </div>

                        <p class="text-sm font-semibold text-gray-900">

                            {{ $purchaseRequest->request_number
                                ?? '#' . $purchaseRequest->id }}

                        </p>

                    </div>


                    {{-- Vendor --}}
                    <div class="p-5">

                        <div class="mb-2 flex items-center gap-2 text-gray-500">

                            <i class="bx bx-store"></i>

                            <span class="text-xs font-medium">
                                Vendor
                            </span>

                        </div>

                        <p class="truncate text-sm font-semibold text-gray-900">

                            {{ $purchaseRequest->vendor->company_name
                                ?: $purchaseRequest->vendor->name }}

                        </p>

                        @if($purchaseRequest->vendor->contact_person)

                            <p class="mt-1 text-xs text-gray-500">
                                {{ $purchaseRequest->vendor->contact_person }}
                            </p>

                        @endif

                    </div>


                    {{-- Status --}}
                    <div class="p-5">

                        <div class="mb-2 flex items-center gap-2 text-gray-500">

                            <i class="bx bx-info-circle"></i>

                            <span class="text-xs font-medium">
                                Status
                            </span>

                        </div>

                        <span
                            class="{{ $statusClass }}
                                   inline-flex items-center gap-1.5
                                   rounded-full border px-2.5 py-1
                                   text-xs font-medium">

                            <span
                                class="h-1.5 w-1.5 rounded-full {{ $statusDot }}">
                            </span>

                            {{ ucwords(str_replace('_', ' ', $status)) }}

                        </span>

                    </div>

                </div>


                {{-- Contact information --}}
                <div
                    class="grid grid-cols-1 border-t border-gray-200
                           bg-gray-50/50 sm:grid-cols-2">

                    <div
                        class="flex items-start gap-3 border-b
                               border-gray-100 px-5 py-4
                               sm:border-b-0 sm:border-r">

                        <i class="bx bx-envelope mt-0.5 text-lg text-gray-400"></i>

                        <div class="min-w-0">

                            <p class="text-[11px] font-medium uppercase
                                      tracking-wide text-gray-400">
                                Email
                            </p>

                            <p class="mt-1 truncate text-sm text-gray-700">
                                {{ $purchaseRequest->vendor->email ?: '—' }}
                            </p>

                        </div>

                    </div>


                    <div class="flex items-start gap-3 px-5 py-4">

                        <i class="bx bx-phone mt-0.5 text-lg text-gray-400"></i>

                        <div>

                            <p class="text-[11px] font-medium uppercase
                                      tracking-wide text-gray-400">
                                Phone
                            </p>

                            <p class="mt-1 text-sm text-gray-700">
                                {{ $purchaseRequest->vendor->phone ?: '—' }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Delivery --}}
                <div class="border-t border-gray-200 px-5 py-4">

                    <div class="flex items-start gap-3">

                        <i class="bx bx-map mt-0.5 text-lg text-gray-400"></i>

                        <div>

                            <p class="text-xs font-medium text-gray-500">
                                Delivery Address
                            </p>

                            <p class="mt-1 text-sm leading-6 text-gray-700">

                                {{ $purchaseRequest->delivery_address ?: '—' }}

                            </p>

                        </div>

                    </div>

                </div>


                @if($purchaseRequest->notes)

                    <div class="border-t border-gray-200 px-5 py-4">

                        <div class="flex items-start gap-3">

                            <i class="bx bx-note mt-0.5 text-lg text-gray-400"></i>

                            <div>

                                <p class="text-xs font-medium text-gray-500">
                                    Notes
                                </p>

                                <p
                                    class="mt-1 whitespace-pre-line text-sm
                                           leading-6 text-gray-700">

                                    {{ $purchaseRequest->notes }}

                                </p>

                            </div>

                        </div>

                    </div>

                @endif

            </section>


            {{-- ================================================== --}}
            {{-- ITEMS --}}
            {{-- ================================================== --}}

            <section
                class="overflow-hidden rounded-xl border border-gray-200 bg-white">

                <div
                    class="flex items-center justify-between
                           border-b border-gray-200 px-5 py-4">

                    <div>

                        <h2 class="text-sm font-semibold text-gray-900">
                            Requested Materials
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Materials included in this purchase request
                        </p>

                    </div>

                    <span
                        class="rounded-full bg-gray-100 px-2.5 py-1
                               text-xs font-medium text-gray-600">

                        {{ $purchaseRequest->items->count() }}

                        {{ Str::plural(
                            'item',
                            $purchaseRequest->items->count()
                        ) }}

                    </span>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full min-w-[720px] text-left text-sm">

                        <thead
                            class="border-b border-gray-200 bg-gray-50
                                   text-[11px] uppercase tracking-wide
                                   text-gray-500">

                            <tr>

                                <th class="w-12 px-5 py-3 font-medium">
                                    #
                                </th>

                                <th class="px-5 py-3 font-medium">
                                    Material
                                </th>

                                <th class="px-5 py-3 font-medium">
                                    SKU
                                </th>

                                <th class="px-5 py-3 text-right font-medium">
                                    Quantity
                                </th>

                                <th class="px-5 py-3 font-medium">
                                    Unit
                                </th>

                                <th class="px-5 py-3 text-right font-medium">
                                    Unit Cost
                                </th>

                                <th class="px-5 py-3 text-right font-medium">
                                    Total
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @foreach($purchaseRequest->items as $index => $item)

                                <tr class="transition hover:bg-gray-50">

                                    <td class="px-5 py-3.5 text-xs text-gray-400">
                                        {{ $index + 1 }}
                                    </td>


                                    <td class="px-5 py-3.5">

                                        <div class="flex items-center gap-3">

                                            <div
                                                class="flex h-8 w-8 shrink-0
                                                       items-center justify-center
                                                       rounded-lg bg-gray-100
                                                       text-gray-500">

                                                <i class="bx bx-package"></i>

                                            </div>

                                            <span class="font-medium text-gray-900">

                                                {{ $item->rawMaterial->name }}

                                            </span>

                                        </div>

                                    </td>


                                    <td class="px-5 py-3.5">

                                        @if($item->rawMaterial->sku)

                                            <span
                                                class="rounded-md bg-gray-100
                                                       px-2 py-1 text-xs
                                                       font-medium text-gray-600">

                                                {{ $item->rawMaterial->sku }}

                                            </span>

                                        @else

                                            <span class="text-gray-400">—</span>

                                        @endif

                                    </td>


                                    <td
                                        class="px-5 py-3.5 text-right
                                               font-semibold text-gray-900">

                                        {{ $item->qty }}

                                    </td>


                                    <td class="px-5 py-3.5 text-gray-600">

                                        {{ $item->unit->short_name
                                            ?? $item->unit->name }}

                                    </td>


                                    <td
                                        class="px-5 py-3.5 text-right
                                               text-gray-700">

                                        {{ $item->unit_cost ?? '-' }}

                                    </td>


                                    <td
                                        class="px-5 py-3.5 text-right
                                               font-semibold text-gray-900">

                                        {{ number_format($item->total, 2) }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Summary --}}
                <div
                    class="grid grid-cols-1 border-t border-gray-200
                           bg-gray-50 sm:grid-cols-3">

                    <div class="px-5 py-3.5">

                        <p class="text-[11px] font-medium uppercase
                                  tracking-wide text-gray-500">
                            Items
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $purchaseRequest->items->count() }}
                        </p>

                    </div>


                    <div
                        class="border-t border-gray-200 px-5 py-3.5
                               sm:border-l sm:border-t-0">

                        <p class="text-[11px] font-medium uppercase
                                  tracking-wide text-gray-500">
                            Total Quantity
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $purchaseRequest->items->sum('qty') }}
                        </p>

                    </div>


                    <div
                        class="border-t border-gray-200 px-5 py-3.5
                               sm:border-l sm:border-t-0 sm:text-right">

                        <p class="text-[11px] font-medium uppercase
                                  tracking-wide text-gray-500">
                            Estimated Total
                        </p>

                        <p class="mt-1 text-base font-semibold text-gray-900">

                            {{ number_format(
                                $purchaseRequest->items->sum('total'),
                                2
                            ) }}

                        </p>

                    </div>

                </div>

            </section>


            {{-- ================================================== --}}
            {{-- ACTION BAR --}}
            {{-- ================================================== --}}

            <section
                class="overflow-hidden rounded-xl border border-gray-200 bg-white">

                <div
                    class="flex flex-col gap-4 p-4
                           lg:flex-row lg:items-center lg:justify-between">

                    <div class="flex items-start gap-3">

                        <div
                            class="flex h-9 w-9 shrink-0 items-center
                                   justify-center rounded-lg bg-gray-100
                                   text-gray-500">

                            <i class="bx bx-info-circle text-lg"></i>

                        </div>

                        <div>

                            <p class="text-sm font-medium text-gray-900">
                                Ready to confirm?
                            </p>

                            <p class="mt-0.5 text-xs text-gray-500">
                                Review the details before creating the purchase order.
                            </p>

                        </div>

                    </div>


                    <div class="flex flex-wrap items-center gap-2">

                        {{-- Edit --}}
                        <a
                            href="{{ route('purchase-requests.edit', $purchaseRequest) }}"
                            class="inline-flex items-center justify-center gap-2
                                   rounded-lg border border-gray-300 bg-white
                                   px-3.5 py-2.5 text-sm font-medium
                                   text-gray-700 transition hover:bg-gray-50">

                            <i class="bx bx-edit"></i>

                            Edit

                        </a>


                        {{-- Resend --}}
                        <button
                            type="button"
                            id="resendRfqBtn"
                            class="inline-flex cursor-pointer items-center
                                   justify-center gap-2 rounded-lg border
                                   border-gray-300 bg-white px-3.5 py-2.5
                                   text-sm font-medium text-gray-700
                                   transition hover:bg-gray-50
                                   disabled:cursor-not-allowed
                                   disabled:opacity-60">

                            <i class="bx bx-send"></i>

                            <span id="resendRfqBtnText">
                                Resend RFQ
                            </span>

                        </button>


                        {{-- Compare --}}
                        <a
                            href="{{ route('purchase-requests.compare', $purchaseRequest) }}"
                            class="inline-flex items-center justify-center gap-2
                                   rounded-lg border border-gray-300 bg-white
                                   px-3.5 py-2.5 text-sm font-medium
                                   text-gray-700 transition hover:bg-gray-50">

                            <i class="bx bx-git-compare"></i>

                            Compare

                        </a>


                        {{-- Confirm --}}
                        <button
                            type="button"
                            id="confirmPurchaseRequestBtn"
                            class="inline-flex cursor-pointer items-center
                                   justify-center gap-2 rounded-lg bg-gray-900
                                   px-4 py-2.5 text-sm font-medium text-white
                                   transition hover:bg-gray-800
                                   disabled:cursor-not-allowed
                                   disabled:opacity-60">

                            <i class="bx bx-check-circle"></i>

                            <span id="confirmPurchaseRequestBtnText">
                                Confirm Order
                            </span>

                        </button>

                    </div>

                </div>

            </section>

        </main>


        {{-- ================================================== --}}
        {{-- ACTIVITY --}}
        {{-- ================================================== --}}

        <aside class="xl:col-span-3">

            <div
                class="overflow-hidden rounded-xl border border-gray-200
                       bg-white xl:sticky xl:top-20">

                {{-- Timeline Header --}}
                <div
                    class="flex items-center justify-between
                           border-b border-gray-200 px-4 py-3">

                    <div class="flex items-center gap-2.5">

                        <div
                            class="flex h-8 w-8 items-center justify-center
                                   rounded-lg bg-gray-100 text-gray-500">

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


                    <span
                        id="activityCount"
                        class="rounded-full bg-gray-100 px-2 py-0.5
                               text-[10px] font-semibold text-gray-500">

                        {{ $purchaseRequest->activities->count() }}

                    </span>

                </div>


                {{-- Timeline --}}
                <div
                    id="activityTimeline"
                    class="max-h-[680px] overflow-y-auto px-4 py-3">

                    @forelse($purchaseRequest->activities as $activity)

                        @php
                            $activityIcon = match($activity->action) {
                                'created' => 'bx-plus',
                                'updated' => 'bx-edit',
                                'submitted' => 'bx-send',
                                'approved' => 'bx-check',
                                'rejected' => 'bx-x',
                                'rfq_sent' => 'bx-envelope',
                                'quotation_received' => 'bx-file',
                                'quotation_selected' => 'bx-check-square',
                                'purchase_order_created' => 'bx-cart',
                                'completed' => 'bx-check-double',
                                default => 'bx-history',
                            };

                            $activityClass = match($activity->action) {
                                'approved',
                                'completed' =>
                                    'border-green-200 bg-green-50 text-green-600',

                                'rejected' =>
                                    'border-red-200 bg-red-50 text-red-600',

                                'submitted',
                                'rfq_sent' =>
                                    'border-blue-200 bg-blue-50 text-blue-600',

                                'quotation_received',
                                'quotation_selected' =>
                                    'border-violet-200 bg-violet-50 text-violet-600',

                                'purchase_order_created' =>
                                    'border-amber-200 bg-amber-50 text-amber-600',

                                default =>
                                    'border-gray-200 bg-white text-gray-500',
                            };
                        @endphp


                        <div class="relative flex gap-2.5 pb-4 last:pb-0">

                            {{-- Line --}}
                            @if(!$loop->last)

                                <span
                                    class="absolute left-[11px] top-6
                                           h-[calc(100%-0.25rem)] w-px
                                           bg-gray-200">
                                </span>

                            @endif


                            {{-- Icon --}}
                            <div
                                class="{{ $activityClass }}
                                       relative z-10 flex h-6 w-6 shrink-0
                                       items-center justify-center
                                       rounded-full border">

                                <i class="bx {{ $activityIcon }} text-[10px]"></i>

                            </div>


                            {{-- Details --}}
                            <div class="min-w-0 flex-1 pt-0.5">

                                <div
                                    class="flex items-start justify-between gap-2">

                                    <p
                                        class="text-[11px] font-semibold
                                               leading-4 text-gray-900">

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $activity->action ?? 'activity'
                                            )
                                        ) }}

                                    </p>

                                    <span
                                        class="shrink-0 whitespace-nowrap
                                               text-[9px] text-gray-400">

                                        {{ $activity->created_at->diffForHumans() }}

                                    </span>

                                </div>


                                @if($activity->description)

                                    <p
                                        class="mt-0.5 text-[11px] leading-4
                                               text-gray-500">

                                        {{ $activity->description }}

                                    </p>

                                @endif


                                @if($activity->vendor)

                                    <div
                                        class="mt-1 flex items-center gap-1
                                               text-[10px] text-gray-500">

                                        <i class="bx bx-store"></i>

                                        <span class="truncate">

                                            {{ $activity->vendor->company_name
                                                ?: $activity->vendor->name }}

                                        </span>

                                    </div>

                                @endif


                                @if($activity->user)

                                    <div
                                        class="mt-1 flex items-center gap-1
                                               text-[10px] text-gray-400">

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
                                class="mx-auto flex h-9 w-9 items-center
                                       justify-center rounded-full
                                       bg-gray-100 text-gray-400">

                                <i class="bx bx-history text-lg"></i>

                            </div>

                            <p class="mt-2 text-xs font-medium text-gray-700">
                                No activity yet
                            </p>

                            <p class="mt-0.5 text-[10px] text-gray-400">
                                Request actions will appear here.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </aside>

    </div>



    {{-- ====================================================== --}}
    {{-- EXISTING JAVASCRIPT --}}
    {{-- LOGIC UNCHANGED --}}
    {{-- ====================================================== --}}

    @push('scripts')
        <script>
            $(document).ready(function() {


                /*
                 * Confirm Purchase Request
                 */
                $('#confirmPurchaseRequestBtn').on('click', function() {

                    const button = $(this);

                    const buttonText =
                        $('#confirmPurchaseRequestBtnText');

                    const originalText =
                        buttonText.text();


                    button.prop('disabled', true);

                    buttonText.text('Confirming...');


                    $.ajax({

                        url: "{{ route('purchase-requests.confirm', $purchaseRequest) }}",

                        type: 'POST',

                        headers: {

                            'Accept': 'application/json',

                            'X-CSRF-TOKEN': '{{ csrf_token() }}'

                        },


                        success: function(response) {

                            showToast(
                                'success',
                                response.message ||
                                'Purchase request confirmed successfully.'
                            );


                            setTimeout(function() {

                                window.location.href =
                                    response.redirect;

                            }, 800);

                        },


                        error: function(xhr) {

                            button.prop(
                                'disabled',
                                false
                            );

                            buttonText.text(
                                originalText
                            );


                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to confirm purchase request.'
                            );

                        }

                    });

                });


                /*
                 * Resend RFQ
                 */
                $('#resendRfqBtn').on('click', function() {

                    const button = $(this);

                    const buttonText =
                        $('#resendRfqBtnText');


                    button.prop(
                        'disabled',
                        true
                    );

                    buttonText.text(
                        'Sending...'
                    );


                    $.ajax({

                        url: "{{ route('purchase-requests.resend-rfq', $purchaseRequest) }}",

                        type: 'POST',

                        headers: {

                            'Accept': 'application/json',

                            'X-CSRF-TOKEN': '{{ csrf_token() }}'

                        },


                        success: function(response) {

                            showToast(
                                'success',
                                response.message ||
                                'RFQ sent successfully.'
                            );


                            button.prop(
                                'disabled',
                                false
                            );

                            buttonText.text(
                                'Resend RFQ'
                            );

                        },


                        error: function(xhr) {

                            button.prop(
                                'disabled',
                                false
                            );

                            buttonText.text(
                                'Resend RFQ'
                            );


                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to resend RFQ.'
                            );

                        }

                    });

                });


                

            });



        </script>
    @endpush

</x-layouts.app>
