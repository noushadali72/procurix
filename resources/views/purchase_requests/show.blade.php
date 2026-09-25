<x-layouts.app title="Purchase Request Details">

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- ====================================================== --}}
    {{-- HEADER --}}
    {{-- ====================================================== --}}

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <div class="flex items-center gap-2">

                <h2 class="text-xl font-semibold text-gray-900">
                    Purchase Request
                </h2>

                @php
                    $status = $purchaseRequest->status;

                    $headerStatusClass = match ($status) {
                        'completed' => 'bg-green-50 text-green-700 border-green-200',
                        'active' => 'bg-green-50 text-green-700 border-green-200',
                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                        default => 'bg-gray-50 text-gray-600 border-gray-200',
                    };
                @endphp

                <span
                    class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-medium
                    {{ $headerStatusClass }}">
                    {{ ucfirst($status) }}
                </span>

            </div>


            <p class="mt-1 text-sm text-gray-500">

                PR-{{ $purchaseRequest->request_number }}

                <span class="mx-1 text-gray-300">•</span>

                Raw material purchase request details.

            </p>

        </div>


        <div class="flex flex-wrap gap-2">

            <a href="{{ route('purchase-requests.edit', $purchaseRequest) }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">
                <i class="bx bx-edit text-lg"></i>

                Edit
            </a>


            <a href="{{ route('purchase-requests.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                <i class="bx bx-list-ul text-lg"></i>

                All Requests
            </a>

        </div>

    </div>


    {{-- ====================================================== --}}
    {{-- MAIN GRID --}}
    {{-- ====================================================== --}}

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">


        {{-- ================================================== --}}
        {{-- LEFT CONTENT --}}
        {{-- ================================================== --}}

        <div class="space-y-6 xl:col-span-8">


            {{-- ================================================== --}}
            {{-- REQUEST INFORMATION --}}
            {{-- ================================================== --}}

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

                <div class="border-b border-gray-200 px-5 py-4">

                    <h3 class="text-sm font-semibold text-gray-900">
                        Request Information
                    </h3>

                    <p class="mt-0.5 text-xs text-gray-500">
                        Overview of this purchase request.
                    </p>

                </div>


                <div class="grid grid-cols-1 sm:grid-cols-2">


                    {{-- Request Number --}}
                    <div class="border-b border-gray-100 p-5 sm:border-r">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                <i class="bx bx-receipt text-xl"></i>
                            </div>


                            <div>

                                <p class="text-xs font-medium text-gray-500">
                                    Request Number
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-900">
                                    PR-{{ $purchaseRequest->request_number }}
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="border-b border-gray-100 p-5">

                        @php
                            $statusClass = match ($status) {
                                'completed' => 'bg-green-50 text-green-700',
                                'active' => 'bg-green-50 text-green-700',
                                'pending' => 'bg-amber-50 text-amber-700',
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


                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                <i class="bx bx-check-circle text-xl"></i>
                            </div>


                            <div>

                                <p class="text-xs font-medium text-gray-500">
                                    Status
                                </p>


                                <span
                                    class="{{ $statusClass }} mt-1 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium">

                                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>

                                    {{ $statusLabel }}

                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- Delivery Address --}}
                    <div class="p-5 sm:border-r">

                        <div class="flex items-start gap-3">

                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                <i class="bx bx-map text-xl"></i>
                            </div>


                            <div class="min-w-0">

                                <p class="text-xs font-medium text-gray-500">
                                    Delivery Address
                                </p>

                                <p class="mt-1 text-sm font-semibold leading-5 text-gray-900">
                                    {{ $purchaseRequest->delivery_address ?? '-' }}
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Created --}}
                    <div class="border-t border-gray-100 p-5 sm:border-t-0">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                <i class="bx bx-calendar text-xl"></i>
                            </div>


                            <div>

                                <p class="text-xs font-medium text-gray-500">
                                    Created
                                </p>

                                <p class="mt-1 text-sm font-semibold text-gray-900">
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
                @if ($purchaseRequest->notes)
                    <div class="border-t border-gray-200 px-5 py-4">

                        <div class="flex items-start gap-3">

                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                <i class="bx bx-note text-lg"></i>
                            </div>


                            <div class="min-w-0">

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


            {{-- ================================================== --}}
            {{-- RAW MATERIALS --}}
            {{-- ================================================== --}}

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

                <div
                    class="flex flex-col gap-3 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

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


                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                        <i class="bx bx-package text-lg"></i>
                    </div>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full min-w-[650px] text-left text-sm">

                        <thead
                            class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wide text-gray-500">

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

                                            <div
                                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                                <i class="bx bx-package text-lg"></i>
                                            </div>


                                            <div class="min-w-0">

                                                <p class="font-medium text-gray-900">
                                                    {{ $item->rawMaterial->name }}
                                                </p>

                                            </div>

                                        </div>

                                    </td>


                                    <td class="px-5 py-4">

                                        @if ($item->rawMaterial->sku)
                                            <span
                                                class="inline-flex rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                                {{ $item->rawMaterial->sku }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">
                                                —
                                            </span>
                                        @endif

                                    </td>


                                    <td class="px-5 py-4">

                                        <span class="font-semibold text-gray-900">

                                            {{ rtrim(rtrim(number_format($item->qty, 4, '.', ''), '0'), '.') }}

                                        </span>

                                    </td>


                                    <td class="px-5 py-4">

                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-md bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-700">

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

                                        <div
                                            class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
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

        </div>


        {{-- ================================================== --}}
        {{-- RIGHT SIDE --}}
        {{-- ACTIVITY TIMELINE --}}
        {{-- ================================================== --}}

        <div class="xl:col-span-4">

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white xl:sticky xl:top-20">

                {{-- Timeline Header --}}
                <div class="border-b border-gray-200 px-5 py-4">

                    <div class="flex items-center justify-between gap-3">

                        <div class="flex items-center gap-3">

                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                <i class="bx bx-history text-lg"></i>
                            </div>


                            <div>

                                <h3 class="text-sm font-semibold text-gray-900">
                                    Activity Timeline
                                </h3>

                                <p class="mt-0.5 text-xs text-gray-500">
                                    Purchase request history
                                </p>

                            </div>

                        </div>


                        @if ($purchaseRequest->activities->count())
                            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500">
                                {{ $purchaseRequest->activities->count() }}
                            </span>
                        @endif

                    </div>

                </div>


                {{-- Timeline Body --}}
                <div class="max-h-[720px] overflow-y-auto p-5">

                    @forelse($purchaseRequest->activities as $activity)
                        @php

                            $activityIcon = match ($activity->action) {
                                'created' => 'bx-plus',
                                'updated' => 'bx-edit',
                                'submitted' => 'bx-send',
                                'approved' => 'bx-check',
                                'rejected' => 'bx-x',
                                'rfq_sent' => 'bx-envelope',
                                'quotation_received' => 'bx-file',
                                'quotation_selected' => 'bx-check-square',
                                'purchase_order_created' => 'bx-cart',
                                'completed' => 'bx-check-double',
                                default => 'bx-history',
                            };

                            $activityIconClass = match ($activity->action) {
                                'approved', 'completed' => 'border-green-200 bg-green-50 text-green-600',

                                'rejected' => 'border-red-200 bg-red-50 text-red-600',

                                'rfq_sent', 'submitted' => 'border-blue-200 bg-blue-50 text-blue-600',

                                'quotation_received',
                                'quotation_selected'
                                    => 'border-violet-200 bg-violet-50 text-violet-600',

                                'purchase_order_created' => 'border-amber-200 bg-amber-50 text-amber-600',

                                default => 'border-gray-200 bg-white text-gray-500',
                            };

                        @endphp


                        <div class="relative flex gap-4 pb-7 last:pb-0">


                            {{-- Timeline Connecting Line --}}
                            @if (!$loop->last)
                                <div class="absolute left-[15px] top-8 h-[calc(100%-1rem)] w-px bg-gray-200"></div>
                            @endif


                            {{-- Activity Icon --}}
                            <div
                                class="{{ $activityIconClass }}
                                    relative z-10 flex h-8 w-8 shrink-0
                                    items-center justify-center rounded-full border">

                                <i class="bx {{ $activityIcon }} text-sm"></i>

                            </div>


                            {{-- Activity Content --}}
                            <div class="min-w-0 flex-1">


                                {{-- Action + Time --}}
                                <div class="flex items-start justify-between gap-3">

                                    <p class="text-sm font-semibold leading-5 text-gray-900">

                                        {{ ucwords(str_replace('_', ' ', $activity->action ?? 'activity')) }}

                                    </p>


                                    <span class="shrink-0 whitespace-nowrap text-[11px] text-gray-400"
                                        title="{{ $activity->created_at->format('d M Y h:i A') }}">

                                        {{ $activity->created_at->diffForHumans() }}

                                    </span>

                                </div>


                                {{-- Description --}}
                                @if ($activity->description)
                                    <p class="mt-1 text-sm leading-5 text-gray-500">
                                        {{ $activity->description }}
                                    </p>
                                @endif


                                {{-- User --}}
                                @if ($activity->user)
                                    <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">

                                        <div
                                            class="flex h-5 w-5 items-center justify-center rounded-full bg-gray-100 text-[10px] font-semibold text-gray-600">

                                            {{ strtoupper(substr($activity->user->name, 0, 1)) }}

                                        </div>

                                        <span>
                                            {{ $activity->user->name }}
                                        </span>

                                    </div>
                                @endif


                                {{-- Vendor --}}
                                @if ($activity->vendor)
                                    <div
                                        class="mt-2 inline-flex items-center gap-1.5 rounded-md bg-gray-50 px-2 py-1 text-xs text-gray-500">

                                        <i class="bx bx-store"></i>

                                        {{ $activity->vendor->name }}

                                    </div>
                                @endif


                                {{-- Polymorphic Reference --}}
                                @if ($activity->reference)
                                    <div class="mt-2">

                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-md border border-gray-200 bg-white px-2 py-1 text-xs font-medium text-gray-600">

                                            <i class="bx bx-link text-sm"></i>

                                            {{ class_basename($activity->reference_type) }}

                                        </span>

                                    </div>
                                @endif


                                {{-- Exact Date --}}
                                <p class="mt-2 text-[11px] text-gray-400">

                                    {{ $activity->created_at->format('d M Y, h:i A') }}

                                </p>

                            </div>

                        </div>

                    @empty

                        {{-- Empty State --}}
                        <div class="py-10 text-center">

                            <div
                                class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                <i class="bx bx-history text-2xl"></i>
                            </div>


                            <h4 class="mt-3 text-sm font-semibold text-gray-900">
                                No activity yet
                            </h4>

                            <p class="mx-auto mt-1 max-w-[220px] text-xs leading-5 text-gray-500">
                                Purchase request activity will appear here as the request progresses.
                            </p>

                        </div>
                    @endforelse

                </div>

            </div>

        </div>

    </div>

</x-layouts.app>
