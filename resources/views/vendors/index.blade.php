
<x-layouts.app title="Vendors">

    <div class="mb-6 flex items-center justify-between">

        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Vendors
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Manage your raw material suppliers.
            </p>
        </div>

        <a
            href="{{ route('vendors.create') }}"
            class="rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-800"
        >
            + Add Vendor
        </a>

    </div>


    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="border-b bg-gray-50 text-xs uppercase text-gray-500">

                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Company</th>
                        <th class="px-6 py-4">Contact</th>
                        <th class="px-6 py-4">Phone</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($vendors as $vendor)

                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4 text-gray-500">
                                {{ $vendors->firstItem() + $loop->index }}
                            </td>


                            <td class="px-6 py-4">

                                <div class="font-medium text-gray-900">
                                    {{ $vendor->name }}
                                </div>

                                @if($vendor->email)
                                    <div class="mt-1 text-xs text-gray-500">
                                        {{ $vendor->email }}
                                    </div>
                                @endif

                            </td>


                            <td class="px-6 py-4 text-gray-600">
                                {{ $vendor->company_name ?? '-' }}
                            </td>


                            <td class="px-6 py-4 text-gray-600">
                                {{ $vendor->contact_person ?? '-' }}
                            </td>


                            <td class="px-6 py-4 text-gray-600">
                                {{ $vendor->phone ?? '-' }}
                            </td>


                            <td class="px-6 py-4">

                                @if($vendor->is_active)

                                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                        Active
                                    </span>

                                @else

                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            <td class="px-6 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    {{-- View --}}
                                    <button
                                        type="button"
                                        class="view-vendor-btn cursor-pointer rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-400 hover:text-white"
                                        data-name="{{ $vendor->name }}"
                                        data-company="{{ $vendor->company_name ?? '-' }}"
                                        data-contact="{{ $vendor->contact_person ?? '-' }}"
                                        data-email="{{ $vendor->email ?? '-' }}"
                                        data-phone="{{ $vendor->phone ?? '-' }}"
                                        data-address="{{ $vendor->address ?? '-' }}"
                                        data-status="{{ $vendor->is_active ? 'Active' : 'Inactive' }}"
                                        data-created="{{ $vendor->created_at->format('d M Y') }}"
                                    >
                                        View
                                    </button>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('vendors.edit', $vendor) }}"
                                        class="rounded-lg border border-blue-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-blue-500 hover:text-white"
                                    >
                                        Edit
                                    </a>


                                    {{-- Delete --}}
                                    <button
                                        type="button"
                                        class="delete-vendor-btn cursor-pointer rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-500 hover:text-white"
                                        data-url="{{ route('vendors.destroy', $vendor) }}"
                                        data-name="{{ $vendor->name }}"
                                    >
                                        Delete
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="7"
                                class="px-6 py-12 text-center text-gray-500"
                            >
                                No vendors found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($vendors->hasPages())

            <div class="border-t px-6 py-4">
                {{ $vendors->links() }}
            </div>

        @endif

    </div>


    {{-- Vendor Details Modal --}}
    <div
        id="vendorModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4"
    >

        <div class="w-full max-w-lg rounded-xl bg-white shadow-xl">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b px-6 py-4">

                <div>
                    <h3
                        id="modalVendorName"
                        class="text-lg font-semibold text-gray-900"
                    >
                    </h3>

                    <p class="text-sm text-gray-500">
                        Vendor details
                    </p>
                </div>

                <button
                    type="button"
                    id="closeVendorModal"
                    class="cursor-pointer text-2xl leading-none text-gray-400 hover:text-gray-700"
                >
                    &times;
                </button>

            </div>


            {{-- Modal Body --}}
            <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2">

                <div>
                    <p class="text-sm text-gray-500">Company</p>
                    <p id="modalVendorCompany" class="mt-1 font-medium text-gray-900"></p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Contact Person</p>
                    <p id="modalVendorContact" class="mt-1 font-medium text-gray-900"></p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p id="modalVendorEmail" class="mt-1 font-medium text-gray-900"></p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Phone</p>
                    <p id="modalVendorPhone" class="mt-1 font-medium text-gray-900"></p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <p id="modalVendorStatus" class="mt-1 font-medium text-gray-900"></p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Added</p>
                    <p id="modalVendorCreated" class="mt-1 font-medium text-gray-900"></p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-sm text-gray-500">Address</p>
                    <p
                        id="modalVendorAddress"
                        class="mt-1 whitespace-pre-line font-medium text-gray-900"
                    ></p>
                </div>

            </div>


            {{-- Modal Footer --}}
            <div class="flex justify-end border-t px-6 py-4">

                <button
                    type="button"
                    id="closeVendorModalBottom"
                    class="cursor-pointer rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Close
                </button>

            </div>

        </div>

    </div>


    @push('scripts')

        <script>

            // View Vendor
            $(document).on('click', '.view-vendor-btn', function () {

                const button = $(this);

                $('#modalVendorName').text(button.data('name'));
                $('#modalVendorCompany').text(button.data('company'));
                $('#modalVendorContact').text(button.data('contact'));
                $('#modalVendorEmail').text(button.data('email'));
                $('#modalVendorPhone').text(button.data('phone'));
                $('#modalVendorStatus').text(button.data('status'));
                $('#modalVendorCreated').text(button.data('created'));
                $('#modalVendorAddress').text(button.data('address'));

                $('#vendorModal')
                    .removeClass('hidden')
                    .addClass('flex');
            });


            // Close Modal
            function closeVendorModal() {

                $('#vendorModal')
                    .removeClass('flex')
                    .addClass('hidden');
            }


            $('#closeVendorModal, #closeVendorModalBottom').on(
                'click',
                function () {
                    closeVendorModal();
                }
            );


            // Close when clicking outside modal
            $('#vendorModal').on('click', function (e) {

                if (e.target === this) {
                    closeVendorModal();
                }

            });


            // Delete Vendor
            function deleteVendor(button) {

                const url = button.data('url');
                const name = button.data('name');

                if (!confirm(`Are you sure you want to delete "${name}"?`)) {
                    return;
                }

                button.prop('disabled', true);

                $.ajax({

                    url: url,

                    type: 'DELETE',

                    headers: {
                        'Accept': 'application/json'
                    },

                    success: function (response) {

                        showToast(
                            'success',
                            response.message
                        );

                        setTimeout(function () {
                            window.location.reload();
                        }, 800);

                    },

                    error: function (xhr) {

                        showToast(
                            'error',
                            xhr.responseJSON?.message ||
                            'Unable to delete vendor.'
                        );

                        button.prop('disabled', false);

                    }

                });

            }


            $(document).on(
                'click',
                '.delete-vendor-btn',
                function () {
                    deleteVendor($(this));
                }
            );

        </script>

    @endpush

</x-layouts.app>