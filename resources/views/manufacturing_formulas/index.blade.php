<x-layouts.app title="Manufacturing Formulas">

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                Manufacturing Formulas
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Manage product manufacturing formulas and their raw materials.
            </p>
        </div>

        <a
            href="{{ route('manufacturing-formulas.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
        >
            <i class="bx bx-plus text-lg"></i>
            Add Formula
        </a>

    </div>


    {{-- Alerts --}}
    @if (session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
            <div class="flex items-center gap-2">
                <i class="bx bx-check-circle text-base"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
            <div class="flex items-center gap-2">
                <i class="bx bx-error-circle text-base"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif


    {{-- Formula List --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">

        {{-- Card Header --}}
        <div class="flex flex-col gap-2 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700">

            <div>
                <h2 class="flex items-center gap-2 text-base font-semibold text-gray-900 dark:text-white">
                    <i class="bx bx-cog text-lg text-gray-500 dark:text-gray-400"></i>
                    Formula List
                </h2>

                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                    {{ $formulas->total() }}
                    {{ Str::plural('formula', $formulas->total()) }} found
                </p>
            </div>

        </div>


        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">

                <thead class="border-b border-gray-200 bg-gray-50 text-xs font-medium uppercase tracking-wide text-gray-500 dark:border-gray-700 dark:bg-gray-700/50 dark:text-gray-400">

                    <tr>
                        <th class="px-5 py-3.5">Formula</th>
                        <th class="px-5 py-3.5">Product</th>
                        <th class="px-5 py-3.5">Raw Materials</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                    @forelse ($formulas as $formula)

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">

                            {{-- Formula --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                        <i class="bx bx-cog text-lg"></i>
                                    </div>

                                    <div class="min-w-0">

                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $formula->name }}
                                        </p>

                                        @if ($formula->description)
                                            <p
                                                class="mt-0.5 max-w-xs truncate text-xs text-gray-500 dark:text-gray-400"
                                                title="{{ $formula->description }}"
                                            >
                                                {{ $formula->description }}
                                            </p>
                                        @else
                                            <p class="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
                                                No description
                                            </p>
                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- Product --}}
                            <td class="px-5 py-4">

                                @if ($formula->product)

                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $formula->product->name }}
                                        </p>

                                        @if ($formula->product->sku)
                                            <span class="mt-1 inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                                {{ $formula->product->sku }}
                                            </span>
                                        @endif
                                    </div>

                                @else

                                    <span class="text-gray-400 dark:text-gray-500">
                                        -
                                    </span>

                                @endif

                            </td>


                            {{-- Raw Materials --}}
                            <td class="px-5 py-4">

                                @if ($formula->items->count())

                                    <div class="flex max-w-xl flex-wrap gap-1.5">

                                        @foreach ($formula->items as $item)

                                            <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">

                                                <i class="bx bx-package text-sm text-gray-400 dark:text-gray-500"></i>

                                                {{ $item->rawMaterial->name ?? '-' }}

                                                <span class="text-gray-400 dark:text-gray-500">
                                                    × {{ $item->quantity }}
                                                    {{ $item->unit->short_name ?? '' }}
                                                </span>

                                            </span>

                                        @endforeach

                                    </div>

                                @else

                                    <span class="text-gray-400 dark:text-gray-500">
                                        No raw materials
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    <a
                                        href="{{ route('manufacturing-formulas.edit', $formula) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white dark:border-gray-600 dark:text-gray-300 dark:hover:border-gray-500 dark:hover:bg-gray-700 dark:hover:text-white"
                                    >
                                        <i class="bx bx-edit-alt"></i>
                                        Edit
                                    </a>

                                    <button
                                        type="button"
                                        class="delete-formula-btn inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-500 hover:text-white dark:border-red-800 dark:text-red-400 dark:hover:bg-red-500 dark:hover:text-white"
                                        data-url="{{ route('manufacturing-formulas.destroy', $formula) }}"
                                        data-name="{{ $formula->name }}"
                                    >
                                        <i class="bx bx-trash"></i>
                                        Delete
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="px-5 py-14">

                                <div class="flex flex-col items-center justify-center text-center">

                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500">
                                        <i class="bx bx-cog text-2xl"></i>
                                    </div>

                                    <h3 class="mt-4 text-sm font-semibold text-gray-900 dark:text-white">
                                        No manufacturing formulas found
                                    </h3>

                                    <p class="mt-1 max-w-sm text-sm text-gray-500 dark:text-gray-400">
                                        Create a manufacturing formula to define the raw materials required for a product.
                                    </p>

                                    <a
                                        href="{{ route('manufacturing-formulas.create') }}"
                                        class="mt-4 inline-flex items-center gap-1.5 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                                    >
                                        <i class="bx bx-plus"></i>
                                        Add Formula
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if ($formulas->hasPages())

            <div class="border-t border-gray-200 px-5 py-4 dark:border-gray-700">
                {{ $formulas->links() }}
            </div>

        @endif

    </div>


    @push('scripts')

        <script>
            function deleteManufacturingFormula(button) {
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
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },

                    success: function(response) {
                        showToast(
                            'success',
                            response.message || 'Manufacturing formula deleted successfully.'
                        );

                        setTimeout(function() {
                            window.location.reload();
                        }, 800);
                    },

                    error: function(xhr) {
                        showToast(
                            'error',
                            xhr.responseJSON?.message ||
                            'Unable to delete manufacturing formula.'
                        );

                        button.prop('disabled', false);
                    }
                });
            }

            $(document).on('click', '.delete-formula-btn', function() {
                deleteManufacturingFormula($(this));
            });
        </script>

    @endpush

</x-layouts.app>