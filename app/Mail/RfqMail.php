<?php

namespace App\Mail;

use App\Models\PurchaseRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RfqMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PurchaseRequest $purchaseRequest
    ) {}

    public function build()
    {
        $pdf = Pdf::loadView('pdfs.rfq', [
            'purchaseRequest' => $this->purchaseRequest,
        ]);

        return $this->subject('Request for Quotation - ' .$this->purchaseRequest->request_number)
            ->view('emails.rfq')
            ->attachData(
                $pdf->output(),
                $this->purchaseRequest->request_number . '.pdf',
                [
                    'mime' => 'application/pdf',
                ]
            );
    }
}
