<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        sup{
            color: red;
            font-weight: bold;
            font-size: 0.85rem;
        }
    </style>

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body class="min-h-screen bg-gray-50 text-gray-900">

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-64 shrink-0 bg-gray-950 text-white">

            {{-- Logo --}}
            <div class="flex h-16 items-center border-b border-gray-800 px-6">
                <img
                    src="{{ asset('storage/logo.png') }}"
                    alt="{{ config('app.name') }}"
                    class="max-h-9 max-w-[150px] object-contain"
                >
            </div>

            {{-- Navigation --}}
            <nav class="px-3 py-5">

                <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                    Procurement
                </p>

                <div class="space-y-1">

                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('admin.dashboard')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                        <i class="bx bx-grid-alt text-[20px]"></i>
                        <span>Dashboard</span>
                    </a>

                    {{-- Products --}}
                    <a href="{{ route('products.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('products.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                        <i class="bx bx-package text-[20px]"></i>
                        <span>Products</span>
                    </a>

                    {{-- Raw Materials --}}
                    <a href="{{ route('raw-materials.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('raw-materials.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                        <i class="bx bx-cube text-[20px]"></i>
                        <span>Raw Materials</span>
                    </a>

                    {{-- Units --}}
                    <a href="{{ route('units.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('units.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                        <i class="bx bx-ruler text-[20px]"></i>
                        <span>Units</span>
                    </a>

                    {{-- Manufacturing Formulas --}}
                    <a href="{{ route('manufacturing-formulas.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('manufacturing-formulas.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                        <i class="bx bx-receipt text-[20px]"></i>
                        <span>Manufacturing Formulas</span>
                    </a>

                    {{-- Vendors --}}
                    <a href="{{ route('vendors.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('vendors.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                        <i class="bx bx-store text-[20px]"></i>
                        <span>Vendors</span>
                    </a>

                    {{-- Purchase Requests --}}
                    <a href="{{ route('purchase-requests.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('purchase-requests.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                        <i class="bx bx-file text-[20px]"></i>
                        <span>Purchase Requests</span>
                    </a>

                    {{-- Quotations --}}
                    <a href="{{ route('quotations.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('quotations.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                        <i class="bx bx-file-find text-[20px]"></i>
                        <span>Quotations</span>
                    </a>

                    {{-- Purchase Orders --}}
                    <a href="{{ route('purchase-orders.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('purchase-orders.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                        <i class="bx bx-cart text-[20px]"></i>
                        <span>Purchase Orders</span>
                    </a>

                    {{-- Goods Receipts --}}
                    <a href="{{ route('goods-receipts.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('goods-receipts.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                        <i class="bx bx-package text-[20px]"></i>
                        <span>Goods Receipts</span>
                    </a>


                </div>
            </nav>
        </aside>


        {{-- Main Content --}}
        <div class="flex min-w-0 flex-1 flex-col">

            {{-- Header --}}
            <header class="flex h-16 items-center justify-between border-b border-gray-200 bg-white px-6">

                <div>
                    <h1 class="text-lg font-semibold text-gray-900">
                        {{ $title ?? 'Dashboard' }}
                    </h1>
                </div>

                @auth
                    <div class="flex items-center gap-4">

                        {{-- User Information --}}
                        <div class="hidden text-right sm:block">
                            <p class="text-sm font-semibold text-gray-900">
                                {{ Auth::user()->name }}
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ Auth::user()->email }}
                            </p>
                        </div>

                        {{-- User Avatar --}}
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-900 text-sm font-semibold text-white">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>

                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <button
                                type="submit"
                                class="flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-500 transition hover:bg-gray-100 hover:text-gray-900"
                            >
                                <i class="bx bx-log-out text-lg"></i>
                                <span class="hidden md:inline">Logout</span>
                            </button>
                        </form>

                    </div>
                @endauth

            </header>


            {{-- Page Content --}}
            <main class="flex-1 p-5 sm:p-6">
                {{ $slot }}
            </main>

        </div>

    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/jquery@4.0.0/dist/jquery.min.js"
        integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao="
        crossorigin="anonymous">
    </script>

    <script>
    function showToast(color, message) {

        const colors = {
            success: {
                wrapper: 'border-green-200 bg-green-50 text-green-700',
                icon: 'bx-check-circle'
            },
            error: {
                wrapper: 'border-red-200 bg-red-50 text-red-700',
                icon: 'bx-error-circle'
            },
            warning: {
                wrapper: 'border-yellow-200 bg-yellow-50 text-yellow-700',
                icon: 'bx-error'
            },
            info: {
                wrapper: 'border-blue-200 bg-blue-50 text-blue-700',
                icon: 'bx-info-circle'
            }
        };

        const type = colors[color] || colors.info;

        const toast = $(`
            <div class="fixed right-5 top-5 z-[100] flex items-center gap-3 rounded-lg border px-4 py-3 text-sm shadow-lg ${type.wrapper}">
                <i class="bx ${type.icon} text-lg"></i>
                <span></span>
                <button type="button" class="ml-2 text-lg opacity-60 hover:opacity-100">
                    &times;
                </button>
            </div>
        `);

        toast.find('span').text(message);

        $('body').append(toast);

        toast.find('button').on('click', function () {
            toast.remove();
        });

        setTimeout(function () {
            toast.fadeOut(300, function () {
                $(this).remove();
            });
        }, 3000);
    }
</script>

    @stack('scripts')

</body>
</html>
