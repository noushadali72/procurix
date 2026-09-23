<div class="grid grid-cols-1 gap-6 md:grid-cols-2">

    {{-- Name --}}
    <div>
        <label for="name" class="mb-1 block text-sm font-medium text-gray-700">
            Name<sup>*</sup>
        </label>

        <input type="text" id="name" name="name" value="{{ old('name', $vendor->name ?? '') }}"
            placeholder="Enter vendor name" autofocus
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">

        <span id="nameErr" class="mt-1 block text-sm text-red-600"></span>
    </div>


    {{-- Company Name --}}
    <div>
        <label for="company_name" class="mb-1 block text-sm font-medium text-gray-700">
            Company Name
        </label>

        <input type="text" id="company_name" name="company_name"
            value="{{ old('company_name', $vendor->company_name ?? '') }}" placeholder="Enter company name"
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">

        <span id="companyNameErr" class="mt-1 block text-sm text-red-600"></span>
    </div>


    {{-- Contact Person --}}
    <div>
        <label for="contact_person" class="mb-1 block text-sm font-medium text-gray-700">
            Contact Person
        </label>

        <input type="text" id="contact_person" name="contact_person"
            value="{{ old('contact_person', $vendor->contact_person ?? '') }}" placeholder="Enter contact person"
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">

        <span id="contactPersonErr" class="mt-1 block text-sm text-red-600"></span>
    </div>


    {{-- Email --}}
    <div>
        <label for="email" class="mb-1 block text-sm font-medium text-gray-700">
            Email
        </label>

        <input type="email" id="email" name="email" value="{{ old('email', $vendor->email ?? '') }}"
            placeholder="Enter email address"
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">

        <span id="emailErr" class="mt-1 block text-sm text-red-600"></span>
    </div>


    {{-- Phone --}}
    <div>
        <label for="phone" class="mb-1 block text-sm font-medium text-gray-700">
            Phone
        </label>

        <input type="text" id="phone" name="phone" value="{{ old('phone', $vendor->phone ?? '') }}"
            placeholder="Enter phone number"
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">

        <span id="phoneErr" class="mt-1 block text-sm text-red-600"></span>
    </div>



    {{-- ntn --}}
    <div>
        <label for="ntn" class="mb-1 block text-sm font-medium text-gray-700">
            NTN
        </label>

        <input type="text" id="ntn" name="ntn" value="{{ old('ntn', $vendor->ntn ?? '') }}"
            placeholder="Enter NTN of vendor"
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">

        <span id="ntnErr" class="mt-1 block text-sm text-red-600"></span>
    </div>


    {{-- status --}}
    <div>
        <label for="is_active" class="mb-1 block text-sm font-medium text-gray-700">
            Status
        </label>

        <select id="is_active" name="is_active"
            class="w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">
            <option value="1" @selected(old('is_active', $vendor->is_active ?? true))>
                Active
            </option>

            <option value="0" @selected(old('is_active', $vendor->is_active ?? true) == 0)>
                Inactive
            </option>
        </select>

        <span id="isActiveErr" class="mt-1 block text-sm text-red-600"></span>
    </div>


    {{-- Payment Term --}}
    <div>
        <label for="payment_term_id" class="mb-1 block text-sm font-medium text-gray-700">
            Payment Term
        </label>

        <select id="payment_term_id" name="payment_term_id"
            class="w-full rounded-lg border-gray-300 bg-white px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">
            <option value="">Select payment term</option>

            @foreach ($paymentTerms as $paymentTerm)
                <option value="{{ $paymentTerm->id }}" @selected(old('payment_term_id', $vendor->payment_term_id ?? '') == $paymentTerm->id)>
                    {{ $paymentTerm->name }}
                    ({{ $paymentTerm->due_days }} days)
                </option>
            @endforeach
        </select>

        <span id="paymentTermErr" class="mt-1 block text-sm text-red-600"></span>
    </div>

    {{-- Address --}}
    <div class="md:col-span-2">
        <label for="address" class="mb-1 block text-sm font-medium text-gray-700">
            Address
        </label>

        <textarea id="address" name="address" rows="4" placeholder="Enter vendor address"
            class="w-full rounded-lg border-gray-300 px-4 py-2.5 shadow-sm focus:border-gray-500 focus:ring-gray-500">{{ old('address', $vendor->address ?? '') }}</textarea>

        <span id="addressErr" class="mt-1 block text-sm text-red-600"></span>
    </div>

</div>
