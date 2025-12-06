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
        Schema::create('product_variation_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variation_id')->constrained('product_variations')->onDelete('cascade');
            $table->enum('variation_type', ['text', 'color','image'])->nullable()->default('text');
            $table->string('value'); // e.g., "Red", "Blue", "Small", "Large"
            $table->decimal('price', 10, 2)->nullable(); // Optional different price for this variation
            $table->integer('stock')->default(0); // Stock per variation
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variation_options');
    }
};
