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
        Schema::create('manufacturing_formula_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manufacturing_formula_id')->constrained('manufacturing_formulas')->cascadeOnDelete();
            $table->foreignId('raw_material_id')->nullable()->constrained('raw_materials')->nullOnDelete();
            $table->decimal('quantity',15,3)->default(1);
            $table->foreignId('unit_id')->constrained('units')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturing_formula_items');
    }
};
