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
        Schema::create('rfq_comparison_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_request_id')->nullable()->constrained('purchase_requests')->nullOnDelete();
            $table->foreignId('rfq_comparison_id')->constrained('rfq_comparisons')->cascadeOnDelete();
            $table->foreignId('raw_material_id')->nullable()->constrained('raw_materials')->nullOnDelete();
            $table->decimal('qty',12,2)->default(1);
            $table->decimal('unit_cost',15,2)->nullable();
            $table->decimal('line_total',20,2)->nullable();
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfq_comparison_items');
    }
};
