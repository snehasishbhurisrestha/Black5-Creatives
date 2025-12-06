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
            $table->tinyInteger('status')->default(1)->after('id');
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone')->nullable()->after('email_verified_at');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');

            $table->string('opt_mobile_no')->nullable()->after('phone_verified_at');
            $table->date('date_of_birth')->nullable()->after('remember_token');
            $table->enum('gender', ['male', 'female','others'])->default('male')->after('date_of_birth');
            $table->text('address')->nullable()->after('gender');
            $table->string('pan_card_number')->nullable()->after('address');
            $table->string('aadhar_card_number')->nullable()->after('pan_card_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'first_name',
                'last_name',
                'phone',
                'phone_verified_at',
                'opt_mobile_no',
                'date_of_birth',
                'gender',
                'address',
                'pan_card_number',
                'aadhar_card_number',
            ]);
        });
    }
};
