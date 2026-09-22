<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Request for Quotation</title>
</head>

<body style="margin:0; padding:0; background:#f4f6f8; font-family:Arial, Helvetica, sans-serif; color:#1e293b;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8; padding:30px 15px;">
        <tr>
            <td align="center">

                <table width="650" cellpadding="0" cellspacing="0"
                    style="max-width:650px; width:100%; background:#ffffff; border:1px solid #e2e8f0; border-radius:8px;">

                    {{-- Header --}}
                    <tr>
                        <td style="padding:25px 30px; border-bottom:1px solid #e2e8f0;">

                            <h1 style="margin:0; font-size:22px; color:#0f172a;">
                                Request for Quotation
                            </h1>

                            <p style="margin:8px 0 0; font-size:14px; color:#64748b;">
                                {{ $purchaseRequest->request_number }}
                            </p>

                        </td>
                    </tr>


                    {{-- Content --}}
                    <tr>
                        <td style="padding:30px;">

                            <p style="margin:0 0 15px; font-size:15px;">
                                Dear {{ $purchaseRequest->vendor->company_name ?: $purchaseRequest->vendor->name }},
                            </p>

                            <p style="margin:0 0 20px; font-size:14px; line-height:1.6; color:#475569;">
                                We would like to request a quotation for the following raw materials.
                                Please provide your best available pricing and delivery details.
                            </p>


                            {{-- Request Information --}}
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="margin-bottom:25px;">

                                <tr>
                                    <td style="padding:8px 0; font-size:13px; color:#64748b;">
                                        Request Number
                                    </td>

                                    <td align="right"
                                        style="padding:8px 0; font-size:13px; font-weight:bold; color:#0f172a;">
                                        {{ $purchaseRequest->request_number }}
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:8px 0; font-size:13px; color:#64748b;">
                                        Delivery Address
                                    </td>

                                    <td align="right"
                                        style="padding:8px 0; font-size:13px; color:#0f172a;">
                                        {{ $purchaseRequest->delivery_address ?: 'Not specified' }}
                                    </td>
                                </tr>

                            </table>


                            {{-- Items --}}
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="border-collapse:collapse; margin-bottom:25px;">

                                <thead>
                                    <tr style="background:#f8fafc;">

                                        <th align="left"
                                            style="padding:12px 10px; border:1px solid #e2e8f0; font-size:12px; color:#475569;">
                                            Raw Material
                                        </th>

                                        <th align="right"
                                            style="padding:12px 10px; border:1px solid #e2e8f0; font-size:12px; color:#475569;">
                                            Quantity
                                        </th>

                                        <th align="left"
                                            style="padding:12px 10px; border:1px solid #e2e8f0; font-size:12px; color:#475569;">
                                            Unit
                                        </th>

                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($purchaseRequest->items as $item)

                                        <tr>

                                            <td style="padding:12px 10px; border:1px solid #e2e8f0; font-size:13px;">
                                                {{ $item->rawMaterial->name }}

                                                @if ($item->rawMaterial->sku)
                                                    <span style="color:#64748b;">
                                                        ({{ $item->rawMaterial->sku }})
                                                    </span>
                                                @endif
                                            </td>

                                            <td align="right"
                                                style="padding:12px 10px; border:1px solid #e2e8f0; font-size:13px;">
                                                {{ $item->qty }}
                                            </td>

                                            <td style="padding:12px 10px; border:1px solid #e2e8f0; font-size:13px;">
                                                {{ $item->unit->name }}
                                                ({{ $item->unit->short_name }})
                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>


                            @if ($purchaseRequest->notes)

                                <div style="margin-bottom:25px; padding:15px; background:#f8fafc; border-left:3px solid #64748b;">

                                    <p style="margin:0 0 5px; font-size:12px; font-weight:bold; color:#475569;">
                                        Notes
                                    </p>

                                    <p style="margin:0; font-size:13px; color:#475569;">
                                        {{ $purchaseRequest->notes }}
                                    </p>

                                </div>

                            @endif


                            <p style="margin:0; font-size:14px; line-height:1.6; color:#475569;">
                                Please review the requested items and send your quotation at your earliest convenience.
                            </p>

                        </td>
                    </tr>


                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 30px; border-top:1px solid #e2e8f0; background:#f8fafc;">

                            <p style="margin:0; font-size:12px; color:#64748b; text-align:center;">
                                This is an automated email. Please do not reply directly to this message.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
