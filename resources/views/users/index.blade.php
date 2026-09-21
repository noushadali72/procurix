<x-layouts.app title="Users">

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-bold text-slate-900">
                Users
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Manage system users and their access.
            </p>
        </div>

        <button type="button" id="createUserBtn"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900">
            <i class="bx bx-plus text-lg"></i>
            Create User
        </button>

    </div>


    {{-- Alerts --}}
    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif


    {{-- Users Card --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">

        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="text-sm font-semibold text-slate-900">
                User List
            </h2>

            <p class="mt-0.5 text-xs text-slate-500">
                {{ $users->total() }}
                {{ Str::plural('user', $users->total()) }}
                registered
            </p>
        </div>


        <div class="overflow-x-auto">

            <table class="w-full min-w-[800px] text-left text-sm">

                <thead class="border-b border-slate-200 bg-slate-50">

                    <tr>
                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            User
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Role
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Status
                        </th>

                        <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Created
                        </th>

                        <th class="px-5 py-3.5 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Actions
                        </th>
                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100">

                    @forelse ($users as $user)
                        <tr class="transition hover:bg-slate-50">

                            {{-- User --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-sm font-semibold text-slate-600">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <div class="font-medium text-slate-900">
                                            {{ $user->name }}
                                        </div>

                                        <div class="mt-0.5 text-xs text-slate-500">
                                            {{ $user->email }}
                                        </div>
                                    </div>

                                </div>

                            </td>


                            {{-- Role --}}
                            <td class="px-5 py-4">

                                <span
                                    class="inline-flex rounded-md bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                    {{ ucfirst($user->role ?? 'User') }}
                                </span>

                            </td>


                            {{-- Status --}}
                            <td class="px-5 py-4">

                                @php
                                    $status = $user->status ?? 'active';

                                    $statusClass = match ($status) {
                                        'active' => 'bg-green-50 text-green-600',
                                        'inactive' => 'bg-red-50 text-red-600',
                                        default => 'bg-slate-100 text-slate-600',
                                    };

                                    $statusDot = match ($status) {
                                        'active' => 'bg-green-500',
                                        'inactive' => 'bg-red-500',
                                        default => 'bg-slate-400',
                                    };
                                @endphp

                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium {{ $statusClass }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>
                                    {{ ucfirst($status) }}
                                </span>

                            </td>


                            {{-- Created --}}
                            <td class="px-5 py-4">

                                <div class="text-sm text-slate-700">
                                    {{ $user->created_at->format('d M Y') }}
                                </div>

                                <div class="mt-0.5 text-xs text-slate-400">
                                    {{ $user->created_at->format('h:i A') }}
                                </div>

                            </td>


                            {{-- Actions --}}
                            <td class="px-5 py-4">

                                <div class="flex items-center justify-end gap-2">

                                    <button type="button"
                                        class="edit-user inline-flex items-center gap-1.5 rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 transition hover:bg-slate-800 hover:text-white"
                                        data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                        data-email="{{ $user->email }}" data-role="{{ $user->role }}"
                                        data-status="{{ $user->status }}"
                                        data-url="{{ route('users.update', $user) }}">
                                        <i class="bx bx-edit"></i>
                                        Edit
                                    </button>


                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this user?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-500 hover:text-white">
                                            <i class="bx bx-trash"></i>
                                            Delete
                                        </button>
                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">

                                <div class="mx-auto flex max-w-sm flex-col items-center">

                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                        <i class="bx bx-user text-2xl"></i>
                                    </div>

                                    <p class="mt-3 text-sm font-semibold text-slate-700">
                                        No users found
                                    </p>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Create your first user to get started.
                                    </p>

                                </div>

                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $users->links() }}
            </div>
        @endif

    </div>


    {{-- User Modal --}}
    <div id="userModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">

        <div class="w-full max-w-lg rounded-xl border border-slate-200 bg-white shadow-xl">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">

                <div>
                    <h2 id="modalTitle" class="text-base font-semibold text-slate-900">
                        Create User
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500">
                        Add or update user information.
                    </p>
                </div>

                <button type="button" id="closeUserModal"
                    class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700">
                    <i class="bx bx-x text-xl"></i>
                </button>

            </div>


            {{-- Form --}}
            <form id="userForm" action="{{ route('users.store') }}" method="POST" novalidate>
                @csrf
                <div id="methodField"></div>
                <div class="space-y-4 px-5 py-5">
                    {{-- Name --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Name
                        </label>

                        <input type="text" name="name" id="userName" value="{{ old('name') }}" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                            placeholder="Enter user name">

                        @error('name')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Email --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Email
                        </label>

                        <input type="email" name="email" id="userEmail" value="{{ old('email') }}" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                            placeholder="Enter email address">

                        @error('email')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Password --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Password
                        </label>

                        <input type="password" name="password" id="userPassword"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                            placeholder="Enter password">

                        <p id="passwordHint" class="mt-1 text-xs text-slate-400">
                            Required when creating a user.
                        </p>

                        @error('password')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Role --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Role
                        </label>

                        <select name="role_id" id="userRole" required
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500">
                            <option value="">Select role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->label }}</option>
                            @endforeach
                            <option value="1">Admin</option>
                            <option value="2">User</option>
                        </select>

                        @error('role')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Status --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Status
                        </label>

                        <select name="status" id="userStatus" required
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>

                        @error('status')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>


                {{-- Modal Footer --}}
                <div class="flex justify-end gap-3 border-t border-slate-200 px-5 py-4">

                    <button type="button" id="cancelUserModal"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Cancel
                    </button>

                    <button type="submit"
                        class="rounded-lg bg-slate-800 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900">
                        Save User
                    </button>

                </div>

            </form>

        </div>

    </div>


    @push('scripts')
        <script>
            const userModal = $('#userModal');
            const userForm = $('#userForm');
            const submitBtn = userForm.find('button[type="submit"]');

            function openUserModal() {
                userModal.removeClass('hidden').addClass('flex');
            }

            function closeUserModal() {
                userModal.addClass('hidden').removeClass('flex');
                clearUserForm();
            }

            function clearUserForm() {
                userForm[0].reset();
                userForm.attr('action', "{{ route('users.store') }}");
                $('#methodField').html('');

                $('#modalTitle').text('Create User');
                $('#userPassword').prop('required', true);
                $('#passwordHint').text('Required when creating a user.');

                $('.field-error').remove();
            }


            // Create User
            $('#createUserBtn').on('click', function() {

                clearUserForm();

                openUserModal();
            });


            // Edit User
            $(document).on('click', '.edit-user', function() {

                const button = $(this);

                clearUserForm();

                userForm.attr('action', button.data('url'));

                $('#methodField').html('@method('PUT')');

                $('#modalTitle').text('Edit User');

                $('#userName').val(button.data('name'));
                $('#userEmail').val(button.data('email'));
                $('#userRole').val(button.data('role'));
                $('#userStatus').val(button.data('status'));

                $('#userPassword')
                    .val('')
                    .prop('required', false);

                $('#passwordHint').text(
                    'Leave password empty to keep the current password.'
                );

                openUserModal();
            });


            // Submit Create / Edit
            userForm.on('submit', function(e) {

                e.preventDefault();

                const form = $(this);

                clearValidationErrors();

                submitBtn.prop('disabled', true);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),

                    headers: {
                        'Accept': 'application/json'
                    },

                    success: function(response) {

                        showToast(
                            'success',
                            response.message
                        );

                        closeUserModal();

                        setTimeout(function() {
                            window.location.reload();
                        }, 500);
                    },

                    error: function(xhr) {

                        submitBtn.prop('disabled', false);

                        if (xhr.status === 422) {
                            showValidationErrors(
                                xhr.responseJSON?.errors || {}
                            );

                            return;
                        }

                        showToast(
                            'error',
                            xhr.responseJSON?.message ||
                            'Something went wrong.'
                        );
                    }
                });
            });


            // Validation errors
            function showValidationErrors(errors) {

                $.each(errors, function(field, messages) {

                    const input = $(`[name="${field}"]`);

                    if (!input.length) {
                        return;
                    }

                    input.after(`
            <p class="field-error mt-1 text-xs text-red-600">
                ${messages[0]}
            </p>
        `);
                });
            }


            function clearValidationErrors() {
                $('.field-error').remove();
            }


            // Close modal
            $('#closeUserModal, #cancelUserModal').on('click', function() {
                closeUserModal();
            });


            userModal.on('click', function(e) {

                if (e.target === this) {
                    closeUserModal();
                }
            });
        </script>
    @endpush

</x-layouts.app>
