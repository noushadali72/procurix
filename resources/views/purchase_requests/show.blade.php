<x-layouts.app title="Purchase Request Details">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="flex items-center gap-2">


                <h2 class="text-xl font-semibold text-gray-900">
                    Purchase Request
                </h2>
            </div>

            <p class="mt-1 text-sm text-gray-500">
                PR-{{ $purchaseRequest->request_number }}
                · Raw material purchase request details.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">

            <a
                href="{{ route('purchase-requests.edit', $purchaseRequest) }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800"
            >
                <i class="bx bx-edit text-lg"></i>
                Edit
            </a>

            <a
                href="{{ route('purchase-requests.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                <i class="bx bx-list-ul text-lg"></i>
                All Requests
            </a>

        </div>

    </div>


    {{-- Request Information --}}
    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white">

        <div class="border-b border-gray-200 px-5 py-4">
            <h3 class="text-sm font-semibold text-gray-900">
                Request Information
            </h3>
            <p class="mt-0.5 text-xs text-gray-500">
                Overview of this purchase request.
            </p>
        </div>

        <div class="grid grid-cols-1 divide-y divide-gray-100 md:grid-cols-3 md:divide-x md:divide-y-0">

            {{-- Request Number --}}
            <div class="p-5">
                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                        <i class="bx bx-receipt text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500">
                            Request Number
                        </p>

                        <p class="mt-0.5 text-sm font-semibold text-gray-900">
                            PR-{{ $purchaseRequest->request_number }}
                        </p>
                    </div>

                </div>
            </div>


            {{-- Status --}}
            <div class="p-5">
                <div class="flex items-center gap-3">

                    @php
                        $status = $purchaseRequest->status;

                    $statusClass = match ($status) {
                        'completed' => 'bg-green-50 text-green-600',
                        'active' => 'bg-green-50 text-green-600',
                        'pending' => 'bg-amber-50 text-amber-600',
                        default => 'bg-gray-100 text-gray-600',
                    };

                    $statusDot = match ($status) {
                        'completed' => 'bg-green-500',
                        'active' => 'bg-green-500',
                        'pending' => 'bg-amber-500',
                        default => 'bg-gray-400',
                    };

                        $statusLabel = match ($status) {
                            'completed' => 'Completed',
                            'pending' => 'Pending',
                            'active' => 'Active',
                            default => ucfirst($status),
                        };
                    @endphp

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                        <i class="bx bx-check-circle text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500">
                            Status
                        </p>

                        <span class="{{ $statusClass }} mt-1 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium">
                            <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                            {{ $statusLabel }}
                        </span>
                    </div>

                </div>
            </div>


            {{-- Created --}}
            <div class="p-5">
                <div class="flex items-center gap-3">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                        <i class="bx bx-calendar text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500">
                            Created
                        </p>

                        <p class="mt-0.5 text-sm font-semibold text-gray-900">
                            {{ $purchaseRequest->created_at->format('d M Y') }}
                        </p>

                        <p class="text-xs text-gray-500">
                            {{ $purchaseRequest->created_at->format('h:i A') }}
                        </p>
                    </div>

                </div>
            </div>

        </div>


        {{-- Notes --}}
        @if($purchaseRequest->notes)
            <div class="border-t border-gray-200 px-5 py-4">

                <div class="flex items-start gap-3">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                        <i class="bx bx-note text-lg"></i>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500">
                            Notes
                        </p>

                        <p class="mt-1 whitespace-pre-line text-sm leading-6 text-gray-700">
                            {{ $purchaseRequest->notes }}
                        </p>
                    </div>

                </div>

            </div>
        @endif

    </div>


    {{-- Raw Materials --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        <div class="flex flex-col gap-3 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h3 class="text-sm font-semibold text-gray-900">
                    Raw Materials
                </h3>

                <p class="mt-0.5 text-xs text-gray-500">
                    {{ $purchaseRequest->items->count() }}
                    {{ Str::plural('item', $purchaseRequest->items->count()) }}
                    requested
                </p>
            </div>

            @if($purchaseRequest->status === 'active')
                <a
                    href="{{ route('quotations.create', $purchaseRequest) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800"
                >
                    <i class="bx bx-file text-lg"></i>
                    Add Quotation
                </a>
            @endif

        </div>


        <div class="overflow-x-auto">

            <table class="w-full min-w-[700px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wide text-gray-500">

                    <tr>
                        <th class="px-5 py-3.5 font-medium">
                            #
                        </th>

                        <th class="px-5 py-3.5 font-medium">
                            Raw Material
                        </th>

                        <th class="px-5 py-3.5 font-medium">
                            SKU
                        </th>

                        <th class="px-5 py-3.5 font-medium">
                            Quantity
                        </th>

                        <th class="px-5 py-3.5 font-medium">
                            Unit
                        </th>
                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse($purchaseRequest->items as $item)

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-5 py-4 text-gray-400">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                        <i class="bx bx-package text-lg"></i>
                                    </div>

                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ $item->rawMaterial->name }}
                                        </p>
                                    </div>

                                </div>

                            </td>

                            <td class="px-5 py-4">

                                @if($item->rawMaterial->sku)
                                    <span class="inline-flex rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                        {{ $item->rawMaterial->sku }}
                                    </span>
                                @else
                                    <span class="text-gray-400">
                                        —
                                    </span>
                                @endif

                            </td>

                            <td class="px-5 py-4">

                                <span class="font-medium text-gray-900">
                                    {{ rtrim(rtrim(number_format($item->qty, 4, '.', ''), '0'), '.') }}
                                </span>

                            </td>

                            <td class="px-5 py-4">

                                <span class="inline-flex items-center gap-1.5 rounded-md bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-700">
                                    {{ $item->unit->name }}
                                    <span class="text-gray-400">
                                        ({{ $item->unit->short_name }})
                                    </span>
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">

                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                    <i class="bx bx-package text-2xl"></i>
                                </div>

                                <h4 class="mt-3 text-sm font-semibold text-gray-900">
                                    No raw materials
                                </h4>

                                <p class="mt-1 text-sm text-gray-500">
                                    This purchase request does not contain any items.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</x-layouts.app>