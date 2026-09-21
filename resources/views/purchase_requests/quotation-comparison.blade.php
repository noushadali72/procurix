<x-layouts.app title="Quotation Comparison">

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('purchase-requests.quotations', $pr) }}"
                        class="text-gray-400 transition hover:text-gray-600 dark:hover:text-gray-200">
                        <i class="bx bx-arrow-back text-xl"></i>
                    </a>

                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                        Quotation Comparison
                    </h1>
                </div>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Compare vendor quotations for Purchase Request
                    <a href="{{ route('purchase-requests.show', $pr) }}"
                        class="font-medium text-gray-700 dark:text-gray-300 underline font-bold">
                        #{{ $pr->request_number }}
                    </a>
                </p>
            </div>
        </div>

        @if ($pr->quotations->isEmpty())

            <div
                class="rounded-xl border border-gray-200 bg-white p-10 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div
                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                    <i class="bx bx-file text-2xl text-gray-500 dark:text-gray-300"></i>
                </div>

                <h3 class="mt-4 text-sm font-semibold text-gray-900 dark:text-white">
                    No quotations available
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    No vendor quotations have been received for this purchase request yet.
                </p>
            </div>
        @else
            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div
                    class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Vendors
                    </p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $pr->quotations->count() }}
                    </p>
                </div>

                <div
                    class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Requested Materials
                    </p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $pr->items->count() }}
                    </p>
                </div>

                <div
                    class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Quotations
                    </p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ $pr->quotations->count() }}
                    </p>
                </div>

            </div>

            {{-- Comparison --}}
            <div
                class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h2 class="font-semibold text-gray-900 dark:text-white">
                        Vendor Comparison
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Compare pricing and quotation details before selecting a vendor.
                    </p>
                </div>

                <div class="overflow-x-auto">

                    <table class="min-w-full border-collapse text-sm">

                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-900/60">

                                {{-- Material --}}
                                <th
                                    class="sticky left-0 z-20 min-w-[260px] border-b border-r border-gray-300 bg-gray-50 px-6 py-5 text-left font-semibold text-gray-700 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200">
                                    Requested Material
                                </th>

                                @foreach ($pr->quotations as $quotation)
                                    <th
                                        class="min-w-[300px] border-b border-r border-gray-300 px-6 py-5 text-left last:border-r-0 dark:border-gray-600">
                                        <div class="flex items-start justify-between gap-4">

                                            <div>
                                                <p class="font-semibold text-gray-900 dark:text-white">
                                                    {{ $quotation->vendor->company_name ?? $quotation->vendor->name }}
                                                </p>

                                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $quotation->quotation_number ?? 'Quotation #' . $quotation->id }}
                                                </p>
                                            </div>

                                            @if ($quotation->status ?? false)
                                                <span
                                                    class="shrink-0 rounded-full border border-gray-200 bg-white px-2.5 py-1 text-xs font-medium text-gray-600 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                                    {{ ucfirst(str_replace('_', ' ', $quotation->status)) }}
                                                </span>
                                            @endif

                                        </div>
                                    </th>
                                @endforeach

                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($pr->items as $prItem)
                                <tr class="border-b border-gray-300 last:border-b-0 dark:border-gray-600">

                                    {{-- Material --}}
                                    <td
                                        class="sticky left-0 z-10 border-r border-gray-300 bg-white px-6 py-5 align-top dark:border-gray-600 dark:bg-gray-800">
                                        <div class=" text-gray-900 dark:text-white">
                                            {{ $prItem->rawMaterial->name ?? '-' }}
                                        </div>

                                        <div
                                            class="mt-2 inline-flex rounded-md border border-gray-200 bg-gray-50 px-2.5 py-1 text-xs text-gray-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-400">
                                            Required:
                                            <span class="ml-1 font-medium text-gray-700 dark:text-gray-300">
                                                {{ $prItem->qty }}
                                                {{ $prItem->unit->short_name ?? '' }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Vendor quotations --}}
                                    @foreach ($pr->quotations as $quotation)
                                        @php
                                            $quotationItem = $quotation->items->firstWhere(
                                                'raw_material_id',
                                                $prItem->raw_material_id,
                                            );
                                        @endphp

                                        <td
                                            class="border-r border-gray-300 px-6 py-5 align-top last:border-r-0 dark:border-gray-600">

                                            @if ($quotationItem)
                                                <div
                                                    class="rounded-lg border border-gray-200 bg-gray-50/70 p-4 dark:border-gray-600 dark:bg-gray-900/40">

                                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

                                                        <div>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                Unit Price
                                                            </p>

                                                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                                                                {{ number_format($quotationItem->price, 2) }}
                                                            </p>
                                                        </div>

                                                        <div>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                Qty
                                                            </p>

                                                            <p
                                                                class="mt-1 font-medium text-gray-700 dark:text-gray-300">
                                                                {{ $quotationItem->qty }}
                                                                {{ $quotationItem->unit->short_name ?? '' }}
                                                            </p>
                                                        </div>

                                                        <div>
                                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                                Line Total
                                                            </p>

                                                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                                                                {{ number_format($quotationItem->total, 2) }}
                                                            </p>
                                                        </div>

                                                    </div>

                                                </div>
                                            @else
                                                <div
                                                    class="rounded-lg border border-dashed border-gray-300 bg-gray-50 px-4 py-4 text-center dark:border-gray-600 dark:bg-gray-900/30">
                                                    <span class="text-xs text-gray-400 dark:text-gray-500">
                                                        Not offered
                                                    </span>
                                                </div>
                                            @endif

                                        </td>
                                    @endforeach

                                </tr>
                            @endforeach

                        </tbody>

                        {{-- Totals --}}
                        <tfoot>

                            <tr class="border-t border-gray-300 bg-gray-50 dark:border-gray-600 dark:bg-gray-900/60">

                                <td
                                    class="sticky left-0 z-10 border-r border-gray-300 bg-gray-50 px-6 py-5 font-semibold text-gray-900 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                                    Quotation Total
                                </td>

                                @foreach ($pr->quotations as $quotation)
                                    <td class="border-r border-gray-300 px-6 py-5 last:border-r-0 dark:border-gray-600">

                                        <div class="flex items-center justify-between gap-4">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                Total
                                            </span>

                                            <span class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ number_format($quotation->total ?? 0, 2) }}
                                            </span>
                                        </div>

                                        @if (!empty($quotation->tax))
                                            <div
                                                class="mt-2 border-t border-gray-200 pt-2 text-right text-xs text-gray-500 dark:border-gray-700 dark:text-gray-400">
                                                Tax:
                                                {{ number_format($quotation->tax, 2) }}
                                            </div>
                                        @endif

                                    </td>
                                @endforeach

                            </tr>

                            {{-- Vendor details --}}
                            <tr class="border-t border-gray-200 dark:border-gray-700">

                                <td
                                    class="sticky left-0 z-10 border-r border-gray-300 bg-white px-6 py-5 font-medium text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                    Vendor Details
                                </td>

                                @foreach ($pr->quotations as $quotation)
                                    <td class="border-r border-gray-300 px-6 py-5 last:border-r-0 dark:border-gray-600">

                                        <div class="space-y-2 text-xs text-gray-500 dark:text-gray-400">

                                            @if ($quotation->vendor->phone ?? false)
                                                <div class="flex items-center gap-2">
                                                    <i class="bx bx-phone"></i>
                                                    <span>{{ $quotation->vendor->phone }}</span>
                                                </div>
                                            @endif

                                            @if ($quotation->vendor->email ?? false)
                                                <div class="flex items-center gap-2">
                                                    <i class="bx bx-envelope"></i>
                                                    <span>{{ $quotation->vendor->email }}</span>
                                                </div>
                                            @endif

                                        </div>

                                    </td>
                                @endforeach

                            </tr>

                            {{-- Actions --}}
                            @if ($pr->status !== 'completed')

                                <tr
                                    class="border-t border-gray-300 bg-gray-50 dark:border-gray-600 dark:bg-gray-900/60">

                                    <td
                                        class="sticky left-0 z-10 border-r border-gray-300 bg-gray-50 px-6 py-5 dark:border-gray-600 dark:bg-gray-900">
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Select Quotation
                                        </span>
                                    </td>

                                    @foreach ($pr->quotations as $quotation)
                                        <td
                                            class="border-r border-gray-300 px-6 py-5 last:border-r-0 dark:border-gray-600">

                                            <button type="button"
                                                class="inline-flex items-center rounded-md border border-gray-300 bg-gray-900 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-gray-800 dark:border-gray-600 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200"
                                                onclick="acceptQuotation({{ $quotation->id }})">
                                                <i class="bx bx-check mr-1 text-sm"></i>
                                                Accept
                                            </button>

                                        </td>
                                    @endforeach

                                </tr>

                            @endif

                        </tfoot>

                    </table>

                </div>
            </div>

        @endif

    </div>

    @push('scripts')
        <script>
            function acceptQuotation(quotationId) {
                if (!confirm('Are you sure you want to accept this quotation?')) {
                    return;
                }

                let url = "{{ route('quotations.accept',':id') }}";
                url = url.replace(':id',quotationId);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    headers: {
                        Accept: 'application/json'
                    },
                    success: function(response) {
                        showToast('success', response.message);

                        setTimeout(function() {
                            window.location.reload();
                        }, 500);
                    },
                    error: function(xhr) {
                        showToast(
                            'error',
                            xhr.responseJSON?.message ||
                            'Unable to accept quotation.'
                        );
                    }
                });
            }
        </script>
    @endpush

</x-layouts.app>
