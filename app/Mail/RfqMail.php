<?php

namespace App\Mail;

use App\Models\PurchaseRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RfqMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PurchaseRequest $purchaseRequest
    ) {
    }

    public function build()
    {
        return $this
            ->subject('Request for Quotation - ' . $this->purchaseRequest->request_number)
            ->view('emails.rfq');
    }
}

