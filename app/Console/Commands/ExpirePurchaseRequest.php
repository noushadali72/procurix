<?php

namespace App\Console\Commands;

use App\Models\PurchaseRequest;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('purchaserequest:watch')]
#[Description('The command will check for purchase request which are expired according to due date and update status of the PR to expired.')]
class ExpirePurchaseRequest extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Watching purchase requests...');

        $current_date = now()->toDateString();
        PurchaseRequest::where('due_date','<',$current_date)->update([
            'status'=>'expired'
        ]);
        
    }
}
