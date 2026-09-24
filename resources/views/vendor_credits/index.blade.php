<x-layouts.app title="Vendor Credits">

    <div class="mb-6">

        <h1 class="text-2xl font-semibold text-slate-900">
            Vendor Credits
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Credits created from purchase returns after vendor billing.
        </p>

    </div>


    <div class="overflow-hidden rounded-lg border border-slate-200 bg-white">

        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px] text-left text-sm">

                <thead class="border-b border-slate-200 bg-slate-50">

                    <tr class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">

                        <th class="px-5 py-3">Credit</th>
                        <th class="px-5 py-3">Vendor</th>
                        <th class="px-5 py-3">Return</th>
                        <th class="px-5 py-3 text-right">Amount</th>
                        <th class="px-5 py-3 text-right">Applied</th>
                        <th class="px-5 py-3 text-right">Refunded</th>
                        <th class="px-5 py-3 text-right">Available</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Action</th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse($vendorCredits as $credit)

                        <tr class="hover:bg-slate-50">

                            <td class="px-5 py-4 font-semibold text-slate-900">
                                {{ $credit->credit_number }}
                            </td>

                            <td class="px-5 py-4 text-slate-700">
                                {{ $credit->vendor->company_name ?: $credit->vendor->name }}
                            </td>

                            <td class="px-5 py-4">

                                <a
                                    href="{{ route('purchase-returns.show', $credit->purchaseReturn) }}"
                                    class="font-medium text-slate-700 underline">

                                    {{ $credit->purchaseReturn->return_number }}

                                </a>

                            </td>

                            <td class="px-5 py-4 text-right font-medium">
                                {{ number_format($credit->amount, 2) }}
                            </td>

                            <td class="px-5 py-4 text-right">
                                {{ number_format($credit->applied_amount, 2) }}
                            </td>

                            <td class="px-5 py-4 text-right">
                                {{ number_format($credit->refunded_amount, 2) }}
                            </td>

                            <td class="px-5 py-4 text-right font-semibold text-slate-900">
                                {{ number_format($credit->remaining_amount, 2) }}
                            </td>

                            <td class="px-5 py-4">

                                <span class="rounded-md bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">

                                    {{ ucfirst(str_replace('_', ' ', $credit->status)) }}

                                </span>

                            </td>

                            <td class="px-5 py-4 text-right">

                                <a
                                    href="{{ route('vendor-credits.show', $credit) }}"
                                    class="inline-flex items-center gap-1 rounded-md border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-800 hover:text-white">

                                    <i class="bx bx-show"></i>
                                    View

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9"
                                class="px-5 py-14 text-center text-sm text-slate-500">

                                No vendor credits found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($vendorCredits->hasPages())

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $vendorCredits->links() }}
            </div>

        @endif

    </div>

</x-layouts.app>