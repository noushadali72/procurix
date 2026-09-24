<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_credit_applications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vendor_credit_id')
                ->constrained('vendor_credits')
                ->cascadeOnDelete();

            $table->foreignId('vendor_bill_id')
                ->constrained('vendor_bills')
                ->restrictOnDelete();

            $table->decimal('amount', 20, 2);

            $table->date('applied_date');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_credit_applications');
    }
};