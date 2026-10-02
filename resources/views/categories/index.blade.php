<x-layouts.app title="Categories">

    <div class="space-y-6 mb-8">

        {{-- Page Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Categories
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Manage categories used by products and raw materials.
                </p>
            </div>

            <button type="button" id="addCategoryBtn"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800">
                <i class="bx bx-plus text-lg"></i>
                Add Category
            </button>
        </div>

        {{-- Search --}}
        <div class="border-b border-gray-200 px-5 py-4">

            <form method="GET" action="{{ route('categories.index') }}"
                class="flex flex-col gap-3 sm:flex-row sm:items-center">

                <div class="relative w-full sm:max-w-md">

                    <i
                        class="bx bx-search absolute left-3 top-1/2
                       -translate-y-1/2 text-lg text-gray-400">
                    </i>

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search categories..."
                        class="w-full rounded-lg border border-gray-300
                       bg-white py-2.5 pl-10 pr-10 text-sm
                       text-gray-900 outline-none transition
                       placeholder:text-gray-400
                       focus:border-gray-900
                       focus:ring-1 focus:ring-gray-900">

                    @if (request('search'))
                        <a href="{{ route('categories.index') }}"
                            class="absolute right-3 top-1/2 flex
                           -translate-y-1/2 items-center
                           justify-center text-gray-400
                           transition hover:text-gray-700"
                            title="Clear search">
                            <i class="bx bx-x text-lg"></i>
                        </a>
                    @endif

                </div>


                <button type="submit"
                    class="inline-flex items-center justify-center
                   gap-2 rounded-lg bg-gray-900 px-4 py-2.5
                   text-sm font-semibold text-white transition
                   hover:bg-gray-800">
                    <i class="bx bx-search"></i>
                    Search
                </button>


                @if (request('search'))
                    <a href="{{ route('categories.index') }}"
                        class="inline-flex items-center justify-center
                       rounded-lg border border-gray-300 px-4
                       py-2.5 text-sm font-medium text-gray-700
                       transition hover:border-gray-900
                       hover:bg-gray-900 hover:text-white">
                        Clear
                    </a>
                @endif

            </form>

        </div>


        {{-- Category List --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

            {{-- Card Header --}}
            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                <div>
                    <h2 class="text-base font-semibold text-gray-900">
                        Category List
                    </h2>

                    <p class="mt-0.5 text-xs text-gray-500">
                        All available categories
                    </p>
                </div>

                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                    {{ $categories->count() }} Total
                </span>
            </div>


            @if ($categories->count())

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px]">

                        <thead>
                            <tr class="border-b border-gray-200 bg-gray-50">

                                <th
                                    class="w-1/5 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Name
                                </th>

                                <th
                                    class="w-1/5 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Slug
                                </th>

                                <th
                                    class="w-2/5 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Description
                                </th>

                                <th
                                    class="w-1/10 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Inventories
                                </th>

                                <th
                                    class="w-1/5 px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    Actions
                                </th>

                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @foreach ($categories as $category)
                                <tr class="transition hover:bg-gray-50">

                                    {{-- Name --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">

                                            <div
                                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                                <i class="bx bx-category"></i>
                                            </div>

                                            <span class="text-sm font-medium text-gray-900">
                                                {{ $category->name }}
                                            </span>

                                        </div>
                                    </td>


                                    {{-- Slug --}}
                                    <td class="px-5 py-4">
                                        @if ($category->slug)
                                            <span
                                                class="rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                                {{ $category->slug }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">
                                                —
                                            </span>
                                        @endif
                                    </td>


                                    {{-- Description --}}
                                    <td class="px-5 py-4">
                                        @if ($category->description)
                                            <span class="block max-w-lg truncate text-sm text-gray-600"
                                                title="{{ $category->description }}">
                                                {{ $category->description }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">
                                                —
                                            </span>
                                        @endif
                                    </td>


                                    {{-- Inventories --}}
                                    <td class="px-5 py-4 text-center">

                                        <span
                                            class="inline-flex min-w-8 items-center justify-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">
                                            {{ $category->products_count + $category->raw_materials_count ?? 0 }}
                                        </span>

                                    </td>


                                    {{-- Actions --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">

                                            {{-- Edit --}}
                                            <button type="button"
                                                class="edit-category inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white"
                                                data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                data-slug="{{ $category->slug }}"
                                                data-description="{{ $category->description }}"
                                                data-inventory-account-id="{{ $category->inventory_account_id }}"
                                                data-purchase-account-id="{{ $category->purchase_account_id }}">
                                                <i class="bx bx-edit-alt"></i>
                                                Edit
                                            </button>



                                            {{-- Delete --}}
                                            <button type="button"
                                                class="delete-category inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-500 hover:text-white"
                                                data-url="{{ route('categories.destroy', $category) }}"
                                                data-name="{{ $category->name }}">
                                                <i class="bx bx-trash"></i>
                                                Delete
                                            </button>

                                        </div>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            @else
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center px-6 py-16 text-center">

                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                        <i class="bx bx-category text-2xl"></i>
                    </div>

                    <h3 class="mt-4 text-sm font-semibold text-gray-900">
                        No categories found
                    </h3>

                    <p class="mt-1 max-w-sm text-sm text-gray-500">
                        Create your first category to organize products and raw materials.
                    </p>

                    <button type="button" id="emptyAddCategoryBtn"
                        class="mt-5 inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800">
                        <i class="bx bx-plus"></i>
                        Add Category
                    </button>

                </div>

            @endif

        </div>
        {{-- Pagination --}}
        @if ($categories->hasPages())
            <div class="border-t border-gray-200 px-5 py-4">
                {{ $categories->links() }}
            </div>
        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- ADD / EDIT CATEGORY MODAL --}}
    {{-- ========================================================= --}}

    <div id="categoryModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">

        {{-- Overlay --}}
        <div id="categoryModalOverlay" class="absolute inset-0 bg-black/50"></div>


        {{-- Modal --}}
        <div class="relative flex min-h-full items-center justify-center p-4">

            <div class="w-full max-w-lg rounded-xl bg-white shadow-xl">

                {{-- Modal Header --}}
                <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">

                    <div>
                        <h2 id="categoryModalTitle" class="text-lg font-semibold text-gray-900">
                            Add Category
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Add a category for products and raw materials.
                        </p>
                    </div>

                    <button type="button" id="closeCategoryModal"
                        class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-700">
                        <i class="bx bx-x text-xl"></i>
                    </button>

                </div>
                <form id="categoryForm">

                    @csrf

                    <input type="hidden" id="categoryId">

                    {{-- Tabs --}}
                    <div class="border-b border-gray-200 px-5 pt-4">

                        <div class="flex gap-6">

                            <button type="button"
                                class="category-tab border-b-2 border-gray-900 pb-3 text-sm font-medium text-gray-900"
                                data-tab="details">
                                Details
                            </button>

                            <button type="button"
                                class="category-tab border-b-2 border-transparent pb-3 text-sm font-medium text-gray-500 transition hover:text-gray-900"
                                data-tab="accounts">
                                Accounts
                            </button>

                        </div>

                    </div>


                    <div class="px-5 py-5">

                        {{-- ================================================= --}}
                        {{-- DETAILS TAB --}}
                        {{-- ================================================= --}}

                        <div id="categoryDetailsTab" class="category-tab-content space-y-5">

                            {{-- Name --}}
                            <div>
                                <label for="categoryName" class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Name
                                    <span class="text-red-500">*</span>
                                </label>

                                <input type="text" id="categoryName" name="name"
                                    placeholder="Enter category name"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">

                                <p id="categoryNameError" class="mt-1.5 hidden text-xs text-red-500"></p>
                            </div>


                            {{-- Slug --}}
                            <div>
                                <label for="categorySlug" class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Slug
                                </label>

                                <input type="text" id="categorySlug" name="slug"
                                    placeholder="Enter category slug"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-gray-900 focus:ring-1 focus:ring-gray-900">

                                <p id="categorySlugError" class="mt-1.5 hidden text-xs text-red-500"></p>
                            </div>


                            {{-- Description --}}
                            <div>
                                <label for="categoryDescription"
                                    class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Description
                                </label>

                                <textarea id="categoryDescription" name="description" rows="4" placeholder="Enter category description"
                                    class="w-full resize-none rounded-lg border border-gray-300 px-3 py-2.5 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 focus:border-gray-900 focus:ring-1 focus:ring-gray-900"></textarea>

                                <p id="categoryDescriptionError" class="mt-1.5 hidden text-xs text-red-500"></p>
                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- ACCOUNTS TAB --}}
                        {{-- ================================================= --}}

                        <div id="categoryAccountsTab" class="category-tab-content hidden space-y-5">

                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">
                                    Accounting Configuration
                                </h3>

                                <p class="mt-1 text-xs text-gray-500">
                                    Select the accounts used for transactions related to this category.
                                </p>
                            </div>


                            {{-- Inventory Account --}}
                            <div>

                                <label for="categoryInventoryAccount"
                                    class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Inventory Account
                                </label>

                                <select id="categoryInventoryAccount" name="inventory_account_id"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-gray-900 focus:ring-1 focus:ring-gray-900">

                                    <option value="">
                                        Select inventory account
                                    </option>

                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">
                                            {{ $account->code }} - {{ $account->name }}
                                        </option>
                                    @endforeach

                                </select>

                                <p id="categoryInventoryAccountError" class="mt-1.5 hidden text-xs text-red-500"></p>

                            </div>


                            {{-- Purchase Account --}}
                            <div>

                                <label for="categoryPurchaseAccount"
                                    class="mb-1.5 block text-sm font-medium text-gray-700">
                                    Purchase Account
                                </label>

                                <select id="categoryPurchaseAccount" name="purchase_account_id"
                                    class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 outline-none transition focus:border-gray-900 focus:ring-1 focus:ring-gray-900">

                                    <option value="">
                                        Select purchase account
                                    </option>

                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">
                                            {{ $account->code }} - {{ $account->name }}
                                        </option>
                                    @endforeach

                                </select>

                                <p id="categoryPurchaseAccountError" class="mt-1.5 hidden text-xs text-red-500"></p>

                            </div>


                            <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3">

                                <div class="flex gap-2">

                                    <i class="bx bx-info-circle mt-0.5 text-gray-500"></i>

                                    <p class="text-xs leading-5 text-gray-500">
                                        These accounts determine where inventory and purchase
                                        transactions for this category are recorded.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Modal Footer --}}
                    <div class="flex items-center justify-end gap-2 border-t border-gray-200 px-5 py-4">

                        <button type="button" id="cancelCategoryModal"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white">
                            Cancel
                        </button>

                        <button type="submit" id="saveCategoryBtn"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50">
                            <i class="bx bx-save"></i>

                            <span id="saveCategoryBtnText">
                                Save Category
                            </span>
                        </button>

                    </div>

                </form>


            </div>

        </div>

    </div>


    @push('scripts')
        <script>
            let editingCategory = false;


            /* =====================================================
             * MODAL
             * ===================================================== */

            function openCategoryModal() {
                $('#categoryModal').removeClass('hidden');
                setCategoryTab('details');
                $('#categoryName').trigger('focus');
            }


            function closeCategoryModal() {
                $('#categoryModal').addClass('hidden');
                $('#categoryForm')[0].reset();
                $('#categoryId').val('');
                editingCategory = false;
                $('#categoryModalTitle').text('Add Category');
                $('#saveCategoryBtnText').text('Save Category');
                setCategoryTab('details');
                clearCategoryErrors();
            }


            function clearCategoryErrors() {

                $('#categoryForm input, #categoryForm textarea, #categoryForm select')
                    .removeClass('border-red-500');
                $('#categoryForm p[id$="Error"]')
                    .text('')
                    .addClass('hidden');
            }


            function showCategoryErrors(errors) {

                clearCategoryErrors();
                $.each(errors, function(field, messages) {

                    const fieldName = field
                        .split('.')
                        .map((part, index) => {
                            return index === 0 ?
                                part.charAt(0).toUpperCase() + part.slice(1) :
                                part.charAt(0).toUpperCase() + part.slice(1);
                        })
                        .join('');

                    const input = $('#category' + fieldName);
                    const error = $('#' + 'category' + fieldName + 'Error');

                    input.addClass('border-red-500');

                    error
                        .text(messages[0])
                        .removeClass('hidden');

                    if (
                        field === 'inventory_account_id' ||
                        field === 'purchase_account_id'
                    ) {
                        setCategoryTab('accounts');
                    }
                });
            }


            /* =====================================================
             * TABS
             * ===================================================== */

            function setCategoryTab(tab) {

                $('.category-tab')
                    .removeClass('border-gray-900 text-gray-900')
                    .addClass('border-transparent text-gray-500');

                $('.category-tab[data-tab="' + tab + '"]')
                    .removeClass('border-transparent text-gray-500')
                    .addClass('border-gray-900 text-gray-900');

                $('.category-tab-content').addClass('hidden');

                if (tab === 'details') {
                    $('#categoryDetailsTab').removeClass('hidden');
                }

                if (tab === 'accounts') {
                    $('#categoryAccountsTab').removeClass('hidden');
                }
            }


            $(document).on('click', '.category-tab', function() {
                setCategoryTab($(this).data('tab'));
            });


            /* =====================================================
             * ADD
             * ===================================================== */

            $('#addCategoryBtn, #emptyAddCategoryBtn').on('click', function() {

                $('#categoryForm')[0].reset();

                $('#categoryId').val('');

                editingCategory = false;

                $('#categoryModalTitle').text('Add Category');
                $('#saveCategoryBtnText').text('Save Category');

                clearCategoryErrors();

                openCategoryModal();
            });


            /* =====================================================
             * EDIT
             * ===================================================== */

            $(document).on('click', '.edit-category', function() {

                const button = $(this);

                $('#categoryId').val(button.data('id'));

                $('#categoryName').val(button.data('name'));

                $('#categorySlug').val(
                    button.data('slug') || ''
                );

                $('#categoryDescription').val(
                    button.data('description') || ''
                );

                $('#categoryInventoryAccount').val(
                    button.data('inventory-account-id') || ''
                );

                $('#categoryPurchaseAccount').val(
                    button.data('purchase-account-id') || ''
                );

                editingCategory = true;

                $('#categoryModalTitle').text('Edit Category');
                $('#saveCategoryBtnText').text('Update Category');

                clearCategoryErrors();

                openCategoryModal();
            });


            /* =====================================================
             * CLOSE MODAL
             * ===================================================== */

            $('#closeCategoryModal, #cancelCategoryModal, #categoryModalOverlay')
                .on('click', function() {
                    closeCategoryModal();
                });


            /* =====================================================
             * CREATE / UPDATE
             * ===================================================== */

            $('#categoryForm').on('submit', function(e) {

                e.preventDefault();

                clearCategoryErrors();

                const form = $(this);
                const button = $('#saveCategoryBtn');
                const buttonText = $('#saveCategoryBtnText');

                let url = "{{ route('categories.store') }}";
                let method = 'POST';

                if (editingCategory) {

                    const id = $('#categoryId').val();

                    url = "{{ url('categories') }}/" + id;
                    method = 'PUT';
                }

                button.prop('disabled', true);

                buttonText.text(
                    editingCategory ?
                    'Updating...' :
                    'Saving...'
                );


                $.ajax({

                    url: url,
                    type: method,
                    data: form.serialize(),
                    headers: {
                        Accept: 'application/json'
                    },
                    success: function(response) {
                        showToast(
                            'success',
                            response.message ||
                            (
                                editingCategory ?
                                'Category updated successfully.' :
                                'Category created successfully.'
                            )
                        );

                        closeCategoryModal();
                        setTimeout(function() {
                            window.location.reload();
                        }, 500);
                    },

                    error: function(xhr) {
                        button.prop('disabled', false);
                        buttonText.text(
                            editingCategory ?
                            'Update Category' :
                            'Save Category'
                        );

                        if (xhr.status === 422) {
                            if (xhr.responseJSON?.message) {
                                showToast(
                                    'error',
                                    xhr.responseJSON?.message
                                );
                            }
                            showCategoryErrors(
                                xhr.responseJSON?.errors || {}
                            );
                            return;
                        }

                        showToast(
                            'error',
                            xhr.responseJSON?.message ||
                            'Unable to save category.'
                        );
                    }

                });

            });


            /* =====================================================
             * DELETE
             * ===================================================== */

            $(document).on('click', '.delete-category', function() {

                const button = $(this);
                const url = button.data('url');
                const name = button.data('name');

                if (!confirm(
                        'Are you sure you want to delete "' +
                        name +
                        '"?'
                    )) {
                    return;
                }

                button.prop('disabled', true);

                const originalHtml = button.html();

                button.html(
                    '<i class="bx bx-loader-alt bx-spin"></i> Deleting...'
                );


                $.ajax({

                    url: url,
                    type: 'DELETE',

                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        Accept: 'application/json'
                    },

                    success: function(response) {

                        showToast(
                            'success',
                            response.message ||
                            'Category deleted successfully.'
                        );

                        setTimeout(function() {
                            window.location.reload();
                        }, 500);
                    },

                    error: function(xhr) {

                        button.prop('disabled', false);

                        button.html(originalHtml);

                        showToast(
                            'error',
                            xhr.responseJSON?.message ||
                            'Unable to delete category.'
                        );
                    }

                });

            });
        </script>
    @endpush

</x-layouts.app>
