@php
    $oldItems = old('items');

    if ($oldItems) {
        $formItems = $oldItems;
    } elseif (isset($invoice)) {
        $formItems = $invoice->items->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'description' => $item->description,
                'qty' => $item->qty,
                'unit_id' => $item->unit_id,
                'price' => $item->price,
            ];
        })->toArray();
    } else {
        $formItems = [
            [
                'product_id' => '',
                'description' => '',
                'qty' => 1,
                'unit_id' => '',
                'price' => 0,
            ]
        ];
    }
@endphp


{{-- ========================================================= --}}
{{-- Invoice Information --}}
{{-- ========================================================= --}}

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    {{-- Invoice Number --}}
    <div>
        <label
            for="invoice_number"
            class="mb-1 block text-sm font-medium text-gray-700"
        >
            Invoice Number
        </label>

        <input
            type="number"
            id="invoice_number"
            name="invoice_number"
            value="{{ old('invoice_number', $invoice->invoice_number ?? '') }}"
            required
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
        >

        @error('invoice_number')
            <span class="mt-1 block text-sm text-red-600">
                {{ $message }}
            </span>
        @enderror
    </div>


    {{-- Type --}}
    <div>
        <label
            for="type"
            class="mb-1 block text-sm font-medium text-gray-700"
        >
            Invoice Type
        </label>

        <select
            id="type"
            name="type"
            required
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
        >
            <option
                value="products"
                @selected(old('type', $invoice->type ?? 'products') === 'products')
            >
                Products
            </option>

            <option
                value="raw_material"
                @selected(old('type', $invoice->type ?? '') === 'raw_material')
            >
                Raw Material
            </option>
        </select>

        @error('type')
            <span class="mt-1 block text-sm text-red-600">
                {{ $message }}
            </span>
        @enderror
    </div>


    {{-- Party --}}
    <div>
        <label
            for="party_name"
            class="mb-1 block text-sm font-medium text-gray-700"
        >
            Party Name
        </label>

        <input
            type="text"
            id="party_name"
            name="party_name"
            value="{{ old('party_name', $invoice->party_name ?? '') }}"
            required
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
        >

        @error('party_name')
            <span class="mt-1 block text-sm text-red-600">
                {{ $message }}
            </span>
        @enderror
    </div>


    {{-- Invoice Date --}}
    <div>
        <label
            for="invoice_date"
            class="mb-1 block text-sm font-medium text-gray-700"
        >
            Invoice Date
        </label>

        <input
            type="date"
            id="invoice_date"
            name="invoice_date"
            value="{{ old('invoice_date', isset($invoice) ? $invoice->invoice_date->format('Y-m-d') : date('Y-m-d')) }}"
            required
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
        >

        @error('invoice_date')
            <span class="mt-1 block text-sm text-red-600">
                {{ $message }}
            </span>
        @enderror
    </div>


    {{-- Tax --}}
    <div>
        <label
            for="tax"
            class="mb-1 block text-sm font-medium text-gray-700"
        >
            Tax
        </label>

        <input
            type="number"
            step="0.01"
            min="0"
            id="tax"
            name="tax"
            value="{{ old('tax', $invoice->tax ?? 0) }}"
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
        >

        @error('tax')
            <span class="mt-1 block text-sm text-red-600">
                {{ $message }}
            </span>
        @enderror
    </div>


    {{-- Discount --}}
    <div>
        <label
            for="discount"
            class="mb-1 block text-sm font-medium text-gray-700"
        >
            Discount
        </label>

        <input
            type="number"
            step="0.01"
            min="0"
            id="discount"
            name="discount"
            value="{{ old('discount', $invoice->discount ?? 0) }}"
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
        >

        @error('discount')
            <span class="mt-1 block text-sm text-red-600">
                {{ $message }}
            </span>
        @enderror
    </div>


    {{-- Status --}}
    <div>
        <label
            for="status"
            class="mb-1 block text-sm font-medium text-gray-700"
        >
            Status
        </label>

        <select
            id="status"
            name="status"
            required
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
        >
            <option
                value="pending"
                @selected(old('status', $invoice->status ?? 'pending') === 'pending')
            >
                Pending
            </option>

            <option
                value="paid"
                @selected(old('status', $invoice->status ?? '') === 'paid')
            >
                Paid
            </option>

            <option
                value="unpaid"
                @selected(old('status', $invoice->status ?? '') === 'unpaid')
            >
                Unpaid
            </option>
        </select>

        @error('status')
            <span class="mt-1 block text-sm text-red-600">
                {{ $message }}
            </span>
        @enderror
    </div>


    {{-- Description --}}
    <div class="md:col-span-2">

        <label
            for="description"
            class="mb-1 block text-sm font-medium text-gray-700"
        >
            Description
        </label>

        <textarea
            id="description"
            name="description"
            rows="3"
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
        >{{ old('description', $invoice->description ?? '') }}</textarea>

        @error('description')
            <span class="mt-1 block text-sm text-red-600">
                {{ $message }}
            </span>
        @enderror

    </div>

</div>


{{-- ========================================================= --}}
{{-- Invoice Items --}}
{{-- ========================================================= --}}

<div class="mt-8 border-t pt-6">

    <div class="mb-4 flex items-center justify-between">

        <div>
            <h3 class="text-base font-semibold text-gray-900">
                Invoice Items
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Add the products included in this invoice.
            </p>
        </div>

        <button
            type="button"
            id="add-item"
            class="rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-800"
        >
            + Add Item
        </button>

    </div>


    <div
        id="items-container"
        class="space-y-3"
    >

        @foreach ($formItems as $index => $item)

            <div class="invoice-item rounded-lg border border-gray-200 bg-gray-50 p-4">

                <div class="grid grid-cols-1 gap-4 md:grid-cols-12">

                    {{-- Product --}}
                    <div class="md:col-span-3">

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Product
                        </label>

                        <select
                            name="items[{{ $index }}][product_id]"
                            required
                            class="product-select w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                        >

                            <option value="">
                                Select Product
                            </option>

                            @foreach ($products as $product)

                                <option
                                    value="{{ $product->id }}"
                                    @selected(($item['product_id'] ?? '') == $product->id)
                                >
                                    {{ $product->name }}
                                </option>

                            @endforeach

                        </select>

                        @error("items.$index.product_id")
                            <span class="mt-1 block text-sm text-red-600">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>


                    {{-- Description --}}
                    <div class="md:col-span-3">

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Description
                        </label>

                        <input
                            type="text"
                            name="items[{{ $index }}][description]"
                            value="{{ $item['description'] ?? '' }}"
                            class="w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                        >

                    </div>


                    {{-- Quantity --}}
                    <div class="md:col-span-2">

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Quantity
                        </label>

                        <input
                            type="number"
                            name="items[{{ $index }}][qty]"
                            value="{{ $item['qty'] ?? 1 }}"
                            min="1"
                            required
                            class="item-qty w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                        >

                    </div>


                    {{-- Unit --}}
                    <div class="md:col-span-2">

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Unit
                        </label>

                        <select
                            name="items[{{ $index }}][unit_id]"
                            required
                            class="w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                        >

                            <option value="">
                                Select Unit
                            </option>

                            @foreach ($units as $unit)

                                <option
                                    value="{{ $unit->id }}"
                                    @selected(($item['unit_id'] ?? '') == $unit->id)
                                >
                                    {{ $unit->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Price --}}
                    <div class="md:col-span-2">

                        <label class="mb-1 block text-sm font-medium text-gray-700">
                            Price
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="items[{{ $index }}][price]"
                            value="{{ $item['price'] ?? 0 }}"
                            required
                            class="item-price w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                        >

                    </div>


                    {{-- Remove --}}
                    <div class="md:col-span-12">

                        <button
                            type="button"
                            class="remove-item text-sm font-medium text-red-600 hover:text-red-700"
                        >
                            Remove Item
                        </button>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>


{{-- ========================================================= --}}
{{-- Summary --}}
{{-- ========================================================= --}}

<div class="mt-6 flex justify-end">

    <div class="w-full max-w-sm rounded-lg bg-gray-50 p-5">

        <div class="flex justify-between py-2 text-sm">

            <span class="text-gray-500">
                Subtotal
            </span>

            <span
                id="subtotal-display"
                class="font-medium text-gray-900"
            >
                0.00
            </span>

        </div>


        <div class="flex justify-between py-2 text-sm">

            <span class="text-gray-500">
                Tax
            </span>

            <span
                id="tax-display"
                class="font-medium text-gray-900"
            >
                0.00
            </span>

        </div>


        <div class="flex justify-between py-2 text-sm">

            <span class="text-gray-500">
                Discount
            </span>

            <span
                id="discount-display"
                class="font-medium text-gray-900"
            >
                0.00
            </span>

        </div>


        <div class="mt-2 flex justify-between border-t pt-3">

            <span class="font-semibold text-gray-900">
                Total
            </span>

            <span
                id="total-display"
                class="font-semibold text-gray-900"
            >
                0.00
            </span>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- Dynamic Item Template --}}
{{-- ========================================================= --}}

<template id="item-template">

    <div class="invoice-item rounded-lg border border-gray-200 bg-gray-50 p-4">

        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">

            {{-- Product --}}
            <div class="md:col-span-3">

                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Product
                </label>

                <select
                    name="items[INDEX][product_id]"
                    required
                    class="product-select w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                >

                    <option value="">
                        Select Product
                    </option>

                    @foreach ($products as $product)

                        <option value="{{ $product->id }}">
                            {{ $product->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Description --}}
            <div class="md:col-span-3">

                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Description
                </label>

                <input
                    type="text"
                    name="items[INDEX][description]"
                    class="w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                >

            </div>


            {{-- Quantity --}}
            <div class="md:col-span-2">

                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Quantity
                </label>

                <input
                    type="number"
                    name="items[INDEX][qty]"
                    value="1"
                    min="1"
                    required
                    class="item-qty w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                >

            </div>


            {{-- Unit --}}
            <div class="md:col-span-2">

                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Unit
                </label>

                <select
                    name="items[INDEX][unit_id]"
                    required
                    class="w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                >

                    <option value="">
                        Select Unit
                    </option>

                    @foreach ($units as $unit)

                        <option value="{{ $unit->id }}">
                            {{ $unit->name }}
                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Price --}}
            <div class="md:col-span-2">

                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Price
                </label>

                <input
                    type="number"
                    step="0.01"
                    min="0"
                    name="items[INDEX][price]"
                    value="0"
                    required
                    class="item-price w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500"
                >

            </div>


            {{-- Remove --}}
            <div class="md:col-span-12">

                <button
                    type="button"
                    class="remove-item text-sm font-medium text-red-600 hover:text-red-700"
                >
                    Remove Item
                </button>

            </div>

        </div>

    </div>

</template>


{{-- ========================================================= --}}
{{-- jQuery --}}
{{-- ========================================================= --}}

@push('scripts')

<script>

$(document).ready(function () {

    let index = {{ count($formItems) }};

    const $container = $('#items-container');

    const template = $('#item-template').html();


    /*
    |--------------------------------------------------------------------------
    | Get Product From Backend
    |--------------------------------------------------------------------------
    */

    function getProduct(productId, $item) {

        if (!productId) {

            $item.find('.item-price').val('');

            calculateTotals();

            return;
        }


        $.ajax({

            url: "{{ route('products.show', ':id') }}"
                .replace(':id', productId),

            type: 'GET',

            success: function (product) {

                /*
                 * Populate price
                 */
                $item.find('.item-price').val(
                    product.sale_price ?? 0
                );


                /*
                 * Populate description if empty
                 */
                if (!$item.find('[name$="[description]"]').val()) {

                    $item.find('[name$="[description]"]').val(
                        product.description ?? ''
                    );
                }


                /*
                 * Populate unit
                 */
                if (product.unit_id) {

                    $item.find(
                        'select[name$="[unit_id]"]'
                    ).val(product.unit_id);
                }


                /*
                 * Recalculate invoice
                 */
                calculateTotals();
            },

            error: function (xhr) {

                console.error(xhr);

                alert('Unable to fetch product information.');

                $item.find('.item-price').val('');

                calculateTotals();
            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Product Changed
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Delegated event so it also works for dynamically added items.
    |
    */

    $container.on(
        'change',
        '.product-select',
        function () {

            const productId = $(this).val();

            const $item = $(this).closest('.invoice-item');

            getProduct(
                productId,
                $item
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Calculate Totals
    |--------------------------------------------------------------------------
    */

    function calculateTotals() {

        let subtotal = 0;


        $container.find('.invoice-item').each(function () {

            const qty = parseFloat(
                $(this).find('.item-qty').val()
            ) || 0;


            const price = parseFloat(
                $(this).find('.item-price').val()
            ) || 0;


            subtotal += qty * price;

        });


        const tax = parseFloat(
            $('#tax').val()
        ) || 0;


        const discount = parseFloat(
            $('#discount').val()
        ) || 0;


        const total = subtotal + tax - discount;


        $('#subtotal-display').text(
            subtotal.toFixed(2)
        );


        $('#tax-display').text(
            tax.toFixed(2)
        );


        $('#discount-display').text(
            discount.toFixed(2)
        );


        $('#total-display').text(
            total.toFixed(2)
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Add Item
    |--------------------------------------------------------------------------
    */

    $('#add-item').on('click', function () {

        const html = template.replaceAll(
            'INDEX',
            index
        );


        $container.append(html);


        index++;


        calculateTotals();

    });


    /*
    |--------------------------------------------------------------------------
    | Remove Item
    |--------------------------------------------------------------------------
    */

    $container.on(
        'click',
        '.remove-item',
        function () {

            const itemCount = $container
                .find('.invoice-item')
                .length;


            if (itemCount <= 1) {

                alert(
                    'At least one invoice item is required.'
                );

                return;
            }


            $(this)
                .closest('.invoice-item')
                .remove();


            calculateTotals();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Quantity / Price Changed
    |--------------------------------------------------------------------------
    */

    $container.on(
        'input',
        '.item-qty, .item-price',
        function () {

            calculateTotals();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Tax / Discount Changed
    |--------------------------------------------------------------------------
    */

    $('#tax, #discount').on(
        'input',
        function () {

            calculateTotals();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Initial Calculation
    |--------------------------------------------------------------------------
    */

    calculateTotals();

});

</script>

@endpush