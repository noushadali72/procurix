<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'vendor_credit_refunds',
            function (Blueprint $table) {

                $table->id();

                $table
                    ->foreignId('vendor_credit_id')
                    ->constrained('vendor_credits')
                    ->restrictOnDelete();

                $table->decimal(
                    'amount',
                    20,
                    2
                );

                $table->date(
                    'refund_date'
                );

                $table->string(
                    'refund_method'
                )->nullable();

                $table->string(
                    'transaction_id'
                )->nullable();

                $table->string(
                    'reference'
                )->nullable();

                $table->text(
                    'notes'
                )->nullable();

                $table->timestamps();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'vendor_credit_refunds'
        );
    }
};
