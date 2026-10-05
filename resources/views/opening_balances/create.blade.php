<x-layouts.app title="Opening Balances">

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Opening Balances
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Enter the opening balances of your existing assets, liabilities, and equity.
            </p>
        </div>

        <a href="{{ route('balance-sheet.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300
            bg-white px-4 py-2.5 text-sm font-medium text-gray-700
            transition hover:border-gray-900 hover:bg-gray-50">

            <i class="bx bx-bar-chart-alt-2 text-lg"></i>

            Balance Sheet
        </a>

    </div>


    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">

            <div class="flex items-start gap-3">

                <i class="bx bx-error-circle mt-0.5 text-lg"></i>

                <div>
                    <p class="font-medium">
                        Please fix the following:
                    </p>

                    <ul class="mt-1 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>

        </div>
    @endif


    {{-- Opening Balance Form --}}
    <form method="POST" action="{{ route('opening-balances.store') }}">

        @csrf

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">

            {{-- Card Header --}}
            <div class="border-b border-gray-200 px-5 py-4">

                <h3 class="text-sm font-semibold text-gray-900">
                    Opening Balance Entry
                </h3>

                <p class="mt-0.5 text-xs text-gray-500">
                    Enter the balances that existed when accounting started.
                </p>

            </div>


            {{-- Entry Date --}}
            <div class="border-b border-gray-200 px-5 py-4">

                <div class="max-w-xs">

                    <label for="entry_date"
                        class="mb-1.5 block text-xs font-medium text-gray-600">
                        Opening Date
                    </label>

                    <input
                        type="date"
                        name="entry_date"
                        id="entry_date"
                        value="{{ old('entry_date', now()->toDateString()) }}"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5
                        text-sm text-gray-900 outline-none transition
                        focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
                    >

                </div>

            </div>


            {{-- Accounts --}}
            <div class="overflow-x-auto">

                <table class="w-full min-w-[800px] text-left text-sm">

                    <thead class="border-b border-gray-200 bg-gray-50">

                        <tr>

                            <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Account
                            </th>

                            <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Debit
                            </th>

                            <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Credit
                            </th>

                        </tr>

                    </thead>


                    <tbody id="opening-balance-lines" class="divide-y divide-gray-100">

                        @foreach ($accounts as $index => $account)

                            <tr>

                                {{-- Account --}}
                                <td class="px-5 py-3.5">

                                    <input
                                        type="hidden"
                                        name="lines[{{ $index }}][account_id]"
                                        value="{{ $account->id }}"
                                    >

                                    <div class="flex items-center gap-3">

                                        <span class="text-xs text-gray-400">
                                            {{ $account->code }}
                                        </span>

                                        <div>
                                            <div class="font-medium text-gray-900">
                                                {{ $account->name }}
                                            </div>

                                            <div class="mt-0.5 text-xs text-gray-400">
                                                {{ ucfirst($account->category->type) }}
                                            </div>
                                        </div>

                                    </div>

                                </td>


                                {{-- Debit --}}
                                <td class="px-5 py-3.5">

                                    <input
                                        type="number"
                                        name="lines[{{ $index }}][debit]"
                                        value="{{ old("lines.$index.debit") }}"
                                        min="0"
                                        step="0.01"
                                        placeholder="0.00"
                                        class="opening-debit w-full rounded-lg border border-gray-300
                                        bg-white px-3 py-2 text-right text-sm text-gray-900
                                        outline-none transition
                                        focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
                                    >

                                </td>


                                {{-- Credit --}}
                                <td class="px-5 py-3.5">

                                    <input
                                        type="number"
                                        name="lines[{{ $index }}][credit]"
                                        value="{{ old("lines.$index.credit") }}"
                                        min="0"
                                        step="0.01"
                                        placeholder="0.00"
                                        class="opening-credit w-full rounded-lg border border-gray-300
                                        bg-white px-3 py-2 text-right text-sm text-gray-900
                                        outline-none transition
                                        focus:border-gray-500 focus:ring-2 focus:ring-gray-200"
                                    >

                                </td>

                            </tr>

                        @endforeach

                    </tbody>


                    {{-- Totals --}}
                    <tfoot class="border-t border-gray-200 bg-gray-50">

                        <tr>

                            <td class="px-5 py-4 text-right font-semibold text-gray-900">
                                Total
                            </td>

                            <td class="px-5 py-4 text-right">

                                <span id="total-debit"
                                    class="font-semibold text-gray-900">
                                    0.00
                                </span>

                            </td>

                            <td class="px-5 py-4 text-right">

                                <span id="total-credit"
                                    class="font-semibold text-gray-900">
                                    0.00
                                </span>

                            </td>

                        </tr>

                        <tr>

                            <td class="px-5 pb-4 text-right text-xs text-gray-500">
                                Difference
                            </td>

                            <td colspan="2"
                                class="px-5 pb-4 text-right">

                                <span id="balance-difference"
                                    class="text-sm font-semibold text-gray-900">
                                    0.00
                                </span>

                            </td>

                        </tr>

                    </tfoot>

                </table>

            </div>


            {{-- Footer --}}
            <div class="flex flex-col gap-3 border-t border-gray-200 px-5 py-4
                sm:flex-row sm:items-center sm:justify-between">

                <p class="text-xs text-gray-500">
                    Debit and credit totals must be equal before saving.
                </p>

                <div class="flex items-center gap-2">

                    <a href="{{ route('balance-sheet.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300
                        px-4 py-2.5 text-sm font-medium text-gray-700
                        transition hover:border-gray-900 hover:bg-gray-50">

                        Cancel

                    </a>

                    <button
                        type="submit"
                        id="save-opening-balance"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-900
                        px-4 py-2.5 text-sm font-medium text-white
                        transition hover:bg-gray-800">

                        <i class="bx bx-save text-lg"></i>

                        Save Opening Balances

                    </button>

                </div>

            </div>

        </div>

    </form>


    @push('scripts')

        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const debitInputs = document.querySelectorAll('.opening-debit');
                const creditInputs = document.querySelectorAll('.opening-credit');

                const totalDebit = document.getElementById('total-debit');
                const totalCredit = document.getElementById('total-credit');
                const difference = document.getElementById('balance-difference');

                function calculateTotals() {

                    let debit = 0;
                    let credit = 0;

                    debitInputs.forEach(function (input) {
                        debit += parseFloat(input.value) || 0;
                    });

                    creditInputs.forEach(function (input) {
                        credit += parseFloat(input.value) || 0;
                    });

                    const diff = debit - credit;

                    totalDebit.textContent = debit.toFixed(2);
                    totalCredit.textContent = credit.toFixed(2);
                    difference.textContent = Math.abs(diff).toFixed(2);

                    if (Math.abs(diff) < 0.01) {
                        difference.classList.remove('text-red-600');
                        difference.classList.add('text-green-600');
                    } else {
                        difference.classList.remove('text-green-600');
                        difference.classList.add('text-red-600');
                    }
                }

                debitInputs.forEach(function (input) {
                    input.addEventListener('input', calculateTotals);
                });

                creditInputs.forEach(function (input) {
                    input.addEventListener('input', calculateTotals);
                });

                calculateTotals();
            });
        </script>

    @endpush

</x-layouts.app>