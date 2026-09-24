<x-layouts.app title="Vendor Credit">

    <div class="max-w-7xl">

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <div class="flex items-center gap-3">

                    <h1 class="text-xl font-semibold text-slate-900">
                        {{ $vendorCredit->credit_number }}
                    </h1>

                    <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">
                        {{ ucfirst(str_replace('_', ' ', $vendorCredit->status)) }}
                    </span>

                </div>

                <p class="mt-1 text-sm text-slate-500">
                    Vendor credit details and settlement.
                </p>

            </div>

            <a href="{{ route('purchase-returns.show', $vendorCredit->purchaseReturn) }}"
                class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">

                View Purchase Return

            </a>

        </div>


        {{-- Summary --}}
        <section class="mb-5 overflow-hidden rounded-lg border border-slate-200 bg-white">

            <div class="grid grid-cols-2 lg:grid-cols-4">

                <div class="border-b border-r border-slate-100 px-5 py-4 lg:border-b-0">

                    <p class="text-[11px] uppercase tracking-wide text-slate-400">
                        Credit Amount
                    </p>

                    <p class="mt-1 text-lg font-semibold text-slate-900">
                        {{ number_format($vendorCredit->amount, 2) }}
                    </p>

                </div>


                <div class="border-b border-slate-100 px-5 py-4 lg:border-b-0 lg:border-r">

                    <p class="text-[11px] uppercase tracking-wide text-slate-400">
                        Applied
                    </p>

                    <p class="mt-1 text-lg font-semibold text-slate-900">
                        {{ number_format($vendorCredit->applied_amount, 2) }}
                    </p>

                </div>


                <div class="border-r border-slate-100 px-5 py-4">

                    <p class="text-[11px] uppercase tracking-wide text-slate-400">
                        Refunded
                    </p>

                    <p class="mt-1 text-lg font-semibold text-slate-900">
                        {{ number_format($vendorCredit->refunded_amount, 2) }}
                    </p>

                </div>


                <div class="px-5 py-4">

                    <p class="text-[11px] uppercase tracking-wide text-slate-400">
                        Available
                    </p>

                    <p class="mt-1 text-lg font-bold text-slate-900">
                        {{ number_format($vendorCredit->remaining_amount, 2) }}
                    </p>

                </div>

            </div>

        </section>


        {{-- Information --}}
        <section class="mb-5 rounded-lg border border-slate-200 bg-white">

            <div class="border-b border-slate-200 px-5 py-4">
                <h2 class="text-sm font-semibold text-slate-900">
                    Credit Information
                </h2>
            </div>

            <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-3">

                <div>
                    <p class="text-xs text-slate-400">Vendor</p>

                    <p class="mt-1 text-sm font-medium text-slate-900">
                        {{ $vendorCredit->vendor->company_name ?: $vendorCredit->vendor->name }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-slate-400">Purchase Return</p>

                    <a href="{{ route('purchase-returns.show', $vendorCredit->purchaseReturn) }}"
                        class="mt-1 inline-block text-sm font-medium text-slate-800 underline">

                        {{ $vendorCredit->purchaseReturn->return_number }}

                    </a>
                </div>

                <div>
                    <p class="text-xs text-slate-400">Credit Date</p>

                    <p class="mt-1 text-sm font-medium text-slate-900">
                        {{ $vendorCredit->credit_date?->format('d M Y') }}
                    </p>
                </div>

            </div>

        </section>


        @if ($vendorCredit->remaining_amount > 0)

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

                {{-- Apply Credit --}}
                <section class="rounded-lg border border-slate-200 bg-white">

                    <div class="border-b border-slate-200 px-5 py-4">

                        <h2 class="text-sm font-semibold text-slate-900">
                            Apply Credit
                        </h2>

                        <p class="mt-1 text-xs text-slate-500">
                            Apply available credit against a vendor bill.
                        </p>

                    </div>


                    <form id="applyCreditForm" class="space-y-4 p-5">

                        @csrf

                        <div>

                            <label class="mb-1.5 block text-sm font-medium text-slate-700">
                                Vendor Bill
                            </label>

                           
                            <select name="vendor_bill_id"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm">

                                <option value="">
                                    Select vendor bill
                                </option>

                                @foreach ($vendorCredit->vendor->vendorBills ?? [] as $bill)
                                    @if ($bill->due_amount > 0)
                                        <option value="{{ $bill->id }}">

                                            {{ $bill->bill_number }}
                                            -
                                            Due {{ number_format($bill->due_amount, 2) }}

                                        </option>
                                    @endif
                                @endforeach

                            </select>

                        </div>


                        <div>

                            <label class="mb-1.5 block text-sm font-medium text-slate-700">
                                Amount
                            </label>

                            <input type="number" name="amount" step="0.01" min="0.01"
                                max="{{ $vendorCredit->remaining_amount }}"
                                class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm">

                        </div>


                        <button type="submit"
                            class="rounded-md bg-slate-800 px-4 py-2.5 text-sm font-medium text-white hover:bg-slate-900">

                            Apply Credit

                        </button>

                    </form>

                </section>


                {{-- Refund --}}
                <section class="rounded-lg border border-slate-200 bg-white">

                    <div class="border-b border-slate-200 px-5 py-4">

                        <h2 class="text-sm font-semibold text-slate-900">
                            Record Vendor Refund
                        </h2>

                        <p class="mt-1 text-xs text-slate-500">
                            Record money refunded by the vendor.
                        </p>

                    </div>


                    <form id="refundCreditForm" class="space-y-4 p-5">

                        @csrf

                        <div>
                            <label for="refund_amount" class="mb-1.5 block text-sm font-medium text-slate-700">
                                Refund Amount
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="number" id="refund_amount" name="amount" step="0.01" min="0.01"
                                max="{{ $vendorCredit->remaining_amount }}"
                                class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                            <p data-refund-error="amount" class="mt-1 hidden text-xs text-red-600">
                            </p>
                        </div>


                        <div>
                            <label for="refund_date" class="mb-1.5 block text-sm font-medium text-slate-700">
                                Refund Date
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="date" id="refund_date" name="refund_date"
                                value="{{ now()->toDateString() }}"
                                class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                            <p data-refund-error="refund_date" class="mt-1 hidden text-xs text-red-600">
                            </p>
                        </div>


                        <div>
                            <label for="refund_method" class="mb-1.5 block text-sm font-medium text-slate-700">
                                Refund Method
                            </label>

                            <select id="refund_method" name="refund_method"
                                class="w-full rounded-md border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                                <option value="">Select method</option>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="online">Online</option>
                                <option value="other">Other</option>
                            </select>

                            <p data-refund-error="refund_method" class="mt-1 hidden text-xs text-red-600">
                            </p>
                        </div>


                        <div>
                            <label for="refund_transaction_id" class="mb-1.5 block text-sm font-medium text-slate-700">
                                Transaction ID
                            </label>

                            <input type="text" id="refund_transaction_id" name="transaction_id"
                                placeholder="Optional"
                                class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                            <p data-refund-error="transaction_id" class="mt-1 hidden text-xs text-red-600">
                            </p>
                        </div>


                        <div>
                            <label for="refund_reference" class="mb-1.5 block text-sm font-medium text-slate-700">
                                Reference
                            </label>

                            <input type="text" id="refund_reference" name="reference"
                                placeholder="Bank reference, cheque number..."
                                class="w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                            <p data-refund-error="reference" class="mt-1 hidden text-xs text-red-600">
                            </p>
                        </div>


                        <div>
                            <label for="refund_notes" class="mb-1.5 block text-sm font-medium text-slate-700">
                                Notes
                            </label>

                            <textarea id="refund_notes" name="notes" rows="3" placeholder="Optional refund notes..."
                                class="w-full resize-none rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200"></textarea>

                            <p data-refund-error="notes" class="mt-1 hidden text-xs text-red-600">
                            </p>
                        </div>


                        <button type="submit" id="submitRefund"
                            class="inline-flex items-center gap-2 rounded-md bg-slate-800 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900">
                            <i class="bx bx-revision"></i>
                            Record Refund
                        </button>

                    </form>

                </section>

            </div>
        @else
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4">

                <p class="text-sm font-medium text-emerald-800">
                    This vendor credit has been fully settled.
                </p>

            </div>

        @endif


        {{-- Applications --}}
        @if ($vendorCredit->applications->count())

            <section class="mt-5 overflow-hidden rounded-lg border border-slate-200 bg-white">

                <div class="border-b border-slate-200 px-5 py-4">

                    <h2 class="text-sm font-semibold text-slate-900">
                        Credit Applications
                    </h2>

                </div>


                <table class="w-full text-left text-sm">

                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr class="text-xs text-slate-500">
                            <th class="px-5 py-3">Bill</th>
                            <th class="px-5 py-3">Date</th>
                            <th class="px-5 py-3 text-right">Amount</th>
                        </tr>

                    </thead>

                    <tbody class="divide-y divide-slate-100">

                        @foreach ($vendorCredit->applications as $application)
                            <tr>

                                <td class="px-5 py-3">

                                    <a href="{{ route('vendor-bills.show', $application->vendorBill) }}"
                                        class="font-medium text-slate-800 underline">

                                        {{ $application->vendorBill->bill_number }}

                                    </a>

                                </td>

                                <td class="px-5 py-3 text-slate-600">
                                    {{ $application->applied_date?->format('d M Y') }}
                                </td>

                                <td class="px-5 py-3 text-right font-medium">
                                    {{ number_format($application->amount, 2) }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </section>

        @endif
            {{-- Refund History --}}
        @if ($vendorCredit->refunds->count())

            <section class="mt-5 overflow-hidden rounded-lg border border-slate-200 bg-white">

                <div class="border-b border-slate-200 px-5 py-4">

                    <h2 class="text-sm font-semibold text-slate-900">
                        Refund History
                    </h2>

                    <p class="mt-1 text-xs text-slate-500">
                        Money returned by the vendor against this credit.
                    </p>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full min-w-[800px] text-left text-sm">

                        <thead class="border-b border-slate-200 bg-slate-50">

                            <tr class="text-xs font-medium uppercase tracking-wide text-slate-500">

                                <th class="px-5 py-3">
                                    Date
                                </th>

                                <th class="px-5 py-3">
                                    Method
                                </th>

                                <th class="px-5 py-3">
                                    Transaction ID
                                </th>

                                <th class="px-5 py-3">
                                    Reference
                                </th>

                                <th class="px-5 py-3 text-right">
                                    Amount
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @foreach ($vendorCredit->refunds as $refund)
                                <tr>

                                    <td class="px-5 py-4 text-slate-600">

                                        {{ $refund->refund_date?->format('d M Y') ?? '-' }}

                                    </td>


                                    <td class="px-5 py-4 text-slate-700">

                                        {{ $refund->refund_method ? ucwords(str_replace('_', ' ', $refund->refund_method)) : '-' }}

                                    </td>


                                    <td class="px-5 py-4 text-slate-600">

                                        {{ $refund->transaction_id ?: '-' }}

                                    </td>


                                    <td class="px-5 py-4 text-slate-600">

                                        {{ $refund->reference ?: '-' }}

                                    </td>


                                    <td class="px-5 py-4 text-right font-semibold text-slate-900">

                                        {{ number_format($refund->amount, 2) }}

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>


                        <tfoot class="border-t border-slate-200 bg-slate-50">

                            <tr>

                                <td colspan="4" class="px-5 py-4 text-right text-sm font-medium text-slate-600">
                                    Total Refunded
                                </td>

                                <td class="px-5 py-4 text-right font-bold text-slate-900">

                                    {{ number_format($vendorCredit->refunded_amount, 2) }}

                                </td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </section>

        @endif

    </div>


    @push('scripts')
        <script>
            $(document).ready(function() {

                $('#applyCreditForm').on('submit', function(e) {

                    e.preventDefault();

                    $.ajax({

                        url: "{{ route('vendor-credits.apply', $vendorCredit) }}",

                        type: 'POST',

                        data: $(this).serialize(),

                        headers: {
                            Accept: 'application/json'
                        },

                        success: function(response) {

                            showToast(
                                'success',
                                response.message
                            );

                            setTimeout(function() {
                                window.location.reload();
                            }, 500);

                        },

                        error: function(xhr) {

                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to apply vendor credit.'
                            );

                        }

                    });

                });


                $('#refundCreditForm').on('submit', function(e) {

                    e.preventDefault();

                    const form = $(this);
                    const button = $('#submitRefund');

                    $('[data-refund-error]')
                        .addClass('hidden')
                        .text('');

                    button
                        .prop('disabled', true)
                        .html(`
            <i class="bx bx-loader-alt bx-spin"></i>
            Recording...
        `);

                    $.ajax({

                        url: "{{ route('vendor-credits.refund', $vendorCredit) }}",

                        type: 'POST',

                        data: form.serialize(),

                        headers: {
                            Accept: 'application/json'
                        },

                        success: function(response) {

                            showToast(
                                'success',
                                response.message
                            );

                            setTimeout(function() {
                                window.location.reload();
                            }, 500);
                        },

                        error: function(xhr) {

                            if (xhr.status === 422) {

                                const errors =
                                    xhr.responseJSON?.errors || {};

                                $.each(
                                    errors,
                                    function(field, messages) {

                                        $('[data-refund-error="' + field + '"]')
                                            .removeClass('hidden')
                                            .text(messages[0]);
                                    }
                                );
                            }

                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to record vendor refund.'
                            );

                            button
                                .prop('disabled', false)
                                .html(`
                    <i class="bx bx-revision"></i>
                    Record Refund
                `);
                        }
                    });
                });

            });
        </script>
    @endpush

</x-layouts.app>
