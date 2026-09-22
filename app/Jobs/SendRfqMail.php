<?php

namespace App\Jobs;

use App\Mail\RfqMail;
use App\Models\PurchaseRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRfqMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public PurchaseRequest $purchaseRequest
    ) {
    }

    public function handle(): void
    {
        $purchaseRequest = $this->purchaseRequest->load([
            'vendor',
            'items.rawMaterial',
            'items.unit',
        ]);
        Mail::to($purchaseRequest->vendor->email)->send(new RfqMail($purchaseRequest));
    }
}

