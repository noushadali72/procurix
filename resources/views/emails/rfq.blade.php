<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request for Quotation</title>
</head>

<body style="margin:0; padding:0; background:#f4f6f8; font-family:Arial, Helvetica, sans-serif; color:#1e293b;">

    <table width="100%" cellpadding="0" cellspacing="0"
        style="background:#f4f6f8; padding:30px 15px;">

        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="max-width:600px; width:100%; background:#ffffff; border:1px solid #e2e8f0;">

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

                    <tr>
                        <td style="padding:30px;">

                            <p style="margin:0 0 15px; font-size:15px;">
                                Dear
                                {{ $purchaseRequest->vendor->company_name ?: $purchaseRequest->vendor->name }},
                            </p>

                            <p style="margin:0 0 15px; font-size:14px; line-height:1.6; color:#475569;">
                                We would like to request a quotation for the materials
                                listed in the attached Request for Quotation document.
                            </p>

                            <p style="margin:0 0 20px; font-size:14px; line-height:1.6; color:#475569;">
                                Please review the attached RFQ and provide your quotation,
                                including pricing and expected delivery details.
                            </p>

                            <p style="margin:0; font-size:14px; line-height:1.6; color:#475569;">
                                <strong>Request Number:</strong>
                                {{ $purchaseRequest->request_number }}
                            </p>

                            @if ($purchaseRequest->due_date)
                                <p style="margin:8px 0 0; font-size:14px; color:#475569;">
                                    <strong>Quotation Due Date:</strong>
                                    {{ \Carbon\Carbon::parse($purchaseRequest->due_date)->format('d M Y') }}
                                </p>
                            @endif

                            <p style="margin:25px 0 0; font-size:14px; line-height:1.6; color:#475569;">
                                The detailed RFQ is attached to this email for your reference.
                            </p>

                            <p style="margin:20px 0 0; font-size:14px; color:#475569;">
                                Regards,<br>
                                Procurement Team
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 30px; border-top:1px solid #e2e8f0; background:#f8fafc;">

                            <p style="margin:0; font-size:12px; color:#64748b; text-align:center;">
                                This is an automated email.
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>

    </table>

</body>

</html>