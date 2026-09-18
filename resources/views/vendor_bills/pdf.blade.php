<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Vendor Bill {{ $vendorBill->bill_number }}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1f2937;
        }

        .container {
            width: 100%;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .company {
            float: left;
            width: 55%;
        }

        .bill-info {
            float: right;
            width: 40%;
            text-align: right;
        }

        .company-name {
            margin: 0 0 5px;
            font-size: 20px;
            font-weight: bold;
            color: #111827;
        }

        .company p,
        .bill-info p {
            margin: 3px 0;
            color: #6b7280;
        }

        .title {
            margin-top: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #111827;
        }

        .title h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
            color: #111827;
        }

        .status {
            display: inline-block;
            margin-top: 8px;
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-unpaid {
            background: #fef3c7;
            color: #92400e;
        }

        .status-paid {
            background: #dcfce7;
            color: #166534;
        }

        .status-partial {
            background: #dbeafe;
            color: #1e40af;
        }

        .info-section {
            margin-top: 25px;
        }

        .info-box {
            float: left;
            width: 48%;
            padding: 15px;
            border: 1px solid #e5e7eb;
        }

        .info-box.right {
            float: right;
        }

        .section-title {
            margin: 0 0 10px;
            font-size: 12px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
        }

        .info-row {
            margin-bottom: 6px;
        }

        .label {
            display: inline-block;
            width: 110px;
            color: #6b7280;
        }

        .value {
            font-weight: 600;
            color: #111827;
        }

        .items-section {
            margin-top: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th {
            padding: 9px 8px;
            border-top: 1px solid #d1d5db;
            border-bottom: 1px solid #d1d5db;
            background: #f9fafb;
            font-size: 10px;
            text-align: left;
            text-transform: uppercase;
            color: #4b5563;
        }

        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        P::.text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .item-name {
            font-weight: 600;
            color: #111827;
        }

        .item-description {
            margin-top: 3px;
            font-size: 10px;
            color: #6b7280;
        }

        .summary-wrapper {
            margin-top: 20px;
        }

        .notes {
            float: left;
            width: 55%;
            padding-right: 30px;
        }

        .summary {
            float: right;
            width: 40%;
        }

        .summary-table td {
            padding: 6px 0;
        }

        .summary-label {
            color: #6b7280;
        }

        .summary-value {
            text-align: right;
            font-weight: 600;
        }

        .grand-total td {
            padding-top: 10px;
            border-top: 2px solid #111827;
            font-size: 14px;
            font-weight: bold;
            color: #111827;
        }

        .notes-box {
            min-height: 80px;
            padding: 12px;
            border: 1px solid #e5e7eb;
            color: #4b5563;
            line-height: 1.5;
        }

        .payment-section {
            margin-top: 25px;
            padding: 15px;
            border: 1px solid #e5e7eb;
        }

        .footer {
            margin-top: 40px;
            padding-top: 12px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
        }
    </style>
</head>

<body>

<div class="container">

    {{-- Company Information --}}
    <div class="clearfix">

        <div class="company">
            @if (!empty($company['logo']))
                <img
                    src="{{ $company['logo'] }}"
                    alt="Company Logo"
                    style="max-width: 140px; max-height: 60px; margin-bottom: 8px;"
                >
            @endif

            <h2 class="company-name">
                {{ $company['name'] ?? 'Fossphorus' }}
            </h2>

            <p>{{ $company['address'] ?? '4th Floor, Azad Trade Center Near Civic Center KHI.' }}</p>
            <p>
                {{ $company['phone'] ?? '+932234234232' }}
                @if (!empty($company['email']))
                    &nbsp; | &nbsp; {{ $company['email'] }}
                @endif
            </p>
        </div>

        <div class="bill-info">
            <p><strong>Vendor Bill</strong></p>
            <p>
                Bill #:
                <strong>{{ $vendorBill->bill_number }}</strong>
            </p>
            <p>
                Bill Date:
                {{ \Carbon\Carbon::parse($vendorBill->bill_date)->format('d M Y') }}
            </p>
            <p>
                Due Date:
                {{ \Carbon\Carbon::parse($vendorBill->due_date)->format('d M Y') }}
            </p>

            @php
                $statusClass = match ($vendorBill->status) {
                    'paid' => 'status-paid',
                    'partially_paid' => 'status-partial',
                    default => 'status-unpaid',
                };
            @endphp

            <span class="status {{ $statusClass }}">
                {{ ucfirst(str_replace('_', ' ', $vendorBill->status)) }}
            </span>
        </div>

    </div>

    {{-- Title --}}
    <div class="title">
        <h1>VENDOR BILL</h1>
    </div>

    {{-- Vendor / Purchase Order Information --}}
    <div class="info-section clearfix">

        {{-- Vendor --}}
        <div class="info-box">

            <h3 class="section-title">
                Vendor Information
            </h3>

            <div class="info-row">
                <span class="label">Vendor:</span>
                <span class="value">
                    {{ $vendorBill->vendor->name ?? '-' }}
                </span>
            </div>

            <div class="info-row">
                <span class="label">Contact:</span>
                <span class="value">
                    {{ $vendorBill->vendor->phone ?? '-' }}
                </span>
            </div>

            <div class="info-row">
                <span class="label">Email:</span>
                <span class="value">
                    {{ $vendorBill->vendor->email ?? '-' }}
                </span>
            </div>

            <div class="info-row">
                <span class="label">Address:</span>
                <span class="value">
                    {{ $vendorBill->vendor->address ?? '-' }}
                </span>
            </div>

        </div>

        {{-- Purchase Order --}}
        <div class="info-box right">

            <h3 class="section-title">
                Purchase Order
            </h3>

            <div class="info-row">
                <span class="label">PO Number:</span>
                <span class="value">
                    {{ $vendorBill->purchaseOrder->order_number ?? '-' }}
                </span>
            </div>

            <div class="info-row">
                <span class="label">PO Date:</span>
                <span class="value">
                    {{ $vendorBill->purchaseOrder?->order_date
                        ? \Carbon\Carbon::parse($vendorBill->purchaseOrder->order_date)->format('d M Y')
                        : '-' }}
                </span>
            </div>

            <div class="info-row">
                <span class="label">PO Status:</span>
                <span class="value">
                    {{ ucfirst(str_replace('_', ' ', $vendorBill->purchaseOrder->status ?? '-')) }}
                </span>
            </div>

        </div>

    </div>

    {{-- Items --}}
    <div class="items-section">

        <h3 class="section-title">
            Bill Items
        </h3>

        <table class="items-table">

            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 35%;">Item</th>
                    <th style="width: 12%;" class="text-center">Unit</th>
                    <th style="width: 12%;" class="text-right">Qty</th>
                    <th style="width: 16%;" class="text-right">Unit Price</th>
                    <th style="width: 20%;" class="text-right">Line Total</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($vendorBill->items as $index => $item)

                    <tr>

                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>
                            <div class="item-name">
                                {{ $item->rawMaterial->name ?? $item->product->name ?? '-' }}
                            </div>

                            @if (!empty($item->description))
                                <div class="item-description">
                                    {{ $item->description }}
                                </div>
                            @endif
                        </td>

                        <td class="text-center">
                            {{ $item->unit->short_name ?? '-' }}
                        </td>

                        <td class="text-right">
                            {{ number_format($item->qty, 2) }}
                        </td>

                        <td class="text-right">
                            {{ number_format($item->unit_price, 2) }}
                        </td>

                        <td class="text-right">
                            {{ number_format($item->line_total, 2) }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center">
                            No items found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Notes + Summary --}}
    <div class="summary-wrapper clearfix">

        {{-- Notes --}}
        <div class="notes">

            <h3 class="section-title">
                Notes
            </h3>

            <div class="notes-box">
                {{ $vendorBill->notes ?: 'No notes provided.' }}
            </div>

        </div>

        {{-- Summary --}}
        <div class="summary">

            <h3 class="section-title">
                Bill Summary
            </h3>

            <table class="summary-table">

                <tr>
                    <td class="summary-label">
                        Subtotal
                    </td>

                    <td class="summary-value">
                        {{ number_format($vendorBill->subtotal, 2) }}
                    </td>
                </tr>

                <tr>
                    <td class="summary-label">
                        Tax
                    </td>

                    <td class="summary-value">
                        {{ number_format($vendorBill->tax, 2) }}
                    </td>
                </tr>

                @if (!empty($vendorBill->discount))
                    <tr>
                        <td class="summary-label">
                            Discount
                        </td>

                        <td class="summary-value">
                            -{{ number_format($vendorBill->discount, 2) }}
                        </td>
                    </tr>
                @endif

                <tr class="grand-total">
                    <td>
                        Total
                    </td>

                    <td class="text-right">
                        {{ number_format($vendorBill->total, 2) }}
                    </td>
                </tr>

            </table>

        </div>

    </div>

    {{-- Payment Information --}}
    <div class="payment-section">

        <h3 class="section-title">
            Payment Information
        </h3>

        <div class="clearfix">

            <div style="float: left; width: 50%;">
                <div class="info-row">
                    <span class="label">Status:</span>
                    <span class="value">
                        {{ ucfirst(str_replace('_', ' ', $vendorBill->status)) }}
                    </span>
                </div>
            </div>

            <div style="float: right; width: 50%;">
                <div class="info-row">
                    <span class="label">Amount Due:</span>
                    <span class="value">
                        {{ number_format($vendorBill->total, 2) }}
                    </span>
                </div>
            </div>

        </div>

    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>
            Thank you for your business.
        </p>

        <p>
            This is a computer-generated document and does not require a signature.
        </p>
    </div>

</div>

</body>
</html>