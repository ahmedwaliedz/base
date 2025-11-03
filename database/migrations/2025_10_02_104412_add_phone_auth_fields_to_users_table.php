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
            $table->string('phone')->unique()->nullable()->after('email');
            $table->string('phone_normalized')->unique()->nullable()->after('phone');
            $table->string('activation_code')->nullable()->after('phone_normalized');
            $table->timestamp('activation_expires_at')->nullable()->after('activation_code');
            $table->unsignedTinyInteger('activation_attempts')->default(0)->after('activation_expires_at');
            $table->timestamp('last_activation_requested_at')->nullable()->after('activation_attempts');
            $table->boolean('is_active')->default(true)->after('last_activation_requested_at');
            $table->boolean('is_complete_info')->default(true)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'phone_normalized',
                'activation_code',
                'activation_expires_at',
                'activation_attempts',
                'last_activation_requested_at',
                'is_active',
                'is_complete_info',
            ]);
        });
    }
};
