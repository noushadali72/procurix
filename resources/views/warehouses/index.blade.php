<x-layouts.app title="Warehouses">

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">
                    Warehouses
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Manage your warehouses and storage locations.
                </p>
            </div>

            <button type="button" id="openCreateWarehouse"
                class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">
                <i class="bx bx-plus text-lg"></i>
                Add Warehouse
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="overflow-x-auto">
                <table class="w-full text-left">

                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Warehouse
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Location
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Phone
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Capacity
                            </th>
                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Status
                            </th>
                            <th
                                class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($warehouses as $warehouse)
                            <tr class="hover:bg-gray-50">

                                <td class="px-5 py-4">
                                    <div class="font-medium text-gray-900">
                                        {{ $warehouse->name }}
                                    </div>

                                    @if ($warehouse->description)
                                        <div class="mt-1 text-xs text-gray-500">
                                            {{ $warehouse->description }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="text-sm text-gray-700">
                                        {{ $warehouse->city ?: '—' }}
                                    </div>

                                    @if ($warehouse->country)
                                        <div class="mt-1 text-xs text-gray-500">
                                            {{ $warehouse->country }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $warehouse->phone_no ?: '—' }}
                                </td>

                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $warehouse->capacity ?: '—' }}
                                </td>

                                <td class="px-5 py-4">
                                    @if ($warehouse->is_active)
                                        <span
                                            class="inline-flex rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">

                                        <button type="button"
                                            class="editWarehouse rounded-lg border border-gray-200 px-3 py-2 text-sm text-gray-600 transition hover:bg-gray-50 hover:text-gray-900"
                                            data-id="{{ $warehouse->id }}" data-name="{{ $warehouse->name }}"
                                            data-description="{{ $warehouse->description }}"
                                            data-address="{{ $warehouse->address }}" data-city="{{ $warehouse->city }}"
                                            data-postal-code="{{ $warehouse->postal_code }}"
                                            data-country="{{ $warehouse->country }}"
                                            data-phone="{{ $warehouse->phone_no }}"
                                            data-capacity="{{ $warehouse->capacity }}"
                                            data-active="{{ $warehouse->is_active }}">
                                            <i class="bx bx-edit"></i>
                                        </button>

                                        <form action="{{ route('warehouses.destroy', $warehouse) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this warehouse?')">
                                            @csrf
                                            @method("DELETE")

                                            <button type="submit"
                                                class="rounded-lg border border-red-200 px-3 py-2 text-sm text-red-600 transition hover:bg-red-50">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-500">
                                    No warehouses found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            @if ($warehouses->hasPages())
                <div class="border-t border-gray-200 px-5 py-4">
                    {{ $warehouses->links() }}
                </div>
            @endif

        </div>
    </div>


    {{-- Create / Edit Modal --}}
    <div id="warehouseModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
        <div class="w-full max-w-3xl rounded-xl bg-white shadow-xl">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <div>
                    <h2 id="warehouseModalTitle" class="text-lg font-semibold text-gray-900">
                        Add Warehouse
                    </h2>

                    <p class="mt-1 text-sm text-gray-500">
                        Enter warehouse information.
                    </p>
                </div>

                <button type="button" id="closeWarehouseModal" class="text-gray-400 hover:text-gray-600">
                    <i class="bx bx-x text-2xl"></i>
                </button>
            </div>

            <form id="warehouseForm">

                @csrf

                <input type="hidden" id="warehouseId">

                <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                    {{-- Name --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Name<sup>*</sup>
                        </label>

                        <input type="text" id="warehouseName" name="name"
                            placeholder="Central Distribution Warehouse"
                            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm placeholder:text-gray-400 focus:border-gray-500 focus:ring-gray-500">

                        <span id="nameErr" class="mt-1 block text-sm text-red-600"></span>
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Phone
                        </label>

                        <input type="text" id="warehousePhone" name="phone_no" placeholder="+92 21 34567890"
                            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm placeholder:text-gray-400 focus:border-gray-500 focus:ring-gray-500">

                        <span id="phoneErr" class="mt-1 block text-sm text-red-600"></span>
                    </div>

                    {{-- Address --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Address
                        </label>

                        <input type="text" id="warehouseAddress" name="address" placeholder="Warehouse address"
                            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm placeholder:text-gray-400 focus:border-gray-500 focus:ring-gray-500">

                        <span id="addressErr" class="mt-1 block text-sm text-red-600"></span>
                    </div>

                    {{-- City --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            City
                        </label>

                        <input type="text" id="warehouseCity" name="city" placeholder="Karachi"
                            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm placeholder:text-gray-400 focus:border-gray-500 focus:ring-gray-500">

                        <span id="cityErr" class="mt-1 block text-sm text-red-600"></span>
                    </div>

                    {{-- Postal Code --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Postal Code
                        </label>

                        <input type="text" id="warehousePostalCode" name="postal_code" placeholder="74000"
                            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm placeholder:text-gray-400 focus:border-gray-500 focus:ring-gray-500">

                        <span id="postalCodeErr" class="mt-1 block text-sm text-red-600"></span>
                    </div>

                    {{-- Country --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Country
                        </label>

                        <input type="text" id="warehouseCountry" name="country" placeholder="Pakistan"
                            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm placeholder:text-gray-400 focus:border-gray-500 focus:ring-gray-500">

                        <span id="countryErr" class="mt-1 block text-sm text-red-600"></span>
                    </div>

                    {{-- Capacity --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Capacity
                        </label>

                        <input type="text" id="warehouseCapacity" name="capacity" placeholder="5,000 sq. ft."
                            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm placeholder:text-gray-400 focus:border-gray-500 focus:ring-gray-500">

                        <span id="capacityErr" class="mt-1 block text-sm text-red-600"></span>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Status
                        </label>

                        <select id="warehouseActive" name="is_active"
                            class="w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>

                        <span id="activeErr" class="mt-1 block text-sm text-red-600"></span>
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Description
                        </label>

                        <textarea id="warehouseDescription" name="description" rows="3"
                            placeholder="Primary storage facility for raw materials and finished goods."
                            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm placeholder:text-gray-400 focus:border-gray-500 focus:ring-gray-500"></textarea>

                        <span id="descriptionErr" class="mt-1 block text-sm text-red-600"></span>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 border-t border-gray-200 px-6 py-4">

                    <button type="button" id="cancelWarehouseModal"
                        class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>

                    <button type="submit" id="saveWarehouseBtn"
                        class="rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-medium text-white hover:bg-gray-800">
                        <span id="saveWarehouseText">Save Warehouse</span>
                    </button>

                </div>

            </form>

        </div>
    </div>


    @push('scripts')
        <script>
            $(function() {

                const modal = $('#warehouseModal');
                const form = $('#warehouseForm');

                function clearErrors() {
                    $('[id$="Err"]').text('');
                }

                function openModal() {
                    modal.removeClass('hidden').addClass('flex');
                }

                function closeModal() {
                    modal.removeClass('flex').addClass('hidden');
                }

                function resetForm() {
                    form[0].reset();
                    $('#warehouseId').val('');
                    $('#warehouseActive').val('1');
                    clearErrors();
                }

                function showErrors(errors) {
                    const fields = {
                        name: '#nameErr',
                        description: '#descriptionErr',
                        address: '#addressErr',
                        city: '#cityErr',
                        postal_code: '#postalCodeErr',
                        country: '#countryErr',
                        phone_no: '#phoneErr',
                        capacity: '#capacityErr',
                        is_active: '#activeErr'
                    };

                    $.each(fields, function(field, selector) {
                        if (errors[field]) {
                            $(selector).text(errors[field][0]);
                        }
                    });
                }

                // Create
                $('#openCreateWarehouse').on('click', function() {
                    resetForm();

                    $('#warehouseModalTitle').text('Add Warehouse');
                    $('#saveWarehouseText').text('Save Warehouse');

                    openModal();
                });

                // Edit
                $('.editWarehouse').on('click', function() {

                    const button = $(this);

                    resetForm();

                    $('#warehouseModalTitle').text('Edit Warehouse');
                    $('#saveWarehouseText').text('Update Warehouse');

                    $('#warehouseId').val(button.data('id'));
                    $('#warehouseName').val(button.data('name'));
                    $('#warehouseDescription').val(button.data('description'));
                    $('#warehouseAddress').val(button.data('address'));
                    $('#warehouseCity').val(button.data('city'));
                    $('#warehousePostalCode').val(button.data('postal-code'));
                    $('#warehouseCountry').val(button.data('country'));
                    $('#warehousePhone').val(button.data('phone'));
                    $('#warehouseCapacity').val(button.data('capacity'));
                    $('#warehouseActive').val(button.data('active') ? '1' : '0');

                    openModal();
                });

                // Save
                form.on('submit', function(e) {
                    e.preventDefault();

                    clearErrors();

                    const id = $('#warehouseId').val();
                    const isEdit = Boolean(id);

                    const url = isEdit ?
                        "{{ url('warehouses') }}/" + id :
                        "{{ route('warehouses.store') }}";

                    const method = isEdit ? 'PUT' : 'POST';

                    const button = $('#saveWarehouseBtn');

                    button.prop('disabled', true);
                    $('#saveWarehouseText').text(
                        isEdit ? 'Updating...' : 'Saving...'
                    );

                    $.ajax({
                        url: url,
                        type: method,
                        data: form.serialize(),
                        headers: {
                            'Accept': 'application/json'
                        },

                        success: function(response) {
                            showToast(
                                'success',
                                response.message || 'Warehouse saved successfully.'
                            );

                            closeModal();

                            setTimeout(function() {
                                window.location.reload();
                            }, 600);
                        },

                        error: function(xhr) {
                            if (xhr.status === 422) {
                                showErrors(xhr.responseJSON.errors || {});
                            } else {
                                showToast(
                                    'error',
                                    xhr.responseJSON?.message ||
                                    'Unable to save warehouse.'
                                );
                            }
                        },

                        complete: function() {
                            button.prop('disabled', false);

                            $('#saveWarehouseText').text(
                                isEdit ? 'Update Warehouse' : 'Save Warehouse'
                            );
                        }
                    });
                });

                // Close
                $('#closeWarehouseModal, #cancelWarehouseModal').on('click', function() {
                    closeModal();
                });

                // Close backdrop
                modal.on('click', function(e) {
                    if (e.target === this) {
                        closeModal();
                    }
                });

                // Escape
                $(document).on('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeModal();
                    }
                });

            });
        </script>
    @endpush

</x-layouts.app>
