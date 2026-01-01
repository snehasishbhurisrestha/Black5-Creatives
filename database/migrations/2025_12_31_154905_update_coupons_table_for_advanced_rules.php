<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {

            // Add columns first (safe)
            $table->decimal('override_price', 10, 2)->nullable()->after('value');
            $table->integer('min_qty')->nullable()->after('minimum_purchase');

            $table->string('category')->nullable()->after('min_qty');
            $table->string('product_type')->nullable()->after('category');

            $table->integer('buy_qty')->nullable()->after('product_type');
            $table->integer('get_qty')->nullable()->after('buy_qty');
            $table->string('free_product_type')->nullable()->after('get_qty');

            $table->longText('description')->nullable()->after('is_active');
        });

        // Change ENUM separately using SQL (No DBAL needed)
        DB::statement("
            ALTER TABLE coupons 
            MODIFY type ENUM(
                'percentage',
                'flat',
                'free_shipping',
                'bogo',
                'price_override'
            ) DEFAULT 'percentage'
        ");

        // Change date → datetime
        DB::statement("ALTER TABLE coupons MODIFY start_date DATETIME NULL");
        DB::statement("ALTER TABLE coupons MODIFY end_date DATETIME NULL");
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn([
                'override_price',
                'min_qty',
                'category',
                'product_type',
                'buy_qty',
                'get_qty',
                'free_product_type',
                'description',
            ]);
        });

        DB::statement("
            ALTER TABLE coupons 
            MODIFY type ENUM('percentage','flat') DEFAULT 'percentage'
        ");

        DB::statement("ALTER TABLE coupons MODIFY start_date DATE");
        DB::statement("ALTER TABLE coupons MODIFY end_date DATE");
    }
};

