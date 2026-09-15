
<x-layouts.app title="Purchase Orders">

    <div class="mb-6 flex items-center justify-between">

        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Purchase Orders
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                View and manage purchase orders.
            </p>
        </div>

    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="border-b bg-gray-50">

                    <tr class="text-xs uppercase tracking-wide text-gray-500">

                        <th class="px-6 py-4">
                            Order Number
                        </th>

                        <th class="px-6 py-4">
                            Vendor
                        </th>

                        <th class="px-6 py-4">
                            Order Date
                        </th>

                        <th class="px-6 py-4">
                            Status
                        </th>

                        <th class="px-6 py-4">
                            Received Date
                        </th>

                        <th class="px-6 py-4 text-right">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($orders as $order)

                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4 font-semibold text-gray-900">
                                {{ $order->order_number }}
                            </td>

                            <td class="px-6 py-4 text-gray-700">
                                {{ $order->vendor->company_name ?: $order->vendor->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $order->order_date->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4">

                                @if($order->status === 'received')

                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Received
                                    </span>

                                @elseif($order->status === 'partially_received')

                                    <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                                        Partially Received
                                    </span>

                                @elseif($order->status === 'cancelled')

                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                        Cancelled
                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                        Placed
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $order->received_date?->format('d M Y') ?? '-' }}
                            </td>

                            <td class="px-6 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    <a href="{{ route('purchase-orders.show', $order) }}"
                                        class="inline-flex items-center gap-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">

                                        <i class="bx bx-show"></i>
                                        View

                                    </a>

                                   <button type="button"
                                        class="delete-order inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50"
                                        data-url="{{ route('purchase-orders.destroy', $order) }}">

                                        <i class="bx bx-trash"></i>
                                        Delete

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6"
                                class="px-6 py-12 text-center text-gray-500">

                                No purchase orders found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($orders->hasPages())

            <div class="border-t px-6 py-4">
                {{ $orders->links() }}
            </div>

        @endif

    </div>

    @push('scripts')
        <script>
                 $(document).on('click', '.delete-order', function () {

            const button = $(this);
            const url = button.data('url');

            if (!confirm('Are you sure you want to delete this purchase order?')) {
                return;
            }

            button.prop('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function (response) {

                    showToast(
                        'success',
                        response.message || 'Purchase order deleted successfully.'
                    );

                    button.closest('tr').fadeOut(300, function () {
                        $(this).remove();
                    });
                },

                error: function (xhr) {

                    button.prop('disabled', false);

                    showToast(
                        'error',
                        xhr.responseJSON?.message || 'Unable to delete purchase order.'
                    );
                }
            });

        });    
        </script>   
    @endpush

</x-layouts.app>
