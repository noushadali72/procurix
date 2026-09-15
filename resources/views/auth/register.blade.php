<x-layouts.guest title="Register">
@push('styles') <style>
.error {
color: #dc2626;
margin-top: 6px;
font-size: 0.875rem;
} </style>
@endpush

<div class="flex min-h-screen items-center justify-center bg-gray-50 px-4 py-12">
    <div class="w-full max-w-md">

        {{-- Register Card --}}
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
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-4a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0"
                        />
                    </svg>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                    Create Account
                </h1>

                <p class="mt-2 text-sm text-gray-500">
                    Create your account to get started
                </p>
            </div>

            {{-- Register Form --}}
            <form method="POST" action="{{ route('auth.register') }}">
                @csrf

                {{-- Name --}}
                <div class="mb-5">
                    <label
                        for="name"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Name
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter your full name"
                        required
                        autocomplete="name"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10"
                    >

                    @error('name')
                        <span class="error block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Username --}}
                <div class="mb-5">
                    <label
                        for="username"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Enter your username"
                        required
                        autocomplete="username"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10"
                    >

                    @error('username')
                        <span class="error block">{{ $message }}</span>
                    @enderror
                </div>

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
                        autocomplete="email"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10"
                    >

                    @error('email')
                        <span class="error block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-5">
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
                        placeholder="Create a password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10"
                    >

                    @error('password')
                        <span class="error block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label
                        for="password_confirmation"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Confirm your password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition focus:border-gray-900 focus:ring-2 focus:ring-gray-900/10"
                    >

                    @error('password_confirmation')
                        <span class="error block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="flex w-full items-center justify-center rounded-lg bg-gray-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
                >
                    Create Account
                </button>
            </form>

            {{-- Login --}}
            <div class="mt-7 border-t border-gray-100 pt-6 text-center">
                <p class="text-sm text-gray-500">
                    Already have an account?
                    <a
                        href="{{ route('login') }}"
                        class="ml-1 font-semibold text-gray-900 hover:text-gray-700 hover:underline"
                    >
                        Sign in
                    </a>
                </p>
            </div>

        </div>
    </div>
</div>

</x-layouts.guest>
