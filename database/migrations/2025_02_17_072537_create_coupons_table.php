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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['percentage', 'flat'])->default('percentage');
            $table->decimal('value', 10, 2);
            $table->decimal('minimum_purchase', 10, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('usage_type', ['one-time', 'multiple'])->default('one-time');
            $table->tinyInteger('is_active')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
