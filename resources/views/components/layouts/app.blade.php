<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('storage/favicon.png') }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Browser title only --}}
    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <style>
        sup {
            color: red;
            font-weight: bold;
            font-size: 0.85rem;
        }
    </style>

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>

<body class="min-h-screen bg-gray-50 text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">

    {{-- Fixed Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-40 w-64 bg-gray-950 text-white">

        {{-- Logo --}}
        <div class="flex h-16 items-center border-b border-gray-800 px-5">

            <a href="{{ route('admin.dashboard') }}" class="flex h-full items-center">
                <img src="{{ asset('storage/logo.png') }}" alt="{{ config('app.name') }}"
                    class="max-h-10 max-w-[180px] object-contain">
            </a>

        </div>

        {{-- Navigation --}}
        <nav class="h-[calc(100vh-4rem)] overflow-y-auto px-3 py-5">

            {{-- Dashboard --}}
            <div class="mb-6">

                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                    {{ request()->routeIs('admin.dashboard')
                        ? 'bg-gray-800 text-white'
                        : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">
                    <i class="bx bx-grid-alt text-[20px]"></i>
                    <span>Dashboard</span>
                </a>
            </div>


            {{-- Inventory --}}
            <div class="mb-6">
                <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                    Inventory
                </p>
                <div class="space-y-1">
                    {{-- Inventory Dropdown --}}
                    <button type="button" id="inventoryMenuBtn"
                        class="flex w-full cursor-pointer items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium text-gray-400 transition hover:bg-gray-900 hover:text-white">
                        <span class="flex items-center gap-3">
                            <i class="bx bx-package text-[20px]"></i>
                            <span>Inventories</span>
                        </span>

                        <i id="inventoryMenuIcon" class="bx bx-chevron-down text-lg transition-transform"></i>

                    </button>


                    <div id="inventoryMenu" class="mt-1 hidden space-y-1 pl-3">

                        {{-- Products --}}
                        <a href="{{ route('products.index') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                            {{ request()->routeIs('products.*')
                                ? 'bg-gray-800 text-white'
                                : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">

                            <i class="bx bx-box text-[19px]"></i>
                            <span>Products</span>

                        </a>


                        {{-- Raw Materials --}}
                        <a href="{{ route('raw-materials.index') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                            {{ request()->routeIs('raw-materials.*')
                                ? 'bg-gray-800 text-white'
                                : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">

                            <i class="bx bx-cube text-[19px]"></i>
                            <span>Materials</span>

                        </a>

                    </div>


                    {{-- Categories --}}
                    <a href="{{ route('categories.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('categories.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">

                        <i class="bx bx-category text-[20px]"></i>
                        <span>Categories</span>

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

                </div>

            </div>


            {{-- Procurement --}}
            <div class="mb-6">

                <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                    Procurement
                </p>

                <div class="space-y-1">

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
                    {{-- <a href="{{ route('quotations.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('quotations.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">

                        <i class="bx bx-file-find text-[20px]"></i>
                        <span>Quotations(removed)</span>

                    </a> --}}


                    {{-- Purchase Orders --}}
                    <a href="{{ route('purchase-orders.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('purchase-orders.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">

                        <i class="bx bx-cart text-[20px]"></i>
                        <span>Purchase Orders</span>

                    </a>

                </div>

            </div>


            {{-- Receiving --}}
            <div class="mb-6">

                <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                    Receiving
                </p>

                <div class="space-y-1">

                    {{-- Goods Receipts --}}
                    <a href="{{ route('materials.receive') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('materials.receive')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">

                        <i class="bx bx-package text-[20px]"></i>
                        <span>Receive Materials</span>

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

            </div>


            {{-- Finance --}}
            <div class="mb-6">

                <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                    Finance
                </p>

                <div class="space-y-1">

                    {{-- Vendor Bills --}}
                    <a href="{{ route('vendor-bills.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
            {{ request()->routeIs('vendor-bills.*')
                ? 'bg-gray-800 text-white'
                : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">

                        <i class="bx bx-receipt text-[20px]"></i>
                        <span>Vendor Bills</span>

                    </a>


                    {{-- Vendor Payments --}}
                    <a href="{{ route('vendor-payments.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
            {{ request()->routeIs('vendor-payments.*')
                ? 'bg-gray-800 text-white'
                : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">

                        <i class="bx bx-money text-[20px]"></i>
                        <span>Vendor Payments</span>

                    </a>


                    {{-- Payment Terms --}}
                    <a href="{{ route('payment-terms.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
            {{ request()->routeIs('payment-terms.*')
                ? 'bg-gray-800 text-white'
                : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">

                        <i class="bx bx-time-five text-[20px]"></i>
                        <span>Payment Terms</span>

                    </a>

                </div>

            </div>


            {{-- Manufacturing --}}
            <div class="mb-6">

                <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-gray-500">
                    Manufacturing
                </p>

                <div class="space-y-1">

                    {{-- Manufacture Product --}}
                    <a href="{{ route('manufacturing.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('manufacturing.index')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">

                        <i class="bx bx-cog text-[20px]"></i>
                        <span>Manufacture Product</span>

                    </a>


                    {{-- Manufacturing Records --}}
                    <a href="{{ route('manufacturing.records') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('manufacturing.records')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">

                        <i class="bx bx-history text-[20px]"></i>
                        <span>Manufacturing Records</span>

                    </a>


                    {{-- Manufacturing Formulas --}}
                    <a href="{{ route('manufacturing-formulas.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                        {{ request()->routeIs('manufacturing-formulas.*')
                            ? 'bg-gray-800 text-white'
                            : 'text-gray-400 hover:bg-gray-900 hover:text-white' }}">

                        <i class="bx bx-list-check text-[20px]"></i>
                        <span>Manufacturing Formulas</span>

                    </a>

                </div>

            </div>

        </nav>
    </aside>


    {{-- Main Area --}}
    <div class="ml-64 flex min-h-screen min-w-0 flex-col">

        {{-- Global Header --}}
        <header
            class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-gray-200 bg-white px-5 sm:px-6 dark:border-gray-800 dark:bg-gray-900">

            {{-- Application Context --}}
            <div class="flex items-center gap-3">

                <div
                    class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                    <i class="bx bx-layer text-lg"></i>
                </div>

                <div class="hidden sm:block">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                        {{ config('app.name') }}
                    </p>

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Management System
                    </p>
                </div>

            </div>

            @auth
                <div class="flex items-center gap-3">

                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ Auth::user()->name }}
                        </p>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ Auth::user()->email }}
                        </p>
                    </div>

                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-900 text-sm font-semibold text-white dark:bg-white dark:text-gray-900">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <div class="hidden h-7 w-px bg-gray-200 sm:block dark:bg-gray-700"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit" title="Logout"
                            class="inline-flex cursor-pointer items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-500 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600 dark:border-gray-700 dark:text-gray-400 dark:hover:border-red-800 dark:hover:bg-red-900/20 dark:hover:text-red-400">
                            <i class="bx bx-log-out text-lg"></i>
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


    {{-- jQuery --}}
    <script src="https://cdn.jsdelivr.net/npm/jquery@4.0.0/dist/jquery.min.js"
        integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            const inventoryMenu = $('#inventoryMenu');
            const inventoryMenuIcon = $('#inventoryMenuIcon');

            const inventoryActive = @json(request()->routeIs('products.*', 'raw-materials.*'));

            if (inventoryActive) {
                inventoryMenu.removeClass('hidden');
                inventoryMenuIcon.addClass('rotate-180');
            }

            $('#inventoryMenuBtn').on('click', function() {
                inventoryMenu.toggleClass('hidden');
                inventoryMenuIcon.toggleClass('rotate-180');
            });
        });
    </script>

    {{-- Toast --}}
    <script>
        function showToast(color, message) {

            const colors = {
                success: {
                    wrapper: 'border-green-200 bg-green-50 text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400',
                    icon: 'bx-check-circle'
                },

                error: {
                    wrapper: 'border-red-200 bg-red-50 text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400',
                    icon: 'bx-error-circle'
                },

                warning: {
                    wrapper: 'border-yellow-200 bg-yellow-50 text-yellow-700 dark:border-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                    icon: 'bx-error'
                },

                info: {
                    wrapper: 'border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
                    icon: 'bx-info-circle'
                }
            };

            const type = colors[color] || colors.info;

            const toast = $(`
                <div class="fixed right-5 top-5 z-[100] flex items-center gap-3 rounded-lg border px-4 py-3 text-sm shadow-lg ${type.wrapper}">
                    <i class="bx ${type.icon} text-lg"></i>

                    <span></span>

                    <button
                        type="button"
                        class="ml-2 text-lg opacity-60 transition hover:opacity-100"
                    >
                        &times;
                    </button>
                </div>
            `);

            toast.find('span').text(message);

            $('body').append(toast);

            toast.find('button').on('click', function() {
                toast.remove();
            });

            setTimeout(function() {
                toast.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
    </script>

    @stack('scripts')

</body>

</html>
