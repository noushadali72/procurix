<x-layouts.app title="Purchase Requests">

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Purchase Requests
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Manage requests for raw material purchases.
            </p>
        </div>

        <a href="{{ route('purchase-requests.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">
            <i class="bx bx-plus text-lg"></i>
            Add Purchase Request
        </a>

    </div>


    {{-- Purchase Requests Card --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        {{-- Card Header --}}
        <div
            class="flex flex-col gap-1 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h3 class="text-sm font-semibold text-gray-900">
                    Purchase Request List
                </h3>

                <p class="mt-0.5 text-xs text-gray-500">
                    {{ $purchaseRequests->total() }}
                    {{ Str::plural('request', $purchaseRequests->total()) }}
                    registered
                </p>
            </div>

        </div>


        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">

                    <tr>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Request
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Vendor
                        </th>


                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Status
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Items
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Notes
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Date
                        </th>



                        <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse ($purchaseRequests as $purchaseRequest)
                        <tr class="transition hover:bg-gray-50">

                            {{-- Request --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                                        <i class="bx bx-clipboard text-lg"></i>
                                    </div>

                                    <div>
                                        <a href="{{ route('purchase-requests.show', $purchaseRequest) }}">
                                            <div class="font-medium text-gray-900">
                                                {{ $purchaseRequest->request_number }}
                                            </div>

                                            <div class="mt-0.5 text-xs text-gray-500">
                                                Purchase request
                                            </div>
                                        </a>
                                    </div>

                                </div>

                            </td>

                            {{-- Vendor --}}
                            <td class="max-w-xs px-5 py-4">

                                @if ($purchaseRequest->vendor)
                                    <div class="truncate text-sm text-gray-600" title="{{ $purchaseRequest->notes }}">
                                        {{ $purchaseRequest->vendor->name }}
                                    </div>
                                @else
                                    <span class="text-gray-400">
                                        —
                                    </span>
                                @endif

                            </td>




                            {{-- Status --}}
                            <td class="px-5 py-4 status">

                                @php
                                    $status = $purchaseRequest->status;

                                    $statusClass = match ($status) {
                                        'completed' => 'bg-green-50 text-green-600',
                                        'pending' => 'bg-amber-50 text-amber-600',
                                        'active' => 'bg-blue-100 text-blue-700',
                                        'sent' => 'bg-blue-100 text-blue-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        'partially_closed' => 'bg-yellow-100 text-yellow-700',
                                        default => 'bg-gray-100 text-gray-600',
                                    };

                                    $statusDot = match ($status) {
                                        'completed' => 'bg-green-500',
                                        'pending' => 'bg-amber-500',
                                        'active' => 'bg-blue-500',
                                        'sent' => 'bg-blue-500',
                                        'cancelled' => 'bg-red-500',
                                        'partially_closed' => 'bg-yellow-500',
                                        default => 'bg-gray-400',
                                    };
                                @endphp

                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>

                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>

                            </td>


                            {{-- Items --}}
                            <td class="px-5 py-4">

                                <span
                                    class="inline-flex items-center gap-1.5 rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                    <i class="bx bx-package"></i>
                                    {{ $purchaseRequest->items->count() }}
                                </span>

                            </td>


                            {{-- Notes --}}
                            <td class="max-w-xs px-5 py-4">

                                @if ($purchaseRequest->notes)
                                    <div class="truncate text-sm text-gray-600" title="{{ $purchaseRequest->notes }}">
                                        {{ $purchaseRequest->notes }}
                                    </div>
                                @else
                                    <span class="text-gray-400">
                                        —
                                    </span>
                                @endif

                            </td>


                            {{-- Date --}}
                            <td class="px-5 py-4">

                                <div class="text-sm text-gray-700">
                                    {{ $purchaseRequest->created_at->format('d M Y') }}
                                </div>

                                <div class="mt-0.5 text-xs text-gray-400">
                                    {{ $purchaseRequest->created_at->format('h:i A') }}
                                </div>

                            </td>




                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    {{-- View
                                    <a href="{{ route('purchase-requests.show', $purchaseRequest) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white">
                                        <i class="bx bx-show"></i>
                                        View
                                    </a> --}}

                                    @if ($purchaseRequest->status == 'pending')
                                        {{-- Approve --}}
                                        <button data-purchase-request-id="{{ $purchaseRequest->id }}"
                                            class="approve-btn cursor-pointer inline-flex items-center gap-1.5 rounded-lg border border-green-900 px-3 py-1.5 text-xs font-medium text-green-700 transition hover:bg-green-900 hover:text-white">
                                            <i class="bx bx-edit-alt"></i>
                                            Approve
                                        </button>
                                    @endif

                                    @if(!in_array($purchaseRequest->status,['completed','draft']))

                                    
                                    <button type="button" class="duplicate-pr cursor-pointer" data-id="{{ $purchaseRequest->id }}">
                                        <i class="bx bx-copy"></i>
                                        Duplicate
                                    </button>
                                    @endif

                                    {{-- Edit --}}
                                    <a href="{{ route('purchase-requests.edit', $purchaseRequest) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white">
                                        <i class="bx bx-edit-alt"></i>
                                        Edit
                                    </a>


                                    {{-- Delete --}}
                                    <button type="button"
                                        class="delete-btn inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-500 hover:text-white"
                                        data-url="{{ route('purchase-requests.destroy', $purchaseRequest) }}"
                                        data-name="{{ $purchaseRequest->request_number }}">
                                        <i class="bx bx-trash"></i>
                                        Delete
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="px-6 py-16 text-center">

                                <div class="mx-auto flex max-w-sm flex-col items-center">

                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                        <i class="bx bx-clipboard text-2xl"></i>
                                    </div>

                                    <p class="mt-3 text-sm font-semibold text-gray-700">
                                        No purchase requests found
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Create your first purchase request to get started.
                                    </p>

                                    <a href="{{ route('purchase-requests.create') }}"
                                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-800">
                                        <i class="bx bx-plus"></i>
                                        Add Purchase Request
                                    </a>

                                </div>

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if ($purchaseRequests->hasPages())
            <div class="border-t border-gray-200 px-5 py-4">
                {{ $purchaseRequests->links() }}
            </div>
        @endif

    </div>


    @push('scripts')
        <script>
            $(document).on('click', '.delete-btn', function() {
                const button = $(this);
                const url = button.data('url');
                const name = button.data('name');
                if (!confirm(`Are you sure you want to delete "${name}"?`)) {
                    return;
                }
                button.prop('disabled', true);

                $.ajax({
                    url: url,
                    type: 'DELETE',

                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },

                    success: function(response) {
                        showToast(
                            'success',
                            response.message || 'Purchase request deleted successfully.'
                        );
                        setTimeout(function() {
                            window.location.reload();
                        }, 800);

                    },

                    error: function(xhr) {
                        button.prop('disabled', false);
                        showToast(
                            'error',
                            xhr.responseJSON?.message ||
                            'Unable to delete purchase request.'
                        );

                    }
                });

            });

            function approve() {

                const button = $(this);
                if (!confirm('Do you want to approve the Purchase Request?')) {
                    return;
                }

                var purchaseRequestId = $(this).data('purchase-request-id');
                var url = "{{ route('purchase-requests.updateStatus', ':id') }}";
                url = url.replace(':id', purchaseRequestId);

                button.prop('disabled', true);
                button.text('approving...');

                $.ajax({
                    url: url,
                    type: "POST",
                    success: function(res) {
                        showToast('success', res.message);
                        button.hide();
                        button.closest('tr').find('.status').html(`
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium bg-blue-100 text-blue-700">
                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                    Active
                                </span>
                        `);
                    },
                    error: function(xhr) {
                        showToast('error', xhr.responseJSON?.message || 'Unable to update the status.');
                    }

                });
            }

            $(document).on('click', '.approve-btn', approve);


            // Duplicate pr

            $(document).on('click', '.duplicate-pr', function() {
                const id = $(this).data('id');

                $.ajax({
                    url: `/purchase-requests/${id}/duplicate`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        showToast('success', response.message);

                        setTimeout(() => {
                            window.location.href = response.redirect;
                        }, 500);
                    },
                    error: function(xhr) {
                        showToast(
                            'error',
                            xhr.responseJSON?.message || 'Unable to duplicate purchase request.'
                        );
                    }
                });
            });
        </script>
    @endpush

</x-layouts.app>
