<x-layouts.app title="Quotations">

    <div class="mb-6 flex items-center justify-between">

        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Quotations
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Manage vendor quotations.
            </p>
        </div>

    </div>


    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="border-b bg-gray-50 text-xs uppercase text-gray-500">

                    <tr>
                        <th class="px-6 py-4">SNo.</th>
                        <th class="px-6 py-4">Purchase Request</th>
                        <th class="px-6 py-4">Vendor</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Items</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>

                </thead>


                <tbody
                    id="quotationTableBody"
                    class="divide-y divide-gray-100"
                >

                    @forelse($quotations as $index => $quotation)

                        <tr
                            id="quotation-row-{{ $quotation->id }}"
                            class="hover:bg-gray-50"
                        >

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $quotations->firstItem() + $index }}
                            </td>


                            <td class="px-6 py-4 text-gray-600">
                                {{ $quotation->purchaseRequest->request_number }}
                            </td>


                            <td class="px-6 py-4 text-gray-600">
                                {{ $quotation->vendor->company_name ?: $quotation->vendor->name }}
                            </td>


                            <td class="px-6 py-4 text-gray-600">
                                {{ $quotation->quotation_date->format('d M Y') }}
                            </td>


                            <td class="px-6 py-4 text-gray-600">
                                {{ $quotation->items->count() }}
                            </td>


                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ number_format($quotation->items->sum('total'), 2) }}
                            </td>


                            <td class="px-6 py-4">

                                @if($quotation->status === 'accepted')

                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Accepted
                                    </span>

                                @else

                                    <span class="inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                                        Pending
                                    </span>

                                @endif

                            </td>


                            <td class="px-6 py-4">

                                <div class="flex justify-end gap-2">

                                    <a
                                        href="{{ route('quotations.show', $quotation) }}"
                                        class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-200"
                                    >
                                        View
                                    </a>


                                    <a
                                        href="{{ route('quotations.edit', $quotation) }}"
                                        class="rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-600 transition hover:bg-blue-100 hover:text-blue-700"
                                    >
                                        Edit
                                    </a>


                                    <button
                                        type="button"
                                        class="delete-quotation rounded-lg bg-red-50 px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100 hover:text-red-700"
                                        data-id="{{ $quotation->id }}"
                                        data-url="{{ route('quotations.destroy', $quotation) }}"
                                    >
                                        Delete
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr id="emptyRow">

                            <td
                                colspan="8"
                                class="px-6 py-12 text-center text-gray-500"
                            >
                                No quotations found.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        @if($quotations->hasPages())

            <div class="border-t px-6 py-4">
                {{ $quotations->links() }}
            </div>

        @endif

    </div>


    @push('scripts')

    <script>

        $(document).ready(function () {

            $(document).on(
                'click',
                '.delete-quotation',
                function () {

                    const button = $(this);

                    const quotationId =
                        button.data('id');

                    const url =
                        button.data('url');


                    if (
                        !confirm(
                            'Are you sure you want to delete this quotation?'
                        )
                    ) {
                        return;
                    }


                    button.prop(
                        'disabled',
                        true
                    );

                    button.text(
                        'Deleting...'
                    );


                    $.ajax({

                        url: url,

                        type: 'DELETE',

                        data: {
                            _token:
                                "{{ csrf_token() }}"
                        },

                        headers: {
                            'Accept':
                                'application/json'
                        },


                        success: function (response) {

                            showToast(
                                'success',
                                response.message
                            );


                            $('#quotation-row-' + quotationId)
                                .fadeOut(
                                    300,
                                    function () {

                                        $(this).remove();


                                        if (
                                            $('#quotationTableBody tr')
                                                .length === 0
                                        ) {

                                            $('#quotationTableBody')
                                                .html(`
                                                    <tr id="emptyRow">
                                                        <td
                                                            colspan="8"
                                                            class="px-6 py-12 text-center text-gray-500"
                                                        >
                                                            No quotations found.
                                                        </td>
                                                    </tr>
                                                `);

                                        }

                                    }
                                );

                        },


                        error: function (xhr) {

                            button.prop(
                                'disabled',
                                false
                            );

                            button.text(
                                'Delete'
                            );


                            showToast(
                                'error',
                                xhr.responseJSON?.message ??
                                'Unable to delete quotation.'
                            );

                        }

                    });

                }
            );

        });

    </script>

    @endpush

</x-layouts.app>