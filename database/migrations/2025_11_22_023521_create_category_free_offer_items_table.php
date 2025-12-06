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
        Schema::create('category_free_offer_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_id');          // FK to main offer
            $table->unsignedBigInteger('variation_option_id'); // FK to ProductVariationOption
            $table->integer('free_qty')->default(1);         // admin sets how many free

            $table->timestamps();

            $table->foreign('offer_id')
                ->references('id')->on('category_free_offers')
                ->onDelete('cascade');

            $table->foreign('variation_option_id')
                ->references('id')->on('product_variation_options')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_free_offer_items');
    }
};
