<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_credits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_return_id')
                ->unique()
                ->constrained('purchase_returns')
                ->restrictOnDelete();

            $table->foreignId('vendor_id')
                ->constrained('vendors')
                ->restrictOnDelete();

            /*
             * Nullable because the material may be returned
             * before a Vendor Bill exists.
             */
            $table->foreignId('vendor_bill_id')
                ->nullable()
                ->constrained('vendor_bills')
                ->restrictOnDelete();

            $table->string('credit_number')->unique();

            $table->date('credit_date');

            $table->decimal('amount', 20, 2);
            $table->decimal('applied_amount', 20, 2)->default(0);
            $table->decimal('refunded_amount', 20, 2)->default(0);

            $table->enum('status', [
                'open',
                'partially_applied',
                'applied',
                'refunded'
            ])->default('open');

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_credits');
    }
};