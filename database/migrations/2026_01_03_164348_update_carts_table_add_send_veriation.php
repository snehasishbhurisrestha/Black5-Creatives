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
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('product_variation_options_id2')->after('product_variation_options_id')->nullable();
            $table->foreign('product_variation_options_id2')->references('id')->on('product_variation_options')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['product_variation_options_id2']);
            $table->dropColumn('product_variation_options_id2');
        });
    }
};
