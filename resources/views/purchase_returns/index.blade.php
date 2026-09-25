<x-layouts.app title="Purchase Returns">
{{-- Page Header --}}
<div class="mb-6">

    <div class="mb-3 flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('admin.dashboard') }}"
           class="transition hover:text-slate-900">
            Dashboard
        </a>

        <span class="text-slate-300">/</span>

        <span>Procurement</span>

        <span class="text-slate-300">/</span>

        <span class="font-medium text-slate-700">
            Purchase Returns
        </span>
    </div>

    <div class="flex items-center gap-3">

        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm">
            <i class="bx bx-undo text-xl"></i>
        </div>

        <div>
            <h1 class="text-xl font-semibold text-slate-900">
                Purchase Returns
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Track materials returned to vendors and related financial adjustments.
            </p>
        </div>

    </div>

</div>


{{-- Summary --}}
<div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2">

    <div class="rounded-lg border border-slate-200 bg-white px-4 py-4 shadow-sm">

        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
            Total Returns
        </p>

        <div class="mt-1 flex items-end gap-2">
            <p class="text-xl font-semibold text-slate-900">
                {{ $purchaseReturns->total() }}
            </p>

            <span class="mb-0.5 text-xs text-slate-400">
                {{ Str::plural('return', $purchaseReturns->total()) }}
            </span>
        </div>

    </div>


    <div class="rounded-lg border border-slate-200 bg-white px-4 py-4 shadow-sm">

        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
            Current Page
        </p>

        <div class="mt-1 flex items-end gap-2">
            <p class="text-xl font-semibold text-slate-900">
                {{ $purchaseReturns->count() }}
            </p>

            <span class="mb-0.5 text-xs text-slate-400">
                records shown
            </span>
        </div>

    </div>

</div>


{{-- Return History --}}
<div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">

    <div class="flex flex-col gap-2 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-sm font-semibold text-slate-900">
                Return History
            </h2>

            <p class="mt-0.5 text-xs text-slate-500">
                Review returned materials, source documents, status, and financial treatment.
            </p>
        </div>

        @if($purchaseReturns->total() > 0)

            <span class="w-fit rounded-md bg-slate-50 px-2.5 py-1.5 text-xs font-medium text-slate-600">
                {{ $purchaseReturns->total() }}
                {{ Str::plural('record', $purchaseReturns->total()) }}
            </span>

        @endif

    </div>


    <div class="overflow-x-auto">

        <table class="w-full min-w-[1050px] text-left text-sm">

            <thead class="border-b border-slate-200 bg-slate-50">

                <tr class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">

                    <th class="px-5 py-3">
                        Return
                    </th>

                    <th class="px-5 py-3">
                        Goods Receipt
                    </th>

                    <th class="px-5 py-3">
                        Purchase Order
                    </th>

                    <th class="px-5 py-3">
                        Vendor
                    </th>

                    <th class="px-5 py-3">
                        Return Date
                    </th>

                    <th class="px-5 py-3">
                        Status
                    </th>

                    <th class="px-5 py-3">
                        Finance
                    </th>

                    <th class="px-5 py-3 text-right">
                        Action
                    </th>

                </tr>

            </thead>


            <tbody class="divide-y divide-slate-100">

                @forelse($purchaseReturns as $return)

                    <tr class="transition hover:bg-slate-50/70">

                        {{-- Return --}}
                        <td class="px-5 py-4">

                            <div>
                                <p class="font-semibold text-slate-900">
                                    {{ $return->return_number }}
                                </p>

                                <p class="mt-0.5 text-[11px] text-slate-400">
                                    Return reference
                                </p>
                            </div>

                        </td>


                        {{-- Goods Receipt --}}
                        <td class="px-5 py-4">

                            <a
                                href="{{ route('goods-receipts.show', $return->goodsReceipt) }}"
                                class="inline-flex items-center gap-1.5 font-medium text-slate-700 transition hover:text-slate-900 hover:underline">

                                {{ $return->goodsReceipt->grn_number }}

                               <i class="bx bx-link text-xs text-slate-400"></i>

                            </a>

                        </td>


                        {{-- Purchase Order --}}
                        <td class="px-5 py-4">

                            <a
                                href="{{ route('purchase-orders.show', $return->goodsReceipt->purchaseOrder) }}"
                                class="inline-flex items-center gap-1.5 font-medium text-slate-700 transition hover:text-slate-900 hover:underline">

                                {{ $return->goodsReceipt->purchaseOrder->order_number }}

                               <i class="bx bx-link text-xs text-slate-400"></i>

                            </a>

                        </td>


                        {{-- Vendor --}}
                        <td class="px-5 py-4">

                            <span class="max-w-[200px] truncate font-medium text-slate-700">
                                {{ $return->goodsReceipt->purchaseOrder->vendor->company_name
                                    ?: $return->goodsReceipt->purchaseOrder->vendor->name }}
                            </span>

                        </td>


                        {{-- Date --}}
                        <td class="px-5 py-4 text-slate-600">
                            {{ $return->return_date?->format('d M Y') }}
                        </td>


                        {{-- Status --}}
                        <td class="px-5 py-4">

                            @if($return->status === 'completed')

                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2.5 py-1.5 text-xs font-semibold text-emerald-700">
                                    Completed
                                </span>

                            @elseif($return->status === 'cancelled')

                                <span class="inline-flex items-center rounded-md bg-red-50 px-2.5 py-1.5 text-xs font-semibold text-red-700">
                                    Cancelled
                                </span>

                            @else

                                <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1.5 text-xs font-semibold text-slate-600">
                                    Draft
                                </span>

                            @endif

                        </td>


                        {{-- Finance --}}
                        <td class="px-5 py-4">

                            @if($return->vendorCredit)

                                <span class="inline-flex items-center rounded-md bg-emerald-50 px-2.5 py-1.5 text-xs font-medium text-emerald-700">
                                    Vendor Credit
                                </span>

                            @else

                                <span class="text-xs text-slate-500">
                                    Net Bill Adjustment
                                </span>

                            @endif

                        </td>


                        {{-- Action --}}
                        <td class="px-5 py-4 text-right">

                            <a
                                href="{{ route('purchase-returns.show', $return) }}"
                                class="inline-flex items-center gap-1.5 rounded-md border border-slate-300 bg-white px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-800 hover:text-white">

                                <i class="bx bx-show"></i>
                                View

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8" class="px-5 py-16">

                            <div class="text-center">

                                <h3 class="text-sm font-semibold text-slate-800">
                                    No purchase returns found
                                </h3>

                                <p class="mt-1 text-xs text-slate-500">
                                    Returned materials will appear here once a purchase return has been recorded.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- Pagination --}}
    @if($purchaseReturns->hasPages())

        <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

            <p class="text-xs text-slate-500">
                Showing
                <span class="font-medium text-slate-700">
                    {{ $purchaseReturns->firstItem() }}
                </span>
                to
                <span class="font-medium text-slate-700">
                    {{ $purchaseReturns->lastItem() }}
                </span>
                of
                <span class="font-medium text-slate-700">
                    {{ $purchaseReturns->total() }}
                </span>
                returns
            </p>

            <div>
                {{ $purchaseReturns->links() }}
            </div>

        </div>

    @endif

</div>


</x-layouts.app>
