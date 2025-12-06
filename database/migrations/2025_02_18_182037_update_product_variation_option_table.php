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
        Schema::table('product_variation_options', function (Blueprint $table) {
            $table->string('variation_name',)->nullable()->after('variation_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variation_options', function (Blueprint $table) {
            $table->dropColumn('variation_name');
        });
    }
};
