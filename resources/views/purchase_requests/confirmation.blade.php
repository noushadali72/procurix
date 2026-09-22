<x-layouts.app title="Confirm Purchase Request">

<div class="mb-6 flex items-center justify-between">

    <div>
        <h1 class="text-2xl font-bold text-slate-900">
            Confirm Purchase Request
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Review the purchase request details before confirming the order.
        </p>
    </div>

    <a href="{{ route('purchase-requests.index') }}"
        class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
        <i class="bx bx-arrow-back"></i>
        Back
    </a>

</div>


<div class="space-y-6">

    {{-- Request Information --}}
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">

        <div class="mb-5 flex items-center justify-between">

            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Purchase Request Information
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Review the vendor and request details.
                </p>
            </div>

            <span
                class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold capitalize text-slate-700">
                {{ str_replace('_', ' ', $purchaseRequest->status) }}
            </span>

        </div>


        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

            {{-- Request Number --}}
            <div>
                <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-500">
                    Request Number
                </p>

                <p class="text-sm font-semibold text-slate-900">
                    {{ $purchaseRequest->request_number ?? '#' . $purchaseRequest->id }}
                </p>
            </div>


            {{-- Vendor --}}
            <div>
                <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-500">
                    Vendor
                </p>

                <p class="text-sm font-semibold text-slate-900">
                    {{ $purchaseRequest->vendor->company_name ?: $purchaseRequest->vendor->name }}
                </p>

                @if ($purchaseRequest->vendor->contact_person)
                    <p class="mt-1 text-xs text-slate-500">
                        {{ $purchaseRequest->vendor->contact_person }}
                    </p>
                @endif
            </div>


            {{-- Email --}}
            <div>
                <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-500">
                    Email
                </p>

                <p class="text-sm text-slate-700">
                    {{ $purchaseRequest->vendor->email ?: '—' }}
                </p>
            </div>


            {{-- Phone --}}
            <div>
                <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-500">
                    Phone
                </p>

                <p class="text-sm text-slate-700">
                    {{ $purchaseRequest->vendor->phone ?: '—' }}
                </p>
            </div>


            {{-- Delivery Address --}}
            <div class="md:col-span-2">
                <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-500">
                    Delivery Address
                </p>

                <p class="text-sm text-slate-700">
                    {{ $purchaseRequest->delivery_address ?: '—' }}
                </p>
            </div>


            {{-- Notes --}}
            @if ($purchaseRequest->notes)
                <div class="md:col-span-2 lg:col-span-3">
                    <p class="mb-1 text-xs font-medium uppercase tracking-wide text-slate-500">
                        Notes
                    </p>

                    <p class="text-sm text-slate-700">
                        {{ $purchaseRequest->notes }}
                    </p>
                </div>
            @endif

        </div>

    </div>


    {{-- Raw Materials --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">

            <h2 class="text-lg font-semibold text-slate-800">
                Raw Materials
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Review the materials included in this purchase request.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full min-w-[700px]">

                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">

                        <th
                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            #
                        </th>

                        <th
                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Raw Material
                        </th>

                        <th
                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            SKU
                        </th>

                        <th
                            class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Quantity
                        </th>

                         <th
                            class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Unit Cost
                        </th>

                        <th
                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Unit
                        </th>

                    </tr>
                </thead>


                <tbody class="divide-y divide-slate-100">

                    @foreach ($purchaseRequest->items as $index => $item)

                        <tr class="transition hover:bg-slate-50">

                            <td class="px-6 py-4 text-sm text-slate-500">
                                {{ $index + 1 }}
                            </td>

                            <td class="px-6 py-4">

                                <p class="text-sm font-medium text-slate-900">
                                    {{ $item->rawMaterial->name }}
                                </p>

                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $item->rawMaterial->sku ?: '—' }}
                            </td>

                            <td class="px-6 py-4 text-right text-sm font-medium text-slate-900">
                                {{ $item->qty }}
                            </td>

                              <td class="px-6 py-4 text-right text-sm font-medium text-slate-900">
                                {{ $item->unit_cost??'-' }}
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $item->unit->name }}
                                <span class="text-slate-400">
                                    ({{ $item->unit->short_name }})
                                </span>
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    {{-- Confirmation Notice --}}
    <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">

        <div class="flex gap-3">

            <i class="bx bx-info-circle mt-0.5 text-xl text-amber-600"></i>

            <div>
                <h3 class="text-sm font-semibold text-amber-800">
                    Confirm Purchase Request
                </h3>

                <p class="mt-1 text-sm text-amber-700">
                    Please make sure all vendor, delivery, and raw material
                    details are correct before confirming this purchase request.
                </p>
            </div>

        </div>

    </div>


    {{-- Actions --}}
    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

        <a href="{{ route('purchase-requests.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
            <i class="bx bx-x"></i>
            Cancel
        </a>

        <button type="button" id="confirmPurchaseRequestBtn"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-800 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900 disabled:cursor-not-allowed disabled:opacity-60">

            <i class="bx bx-check-circle"></i>

            <span id="confirmPurchaseRequestBtnText">
                Confirm Order
            </span>

        </button>

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
