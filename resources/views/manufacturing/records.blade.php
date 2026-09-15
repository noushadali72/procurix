<x-layouts.app title="Manufacturing Records">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                Manufacturing Records
            </h1>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                View completed manufacturing records.
            </p>
        </div>

        <a href="{{ route('manufacturing.index') }}"
           class="rounded-lg bg-primary px-4 py-2.5 text-sm font-medium text-white">
            Manufacture Product
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">

                <thead class="bg-gray-50 text-xs uppercase text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Product</th>
                        <th class="px-6 py-3">Formula</th>
                        <th class="px-6 py-3">Quantity</th>
                        <th class="px-6 py-3">Unit</th>
                        <th class="px-6 py-3">Manufactured At</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                    @forelse ($records as $record)

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">

                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                {{ $record->id }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $record->product->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $record->manufacturingFormula->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ rtrim(rtrim(number_format($record->quantity, 4, '.', ''), '0'), '.') }}
                            </td>

                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $record->unit->short_name }}
                            </td>

                            <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                {{ $record->manufactured_at->format('d M Y, h:i A') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6"
                                class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                No manufacturing records found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>
        </div>

        @if ($records->hasPages())
            <div class="border-t border-gray-200 px-6 py-4 dark:border-gray-700">
                {{ $records->links() }}
            </div>
        @endif

    </div>

</x-layouts.app>