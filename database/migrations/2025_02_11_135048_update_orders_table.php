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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('total_amount'); // Payment method (e.g., razorpay, cod)
            $table->enum('payment_status', ['pending', 'failed', 'success'])->default('pending')->after('payment_method'); // Payment status
            $table->string('transaction_id')->nullable()->after('payment_status'); // Payment gateway transaction ID
            $table->timestamp('payment_date')->nullable()->after('transaction_id'); // Payment date

            // Order status
            $table->enum('order_status', [
                'pending',        // Initial state
                'confirmed',      // Payment confirmed or COD accepted
                'processing',     // Preparing order
                'shipped',        // Order shipped
                'delivered',      // Order delivered
                'cancelled',      // Order cancelled
                'refunded'        // Refund processed
            ])->default('pending')->after('payment_date'); // Default to pending
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method','payment_status','transaction_id','payment_date','order_status']);
        });
    }
};
