<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_return_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_return_id')
                ->constrained('purchase_returns')
                ->cascadeOnDelete();

            $table->foreignId('goods_receipt_item_id')
                ->constrained('goods_receipt_items')
                ->restrictOnDelete();

            $table->decimal('qty', 20, 3);

            $table->foreignId('unit_id')
                ->constrained('units')
                ->restrictOnDelete();

            /*
             * Financial snapshot.
             *
             * Don't depend forever on the PO price because historical
             * return value should not change if something else changes.
             */
            $table->decimal('unit_cost', 20, 2);
            $table->decimal('line_total', 20, 2);

            $table->text('reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_return_items');
    }
};