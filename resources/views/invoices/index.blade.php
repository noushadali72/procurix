<x-layouts.app title="Invoices">

    <div class="mb-6 flex items-center justify-between">

        <div>
            <h2 class="text-xl font-semibold text-gray-900">
                Invoices
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Manage product and raw material invoices.
            </p>
        </div>

        <a
            href="{{ route('invoices.create') }}"
            class="rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-800"
        >
            + Add Invoice
        </a>

    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="border-b bg-gray-50 text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Invoice</th>
                        <th class="px-6 py-4">Type</th>
                        <th class="px-6 py-4">Party</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4">Items</th>
                        <th class="px-6 py-4">Total</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse ($invoices as $invoice)

                        <tr class="hover:bg-gray-50">

                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">
                                    #{{ $invoice->invoice_number }}
                                </div>
                            </td>

                            <td class="px-6 py-4">

                                @if ($invoice->type === 'products')

                                    <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                                        Products
                                    </span>

                                @else

                                    <span class="rounded-full bg-purple-100 px-2.5 py-1 text-xs font-medium text-purple-700">
                                        Raw Material
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4 text-gray-700">
                                {{ $invoice->party_name }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $invoice->invoice_date->format('d M Y') }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $invoice->items->count() }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ number_format($invoice->total, 2) }}
                            </td>

                            <td class="px-6 py-4">

                                @if ($invoice->status === 'paid')

                                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                        Paid
                                    </span>

                                @elseif ($invoice->status === 'unpaid')

                                    <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700">
                                        Unpaid
                                    </span>

                                @else

                                    <span class="rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-medium text-yellow-700">
                                        Pending
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    <a
                                        href="{{ route('invoices.edit', $invoice) }}"
                                        class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('invoices.destroy', $invoice) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this invoice?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50"
                                        >
                                            Delete
                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td
                                colspan="8"
                                class="px-6 py-12 text-center text-sm text-gray-500"
                            >
                                No invoices found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if ($invoices->hasPages())
            <div class="border-t px-6 py-4">
                {{ $invoices->links() }}
            </div>
        @endif

    </div>

</x-layouts.app>