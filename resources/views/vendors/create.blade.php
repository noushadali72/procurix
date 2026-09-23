<x-layouts.app title="Create Vendor">

    <div class="mb-6">

        <h2 class="text-xl font-semibold text-gray-900">
            Create Vendor
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Add a new raw material supplier/vendor.
        </p>

    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

        <form
            id="createVendorForm"
            method="POST"
            action="{{ route('vendors.store') }}"
        >

            @csrf

            @include('vendors._form')

            <div class="mt-6 flex justify-end gap-3">

                <a
                    href="{{ route('vendors.index') }}"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-800"
                >
                    Create Vendor
                </button>

            </div>

        </form>

    </div>

@push('scripts')
<script>
// Phone number input validation
$(document).on('input', '#phone', function () {
    let value = this.value;
    // Keep + only if it is the first character
    if (value.startsWith('+')) {
        value = '+' + value.slice(1).replace(/\D/g, '');
    } else {
        value = value.replace(/\D/g, '');
    }
    this.value = value;
});

// ntn number input validation
$(document).on('input', '#ntn', function () {
        let value = this.value;
        value = value.replace(/\D/g, '');
        this.value = value;
});

function clearVendorErrors() {
    $("#nameErr").text("");
    $("#companyNameErr").text("");
    $("#contactPersonErr").text("");
    $("#emailErr").text("");
    $("#phoneErr").text("");
    $("#isActiveErr").text("");
    $("#addressErr").text("");
    $("#paymentTermErr").text("");
}

function showVendorErrors(errors) {
    $("#nameErr").text(errors.name?.[0] || "");
    $("#companyNameErr").text(errors.company_name?.[0] || "");
    $("#contactPersonErr").text(errors.contact_person?.[0] || "");
    $("#emailErr").text(errors.email?.[0] || "");
    $("#phoneErr").text(errors.phone?.[0] || "");
    $("#isActiveErr").text(errors.is_active?.[0] || "");
    $("#addressErr").text(errors.address?.[0] || "");
    $("#paymentTermErr").text(errors.payment_term_id?.[0] || "");
}

function createVendor() {

    const form = $("#createVendorForm");
    const button = form.find('button[type="submit"]');

    clearVendorErrors();
    button.prop("disabled", true);

    $.ajax({
        url: form.attr("action"),
        type: "POST",
        data: form.serialize(),

        headers: {
            "Accept": "application/json"
        },

        success: function(response) {

            showToast("success", response.message);

            setTimeout(function() {
                window.location.href = "{{ route('vendors.index') }}";
            }, 800);
        },

        error: function(xhr) {

            if (xhr.status === 422) {
                showVendorErrors(xhr.responseJSON.errors || {});
            } else {
                showToast(
                    "error",
                    xhr.responseJSON?.message || "Unable to create vendor."
                );
            }

            button.prop("disabled", false);
        }
    });
}

$("#createVendorForm").on("submit", function(e) {
    e.preventDefault();

    createVendor();
});

</script>
@endpush
</x-layouts.app>
