<x-layouts.app title="Vendor Bills">

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                Vendor Bills
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                View and manage vendor bills generated from purchase orders.
            </p>
        </div>


        <button type="button" id="openVendorBillModal"
            class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">

            <i class="bx bx-plus text-lg"></i>

            Create Vendor Bill

        </button>

    </div>


    {{-- Vendor Bills List --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        {{-- Card Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">

            <div>
                <h3 class="font-semibold text-gray-900">
                    Vendor Bill List
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    All generated vendor bills.
                </p>
            </div>

            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                {{ $vendorBills->total() }} Bills
            </span>

        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">

                    <tr class="text-xs font-medium uppercase tracking-wide text-gray-500">

                        <th class="px-6 py-3.5">
                            Bill Number
                        </th>

                        <th class="px-6 py-3.5">
                            Vendor
                        </th>

                        <th class="px-6 py-3.5">
                            PO Number
                        </th>

                        <th class="px-6 py-3.5">
                            Bill Date
                        </th>

                        <th class="px-6 py-3.5">
                            Due Date
                        </th>

                        <th class="px-6 py-3.5 text-right">
                            Total
                        </th>

                        <th class="px-6 py-3.5">
                            Status
                        </th>

                        <th class="px-6 py-3.5 text-right">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($vendorBills as $vendorBill)
                        @php
                            $statusClass = match ($vendorBill->status) {
                                'paid' => 'bg-green-50 text-green-700',
                                'partially_paid' => 'bg-amber-50 text-amber-700',
                                'overdue' => 'bg-red-50 text-red-700',
                                'unpaid' => 'bg-yellow-50 text-yellow-700',
                                default => 'bg-gray-100 text-gray-700',
                            };

                            $statusDot = match ($vendorBill->status) {
                                'paid' => 'bg-green-500',
                                'partially_paid' => 'bg-amber-500',
                                'overdue' => 'bg-red-500',
                                'unpaid' => 'bg-yellow-500',
                                default => 'bg-gray-400',
                            };

                            $statusLabel = match ($vendorBill->status) {
                                'partially_paid' => 'Partially Paid',
                                default => ucfirst(str_replace('_', ' ', $vendorBill->status)),
                            };
                        @endphp

                        <tr class="transition hover:bg-gray-50">

                            {{-- Bill Number --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                        <i class="bx bx-receipt text-lg"></i>
                                    </div>

                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $vendorBill->bill_number }}
                                        </p>
                                    </div>

                                </div>

                            </td>

                            {{-- Vendor --}}
                            <td class="px-6 py-4">

                                <p class="font-medium text-gray-900">
                                    {{ $vendorBill->vendor->company_name ?? ($vendorBill->vendor->name ?? '-') }}
                                </p>

                            </td>

                            {{-- PO Number --}}
                            <td class="px-6 py-4">

                                <a href="{{ route('purchase-orders.show', $vendorBill->purchaseOrder) }}"
                                    class="font-medium text-blue-900 underline">
                                    {{ $vendorBill->purchaseOrder->order_number ?? '-' }}
                                </a>

                            </td>

                            {{-- Bill Date --}}
                            <td class="px-6 py-4 text-gray-600">

                                {{ $vendorBill->bill_date ? \Carbon\Carbon::parse($vendorBill->bill_date)->format('d M Y') : '-' }}

                            </td>

                            {{-- Due Date --}}
                            <td class="px-6 py-4 text-gray-600">

                                {{ $vendorBill->due_date ? \Carbon\Carbon::parse($vendorBill->due_date)->format('d M Y') : '-' }}

                            </td>

                            {{-- Total --}}
                            <td class="px-6 py-4 text-right">

                                <span class="font-semibold text-gray-900">
                                    {{ number_format($vendorBill->total, 2) }}
                                </span>

                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">

                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>

                                    {{ $statusLabel }}
                                </span>

                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('vendor-bills.show', $vendorBill) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white">
                                        <i class="bx bx-show"></i>
                                        View
                                    </a>

                                    <a href="{{ route('vendor-bills.generatepdf', $vendorBill) }}" target="_blank"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white">
                                        <i class="bx bx-file"></i>
                                        PDF
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="px-6 py-12">

                                <div class="flex flex-col items-center justify-center text-center">

                                    <div
                                        class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                        <i class="bx bx-receipt text-2xl"></i>
                                    </div>

                                    <h3 class="font-medium text-gray-900">
                                        No vendor bills found
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Vendor bills will appear here after they are generated from received purchase
                                        orders.
                                    </p>

                                </div>

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if ($vendorBills->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $vendorBills->links() }}
            </div>
        @endif

    </div>


    {{-- Create Vendor Bill Modal --}}
    <div id="vendorBillModal" class="fixed inset-0 z-50 hidden overflow-y-auto">

        <div class="flex min-h-screen items-center justify-center px-4 py-8">

            {{-- Overlay --}}
            <div id="vendorBillModalOverlay" class="fixed inset-0 bg-black/50">
            </div>


            {{-- Modal --}}
            <div class="relative z-10 w-full max-w-lg rounded-xl bg-white shadow-xl">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            Create Vendor Bill
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Select a received purchase order to generate a vendor bill.
                        </p>
                    </div>


                    <button type="button" id="closeVendorBillModal"
                        class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900">

                        <i class="bx bx-x text-2xl"></i>

                    </button>

                </div>


                {{-- Form --}}
                <form id="vendorBillForm" action="{{ route('vendor-bills.store') }}" method="POST" novalidate>

                    @csrf


                    <div class="p-6">

                        <label for="purchase_order_id" class="mb-2 block text-sm font-medium text-gray-700">

                            Purchase Order
                            <span class="text-red-500">*</span>

                        </label>


                        <select name="purchase_order_id" id="purchase_order_id"
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200">

                            <option value="">
                                Select Purchase Order
                            </option>

                            @foreach ($purchaseOrders as $purchaseOrder)
                                
                                <option value="{{ $purchaseOrder->id }}">

                                    {{ $purchaseOrder->order_number }}

                                    -
                                    {{ $purchaseOrder->vendor->company_name ?: $purchaseOrder->vendor->name }}

                                    - {{ $purchaseOrder->total }}
                                </option>
                            @endforeach

                        </select>


                        <span id="purchaseOrderErr" class="mt-1 block text-xs text-red-600">
                        </span>


                        @if ($purchaseOrders->isEmpty())
                            <p class="mt-2 text-xs text-gray-500">
                                No received purchase orders are available for vendor bill generation.
                            </p>
                        @endif

                    </div>


                    {{-- Footer --}}
                    <div class="flex justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">

                        <button type="button" id="cancelVendorBillModal"
                            class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">

                            Cancel

                        </button>


                        <button type="submit" id="createVendorBillBtn"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-60">

                            <i class="bx bx-receipt"></i>

                            <span id="createVendorBillBtnText">
                                Create Vendor Bill
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


@push('scripts')

<script>
    $(document).ready(function() {

        const $modal = $('#vendorBillModal');
        const $form = $('#vendorBillForm');
        const $button = $('#createVendorBillBtn');
        const $buttonText = $('#createVendorBillBtnText');


        // Open modal
        $('#openVendorBillModal').on('click', function() {
            $modal.removeClass('hidden');
        });


        // Close modal
        function closeVendorBillModal() {
            $modal.addClass('hidden');
            $form[0].reset();
            $('#purchaseOrderErr').text('');
        }


        $('#closeVendorBillModal, #cancelVendorBillModal, #vendorBillModalOverlay')
            .on('click', function() {
                closeVendorBillModal();
            });


        // Create vendor bill
        $form.on('submit', function(e) {
            e.preventDefault();
            $('#purchaseOrderErr').text('');
            const originalText = $buttonText.text();
            $button.prop('disabled', true);
            $buttonText.text('Creating...');

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: $form.serialize(),
                headers: {
                    Accept: 'application/json'
                },
                success: function(response) {
                    showToast(
                        'success',
                        response.message ||
                        'Vendor bill generated successfully.'
                    );

                    closeVendorBillModal();
                    setTimeout(function() {

                        window.location.href =
                            response.redirect;

                    }, 800);

                },


                error: function(xhr) {

                    if (xhr.status === 422) {

                        const errors =
                            xhr.responseJSON?.errors || {};

                        $('#purchaseOrderErr').text(
                            errors.purchase_order_id?.[0] || ''
                        );


                        if (!errors.purchase_order_id) {

                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to create vendor bill.'
                            );

                        }

                        return;
                    }


                    showToast(
                        'error',
                        xhr.responseJSON?.message ||
                        'Something went wrong.'
                    );

                },


                complete: function() {

                    $button.prop('disabled', false);

                    $buttonText.text(originalText);

                }

            });

        });

    });
</script>

@endpush

</x-layouts.app>
