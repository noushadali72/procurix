<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id();

            $table->foreignId('goods_receipt_id')
                ->constrained('goods_receipts')
                ->restrictOnDelete();

            $table->string('return_number')->unique();

            $table->date('return_date');

            $table->enum('status', [
                'draft',
                'completed',
                'cancelled'
            ])->default('draft');

            $table->string('reason')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};