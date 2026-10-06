<x-layouts.app title="Journal Entry">

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <a
                href="{{ route('journal-entries.index') }}"
                class="mb-2 inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-900"
            >
                <i class="bx bx-arrow-back mr-1.5"></i>
                Journal Entries
            </a>

            <h1 class="text-2xl font-semibold text-gray-900">
                {{ $journalEntry->reference_no }}
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                {{ $journalEntry->description ?: 'Journal entry' }}
            </p>
        </div>

        @if($journalEntry->referenceUrl())
            <a
                href="{{ $journalEntry->referenceUrl() }}"
                class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
            >
                <i class="bx bx-link-external mr-1.5"></i>
                View Source
            </a>
        @endif

    </div>

    {{-- Header information --}}
    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Journal Entry
            </p>

            <p class="mt-1 font-semibold text-gray-900">
                {{ $journalEntry->reference_no }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Entry Date
            </p>

            <p class="mt-1 font-semibold text-gray-900">
                {{ $journalEntry->entry_date->format('d M Y') }}
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                Reference
            </p>

            @if($journalEntry->referenceUrl())

                <a
                    href="{{ $journalEntry->referenceUrl() }}"
                    class="mt-1 inline-flex items-center gap-1 font-semibold text-gray-900 hover:underline"
                >
                    {{ $journalEntry->referenceLabel() }}
                    <i class="bx bx-link-external text-sm"></i>
                </a>

            @else

                <p class="mt-1 font-semibold text-gray-400">
                    —
                </p>

            @endif
        </div>

    </div>

    {{-- Journal lines --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b border-gray-200 px-5 py-4">
            <h2 class="font-semibold text-gray-900">
                Journal Lines
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Accounts affected by this transaction.
            </p>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Account
                        </th>

                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Description
                        </th>

                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Debit
                        </th>

                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Credit
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @foreach($journalEntry->lines as $line)

                        <tr class="hover:bg-gray-50">

                            <td class="whitespace-nowrap px-5 py-4">

                                <div class="font-medium text-gray-900">
                                    {{ $line->account->name }}
                                </div>

                                <div class="mt-0.5 text-xs text-gray-500">
                                    {{ $line->account->code }}
                                </div>

                            </td>

                            <td class="px-5 py-4 text-sm text-gray-600">
                                {{ $line->description ?: '—' }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-medium text-gray-900">
                                @if((float) $line->debit > 0)
                                    {{ number_format($line->debit, 2) }}
                                @else
                                    —
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-right text-sm font-medium text-gray-900">
                                @if((float) $line->credit > 0)
                                    {{ number_format($line->credit, 2) }}
                                @else
                                    —
                                @endif
                            </td>

                        </tr>

                    @endforeach

                </tbody>

                <tfoot class="border-t border-gray-200 bg-gray-50">

                    <tr>
                        <td colspan="2" class="px-5 py-4 text-right text-sm font-semibold text-gray-900">
                            Total
                        </td>

                        <td class="px-5 py-4 text-right text-sm font-semibold text-gray-900">
                            {{ number_format($totalDebit, 2) }}
                        </td>

                        <td class="px-5 py-4 text-right text-sm font-semibold text-gray-900">
                            {{ number_format($totalCredit, 2) }}
                        </td>
                    </tr>

                </tfoot>

            </table>

        </div>

    </div>

    {{-- Balance status --}}
    <div class="mt-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">

        <div class="flex items-center gap-3">

            @if(abs($totalDebit - $totalCredit) < 0.01)

                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-green-100 text-green-600">
                    <i class="bx bx-check text-lg"></i>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-900">
                        Journal entry is balanced
                    </p>

                    <p class="text-xs text-gray-500">
                        Total debit equals total credit.
                    </p>
                </div>

            @else

                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-red-100 text-red-600">
                    <i class="bx bx-error text-lg"></i>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-900">
                        Journal entry is not balanced
                    </p>

                    <p class="text-xs text-gray-500">
                        Please review the accounting lines.
                    </p>
                </div>

            @endif

        </div>

    </div>

</x-layouts.app>