<x-layouts.app title="Vendor Bills">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">
            Vendor Bills
        </h1>

        <p class="mt-1 text-sm text-gray-500">
            View and manage vendor bills generated from purchase orders.
        </p>
    </div>

    {{-- Vendor Bills List --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        {{-- Card Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">

            <div>
                <h3 class="font-semibold text-gray-900">
                    Vendor Bill List
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    All generated vendor bills.
                </p>
            </div>

            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                {{ $vendorBills->total() }} Bills
            </span>

        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[1000px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">

                    <tr class="text-xs font-medium uppercase tracking-wide text-gray-500">

                        <th class="px-6 py-3.5">
                            Bill Number
                        </th>

                        <th class="px-6 py-3.5">
                            Vendor
                        </th>

                        <th class="px-6 py-3.5">
                            PO Number
                        </th>

                        <th class="px-6 py-3.5">
                            Bill Date
                        </th>

                        <th class="px-6 py-3.5">
                            Due Date
                        </th>

                        <th class="px-6 py-3.5 text-right">
                            Total
                        </th>

                        <th class="px-6 py-3.5">
                            Status
                        </th>

                        <th class="px-6 py-3.5 text-right">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($vendorBills as $vendorBill)

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

                        <tr class="transition hover:bg-gray-50">

                            {{-- Bill Number --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600"
                                    >
                                        <i class="bx bx-receipt text-lg"></i>
                                    </div>

                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $vendorBill->bill_number }}
                                        </p>
                                    </div>

                                </div>

                            </td>

                            {{-- Vendor --}}
                            <td class="px-6 py-4">

                                <p class="font-medium text-gray-900">
                                    {{ $vendorBill->vendor->company_name ?? $vendorBill->vendor->name ?? '-' }}
                                </p>

                            </td>

                            {{-- PO Number --}}
                            <td class="px-6 py-4">

                                <p class="font-medium text-gray-900">
                                    {{ $vendorBill->purchaseOrder->order_number ?? '-' }}
                                </p>

                            </td>

                            {{-- Bill Date --}}
                            <td class="px-6 py-4 text-gray-600">

                                {{ $vendorBill->bill_date
                                    ? \Carbon\Carbon::parse($vendorBill->bill_date)->format('d M Y')
                                    : '-' }}

                            </td>

                            {{-- Due Date --}}
                            <td class="px-6 py-4 text-gray-600">

                                {{ $vendorBill->due_date
                                    ? \Carbon\Carbon::parse($vendorBill->due_date)->format('d M Y')
                                    : '-' }}

                            </td>

                            {{-- Total --}}
                            <td class="px-6 py-4 text-right">

                                <span class="font-semibold text-gray-900">
                                    {{ number_format($vendorBill->total, 2) }}
                                </span>

                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">

                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}"
                                >
                                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>

                                    {{ $statusLabel }}
                                </span>

                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    <a
                                        href="{{ route('vendor-bills.show', $vendorBill) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white"
                                    >
                                        <i class="bx bx-show"></i>
                                        View
                                    </a>

                                    <a
                                        href="{{ route('vendor-bills.generatepdf', $vendorBill) }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white"
                                    >
                                        <i class="bx bx-file"></i>
                                        PDF
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="px-6 py-12">

                                <div class="flex flex-col items-center justify-center text-center">

                                    <div
                                        class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400"
                                    >
                                        <i class="bx bx-receipt text-2xl"></i>
                                    </div>

                                    <h3 class="font-medium text-gray-900">
                                        No vendor bills found
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Vendor bills will appear here after they are generated from received purchase orders.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if ($vendorBills->hasPages())

            <div class="border-t border-gray-200 px-6 py-4">
                {{ $vendorBills->links() }}
            </div>

        @endif

    </div>

</x-layouts.app>