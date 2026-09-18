<x-layouts.app title="Purchase Orders">

    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900">
            Purchase Orders
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            View and manage purchase orders.
        </p>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Purchase Order List --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        {{-- Card Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">

            <div>
                <h3 class="font-semibold text-gray-900">
                    Purchase Order List
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    All purchase orders and their current status.
                </p>
            </div>

            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                {{ $orders->total() }} Orders
            </span>

        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[1050px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">
                    <tr class="text-xs font-medium uppercase tracking-wide text-gray-500">

                        <th class="px-6 py-3.5">
                            Order Number
                        </th>

                        <th class="px-6 py-3.5">
                            Vendor
                        </th>

                        <th class="px-6 py-3.5">
                            Order Date
                        </th>

                        <th class="px-6 py-3.5">
                            Status
                        </th>

                        <th class="px-6 py-3.5">
                            Received Date
                        </th>

                        <th class="px-6 py-3.5">
                            Bill Status
                        </th>

                        <th class="px-6 py-3.5 text-right">
                            Actions
                        </th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($orders as $order)
                        @php
                            $statusClass = match ($order->status) {
                                'received' => 'bg-green-50 text-green-700',
                                'partially_received' => 'bg-amber-50 text-amber-700',
                                'cancelled' => 'bg-red-50 text-red-700',
                                default => 'bg-gray-100 text-gray-700',
                            };

                            $statusDot = match ($order->status) {
                                'received' => 'bg-green-500',
                                'partially_received' => 'bg-amber-500',
                                'cancelled' => 'bg-red-500',
                                default => 'bg-gray-400',
                            };

                            $statusLabel = match ($order->status) {
                                'partially_received' => 'Partially Received',
                                default => ucfirst(str_replace('_', ' ', $order->status)),
                            };
                        @endphp

                        <tr class="transition hover:bg-gray-50">

                            {{-- Order Number --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                        <i class="bx bx-receipt text-lg"></i>
                                    </div>

                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $order->order_number }}
                                        </p>

                                        @if ($order->quotation)
                                            <p class="mt-0.5 text-xs text-gray-500">
                                                Quotation
                                               
                                               <a href="{{ route('quotations.show',$order->quotation) }}" class="underline">
                                                   #{{ $order->quotation->quotation_number ?? $order->quotation->id }}
                                                </a>
                                            </p>
                                        @endif
                                    </div>

                                </div>

                            </td>

                            {{-- Vendor --}}
                            <td class="px-6 py-4">

                                <p class="font-medium text-gray-900">
                                    {{ $order->vendor->company_name ?: $order->vendor->name }}
                                </p>

                            </td>

                            {{-- Order Date --}}
                            <td class="px-6 py-4 text-gray-600">

                                <div>
                                    <p>
                                        {{ $order->order_date->format('d M Y') }}
                                    </p>

                                    <p class="mt-0.5 text-xs text-gray-400">
                                        {{ $order->order_date->format('h:i A') }}
                                    </p>
                                </div>

                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">

                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>

                                    {{ $statusLabel }}
                                </span>

                            </td>

                            {{-- Received Date --}}
                            <td class="px-6 py-4 text-gray-600">

                                @if ($order->received_date)
                                    {{ $order->received_date->format('d M Y') }}
                                @else
                                    <span class="text-gray-400">
                                        Not received
                                    </span>
                                @endif

                            </td>

                            {{-- Bill Status --}}
                            <td class="px-6 py-4">

                                @if ($order->vendorBill)
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                        Generated
                                    </span>
                                @elseif ($order->status === 'received')
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                        Not Generated
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                        Not Available
                                    </span>
                                @endif

                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('purchase-orders.show', $order) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white">
                                        <i class="bx bx-show"></i>
                                        View
                                    </a>

                                    @if ($order->vendorBill)
                                        <a href="{{ route('vendor-bills.show', $order->vendorBill) }}"
                                           
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white">
                                            <i class="bx bx-file"></i>
                                            Bill
                                        </a>
                                    @elseif ($order->status === 'received')
                                        <button type="button"
                                            class="generate-bill inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white"
                                            data-url="{{ route('vendor-bills.generate', $order) }}">

                                            <i class="bx bx-receipt"></i>
                                            Generate Bill

                                        </button>
                                    @endif

                                    <button type="button"
                                        class="delete-order inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-500 hover:text-white"
                                        data-url="{{ route('purchase-orders.destroy', $order) }}">
                                        <i class="bx bx-trash"></i>
                                        Delete
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="px-6 py-12">

                                <div class="flex flex-col items-center justify-center text-center">

                                    <div
                                        class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                        <i class="bx bx-receipt text-2xl"></i>
                                    </div>

                                    <h3 class="font-medium text-gray-900">
                                        No purchase orders found
                                    </h3>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Purchase orders will appear here once they are created.
                                    </p>

                                </div>

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Pagination --}}
        @if ($orders->hasPages())
            <div class="border-t border-gray-200 px-6 py-4">
                {{ $orders->links() }}
            </div>
        @endif

    </div>

    @push('scripts')
        <script>
            $(document).on('click', '.delete-order', function() {

                const button = $(this);
                const url = button.data('url');

                if (!confirm('Are you sure you want to delete this purchase order?')) {
                    return;
                }

                button.prop('disabled', true);
                button.text('Deleting...');
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        showToast(
                            'success',
                            response.message || 'Purchase order deleted successfully.'
                        );
                        button.closest('tr').fadeOut(300, function() {
                            $(this).remove();
                        });

                    },
                    error: function(xhr) {
                        button.prop('disabled', false);
                        button.html(`
                            <i class="bx bx-trash"></i>
                            Delete
                        `);
                        showToast(
                            'error',
                            xhr.responseJSON?.message || 'Unable to delete purchase order.'
                        );
                    }
                });

            });

            $(document).on('click', '.generate-bill', function() {
                const button = $(this);
                const url = button.data('url');
                if (!confirm('Do you want to generate the vendor bill?')) {
                    return;
                }

                button.prop('disabled', true);

                button.html(`
                    <i class="bx bx-loader-alt bx-spin"></i>
                    Generating...
                `);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    headers: {
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        showToast(
                            'success',
                            response.message || 'Vendor bill generated successfully.'
                        );
                        setTimeout(function() {
                            window.location.reload();
                        }, 800);
                    },

                    error: function(xhr) {
                        button.prop('disabled', false);
                        button.html(`
                            <i class="bx bx-receipt"></i>
                            Generate Bill
                        `);
                        showToast(
                            'error',
                            xhr.responseJSON?.message ||
                            'Unable to generate vendor bill.'
                        );
                    }

                });

            });
        </script>
    @endpush

</x-layouts.app>
