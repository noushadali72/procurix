<x-layouts.app title="Create Purchase Request">

    <div class="mb-6 flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Create Purchase Request
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Create a new raw material purchase request.
            </p>
        </div>


        <a href="{{ route('purchase-requests.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
            <i class="bx bx-arrow-back"></i>

            Back
        </a>

    </div>


    <form id="purchaseRequestForm" action="{{ route('purchase-requests.store') }}" method="POST" novalidate>

        @csrf

        @include('purchase_requests._form')

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('purchase-requests.index') }}"
                class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                Cancel
            </a>

            <button type="button" id="saveDraftBtn"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-60">
                <i class="bx bx-save"></i>
                <span>Save Draft</span>
            </button>

            <button type="submit" id="submitBtn"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-800 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900 disabled:cursor-not-allowed disabled:opacity-60">
                <i class="bx bx-send"></i>
                Send RFQ
            </button>
        </div>

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
        <script>
            $(document).ready(function() {

                const form = $('#purchaseRequestForm');
                const saveDraftBtn = $('#saveDraftBtn');

                let draftId = null;
                let autoSaveTimer = null;
                let isSavingDraft = false;

                /*
                |--------------------------------------------------------------------------
                | Save Draft
                |--------------------------------------------------------------------------
                */

                function saveDraft(showToastMessage = false) {

                    if (isSavingDraft) {
                        return;
                    }

                    isSavingDraft = true;

                    const buttonText = saveDraftBtn.find('span');

                    saveDraftBtn.prop('disabled', true);
                    buttonText.text('Saving...');

                    let data = form.serializeArray();

                    if (draftId) {
                        data.push({
                            name: 'purchase_request_id',
                            value: draftId
                        });
                    }

                    $.ajax({
                        url: "{{ route('purchase-requests.save-draft') }}",
                        type: 'POST',
                        data: $.param(data),
                        headers: {
                            'Accept': 'application/json'
                        },

                        success: function(response) {

                            draftId = response.id;

                            if (showToastMessage) {
                                showToast(
                                    'success',
                                    response.message || 'Draft saved successfully.'
                                );
                            }
                        },

                        error: function(xhr) {

                            if (xhr.status === 422) {
                                showValidationErrors(
                                    xhr.responseJSON?.errors || {}
                                );

                                if (showToastMessage) {
                                    showToast(
                                        'error',
                                        'Please fix the validation errors.'
                                    );
                                }

                                return;
                            }

                            if (showToastMessage) {
                                showToast(
                                    'error',
                                    xhr.responseJSON?.message ||
                                    'Unable to save draft.'
                                );
                            }
                        },

                        complete: function() {

                            isSavingDraft = false;

                            saveDraftBtn.prop('disabled', false);
                            buttonText.text('Save Draft');
                        }
                    });
                }


                /*
                |--------------------------------------------------------------------------
                | Manual Save Draft
                |--------------------------------------------------------------------------
                */

                saveDraftBtn.on('click', function() {
                    saveDraft(true);
                });


                /*
                |--------------------------------------------------------------------------
                | Auto Save
                |--------------------------------------------------------------------------
                */

                function scheduleAutoSave() {

                    clearTimeout(autoSaveTimer);

                    autoSaveTimer = setTimeout(function() {
                        saveDraft(false);
                    }, 700);
                }


                /*
                |--------------------------------------------------------------------------
                | Auto Save When Form Fields Change
                |--------------------------------------------------------------------------
                */

                form.on(
                    'change',
                    'select, textarea, input:not([type="hidden"])',
                    function() {
                        scheduleAutoSave();
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Auto Save When User Changes Material / Quantity / Unit / Cost
                |--------------------------------------------------------------------------
                */

                form.on(
                    'input',
                    'input[name*="[qty]"], input[name*="[unit_cost]"]',
                    function() {
                        scheduleAutoSave();
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Auto Save After Adding / Removing Material Rows
                |--------------------------------------------------------------------------
                */

                $(document).on(
                    'click',
                    '#addItem, .add-item, .remove-item, .remove-row, [data-remove-item]',
                    function() {
                        scheduleAutoSave();
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | Normal Purchase Request Submit
                |--------------------------------------------------------------------------
                */

                form.on('submit', function(e) {

                    e.preventDefault();

                    clearErrors();

                    const button = $('#submitBtn');

                    button.prop('disabled', true);

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),

                        headers: {
                            'Accept': 'application/json'
                        },

                        success: function(response) {

                            showToast(
                                'success',
                                response.message
                            );

                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 800);
                        },

                        error: function(xhr) {

                            button.prop('disabled', false);

                            if (xhr.status === 422) {

                                showValidationErrors(
                                    xhr.responseJSON?.errors || {}
                                );

                                return;
                            }

                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Something went wrong.'
                            );
                        }
                    });
                });


                /*
                |--------------------------------------------------------------------------
                | Clear Validation Errors
                |--------------------------------------------------------------------------
                */

                function clearErrors() {

                    $('#statusErr').text('');
                    $('#notesErr').text('');
                    $('#deliveryAddressErr').text('');
                    $('#vendorIdErr').text('');
                }


                /*
                |--------------------------------------------------------------------------
                | Show Validation Errors
                |--------------------------------------------------------------------------
                */

                function showValidationErrors(errors) {

                    $('#statusErr').text(
                        errors.status?.[0] || ''
                    );

                    $('#notesErr').text(
                        errors.notes?.[0] || ''
                    );

                    $('#deliveryAddressErr').text(
                        errors.delivery_address?.[0] || ''
                    );

                    $('#vendorIdErr').text(
                        errors.vendor_id?.[0] || ''
                    );

                    let itemErrorShown = false;

                    $.each(errors, function(key, messages) {

                        if (
                            !itemErrorShown &&
                            (key === 'items' || key.startsWith('items.'))
                        ) {
                            showToast(
                                'error',
                                messages[0]
                            );

                            itemErrorShown = true;
                        }
                    });
                }

            });
        </script>
    @endpush

</x-layouts.app>
