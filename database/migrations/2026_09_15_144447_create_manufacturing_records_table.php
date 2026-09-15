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

            $table->foreignId('product_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('manufacturing_formula_id')
                ->constrained()
                ->restrictOnDelete();

            $table->decimal('quantity', 12, 4);

            $table->foreignId('unit_id')
                ->constrained()
                ->restrictOnDelete();

            $table->dateTime('manufactured_at');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manufacturing_records');
    }
};