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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('address_book_id');
            $table->decimal('price_subtotal', 10, 2)->default(0.00);
            $table->decimal('price_gst', 10, 2)->default(0.00);
            $table->decimal('price_shipping', 10, 2)->default(0.00);
            $table->decimal('discounted_price', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('address_book_id')->references('id')->on('address_books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
