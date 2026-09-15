<x-layouts.app title="Units">

    {{-- Page Header --}}
    <div class="mb-6 flex items-center justify-between">

        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Units
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Manage measurement units used by products and raw materials.
            </p>
        </div>

        <button type="button" id="openCreateUnitModal"
            class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">
            <i class="bx bx-plus text-lg"></i>
            Add Unit
        </button>

    </div>


    {{-- Alerts --}}
    @if (session('success'))
        <div
            class="mb-6 flex items-center gap-2 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <i class="bx bx-check-circle text-lg"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div
            class="mb-6 flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="bx bx-error-circle text-lg"></i>
            {{ session('error') }}
        </div>
    @endif


    {{-- Units Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead
                    class="border-b border-gray-200 bg-gray-50 text-xs font-medium uppercase tracking-wide text-gray-500">

                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Short Name</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4">Factor</th>
                        <th class="px-6 py-4">Base</th>
                        <th class="px-6 py-4">Created</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($units as $unit)
                        <tr class="transition hover:bg-gray-50">

                            <td class="px-6 py-4 text-gray-500">
                                {{ $units->firstItem() + $loop->index }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $unit->name }}
                            </td>

                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                    {{ $unit->short_name }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $unit->unitCategory->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $unit->conversion_factor }}
                            </td>

                            <td class="px-6 py-4">
                                @if ($unit->is_base)
                                    <span
                                        class="inline-flex rounded-md bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                                        Yes
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                        No
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $unit->created_at->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- Edit --}}
                                    <button type="button"
                                        class="edit-unit-btn rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-blue-500 hover:text-white"
                                        data-id="{{ $unit->id }}" data-name="{{ $unit->name }}"
                                        data-short-name="{{ $unit->short_name }}"
                                        data-category-id="{{ $unit->unit_category_id }}"
                                        data-conversion-factor="{{ $unit->conversion_factor }}"
                                        data-is-base="{{ $unit->is_base }}"
                                        data-url="{{ route('units.update', $unit) }}">
                                        Edit
                                    </button>

                                    {{-- Delete --}}
                                    <button type="button"
                                        class="delete-unit-btn inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-50"
                                        data-url="{{ route('units.destroy', $unit) }}"
                                        data-name="{{ $unit->name }}">
                                        <i class="bx bx-trash"></i>
                                        <span>Delete</span>
                                    </button>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="px-6 py-14 text-center">

                                <div
                                    class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-gray-100">
                                    <i class="bx bx-ruler text-xl text-gray-500"></i>
                                </div>

                                <h3 class="mt-3 text-sm font-medium text-gray-900">
                                    No units found
                                </h3>

                                <p class="mt-1 text-sm text-gray-500">
                                    Add your first unit to get started.
                                </p>

                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($units->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $units->links() }}
            </div>
        @endif

    </div>


    {{-- Unit Modal --}}
    <div id="unitModal" class="fixed inset-0 z-50 hidden">

        {{-- Overlay --}}
        <div id="unitModalOverlay" class="absolute inset-0 bg-black/50"></div>

        {{-- Modal --}}
        <div class="relative flex min-h-full items-center justify-center p-4">

            <div class="w-full max-w-md rounded-xl bg-white shadow-xl">

                {{-- Header --}}
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">

                    <div>
                        <h3 id="unitModalTitle" class="text-lg font-semibold text-gray-900">
                            Add Unit
                        </h3>

                        <p class="mt-1 text-sm text-gray-500">
                            Add or update a measurement unit.
                        </p>
                    </div>

                    <button type="button" id="closeUnitModal"
                        class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600">
                        <i class="bx bx-x text-xl"></i>
                    </button>

                </div>


                {{-- Form --}}
                <form id="unitForm">

                    <div class="space-y-5 p-6">

                        {{-- Name --}}
                        <div>
                            <label for="unit_name" class="mb-1 block text-sm font-medium text-gray-700">
                                Unit Name
                            </label>

                            <input type="text" id="unit_name" name="name" placeholder="e.g. Kilogram"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-gray-500 focus:ring-gray-500">

                            <p id="nameError" class="mt-1 hidden text-sm text-red-600"></p>
                        </div>


                        {{-- Short Name --}}
                        <div>
                            <label for="unit_short_name" class="mb-1 block text-sm font-medium text-gray-700">
                                Short Name
                            </label>

                            <input type="text" id="unit_short_name" name="short_name" placeholder="e.g. kg"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-gray-500 focus:ring-gray-500">

                            <p id="shortNameError" class="mt-1 hidden text-sm text-red-600"></p>
                        </div>


                        {{-- Category --}}
                        <div>
                            <label for="unit_category_id" class="mb-1 block text-sm font-medium text-gray-700">
                                Category
                            </label>

                            <select id="unit_category_id" name="unit_category_id"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-gray-500 focus:ring-gray-500">

                                <option value="">Select category</option>

                                @foreach ($unitCategories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach

                            </select>

                            <p id="unitCategoryIdError" class="mt-1 hidden text-sm text-red-600"></p>
                        </div>


                        {{-- Conversion Factor --}}
                        <div>
                            <label for="conversion_factor" class="mb-1 block text-sm font-medium text-gray-700">
                                Conversion Factor
                            </label>

                            <input type="number" id="conversion_factor" name="conversion_factor" step="any"
                                min="0" placeholder="e.g. 1000"
                                class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:border-gray-500 focus:ring-gray-500">

                            <p class="mt-1 text-xs text-gray-500">
                                Example: 1 kg = 1000 g
                            </p>

                            <p id="conversionFactorError" class="mt-1 hidden text-sm text-red-600"></p>
                        </div>


                        {{-- Base Unit --}}
                        <div>
                            <label class="flex cursor-pointer items-center gap-3">

                                <input type="checkbox" id="is_base" name="is_base" value="1"
                                    class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-500">

                                <span>
                                    <span class="block text-sm font-medium text-gray-700">
                                        Base Unit
                                    </span>

                                    <span class="block text-xs text-gray-500">
                                        Use this as the base unit for this category.
                                    </span>
                                </span>

                            </label>

                            <p id="isBaseError" class="mt-1 hidden text-sm text-red-600"></p>
                        </div>

                    </div>


                    {{-- Footer --}}
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">

                        <button type="button" id="cancelUnitModal"
                            class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>

                        <button type="submit" id="unitSubmitBtn"
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-800">

                            <i class="bx bx-plus text-lg"></i>

                            <span>Add Unit</span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    @push('scripts')

    <script>
        $(document).on(
                'input',
                '#conversion_factor',
                function () {
                    this.value = this.value.replace(/\D/g, '');
                }
            );
    </script>
        <script>
            $(function() {

                const modal = $('#unitModal');
                const form = $('#unitForm');
                let editUrl = null;


                // =========================
                // Modal
                // =========================

                function openModal() {
                    modal.removeClass('hidden');
                    $('body').addClass('overflow-hidden');
                }

                function closeModal() {
                    modal.addClass('hidden');
                    $('body').removeClass('overflow-hidden');
                }


                // =========================
                // Errors
                // =========================

                function clearErrors() {

                    $('#nameError, #shortNameError, #unitCategoryIdError, #conversionFactorError, #isBaseError')
                        .text('')
                        .addClass('hidden');

                    $('#unit_name, #unit_short_name, #unit_category_id, #conversion_factor')
                        .removeClass('border-red-500');
                }

                function showErrors(errors) {
                    if (errors.name) {
                        $('#nameError')
                            .text(errors.name[0])
                            .removeClass('hidden');
                        $('#unit_name').addClass('border-red-500');
                    }

                    if (errors.short_name) {
                        $('#shortNameError')
                            .text(errors.short_name[0])
                            .removeClass('hidden');
                        $('#unit_short_name').addClass('border-red-500');
                    }

                    if (errors.unit_category_id) {
                        $('#unitCategoryIdError')
                            .text(errors.unit_category_id[0])
                            .removeClass('hidden');
                        $('#unit_category_id').addClass('border-red-500');
                    }

                    if (errors.conversion_factor) {
                        $('#conversionFactorError')
                            .text(errors.conversion_factor[0])
                            .removeClass('hidden');
                        $('#conversion_factor').addClass('border-red-500');
                    }

                    if (errors.is_base) {
                        $('#isBaseError')
                            .text(errors.is_base[0])
                            .removeClass('hidden');
                    }
                }


                // =========================
                // Form
                // =========================

                function resetForm() {
                    form[0].reset();
                    clearErrors();
                    editUrl = null;
                    $('#unitModalTitle').text('Add Unit');
                    $('#unitSubmitBtn span').text('Add Unit');
                    $('#unitSubmitBtn i')
                        .attr('class', 'bx bx-plus text-lg');
                }


                function setEditMode(button) {
                    editUrl = button.data('url');
                    $('#unit_name').val(button.data('name'));
                    $('#unit_short_name')
                        .val(button.data('short-name'));
                    $('#unit_category_id')
                        .val(button.data('category-id'));
                    $('#conversion_factor')
                        .val(button.data('conversion-factor'));
                    $('#is_base')
                        .prop('checked', Number(button.data('is-base')) === 1);
                    $('#unitModalTitle').text('Edit Unit');
                    $('#unitSubmitBtn span')
                        .text('Update Unit');
                    $('#unitSubmitBtn i')
                        .attr('class', 'bx bx-save text-lg');
                }


                // =========================
                // Create Unit
                // =========================

                function createUnit() {
                    const submitButton = $('#unitSubmitBtn');
                    submitButton.prop('disabled', true);
                    submitButton.find('span').text('Creating...');
                    $.ajax({
                        url: '{{ route('units.store') }}',
                        type: 'POST',
                        data: form.serialize(),
                        headers: {
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            closeModal();
                            resetForm();
                            showToast('success', response.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 800);
                        },

                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const response = xhr.responseJSON || {};
                                if (response.message) {
                                    showToast('error', response.message);
                                }
                                if (response.errors) {
                                    showErrors(response.errors);
                                    openModal();
                                }
                                return;
                            }
                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to create unit.'
                            );
                        },

                        complete: function() {
                            submitButton.prop('disabled', false);
                            submitButton.find('span')
                                .text('Add Unit');
                        }
                    });
                }


                // =========================
                // Update Unit
                // =========================

                function updateUnit() {
                    const submitButton = $('#unitSubmitBtn');
                    submitButton.prop('disabled', true);
                    submitButton.find('span')
                        .text('Updating...');

                    $.ajax({
                        url: editUrl,
                        type: 'PUT',
                        data: form.serialize(),
                        headers: {
                            'Accept': 'application/json'
                        },

                        success: function(response) {
                            closeModal();
                            resetForm();
                            showToast('success', response.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 800);
                        },

                        error: function(xhr) {
                            if (xhr.status === 422) {
                                const response = xhr.responseJSON || {};
                                if (response.message) {
                                    showToast('error', response.message);
                                }
                                if (response.errors) {
                                    showErrors(response.errors);
                                    openModal();
                                }
                                return;
                            }

                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to update unit.'
                            );
                        },

                        complete: function() {
                            submitButton.prop('disabled', false);
                            submitButton.find('span')
                                .text('Update Unit');
                        }
                    });
                }


                // =========================
                // Delete Unit
                // =========================

                function deleteUnit(button) {
                    const url = button.data('url');
                    const name = button.data('name');
                    if (!confirm(`Are you sure you want to delete "${name}"?`)) {
                        return;
                    }
                    button.prop('disabled', true);
                    button.find('span').text('Deleting...');
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            showToast('success', response.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 800);
                        },
                        error: function(xhr) {
                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to delete unit.'
                            );
                            button.prop('disabled', false);
                            button.find('span').text('Delete');
                        }
                    });
                }


                // =========================
                // Open Create Modal
                // =========================

                $('#openCreateUnitModal').on('click', function() {
                    resetForm();
                    openModal();
                    $('#unit_name').trigger('focus');
                });


                // =========================
                // Open Edit Modal
                // =========================

                $(document).on('click', '.edit-unit-btn', function() {
                    const button = $(this);
                    resetForm();
                    setEditMode(button);
                    openModal();
                    $('#unit_name').trigger('focus');
                });


                // =========================
                // Submit
                // =========================

                form.on('submit', function(e) {
                    e.preventDefault();
                    clearErrors();
                    if (editUrl) {
                        updateUnit();
                    } else {
                        createUnit();
                    }
                });


                // =========================
                // Delete
                // =========================

                $(document).on('click', '.delete-unit-btn', function() {
                    deleteUnit($(this));
                });


                // =========================
                // Close Modal
                // =========================

                $('#closeUnitModal, #cancelUnitModal, #unitModalOverlay')
                    .on('click', function() {
                        closeModal();
                    });


                // =========================
                // Escape
                // =========================

                $(document).on('keydown', function(e) {
                    if (
                        e.key === 'Escape' &&
                        !modal.hasClass('hidden')
                    ) {
                        closeModal();
                    }
                });

            });
        </script>
    @endpush

</x-layouts.app>
