<x-layouts.app title="Purchase Returns">

    <div class="mb-6 flex items-center justify-between">

        <div>
            <h1 class="text-2xl font-semibold text-slate-900">
                Purchase Returns
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Materials returned to vendors.
            </p>
        </div>

    </div>


    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">

        <div class="border-b border-slate-200 px-5 py-4">

            <h2 class="text-sm font-semibold text-slate-900">
                Return History
            </h2>

            <p class="mt-0.5 text-xs text-slate-500">
                {{ $purchaseReturns->total() }}
                {{ Str::plural('return', $purchaseReturns->total()) }}
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px] text-left text-sm">

                <thead class="border-b border-slate-200 bg-slate-50">

                    <tr class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">

                        <th class="px-5 py-3">Return</th>
                        <th class="px-5 py-3">Goods Receipt</th>
                        <th class="px-5 py-3">Purchase Order</th>
                        <th class="px-5 py-3">Vendor</th>
                        <th class="px-5 py-3">Date</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Finance</th>
                        <th class="px-5 py-3 text-right">Action</th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($purchaseReturns as $return)

                        <tr class="transition hover:bg-slate-50">

                            <td class="px-5 py-4 font-semibold text-slate-900">
                                {{ $return->return_number }}
                            </td>

                            <td class="px-5 py-4">

                                <a
                                    href="{{ route('goods-receipts.show', $return->goodsReceipt) }}"
                                    class="font-medium text-slate-700 underline">
                                    {{ $return->goodsReceipt->grn_number }}
                                </a>

                            </td>

                            <td class="px-5 py-4">

                                <a
                                    href="{{ route('purchase-orders.show', $return->goodsReceipt->purchaseOrder) }}"
                                    class="font-medium text-slate-700 underline">

                                    {{ $return->goodsReceipt->purchaseOrder->order_number }}

                                </a>

                            </td>

                            <td class="px-5 py-4 text-slate-700">

                                {{ $return->goodsReceipt->purchaseOrder->vendor->company_name
                                    ?: $return->goodsReceipt->purchaseOrder->vendor->name }}

                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                {{ $return->return_date?->format('d M Y') }}
                            </td>

                            <td class="px-5 py-4">

                                @if($return->status === 'completed')

                                    <span class="rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                                        Completed
                                    </span>

                                @elseif($return->status === 'cancelled')

                                    <span class="rounded-md bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700">
                                        Cancelled
                                    </span>

                                @else

                                    <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                        Draft
                                    </span>

                                @endif

                            </td>

                            <td class="px-5 py-4">

                                @if($return->vendorCredit)

                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700">

                                        <i class="bx bx-credit-card"></i>

                                        Vendor Credit

                                    </span>

                                @else

                                    <span class="text-xs text-slate-400">
                                        Net Bill Adjustment
                                    </span>

                                @endif

                            </td>

                            <td class="px-5 py-4 text-right">

                                <a
                                    href="{{ route('purchase-returns.show', $return) }}"
                                    class="inline-flex items-center gap-1.5 rounded-md border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-800 hover:text-white">

                                    <i class="bx bx-show"></i>
                                    View

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="px-5 py-14 text-center text-sm text-slate-500">

                                No purchase returns found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($purchaseReturns->hasPages())

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $purchaseReturns->links() }}
            </div>

        @endif

    </div>

</x-layouts.app>