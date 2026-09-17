<x-layouts.app title="Vendor Bill">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <a
                href="{{ route('vendor-bills.index') }}"
                class="inline-flex items-center text-sm font-medium text-gray-500 transition hover:text-gray-900"
            >
                <i class="bx bx-arrow-back mr-1.5"></i>
                Back to Vendor Bills
            </a>

            <div class="mt-3">

                <div class="flex flex-wrap items-center gap-2">

                    <h1 class="text-xl font-semibold text-gray-900">
                        Vendor Bill #{{ $vendorBill->bill_number }}
                    </h1>

                    @php
                        $statusClass = match ($vendorBill->status) {
                            'paid' => 'bg-green-50 text-green-700',
                            'partially_paid' => 'bg-amber-50 text-amber-700',
                            'overdue' => 'bg-red-50 text-red-700',
                            'unpaid' => 'bg-yellow-50 text-yellow-700',
                            default => 'bg-gray-100 text-gray-700',
                        };

                        $statusDot = match ($vendorBill->status) {
                            'paid' => 'bg-green-500',
                            'partially_paid' => 'bg-amber-500',
                            'overdue' => 'bg-red-500',
                            'unpaid' => 'bg-yellow-500',
                            default => 'bg-gray-400',
                        };

                        $statusLabel = match ($vendorBill->status) {
                            'partially_paid' => 'Partially Paid',
                            default => ucfirst(str_replace('_', ' ', $vendorBill->status)),
                        };
                    @endphp

                    <span
                        class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}"
                    >
                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                        {{ $statusLabel }}
                    </span>

                </div>

                <p class="mt-1 text-sm text-gray-500">
                    View vendor bill details, items, and payment information.
                </p>

            </div>
        </div>

        {{-- Header Actions --}}
        <div class="flex items-center gap-2">

            <a
                href="{{ route('vendor-bills.generatepdf', $vendorBill) }}"
                target="_blank"
                class="inline-flex items-center gap-1.5 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800"
            >
                <i class="bx bx-file"></i>
                Generate PDF
            </a>

        </div>

    </div>


    {{-- Bill Information --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-4">

            <h3 class="font-semibold text-gray-900">
                Bill Information
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Vendor bill and purchase order information.
            </p>

        </div>

        <div class="grid grid-cols-1 gap-x-8 gap-y-6 p-6 sm:grid-cols-2 lg:grid-cols-3">

            {{-- Bill Number --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Bill Number
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->bill_number }}
                </p>
            </div>

            {{-- Vendor --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Vendor
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->vendor->company_name ?? $vendorBill->vendor->name ?? '-' }}
                </p>
            </div>

            {{-- Purchase Order --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Purchase Order
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->purchaseOrder->order_number ?? '-' }}
                </p>
            </div>

            {{-- Bill Date --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Bill Date
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->bill_date
                        ? \Carbon\Carbon::parse($vendorBill->bill_date)->format('d M Y')
                        : '-' }}
                </p>
            </div>

            {{-- Due Date --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Due Date
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->due_date
                        ? \Carbon\Carbon::parse($vendorBill->due_date)->format('d M Y')
                        : '-' }}
                </p>
            </div>

            {{-- Status --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Status
                </p>

                <div class="mt-1.5">
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}"
                    >
                        <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>

        </div>

    </div>


    {{-- Vendor Information --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-4">

            <h3 class="font-semibold text-gray-900">
                Vendor Information
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Vendor details associated with this bill.
            </p>

        </div>

        <div class="grid grid-cols-1 gap-x-8 gap-y-6 p-6 sm:grid-cols-2 lg:grid-cols-3">

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Vendor
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->vendor->company_name ?? $vendorBill->vendor->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Contact
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->vendor->phone ?? $vendorBill->vendor->contact_no ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Email
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $vendorBill->vendor->email ?? '-' }}
                </p>
            </div>

        </div>

    </div>


    {{-- Bill Items --}}
    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">

            <div>
                <h3 class="font-semibold text-gray-900">
                    Bill Items
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Items included in this vendor bill.
                </p>
            </div>

            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                {{ $vendorBill->items->count() }} Items
            </span>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[750px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">

                    <tr class="text-xs font-medium uppercase tracking-wide text-gray-500">

                        <th class="px-6 py-3.5">
                            Item
                        </th>

                        <th class="px-6 py-3.5">
                            Unit
                        </th>

                        <th class="px-6 py-3.5 text-right">
                            Quantity
                        </th>

                        <th class="px-6 py-3.5 text-right">
                            Unit Price
                        </th>

                        <th class="px-6 py-3.5 text-right">
                            Total
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($vendorBill->items as $item)

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-6 py-4">

                                <p class="font-medium text-gray-900">
                                    {{ $item->product->name
                                        ?? $item->rawMaterial->name
                                        ?? '-' }}
                                </p>

                                @if ($item->rawMaterial?->sku)
                                    <p class="mt-0.5 text-xs text-gray-500">
                                        {{ $item->rawMaterial->sku }}
                                    </p>
                                @endif

                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $item->unit->short_name ?? $item->unit->name ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-right font-medium text-gray-900">
                                {{ $item->qty }}
                            </td>

                            <td class="px-6 py-4 text-right text-gray-700">
                                {{ number_format($item->unit_price, 2) }}
                            </td>

                            <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                {{ number_format($item->line_total, 2) }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                                No bill items found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Financial Summary --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-4">

            <h3 class="font-semibold text-gray-900">
                Financial Summary
            </h3>

        </div>

        <div class="p-6">

            <div class="ml-auto max-w-sm space-y-3">

                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">
                        Subtotal
                    </span>

                    <span class="font-medium text-gray-900">
                        {{ number_format($vendorBill->subtotal, 2) }}
                    </span>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">
                        Tax
                    </span>

                    <span class="font-medium text-gray-900">
                        {{ number_format($vendorBill->tax, 2) }}
                    </span>
                </div>

                @if (isset($vendorBill->discount))
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">
                            Discount
                        </span>

                        <span class="font-medium text-gray-900">
                            {{ number_format($vendorBill->discount, 2) }}
                        </span>
                    </div>
                @endif

                <div class="border-t border-gray-200 pt-3">

                    <div class="flex items-center justify-between">

                        <span class="font-semibold text-gray-700">
                            Total
                        </span>

                        <span class="text-lg font-bold text-gray-900">
                            {{ number_format($vendorBill->total, 2) }}
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Notes --}}
    @if ($vendorBill->notes)

        <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-4">

                <h3 class="font-semibold text-gray-900">
                    Notes
                </h3>

            </div>

            <div class="p-6">

                <p class="whitespace-pre-line text-sm leading-6 text-gray-700">
                    {{ $vendorBill->notes }}
                </p>

            </div>

        </div>

    @endif

</x-layouts.app>