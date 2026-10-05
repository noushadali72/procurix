<x-layouts.app title="Balance Sheet">

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Balance Sheet
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Financial position as of
                {{ \Carbon\Carbon::parse($asOfDate)->format('d M Y') }}
            </p>
        </div>

        {{-- Date Filter --}}
        <form method="GET" action="{{ route('balance-sheet.index') }}"
            class="flex flex-wrap items-end gap-2">

            <div>
                <label for="as_of_date"
                    class="mb-1.5 block text-xs font-medium text-gray-600">
                    As of Date
                </label>

                <input
                    type="date"
                    name="as_of_date"
                    id="as_of_date"
                    value="{{ $asOfDate }}"
                    class="rounded-lg border border-gray-300 bg-white px-3 py-2.5
                    text-sm text-gray-900 outline-none transition
                    focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
                >
            </div>

            <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-gray-900
                px-4 py-2.5 text-sm font-medium text-white transition
                hover:bg-gray-800"
            >
                <i class="bx bx-filter-alt text-lg"></i>
                Apply
            </button>
        </form>
    </div>


    {{-- Balance Status --}}
    @if ($report['is_balanced'])
        <div class="mb-5 flex items-center gap-3 rounded-lg border border-green-200
            bg-green-50 px-4 py-3 text-sm text-green-700">

            <i class="bx bx-check-circle text-lg"></i>

            <span>
                Balance Sheet is balanced.
            </span>
        </div>
    @else
        <div class="mb-5 flex items-center justify-between gap-4 rounded-lg
            border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">

            <div class="flex items-center gap-3">
                <i class="bx bx-error-circle text-lg"></i>

                <span>
                    Balance Sheet is out of balance.
                </span>
            </div>

            <span class="font-semibold">
                Difference:
                {{ number_format(abs($report['difference']), 2) }}
            </span>
        </div>
    @endif


    {{-- Main Report --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

        {{-- Assets --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

            <div class="border-b border-gray-200 px-5 py-4">
                <h3 class="text-sm font-semibold text-gray-900">
                    Assets
                </h3>

                <p class="mt-0.5 text-xs text-gray-500">
                    Resources owned by the business
                </p>
            </div>

            <div class="divide-y divide-gray-100">

                @forelse ($report['assets'] as $account)

                    <div class="flex items-center justify-between px-5 py-3.5">

                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400">
                                {{ $account['code'] }}
                            </span>

                            <span class="text-sm text-gray-700">
                                {{ $account['name'] }}
                            </span>
                        </div>

                        <span class="text-sm font-medium text-gray-900">
                            {{ number_format($account['balance'], 2) }}
                        </span>

                    </div>

                @empty

                    <div class="px-5 py-10 text-center text-sm text-gray-500">
                        No asset balances.
                    </div>

                @endforelse

            </div>

            <div class="flex items-center justify-between border-t border-gray-200
                bg-gray-50 px-5 py-4">

                <span class="text-sm font-semibold text-gray-900">
                    Total Assets
                </span>

                <span class="text-sm font-semibold text-gray-900">
                    {{ number_format($report['total_assets'], 2) }}
                </span>

            </div>
        </div>


        {{-- Liabilities & Equity --}}
        <div class="space-y-5">

            {{-- Liabilities --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

                <div class="border-b border-gray-200 px-5 py-4">
                    <h3 class="text-sm font-semibold text-gray-900">
                        Liabilities
                    </h3>

                    <p class="mt-0.5 text-xs text-gray-500">
                        Amounts owed by the business
                    </p>
                </div>

                <div class="divide-y divide-gray-100">

                    @forelse ($report['liabilities'] as $account)

                        <div class="flex items-center justify-between px-5 py-3.5">

                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-400">
                                    {{ $account['code'] }}
                                </span>

                                <span class="text-sm text-gray-700">
                                    {{ $account['name'] }}
                                </span>
                            </div>

                            <span class="text-sm font-medium text-gray-900">
                                {{ number_format($account['balance'], 2) }}
                            </span>

                        </div>

                    @empty

                        <div class="px-5 py-10 text-center text-sm text-gray-500">
                            No liability balances.
                        </div>

                    @endforelse

                </div>

                <div class="flex items-center justify-between border-t border-gray-200
                    bg-gray-50 px-5 py-4">

                    <span class="text-sm font-semibold text-gray-900">
                        Total Liabilities
                    </span>

                    <span class="text-sm font-semibold text-gray-900">
                        {{ number_format($report['total_liabilities'], 2) }}
                    </span>

                </div>
            </div>


            {{-- Equity --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

                <div class="border-b border-gray-200 px-5 py-4">
                    <h3 class="text-sm font-semibold text-gray-900">
                        Equity
                    </h3>

                    <p class="mt-0.5 text-xs text-gray-500">
                        Owner's interest in the business
                    </p>
                </div>

                <div class="divide-y divide-gray-100">

                    @forelse ($report['equity'] as $account)

                        <div class="flex items-center justify-between px-5 py-3.5">

                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-400">
                                    {{ $account['code'] }}
                                </span>

                                <span class="text-sm text-gray-700">
                                    {{ $account['name'] }}
                                </span>
                            </div>

                            <span class="text-sm font-medium text-gray-900">
                                {{ number_format($account['balance'], 2) }}
                            </span>

                        </div>

                    @empty

                        <div class="px-5 py-10 text-center text-sm text-gray-500">
                            No equity balances.
                        </div>

                    @endforelse


                    {{-- Current Earnings --}}
                    <div class="flex items-center justify-between px-5 py-3.5">

                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-400">
                                —
                            </span>

                            <span class="text-sm text-gray-700">
                                Current Earnings
                            </span>
                        </div>

                        <span class="text-sm font-medium text-gray-900">
                            {{ number_format($report['current_earnings'], 2) }}
                        </span>

                    </div>

                </div>

                <div class="flex items-center justify-between border-t border-gray-200
                    bg-gray-50 px-5 py-4">

                    <span class="text-sm font-semibold text-gray-900">
                        Total Equity
                    </span>

                    <span class="text-sm font-semibold text-gray-900">
                        {{ number_format($report['total_equity'], 2) }}
                    </span>

                </div>
            </div>

        </div>
    </div>


    {{-- Final Total --}}
    <div class="mt-5 overflow-hidden rounded-xl border border-gray-200 bg-white">

        <div class="flex items-center justify-between bg-gray-900 px-5 py-4 text-white">

            <span class="text-sm font-semibold">
                Total Liabilities + Equity
            </span>

            <span class="text-sm font-semibold">
                {{ number_format($report['total_liabilities_and_equity'], 2) }}
            </span>

        </div>

    </div>

</x-layouts.app>