<x-layouts.app title="Edit Purchase Request">

    <div class="mb-6 flex items-center justify-between">

        <div>

            <h1 class="text-2xl font-bold text-slate-900">
                Edit Purchase Request
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Update purchase request information and materials.
            </p>

        </div>


        <a href="{{ route('purchase-requests.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
            <i class="bx bx-arrow-back"></i>

            Back
        </a>

    </div>


    <form id="purchaseRequestForm" action="{{ route('purchase-requests.update', $purchaseRequest) }}" method="POST"
        novalidate>
        @csrf
        @method('PUT')
        @include('purchase_requests._form')
        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('purchase-requests.index') }}"
                class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                Cancel
            </a>
            <button type="submit" id="submitBtn"
                class="inline-flex items-center gap-2 rounded-lg bg-slate-800 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-slate-900 disabled:cursor-not-allowed disabled:opacity-60">
                <i class="bx bx-save"></i>
                Update Purchase Request
            </button>

        </div>

    </form>


    @push('scripts')
<script>
    $('#purchaseRequestForm').on('submit', function(e) {

        e.preventDefault();

        clearErrors();

        const form = $(this);
        const button = form.find('#submitBtn');

        button.prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            headers: {
                'Accept': 'application/json'
            },
            success: function(response) {
                showToast('success', response.message);
                
                button.prop('disabled', false);
                setTimeout(function(){
                    window.location.href = "{{ route('purchase-requests.index') }}";
                },500)
            },

            error: function(xhr) {
                button.prop('disabled', false);
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


    function clearErrors() {
        $('#statusErr').text('');
        $('#notesErr').text('');
        $('#deliveryAddressErr').text('');
    }


    function showValidationErrors(errors) {

        $('#statusErr').text(
            errors.status?.[0] || ''
        );
        $('#notesErr').text(
            errors.notes?.[0] || ''
        );

        $('#deliveryAddressErr').text(
            errors.delivery_address?.[0] || ''
        );
        let itemErrorShown = false;
        $.each(errors, function(key, messages) {

            if (
                !itemErrorShown &&
                (
                    key === 'items' ||
                    key.startsWith('items.')
                )
            ) {
                showToast('error', messages[0]);
                itemErrorShown = true;
            }

        });
    }
</script>
    @endpush

</x-layouts.app>
