<x-layouts.app title="Vendors">

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Vendors
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Manage your suppliers and vendor information.
            </p>
        </div>

        <a
            href="{{ route('vendors.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800"
        >
            <i class="bx bx-plus text-lg"></i>
            Add Vendor
        </a>

    </div>


    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <i class="bx bx-check-circle text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="bx bx-error-circle text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif


    {{-- Vendors Card --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        {{-- Card Header --}}
        <div class="flex flex-col gap-1 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h3 class="text-sm font-semibold text-gray-900">
                    Vendor Directory
                </h3>

                <p class="mt-0.5 text-xs text-gray-500">
                    {{ $vendors->total() }}
                    {{ Str::plural('vendor', $vendors->total()) }}
                    registered
                </p>
            </div>

        </div>


        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">

                    <tr>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            #
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Vendor
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Company
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Contact
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Phone
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Status
                        </th>

                        <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse ($vendors as $vendor)

                        <tr class="transition hover:bg-gray-50">

                            {{-- Number --}}
                            <td class="px-5 py-4 text-xs font-medium text-gray-400">
                                {{ $vendors->firstItem() + $loop->index }}
                            </td>


                            {{-- Vendor --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                                        <i class="bx bx-store-alt text-lg"></i>
                                    </div>

                                    <div class="min-w-0">

                                        <div class="truncate font-medium text-gray-900">
                                            {{ $vendor->name }}
                                        </div>

                                        @if ($vendor->email)
                                            <div class="mt-0.5 max-w-xs truncate text-xs text-gray-500">
                                                {{ $vendor->email }}
                                            </div>
                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- Company --}}
                            <td class="px-5 py-4">

                                <span class="text-gray-600">
                                    {{ $vendor->company_name ?? '—' }}
                                </span>

                            </td>


                            {{-- Contact --}}
                            <td class="px-5 py-4">

                                <span class="text-gray-600">
                                    {{ $vendor->contact_person ?? '—' }}
                                </span>

                            </td>


                            {{-- Phone --}}
                            <td class="px-5 py-4">

                                <span class="text-gray-600">
                                    {{ $vendor->phone ?? '—' }}
                                </span>

                            </td>


                            {{-- Status --}}
                            <td class="px-5 py-4">

                                @if ($vendor->is_active)

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                        Active
                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    {{-- View --}}
                                    <button
                                        type="button"
                                        class="view-vendor-btn inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white"
                                        data-name="{{ $vendor->name }}"
                                        data-company="{{ $vendor->company_name ?? '—' }}"
                                        data-contact="{{ $vendor->contact_person ?? '—' }}"
                                        data-email="{{ $vendor->email ?? '—' }}"
                                        data-ntn="{{ $vendor->ntn??'-' }}"
                                        data-phone="{{ $vendor->phone ?? '—' }}"
                                        data-address="{{ $vendor->address ?? '—' }}"
                                        data-status="{{ $vendor->is_active ? 'Active' : 'Inactive' }}"
                                        data-created="{{ $vendor->created_at->format('d M Y') }}"
                                    >
                                        <i class="bx bx-show"></i>
                                        View
                                    </button>


                                    {{-- Edit --}}
                                    <a
                                        href="{{ route('vendors.edit', $vendor) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white"
                                    >
                                        <i class="bx bx-edit-alt"></i>
                                        Edit
                                    </a>


                                    {{-- Delete --}}
                                    <button
                                        type="button"
                                        class="delete-vendor-btn inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-500 hover:text-white"
                                        data-url="{{ route('vendors.destroy', $vendor) }}"
                                        data-name="{{ $vendor->name }}"
                                    >
                                        <i class="bx bx-trash"></i>
                                        Delete
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="px-6 py-16 text-center">

                                <div class="mx-auto flex max-w-sm flex-col items-center">

                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                        <i class="bx bx-store-alt text-2xl"></i>
                                    </div>

                                    <p class="mt-3 text-sm font-semibold text-gray-700">
                                        No vendors found
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Add your first vendor to start managing suppliers.
                                    </p>

                                    <a
                                        href="{{ route('vendors.create') }}"
                                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-800"
                                    >
                                        <i class="bx bx-plus"></i>
                                        Add Vendor
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if ($vendors->hasPages())

            <div class="border-t border-gray-200 px-5 py-4">
                {{ $vendors->links() }}
            </div>

        @endif

    </div>


    {{-- Vendor Details Modal --}}
    <div
        id="vendorModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 py-6"
    >

        <div
            class="w-full max-w-lg overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl"
            role="dialog"
            aria-modal="true"
        >

            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">

                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                        <i class="bx bx-store-alt text-lg"></i>
                    </div>

                    <div>
                        <h3
                            id="modalVendorName"
                            class="text-base font-semibold text-gray-900"
                        ></h3>

                        <p class="text-xs text-gray-500">
                            Vendor details
                        </p>
                    </div>

                </div>

                <button
                    type="button"
                    id="closeVendorModal"
                    class="flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-xl text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
                    aria-label="Close"
                >
                    <i class="bx bx-x"></i>
                </button>

            </div>


            {{-- Modal Body --}}
            <div class="grid grid-cols-1 gap-x-6 gap-y-5 p-5 sm:grid-cols-2">

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Company
                    </p>
                    <p id="modalVendorCompany" class="mt-1 text-sm font-medium text-gray-900"></p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Contact Person
                    </p>
                    <p id="modalVendorContact" class="mt-1 text-sm font-medium text-gray-900"></p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Email
                    </p>
                    <p id="modalVendorEmail" class="mt-1 break-all text-sm font-medium text-gray-900"></p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Phone
                    </p>
                    <p id="modalVendorPhone" class="mt-1 text-sm font-medium text-gray-900"></p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        ntn
                    </p>
                    <p id="modalVendorntn" class="mt-1 text-sm font-medium text-gray-900"></p>
                </div>


                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Status
                    </p>
                    <p id="modalVendorStatus" class="mt-1 text-sm font-medium text-gray-900"></p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Added
                    </p>
                    <p id="modalVendorCreated" class="mt-1 text-sm font-medium text-gray-900"></p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                        Address
                    </p>
                    <p
                        id="modalVendorAddress"
                        class="mt-1 whitespace-pre-line text-sm font-medium text-gray-900"
                    ></p>
                </div>

            </div>


            {{-- Modal Footer --}}
            <div class="flex justify-end border-t border-gray-200 px-5 py-4">

                <button
                    type="button"
                    id="closeVendorModalBottom"
                    class="inline-flex cursor-pointer items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
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
                $('#modalVendorntn').text(button.data('ntn'));
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
                closeVendorModal
            );


            // Close on backdrop click
            $('#vendorModal').on('click', function (e) {
                if (e.target === this) {
                    closeVendorModal();
                }
            });


            // Close with Escape
            $(document).on('keydown', function (e) {
                if (e.key === 'Escape') {
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
                            response.message || 'Vendor deleted successfully.'
                        );

                        setTimeout(function () {
                            window.location.reload();
                        }, 800);
                    },

                    error: function (xhr) {
                        showToast(
                            'error',
                            xhr.responseJSON?.message || 'Unable to delete vendor.'
                        );

                        button.prop('disabled', false);
                    }
                });
            }

            $(document).on('click', '.delete-vendor-btn', function () {
                deleteVendor($(this));
            });
        </script>

    @endpush

</x-layouts.app>