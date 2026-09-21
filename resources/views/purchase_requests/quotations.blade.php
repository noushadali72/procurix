<x-layouts.app title="Quotations">

    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('purchase-requests.show', $pr) }}"
                        class="text-gray-400 transition hover:text-gray-700 dark:hover:text-gray-200">
                        <i class="bx bx-arrow-back text-xl"></i>
                    </a>

                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                        Quotations
                    </h1>
                </div>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Review vendor quotations for
                    <a href="{{ route('purchase-requests.show', $pr) }}"
                        class="font-medium text-gray-700 dark:text-gray-300 underline font-bold">
                        {{ $pr->request_number }}
                    </a>
                </p>
            </div>

            <div class="flex items-center gap-3">

                <span
                    class="rounded-lg bg-gray-100 px-3 py-2 text-sm text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                    {{ $pr->quotations->count() }} Quotations
                </span>

                <button type="button" id="compare-button" disabled onclick="compareQuotations()"
                    class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800 disabled:cursor-not-allowed disabled:opacity-40 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200">
                    <i class="bx bx-git-compare"></i>
                    Compare Selected
                </button>

            </div>
        </div>


        @if ($pr->quotations->isEmpty())

            {{-- Empty State --}}
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
                    No vendor quotations have been received for this purchase request.
                </p>

            </div>
        @else
            {{-- Selection Info --}}
            <div id="selection-bar"
                class="hidden items-center justify-between rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 dark:border-blue-900/50 dark:bg-blue-900/20">
                <div class="flex items-center gap-2 text-sm text-blue-700 dark:text-blue-300">
                    <i class="bx bx-info-circle text-lg"></i>

                    <span>
                        <strong id="selected-count">0</strong>
                        quotations selected for comparison.
                    </span>
                </div>

                <button type="button" onclick="clearSelection()"
                    class="text-sm font-medium text-blue-700 hover:underline dark:text-blue-300">
                    Clear
                </button>
            </div>


            {{-- Quotations --}}
            <div
                class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h2 class="font-semibold text-gray-900 dark:text-white">
                        Vendor Quotations
                    </h2>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Select multiple quotations to compare their prices and terms.
                    </p>
                </div>


                <div class="divide-y divide-gray-200 dark:divide-gray-700">

                    @foreach ($pr->quotations as $quotation)
                        <div class="quotation-row p-5 transition hover:bg-gray-50 dark:hover:bg-gray-700/30">

                            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                                {{-- Left --}}
                                <div class="flex min-w-0 items-start gap-4">

                                    <div class="pt-1">
                                        <input type="checkbox"
                                            class="quotation-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                            value="{{ $quotation->id }}">
                                    </div>

                                    <div
                                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700">
                                        <i class="bx bx-store-alt text-xl text-gray-500 dark:text-gray-300"></i>
                                    </div>

                                    <div class="min-w-0">

                                        <div class="flex flex-wrap items-center gap-2">

                                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                                {{ $quotation->vendor->company_name ?? $quotation->vendor->name }}
                                            </h3>

                                            @if($quotation->status === 'accepted')
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                                    Accepted
                                                </span>
                                            @elseif($quotation->status==='expired')
                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                                    Expired
                                                </span>
                                            @else

                                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                    Pending
                                                </span>

                                            @endif

                                        </div>

                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $quotation->quotation_number ?? 'Quotation #' . $quotation->id }}
                                        </p>

                                        <div
                                            class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-xs text-gray-500 dark:text-gray-400">

                                            <span>
                                                <i class="bx bx-package mr-1"></i>
                                                {{ $quotation->items->count() }} items
                                            </span>

                                            @if ($quotation->vendor->phone ?? false)
                                                <span>
                                                    <i class="bx bx-phone mr-1"></i>
                                                    {{ $quotation->vendor->phone }}
                                                </span>
                                            @endif

                                            @if ($quotation->quotation_date ?? false)
                                                <span>
                                                    <i class="bx bx-calendar mr-1"></i>
                                                    {{ optional($quotation->quotation_date)->format('d M Y') }}
                                                </span>
                                            @endif

                                        </div>

                                    </div>

                                </div>


                                {{-- Right --}}
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                    <div class="sm:min-w-[150px] sm:text-right">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            Total Amount
                                        </p>
                                        <p class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">
                                            {{ number_format($quotation->total ?? 0, 2) }}
                                        </p>

                                    </div>


                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('quotations.show', $quotation) }}"
                                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                            <i class="bx bx-show"></i>
                                            Details
                                        </a>
                                        @if($quotation->purchaseRequest->status!='completed')
                                        <button type="button" onclick="acceptQuotation({{ $quotation->id }})"
                                            class="inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200">
                                            <i class="bx bx-check"></i>
                                            Accept
                                        </button>
                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

            </div>

        @endif

    </div>


    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.quotation-checkbox').on('change', updateSelection);
            });

            function updateSelection() {
                const count = $('.quotation-checkbox:checked').length;
                $('#selected-count').text(count);
                $('#compare-button').prop('disabled', count < 2);
                if (count > 0) {
                    $('#selection-bar').removeClass('hidden').addClass('flex');
                } else {
                    $('#selection-bar').addClass('hidden').removeClass('flex');
                }
            }

            function clearSelection() {
                $('.quotation-checkbox').prop('checked', false);
                updateSelection();
            }

            function compareQuotations() {
                const quotations = $('.quotation-checkbox:checked')
                    .map(function() {
                        return this.value; 
                    }).get();

                if (quotations.length < 2) {
                    showToast('error', 'Select at least two quotations to compare.');
                    return;
                }

                const params = quotations.map(id => `quotations[]=${id}`).join('&');
                window.location.href = `{{ route('purchase-requests.quotations.compare', $pr) }}?${params}`;
            }

            function acceptQuotation(quotationId) {
                if (!confirm('Are you sure you want to accept this quotation?')) {
                    return;
                }
                $.ajax({
                    url: `/quotations/${quotationId}/accept`,
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
                        showToast('error', xhr.responseJSON?.message || 'Unable to accept quotation.');

                    }
                });
            }
        </script>
    @endpush

</x-layouts.app>
