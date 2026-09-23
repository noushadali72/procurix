<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1e293b;
            font-size: 12px;
        }

        .header {
            border-bottom: 2px solid #334155;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
        }

        .company-subtitle {
            margin-top: 4px;
            font-size: 10px;
            color: #64748b;
        }

        .document-title {
            font-size: 16px;
            font-weight: bold;
            color: #334155;
        }

        .document-number {
            margin-top: 5px;
            font-size: 11px;
            color: #64748b;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
        }

        .subtitle {
            color: #64748b;
            margin-top: 5px;
        }

        .section {
            margin-bottom: 22px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #334155;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 6px 0;
        }

        .label {
            width: 35%;
            color: #64748b;
        }

        .value {
            font-weight: bold;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
        }

        .items th {
            background: #f1f5f9;
            color: #334155;
            font-weight: bold;
            text-align: left;
        }

        .items th,
        .items td {
            border: 1px solid #cbd5e1;
            padding: 9px;
        }

        .items .qty {
            text-align: right;
        }

        .notes {
            background: #f8fafc;
            border-left: 3px solid #64748b;
            padding: 10px 12px;
        }

        .footer {
            margin-top: 35px;
            padding-top: 12px;
            border-top: 1px solid #cbd5e1;
            color: #64748b;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="header">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    <div class="company-name">
                        {{ config('app.name') }}
                    </div>

                    <div class="company-subtitle">
                        Procurement Management System
                    </div>
                </td>

                <td style="width: 40%; text-align: right; vertical-align: top;">
                    <div class="document-title">
                        REQUEST FOR QUOTATION
                    </div>

                    <div class="document-number">
                        {{ $purchaseRequest->request_number }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Vendor --}}
    <div class="section">

        <div class="section-title">
            Vendor Information
        </div>

        <table class="info-table">
            <tr>
                <td class="label">Vendor</td>
                <td class="value">
                    {{ $purchaseRequest->vendor->company_name ?: $purchaseRequest->vendor->name }}
                </td>
            </tr>

            @if ($purchaseRequest->vendor->contact_person)
                <tr>
                    <td class="label">Contact Person</td>
                    <td>
                        {{ $purchaseRequest->vendor->contact_person }}
                    </td>
                </tr>
            @endif

            @if ($purchaseRequest->vendor->email)
                <tr>
                    <td class="label">Email</td>
                    <td>
                        {{ $purchaseRequest->vendor->email }}
                    </td>
                </tr>
            @endif

            @if ($purchaseRequest->vendor->phone)
                <tr>
                    <td class="label">Phone</td>
                    <td>
                        {{ $purchaseRequest->vendor->phone }}
                    </td>
                </tr>
            @endif
        </table>

    </div>

    {{-- Request Information --}}
    <div class="section">

        <div class="section-title">
            Request Information
        </div>

        <table class="info-table">

            <tr>
                <td class="label">Request Number</td>
                <td class="value">
                    {{ $purchaseRequest->request_number }}
                </td>
            </tr>

            <tr>
                <td class="label">Request Date</td>
                <td>
                    {{ $purchaseRequest->created_at->format('d M Y') }}
                </td>
            </tr>

            @if ($purchaseRequest->due_date)
                <tr>
                    <td class="label">Quotation Due Date</td>
                    <td>
                        {{ \Carbon\Carbon::parse($purchaseRequest->due_date)->format('d M Y') }}
                    </td>
                </tr>
            @endif

            <tr>
                <td class="label">Delivery Address</td>
                <td>
                    {{ $purchaseRequest->delivery_address ?: 'Not specified' }}
                </td>
            </tr>

        </table>

    </div>

    {{-- Items --}}
    <div class="section">

        <div class="section-title">
            Requested Materials
        </div>

        <table class="items">

            <thead>
                <tr>
                    <th style="width: 7%;">#</th>
                    <th>Raw Material</th>
                    <th style="width: 18%;" class="qty">Quantity</th>
                    <th style="width: 18%;">Unit Cost</th>
                    <th style="width: 20%;">Line Total</th>
                </tr>
            </thead>

            <tbody>

                @php
                    $grandTotal = 0;
                @endphp

                @foreach ($purchaseRequest->items as $index => $item)
                    @php
                        $lineTotal = $item->qty * $item->unit_cost;
                        $grandTotal += $lineTotal;
                    @endphp

                    <tr>
                        <td>
                            {{ $index + 1 }}
                        </td>

                        <td>
                            {{ $item->rawMaterial->name }}

                            @if ($item->rawMaterial->sku)
                                <span style="color:#64748b;">
                                    ({{ $item->rawMaterial->sku }})
                                </span>
                            @endif
                        </td>

                        <td class="qty">
                            {{ $item->qty }} {{ $item->unit->short_name }}
                        </td>

                        <td>
                            {{ number_format($item->unit_cost, 2) }}
                        </td>

                        <td>
                            {{ number_format($lineTotal, 2) }}
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="4" style="text-align: right; font-weight: bold;">
                        Grand Total
                    </td>

                    <td style="font-weight: bold;">
                        {{ number_format($grandTotal, 2) }}
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

    {{-- Notes --}}
    @if ($purchaseRequest->notes)
        <div class="section">

            <div class="section-title">
                Notes
            </div>

            <div class="notes">
                {{ $purchaseRequest->notes }}
            </div>

        </div>
    @endif

    <div class="footer">
        This document was generated automatically by the procurement system.
    </div>

</body>

</html>
