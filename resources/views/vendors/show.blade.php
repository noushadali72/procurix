<x-layouts.app title="Vendor Details">

    {{-- Success --}}
    @if(session('success'))

        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>

    @endif


    {{-- Error --}}
    @if(session('error'))

        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>

    @endif


    <div class="mb-6 flex items-center justify-between">

        <div>

            <h2 class="text-xl font-semibold text-gray-900">
                {{ $vendor->name }}
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Vendor information and contact details.
            </p>

        </div>


        <div class="flex gap-2">

            <a
                href="{{ route('vendors.edit', $vendor) }}"
                class="rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-800"
            >
                Edit
            </a>

            <a
                href="{{ route('vendors.index') }}"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Back
            </a>

        </div>

    </div>


    <div class="rounded-xl border border-gray-200 bg-white shadow-sm">

        {{-- Header --}}
        <div class="border-b px-6 py-5">

            <div class="flex items-center justify-between">

                <div>

                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $vendor->name }}
                    </h3>

                    @if($vendor->company_name)

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $vendor->company_name }}
                        </p>

                    @endif

                </div>


                @if($vendor->is_active)

                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                        Active
                    </span>

                @else

                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                        Inactive
                    </span>

                @endif

            </div>

        </div>


        {{-- Information --}}
        <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

            {{-- Contact Person --}}
            <div>

                <p class="text-sm text-gray-500">
                    Contact Person
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $vendor->contact_person ?? '-' }}
                </p>

            </div>


            {{-- Email --}}
            <div>

                <p class="text-sm text-gray-500">
                    Email
                </p>

                @if($vendor->email)

                    <a
                        href="mailto:{{ $vendor->email }}"
                        class="mt-1 block font-medium text-blue-600 hover:underline"
                    >
                        {{ $vendor->email }}
                    </a>

                @else

                    <p class="mt-1 font-medium text-gray-900">
                        -
                    </p>

                @endif

            </div>


            {{-- Phone --}}
            <div>

                <p class="text-sm text-gray-500">
                    Phone
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $vendor->phone ?? '-' }}
                </p>

            </div>


            {{-- Created --}}
            <div>

                <p class="text-sm text-gray-500">
                    Added
                </p>

                <p class="mt-1 font-medium text-gray-900">
                    {{ $vendor->created_at->format('d M Y') }}
                </p>

            </div>


            {{-- Address --}}
            <div class="md:col-span-2">

                <p class="text-sm text-gray-500">
                    Address
                </p>

                <p class="mt-1 whitespace-pre-line font-medium text-gray-900">
                    {{ $vendor->address ?? '-' }}
                </p>

            </div>

        </div>

    </div>

</x-layouts.app>