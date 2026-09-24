<?php

namespace App\Console\Commands;

use App\Models\VendorBill;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('vendorbills:watch')]
#[Description('Command description')]
class CheckVendorBillsDue extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("watching the vendor bills....");
        $current_date = now()->toDateString();
        VendorBill::where('due_date',"<",$current_date)->where('status','!=','paid')->update(['status'=>'overdue']);
       
    }
}
