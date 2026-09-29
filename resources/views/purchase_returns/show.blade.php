<x-layouts.app title="Purchase Return">

    @php
        $purchaseRequest = $purchaseReturn->goodsReceipt->purchaseOrder->purchaseRequest;

        $activities = $purchaseRequest?->activities ?? collect();
    @endphp

    <div class="max-w-7xl">

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <div class="lg:col-span-2">
                    {{-- Header --}}
                    <div
                        class="mb-5 flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-end sm:justify-between">

                        <div>
                            <div class="flex items-center gap-3">

                                <h1 class="text-xl font-semibold text-slate-900">
                                    {{ $purchaseReturn->return_number }}
                                </h1>

                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                    {{ ucfirst($purchaseReturn->status) }}
                                </span>

                            </div>

                            <p class="mt-1 text-sm text-slate-500">
                                Purchase return details and financial impact.
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2">

                            <a href="{{ route('goods-receipts.show', $purchaseReturn->goodsReceipt) }}"
                                class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">

                                <i class="bx bx-package"></i>
                                Goods Receipt

                            </a>

                            @if ($purchaseReturn->vendorCredit)
                                <a href="{{ route('vendor-credits.show', $purchaseReturn->vendorCredit) }}"
                                    class="inline-flex items-center gap-2 rounded-md bg-slate-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-900">

                                    <i class="bx bx-credit-card"></i>
                                    View Vendor Credit

                                </a>
                            @endif

                        </div>

                    </div>


                    {{-- Financial Impact --}}
                    <section class="mb-5 overflow-hidden rounded-lg border border-emerald-200 bg-white">

                        <div class="border-b border-emerald-100 bg-emerald-50 px-5 py-4">

                            <div class="flex items-center gap-3">

                                <div class="flex h-9 w-9 items-center justify-center rounded-md bg-emerald-100">
                                    <i class="bx bx-money-withdraw text-xl text-emerald-700"></i>
                                </div>

                                <div>
                                    <h2 class="text-sm font-semibold text-emerald-900">
                                        Financial Impact
                                    </h2>

                                    <p class="mt-0.5 text-xs text-emerald-700">
                                        Financial effect of this purchase return.
                                    </p>
                                </div>

                            </div>

                        </div>


                        <div class="grid grid-cols-1 divide-y divide-slate-100 sm:grid-cols-3 sm:divide-x sm:divide-y-0">

                            {{-- Return Value --}}
                            <div class="px-5 py-5">

                                <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                    Return Value
                                </p>

                                <p class="mt-1 text-2xl font-bold text-slate-900">
                                    {{ number_format($purchaseReturn->items->sum('line_total'), 2) }}
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    Value of goods returned to vendor
                                </p>

                            </div>


                            {{-- Vendor Credit --}}
                            <div class="px-5 py-5">

                                @if ($purchaseReturn->vendorCredit)
                                    <div class="flex items-start justify-between gap-3">

                                        <div>

                                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                                Vendor Credit
                                            </p>

                                            <p class="mt-1 text-2xl font-bold text-emerald-700">
                                                {{ number_format($purchaseReturn->vendorCredit->amount, 2) }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-500">
                                                Credit issued by vendor
                                            </p>

                                        </div>

                                        <span
                                            class="rounded-full bg-emerald-50 px-2 py-1 text-[11px] font-semibold text-emerald-700">
                                            Created
                                        </span>

                                    </div>
                                @else
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Vendor Credit
                                    </p>

                                    <p class="mt-1 text-2xl font-bold text-slate-400">
                                        —
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        No credit created
                                    </p>
                                @endif

                            </div>


                            {{-- Financial Action --}}
                            <div class="px-5 py-5">

                                @if ($purchaseReturn->vendorCredit)
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Financial Action
                                    </p>

                                    <p class="mt-1 text-base font-semibold text-slate-900">
                                        Bill adjusted by credit
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        The vendor credit reduces the amount payable to the vendor.
                                    </p>
                                @else
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                                        Financial Action
                                    </p>

                                    <p class="mt-1 text-base font-semibold text-slate-900">
                                        Adjusted on billing
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        This return occurred before vendor bill generation.
                                    </p>
                                @endif

                            </div>

                        </div>


                        {{-- Impact Explanation --}}
                        @if ($purchaseReturn->vendorCredit)
                            <div class="border-t border-emerald-100 bg-emerald-50/50 px-5 py-4">

                                <div class="flex items-start gap-3">

                                    <i class="bx bx-info-circle mt-0.5 text-lg text-emerald-700"></i>

                                    <div>

                                        <p class="text-sm font-semibold text-emerald-900">
                                            Vendor credit {{ $purchaseReturn->vendorCredit->credit_number }} created
                                        </p>

                                        <p class="mt-1 text-sm text-emerald-800">
                                            A credit of
                                            <strong>{{ number_format($purchaseReturn->vendorCredit->amount, 2) }}</strong>
                                            was created for the returned goods and can be applied against the vendor bill.
                                        </p>

                                    </div>

                                </div>

                            </div>
                        @else
                            <div class="border-t border-slate-100 bg-slate-50 px-5 py-4">

                                <div class="flex items-start gap-3">

                                    <i class="bx bx-info-circle mt-0.5 text-lg text-slate-500"></i>

                                    <div>

                                        <p class="text-sm font-semibold text-slate-800">
                                            No vendor credit was created
                                        </p>

                                        <p class="mt-1 text-sm text-slate-600">
                                            This return occurred before vendor bill generation.
                                            The returned amount will be excluded when the vendor bill is generated.
                                        </p>

                                    </div>

                                </div>

                            </div>
                        @endif

                    </section>


                    {{-- Return Information --}}
                    <section class="mb-5 overflow-hidden rounded-lg border border-slate-200 bg-white">

                        <div class="border-b border-slate-200 px-5 py-4">

                            <h2 class="text-sm font-semibold text-slate-900">
                                Return Information
                            </h2>

                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">

                            <div class="border-b border-slate-100 px-5 py-4 lg:border-b-0 lg:border-r">

                                <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                                    Return Number
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ $purchaseReturn->return_number }}
                                </p>

                            </div>


                            <div class="border-b border-slate-100 px-5 py-4 lg:border-b-0 lg:border-r">

                                <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                                    Goods Receipt
                                </p>

                                <a href="{{ route('goods-receipts.show', $purchaseReturn->goodsReceipt) }}"
                                    class="mt-1 inline-flex items-center gap-1 text-sm font-semibold text-slate-900 hover:underline">

                                    {{ $purchaseReturn->goodsReceipt->grn_number }}

                                    <i class="bx bx-link text-xs text-slate-400"></i>

                                </a>

                            </div>


                            <div class="border-b border-slate-100 px-5 py-4 sm:border-b-0 lg:border-r">

                                <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                                    Vendor
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ $purchaseReturn->goodsReceipt->purchaseOrder->vendor->company_name ?:
                                        $purchaseReturn->goodsReceipt->purchaseOrder->vendor->name }}
                                </p>

                            </div>


                            <div class="px-5 py-4">

                                <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                                    Return Date
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ $purchaseReturn->return_date?->format('d M Y') }}
                                </p>

                            </div>

                        </div>

                    </section>


                    {{-- Returned Materials --}}
                    <section class="overflow-hidden rounded-lg border border-slate-200 bg-white">

                        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">

                            <div>

                                <h2 class="text-sm font-semibold text-slate-900">
                                    Returned Materials
                                </h2>

                                <p class="mt-0.5 text-xs text-slate-500">
                                    Materials included in this purchase return.
                                </p>

                            </div>

                            <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                {{ $purchaseReturn->items->count() }}
                                {{ $purchaseReturn->items->count() === 1 ? 'Item' : 'Items' }}
                            </span>

                        </div>


                        <div class="overflow-x-auto">

                            <table class="w-full min-w-[700px] text-left text-sm">

                                <thead class="border-b border-slate-200 bg-slate-50">

                                    <tr class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">

                                        <th class="px-5 py-3">
                                            Material
                                        </th>

                                        <th class="px-5 py-3 text-right">
                                            Quantity
                                        </th>

                                        <th class="px-5 py-3 text-right">
                                            Unit Cost
                                        </th>

                                        <th class="px-5 py-3 text-right">
                                            Return Value
                                        </th>

                                    </tr>

                                </thead>


                                <tbody class="divide-y divide-slate-100">

                                    @foreach ($purchaseReturn->items as $item)
                                        <tr class="hover:bg-slate-50/70">

                                            <td class="px-5 py-4">

                                                <p class="font-medium text-slate-900">
                                                    {{ $item->goodsReceiptItem->purchaseOrderItem->rawMaterial->name ?? '-' }}
                                                </p>

                                            </td>

                                            <td class="px-5 py-4 text-right text-slate-700">

                                                {{ $item->qty }}

                                                <span class="text-xs text-slate-400">
                                                    {{ $item->unit->short_name ?? $item->unit->name }}
                                                </span>

                                            </td>

                                            <td class="px-5 py-4 text-right text-slate-700">
                                                {{ number_format($item->unit_cost, 2) }}
                                            </td>

                                            <td class="px-5 py-4 text-right font-semibold text-slate-900">
                                                {{ number_format($item->line_total, 2) }}
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>


                                <tfoot class="border-t border-slate-200 bg-slate-50">

                                    <tr>

                                        <td colspan="3" class="px-5 py-4 text-right text-sm font-medium text-slate-600">

                                            Total Return Value

                                        </td>

                                        <td class="px-5 py-4 text-right text-lg font-bold text-slate-900">

                                            {{ number_format($purchaseReturn->items->sum('line_total'), 2) }}

                                        </td>

                                    </tr>

                                </tfoot>

                            </table>

                        </div>

                    </section>

            </div>

                    {{-- Activity Timeline --}}
            <aside class="lg:col-span-1">

                <div class="sticky top-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                    <div class="border-b border-slate-200 px-5 py-4">

                        <div class="flex items-center justify-between">

                            <div class="flex items-center gap-3">

                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                                    <i class="bx bx-history"></i>
                                </div>

                                <div>
                                    <h2 class="text-sm font-semibold text-slate-900">
                                        Activity
                                    </h2>

                                    <p class="mt-0.5 text-xs text-slate-500">
                                        Purchase request history
                                    </p>
                                </div>

                            </div>

                            <span
                                class="inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-slate-100 px-2 text-[11px] font-semibold text-slate-600">
                                {{ $activities->count() }}
                            </span>

                        </div>

                    </div>


                    <div class="max-h-[calc(100vh-150px)] overflow-y-auto">

                        @forelse ($activities as $activity)

                            @php
                                $activityIcon = match ($activity->action) {
                                    'Purchase Request Created' => 'bx-plus',
                                    'Purchase Request Updated' => 'bx-edit',
                                    'RFQ Sent' => 'bx-envelope',
                                    'RFQ Resent' => 'bx-refresh',
                                    'Quotation Received' => 'bx-file',
                                    'Quotation Selected' => 'bx-check-square',
                                    'Purchase Order Created' => 'bx-cart',
                                    'Purchase Order Approved' => 'bx-check',
                                    'Goods Receipt Created',
                                    'Goods Received',
                                    'Materials Received' => 'bx-package',
                                    'Material Return Created' => 'bx-undo',
                                    'Material Return Completed' => 'bx-check-double',
                                    'Vendor Bill Created' => 'bx-receipt',
                                    'Payment Recorded' => 'bx-money',
                                    'Vendor Bill Partially Paid' => 'bx-time-five',
                                    'Vendor Bill Paid' => 'bx-check-circle',
                                    'Vendor Credit Created' => 'bx-credit-card',
                                    'Vendor Credit Applied' => 'bx-transfer',
                                    'Vendor Refund Recorded' => 'bx-undo',
                                    default => 'bx-history',
                                };

                                $activityClass = match ($activity->action) {
                                    'Purchase Request Created',
                                    'Purchase Request Updated'
                                        => 'border-slate-200 bg-slate-50 text-slate-600',

                                    'RFQ Sent',
                                    'RFQ Resent'
                                        => 'border-blue-200 bg-blue-50 text-blue-600',

                                    'Quotation Received',
                                    'Quotation Selected'
                                        => 'border-violet-200 bg-violet-50 text-violet-600',

                                    'Purchase Order Created',
                                    'Purchase Order Approved'
                                        => 'border-amber-200 bg-amber-50 text-amber-600',

                                    'Goods Receipt Created',
                                    'Goods Received',
                                    'Materials Received'
                                        => 'border-teal-200 bg-teal-50 text-teal-600',

                                    'Material Return Created'
                                        => 'border-orange-200 bg-orange-50 text-orange-600',

                                    'Material Return Completed',
                                    'Vendor Bill Paid'
                                        => 'border-green-200 bg-green-50 text-green-600',

                                    'Vendor Bill Created'
                                        => 'border-indigo-200 bg-indigo-50 text-indigo-600',

                                    'Payment Recorded',
                                    'Vendor Bill Partially Paid'
                                        => 'border-blue-200 bg-blue-50 text-blue-600',

                                    'Vendor Credit Created',
                                    'Vendor Credit Applied'
                                        => 'border-violet-200 bg-violet-50 text-violet-600',

                                    'Vendor Refund Recorded'
                                        => 'border-orange-200 bg-orange-50 text-orange-600',

                                    default
                                        => 'border-gray-200 bg-white text-gray-500',
                                };
                            @endphp


                            <div class="relative flex gap-3 px-5 py-4">

                                @if (!$loop->last)
                                    <span class="absolute bottom-0 left-[31px] top-12 w-px bg-slate-200"></span>
                                @endif


                                {{-- Activity Icon --}}
                                <div
                                    class="relative z-10 flex h-7 w-7 shrink-0 items-center justify-center rounded-full border {{ $activityClass }}">

                                    <i class="bx {{ $activityIcon }} text-sm"></i>

                                </div>


                                {{-- Activity Content --}}
                                <div class="min-w-0 flex-1">

                                    <div class="flex items-start justify-between gap-3">

                                        <p class="text-xs font-semibold text-slate-800">
                                            {{ $activity->action }}
                                        </p>

                                        <span class="shrink-0 text-[10px] text-slate-400">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </span>

                                    </div>


                                    @if ($activity->description)
                                        <p class="mt-1 text-xs leading-5 text-slate-500">
                                            {{ $activity->description }}
                                        </p>
                                    @endif


                                    @if ($activity->vendor || $activity->user)

                                        <div class="mt-2 flex flex-wrap gap-2">

                                            @if ($activity->vendor)
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-md bg-slate-50 px-2 py-1 text-[10px] font-medium text-slate-500">

                                                    <i class="bx bx-store text-xs"></i>

                                                    {{ $activity->vendor->company_name ?: $activity->vendor->name }}

                                                </span>
                                            @endif


                                            @if ($activity->user)
                                                <span
                                                    class="inline-flex items-center gap-1 rounded-md bg-slate-50 px-2 py-1 text-[10px] font-medium text-slate-500">

                                                    <i class="bx bx-user text-xs"></i>

                                                    {{ $activity->user->name }}

                                                </span>
                                            @endif

                                        </div>

                                    @endif

                                </div>

                            </div>

                        @empty

                            <div class="px-5 py-10 text-center">

                                <div
                                    class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400">

                                    <i class="bx bx-history text-lg"></i>

                                </div>

                                <p class="mt-3 text-xs font-medium text-slate-600">
                                    No activity yet
                                </p>

                                <p class="mt-1 text-[11px] text-slate-400">
                                    Activity will appear here as this request progresses.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </div>

            </aside>

        </div>

    </div>

</x-layouts.app>
