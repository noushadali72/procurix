<x-layouts.app title="Purchase Return">

    <div class="max-w-7xl">

        {{-- Header --}}
        <div class="mb-6 flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-end sm:justify-between">

            <div>

                <div class="flex items-center gap-3">

                    <h1 class="text-xl font-semibold text-slate-900">
                        {{ $purchaseReturn->return_number }}
                    </h1>

                    <span class="rounded-md bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                        {{ ucfirst($purchaseReturn->status) }}
                    </span>

                </div>

                <p class="mt-1 text-sm text-slate-500">
                    Purchase return details and financial impact.
                </p>

            </div>


            <div class="flex gap-2">

                <a href="{{ route('goods-receipts.show', $purchaseReturn->goodsReceipt) }}"
                    class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">

                    Goods Receipt

                </a>

                @if($purchaseReturn->vendorCredit)

                    <a href="{{ route('vendor-credits.show', $purchaseReturn->vendorCredit) }}"
                        class="rounded-md bg-slate-800 px-4 py-2 text-sm font-medium text-white hover:bg-slate-900">

                        View Vendor Credit

                    </a>

                @endif

            </div>

        </div>


        {{-- Summary --}}
        <section class="mb-5 overflow-hidden rounded-lg border border-slate-200 bg-white">

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
                        GRN
                    </p>

                    <a href="{{ route('goods-receipts.show',$purchaseReturn->goodsReceipt) }}" class="mt-1 text-sm font-semibold text-slate-900 hover:underline">
                        {{ $purchaseReturn->goodsReceipt->grn_number }}
                          <i class="bx bx-link text-xs text-slate-400"></i>

                    </a>

                </div>


                <div class="border-b border-slate-100 px-5 py-4 sm:border-b-0 lg:border-r">

                    <p class="text-[11px] font-medium uppercase tracking-wide text-slate-400">
                        Vendor
                    </p>

                    <p class="mt-1 text-sm font-semibold text-slate-900">

                        {{ $purchaseReturn->goodsReceipt->purchaseOrder->vendor->company_name
                            ?: $purchaseReturn->goodsReceipt->purchaseOrder->vendor->name }}

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


        {{-- Materials --}}
        <section class="mb-5 overflow-hidden rounded-lg border border-slate-200 bg-white">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="text-sm font-semibold text-slate-900">
                    Returned Materials
                </h2>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full min-w-[700px] text-left text-sm">

                    <thead class="border-b border-slate-200 bg-slate-50">

                        <tr class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">

                            <th class="px-5 py-3">Material</th>
                            <th class="px-5 py-3 text-right">Quantity</th>
                            <th class="px-5 py-3 text-right">Unit Cost</th>
                            <th class="px-5 py-3 text-right">Return Value</th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @foreach($purchaseReturn->items as $item)

                            <tr>

                                <td class="px-5 py-4 font-medium text-slate-900">

                                    {{ $item
                                        ->goodsReceiptItem
                                        ->purchaseOrderItem
                                        ->rawMaterial
                                        ->name ?? '-' }}

                                </td>

                                <td class="px-5 py-4 text-right">

                                    {{ $item->qty }}

                                    {{ $item->unit->short_name ?? $item->unit->name }}

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

                            <td colspan="3"
                                class="px-5 py-4 text-right text-sm font-medium text-slate-600">
                                Total Return Value
                            </td>

                            <td class="px-5 py-4 text-right text-base font-bold text-slate-900">
                                {{ number_format($purchaseReturn->items->sum('line_total'), 2) }}
                            </td>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </section>


        {{-- Financial Impact --}}
        <section class="rounded-lg border border-slate-200 bg-white">

            <div class="border-b border-slate-200 px-5 py-4">

                <h2 class="text-sm font-semibold text-slate-900">
                    Financial Impact
                </h2>

            </div>


            <div class="p-5">

                @if($purchaseReturn->vendorCredit)

                    <div class="flex items-start gap-3 rounded-md border border-emerald-200 bg-emerald-50 p-4">

                        <i class="bx bx-credit-card mt-0.5 text-xl text-emerald-600"></i>

                        <div>

                            <p class="text-sm font-semibold text-emerald-800">
                                Vendor Credit Created
                            </p>

                            <p class="mt-1 text-sm text-emerald-700">

                                Credit
                                {{ $purchaseReturn->vendorCredit->credit_number }}
                                for
                                {{ number_format($purchaseReturn->vendorCredit->amount, 2) }}
                                was created because a vendor bill already existed.

                            </p>

                        </div>

                    </div>

                @else

                    <div class="flex items-start gap-3 rounded-md border border-slate-200 bg-slate-50 p-4">

                        <i class="bx bx-info-circle mt-0.5 text-xl text-slate-500"></i>

                        <div>

                            <p class="text-sm font-semibold text-slate-800">
                                No Vendor Credit Required
                            </p>

                            <p class="mt-1 text-sm text-slate-600">
                                This return occurred before vendor bill generation.
                                The returned quantity will be excluded when the vendor
                                bill is generated.
                            </p>

                        </div>

                    </div>

                @endif

            </div>

        </section>

    </div>

</x-layouts.app>