<x-layouts.app title="Accounts">

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                Accounts
            </h1>

            <p class="mt-1 text-sm text-gray-500">
                Manage accounts used for financial transactions.
            </p>
        </div>

        <button type="button"
            onclick="openAccountModal()"
            class="inline-flex items-center justify-center rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800">
            <i class="bx bx-plus mr-1.5"></i>
            Add Account
        </button>

    </div>


    {{-- Accounts Table --}}
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full divide-y divide-gray-200">

                <thead class="bg-gray-50">

                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Code
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Account
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Category
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Type
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Status
                        </th>

                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Actions
                        </th>
                    </tr>

                </thead>

                <tbody id="accountsTable" class="divide-y divide-gray-100">

                    @forelse ($accounts as $account)

                        <tr id="accountRow{{ $account->id }}" class="hover:bg-gray-50">

                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $account->code }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $account->name }}
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $account->category->name }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700">
                                    {{ ucfirst($account->category->type) }}
                                </span>
                            </td>

                            <td class="px-6 py-4">

                                @if ($account->is_active)

                                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                        Active
                                    </span>

                                @else

                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            <td class="px-6 py-4 text-right">

                                <button type="button"
                                    onclick='editAccount(@json($account))'
                                    class="mr-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                                    Edit
                                </button>

                                <button type="button"
                                    onclick="deleteAccount({{ $account->id }})"
                                    class="text-sm font-medium text-red-600 hover:text-red-800">
                                    Delete
                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                                No accounts found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Account Modal --}}
    <div id="accountModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">

        <div class="w-full max-w-lg rounded-xl bg-white shadow-xl">

            <div class="flex items-center justify-between border-b px-6 py-4">

                <div>
                    <h2 id="accountModalTitle" class="text-lg font-semibold text-gray-900">
                        Add Account
                    </h2>

                    <p class="text-sm text-gray-500">
                        Add an account to your chart of accounts.
                    </p>
                </div>

                <button type="button"
                    onclick="closeAccountModal()"
                    class="text-gray-400 hover:text-gray-700">
                    <i class="bx bx-x text-2xl"></i>
                </button>

            </div>


            <form id="accountForm" class="p-6">

                <input type="hidden" id="accountId">

                <div class="space-y-5">

                    {{-- Category --}}
                    <div>

                        <label for="account_category_id"
                            class="mb-1 block text-sm font-medium text-gray-700">
                            Account Category
                        </label>

                        <select id="account_category_id"
                            name="account_category_id"
                            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">

                            <option value="">Select Category</option>

                            @foreach ($categories as $category)

                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>

                            @endforeach

                        </select>

                        <span id="accountCategoryIdErr"
                            class="mt-1 block text-sm text-red-600"></span>

                    </div>


                    {{-- Name --}}
                    <div>

                        <label for="account_name"
                            class="mb-1 block text-sm font-medium text-gray-700">
                            Account Name
                        </label>

                        <input type="text"
                            id="account_name"
                            name="name"
                            placeholder="e.g. Bank"
                            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">

                        <span id="accountNameErr"
                            class="mt-1 block text-sm text-red-600"></span>

                    </div>


                    {{-- Code --}}
                    <div>

                        <label for="account_code"
                            class="mb-1 block text-sm font-medium text-gray-700">
                            Account Code
                        </label>

                        <input type="text"
                            id="account_code"
                            name="code"
                            placeholder="e.g. 1001"
                            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">

                        <span id="accountCodeErr"
                            class="mt-1 block text-sm text-red-600"></span>

                    </div>


                    {{-- Active --}}
                    <div class="flex items-center">

                        <input type="checkbox"
                            id="account_is_active"
                            name="is_active"
                            value="1"
                            checked
                            class="h-4 w-4 rounded border-gray-300 text-gray-900 focus:ring-gray-500">

                        <label for="account_is_active"
                            class="ml-2 text-sm text-gray-700">
                            Active
                        </label>

                    </div>

                </div>


                <div class="mt-6 flex justify-end gap-3">

                    <button type="button"
                        onclick="closeAccountModal()"
                        class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>

                    <button type="submit"
                        id="accountSubmitBtn"
                        class="rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white hover:bg-gray-800">
                        Save Account
                    </button>

                </div>

            </form>

        </div>

    </div>


    @push('scripts')

        <script>
            let editingAccountId = null;


            function openAccountModal() {

                editingAccountId = null;

                $("#accountForm")[0].reset();

                $("#account_is_active").prop("checked", true);

                $("#accountModalTitle").text("Add Account");
                $("#accountSubmitBtn").text("Save Account");

                clearAccountErrors();

                $("#accountModal")
                    .removeClass("hidden")
                    .addClass("flex");
            }


            function closeAccountModal() {

                $("#accountModal")
                    .addClass("hidden")
                    .removeClass("flex");
            }


            function editAccount(account) {

                editingAccountId = account.id;

                clearAccountErrors();

                $("#account_category_id").val(account.account_category_id);
                $("#account_name").val(account.name);
                $("#account_code").val(account.code);
                $("#account_is_active").prop("checked", account.is_active);

                $("#accountModalTitle").text("Edit Account");
                $("#accountSubmitBtn").text("Update Account");

                $("#accountModal")
                    .removeClass("hidden")
                    .addClass("flex");
            }


            function clearAccountErrors() {

                $("[id$='Err']").text("");
            }


            $("#accountForm").on("submit", function(e) {

                e.preventDefault();

                clearAccountErrors();

                const form = this;
                const formData = new FormData(form);

                formData.set(
                    "is_active",
                    $("#account_is_active").is(":checked") ? 1 : 0
                );

                const url = editingAccountId
                    ? `/accounts/${editingAccountId}`
                    : "{{ route('accounts.store') }}";

                if (editingAccountId) {
                    formData.append("_method", "PUT");
                }

                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        "Accept": "application/json"
                    },

                    success: function(response) {

                        showToast("success", response.message);

                        closeAccountModal();

                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    },

                    error: function(xhr) {

                        if (xhr.status === 422 && xhr.responseJSON?.errors) {

                            const errors = xhr.responseJSON.errors;

                            if (errors.account_category_id) {
                                $("#accountCategoryIdErr")
                                    .text(errors.account_category_id[0]);
                            }

                            if (errors.name) {
                                $("#accountNameErr")
                                    .text(errors.name[0]);
                            }

                            if (errors.code) {
                                $("#accountCodeErr")
                                    .text(errors.code[0]);
                            }

                            return;
                        }

                        showToast(
                            "error",
                            xhr.responseJSON?.message || "Something went wrong."
                        );
                    }
                });
            });


            function deleteAccount(id) {

                if (!confirm("Are you sure you want to delete this account?")) {
                    return;
                }

                $.ajax({
                    url: `/accounts/${id}`,
                    method: "POST",
                    data: {
                        _method: "DELETE",
                        _token: "{{ csrf_token() }}"
                    },
                    headers: {
                        "Accept": "application/json"
                    },

                    success: function(response) {

                        showToast("success", response.message);

                        $(`#accountRow${id}`).remove();
                    },

                    error: function(xhr) {

                        showToast(
                            "error",
                            xhr.responseJSON?.message || "Unable to delete account."
                        );
                    }
                });
            }
        </script>

    @endpush

</x-layouts.app>