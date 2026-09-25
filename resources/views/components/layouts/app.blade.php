<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('storage/favicon.png') }}" type="image/x-icon">

    <title>
        {{ $title ?? config('app.name') }}
    </title>


    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">


    @stack('styles')


    <style>
        sup {
            color: red;
            font-weight: 700;
            font-size: 0.85rem;
        }


        /*
         * Sidebar scrollbar
         */
        .app-sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: #374151 transparent;
        }

        .app-sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .app-sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .app-sidebar-scroll::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 999px;
        }

        .app-sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #4b5563;
        }
    </style>

</head>


<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">


    {{-- ====================================================== --}}
    {{-- SIDEBAR --}}
    {{-- ====================================================== --}}

    <aside
        class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col
               border-r border-gray-800 bg-gray-950 text-white">


        {{-- ================================================== --}}
        {{-- LOGO --}}
        {{-- ================================================== --}}

        <div class="flex h-16 shrink-0 items-center
                   border-b border-gray-800 px-5">

            <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 items-center">

                <img src="{{ asset('storage/logo.png') }}" alt="{{ config('app.name') }}"
                    class="max-h-9 max-w-[175px] object-contain">

            </a>

        </div>


        {{-- ================================================== --}}
        {{-- NAVIGATION --}}
        {{-- ================================================== --}}

        <nav class="app-sidebar-scroll flex-1 overflow-y-auto
                   px-3 py-4">


            {{-- ================================================== --}}
            {{-- DASHBOARD --}}
            {{-- ================================================== --}}

            <div class="mb-5">

                <a href="{{ route('admin.dashboard') }}"
                    class="group flex items-center gap-3 rounded-lg
                           px-3 py-2.5 text-sm font-medium transition

                    {{ request()->routeIs('admin.dashboard')
                        ? 'bg-white/10 text-white'
                        : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                    <i class="bx bx-grid-alt w-5 text-center
                               text-[19px]">
                    </i>

                    <span>
                        Dashboard
                    </span>

                </a>

            </div>


            {{-- ================================================== --}}
            {{-- INVENTORY --}}
            {{-- ================================================== --}}

            <div class="mb-5">

                <p
                    class="mb-2 px-3 text-[10px] font-semibold
                           uppercase tracking-[0.14em] text-gray-600">

                    Inventory

                </p>


                <div class="space-y-0.5">


                    {{-- Inventories --}}
                    <button type="button" id="inventoryMenuBtn"
                        class="group flex w-full cursor-pointer
                               items-center justify-between rounded-lg
                               px-3 py-2.5 text-sm font-medium
                               text-gray-400 transition
                               hover:bg-white/[0.06] hover:text-white">

                        <span class="flex min-w-0 items-center gap-3">

                            <i class="bx bx-package w-5 text-center
                                       text-[19px]">
                            </i>

                            <span>
                                Inventories
                            </span>

                        </span>


                        <i id="inventoryMenuIcon"
                            class="bx bx-chevron-down text-base
                                   transition-transform duration-200">
                        </i>

                    </button>


                    {{-- Inventory Submenu --}}
                    <div id="inventoryMenu"
                        class="mt-1 hidden space-y-0.5
                               border-l border-gray-800
                               pl-3 ml-5">


                        {{-- Products --}}
                        <a href="{{ route('products.index') }}"
                            class="flex items-center gap-2.5 rounded-lg
                                   px-3 py-2 text-[13px] font-medium
                                   transition

                            {{ request()->routeIs('products.*')
                                ? 'bg-white/10 text-white'
                                : 'text-gray-500 hover:bg-white/[0.06] hover:text-gray-200' }}">

                            <i class="bx bx-box text-base"></i>

                            <span>
                                Products
                            </span>

                        </a>


                        {{-- Raw Materials --}}
                        <a href="{{ route('raw-materials.index') }}"
                            class="flex items-center gap-2.5 rounded-lg
                                   px-3 py-2 text-[13px] font-medium
                                   transition

                            {{ request()->routeIs('raw-materials.*')
                                ? 'bg-white/10 text-white'
                                : 'text-gray-500 hover:bg-white/[0.06] hover:text-gray-200' }}">

                            <i class="bx bx-cube text-base"></i>

                            <span>
                                Materials
                            </span>

                        </a>

                    </div>


                    {{-- Categories --}}
                    <a href="{{ route('categories.index') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('categories.*')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-category w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Categories
                        </span>

                    </a>


                    {{-- Units --}}
                    <a href="{{ route('units.index') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('units.*')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-ruler w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Units
                        </span>

                    </a>


                    {{-- Warehouses --}}
                    <a href="{{ route('warehouses.index') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('warehouses.*')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-buildings w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Warehouses
                        </span>

                    </a>

                </div>

            </div>


            {{-- ================================================== --}}
            {{-- PROCUREMENT --}}
            {{-- ================================================== --}}

            <div class="mb-5">

                <p
                    class="mb-2 px-3 text-[10px] font-semibold
                           uppercase tracking-[0.14em] text-gray-600">

                    Procurement

                </p>


                <div class="space-y-0.5">


                    {{-- Vendors --}}
                    <a href="{{ route('vendors.index') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('vendors.*')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-store w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Vendors
                        </span>

                    </a>


                    {{-- Purchase Requests --}}
                    <a href="{{ route('purchase-requests.index') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('purchase-requests.*')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-file w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Purchase Requests
                        </span>

                    </a>


                    {{-- Purchase Orders --}}
                    <a href="{{ route('purchase-orders.index') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('purchase-orders.*')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-cart w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Purchase Orders
                        </span>

                    </a>

                </div>

            </div>


            {{-- ================================================== --}}
            {{-- RECEIVING --}}
            {{-- ================================================== --}}

            <div class="mb-5">

                <p
                    class="mb-2 px-3 text-[10px] font-semibold
                           uppercase tracking-[0.14em] text-gray-600">

                    Receiving

                </p>


                <div class="space-y-0.5">


                    {{-- Receive Materials --}}
                    <a href="{{ route('materials.receive') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('materials.receive')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-download w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Receive Materials
                        </span>

                    </a>


                    {{-- Goods Receipts --}}
                    <a href="{{ route('goods-receipts.index') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('goods-receipts.*')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-receipt w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Goods Receipts
                        </span>

                    </a>


                    {{-- Purchase Returns --}}
                    <a href="{{ route('purchase-returns.index') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('purchase-returns.*')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-undo w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Purchase Returns
                        </span>

                    </a>

                </div>

            </div>


            {{-- ================================================== --}}
            {{-- FINANCE --}}
            {{-- ================================================== --}}

            <div class="mb-5">

                <p
                    class="mb-2 px-3 text-[10px] font-semibold
                           uppercase tracking-[0.14em] text-gray-600">

                    Finance

                </p>


                <div class="space-y-0.5">


                    {{-- Vendor Bills --}}
                    <a href="{{ route('vendor-bills.index') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('vendor-bills.*')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-receipt w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Vendor Bills
                        </span>

                    </a>


                    {{-- Vendor Payments --}}
                    <a href="{{ route('vendor-payments.index') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('vendor-payments.*')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-money w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Vendor Payments
                        </span>

                    </a>


                    {{-- Vendor Credits --}}
                    <a href="{{ route('vendor-credits.index') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('vendor-credits.*')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-credit-card w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Vendor Credits
                        </span>

                    </a>


                    {{-- Payment Terms --}}
                    <a href="{{ route('payment-terms.index') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('payment-terms.*')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-time-five w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Payment Terms
                        </span>

                    </a>

                </div>

            </div>


            {{-- ================================================== --}}
            {{-- MANUFACTURING --}}
            {{-- ================================================== --}}

            <div class="mb-3">

                <p
                    class="mb-2 px-3 text-[10px] font-semibold
                           uppercase tracking-[0.14em] text-gray-600">

                    Manufacturing

                </p>


                <div class="space-y-0.5">


                    {{-- Manufacture Product --}}
                    <a href="{{ route('manufacturing.index') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('manufacturing.index')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-cog w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Manufacture Product
                        </span>

                    </a>


                    {{-- Manufacturing Records --}}
                    <a href="{{ route('manufacturing.records') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('manufacturing.records')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-history w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Manufacturing Records
                        </span>

                    </a>


                    {{-- Manufacturing Formulas --}}
                    <a href="{{ route('manufacturing-formulas.index') }}"
                        class="flex items-center gap-3 rounded-lg
                               px-3 py-2.5 text-sm font-medium transition

                        {{ request()->routeIs('manufacturing-formulas.*')
                            ? 'bg-white/10 text-white'
                            : 'text-gray-400 hover:bg-white/[0.06] hover:text-white' }}">

                        <i class="bx bx-list-check w-5 text-center
                                   text-[19px]">
                        </i>

                        <span>
                            Manufacturing Formulas
                        </span>

                    </a>

                </div>

            </div>

        </nav>

    </aside>


    {{-- ====================================================== --}}
    {{-- MAIN APPLICATION --}}
    {{-- ====================================================== --}}

    <div class="ml-64 flex min-h-screen min-w-0 flex-col">


        {{-- ================================================== --}}
        {{-- GLOBAL HEADER --}}
        {{-- ================================================== --}}

        <header
            class="sticky top-0 z-30 flex h-16 shrink-0
                   items-center justify-between border-b
                   border-gray-200 bg-white/95 px-5
                   backdrop-blur sm:px-6">


            {{-- Left --}}
            <div class="flex min-w-0 items-center gap-3">

                <div
                    class="flex h-9 w-9 shrink-0 items-center
                           justify-center rounded-lg border
                           border-gray-200 bg-gray-50 text-gray-500">

                    <i class="bx bx-layer text-lg"></i>

                </div>


                <div class="hidden min-w-0 sm:block">

                    <p class="truncate text-sm font-semibold
                               text-gray-900">

                        {{ config('app.name') }}

                    </p>

                    <p class="text-[11px] text-gray-400">
                        Procurement & Inventory Management
                    </p>

                </div>

            </div>


            {{-- Right --}}
            @auth

                <div class="flex items-center gap-3">


                    {{-- User --}}
                    <div class="hidden text-right md:block">

                        <p
                            class="max-w-[180px] truncate text-sm
                                   font-medium text-gray-800">

                            {{ Auth::user()->name }}

                        </p>

                        <p class="max-w-[180px] truncate
                                   text-[11px] text-gray-400">

                            {{ Auth::user()->email }}

                        </p>

                    </div>


                    {{-- Avatar --}}
                    <div
                        class="flex h-9 w-9 shrink-0 items-center
                               justify-center rounded-full bg-gray-900
                               text-xs font-semibold uppercase text-white
                               ring-2 ring-gray-100">

                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                    </div>


                    <div class="hidden h-7 w-px bg-gray-200 sm:block">
                    </div>


                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">

                        @csrf


                        <button type="submit" title="Logout"
                            class="inline-flex h-9 w-9 cursor-pointer
                                   items-center justify-center rounded-lg
                                   border border-gray-200 bg-white
                                   text-gray-500 transition
                                   hover:border-red-200 hover:bg-red-50
                                   hover:text-red-600">

                            <i class="bx bx-log-out text-lg"></i>

                        </button>

                    </form>

                </div>

            @endauth

        </header>


        {{-- ================================================== --}}
        {{-- PAGE CONTENT --}}
        {{-- ================================================== --}}

        <main class="min-w-0 flex-1">

            <div class="mx-auto w-full p-5 sm:p-6 lg:p-7">

                {{ $slot }}

            </div>

        </main>

    </div>


    {{-- ====================================================== --}}
    {{-- JQUERY --}}
    {{-- ====================================================== --}}

    <script src="https://cdn.jsdelivr.net/npm/jquery@4.0.0/dist/jquery.min.js"
        integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>


    {{-- ====================================================== --}}
    {{-- SIDEBAR --}}
    {{-- ====================================================== --}}

    <script>
        $(document).ready(function() {

            const inventoryMenu =
                $('#inventoryMenu');

            const inventoryMenuIcon =
                $('#inventoryMenuIcon');


            const inventoryActive =
                @json(request()->routeIs('products.*', 'raw-materials.*'));


            /*
             * Automatically open submenu
             * when current page belongs to it.
             */
            if (inventoryActive) {

                inventoryMenu
                    .removeClass('hidden');

                inventoryMenuIcon
                    .addClass('rotate-180');

            }


            /*
             * Toggle inventory submenu
             */
            $('#inventoryMenuBtn')
                .on('click', function() {

                    inventoryMenu
                        .toggleClass('hidden');

                    inventoryMenuIcon
                        .toggleClass('rotate-180');

                });

        });
    </script>


    {{-- ====================================================== --}}
    {{-- GLOBAL TOAST --}}
    {{-- ====================================================== --}}

    <script>
        function showToast(color, message) {

            const colors = {

                success: {
                    wrapper: 'border-green-200 bg-white text-gray-700',
                    iconWrapper: 'bg-green-50 text-green-600',
                    icon: 'bx-check'
                },

                error: {
                    wrapper: 'border-red-200 bg-white text-gray-700',
                    iconWrapper: 'bg-red-50 text-red-600',
                    icon: 'bx-x'
                },

                warning: {
                    wrapper: 'border-amber-200 bg-white text-gray-700',
                    iconWrapper: 'bg-amber-50 text-amber-600',
                    icon: 'bx-error'
                },

                info: {
                    wrapper: 'border-blue-200 bg-white text-gray-700',
                    iconWrapper: 'bg-blue-50 text-blue-600',
                    icon: 'bx-info-circle'
                }

            };


            const type =
                colors[color] || colors.info;


            const toast = $(`
                <div
                    class="fixed right-5 top-5 z-[100]
                           flex max-w-sm items-center gap-3
                           rounded-xl border px-3.5 py-3
                           text-sm shadow-xl
                           ${type.wrapper}">

                    <div
                        class="flex h-8 w-8 shrink-0
                               items-center justify-center
                               rounded-lg
                               ${type.iconWrapper}">

                        <i
                            class="bx ${type.icon}
                                   text-lg">
                        </i>

                    </div>

                    <span
                        class="min-w-0 flex-1
                               font-medium">
                    </span>

                    <button
                        type="button"
                        class="flex h-7 w-7 shrink-0
                               cursor-pointer items-center
                               justify-center rounded-md
                               text-gray-400 transition
                               hover:bg-gray-100
                               hover:text-gray-700">

                        <i class="bx bx-x text-lg"></i>

                    </button>

                </div>
            `);


            toast
                .find('span')
                .text(message);


            $('body')
                .append(toast);


            toast
                .find('button')
                .on('click', function() {

                    toast.remove();

                });


            setTimeout(function() {

                toast.fadeOut(
                    250,
                    function() {

                        $(this).remove();

                    }
                );

            }, 3000);

        }
    </script>


    @stack('scripts')

</body>

</html>
