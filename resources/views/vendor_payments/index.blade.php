<x-layouts.app title="Vendor Payments">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-xl font-semibold text-slate-900">
                Vendor Payments
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Record and manage payments made against vendor bills.
            </p>
        </div>

        <button type="button" id="openPaymentModal"
            class="rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900">
            Record Payment
        </button>

    </div>


    {{-- Payment List --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">

            <div>
                <h3 class="font-semibold text-slate-900">
                    Payment History
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    All recorded vendor payments.
                </p>
            </div>

            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                {{ $vendorPayments->total() }} Payments
            </span>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px] text-left text-sm">

                <thead class="border-b border-slate-200 bg-slate-50">

                    <tr class="text-xs font-medium uppercase tracking-wide text-slate-500">

                         <th class="px-6 py-3.5">
                            Sno.
                        </th>
                        <th class="px-6 py-3.5">
                            Transaction ID
                        </th>

                        <th class="px-6 py-3.5">
                            Vendor
                        </th>

                        <th class="px-6 py-3.5">
                            Bill
                        </th>

                        <th class="px-6 py-3.5">
                            Payment Date
                        </th>

                        <th class="px-6 py-3.5">
                            Method
                        </th>

                        <th class="px-6 py-3.5">
                            Amount
                        </th>

                        <th class="px-6 py-3.5">
                            Status
                        </th>

                        <th class="px-6 py-3.5">
                            Proof
                        </th>


                        <th class="px-6 py-3.5">
                            Reference
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($vendorPayments as $key=>$payment)
                        <tr class="transition hover:bg-slate-50">


                            {{-- Sno --}}
                            <td class="px-6 py-4">

                                <p class="font-medium text-slate-900">
                                    {{ $key+1 }}
                                </p>

                            </td>

                            {{-- Transaction --}}
                            <td class="px-6 py-4">

                                <p class="font-medium text-slate-900">
                                    {{ $payment->transaction_id ?: '-' }}
                                </p>

                            </td>


                            {{-- Vendor --}}
                            <td class="px-6 py-4">

                                <p class="font-medium text-slate-900">
                                    {{ $payment->vendorBill->vendor->company_name ?: $payment->vendorBill->vendor->name }}
                                </p>

                            </td>


                            {{-- Bill --}}
                            <td class="px-6 py-4 text-slate-600">
                                <a href="{{ route('vendor-bills.show',$payment->vendorBill) }}" class="mt-1.5 font-semibold text-blue-900 underline">
                                    {{ $payment->vendorBill->bill_number ?: 'Bill #' . $payment->vendorBill->id }}
                                </a>

                            </td>


                            {{-- Date --}}
                            <td class="px-6 py-4 text-slate-600">

                                {{ $payment->payment_date ? $payment->payment_date->format('d M Y') : '-' }}

                            </td>


                            {{-- Method --}}
                            <td class="px-6 py-4 text-slate-600">

                                {{ $payment->payment_method ? ucwords(str_replace('_', ' ', $payment->payment_method)) : '-' }}

                            </td>


                            {{-- Amount --}}
                            <td class="px-6 py-4 font-semibold text-slate-900">

                                {{ number_format($payment->amount, 2) }}

                            </td>


                            {{-- Status --}}
                            <td class="px-6 py-4">

                                @php
                                    $statusClass = match ($payment->status) {
                                        'successful' => 'bg-green-50 text-green-700',
                                        'failed' => 'bg-red-50 text-red-700',
                                        'refunded' => 'bg-amber-50 text-amber-700',
                                        default => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp

                                <span
                                    class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">
                                    {{ ucfirst($payment->status) }}
                                </span>

                            </td>


                            {{-- Payment Proof --}}
                            <td class="px-6 py-4">

                                @if ($payment->payment_proof)

                                    <button type="button"
                                        class="view-proof-btn inline-flex items-center gap-1.5 text-sm font-medium text-slate-700 transition hover:text-slate-900"
                                        data-image="{{ asset('storage/' . $payment->payment_proof) }}">

                                        <i class="bx bx-image text-lg"></i>

                                        View

                                    </button>

                                @else

                                    <span class="text-slate-400">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Reference --}}
                            <td class="max-w-[200px] px-6 py-4 text-slate-600">

                                @if ($payment->references)
                                    <span class="block truncate" title="{{ $payment->references }}">
                                        {{ $payment->references }}
                                    </span>
                                @else
                                    <span class="text-slate-400">
                                        -
                                    </span>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="px-6 py-12 text-center">

                                <h3 class="font-medium text-slate-900">
                                    No payments recorded
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    Record a payment against an unpaid vendor bill.
                                </p>

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if ($vendorPayments->hasPages())
            <div class="border-t border-slate-200 px-6 py-4">
                {{ $vendorPayments->links() }}
            </div>
        @endif

    </div>


    {{-- Record Payment Modal --}}
    <div id="paymentModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">

        <div class="flex max-h-[90vh] w-full max-w-lg flex-col overflow-hidden rounded-xl bg-white shadow-xl">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">

                <div>
                    <h3 class="font-semibold text-slate-900">
                        Record Vendor Payment
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Select a vendor bill and enter the payment details.
                    </p>
                </div>

                <button type="button" id="closePaymentModal"
                    class="rounded-lg px-3 py-1.5 text-sm font-medium text-slate-500 transition hover:bg-slate-100 hover:text-slate-900">
                    Close
                </button>

            </div>


            <form id="paymentForm" enctype="multipart/form-data">

                @csrf

                <div class="max-h-[70vh] overflow-y-auto p-6">

                    {{-- Vendor Bill --}}
                    <div class="mb-5">

                        <label for="vendor_bill_id" class="mb-1.5 block text-sm font-medium text-slate-700">
                            Vendor Bill
                            <span class="text-red-500">*</span>
                        </label>

                        <select id="vendor_bill_id" name="vendor_bill_id"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-900 outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                            <option value="">
                                Select Vendor Bill
                            </option>

                            @foreach ($unpaidBills as $bill)
                                @php
                                    $paid = $bill->vendorPayments
                                        ? $bill->vendorPayments->where('status', 'successful')->sum('amount')
                                        : 0;

                                    $due = max($bill->total - $paid, 0);
                                @endphp

                                <option value="{{ $bill->id }}" data-total="{{ $bill->total }}"
                                    data-paid="{{ $paid }}" data-due="{{ $due }}">

                                    {{ $bill->bill_number ?: 'Bill #' . $bill->id }}
                                    -
                                    {{ $bill->vendor->company_name ?: $bill->vendor->name }}
                                    -
                                    Due: {{ number_format($due, 2) }}

                                </option>
                            @endforeach

                        </select>

                        <span id="vendorBillErr" class="mt-1 block text-xs text-red-600">
                        </span>

                    </div>


                    {{-- Bill Summary --}}
                    <div id="billSummary" class="mb-5 hidden rounded-lg border border-slate-200 bg-slate-50 p-4">

                        <div class="grid grid-cols-3 gap-4">

                            <div>
                                <p class="text-xs font-medium uppercase text-slate-500">
                                    Bill Total
                                </p>

                                <p id="billTotal" class="mt-1 font-semibold text-slate-900">
                                    0.00
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase text-slate-500">
                                    Paid
                                </p>

                                <p id="billPaid" class="mt-1 font-semibold text-green-700">
                                    0.00
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-medium uppercase text-slate-500">
                                    Due
                                </p>

                                <p id="billDue" class="mt-1 font-semibold text-red-600">
                                    0.00
                                </p>
                            </div>

                        </div>

                    </div>


                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        {{-- Amount --}}
                        <div>

                            <label for="amount" class="mb-1.5 block text-sm font-medium text-slate-700">
                                Amount
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="number" id="amount" name="amount" step="0.01" min="0.01"
                                placeholder="0.00"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                            <span id="amountErr" class="mt-1 block text-xs text-red-600">
                            </span>

                        </div>


                        {{-- Payment Method --}}
                        <div>

                            <label for="payment_method" class="mb-1.5 block text-sm font-medium text-slate-700">
                                Payment Method
                                <span class="text-red-500">*</span>
                            </label>

                            <select id="payment_method" name="payment_method"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                                <option value="">
                                    Select Method
                                </option>

                                <option value="cash">
                                    Cash
                                </option>

                                <option value="bank_transfer">
                                    Bank Transfer
                                </option>

                                <option value="cheque">
                                    Cheque
                                </option>

                                <option value="online">
                                    Online
                                </option>

                                <option value="other">
                                    Other
                                </option>

                            </select>

                            <span id="paymentMethodErr" class="mt-1 block text-xs text-red-600">
                            </span>

                        </div>


                        {{-- Transaction ID --}}
                        <div>

                            <label for="transaction_id" class="mb-1.5 block text-sm font-medium text-slate-700">
                                Transaction ID
                            </label>

                            <input type="text" id="transaction_id" name="transaction_id" placeholder="Optional"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                            <span id="transactionIdErr" class="mt-1 block text-xs text-red-600">
                            </span>

                        </div>


                        {{-- Payment Date --}}
                        <div>

                            <label for="payment_date" class="mb-1.5 block text-sm font-medium text-slate-700">
                                Payment Date
                            </label>

                            <input type="date" id="payment_date" name="payment_date"
                                value="{{ now()->toDateString() }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                            <span id="paymentDateErr" class="mt-1 block text-xs text-red-600">
                            </span>

                        </div>

                    </div>


                    {{-- Reference --}}
                    <div class="mt-5">

                        <label for="references" class="mb-1.5 block text-sm font-medium text-slate-700">
                            Reference
                        </label>

                        <input type="text" id="references" name="references"
                            placeholder="Cheque number, bank reference, receipt number..."
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                        <span id="referencesErr" class="mt-1 block text-xs text-red-600">
                        </span>

                    </div>


                    {{-- Notes --}}
                    <div class="mt-5">

                        <label for="notes" class="mb-1.5 block text-sm font-medium text-slate-700">
                            Notes
                        </label>

                        <textarea id="notes" name="notes" rows="3" placeholder="Optional payment notes..."
                            class="w-full resize-none rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200"></textarea>

                        <span id="notesErr" class="mt-1 block text-xs text-red-600">
                        </span>

                    </div>

                     {{-- Payment proof --}}
                        <div>

                            <label for="payment_proof" class="mb-1.5 block text-sm font-medium text-slate-700">
                                Payment Proof
                            
                            </label>

                            <input type="file" accept="image/png, image/jpeg, image/jpg" id="payment_proof" name="payment_proof"
                                placeholder="0.00"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">

                            <span id="paymentProofErr" class="mt-1 block text-xs text-red-600">
                            </span>

                        </div>

                </div>


                {{-- Modal Footer --}}
                <div class="flex justify-end gap-3 border-t border-slate-200 px-6 py-4">

                    <button type="button" id="cancelPayment"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Cancel
                    </button>

                    <button type="submit" id="submitPayment"
                        class="rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900">
                        Record Payment
                    </button>

                </div>

            </form>

        </div>

    </div>


        {{-- Payment Proof Preview Modal --}}
        <div id="proofModal"
            class="fixed inset-0 z-[60] hidden items-center justify-center bg-slate-950/70 p-4 backdrop-blur-[2px]">

            {{-- Modal Container --}}
            <div
                class="flex max-h-[90vh] w-full max-w-3xl flex-col overflow-hidden
                    rounded-2xl border border-slate-200 bg-white
                    shadow-[0_25px_70px_-15px_rgba(15,23,42,0.45)]">

                {{-- Header --}}
                <div
                    class="flex shrink-0 items-center justify-between
                        border-b border-slate-200 bg-white px-5 py-4
                        shadow-[0_1px_3px_rgba(15,23,42,0.06)]">

                    <div class="flex items-center gap-3">

                        {{-- Icon --}}
                        <div
                            class="flex h-10 w-10 items-center justify-center
                                rounded-xl border border-slate-200 bg-slate-50
                                shadow-sm">

                            <i class="bx bx-image text-xl text-slate-700"></i>

                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">
                                Payment Proof
                            </h3>

                            <p class="mt-0.5 text-xs text-slate-500">
                                Uploaded payment receipt or proof
                            </p>
                        </div>

                    </div>


                    {{-- Close --}}
                    <button type="button"
                        id="closeProofModal"
                        class="flex h-9 w-9 items-center justify-center
                            rounded-lg border border-slate-200 bg-white
                            text-slate-500 shadow-sm transition
                            hover:border-slate-300 hover:bg-slate-50
                            hover:text-slate-900">

                        <i class="bx bx-x text-2xl"></i>

                    </button>

                </div>


                {{-- Preview Area --}}
                <div class="min-h-0 flex-1 overflow-auto bg-slate-100 p-5 sm:p-6">

                    {{-- Image Frame --}}
                    <div
                        class="flex min-h-[350px] items-center justify-center
                            rounded-xl border border-slate-200 bg-white p-3
                            shadow-[0_4px_18px_rgba(15,23,42,0.10)]">

                        <img id="proofPreviewImage"
                            src=""
                            alt="Payment Proof"
                            class="max-h-[68vh] max-w-full rounded-lg
                                border border-slate-200 object-contain
                                shadow-md">

                    </div>

                </div>


                {{-- Footer --}}
                <div
                    class="flex shrink-0 items-center justify-between
                        border-t border-slate-200 bg-white px-5 py-3">

                    <p class="flex items-center gap-1.5 text-xs text-slate-500">
                        <i class="bx bx-check-shield text-base"></i>
                        Payment attachment
                    </p>

                    <button type="button"
                        id="closeProofModalFooter"
                        class="rounded-lg border border-slate-300 bg-white
                            px-4 py-2 text-sm font-medium text-slate-700
                            shadow-sm transition
                            hover:bg-slate-50 hover:text-slate-900">
                        Close
                    </button>

                </div>

            </div>

        </div>


    @push('scripts')
        <script>
            $(document).ready(function() {

                const modal = $('#paymentModal');
                const form = $('#paymentForm');


                // Open modal
                $('#openPaymentModal').on('click', function() {

                    clearErrors();

                    modal
                        .removeClass('hidden')
                        .addClass('flex');

                });


                // Close modal
                $('#closePaymentModal, #cancelPayment').on('click', function() {

                    closeModal();

                });


                // Close when clicking backdrop
                modal.on('click', function(e) {

                    if (e.target === this) {
                        closeModal();
                    }

                });


                // Bill selection
                $('#vendor_bill_id').on('change', function() {

                    const selected =
                        $(this).find(':selected');

                    const billId =
                        $(this).val();

                    if (!billId) {

                        $('#billSummary').addClass('hidden');

                        $('#amount').val('');

                        return;
                    }


                    const total =
                        parseFloat(selected.data('total')) || 0;

                    const paid =
                        parseFloat(selected.data('paid')) || 0;

                    const due =
                        parseFloat(selected.data('due')) || 0;


                    $('#billTotal').text(
                        total.toFixed(2)
                    );

                    $('#billPaid').text(
                        paid.toFixed(2)
                    );

                    $('#billDue').text(
                        due.toFixed(2)
                    );

                    $('#amount')
                        .attr('max', due)
                        .val(due.toFixed(2));

                    $('#billSummary')
                        .removeClass('hidden');

                });


                // Submit payment
                form.on('submit', function(e) {

                    e.preventDefault();

                    clearErrors();

                    const billId =
                        $('#vendor_bill_id').val();


                    if (!billId) {

                        $('#vendorBillErr').text(
                            'Please select a vendor bill.'
                        );

                        return;
                    }


                    const button =
                        $('#submitPayment');


                    button
                        .prop('disabled', true)
                        .text('Recording...');


                    
                    const formData = new FormData(form[0]);
                    $.ajax({

                        url: "{{ route('vendor-payments.store', ':vendorBill') }}"
                            .replace(':vendorBill', billId),
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            showToast(
                                'success',
                                response.message ||
                                'Payment recorded successfully.'
                            );
                            closeModal();
                            setTimeout(function() {
                                window.location.reload();

                            }, 800);

                        },
                        error: function(xhr) {
                            button
                                .prop('disabled', false)
                                .text('Record Payment');
                            if (xhr.status === 422) {

                                showValidationErrors(
                                    xhr.responseJSON?.errors || {}
                                );

                                showToast(
                                    'error',
                                    xhr.responseJSON?.message ||
                                    'Please check the entered information.'
                                );

                                return;
                            }


                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to record payment.'
                            );

                        }

                    });

                });


                function clearErrors() {

                    $('#vendorBillErr').text('');
                    $('#amountErr').text('');
                    $('#paymentMethodErr').text('');
                    $('#transactionIdErr').text('');
                    $('#paymentDateErr').text('');
                    $('#paymentProofErr').text('');
                    $('#referencesErr').text('');
                    $('#notesErr').text('');

                }


                function showValidationErrors(errors) {

                    if (errors.vendor_bill_id) {
                        $('#vendorBillErr')
                            .text(errors.vendor_bill_id[0]);
                    }

                    if (errors.amount) {
                        $('#amountErr')
                            .text(errors.amount[0]);
                    }

                    if (errors.payment_method) {
                        $('#paymentMethodErr')
                            .text(errors.payment_method[0]);
                    }

                    if (errors.transaction_id) {
                        $('#transactionIdErr')
                            .text(errors.transaction_id[0]);
                    }

                    if (errors.payment_date) {
                        $('#paymentDateErr')
                            .text(errors.payment_date[0]);
                    }

                    if (errors.references) {
                        $('#referencesErr')
                            .text(errors.references[0]);
                    }

                    if (errors.notes) {
                        $('#notesErr')
                            .text(errors.notes[0]);
                    }

                    if (errors.payment_proof) {
                        $('#paymentProofErr')
                            .text(errors.payment_proof[0]);
                    }

                }


                function closeModal() {

                    modal
                        .addClass('hidden')
                        .removeClass('flex');

                    form[0].reset();

                    clearErrors();

                    $('#billSummary')
                        .addClass('hidden');

                    $('#amount')
                        .removeAttr('max');

                    $('#submitPayment')
                        .prop('disabled', false)
                        .text('Record Payment');

                }

            });
        
                
                // Open payment proof preview
        $('.view-proof-btn').on('click', function () {

            const image = $(this).data('image');

            $('#proofPreviewImage').attr('src', image);

            $('#proofModal')
                .removeClass('hidden')
                .addClass('flex');

        });


        // Close payment proof preview
        $('#closeProofModal, #closeProofModalFooter').on('click', function () {
            closeProofModal();
        });


        // Close when clicking outside image modal
        $('#proofModal').on('click', function (e) {

            if (e.target === this) {
                closeProofModal();
            }

        });


        function closeProofModal() {

            $('#proofModal')
                .addClass('hidden')
                .removeClass('flex');

            $('#proofPreviewImage').attr('src', '');

        }
                
        </script>
    @endpush

</x-layouts.app>
