<x-layouts.app title="Purchase Requests">

    

        <div class="mb-6 flex items-center justify-between">

            <div>

                <h1 class="text-2xl font-bold text-slate-900">
                    Purchase Requests
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Manage raw material purchase requests.
                </p>

            </div>


            <a
                href="{{ route('purchase-requests.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900"
            >
                <i class="bx bx-plus text-lg"></i>

                Add Purchase Request
            </a>

        </div>


        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-slate-50">

                        <tr class="border-b border-slate-200">

                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Request #
                            </th>

                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Status
                            </th>

                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Items
                            </th>

                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Notes
                            </th>

                            <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Date
                            </th>

                            <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($purchaseRequests as $purchaseRequest)

                            <tr class="transition hover:bg-slate-50">

                                {{-- Request Number --}}
                                <td class="px-5 py-4">

                                    <span class="font-medium text-slate-800">
                                        {{ $purchaseRequest->request_number }}
                                    </span>

                                </td>


                                {{-- Status --}}
                                <td class="px-5 py-4">

                                    @php
                                        $statusClass = match($purchaseRequest->status) {
                                            'completed' => 'bg-emerald-50 text-emerald-700',
                                            'pending' => 'bg-amber-50 text-amber-700',
                                            default => 'bg-slate-100 text-slate-700',
                                        };
                                    @endphp


                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}"
                                    >
                                        {{ ucfirst($purchaseRequest->status) }}
                                    </span>

                                </td>


                                {{-- Items --}}
                                <td class="px-5 py-4 text-sm text-slate-600">

                                    {{ $purchaseRequest->items->count() }}

                                </td>


                                {{-- Notes --}}
                                <td class="max-w-xs px-5 py-4 text-sm text-slate-600">

                                    <div class="truncate">
                                        {{ $purchaseRequest->notes ?: '-' }}
                                    </div>

                                </td>


                                {{-- Created --}}
                                <td class="px-5 py-4 text-sm text-slate-500">

                                    {{ $purchaseRequest->created_at->format('d M Y') }}

                                </td>


                                {{-- Actions --}}
                                <td class="px-5 py-4">

                                    <div class="flex items-center justify-end gap-2">

                                        {{-- View --}}
                                        <a
                                            href="{{ route('purchase-requests.show', $purchaseRequest) }}"
                                            title="View"
                                            class=" cursor-pointer rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-400 hover:text-white"
                                        >
                                           View
                                        </a>


                                        {{-- Edit --}}
                                        <a
                                            href="{{ route('purchase-requests.edit', $purchaseRequest) }}"
                                            title="Edit"
                                            class="rounded-lg border border-blue-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-blue-500 hover:text-white"
                                        >
                                           Edit
                                        </a>


                                        {{-- Delete --}}
                                        <button
                                            type="button"
                                            title="Delete"
                                            data-url="{{ route('purchase-requests.destroy', $purchaseRequest) }}"
                                            class="delete-btn cursor-pointer rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-500 hover:text-white"
                                        >
                                           Delete
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="px-5 py-12 text-center text-sm text-slate-500"
                                >
                                    No purchase requests found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            @if($purchaseRequests->hasPages())

                <div class="border-t border-slate-200 px-5 py-4">

                    {{ $purchaseRequests->links() }}

                </div>

            @endif

        </div>

    


    @push('scripts')

    <script>

        $(document).on('click', '.delete-btn', function () {

            const button = $(this);

            const url =
                button.data('url');


            if (
                !confirm(
                    'Are you sure you want to delete this purchase request?'
                )
            ) {
                return;
            }


            button.prop('disabled', true);


            $.ajax({

                url: url,

                type: 'DELETE',

                headers: {

                    'X-CSRF-TOKEN':
                        '{{ csrf_token() }}',

                    'Accept':
                        'application/json'

                },


                success: function (response) {

                    showToast(
                        'success',
                        response.message
                    );


                    setTimeout(function () {

                        window.location.reload();

                    }, 800);

                },


                error: function (xhr) {

                    button.prop('disabled', false);


                    showToast(
                        'error',
                        xhr.responseJSON?.message ??
                        'Unable to delete purchase request.'
                    );

                }

            });

        });

    </script>

    @endpush

</x-layouts.app>