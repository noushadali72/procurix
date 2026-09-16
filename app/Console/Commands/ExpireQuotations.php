<?php

namespace App\Console\Commands;

use App\Models\Quotation;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('quotation:watch')]
#[Description('Command description')]
class ExpireQuotations extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Watching quotations...');

        $current_date = now()->toDateString();
        Quotation::where('valid_until','<',$current_date)->update([
            'status'=>'expired'
        ]);
        
    }
}
