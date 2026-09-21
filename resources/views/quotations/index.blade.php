<x-layouts.app title="Quotations">

    {{-- Header --}}
    <div class="mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Quotations
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Manage vendor quotations and pricing.
            </p>
        </div>
    </div>


    {{-- Quotations --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        {{-- Card Header --}}
        <div
            class="flex flex-col gap-1 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h3 class="text-sm font-semibold text-gray-900">
                    Quotation List
                </h3>

                <p class="mt-0.5 text-xs text-gray-500">
                    {{ $quotations->total() }}
                    {{ Str::plural('quotation', $quotations->total()) }}
                    registered
                </p>
            </div>

        </div>


        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[950px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wide text-gray-500">

                    <tr>

                        <th class="px-5 py-3.5 font-medium">
                            Quotation
                        </th>

                        <th class="px-5 py-3.5 font-medium">
                            Purchase Request
                        </th>

                        <th class="px-5 py-3.5 font-medium">
                            Vendor
                        </th>

                        <th class="px-5 py-3.5 font-medium">
                            Date
                        </th>

                        <th class="px-5 py-3.5 font-medium">
                            Items
                        </th>

                        <th class="px-5 py-3.5 font-medium">
                            Total
                        </th>

                        <th class="px-5 py-3.5 font-medium">
                            Status
                        </th>

                        <th class="px-5 py-3.5 text-right font-medium">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody id="quotationTableBody" class="divide-y divide-gray-100">

                    @forelse($quotations as $index => $quotation)
                        @php
                            $status = $quotation->status;

                            $statusClass = match ($status) {
                                'accepted' => 'bg-green-50 text-green-600',
                                'expired'=>'bg-red-50 text-red-600',
                                default => 'bg-amber-50 text-amber-600',
                            };

                            $statusDot = match ($status) {
                                'accepted' => 'bg-green-500',
                                'expired'=>'bg-red-500',
                                default => 'bg-amber-500',
                            };

                            $statusLabel = match ($status) {
                                'accepted' => 'Accepted',
                                'expired'=>'Expired',
                                default => 'Pending',
                            };
                        @endphp

                        <tr id="quotation-row-{{ $quotation->id }}" class="transition hover:bg-gray-50">

                            {{-- Serial Number --}}
                            <td class="px-5 py-4 text-gray-700">
                                <a href="{{ route('quotations.show',$quotation) }}" class="underline">
                                {{ $quotation->quotation_number??$index+1 }}
                                </a>
                            </td>


                            {{-- Purchase Request --}}
                            <td class="px-5 py-4">

                                <span
                                    class="inline-flex items-center gap-1.5 rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                    <a href="{{ route('purchase-requests.show',$quotation->purchaseRequest) }}">
                                        <i class="bx bx-receipt text-sm"></i>
                                        PR-{{ $quotation->purchaseRequest->request_number }}
                                    </a>
                                </span>

                            </td>


                            {{-- Vendor --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600">
                                        <i class="bx bx-store text-lg"></i>
                                    </div>

                                    <span class="font-medium text-gray-900">
                                        {{ $quotation->vendor->company_name ?: $quotation->vendor->name }}
                                    </span>

                                </div>

                            </td>


                            {{-- Date --}}
                            <td class="px-5 py-4">

                                <div>
                                    <p class="font-medium text-gray-700">
                                        {{ $quotation->quotation_date->format('d M Y') }}
                                    </p>

                                    @if ($quotation->quotation_date->format('Y-m-d') !== $quotation->created_at->format('Y-m-d'))
                                        <p class="mt-0.5 text-xs text-gray-400">
                                            Quotation date
                                        </p>
                                    @endif
                                </div>

                            </td>


                            {{-- Items --}}
                            <td class="px-5 py-4">

                                <span
                                    class="inline-flex items-center gap-1.5 rounded-md bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                    <i class="bx bx-package text-sm"></i>
                                    {{ $quotation->items->count() }}
                                    {{ Str::plural('item', $quotation->items->count()) }}
                                </span>

                            </td>


                            {{-- Total --}}
                            <td class="px-5 py-4">

                                <span class="font-semibold text-gray-900">
                                    {{ number_format($quotation->items->sum('total'), 2) }}
                                </span>

                            </td>


                            {{-- Status --}}
                            <td class="px-5 py-4">

                                <span
                                    class="{{ $statusClass }} inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium">

                                    <span class="{{ $statusDot }} h-1.5 w-1.5 rounded-full"></span>

                                    {{ $statusLabel }}

                                </span>

                            </td>


                        {{-- Actions --}}
                        <td class="px-5 py-4">

                            <div class="flex justify-end gap-2">

                                {{-- <a
                                    href="{{ route('quotations.show', $quotation) }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white"
                                >
                                    View
                                </a> --}}

                                 @if($quotation->status=='pending' && $quotation->purchaseRequest->status!='completed')
                                    {{-- Accept --}}
                                    <button data-quotation-id="{{ $quotation->id }}"
                                        class="accept-btn cursor-pointer inline-flex items-center gap-1.5 rounded-lg border border-green-300 px-3 py-1.5 text-xs font-medium text-green-700 transition hover:green-gray-900 hover:bg-green-900 hover:text-white">
                                        <i class="bx bx-edit-alt"></i>
                                        Accept
                                    </button>
                                    @endif

                                <a
                                    href="{{ route('quotations.edit', $quotation) }}"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white"
                                >
                                    Edit
                                </a>

                                <button
                                    type="button"
                                    class="delete-quotation inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-500 hover:text-white"
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

                            <td colspan="8" class="px-5 py-12 text-center">

                                <div
                                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                    <i class="bx bx-file text-2xl"></i>
                                </div>

                                <h4 class="mt-3 text-sm font-semibold text-gray-900">
                                    No quotations found
                                </h4>

                                <p class="mt-1 text-sm text-gray-500">
                                    Vendor quotations will appear here once they are created.
                                </p>

                            </td>

                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if ($quotations->hasPages())
            <div class="border-t border-gray-200 px-5 py-4">
                {{ $quotations->links() }}
            </div>
        @endif

    </div>


    @push('scripts')
        <script>
            $(document).ready(function() {

                $(document).on('click', '.delete-quotation', function() {

                    const button = $(this);
                    const quotationId = button.data('id');
                    const url = button.data('url');

                    if (!confirm('Are you sure you want to delete this quotation?')) {
                        return;
                    }

                    button.prop('disabled', true);
                    button.html(`
                    <i class="bx bx-loader-alt bx-spin text-base"></i>
                    Deleting...
                `);

                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        headers: {
                            Accept: 'application/json'
                        },

                        success: function(response) {

                            showToast(
                                'success',
                                response.message || 'Quotation deleted successfully.'
                            );

                            $('#quotation-row-' + quotationId).fadeOut(
                                300,
                                function() {

                                    $(this).remove();

                                    if ($('#quotationTableBody tr').length === 0) {

                                        $('#quotationTableBody').html(`
                                        <tr id="emptyRow">
                                            <td colspan="8" class="px-5 py-12 text-center">
                                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                                    <i class="bx bx-file text-2xl"></i>
                                                </div>

                                                <h4 class="mt-3 text-sm font-semibold text-gray-900">
                                                    No quotations found
                                                </h4>

                                                <p class="mt-1 text-sm text-gray-500">
                                                    Vendor quotations will appear here once they are created.
                                                </p>
                                            </td>
                                        </tr>
                                    `);

                                    }

                                }
                            );

                        },

                        error: function(xhr) {

                            button.prop('disabled', false);

                            button.html(`
                            <i class="bx bx-trash text-base"></i>
                            Delete
                        `);

                            showToast(
                                'error',
                                xhr.responseJSON?.message ||
                                'Unable to delete quotation.'
                            );

                        }
                    });

                });



                function accept(){
            
                const button = $(this);
                if(!confirm('Do you want to Accept the quotation?')){
                    return;
                }
                
                var quotationId = $(this).data('quotation-id');
                var url = "{{ route('quotations.accept',':id') }}";
                url = url.replace(':id',quotationId);

                button.prop('disabled',true);
                button.text('accepting...');

                $.ajax({
                   url:url,
                   type:"POST",
                   success:function(res){
                        showToast('success',res.message);
                        button.hide();
                        setTimeout(function(){
                            window.location.reload();
                        },500)
                   },
                   error:function(xhr){
                        showToast('error',xhr.responseJSON?.message || 'Unable to accept quotation.');
                   }

                });
            }

            $(document).on('click','.accept-btn',accept);


            });
        </script>
    @endpush

</x-layouts.app>
