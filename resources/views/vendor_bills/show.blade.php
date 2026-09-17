<x-layouts.app title="Vendor Bill">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <a
                href="{{ route('vendor-bills.index') }}"
                class="inline-flex items-center text-sm font-medium text-gray-500 transition hover:text-gray-900"
            >
                <i class="bx bx-arrow-back mr-1.5"></i>
                Back to Vendor Bills
            </a>

            <div class="mt-3">

                <div class="flex flex-wrap items-center gap-2">

                    <h1 class="text-xl font-semibold text-gray-900">
                        Vendor Bill #{{ $vendorBill->bill_number }}
                    </h1>

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

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}"
                    >
                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                        {{ $statusLabel }}
                    </span>

                </div>

                <p class="mt-1 text-sm text-gray-500">
                    View vendor bill details, items, and payment information.
                </p>

            </div>
        </div>

        {{-- Header Actions --}}
        <div class="flex items-center gap-2">

            <a
                href="{{ route('vendor-bills.generatepdf', $vendorBill) }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800"
            >
                <i class="bx bx-file"></i>
                Generate PDF
            </a>

            @if ($vendorBill->due_amount > 0)
                <button
                    type="button"
                    id="openPaymentModal"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800"
                >
                    <i class="bx bx-money"></i>
                    Make Payment
                </button>
            @endif

        </div>

    </div>


    {{-- Bill Information --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-4">

            <h3 class="font-semibold text-gray-900">
                Bill Information
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Vendor bill and purchase order information.
            </p>

        </div>

        <div class="grid grid-cols-1 gap-x-8 gap-y-6 p-6 sm:grid-cols-2 lg:grid-cols-3">

            {{-- Bill Number --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Bill Number
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->bill_number }}
                </p>
            </div>

            {{-- Vendor --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Vendor
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->vendor->company_name ?? $vendorBill->vendor->name ?? '-' }}
                </p>
            </div>

            {{-- Purchase Order --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Purchase Order
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->purchaseOrder->order_number ?? '-' }}
                </p>
            </div>

            {{-- Bill Date --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Bill Date
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->bill_date
                        ? \Carbon\Carbon::parse($vendorBill->bill_date)->format('d M Y')
                        : '-' }}
                </p>
            </div>

            {{-- Due Date --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Due Date
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->due_date
                        ? \Carbon\Carbon::parse($vendorBill->due_date)->format('d M Y')
                        : '-' }}
                </p>
            </div>

            {{-- Status --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Status
                </p>

                <div class="mt-1.5">
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}"
                    >
                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>

        </div>

    </div>


    {{-- Vendor Information --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-4">

            <h3 class="font-semibold text-gray-900">
                Vendor Information
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Vendor details associated with this bill.
            </p>

        </div>

        <div class="grid grid-cols-1 gap-x-8 gap-y-6 p-6 sm:grid-cols-2 lg:grid-cols-3">

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Vendor
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->vendor->company_name ?? $vendorBill->vendor->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Contact
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->vendor->phone ?? $vendorBill->vendor->contact_no ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Email
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->vendor->email ?? '-' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Bill Items --}}
    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">

            <div>
                <h3 class="font-semibold text-gray-900">
                    Bill Items
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Items included in this vendor bill.
                </p>
            </div>

            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                {{ $vendorBill->items->count() }} Items
            </span>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[750px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">

                    <tr class="text-xs font-medium uppercase tracking-wide text-gray-500">

                        <th class="px-6 py-3.5">
                            Item
                        </th>

                        <th class="px-6 py-3.5">
                            Unit
                        </th>

                        <th class="px-6 py-3.5 text-right">
                            Quantity
                        </th>

                        <th class="px-6 py-3.5 text-right">
                            Unit Price
                        </th>

                        <th class="px-6 py-3.5 text-right">
                            Total
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($vendorBill->items as $item)

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-6 py-4">

                                <p class="font-medium text-gray-900">
                                    {{ $item->product->name
                                        ?? $item->rawMaterial->name
                                        ?? '-' }}
                                </p>

                                @if ($item->rawMaterial?->sku)
                                    <p class="mt-0.5 text-xs text-gray-500">
                                        {{ $item->rawMaterial->sku }}
                                    </p>
                                @endif

                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $item->unit->short_name ?? $item->unit->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-right font-medium text-gray-900">
                                {{ $item->qty }}
                            </td>

                            <td class="px-6 py-4 text-right text-gray-700">
                                {{ number_format($item->unit_price, 2) }}
                            </td>

                            <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                {{ number_format($item->line_total, 2) }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                                No bill items found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Financial Summary --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-4">

            <h3 class="font-semibold text-gray-900">
                Financial Summary
            </h3>

        </div>

        <div class="p-6">

            <div class="ml-auto max-w-sm space-y-3">

                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">
                        Subtotal
                    </span>

                    <span class="font-medium text-gray-900">
                        {{ number_format($vendorBill->subtotal, 2) }}
                    </span>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">
                        Tax
                    </span>

                    <span class="font-medium text-gray-900">
                        {{ number_format($vendorBill->tax, 2) }}
                    </span>
                </div>

                @if (isset($vendorBill->discount))
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">
                            Discount
                        </span>

                        <span class="font-medium text-gray-900">
                            {{ number_format($vendorBill->discount, 2) }}
                        </span>
                    </div>
                @endif

                <div class="border-t border-gray-200 pt-3">

                    <div class="flex items-center justify-between">

                        <span class="font-semibold text-gray-700">
                            Total
                        </span>

                        <span class="text-lg font-bold text-gray-900">
                            {{ number_format($vendorBill->total, 2) }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Notes --}}
    @if ($vendorBill->notes)

        <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-4">

                <h3 class="font-semibold text-gray-900">
                    Notes
                </h3>

            </div>

            <div class="p-6">

                <p class="whitespace-pre-line text-sm leading-6 text-gray-700">
                    {{ $vendorBill->notes }}
                </p>

            </div>

        </div>

    @endif

    {{-- Payment Modal --}}
<div
    id="paymentModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4"
>
    <div class="w-full max-w-lg rounded-xl bg-white shadow-xl">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
            <div>
                <h3 class="font-semibold text-gray-900">
                    Make Payment
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Record a payment for this vendor bill.
                </p>
            </div>

            <button
                type="button"
                id="closePaymentModal"
                class="text-gray-400 transition hover:text-gray-700"
            >
                <i class="bx bx-x text-2xl"></i>
            </button>
        </div>

        {{-- Form --}}
        <form
            id="paymentForm"
            action="{{ route('vendor-payments.store', $vendorBill) }}"
            method="POST"
            class="p-6"
        >
            @csrf



            <div class="space-y-5">

                {{-- Outstanding --}}
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">
                            Outstanding Amount
                        </span>

                        <span class="font-semibold text-gray-900">
                            {{ number_format($vendorBill->due_amount, 2) }}
                        </span>
                    </div>
                </div>

                {{-- Amount --}}
                <div>
                    <label
                        for="payment_amount"
                        class="mb-1 block text-sm font-medium text-gray-700"
                    >
                        Payment Amount
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0.01"
                        max="{{ $vendorBill->due_amount }}"
                        id="payment_amount"
                        name="amount"
                        placeholder="Enter payment amount"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                    >

                    <p
                        data-error="amount"
                        class="mt-1 hidden text-sm text-red-600"
                    ></p>
                </div>

                {{-- Payment Method --}}
                <div>
                    <label
                        for="payment_method"
                        class="mb-1 block text-sm font-medium text-gray-700"
                    >
                        Payment Method
                    </label>

                    <select
                        id="payment_method"
                        name="payment_method"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                    >
                        <option value="">Select payment method</option>
                        <option value="cash">Cash</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cheque">Cheque</option>
                        <option value="card">Card</option>
                        <option value="other">Other</option>
                    </select>

                    <p
                        data-error="payment_method"
                        class="mt-1 hidden text-sm text-red-600"
                    ></p>
                </div>

                {{-- Payment Date --}}
                <div>
                    <label
                        for="payment_date"
                        class="mb-1 block text-sm font-medium text-gray-700"
                    >
                        Payment Date
                    </label>

                    <input
                        type="date"
                        id="payment_date"
                        name="payment_date"
                        value="{{ now()->toDateString() }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                    >

                    <p
                        data-error="payment_date"
                        class="mt-1 hidden text-sm text-red-600"
                    ></p>
                </div>

                {{-- Transaction ID --}}
                <div>
                    <label
                        for="transaction_id"
                        class="mb-1 block text-sm font-medium text-gray-700"
                    >
                        Transaction ID
                        <span class="font-normal text-gray-400">(Optional)</span>
                    </label>

                    <input
                        type="text"
                        id="transaction_id"
                        name="transaction_id"
                        placeholder="Enter transaction ID"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                    >

                    <p
                        data-error="transaction_id"
                        class="mt-1 hidden text-sm text-red-600"
                    ></p>
                </div>

                {{-- Notes --}}
                <div>
                    <label
                        for="payment_notes"
                        class="mb-1 block text-sm font-medium text-gray-700"
                    >
                        Notes
                        <span class="font-normal text-gray-400">(Optional)</span>
                    </label>

                    <textarea
                        id="payment_notes"
                        name="notes"
                        rows="3"
                        placeholder="Enter payment notes"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                    ></textarea>

                    <p
                        data-error="notes"
                        class="mt-1 hidden text-sm text-red-600"
                    ></p>
                </div>

            </div>

            {{-- Actions --}}
            <div class="mt-6 flex justify-end gap-3 border-t border-gray-200 pt-5">

                <button
                    type="button"
                    id="cancelPayment"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    id="submitPayment"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800"
                >
                    <i class="bx bx-check"></i>
                    Record Payment
                </button>

            </div>
        </form>
    </div>
</div>


@push('scripts')
<script>
    $(function () {

        function openPaymentModal() {
            $('#paymentModal')
                .removeClass('hidden')
                .addClass('flex');
        }

        function closePaymentModal() {
            $('#paymentModal')
                .removeClass('flex')
                .addClass('hidden');
        }

        function clearPaymentErrors() {
            $('[data-error]').addClass('hidden').text('');
        }

        function showPaymentErrors(errors) {
            $.each(errors, function (field, messages) {
                const error = $('[data-error="' + field + '"]');

                if (error.length) {
                    error
                        .removeClass('hidden')
                        .text(messages[0]);
                }
            });
        }

        $('#openPaymentModal').on('click', openPaymentModal);

        $('#closePaymentModal, #cancelPayment').on('click', closePaymentModal);

        $('#paymentModal').on('click', function (e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });

        $('#paymentForm').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            const button = $('#submitPayment');

            clearPaymentErrors();

            button
                .prop('disabled', true)
                .html(`
                    <i class="bx bx-loader-alt bx-spin"></i>
                    Processing...
                `);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),

                success: function (response) {
                    showToast('green', response.message);

                    setTimeout(function () {
                        window.location.reload();
                    }, 500);
                },

                error: function (xhr) {
                    if (xhr.status === 422) {
                        const response = xhr.responseJSON;

                        if (response.errors) {
                            showPaymentErrors(response.errors);
                        }

                        if (response.message) {
                            showToast('red', response.message);
                        }
                    } else {
                        showToast(
                            'red',
                            xhr.responseJSON?.message || 'Something went wrong.'
                        );
                    }

                    button
                        .prop('disabled', false)
                        .html(`
                            <i class="bx bx-check"></i>
                            Record Payment
                        `);
                }
            });
        });

    });
</script>
@endpush


</x-layouts.app>