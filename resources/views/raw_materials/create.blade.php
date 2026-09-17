<x-layouts.app title="Create Raw Material">

    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900">
            Create Raw Material
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Add a new raw material to your inventory.
        </p>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        <form id="createRawMaterialForm" method="POST" action="{{ route('raw-materials.store') }}">
            @csrf

            <div class="p-6">
                @include('raw_materials._form')
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-200 bg-gray-50 px-6 py-4">

                <a href="{{ route('raw-materials.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                    Cancel
                </a>

                <button type="submit" id="createRawMaterialBtn"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-60">
                    <i class="bx bx-plus text-lg"></i>
                    <span>Create Raw Material</span>
                </button>

            </div>

        </form>

    </div>


    {{-- Category modal --}}
    <div id="categoryModal"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/40 px-4">
        <div class="w-full max-w-md rounded-xl bg-white shadow-xl">

            <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        Add Category
                    </h3>

                    <p class="mt-0.5 text-sm text-gray-500">
                        Create a category for your products.
                    </p>
                </div>

                <button
                    type="button"
                    id="closeCategoryModal"
                    class="inline-flex h-8 w-8 cursor-pointer items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-900"
                >
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <form
                id="categoryForm"
                action="{{ route('categories.store') }}"
                method="POST"
            >
                @csrf

                <div class="space-y-5 p-5">

                    {{-- Name --}}
                    <div>
                        <label
                            for="category_name"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Category Name<sup>*</sup>
                        </label>

                        <input
                            type="text"
                            id="category_name"
                            name="name"
                            placeholder="Enter category name"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
                        >

                        <span
                            id="categoryNameErr"
                            class="mt-1.5 block text-xs text-red-600"
                        ></span>
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label
                            for="category_slug"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Slug
                        </label>

                        <input
                            type="text"
                            id="category_slug"
                            name="slug"
                            placeholder="Enter category slug"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
                        >

                        <span
                            id="categorySlugErr"
                            class="mt-1.5 block text-xs text-red-600"
                        ></span>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label
                            for="category_description"
                            class="mb-1.5 block text-sm font-medium text-gray-700"
                        >
                            Description
                        </label>

                        <textarea
                            id="category_description"
                            name="description"
                            rows="3"
                            placeholder="Enter category description"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
                        ></textarea>

                        <span
                            id="categoryDescriptionErr"
                            class="mt-1.5 block text-xs text-red-600"
                        ></span>
                    </div>

                </div>

                <div class="flex justify-end gap-3 border-t border-gray-200 bg-gray-50 px-5 py-4">

                    <button
                        type="button"
                        id="cancelCategoryModal"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        id="createCategoryBtn"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <i class="bx bx-plus text-lg"></i>
                        <span id="createCategoryBtnText">Create Category</span>
                    </button>

                </div>

            </form>

        </div>
    </div>

    @push('scripts')
        <script>

        // Cost price: positive whole numbers only (including 0)
        $('#cost_price').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Stock & minimum stock: positive decimal numbers only
        $('#stock, #minimum_stock').on('input', function() {

            // Remove letters, negative sign and other symbols
            this.value = this.value.replace(/[^0-9.]/g, '');

            // Allow only one decimal point
            const parts = this.value.split('.');

            if (parts.length > 2) {
                this.value = parts[0] + '.' + parts.slice(1).join('');
            }

        });




    function clearCategoryErrors() {
        $('#categoryNameErr, #categorySlugErr, #categoryDescriptionErr').text('');
    }

    function showCategoryErrors(errors) {
        $('#categoryNameErr').text(errors.name?.[0] || '');
        $('#categorySlugErr').text(errors.slug?.[0] || '');
        $('#categoryDescriptionErr').text(errors.description?.[0] || '');
    }

    function openCategoryModal() {
        clearCategoryErrors();

        $('#categoryForm')[0].reset();
        $('#categoryModal').removeClass('hidden');
        $('#category_name').trigger('focus');
    }

    function closeCategoryModal() {
        $('#categoryModal').addClass('hidden');
        $('#categoryForm')[0].reset();
        clearCategoryErrors();
    }

    function createCategory() {
        const form = $('#categoryForm');
        const button = $('#createCategoryBtn');
        const buttonText = $('#createCategoryBtnText');

        button.prop('disabled', true);
        buttonText.text('Creating...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            headers: {
                'Accept': 'application/json'
            },

            success: function (response) {
                const category = response.category;

                $('#category_id').append(
                    $('<option>', {
                        value: category.id,
                        text: category.name
                    })
                );

                $('#category_id').val(category.id);

                closeCategoryModal();

                showToast(
                    'success',
                    response.message || 'Category created successfully.'
                );
            },

            error: function (xhr) {
                if (xhr.status === 422) {
                    showCategoryErrors(xhr.responseJSON.errors || {});
                    return;
                }

                showToast(
                    'error',
                    xhr.responseJSON?.message || 'Unable to create category.'
                );
            },

            complete: function () {
                button.prop('disabled', false);
                buttonText.text('Create Category');
            }
        });
    }

    $('#openCategoryModal').on('click', function () {
        openCategoryModal();
    });

    $('#closeCategoryModal, #cancelCategoryModal').on('click', function () {
        closeCategoryModal();
    });

    $('#categoryForm').on('submit', function (e) {
        e.preventDefault();

        clearCategoryErrors();
        createCategory();
    });

    function clearRawMaterialErrors() {
        $(
            "#nameErr, #skuErr, #unitIdErr, #costPriceErr, #stockErr, #minimumStockErr, #descriptionErr"
        ).text("");
    }


    function showRawMaterialErrors(errors) {
        $("#nameErr").text(errors.name || "");
        $("#skuErr").text(errors.sku || "");
        $("#unitIdErr").text(errors.unit_id || "");
        $("#costPriceErr").text(errors.cost_price || "");
        $("#stockErr").text(errors.stock || "");
        $("#minimumStockErr").text(errors.minimum_stock || "");
        $("#descriptionErr").text(errors.description || "");
    }


    function createRawMaterial() {

        const form = $('#createRawMaterialForm');
        const button = $('#createRawMaterialBtn');

        button.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),

            type: 'POST',

            data: form.serialize(),

            headers: {
                'Accept': 'application/json'
            },

            success: function(response) {

                showToast('success', response.message);

                setTimeout(function() {
                    window.location.href = "{{ route('raw-materials.index') }}";
                }, 800);

            },

            error: function(xhr) {

                if (xhr.status === 422) {
                    showRawMaterialErrors(xhr.responseJSON.errors);
                    return;
                }

                showToast(
                    'error',
                    xhr.responseJSON?.message || 'Unable to create raw material.'
                );

            },

            complete: function() {
                button.prop('disabled', false);
            }

        });

    }


    $('#createRawMaterialForm').on('submit', function(e) {

        e.preventDefault();

        clearRawMaterialErrors();

        createRawMaterial();

    });
        </script>
    @endpush

</x-layouts.app>
