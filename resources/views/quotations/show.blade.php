<x-layouts.app title="Quotation Details">

    <div class="mb-6 flex items-center justify-between">

        <div>
            <a href="{{ route('quotations.index') }}"
                class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900">
                <i class="bx bx-arrow-back"></i>
                Back to Quotations
            </a>

            <h2 class="mt-3 text-xl font-semibold text-gray-900">
                Quotation #{{ $quotation->quotation_number ?? $quotation->id }}
            </h2>
        </div>

        <div class="flex items-center gap-3">

    @if($quotation->status === 'pending')

        <a href="{{ route('quotations.edit', $quotation) }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
            <i class="bx bx-edit"></i>
            Edit
        </a>

        <form action="{{ route('quotations.accept', $quotation) }}"
            method="POST"
            id="accept-quotation-form">

            @csrf

            <button type="button"
                id="accept-quotation"
                class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-green-700">

                <i class="bx bx-check-circle"></i>
                Accept Quotation

            </button>

        </form>

    @else

        <span class="inline-flex items-center gap-2 rounded-lg bg-green-100 px-4 py-2.5 text-sm font-medium text-green-700">
            <i class="bx bx-check-circle"></i>
            Accepted
        </span>

    @endif

</div>

    </div>

    <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-3">

            <div>
                <p class="text-sm text-gray-500">Quotation Number</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $quotation->quotation_number ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Purchase Request</p>
                <p class="mt-1 font-semibold text-gray-900">
                    PR-{{ $quotation->purchaseRequest->request_number }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Vendor</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $quotation->vendor->company_name ?: $quotation->vendor->name }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Quotation Date</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $quotation->quotation_date->format('d M Y') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Valid Until</p>
                <p class="mt-1 font-semibold text-gray-900">
                    {{ $quotation->valid_until?->format('d M Y') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Status</p>

                <div class="mt-1">
                    @if($quotation->status === 'accepted')
                        <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                            Accepted
                        </span>
                    @else
                        <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                            Pending
                        </span>
                    @endif
                </div>
            </div>

            @if($quotation->notes)

                <div class="md:col-span-3">
                    <p class="text-sm text-gray-500">Notes</p>
                    <p class="mt-1 whitespace-pre-line text-gray-900">
                        {{ $quotation->notes }}
                    </p>
                </div>

            @endif

        </div>

    </div>

    {{-- Purchase Request --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b px-6 py-4">
            <h3 class="font-semibold text-gray-900">
                Purchase Request
            </h3>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="border-b bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Raw Material</th>
                        <th class="px-6 py-4">SKU</th>
                        <th class="px-6 py-4">Requested Qty</th>
                        <th class="px-6 py-4">Unit</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @foreach($quotation->purchaseRequest->items as $item)

                        <tr>

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $item->rawMaterial->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $item->rawMaterial->sku }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $item->qty }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $item->unit->short_name ?? $item->unit->name }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

    {{-- Quotation Items --}}
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b px-6 py-4">
            <h3 class="font-semibold text-gray-900">
                Quotation Items
            </h3>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="border-b bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Raw Material</th>
                        <th class="px-6 py-4">Quantity</th>
                        <th class="px-6 py-4">Unit</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4 text-right">Total</th>
                    </tr>
                </thead>

                <tbody class="divide-y">

                    @foreach($quotation->items as $item)

                        <tr>

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $item->rawMaterial->name }}
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
                            {{ number_format($quotation->items->sum('total'), 2) }}
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