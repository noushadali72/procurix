<x-layouts.app title="Create Quotation">

    <div class="mb-6 flex items-center justify-between">

        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Create Quotation
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Create a quotation for Purchase Request #{{ $purchaseRequest->request_number }}.
            </p>
        </div>

        <a href="{{ route('quotations.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
            <i class="bx bx-arrow-back"></i>
            Back
        </a>

    </div>


    {{-- Purchase Request --}}
    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="flex items-center gap-3 border-b bg-gray-50 px-6 py-4">

            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gray-900 text-white">
                <i class="bx bx-file text-xl"></i>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900">
                     Purchase Request 
                    <a class="underline" href="{{ route('purchase-requests.show',$purchaseRequest) }}">
                       #{{ $purchaseRequest->request_number }}
                    </a>
                </h3>

              
            </div>

        </div>


        <div class="p-6">

            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Request Number
                    </p>

                    <p class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $purchaseRequest->request_number }}
                    </p>

                </div>


                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Status
                    </p>

                    <p class="mt-1">

                        <span
                            class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-medium capitalize text-blue-700">
                            {{ $purchaseRequest->status }}
                        </span>

                    </p>

                </div>


                <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Created
                    </p>

                    <p class="mt-1 text-lg font-semibold text-gray-900">
                        {{ $purchaseRequest->created_at->format('d M Y') }}
                    </p>

                </div>

            </div>


            @if ($purchaseRequest->notes)
                <div class="mb-6 rounded-lg border border-gray-200 bg-white p-4">

                    <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500">
                        Notes
                    </p>

                    <p class="text-sm leading-6 text-gray-700">
                        {{ $purchaseRequest->notes }}
                    </p>

                </div>
            @endif


            <div class="overflow-hidden rounded-lg border border-gray-200">

                <div class="border-b bg-gray-50 px-4 py-3">

                    <h4 class="text-sm font-semibold text-gray-800">
                        Requested Materials
                    </h4>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full text-left text-sm">

                        <thead class="border-b bg-white">

                            <tr class="text-xs uppercase tracking-wide text-gray-500">
                                <th class="px-4 py-3 font-medium">#</th>
                                <th class="px-4 py-3 font-medium">Raw Material</th>
                                <th class="px-4 py-3 font-medium">SKU</th>
                                <th class="px-4 py-3 text-right font-medium">Quantity</th>
                                <th class="px-4 py-3 font-medium">Unit</th>
                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @foreach ($purchaseRequest->items as $index => $item)
                                <tr class="hover:bg-gray-50">

                                    <td class="px-4 py-3 text-gray-500">
                                        {{ $index + 1 }}
                                    </td>

                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        {{ $item->rawMaterial->name }}
                                    </td>

                                    <td class="px-4 py-3 text-gray-500">
                                        {{ $item->rawMaterial->sku }}
                                    </td>

                                    <td class="px-4 py-3 text-right font-medium text-gray-900">
                                        {{ $item->qty }}
                                    </td>

                                    <td class="px-4 py-3 text-gray-600">
                                        {{ $item->unit->short_name ?? $item->unit->name }}
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    <form id="quotationForm" action="{{ route('quotations.store') }}" method="POST">

        @csrf

        <input type="hidden" name="purchase_request_id" value="{{ $purchaseRequest->id }}">

        @include('quotations._form')

        
    </form>



    {{-- Add Vendor Modal --}}
    <div id="vendorModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center px-4 py-8">

            {{-- Overlay --}}
            <div id="vendorModalOverlay" class="fixed inset-0 bg-black/50"></div>

            {{-- Modal --}}
            <div class="relative z-10 w-full max-w-3xl rounded-xl bg-white shadow-xl">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b px-6 py-4">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            Add Vendor
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Enter the vendor details.
                        </p>
                    </div>

                    <button type="button" id="closeVendorModal"
                        class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-900">
                        <i class="bx bx-x text-2xl"></i>
                    </button>

                </div>


                {{-- Form --}}
                <form id="vendorForm" method="POST" action="{{ route('vendors.store') }}" novalidate>
                    @csrf

                    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

                        {{-- Name --}}
                        <div>
                            <label for="vendor_name" class="mb-1 block text-sm font-medium text-gray-700">
                                Name
                            </label>

                            <input type="text" id="vendor_name" name="name" placeholder="Enter vendor name"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">

                            <span id="vendorNameErr" class="mt-1 block text-sm text-red-600"></span>
                        </div>


                        {{-- Company Name --}}
                        <div>
                            <label for="vendor_company_name" class="mb-1 block text-sm font-medium text-gray-700">
                                Company Name
                            </label>

                            <input type="text" id="vendor_company_name" name="company_name"
                                placeholder="Enter company name"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">

                            <span id="vendorCompanyNameErr" class="mt-1 block text-sm text-red-600"></span>
                        </div>


                        {{-- Contact Person --}}
                        <div>
                            <label for="vendor_contact_person" class="mb-1 block text-sm font-medium text-gray-700">
                                Contact Person
                            </label>

                            <input type="text" id="vendor_contact_person" name="contact_person"
                                placeholder="Enter contact person"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">

                            <span id="vendorContactPersonErr" class="mt-1 block text-sm text-red-600"></span>
                        </div>


                        {{-- Email --}}
                        <div>
                            <label for="vendor_email" class="mb-1 block text-sm font-medium text-gray-700">
                                Email
                            </label>

                            <input type="email" id="vendor_email" name="email" placeholder="Enter email address"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">

                            <span id="vendorEmailErr" class="mt-1 block text-sm text-red-600"></span>
                        </div>


                        {{-- Phone --}}
                        <div>
                            <label for="vendor_phone" class="mb-1 block text-sm font-medium text-gray-700">
                                Phone
                            </label>

                            <input type="text" id="vendor_phone" name="phone" placeholder="Enter phone number"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">

                            <span id="vendorPhoneErr" class="mt-1 block text-sm text-red-600"></span>
                        </div>

                          {{-- NTN --}}
                        <div>
                            <label for="vendor_ntn" class="mb-1 block text-sm font-medium text-gray-700">
                                NTN
                            </label>

                            <input type="text" id="vendor_ntn" name="ntn" placeholder="Enter NTN"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">

                            <span id="vendorNtnErr" class="mt-1 block text-sm text-red-600"></span>
                        </div>


                        {{-- Active --}}
                        <div>
                            <label for="vendor_is_active" class="mb-1 block text-sm font-medium text-gray-700">
                                Status
                            </label>

                            <select id="vendor_is_active" name="is_active"
                                class="w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">
                                <option value="1" selected>
                                    Active
                                </option>

                                <option value="0">
                                    Inactive
                                </option>
                            </select>

                            <span id="vendorIsActiveErr" class="mt-1 block text-sm text-red-600"></span>
                        </div>


                        {{-- Address --}}
                        <div class="md:col-span-2">
                            <label for="vendor_address" class="mb-1 block text-sm font-medium text-gray-700">
                                Address
                            </label>

                            <textarea id="vendor_address" name="address" rows="4" placeholder="Enter vendor address"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"></textarea>

                            <span id="vendorAddressErr" class="mt-1 block text-sm text-red-600"></span>
                        </div>

                    </div>


                    {{-- Footer --}}
                    <div class="flex justify-end gap-3 border-t bg-gray-50 px-6 py-4">

                        <button type="button" id="cancelVendorModal"
                            class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                            Cancel
                        </button>

                        <button type="submit" id="saveVendorBtn"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-60">
                            <i class="bx bx-save"></i>

                            <span id="saveVendorBtnText">
                                Save Vendor
                            </span>
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Item Input Validation --}}
        <script>
            // Cost price: whole numbers only (including 0)
            $('.item-price').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Qty: decimal numbers only
            $('.item-qty').on('input', function() {

                // Remove letters, negative sign and other symbols
                this.value = this.value.replace(/[^0-9.]/g, '');

                // Allow only one decimal point
                const parts = this.value.split('.');

                if (parts.length > 2) {
                    this.value = parts[0] + '.' + parts.slice(1).join('');
                }

            });
        </script>


        {{-- Add Vendor Script  --}}
        <script>
            $(document).ready(function() {

                // Open modal
                $('#openVendorModal').on('click', function() {
                    $('#vendorModal').removeClass('hidden');
                });


                // Close modal
                function closeVendorModal() {
                    $('#vendorModal').addClass('hidden');
                    $('#vendorForm')[0].reset();
                    $('.text-red-600').text('');
                }


                $('#closeVendorModal, #cancelVendorModal, #vendorModalOverlay')
                    .on('click', function() {
                        closeVendorModal();
                    });


                // Submit vendor
                $('#vendorForm').on('submit', function(e) {
                    e.preventDefault();
                    const $form = $(this);
                    const $button = $('#saveVendorBtn');
                    const originalText = $('#saveVendorBtnText').text();

                    // Clear previous errors
                    $('#vendorForm span[id$="Err"]').text('');

                    $button
                        .prop('disabled', true);

                    $('#saveVendorBtnText')
                        .text('Saving...');


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
                                response.message || 'Vendor created successfully.'
                            );

                            closeVendorModal();

                            // Refresh quotation page so the new vendor
                            // appears in the dropdown.
                            setTimeout(function() {
                                window.location.reload();
                            }, 500);
                        },

                        error: function(xhr) {

                            if (xhr.status === 422) {

                                const errors =
                                    xhr.responseJSON?.errors || {};

                                $.each(errors, function(field, messages) {

                                    const errorMap = {
                                        name: '#vendorNameErr',
                                        company_name: '#vendorCompanyNameErr',
                                        contact_person: '#vendorContactPersonErr',
                                        email: '#vendorEmailErr',
                                        phone: '#vendorPhoneErr',
                                        ntn:'#vendorNtnErr',
                                        is_active: '#vendorIsActiveErr',
                                        address: '#vendorAddressErr'
                                    };

                                    if (errorMap[field]) {
                                        $(errorMap[field])
                                            .text(messages[0]);
                                    }

                                    return true;
                                });

                                return;
                            }

                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to create vendor.'
                            );
                        },

                        complete: function() {

                            $button
                                .prop('disabled', false);

                            $('#saveVendorBtnText')
                                .text(originalText);
                        }
                    });

                });

            });
        </script>

        <script>
            $(document).ready(function() {

                $('#quotationForm').on('submit', function(e) {
                    e.preventDefault();

                    const form = $(this);
                    const button = $('#submitBtn');
                    const buttonText = $('#submitBtnText');

                    clearErrors();

                    button.prop('disabled', true);
                    buttonText.text('Saving...');

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        headers: {
                            Accept: 'application/json'
                        },

                        success: function(response) {
                            showToast(
                                'success',
                                response.message || 'Quotation created successfully.'
                            );

                            setTimeout(function() {
                                window.location.href = "{{ route('quotations.index') }}";
                            }, 800);
                        },

                        error: function(xhr) {
                            button.prop('disabled', false);
                            buttonText.text('Save Quotation');

                            if (xhr.status === 422) {
                                showErrors(xhr.responseJSON?.errors || {});
                                return;
                            }

                            showToast(
                                'error',
                                xhr.responseJSON?.message || 'Unable to create quotation.'
                            );
                        }
                    });
                });


                function clearErrors() {
                    $('#vendorIdErr').text('');
                    $('#quotationNumberErr').text('');
                    $('#quotationDateErr').text('');
                    $('#validUntilErr').text('');
                    $('#statusErr').text('');
                    $('#notesErr').text('');
                }


                function showErrors(errors) {
                    $('#vendorIdErr').text(errors.vendor_id?.[0] || '');
                    $('#quotationNumberErr').text(errors.quotation_number?.[0] || '');
                    $('#quotationDateErr').text(errors.quotation_date?.[0] || '');
                    $('#validUntilErr').text(errors.valid_until?.[0] || '');
                    $('#statusErr').text(errors.status?.[0] || '');
                    $('#notesErr').text(errors.notes?.[0] || '');

                    $.each(errors, function(key, messages) {
                        if (key === 'items' || key.startsWith('items.')) {
                            showToast('error', messages[0]);
                            return false;
                        }
                    });
                }

            });
        </script>
    @endpush

</x-layouts.app>
