<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manufacturing_records', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->nullable()->constrained()->restrictOnDelete();
            $table->foreignId('manufacturing_formula_id')->nullable()->constrained()->restrictOnDelete();
            $table->decimal('quantity', 12, 4)->nullable();
            $table->foreignId('unit_id')->nullable()->constrained()->restrictOnDelete();
            $table->dateTime('manufactured_at')->nullable();
            $table->enum('status',['draft','completed','progress'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manufacturing_records');
    }
};