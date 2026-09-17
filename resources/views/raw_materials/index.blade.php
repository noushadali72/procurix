<x-layouts.app title="Raw Materials">

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Raw Materials
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Manage raw materials, costs, and inventory stock.
            </p>
        </div>

        <a
            href="{{ route('raw-materials.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800"
        >
            <i class="bx bx-plus text-lg"></i>
            Add Raw Material
        </a>

    </div>


    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="mb-5 flex items-center gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            <i class="bx bx-check-circle text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-5 flex items-center gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <i class="bx bx-error-circle text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif


    {{-- Raw Materials Card --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

        {{-- Card Header --}}
        <div class="flex flex-col gap-1 border-b border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-900">
                    Raw Material Inventory
                </h3>

                <p class="mt-0.5 text-xs text-gray-500">
                    {{ $rawMaterials->total() }}
                    {{ Str::plural('raw material', $rawMaterials->total()) }}
                    in inventory
                </p>
            </div>
        </div>


        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[950px] text-left text-sm">

                <thead class="border-b border-gray-200 bg-gray-50">

                    <tr>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Raw Material
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Category
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Unit
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Cost
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Stock
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Minimum
                        </th>

                        <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @forelse ($rawMaterials as $rawMaterial)

                        <tr class="transition hover:bg-gray-50">

                            {{-- Raw Material --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                                        <i class="bx bx-cube text-lg"></i>
                                    </div>

                                    <div class="min-w-0">

                                        <div class="truncate font-medium text-gray-900">
                                            {{ $rawMaterial->name }}
                                        </div>

                                        @if ($rawMaterial->description)
                                            <div class="mt-0.5 max-w-xs truncate text-xs text-gray-500">
                                                {{ $rawMaterial->description }}
                                            </div>
                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- Category --}}
                            <td class="px-5 py-4">

                                @if ($rawMaterial->category_id)

                                    <span class="rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                        {{ $rawMaterial->category->name }}
                                    </span>

                                @else
                                     <span class="rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                       Uncategorized
                                    </span>
                                @endif

                            </td>


                            {{-- Unit --}}
                            <td class="px-5 py-4">

                                <span class="text-gray-600">
                                    {{ $rawMaterial->unit->name ?? '—' }}
                                </span>

                            </td>


                            {{-- Cost --}}
                            <td class="px-5 py-4">

                                <span class="font-medium text-gray-700">
                                    {{ number_format($rawMaterial->cost_price ?? 0, 2) }}
                                </span>

                            </td>


                            {{-- Stock --}}
                            <td class="px-5 py-4">

                                @if ($rawMaterial->stock <= $rawMaterial->minimum_stock)

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>

                                        {{ number_format($rawMaterial->stock, 4) }}
                                        {{ $rawMaterial->unit->short_name ?? '' }}

                                        <span class="text-red-400">
                                            · Low
                                        </span>
                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>

                                        {{ number_format($rawMaterial->stock, 4) }}
                                        {{ $rawMaterial->unit->short_name ?? '' }}
                                    </span>

                                @endif

                            </td>


                            {{-- Minimum Stock --}}
                            <td class="px-5 py-4">

                                <span class="text-gray-600">
                                    {{ number_format($rawMaterial->minimum_stock, 4) }}
                                    {{ $rawMaterial->unit->short_name ?? '' }}
                                </span>

                            </td>


                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    <a
                                        href="{{ route('raw-materials.edit', $rawMaterial) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition hover:border-gray-900 hover:bg-gray-900 hover:text-white"
                                    >
                                        <i class="bx bx-edit-alt"></i>
                                        Edit
                                    </a>

                                    <button
                                        type="button"
                                        class="delete-raw-material-btn inline-flex cursor-pointer items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-500 hover:text-white"
                                        data-url="{{ route('raw-materials.destroy', $rawMaterial) }}"
                                        data-name="{{ $rawMaterial->name }}"
                                    >
                                        <i class="bx bx-trash"></i>
                                        Delete
                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="px-6 py-16 text-center">

                                <div class="mx-auto flex max-w-sm flex-col items-center">

                                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400">
                                        <i class="bx bx-cube text-2xl"></i>
                                    </div>

                                    <p class="mt-3 text-sm font-semibold text-gray-700">
                                        No raw materials found
                                    </p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        Add your first raw material to start managing inventory.
                                    </p>

                                    <a
                                        href="{{ route('raw-materials.create') }}"
                                        class="mt-4 inline-flex items-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-gray-800"
                                    >
                                        <i class="bx bx-plus"></i>
                                        Add Raw Material
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if ($rawMaterials->hasPages())

            <div class="border-t border-gray-200 px-5 py-4">
                {{ $rawMaterials->links() }}
            </div>

        @endif

    </div>


    @push('scripts')

        <script>
            function deleteRawMaterial(button) {
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
                        'Accept': 'application/json'
                    },

                    success: function (response) {
                        showToast(
                            'success',
                            response.message || 'Raw material deleted successfully.'
                        );

                        setTimeout(function () {
                            window.location.reload();
                        }, 800);
                    },

                    error: function (xhr) {
                        showToast(
                            'error',
                            xhr.responseJSON?.message || 'Unable to delete raw material.'
                        );

                        button.prop('disabled', false);
                    }
                });
            }

            $(document).on('click', '.delete-raw-material-btn', function () {
                deleteRawMaterial($(this));
            });
        </script>

    @endpush

</x-layouts.app>