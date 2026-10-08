<x-layouts.app title="Balance Sheet">

    <div class="max-w-7xl space-y-5">

        {{-- Report Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <div class="mb-1 flex items-center gap-2 text-sm text-gray-500">
                    <i class="bx bx-line-chart text-lg"></i>
                    <span>Accounting</span>
                    <i class="bx bx-chevron-right"></i>
                    <span>Reporting</span>
                </div>

                <h1 class="text-2xl font-semibold tracking-tight text-gray-900">
                    Balance Sheet
                </h1>

                <p class="mt-1 text-sm text-gray-500">
                    Financial position as of
                    <span class="font-medium text-gray-700">
                        {{ \Carbon\Carbon::parse($asOfDate)->format('d M Y') }}
                    </span>
                </p>
            </div>

            {{-- Date Filter --}}
            <form
                method="GET"
                action="{{ route('balance-sheet.index') }}"
                class="flex flex-wrap items-end gap-2"
            >
                <div>
                    <label
                        for="as_of_date"
                        class="mb-1.5 block text-xs font-medium text-gray-600"
                    >
                        As of Date
                    </label>

                    <input
                        type="date"
                        name="as_of_date"
                        id="as_of_date"
                        value="{{ $asOfDate }}"
                        required
                        class="w-full rounded-md border border-gray-300 bg-white px-3 py-2
                               text-sm text-gray-800 outline-none transition
                               focus:border-gray-500 focus:ring-2 focus:ring-gray-100
                               sm:w-44"
                    >
                </div>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-md
                           bg-gray-900 px-4 py-2 text-sm font-medium text-white
                           transition hover:bg-gray-800"
                >
                    <i class="bx bx-filter-alt text-lg"></i>
                    Apply
                </button>
            </form>

        </div>

        {{-- Balance Status --}}
        <div class="flex flex-col gap-3 rounded-md border border-gray-200
                    bg-white px-4 py-3 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-center gap-3">

                @if ($report['is_balanced'])
                    <span class="flex h-9 w-9 items-center justify-center rounded-full
                                 bg-gray-100 text-gray-700">
                        <i class="bx bx-check-circle text-xl"></i>
                    </span>

                    <div>
                        <p class="text-sm font-medium text-gray-900">
                            Balance sheet is balanced
                        </p>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Total assets equal total liabilities and equity.
                        </p>
                    </div>
                @else
                    <span class="flex h-9 w-9 items-center justify-center rounded-full
                                 bg-red-50 text-red-600">
                        <i class="bx bx-error-circle text-xl"></i>
                    </span>

                    <div>
                        <p class="text-sm font-medium text-red-700">
                            Balance sheet is out of balance
                        </p>

                        <p class="mt-0.5 text-xs text-gray-500">
                            Review your journal entries and account balances.
                        </p>
                    </div>
                @endif

            </div>

            @if (!$report['is_balanced'])
                <div class="text-sm sm:text-right">
                    <span class="text-gray-500">Difference</span>

                    <span class="ml-2 font-semibold tabular-nums text-red-600">
                        {{ number_format(abs($report['difference']), 2) }}
                    </span>
                </div>
            @endif

        </div>

        {{-- Financial Summary --}}
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

            <div class="rounded-md border border-gray-200 bg-white p-4">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-gray-500">Total Assets</p>

                    <i class="bx bx-wallet text-xl text-gray-400"></i>
                </div>

                <p class="mt-3 text-xl font-semibold tracking-tight text-gray-900
                          tabular-nums">
                    {{ number_format($report['total_assets'], 2) }}
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Resources owned by the business
                </p>
            </div>

            <div class="rounded-md border border-gray-200 bg-white p-4">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-gray-500">Total Liabilities</p>

                    <i class="bx bx-receipt text-xl text-gray-400"></i>
                </div>

                <p class="mt-3 text-xl font-semibold tracking-tight text-gray-900
                          tabular-nums">
                    {{ number_format($report['total_liabilities'], 2) }}
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Amounts owed by the business
                </p>
            </div>

            <div class="rounded-md border border-gray-200 bg-white p-4">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-gray-500">Total Equity</p>

                    <i class="bx bx-building-house text-xl text-gray-400"></i>
                </div>

                <p class="mt-3 text-xl font-semibold tracking-tight text-gray-900
                          tabular-nums">
                    {{ number_format($report['total_equity'], 2) }}
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Owner's interest plus current earnings
                </p>
            </div>

        </div>

        {{-- Balance Sheet Report --}}
        <div class="overflow-hidden rounded-md border border-gray-200 bg-white">

            {{-- Report Toolbar --}}
            <div class="flex flex-col gap-2 border-b border-gray-200 px-4 py-4
                        sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h2 class="text-base font-semibold text-gray-900">
                        Financial Position
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        As of {{ \Carbon\Carbon::parse($asOfDate)->format('d F Y') }}
                    </p>
                </div>

                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <i class="bx bx-calendar text-base"></i>
                    <span>Amounts in your accounting currency</span>
                </div>

            </div>

            {{-- Column Headings --}}
            <div class="grid grid-cols-12 border-b border-gray-200 bg-gray-50
                        px-4 py-3 text-xs font-semibold uppercase tracking-wide
                        text-gray-500 sm:px-6">

                <div class="col-span-8 sm:col-span-9">
                    Account
                </div>

                <div class="col-span-4 text-right sm:col-span-3">
                    Balance
                </div>

            </div>

            {{-- Assets --}}
            <div>

                <div class="flex items-center justify-between border-b border-gray-200
                            bg-gray-100 px-4 py-3 sm:px-6">

                    <div class="flex items-center gap-2">
                        <i class="bx bx-chevron-down text-lg text-gray-600"></i>

                        <h3 class="text-sm font-semibold text-gray-900">
                            Assets
                        </h3>
                    </div>

                    <span class="text-sm font-semibold tabular-nums text-gray-900">
                        {{ number_format($report['total_assets'], 2) }}
                    </span>

                </div>

                @forelse ($report['assets'] as $account)

                    <div class="grid grid-cols-12 items-center border-b
                                border-gray-100 px-4 py-3 transition
                                hover:bg-gray-50 sm:px-6">

                        <div class="col-span-8 flex min-w-0 items-center gap-3
                                    sm:col-span-9">

                            <span class="w-10 shrink-0 text-xs text-gray-400">
                                {{ $account['code'] }}
                            </span>

                            <span class="truncate text-sm text-gray-700">
                                {{ $account['name'] }}
                            </span>

                        </div>

                        <div class="col-span-4 text-right text-sm tabular-nums
                                    text-gray-800 sm:col-span-3">
                            {{ number_format($account['balance'], 2) }}
                        </div>

                    </div>

                @empty

                    <div class="px-6 py-6 text-center text-sm text-gray-500">
                        No asset balances for this date.
                    </div>

                @endforelse

                <div class="flex items-center justify-between border-b border-gray-200
                            bg-gray-50 px-4 py-3 sm:px-6">

                    <span class="text-sm font-semibold text-gray-800">
                        Total Assets
                    </span>

                    <span class="text-sm font-semibold tabular-nums text-gray-900">
                        {{ number_format($report['total_assets'], 2) }}
                    </span>

                </div>

            </div>

            {{-- Liabilities --}}
            <div>

                <div class="flex items-center justify-between border-b border-gray-200
                            bg-gray-100 px-4 py-3 sm:px-6">

                    <div class="flex items-center gap-2">
                        <i class="bx bx-chevron-down text-lg text-gray-600"></i>

                        <h3 class="text-sm font-semibold text-gray-900">
                            Liabilities
                        </h3>
                    </div>

                    <span class="text-sm font-semibold tabular-nums text-gray-900">
                        {{ number_format($report['total_liabilities'], 2) }}
                    </span>

                </div>

                @forelse ($report['liabilities'] as $account)

                    <div class="grid grid-cols-12 items-center border-b
                                border-gray-100 px-4 py-3 transition
                                hover:bg-gray-50 sm:px-6">

                        <div class="col-span-8 flex min-w-0 items-center gap-3
                                    sm:col-span-9">

                            <span class="w-10 shrink-0 text-xs text-gray-400">
                                {{ $account['code'] }}
                            </span>

                            <span class="truncate text-sm text-gray-700">
                                {{ $account['name'] }}
                            </span>

                        </div>

                        <div class="col-span-4 text-right text-sm tabular-nums
                                    text-gray-800 sm:col-span-3">
                            {{ number_format($account['balance'], 2) }}
                        </div>

                    </div>

                @empty

                    <div class="px-6 py-6 text-center text-sm text-gray-500">
                        No liability balances for this date.
                    </div>

                @endforelse

                <div class="flex items-center justify-between border-b border-gray-200
                            bg-gray-50 px-4 py-3 sm:px-6">

                    <span class="text-sm font-semibold text-gray-800">
                        Total Liabilities
                    </span>

                    <span class="text-sm font-semibold tabular-nums text-gray-900">
                        {{ number_format($report['total_liabilities'], 2) }}
                    </span>

                </div>

            </div>

            {{-- Equity --}}
            <div>

                <div class="flex items-center justify-between border-b border-gray-200
                            bg-gray-100 px-4 py-3 sm:px-6">

                    <div class="flex items-center gap-2">
                        <i class="bx bx-chevron-down text-lg text-gray-600"></i>

                        <h3 class="text-sm font-semibold text-gray-900">
                            Equity
                        </h3>
                    </div>

                    <span class="text-sm font-semibold tabular-nums text-gray-900">
                        {{ number_format($report['total_equity'], 2) }}
                    </span>

                </div>

                @forelse ($report['equity'] as $account)

                    <div class="grid grid-cols-12 items-center border-b
                                border-gray-100 px-4 py-3 transition
                                hover:bg-gray-50 sm:px-6">

                        <div class="col-span-8 flex min-w-0 items-center gap-3
                                    sm:col-span-9">

                            <span class="w-10 shrink-0 text-xs text-gray-400">
                                {{ $account['code'] }}
                            </span>

                            <span class="truncate text-sm text-gray-700">
                                {{ $account['name'] }}
                            </span>

                        </div>

                        <div class="col-span-4 text-right text-sm tabular-nums
                                    text-gray-800 sm:col-span-3">
                            {{ number_format($account['balance'], 2) }}
                        </div>

                    </div>

                @empty

                    <div class="px-6 py-4 text-center text-sm text-gray-500">
                        No equity account balances for this date.
                    </div>

                @endforelse

                {{-- Current Earnings --}}
                <div class="grid grid-cols-12 items-center border-b border-gray-100
                            px-4 py-3 transition hover:bg-gray-50 sm:px-6">

                    <div class="col-span-8 flex min-w-0 items-center gap-3
                                sm:col-span-9">

                        <span class="w-10 shrink-0 text-xs text-gray-400">
                            —
                        </span>

                        <div class="min-w-0">
                            <p class="text-sm text-gray-700">
                                Current Earnings
                            </p>

                            <p class="mt-0.5 text-xs text-gray-400">
                                Income minus expenses
                            </p>
                        </div>

                    </div>

                    <div class="col-span-4 text-right text-sm tabular-nums
                                text-gray-800 sm:col-span-3">
                        {{ number_format($report['current_earnings'], 2) }}
                    </div>

                </div>

                <div class="flex items-center justify-between border-b border-gray-200
                            bg-gray-50 px-4 py-3 sm:px-6">

                    <span class="text-sm font-semibold text-gray-800">
                        Total Equity
                    </span>

                    <span class="text-sm font-semibold tabular-nums text-gray-900">
                        {{ number_format($report['total_equity'], 2) }}
                    </span>

                </div>

            </div>

            {{-- Final Total --}}
            <div class="border-t-2 border-gray-300 bg-white px-4 py-5 sm:px-6">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center
                            sm:justify-between">

                    <div>
                        <p class="text-base font-semibold text-gray-900">
                            Total Liabilities & Equity
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Liabilities plus owner's equity and current earnings
                        </p>
                    </div>

                    <p class="text-xl font-semibold tracking-tight tabular-nums
                              text-gray-900">
                        {{ number_format($report['total_liabilities_and_equity'], 2) }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Bottom Note --}}
        <div class="flex items-start gap-2 px-1 text-xs leading-5 text-gray-500">
            <i class="bx bx-info-circle mt-0.5 text-base"></i>

            <p>
                This report includes journal entries dated on or before
                {{ \Carbon\Carbon::parse($asOfDate)->format('d M Y') }}.
                Current earnings are calculated from income less expenses.
            </p>
        </div>

    </div>

</x-layouts.app>