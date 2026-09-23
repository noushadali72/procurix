<x-layouts.app title="Confirm Purchase Request">

    <div class="max-w-7xl">

        {{-- Breadcrumb --}}
        <nav class="mb-5 flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.dashboard') }}" class="transition hover:text-slate-800">
                Dashboard
            </a>

            <i class="bx bx-chevron-right text-sm text-slate-400"></i>

            <a href="{{ route('purchase-requests.index') }}" class="transition hover:text-slate-800">
                Purchase Requests
            </a>

            <i class="bx bx-chevron-right text-sm text-slate-400"></i>

            <span class="font-medium text-slate-700">
                {{ $purchaseRequest->request_number ?? '#' . $purchaseRequest->id }}
            </span>
        </nav>


        {{-- Page Header --}}
        <div
            class="mb-6 flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-end sm:justify-between">

            <div>
                <div class="flex flex-wrap items-center gap-3">

                    <h1 class="text-xl font-semibold tracking-tight text-slate-900">
                        Confirm Purchase Request
                    </h1>

                    <span
                        class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-[11px] font-semibold capitalize text-slate-600">
                        {{ str_replace('_', ' ', $purchaseRequest->status) }}
                    </span>

                </div>

                <p class="mt-1 text-sm text-slate-500">
                    Review the request details before creating the purchase order.
                </p>
            </div>

            <a href="{{ route('purchase-requests.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-md border border-slate-300 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">

                <i class="bx bx-arrow-back"></i>
                Purchase Requests

            </a>

        </div>


        <div class="space-y-5">

            {{-- Request Information --}}
            <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">

                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="text-sm font-semibold text-slate-900">
                        Request Information
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500">
                        Vendor and delivery information for this purchase request.
                    </p>
                </div>


                <div class="grid grid-cols-1 divide-y divide-slate-100 md:grid-cols-2 md:divide-y-0">

                    {{-- Request Number --}}
                    <div class="border-b border-slate-100 px-5 py-4 md:border-r">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                            Request Number
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $purchaseRequest->request_number ?? '#' . $purchaseRequest->id }}
                        </p>
                    </div>


                    {{-- Vendor --}}
                    <div class="border-b border-slate-100 px-5 py-4">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                            Vendor
                        </p>

                        <p class="mt-1 text-sm font-semibold text-slate-900">
                            {{ $purchaseRequest->vendor->company_name ?: $purchaseRequest->vendor->name }}
                        </p>

                        @if ($purchaseRequest->vendor->contact_person)
                            <p class="mt-0.5 text-xs text-slate-500">
                                {{ $purchaseRequest->vendor->contact_person }}
                            </p>
                        @endif
                    </div>


                    {{-- Email --}}
                    <div class="border-b border-slate-100 px-5 py-4 md:border-r">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                            Email
                        </p>

                        <p class="mt-1 text-sm text-slate-700">
                            {{ $purchaseRequest->vendor->email ?: '—' }}
                        </p>
                    </div>


                    {{-- Phone --}}
                    <div class="border-b border-slate-100 px-5 py-4">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                            Phone
                        </p>

                        <p class="mt-1 text-sm text-slate-700">
                            {{ $purchaseRequest->vendor->phone ?: '—' }}
                        </p>
                    </div>


                    {{-- Delivery Address --}}
                    <div class="border-b border-slate-100 px-5 py-4 md:col-span-2">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                            Delivery Address
                        </p>

                        <p class="mt-1 text-sm leading-6 text-slate-700">
                            {{ $purchaseRequest->delivery_address ?: '—' }}
                        </p>
                    </div>


                    {{-- Notes --}}
                    @if ($purchaseRequest->notes)
                        <div class="px-5 py-4 md:col-span-2">
                            <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                                Notes
                            </p>

                            <p class="mt-1 text-sm leading-6 text-slate-700">
                                {{ $purchaseRequest->notes }}
                            </p>
                        </div>
                    @endif

                </div>

            </section>


            {{-- Request Items --}}
            <section class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">

                <div
                    class="flex flex-col gap-1 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">
                            Request Items
                        </h2>

                        <p class="mt-0.5 text-xs text-slate-500">
                            Raw materials requested for this purchase.
                        </p>
                    </div>

                    <span class="text-xs font-medium text-slate-500">
                        {{ $purchaseRequest->items->count() }}
                        {{ Str::plural('item', $purchaseRequest->items->count()) }}
                    </span>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full min-w-[760px]">

                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50">

                                <th
                                    class="w-12 px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                    #
                                </th>

                                <th
                                    class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                    Raw Material
                                </th>

                                <th
                                    class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                    SKU
                                </th>

                                <th
                                    class="px-5 py-3 text-right text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                    Quantity
                                </th>

                                <th
                                    class="px-5 py-3 text-right text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                    Unit Cost
                                </th>

                                <th
                                    class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-slate-500">
                                    Unit
                                </th>

                            </tr>
                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @foreach ($purchaseRequest->items as $index => $item)
                                <tr class="transition hover:bg-slate-50">

                                    <td class="px-5 py-3.5 text-xs text-slate-400">
                                        {{ $index + 1 }}
                                    </td>


                                    <td class="px-5 py-3.5">
                                        <p class="text-sm font-medium text-slate-900">
                                            {{ $item->rawMaterial->name }}
                                        </p>
                                    </td>


                                    <td class="px-5 py-3.5 text-sm text-slate-600">
                                        {{ $item->rawMaterial->sku ?: '—' }}
                                    </td>


                                    <td class="px-5 py-3.5 text-right text-sm font-medium text-slate-900">
                                        {{ $item->qty }}
                                    </td>


                                    <td class="px-5 py-3.5 text-right text-sm font-medium text-slate-900">
                                        {{ $item->unit_cost ?? '-' }}
                                    </td>


                                    <td class="px-5 py-3.5 text-sm text-slate-700">
                                        {{ $item->unit->name }}

                                        <span class="text-xs text-slate-400">
                                            ({{ $item->unit->short_name }})
                                        </span>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>



                </div>
                {{-- Request Summary --}}
                <div class="grid grid-cols-3 border-t border-slate-200 bg-slate-50">

                    <div class="px-5 py-3">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">
                            Total Items
                        </p>
                        <p class="mt-0.5 text-sm font-semibold text-slate-900">
                            {{ $purchaseRequest->items->count() }}
                        </p>
                    </div>

                    <div class="border-l border-slate-200 px-5 py-3">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">
                            Total Quantity
                        </p>
                        <p class="mt-0.5 text-sm font-semibold text-slate-900">
                            {{ $purchaseRequest->items->sum('qty') }}
                        </p>
                    </div>

                    <div class="border-l border-slate-200 px-5 py-3 text-right">
                        <p class="text-[11px] font-medium uppercase tracking-wide text-slate-500">
                            Estimated Total
                        </p>
                        <p class="mt-0.5 text-base font-semibold text-slate-900">
                            {{ number_format($purchaseRequest->items->sum('total'), 2) }}
                        </p>
                    </div>

                </div>

            </section>




            {{-- Confirmation Notice --}}
            <section class="rounded-lg border border-amber-200 bg-amber-50 px-5 py-4">

                <div class="flex gap-3">

                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-amber-100">
                        <i class="bx bx-info-circle text-lg text-amber-600"></i>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-amber-800">
                            Ready to confirm?
                        </h3>

                        <p class="mt-0.5 text-sm leading-5 text-amber-700">
                            Confirming this request will create a purchase order using the vendor
                            and item details shown above.
                        </p>
                    </div>

                </div>

            </section>


            {{-- Actions --}}
            <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">

                <a href="{{ route('purchase-requests.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-md border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">

                    <i class="bx bx-x"></i>
                    Cancel

                </a>

                <a href="{{ route('purchase-requests.compare', $purchaseRequest) }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                    <i class="bx bx-git-compare text-lg"></i>
                    Compare Purchase Requests
                </a>


                <button type="button" id="confirmPurchaseRequestBtn"
                    class="inline-flex items-center justify-center gap-2 rounded-md bg-slate-800 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900 disabled:cursor-not-allowed disabled:opacity-60">

                    <i class="bx bx-check-circle"></i>

                    <span id="confirmPurchaseRequestBtnText">
                        Confirm Order
                    </span>

                </button>

            </div>

        </div>

    </div>


    @push('scripts')
        <script>
            $(document).ready(function() {

                $('#confirmPurchaseRequestBtn').on('click', function() {

                    const button = $(this);
                    const buttonText = $('#confirmPurchaseRequestBtnText');
                    const originalText = buttonText.text();

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
                                response.message || 'Purchase request confirmed successfully.'
                            );

                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 800);

                        },

                        error: function(xhr) {

                            button.prop('disabled', false);
                            buttonText.text(originalText);

                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to confirm purchase request.'
                            );

                        }

                    });

                });

            });
        </script>
    @endpush

</x-layouts.app>
