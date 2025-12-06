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
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp')->after('email_verified_at')->nullable();
            $table->timestamp('otp_expires_at')->after('otp')->nullable();
            $table->boolean('is_verified')->after('otp_expires_at')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['otp','otp_expires_at','is_verified']);
        });
    }
};
