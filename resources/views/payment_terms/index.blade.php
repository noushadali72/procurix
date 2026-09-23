<x-layouts.app title="Payment Terms">

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-700">
                        Dashboard
                    </a>
                    <i class="bx bx-chevron-right text-lg"></i>
                    <span>Payment Terms</span>
                </div>

                <h1 class="mt-2 text-2xl font-semibold text-gray-900">
                    Payment Terms
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Manage payment terms used for vendor transactions.
                </p>
            </div>

            <button type="button" onclick="openPaymentTermModal()"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">
                <i class="bx bx-plus text-lg"></i>
                Add Payment Term
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                #
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Payment Term
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Due Days
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Discount
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($paymentTerms as $paymentTerm)
                            <tr class="hover:bg-gray-50">

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $paymentTerms->firstItem() + $loop->index }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">
                                        {{ $paymentTerm->name }}
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    <span
                                        class="inline-flex items-center rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                        {{ $paymentTerm->due_days }} days
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if ($paymentTerm->discount_days !== null && $paymentTerm->discount_percentage !== null)
                                        {{ $paymentTerm->discount_percentage }}%
                                        <span class="text-gray-400">
                                            within {{ $paymentTerm->discount_days }} days
                                        </span>
                                    @else
                                        <span class="text-gray-400">No discount</span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="flex justify-end gap-2">

                                        <button type="button" onclick='editPaymentTerm(@json($paymentTerm))'
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-600 transition hover:border-gray-300 hover:bg-gray-50 hover:text-gray-900"
                                            title="Edit">
                                            <i class="bx bx-edit-alt text-lg"></i>
                                        </button>

                                        <form action="{{ route('payment-terms.destroy', $paymentTerm) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this payment term?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-red-100 text-red-500 transition hover:bg-red-50 hover:text-red-600"
                                                title="Delete">
                                                <i class="bx bx-trash text-lg"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100">
                                            <i class="bx bx-credit-card text-2xl text-gray-400"></i>
                                        </div>

                                        <h3 class="text-sm font-semibold text-gray-900">
                                            No payment terms found
                                        </h3>

                                        <p class="mt-1 text-sm text-gray-500">
                                            Create your first payment term to get started.
                                        </p>

                                        <button type="button" onclick="openPaymentTermModal()"
                                            class="mt-4 text-sm font-medium text-gray-900 underline hover:text-gray-600">
                                            Add Payment Term
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($paymentTerms->hasPages())
                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $paymentTerms->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- Create / Edit Modal --}}
    <div id="paymentTermModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
        <div class="w-full max-w-lg rounded-xl bg-white shadow-xl" onclick="event.stopPropagation()">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <div>
                    <h2 id="paymentTermModalTitle" class="text-lg font-semibold text-gray-900">
                        Add Payment Term
                    </h2>

                    <p class="mt-0.5 text-sm text-gray-500">
                        Define payment and early payment discount terms.
                    </p>
                </div>

                <button type="button" onclick="closePaymentTermModal()"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 hover:bg-gray-100 hover:text-gray-700">
                    <i class="bx bx-x text-2xl"></i>
                </button>
            </div>

            {{-- Form --}}
            <form id="paymentTermForm" method="POST" class="p-6">
                @csrf

                <input type="hidden" id="paymentTermId">

                <div class="space-y-5">

                    {{-- Name --}}
                    <div>
                        <label for="name" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Name <span class="text-red-500">*</span>
                        </label>

                        <input type="text" id="name" name="name" placeholder="e.g. Net 30"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-gray-500 focus:ring-1 focus:ring-gray-500">

                        <p id="error-name" class="mt-1 hidden text-xs text-red-500"></p>
                    </div>

                    {{-- Due Days --}}
                    <div>
                        <label for="due_days" class="mb-1.5 block text-sm font-medium text-gray-700">
                            Due Days <span class="text-red-500">*</span>
                        </label>

                        <div class="relative">
                            <input type="number" id="due_days" name="due_days" min="2" placeholder="30"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 pr-16 text-sm outline-none transition focus:border-gray-500 focus:ring-1 focus:ring-gray-500">

                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                days
                            </span>
                        </div>

                        <p id="error-due_days" class="mt-1 hidden text-xs text-red-500"></p>
                    </div>

                    {{-- Discount --}}
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <div class="mb-4">
                            <h3 class="text-sm font-medium text-gray-900">
                                Early Payment Discount
                            </h3>
                            <p class="mt-0.5 text-xs text-gray-500">
                                Optional discount for payments made within a specific period.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                            <div>
                                <label for="discount_days" class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Discount Days
                                </label>

                                <div class="relative">
                                    <input type="number" id="discount_days" name="discount_days" min="0"
                                        placeholder="10"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 pr-16 text-sm outline-none transition focus:border-gray-500 focus:ring-1 focus:ring-gray-500">

                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                        days
                                    </span>
                                </div>

                                <p id="error-discount_days" class="mt-1 hidden text-xs text-red-500"></p>
                            </div>

                            <div>
                                <label for="discount_percentage"
                                    class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Discount Percentage
                                </label>

                                <div class="relative">
                                    <input type="number" id="discount_percentage" name="discount_percentage"
                                        min="0" step="0.01" placeholder="2"
                                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 pr-10 text-sm outline-none transition focus:border-gray-500 focus:ring-1 focus:ring-gray-500">

                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                                        %
                                    </span>
                                </div>

                                <p id="error-discount_percentage" class="mt-1 hidden text-xs text-red-500"></p>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="mt-6 flex justify-end gap-3 border-t border-gray-200 pt-5">
                    <button type="button" onclick="closePaymentTermModal()"
                        class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>

                    <button type="submit" id="paymentTermSubmit"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-800">
                        <span id="paymentTermSubmitText">Create Payment Term</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            let editingPaymentTerm = false;

            function openPaymentTermModal() {
                editingPaymentTerm = false;

                $('#paymentTermForm')[0].reset();
                $('#paymentTermId').val('');
                clearPaymentTermErrors();

                $('#paymentTermModalTitle').text('Add Payment Term');
                $('#paymentTermSubmitText').text('Create Payment Term');

                $('#paymentTermModal')
                    .removeClass('hidden')
                    .addClass('flex');
            }

            function closePaymentTermModal() {
                $('#paymentTermModal')
                    .removeClass('flex')
                    .addClass('hidden');
            }

            function editPaymentTerm(paymentTerm) {
                editingPaymentTerm = true;

                clearPaymentTermErrors();

                $('#paymentTermId').val(paymentTerm.id);
                $('#name').val(paymentTerm.name);
                $('#due_days').val(paymentTerm.due_days);
                $('#discount_days').val(paymentTerm.discount_days ?? '');
                $('#discount_percentage').val(paymentTerm.discount_percentage ?? '');

                $('#paymentTermModalTitle').text('Edit Payment Term');
                $('#paymentTermSubmitText').text('Update Payment Term');

                $('#paymentTermModal')
                    .removeClass('hidden')
                    .addClass('flex');
            }

            function clearPaymentTermErrors() {
                $('.payment-term-error').remove();

                $('#paymentTermForm .border-red-500')
                    .removeClass('border-red-500');

                $('[id^="error-"]')
                    .text('')
                    .addClass('hidden');
            }

            function showPaymentTermErrors(errors) {
                Object.keys(errors).forEach(function(field) {
                    const message = errors[field][0];

                    $('#error-' + field)
                        .text(message)
                        .removeClass('hidden');

                    $('#' + field)
                        .addClass('border-red-500');
                });
            }

            $('#paymentTermForm').on('submit', function(e) {
                e.preventDefault();

                clearPaymentTermErrors();

                const id = $('#paymentTermId').val();


                const url = editingPaymentTerm ?
                    "{{ route('payment-terms.update', ':id') }}".replace(':id', id) :
                    "{{ route('payment-terms.store') }}";

                const data = $(this).serialize();

                $('#paymentTermSubmit')
                    .prop('disabled', true)
                    .addClass('opacity-60 cursor-not-allowed');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: editingPaymentTerm ?
                        data + '&_method=PUT' :
                        data,

                    success: function(response) {
                        showToast(
                            'success',
                            response.message || 'Payment term saved successfully.'
                        );

                        closePaymentTermModal();

                        setTimeout(function() {
                            window.location.reload();
                        }, 800);
                    },

                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            showPaymentTermErrors(xhr.responseJSON.errors);
                            return;
                        }

                        showToast(
                            'error',
                            xhr.responseJSON?.message ||
                            'Unable to save payment term.'
                        );
                    },

                    complete: function() {
                        $('#paymentTermSubmit')
                            .prop('disabled', false)
                            .removeClass('opacity-60 cursor-not-allowed');
                    }
                });
            });

            $('#paymentTermModal').on('click', function(e) {
                if (e.target === this) {
                    closePaymentTermModal();
                }
            });

            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    closePaymentTermModal();
                }
            });
        </script>
    @endpush

</x-layouts.app>
