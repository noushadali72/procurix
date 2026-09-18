<x-layouts.app title="Quotation Details">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <a
                href="{{ route('quotations.index') }}"
                class="inline-flex items-center text-sm font-medium text-gray-500 transition hover:text-gray-900"
            >
                <i class="bx bx-arrow-back mr-1.5"></i>
                Back to Quotations
            </a>

            <div class="mt-3">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Quotation #{{ $quotation->quotation_number ?? $quotation->id }}
                    </h2>

                 
                </div>

                <p class="mt-1 text-sm text-gray-500">
                    Review quotation details and pricing information.
                </p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2">

            @if($quotation->status === 'pending')

                <a
                    href="{{ route('quotations.edit', $quotation) }}"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Edit
                </a>

                <form
                    action="{{ route('quotations.accept', $quotation) }}"
                    method="POST"
                    id="accept-quotation-form"
                >
                    @csrf

                    <button
                        type="button"
                        id="accept-quotation"
                        class="rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800"
                    >
                        Accept Quotation
                    </button>
                </form>

            @elseif($quotation->status==='expired')
                 <span class="inline-flex items-center rounded-lg bg-red-50 px-4 py-2.5 text-sm font-medium text-red-700">
                    Quotation Expired
                </span>
            @else
                <span class="inline-flex items-center rounded-lg bg-green-50 px-4 py-2.5 text-sm font-medium text-green-700">
                    Quotation Accepted
                </span>

            @endif

        </div>

    </div>


    {{-- Quotation Information --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="font-semibold text-gray-900">
                Quotation Information
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Basic quotation and vendor information.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-x-8 gap-y-6 p-6 sm:grid-cols-2 lg:grid-cols-3">

            {{-- Quotation Number --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Quotation Number
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $quotation->quotation_number ?? '-' }}
                </p>
            </div>

            {{-- Purchase Request --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Purchase Request
                </p>

                <a href="{{ route('purchase-requests.show',$quotation->purchaseRequest) }}" class="mt-1.5 font-semibold text-blue-900 underline">
                    PR-{{ $quotation->purchaseRequest->request_number }}
                </a>
            </div>

            {{-- Vendor --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Vendor
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $quotation->vendor->company_name ?: $quotation->vendor->name }}
                </p>
            </div>

            {{-- Quotation Date --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Quotation Date
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $quotation->quotation_date->format('d M Y') }}
                </p>
            </div>

            {{-- Valid Until --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Valid Until
                </p>

                <p class="mt-1.5 font-semibold text-gray-900">
                    {{ $quotation->valid_until?->format('d M Y') ?? '-' }}
                </p>
            </div>

            {{-- Status --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    Status
                </p>

                <div class="mt-1.5">
                    @if($quotation->status === 'accepted')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                            Accepted
                        </span>
                    @elseif($quotation->status==='expired')
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                            Expired
                        </span>
                    @else

                          <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            Pending
                        </span>

                    @endif
                </div>
            </div>

            {{-- Notes --}}
            @if($quotation->notes)
                <div class="border-t border-gray-100 pt-5 sm:col-span-2 lg:col-span-3">
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        Notes
                    </p>

                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-700">
                        {{ $quotation->notes }}
                    </p>
                </div>
            @endif

        </div>

    </div>


    {{-- Purchase Request Items --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">

            <div>
                <h3 class="font-semibold text-gray-900">
                    Purchase Request Items
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Materials requested in the original purchase request.
                </p>
            </div>

            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                {{ $quotation->purchaseRequest->items->count() }} Items
            </span>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[700px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        <th class="px-6 py-3.5">Raw Material</th>
                        <th class="px-6 py-3.5">SKU</th>
                        <th class="px-6 py-3.5">Requested Qty</th>
                        <th class="px-6 py-3.5">Unit</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($quotation->purchaseRequest->items as $item)

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">
                                    {{ $item->rawMaterial->name }}
                                </p>
                            </td>

                            <td class="px-6 py-4">
                                <span class="rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                    {{ $item->rawMaterial->sku }}
                                </span>
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $item->qty }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $item->unit->short_name ?? $item->unit->name }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                                No purchase request items found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Quotation Items --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">

            <div>
                <h3 class="font-semibold text-gray-900">
                    Quotation Items
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Pricing provided by the vendor for the requested materials.
                </p>
            </div>

            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                {{ $quotation->items->count() }} Items
            </span>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[800px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr class="text-xs font-medium uppercase tracking-wide text-gray-500">
                        <th class="px-6 py-3.5">Raw Material</th>
                        <th class="px-6 py-3.5">Quantity</th>
                        <th class="px-6 py-3.5">Unit</th>
                        <th class="px-6 py-3.5">Unit Price</th>
                        <th class="px-6 py-3.5 text-right">Total</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($quotation->items as $item)

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">
                                    {{ $item->rawMaterial->name }}
                                </p>

                                @if($item->rawMaterial->sku)
                                    <p class="mt-0.5 text-xs text-gray-500">
                                        {{ $item->rawMaterial->sku }}
                                    </p>
                                @endif
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $item->qty }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $item->unit->short_name ?? $item->unit->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-700">
                                {{ number_format($item->price, 2) }}
                            </td>

                            <td class="px-6 py-4 text-right font-semibold text-gray-900">
                                {{ number_format($item->total, 2) }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">
                                No quotation items found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

                <tfoot class="border-t border-gray-200 bg-gray-50">

                    <tr>
                        <td
                            colspan="4"
                            class="px-6 py-4 text-right text-sm font-semibold text-gray-700"
                        >
                            Grand Total
                        </td>

                        <td class="px-6 py-4 text-right">
                            <span class="text-lg font-bold text-gray-900">
                                {{ number_format($quotation->items->sum('total'), 2) }}
                            </span>
                        </td>
                    </tr>

                </tfoot>

            </table>

        </div>

    </div>


    @push('scripts')
        <script>
            $(document).ready(function () {

                $('#accept-quotation').on('click', function () {

                    const confirmed = confirm(
                        'Are you sure you want to accept this quotation?\n\nA Purchase Order will be created for this quotation.'
                    );

                    if (confirmed) {
                        $('#accept-quotation-form').submit();
                    }

                });

            });
        </script>
    @endpush

</x-layouts.app>