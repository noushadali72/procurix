<x-layouts.app title="Goods Receipt">

    @php
        $purchaseOrder = $goodsReceipt->purchaseOrder;
        $purchaseRequest = $purchaseOrder->purchaseRequest;
    @endphp


    {{-- ====================================================== --}}
    {{-- BREADCRUMB --}}
    {{-- ====================================================== --}}

    <nav class="mb-5 flex items-center gap-2 text-xs text-gray-500">

        <a href="{{ route('admin.dashboard') }}" class="transition hover:text-gray-900">
            Dashboard
        </a>

        <i class="bx bx-chevron-right text-sm text-gray-400"></i>

        <a href="{{ route('purchase-orders.index') }}" class="transition hover:text-gray-900">
            Purchase Orders
        </a>

        <i class="bx bx-chevron-right text-sm text-gray-400"></i>

        <a href="{{ route('purchase-orders.show', $purchaseOrder) }}" class="transition hover:text-gray-900">
            {{ $purchaseOrder->order_number }}
        </a>

        <i class="bx bx-chevron-right text-sm text-gray-400"></i>

        <span class="font-medium text-gray-700">
            {{ $goodsReceipt->grn_number }}
        </span>

    </nav>


    {{-- ====================================================== --}}
    {{-- HEADER --}}
    {{-- ====================================================== --}}

    <div
        class="mb-6 flex flex-col gap-4 border-b border-gray-200 pb-5
               lg:flex-row lg:items-center lg:justify-between">

        <div>

            <div class="flex flex-wrap items-center gap-3">

                <h1 class="text-xl font-semibold tracking-tight text-gray-900">
                    {{ $goodsReceipt->grn_number }}
                </h1>

                <span
                    class="inline-flex items-center gap-1.5 rounded-full
                           border border-green-200 bg-green-50
                           px-2.5 py-1 text-xs font-medium text-green-700">

                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>

                    Received

                </span>

            </div>

            <p class="mt-1 text-sm text-gray-500">

                Goods receipt against

                <a href="{{ route('purchase-orders.show', $purchaseOrder) }}"
                    class="font-medium text-gray-700 hover:text-gray-900 hover:underline">

                    {{ $purchaseOrder->order_number }}

                </a>

            </p>

        </div>


        {{-- Actions --}}
        <div class="flex flex-wrap items-center gap-2">

            <a href="{{ route('purchase-orders.show', $purchaseOrder) }}"
                class="inline-flex items-center justify-center gap-2
                       rounded-lg border border-gray-300 bg-white
                       px-3.5 py-2 text-sm font-medium text-gray-700
                       transition hover:bg-gray-50">

                <i class="bx bx-file"></i>

                Purchase Order

            </a>


            <a href="{{ route('purchase-returns.create', $goodsReceipt) }}"
                class="inline-flex items-center justify-center gap-2
                       rounded-lg bg-gray-900 px-3.5 py-2
                       text-sm font-medium text-white
                       transition hover:bg-gray-800">

                <i class="bx bx-undo"></i>

                Return Materials

            </a>


            <button type="button" id="delete-grn"
                class="inline-flex cursor-pointer items-center
                       justify-center gap-2 rounded-lg border
                       border-red-200 bg-red-50 px-3.5 py-2
                       text-sm font-medium text-red-700
                       transition hover:bg-red-100">

                <i class="bx bx-trash"></i>

                Delete

            </button>

        </div>

    </div>


    {{-- ====================================================== --}}
    {{-- MAIN GRID --}}
    {{-- ====================================================== --}}

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">


        {{-- ================================================== --}}
        {{-- LEFT --}}
        {{-- ================================================== --}}

        <main class="space-y-5 xl:col-span-9">


            {{-- ================================================== --}}
            {{-- RECEIPT OVERVIEW --}}
            {{-- ================================================== --}}

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white">

                <div
                    class="flex items-center justify-between
                           border-b border-gray-200 px-5 py-3.5">

                    <div>

                        <h2 class="text-sm font-semibold text-gray-900">
                            Receipt Overview
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Receiving and purchase order information
                        </p>

                    </div>

                    <div
                        class="flex h-8 w-8 items-center justify-center
                               rounded-lg bg-gray-100 text-gray-500">

                        <i class="bx bx-package text-lg"></i>

                    </div>

                </div>


                <div
                    class="grid grid-cols-1 divide-y divide-gray-100
                           sm:grid-cols-2 sm:divide-x sm:divide-y-0
                           lg:grid-cols-4">

                    {{-- GRN --}}
                    <div class="p-5">

                        <p
                            class="text-[10px] font-medium uppercase
                                   tracking-wide text-gray-400">
                            GRN Number
                        </p>

                        <p class="mt-1.5 text-sm font-semibold text-gray-900">
                            {{ $goodsReceipt->grn_number }}
                        </p>

                    </div>


                    {{-- Purchase Order --}}
                    <div class="p-5">

                        <p
                            class="text-[10px] font-medium uppercase
                                   tracking-wide text-gray-400">
                            Purchase Order
                        </p>

                        <a href="{{ route('purchase-orders.show', $purchaseOrder) }}"
                            class="mt-1.5 inline-flex items-center gap-1
                                   text-sm font-semibold text-gray-900
                                   hover:underline">

                            {{ $purchaseOrder->order_number }}

                            <i class="bx bx-link-external text-xs text-gray-400"></i>

                        </a>

                    </div>


                    {{-- Vendor --}}
                    <div class="p-5">

                        <p
                            class="text-[10px] font-medium uppercase
                                   tracking-wide text-gray-400">
                            Vendor
                        </p>

                        <p
                            class="mt-1.5 truncate text-sm font-semibold
                                   text-gray-900">

                            {{ $purchaseOrder->vendor->company_name ?: $purchaseOrder->vendor->name }}

                        </p>

                    </div>


                    {{-- Date --}}
                    <div class="p-5">

                        <p
                            class="text-[10px] font-medium uppercase
                                   tracking-wide text-gray-400">
                            Received Date
                        </p>

                        <div class="mt-1.5 flex items-center gap-1.5">

                            <i class="bx bx-calendar text-gray-400"></i>

                            <p class="text-sm font-semibold text-gray-900">

                                {{ $goodsReceipt->received_date?->format('d M Y') ?? '-' }}

                            </p>

                        </div>

                    </div>

                </div>


                {{-- Notes --}}
                <div class="border-t border-gray-200 bg-gray-50/50
                           px-5 py-4">

                    <div class="flex items-start gap-3">

                        <i class="bx bx-note mt-0.5 text-lg text-gray-400"></i>

                        <div>

                            <p class="text-xs font-medium text-gray-500">
                                Receipt Notes
                            </p>

                            @if ($goodsReceipt->notes)
                                <p
                                    class="mt-1 whitespace-pre-line text-sm
                                           leading-6 text-gray-700">

                                    {{ $goodsReceipt->notes }}

                                </p>
                            @else
                                <p class="mt-1 text-sm text-gray-400">
                                    No notes were added to this receipt.
                                </p>
                            @endif

                        </div>

                    </div>

                </div>

            </section>


            {{-- ================================================== --}}
            {{-- RECEIVED MATERIALS --}}
            {{-- ================================================== --}}

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white">

                <div
                    class="flex items-center justify-between
                           border-b border-gray-200 px-5 py-4">

                    <div>

                        <h2 class="text-sm font-semibold text-gray-900">
                            Received Materials
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Materials included in this goods receipt
                        </p>

                    </div>


                    <span
                        class="rounded-full bg-gray-100 px-2.5 py-1
                               text-xs font-medium text-gray-600">

                        {{ $goodsReceipt->items->count() }}

                        {{ Str::plural('item', $goodsReceipt->items->count()) }}

                    </span>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full min-w-[720px] text-left text-sm">

                        <thead
                            class="border-b border-gray-200 bg-gray-50
                                   text-[11px] uppercase tracking-wide
                                   text-gray-500">

                            <tr>

                                <th class="px-5 py-3 font-medium">
                                    Material
                                </th>

                                <th class="px-5 py-3 text-right font-medium">
                                    Ordered
                                </th>

                                <th class="px-5 py-3 text-right font-medium">
                                    Received
                                </th>

                                <th class="px-5 py-3 text-right font-medium">
                                    Remaining
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-100">

                            @forelse($goodsReceipt->items as $item)
                                @php
                                    $orderItem = $item->purchaseOrderItem;
                                @endphp


                                <tr class="transition hover:bg-gray-50">

                                    {{-- Material --}}
                                    <td class="px-5 py-4">

                                        <div class="flex items-center gap-3">

                                            <div
                                                class="flex h-9 w-9 shrink-0
                                                       items-center justify-center
                                                       rounded-lg bg-gray-100
                                                       text-gray-500">

                                                <i class="bx bx-package text-lg"></i>

                                            </div>


                                            <div>

                                                <p
                                                    class="font-medium
                                                           text-gray-900">

                                                    {{ $orderItem->rawMaterial->name ?? '-' }}

                                                </p>


                                                @if ($orderItem->rawMaterial->sku ?? false)
                                                    <p
                                                        class="mt-0.5
                                                               text-xs
                                                               text-gray-400">

                                                        {{ $orderItem->rawMaterial->sku }}

                                                    </p>
                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Ordered --}}
                                    <td
                                        class="px-5 py-4 text-right
                                               text-gray-700">

                                        <span class="font-semibold text-gray-900">

                                            {{ $orderItem->qty }}

                                        </span>

                                        <span class="ml-1 text-xs text-gray-400">

                                            {{ $orderItem->unit->short_name ?? '' }}

                                        </span>

                                    </td>


                                    {{-- Received --}}
                                    <td class="px-5 py-4 text-right">

                                        <span
                                            class="inline-flex items-center
                                                   rounded-md bg-green-50
                                                   px-2.5 py-1 text-xs
                                                   font-semibold text-green-700">

                                            {{ $item->qty }}

                                            <span class="ml-1 font-medium">

                                                {{ $item->unit->short_name ?? '' }}

                                            </span>

                                        </span>

                                    </td>


                                    {{-- Remaining --}}
                                    <td class="px-5 py-4 text-right">

                                        @if ($item->remaining_qty > 0)
                                            <span
                                                class="inline-flex items-center
                                                       rounded-md bg-amber-50
                                                       px-2.5 py-1 text-xs
                                                       font-semibold
                                                       text-amber-700">

                                                {{ $item->remaining_qty }}

                                                <span class="ml-1 font-medium">

                                                    {{ $orderItem->unit->short_name ?? '' }}

                                                </span>

                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center
                                                       gap-1 rounded-md
                                                       bg-green-50 px-2.5 py-1
                                                       text-xs font-semibold
                                                       text-green-700">

                                                <i class="bx bx-check"></i>

                                                Complete

                                            </span>
                                        @endif

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="4" class="px-5 py-12 text-center">

                                        <div
                                            class="mx-auto flex h-10 w-10
                                                   items-center justify-center
                                                   rounded-full bg-gray-100
                                                   text-gray-400">

                                            <i class="bx bx-package text-xl"></i>

                                        </div>

                                        <p
                                            class="mt-3 text-sm font-medium
                                                   text-gray-700">

                                            No materials received

                                        </p>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


                {{-- Receipt totals --}}
                <div
                    class="grid grid-cols-1 border-t border-gray-200
                           bg-gray-50 sm:grid-cols-2">

                    <div class="px-5 py-3.5">

                        <p
                            class="text-[10px] font-medium uppercase
                                   tracking-wide text-gray-500">
                            Materials
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $goodsReceipt->items->count() }}
                        </p>

                    </div>


                    <div
                        class="border-t border-gray-200 px-5 py-3.5
                               sm:border-l sm:border-t-0 sm:text-right">

                        <p
                            class="text-[10px] font-medium uppercase
                                   tracking-wide text-gray-500">
                            Received Quantity
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ $goodsReceipt->items->sum('qty') }}
                        </p>

                    </div>

                </div>

            </section>


            {{-- ================================================== --}}
            {{-- ATTACHMENTS --}}
            {{-- ================================================== --}}

            <section class="overflow-hidden rounded-xl border border-gray-200 bg-white">

                <div
                    class="flex items-center justify-between
                           border-b border-gray-200 px-5 py-4">

                    <div>

                        <h2 class="text-sm font-semibold text-gray-900">
                            Attachments
                        </h2>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Documents attached to this goods receipt
                        </p>

                    </div>


                    <span
                        class="rounded-full bg-gray-100 px-2.5 py-1
                               text-xs font-medium text-gray-600">

                        {{ $goodsReceipt->attachments->count() }}

                    </span>

                </div>


                <div class="p-5">

                    @if ($goodsReceipt->attachments->count())

                        <div
                            class="divide-y divide-gray-100
                                   overflow-hidden rounded-lg
                                   border border-gray-200">

                            @foreach ($goodsReceipt->attachments as $attachment)
                                <div id="attachment-{{ $attachment->id }}"
                                    class="flex items-center justify-between
                                           gap-4 px-4 py-3">

                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank"
                                        class="group flex min-w-0
                                               items-center gap-3">

                                        <div
                                            class="flex h-9 w-9 shrink-0
                                                   items-center justify-center
                                                   rounded-lg bg-gray-100
                                                   text-gray-500
                                                   transition
                                                   group-hover:bg-gray-200">

                                            <i class="bx bx-file text-lg"></i>

                                        </div>


                                        <div class="min-w-0">

                                            <p
                                                class="truncate text-sm
                                                       font-medium
                                                       text-gray-800
                                                       group-hover:text-gray-950">

                                                {{ basename($attachment->file_path) }}

                                            </p>

                                            <p
                                                class="mt-0.5 flex
                                                       items-center gap-1
                                                       text-[10px]
                                                       text-gray-400">

                                                <i class="bx bx-link-external"></i>

                                                Open attachment

                                            </p>

                                        </div>

                                    </a>


                                    <button type="button"
                                        class="delete-attachment
                                               inline-flex shrink-0
                                               cursor-pointer items-center
                                               gap-1.5 rounded-md px-2.5
                                               py-1.5 text-xs font-medium
                                               text-red-600 transition
                                               hover:bg-red-50"
                                        data-url="{{ route('goods-receipt-attachments.destroy', $attachment) }}"
                                        data-id="{{ $attachment->id }}">

                                        <i class="bx bx-trash"></i>

                                        Delete

                                    </button>

                                </div>
                            @endforeach

                        </div>
                    @else
                        <div
                            class="rounded-lg border border-dashed
                                   border-gray-300 px-5 py-8 text-center">

                            <div
                                class="mx-auto flex h-10 w-10
                                       items-center justify-center
                                       rounded-full bg-gray-100
                                       text-gray-400">

                                <i class="bx bx-paperclip text-xl"></i>

                            </div>

                            <p class="mt-3 text-sm font-medium
                                       text-gray-700">

                                No attachments

                            </p>

                            <p class="mt-1 text-xs text-gray-400">
                                No documents were uploaded with this receipt.
                            </p>

                        </div>

                    @endif

                </div>

            </section>

        </main>


        {{-- ================================================== --}}
        {{-- RIGHT - ACTIVITY TIMELINE --}}
        {{-- ================================================== --}}

        <aside class="xl:col-span-3">

            <div
                class="overflow-hidden rounded-xl border border-gray-200
                       bg-white xl:sticky xl:top-20">

                {{-- Header --}}
                <div
                    class="flex items-center justify-between
                           border-b border-gray-200 px-4 py-3">

                    <div class="flex items-center gap-2.5">

                        <div
                            class="flex h-8 w-8 shrink-0 items-center
                                   justify-center rounded-lg bg-gray-100
                                   text-gray-500">

                            <i class="bx bx-history"></i>

                        </div>

                        <div>

                            <h3 class="text-sm font-semibold text-gray-900">
                                Activity
                            </h3>

                            <p class="text-[10px] text-gray-400">
                                Request timeline
                            </p>

                        </div>

                    </div>


                    <span id="activityCount"
                        class="rounded-full bg-gray-100 px-2 py-0.5
                               text-[10px] font-semibold text-gray-500">

                        {{ $purchaseRequest->activities->count() }}

                    </span>

                </div>


                {{-- Timeline --}}
                <div id="activityTimeline" class="max-h-[700px] overflow-y-auto px-4 py-3">

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
                                'goods_received' => 'bx-package',
                                'materials_received' => 'bx-package',
                                'goods_receipt_created' => 'bx-package',
                                'purchase_return_created' => 'bx-undo',
                                'completed' => 'bx-check-double',
                                default => 'bx-history',
                            };

                            $activityClass = match ($activity->action) {
                                'approved', 'completed' => 'border-green-200 bg-green-50 text-green-600',

                                'rejected' => 'border-red-200 bg-red-50 text-red-600',

                                'submitted', 'rfq_sent' => 'border-blue-200 bg-blue-50 text-blue-600',

                                'quotation_received',
                                'quotation_selected'
                                    => 'border-violet-200 bg-violet-50 text-violet-600',

                                'purchase_order_created' => 'border-amber-200 bg-amber-50 text-amber-600',

                                'goods_received',
                                'materials_received',
                                'goods_receipt_created'
                                    => 'border-teal-200 bg-teal-50 text-teal-600',

                                'purchase_return_created' => 'border-orange-200 bg-orange-50 text-orange-600',

                                default => 'border-gray-200 bg-white text-gray-500',
                            };
                        @endphp


                        <div class="relative flex gap-2.5 pb-4 last:pb-0">

                            {{-- Connector --}}
                            @if (!$loop->last)
                                <span
                                    class="absolute left-[11px] top-6
                                           h-[calc(100%-0.25rem)] w-px
                                           bg-gray-200">
                                </span>
                            @endif


                            {{-- Icon --}}
                            <div
                                class="{{ $activityClass }}
                                       relative z-10 flex h-6 w-6
                                       shrink-0 items-center justify-center
                                       rounded-full border">

                                <i
                                    class="bx {{ $activityIcon }}
                                           text-[10px]">
                                </i>

                            </div>


                            {{-- Content --}}
                            <div class="min-w-0 flex-1 pt-0.5">

                                <div
                                    class="flex items-start
                                           justify-between gap-2">

                                    <p
                                        class="text-[11px] font-semibold
                                               leading-4 text-gray-900">

                                        {{ ucwords(str_replace('_', ' ', $activity->action ?? 'activity')) }}

                                    </p>


                                    <span
                                        class="shrink-0 whitespace-nowrap
                                               text-[9px] text-gray-400">

                                        {{ $activity->created_at->diffForHumans() }}

                                    </span>

                                </div>


                                @if ($activity->description)
                                    <p
                                        class="mt-0.5 text-[11px]
                                               leading-4 text-gray-500">

                                        {{ $activity->description }}

                                    </p>
                                @endif


                                @if ($activity->vendor)
                                    <div
                                        class="mt-1 flex items-center
                                               gap-1 text-[10px]
                                               text-gray-500">

                                        <i class="bx bx-store"></i>

                                        <span class="truncate">

                                            {{ $activity->vendor->company_name ?: $activity->vendor->name }}

                                        </span>

                                    </div>
                                @endif


                                @if ($activity->user)
                                    <div
                                        class="mt-1 flex items-center
                                               gap-1 text-[10px]
                                               text-gray-400">

                                        <i class="bx bx-user"></i>

                                        <span class="truncate">
                                            {{ $activity->user->name }}
                                        </span>

                                    </div>
                                @endif

                            </div>

                        </div>


                    @empty

                        <div class="py-10 text-center">

                            <div
                                class="mx-auto flex h-9 w-9
                                       items-center justify-center
                                       rounded-full bg-gray-100
                                       text-gray-400">

                                <i class="bx bx-history text-lg"></i>

                            </div>

                            <p class="mt-2 text-xs font-medium
                                       text-gray-700">
                                No activity yet
                            </p>

                            <p class="mt-0.5 text-[10px] text-gray-400">
                                Request actions will appear here.
                            </p>

                        </div>
                    @endforelse

                </div>

            </div>

        </aside>

    </div>


    {{-- ====================================================== --}}
    {{-- EXISTING LOGIC --}}
    {{-- ====================================================== --}}

    @push('scripts')
        <script>
            $(document).ready(function() {


                /*
                 * Delete Goods Receipt
                 */
                $('#delete-grn').on('click', function() {

                    if (!confirm(
                            'Are you sure you want to delete this goods receipt?'
                        )) {
                        return;
                    }


                    const $button = $(this);


                    $button
                        .prop('disabled', true)
                        .html(
                            '<i class="bx bx-loader-alt bx-spin"></i> Deleting...'
                        );


                    $.ajax({

                        url: "{{ route('goods-receipts.destroy', $goodsReceipt) }}",

                        type: 'POST',

                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },

                        headers: {
                            Accept: 'application/json'
                        },


                        success: function(response) {

                            showToast(
                                'success',
                                response.message
                            );


                            setTimeout(function() {

                                window.location.href =
                                    response.redirect;

                            }, 500);

                        },


                        error: function(xhr) {

                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to delete goods receipt.'
                            );


                            $button
                                .prop('disabled', false)
                                .html(
                                    '<i class="bx bx-trash"></i> Delete'
                                );

                        }

                    });

                });


                /*
                 * Delete Attachment
                 */
                $('.delete-attachment').on('click', function() {

                    if (!confirm(
                            'Are you sure you want to delete this attachment?'
                        )) {
                        return;
                    }


                    const $button = $(this);

                    const url = $button.data('url');

                    const id = $button.data('id');


                    $button
                        .prop('disabled', true)
                        .html(
                            '<i class="bx bx-loader-alt bx-spin"></i> Deleting'
                        );


                    $.ajax({

                        url: url,

                        type: 'POST',

                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },

                        headers: {
                            Accept: 'application/json'
                        },


                        success: function(response) {

                            showToast(
                                'success',
                                response.message
                            );


                            $('#attachment-' + id)
                                .fadeOut(200, function() {

                                    $(this).remove();

                                });

                        },


                        error: function(xhr) {

                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to delete attachment.'
                            );


                            $button
                                .prop('disabled', false)
                                .html(
                                    '<i class="bx bx-trash"></i> Delete'
                                );

                        }

                    });

                });

            });
        </script>
    @endpush

</x-layouts.app>
