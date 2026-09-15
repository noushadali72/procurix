
<x-layouts.app title="Purchase Order">

    <div class="mb-6 flex items-center justify-between">

        <div>

            <a href="{{ route('purchase-orders.index') }}"
                class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900">

                <i class="bx bx-arrow-back"></i>

                Back to Orders

            </a>

            <h2 class="mt-3 text-xl font-semibold text-gray-900">
                Purchase Order #{{ $purchaseOrder->order_number }}
            </h2>

        </div>

        @if(in_array($purchaseOrder->status, ['placed', 'partially_received']))

            <a href="{{ route('goods-receipts.create', $purchaseOrder) }}"
                class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-700">

                <i class="bx bx-package"></i>

                Receive Materials

            </a>

        @endif

    </div>

    @if(session('success'))

        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>

    @endif

    @if($errors->any())

        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

            <ul class="list-inside list-disc text-sm text-red-700">

                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

    @endif


    {{-- Order Information --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b px-6 py-4">

            <h3 class="font-semibold text-gray-900">
                Order Information
            </h3>

        </div>

        <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-3">

            <div>

                <p class="text-sm text-gray-500">
                    Order Number
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ $purchaseOrder->order_number }}
                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Vendor
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ $purchaseOrder->vendor->company_name ?: $purchaseOrder->vendor->name }}
                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Purchase Request
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    PR-{{ $purchaseOrder->quotation->purchaseRequest->request_number }}
                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Order Date
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ $purchaseOrder->order_date->format('d M Y') }}
                </p>

            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Status
                </p>

                <div class="mt-1">

                    @if($purchaseOrder->status === 'received')

                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                            Received
                        </span>

                    @elseif($purchaseOrder->status === 'partially_received')

                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                            Partially Received
                        </span>

                    @elseif($purchaseOrder->status === 'cancelled')

                        <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                            Cancelled
                        </span>

                    @else

                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                            Placed
                        </span>

                    @endif

                </div>

            </div>

            <div>

                <p class="text-sm text-gray-500">
                    Received Date
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ $purchaseOrder->received_date?->format('d M Y') ?? '-' }}
                </p>

            </div>

            @if($purchaseOrder->notes)

                <div class="md:col-span-3">

                    <p class="text-sm text-gray-500">
                        Notes
                    </p>

                    <p class="mt-1 whitespace-pre-line text-gray-900">
                        {{ $purchaseOrder->notes }}
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- Order Items --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b px-6 py-4">

            <h3 class="font-semibold text-gray-900">
                Order Items
            </h3>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="border-b bg-gray-50 text-xs uppercase text-gray-500">

                    <tr>

                        <th class="px-6 py-4">
                            Raw Material
                        </th>

                        <th class="px-6 py-4">
                            Ordered
                        </th>

                        <th class="px-6 py-4">
                            Unit
                        </th>

                        <th class="px-6 py-4">
                            Price
                        </th>

                        <th class="px-6 py-4 text-right">
                            Total
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y">

                    @foreach($purchaseOrder->items as $item)

                        <tr>

                            <td class="px-6 py-4">

                                <p class="font-medium text-gray-900">
                                    {{ $item->rawMaterial->name }}
                                </p>

                                @if($item->rawMaterial->sku)

                                    <p class="text-xs text-gray-500">
                                        {{ $item->rawMaterial->sku }}
                                    </p>

                                @endif

                            </td>

                            <td class="px-6 py-4">
                                {{ $item->qty }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $item->unit->short_name ?? $item->unit->name }}
                            </td>

                            <td class="px-6 py-4">
                                {{ number_format($item->price, 2) }}
                            </td>

                            <td class="px-6 py-4 text-right font-semibold">
                                {{ number_format($item->total, 2) }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

                <tfoot class="border-t bg-gray-50">

                    <tr>

                        <td colspan="4"
                            class="px-6 py-4 text-right font-semibold">

                            Grand Total

                        </td>

                        <td class="px-6 py-4 text-right text-lg font-bold">

                            {{ number_format($purchaseOrder->items->sum('total'), 2) }}

                        </td>

                    </tr>

                </tfoot>

            </table>

        </div>

    </div>


    {{-- Goods Receipts --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="flex items-center justify-between border-b px-6 py-4">

            <div>

                <h3 class="font-semibold text-gray-900">
                    Goods Receipts
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Receiving records for this purchase order.
                </p>

            </div>

            @if(in_array($purchaseOrder->status, ['placed', 'partially_received']))

                <a href="{{ route('goods-receipts.create', $purchaseOrder) }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">

                    <i class="bx bx-plus"></i>

                    New Receipt

                </a>

            @endif

        </div>

        <div class="overflow-x-auto">

            @if($purchaseOrder->goodsReceipts->count())

                <table class="w-full text-left text-sm">

                    <thead class="border-b bg-gray-50 text-xs uppercase text-gray-500">

                        <tr>

                            <th class="px-6 py-4">
                                GRN Number
                            </th>

                            <th class="px-6 py-4">
                                Received Date
                            </th>

                            <th class="px-6 py-4">
                                Items
                            </th>

                            <th class="px-6 py-4">
                                Notes
                            </th>

                            <th class="px-6 py-4 text-right">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y">

                        @foreach($purchaseOrder->goodsReceipts as $receipt)

                            <tr>

                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    {{ $receipt->grn_number }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $receipt->received_date->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $receipt->items->count() }}
                                </td>

                                <td class="px-6 py-4 text-gray-600">
                                    {{ $receipt->notes ?: '-' }}
                                </td>

                                <td class="px-6 py-4 text-right">

                                    <a href="{{ route('goods-receipts.show', $receipt) }}"
                                        class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">

                                        <i class="bx bx-show"></i>

                                        View

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="py-12 text-center">

                    <i class="bx bx-package text-4xl text-gray-300"></i>

                    <p class="mt-3 text-sm font-medium text-gray-700">
                        No goods receipts yet.
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Create a goods receipt when materials arrive.
                    </p>

                </div>

            @endif

        </div>

    </div>

</x-layouts.app>

