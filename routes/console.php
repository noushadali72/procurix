<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('stock:watch')->hourly();
Schedule::command('quotation:watch')->dailyAt('00:05');
Schedule::command('purchaserequest:watch')->dailyAt('00:05');
Schedule::command('vendorBills:watch')->dailyAt('00:05');