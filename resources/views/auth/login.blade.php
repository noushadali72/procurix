<x-layouts.guest title="Login">
@push('styles') <style>
.error {
color: #dc2626;
margin-top: 6px;
font-size: 0.875rem;
} </style>
@endpush

<div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-12">
    <div class="w-full max-w-md">

        {{-- Login Card --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-lg sm:p-10">

            {{-- Header --}}
            <div class="mb-8 text-center">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-gray-900">
                    <svg
                        class="h-6 w-6 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2h8z"
                        />
                    </svg>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                    Welcome Back
                </h1>

                <p class="mt-2 text-sm text-gray-500">
                    Sign in to your account to continue
                </p>
            </div>

            {{-- Success Alert --}}
            @if (session()->has('success'))
                <div class="mb-6 flex items-start gap-3 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    <svg
                        class="mt-0.5 h-5 w-5 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Danger/Error Alert --}}
            @if (session()->has('error'))
                <div class="mb-6 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <svg
                        class="mt-0.5 h-5 w-5 shrink-0"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>

                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('auth.login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-5">
                    <label
                        for="email"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email address"
                        required
                        autofocus
                        autocomplete="email"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10"
                    >

                    @error('email')
                        <span class="error block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-6">
                    <label
                        for="password"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10"
                    >

                    @error('password')
                        <span class="error block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="flex w-full items-center justify-center rounded-lg bg-gray-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                >
                    Sign In
                </button>
            </form>

            {{-- Register --}}
            <div class="mt-7 border-t border-gray-100 pt-6 text-center">
                <p class="text-sm text-gray-500">
                    Don't have an account?
                    <a
                        href="{{ route('register') }}"
                        class="ml-1 font-semibold text-gray-900 hover:text-gray-700 hover:underline"
                    >
                        Create an account
                    </a>
                </p>
            </div>

        </div>
    </div>
</div>

</x-layouts.guest>
