<x-layouts.app title="Purchase Request Details">

    @if(session('success'))

        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>

    @endif


    <div class="mb-6 flex items-center justify-between">

        <div>

            <h2 class="text-xl font-semibold text-gray-900">
                Purchase Request
                #{{ $purchaseRequest->request_number }}
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Raw material purchase request details.
            </p>

        </div>


        <div class="flex gap-2">

            <a
                href="{{ route('purchase-requests.edit', $purchaseRequest) }}"
                class="rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-800"
            >
                Edit
            </a>

            <a
                href="{{ route('purchase-requests.index') }}"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Back
            </a>

        </div>

    </div>


    {{-- Request Information --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="grid grid-cols-1 divide-y divide-gray-100 md:grid-cols-3 md:divide-x md:divide-y-0">

            <div class="p-6">

                <p class="text-sm text-gray-500">
                    Request Number
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    PR-{{ $purchaseRequest->request_number }}
                </p>

            </div>


            <div class="p-6">

                <p class="text-sm text-gray-500">
                    Status
                </p>

                <div class="mt-2">

                    @if($purchaseRequest->status === 'completed')

                        <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                            Completed
                        </span>

                    @elseif($purchaseRequest->status === 'active')

                           <span class="rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                            Active
                        </span>


                    @else
                        
                        <span class="rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-medium text-yellow-700">
                            Pending
                        </span>
                 
                    @endif

                </div>

            </div>


            <div class="p-6">

                <p class="text-sm text-gray-500">
                    Created
                </p>

                <p class="mt-1 font-semibold text-gray-900">
                    {{ $purchaseRequest->created_at->format('d M Y') }}
                </p>

            </div>

        </div>


        @if($purchaseRequest->notes)

            <div class="border-t border-gray-100 p-6">

                <p class="text-sm text-gray-500">
                    Notes
                </p>

                <p class="mt-2 whitespace-pre-line text-sm text-gray-900">
                    {{ $purchaseRequest->notes }}
                </p>

            </div>

        @endif

    </div>


    {{-- Items --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="border-b px-6 py-4 flex items-center justify-between">

            <h3 class="font-semibold text-gray-900">
                Raw Materials
            </h3>
            @if($purchaseRequest->status=='active')
            <div class="flex gap-2">

                <a href="{{ route('quotations.create', $purchaseRequest) }}" class="flex gap-2  items-center rounded-lg bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-800">
                    <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"  
                    fill="currentColor" viewBox="0 0 24 24" >
                    <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                    <path d="M20 7h-.69a3.05 3.05 0 0 0-.21-4.1c-1.16-1.16-3.19-1.16-4.35 0l-2.04 2.04C12.07 3.23 10.44 2 8.5 2 6.02 2 4 4.02 4 6.5c0 .17 0 .34.03.5H4c-.52 0-.95.4-1 .92l-.91 10.92a2.007 2.007 0 0 0 1.99 2.17h15.83a2.007 2.007 0 0 0 1.99-2.17l-.91-10.92c-.04-.52-.48-.92-1-.92Zm-3.84-2.68c.41-.41 1.12-.41 1.53 0 .2.2.32.47.32.76s-.11.56-.32.76L16.53 7h-3.05zM6 6.5a2.5 2.5 0 0 1 5 0c0 .14-.01.29-.05.46 0 .01-.01.03-.01.04H6.05C6.02 6.84 6 6.67 6 6.5M4.09 19l.83-10h14.16l.83 10z"></path><path d="M12 14c-1.65 0-3-1.35-3-3H7c0 2.76 2.24 5 5 5s5-2.24 5-5h-2c0 1.65-1.35 3-3 3"></path>
                    </svg>
                   <span>
                       Request for Quote
                </span>
                </a>
            </div>
            @endif
        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="border-b bg-gray-50 text-xs uppercase text-gray-500">

                    <tr>

                        <th class="px-6 py-4">
                            #
                        </th>

                        <th class="px-6 py-4">
                            Raw Material
                        </th>

                        <th class="px-6 py-4">
                            SKU
                        </th>

                        <th class="px-6 py-4">
                            Quantity
                        </th>

                        <th class="px-6 py-4">
                            Unit
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-gray-100">

                    @foreach($purchaseRequest->items as $item)

                        <tr>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $item->rawMaterial->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $item->rawMaterial->sku ?? '-' }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $item->qty }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $item->unit->name }}
                                ({{ $item->unit->short_name }})
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</x-layouts.app>