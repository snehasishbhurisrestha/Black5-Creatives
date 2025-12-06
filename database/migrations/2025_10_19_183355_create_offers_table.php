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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. Silver Card
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // store image path
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->enum('type', ['free_shipping', 'bogo', 'discount', 'custom'])->default('custom');
            $table->json('benefits')->nullable(); // store flexible data like discounts, product restrictions, etc.
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
