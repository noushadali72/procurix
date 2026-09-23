<x-layouts.app title="Compare Purchase Requests">

    <div class="max-w-7xl space-y-5">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <a
                href="{{ route('admin.dashboard') }}"
                class="transition hover:text-gray-900"
            >
                Dashboard
            </a>

            <i class="bx bx-chevron-right text-gray-400"></i>

            <a
                href="{{ route('purchase-requests.index') }}"
                class="transition hover:text-gray-900"
            >
                Purchase Requests
            </a>

            <i class="bx bx-chevron-right text-gray-400"></i>

            <a
                href="{{ route('purchase-requests.confirmation', $purchaseRequest) }}"
                class="transition hover:text-gray-900"
            >
                {{ $purchaseRequest->request_number }}
            </a>

            <i class="bx bx-chevron-right text-gray-400"></i>

            <span class="font-medium text-gray-700">
                Compare
            </span>
        </div>

        {{-- Header --}}
        <div class="flex flex-col gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100">
                        <i class="bx bx-git-compare text-xl text-gray-600"></i>
                    </div>

                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">
                            Compare Purchase Requests
                        </h1>

                        <p class="mt-0.5 text-sm text-gray-500">
                            Compare vendor requests and select one to create the purchase order.
                        </p>
                    </div>
                </div>
            </div>

            <a
                href="{{ route('purchase-requests.confirmation', $purchaseRequest) }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                <i class="bx bx-arrow-back"></i>
                Back to Confirmation
            </a>

        </div>

        {{-- Comparison info --}}
        <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3">
            <div class="flex items-start gap-3">
                <i class="bx bx-info-circle mt-0.5 text-lg text-blue-600"></i>

                <div>
                    <p class="text-sm font-medium text-blue-900">
                        Vendor Request Comparison
                    </p>

                    <p class="mt-0.5 text-sm text-blue-700">
                        Select one purchase request. The selected request will be used to create
                        the purchase order and the other matching vendor requests will be cancelled.
                    </p>
                </div>
            </div>
        </div>

        @if ($purchaseRequests->count() < 2)

            <div class="rounded-xl border border-gray-200 bg-white px-6 py-12 text-center shadow-sm">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                    <i class="bx bx-search-alt text-2xl text-gray-500"></i>
                </div>

                <h2 class="mt-4 text-base font-semibold text-gray-900">
                    No other vendor requests available
                </h2>

                <p class="mx-auto mt-1 max-w-md text-sm text-gray-500">
                    There are currently no other sent purchase requests for the same material
                    requirement.
                </p>

                <a
                    href="{{ route('purchase-requests.confirmation', $purchaseRequest) }}"
                    class="mt-5 inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-800"
                >
                    <i class="bx bx-arrow-back"></i>
                    Back to Confirmation
                </a>
            </div>

        @else

            {{-- Vendor summary --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">

                @foreach ($purchaseRequests as $request)
                    <label
                        class="vendor-card relative cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition hover:border-gray-400"
                        data-request-id="{{ $request->id }}"
                    >
                        <input
                            type="radio"
                            name="selected_request"
                            value="{{ $request->id }}"
                            class="peer sr-only"
                            @checked($request->id === $purchaseRequest->id)
                        >

                        <div class="flex items-start justify-between gap-3">

                            <div class="flex min-w-0 items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100">
                                    <i class="bx bx-store text-lg text-gray-600"></i>
                                </div>

                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-gray-900">
                                        {{ $request->vendor?->company_name ?? $request->vendor?->name ?? 'Unknown Vendor' }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-500">
                                        {{ $request->request_number }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-gray-300 peer-checked:border-gray-900 peer-checked:bg-gray-900">
                                <i class="bx bx-check hidden text-xs text-white peer-checked:block"></i>
                            </div>

                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3 border-t border-gray-100 pt-3">

                            <div>
                                <p class="text-xs text-gray-500">
                                    Items
                                </p>

                                <p class="mt-1 text-sm font-medium text-gray-900">
                                    {{ $request->items->count() }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">
                                    Total
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                    {{ number_format($request->items->sum('total'), 2) }}
                                </p>
                            </div>

                        </div>
                    </label>
                @endforeach

            </div>

            {{-- Comparison table --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

                <div class="border-b border-gray-200 px-5 py-4">
                    <div>
                        <h2 class="text-base font-semibold text-gray-900">
                            Request Comparison
                        </h2>

                        <p class="mt-0.5 text-sm text-gray-500">
                            Review quantities, units, prices and totals before confirming the order.
                        </p>
                    </div>
                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-[1000px] w-full text-left text-sm">

                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="w-64 px-5 py-3 font-medium">
                                    Material
                                </th>

                                @foreach ($purchaseRequests as $request)
                                    <th class="min-w-[220px] border-l border-gray-200 px-5 py-3">
                                        <div class="font-semibold text-gray-700">
                                            {{ $request->vendor?->company_name ?? $request->vendor?->name ?? 'Unknown Vendor' }}
                                        </div>

                                        <div class="mt-0.5 normal-case tracking-normal text-xs text-gray-400">
                                            {{ $request->request_number }}
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @foreach ($purchaseRequest->items as $baseItem)

                                <tr class="align-top">

                                    <td class="px-5 py-4">
                                        <div class="flex items-start gap-3">

                                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100">
                                                <i class="bx bx-package text-gray-500"></i>
                                            </div>

                                            <div>
                                                <p class="font-medium text-gray-900">
                                                    {{ $baseItem->rawMaterial?->name }}
                                                </p>

                                                @if ($baseItem->rawMaterial?->sku)
                                                    <p class="mt-0.5 text-xs text-gray-500">
                                                        SKU: {{ $baseItem->rawMaterial->sku }}
                                                    </p>
                                                @endif
                                            </div>

                                        </div>
                                    </td>

                                    @foreach ($purchaseRequests as $request)

                                        @php
                                            $item = $request->items->firstWhere(
                                                'raw_material_id',
                                                $baseItem->raw_material_id
                                            );
                                        @endphp

                                        <td class="border-l border-gray-200 px-5 py-4">

                                            @if ($item)

                                                <div class="space-y-2">

                                                    <div class="flex justify-between gap-4">
                                                        <span class="text-gray-500">
                                                            Quantity
                                                        </span>

                                                        <span class="font-medium text-gray-900">
                                                            {{ $item->qty }}
                                                        </span>
                                                    </div>

                                                    <div class="flex justify-between gap-4">
                                                        <span class="text-gray-500">
                                                            Unit
                                                        </span>

                                                        <span class="font-medium text-gray-900">
                                                            {{ $item->unit?->short_name ?? $item->unit?->name ?? '-' }}
                                                        </span>
                                                    </div>

                                                    <div class="flex justify-between gap-4">
                                                        <span class="text-gray-500">
                                                            Unit Cost
                                                        </span>

                                                        <span class="font-medium text-gray-900">
                                                            {{ number_format($item->unit_cost, 2) }}
                                                        </span>
                                                    </div>

                                                    <div class="flex justify-between gap-4 border-t border-gray-100 pt-2">
                                                        <span class="font-medium text-gray-600">
                                                            Line Total
                                                        </span>

                                                        <span class="font-semibold text-gray-900">
                                                            {{ number_format($item->total, 2) }}
                                                        </span>
                                                    </div>

                                                </div>

                                            @else

                                                <span class="text-gray-400">
                                                    Not quoted
                                                </span>

                                            @endif

                                        </td>

                                    @endforeach

                                </tr>

                            @endforeach

                        </tbody>

                        {{-- Totals --}}
                        <tfoot class="border-t border-gray-200 bg-gray-50">

                            <tr>

                                <td class="px-5 py-4">
                                    <span class="font-semibold text-gray-900">
                                        Estimated Total
                                    </span>
                                </td>

                                @foreach ($purchaseRequests as $request)

                                    <td class="border-l border-gray-200 px-5 py-4">
                                        <span class="text-base font-semibold text-gray-900">
                                            {{ number_format($request->items->sum('total'), 2) }}
                                        </span>
                                    </td>

                                @endforeach

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

            {{-- Bottom action --}}
            <div class="flex flex-col gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <p class="text-sm font-medium text-gray-900">
                        Ready to confirm?
                    </p>

                    <p class="mt-0.5 text-xs text-gray-500">
                        The selected vendor request will create the purchase order.
                    </p>
                </div>

                <div class="flex items-center gap-3">

                    <a
                        href="{{ route('purchase-requests.confirmation', $purchaseRequest) }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Cancel
                    </a>

                    <button
                        type="button"
                        id="confirmComparisonBtn"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-5 py-2 text-sm font-medium text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50"
                    >
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
            $(document).ready(function () {

                $('.vendor-card').on('click', function () {
                    $('.vendor-card').removeClass('border-gray-900 ring-1 ring-gray-900');
                    $(this).addClass('border-gray-900 ring-1 ring-gray-900');

                    $(this).find('input[type="radio"]').prop('checked', true);
                });

                $('#confirmComparisonBtn').on('click', function () {

                    const selectedRequest = $('input[name="selected_request"]:checked').val();

                    if (!selectedRequest) {
                        showToast('error', 'Please select a purchase request.');
                        return;
                    }

                    const button = $(this);
                    const text = $('#confirmComparisonText');

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
                            selected_purchase_request: selectedRequest
                        },
                        headers: {
                            Accept: 'application/json'
                        },
                        success: function (response) {
                            showToast(
                                'success',
                                response.message || 'Purchase order created successfully.'
                            );

                            setTimeout(function () {
                                window.location.href = response.redirect;
                            }, 700);
                        },
                        error: function (xhr) {
                            button.prop('disabled', false);
                            text.text('Confirm Selected Order');

                            if (xhr.status === 422) {
                                const errors = xhr.responseJSON?.errors || {};

                                const message =
                                    errors.selected_purchase_request?.[0] ||
                                    xhr.responseJSON?.message ||
                                    'Unable to confirm the selected request.';

                                showToast('error', message);
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