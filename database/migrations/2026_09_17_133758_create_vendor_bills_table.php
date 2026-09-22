<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vendor_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->restrictOnDelete();
            $table->foreignId('vendor_id')->constrained('vendors')->restrictOnDelete();
            $table->string('bill_number')->nullable();
            $table->date('bill_date')->nullable();
            $table->date('due_date')->nullable();
           
            $table->decimal('subtotal',20,2)->default(0);
            $table->decimal('tax',10,2)->default(0);
            $table->decimal('total',20,2)->default(0);
            $table->enum('status',['paid','partially_paid','unpaid','pending',])->default('unpaid');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_bills');
    }
};
