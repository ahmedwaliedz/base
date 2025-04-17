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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('name');
            $table->string('phone')->unique()->nullable();
            $table->string('country_code', 5);
            $table->string('full_phone')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_blocked');
            $table->boolean('is_notify');
            $table->string('type')->nullable();
            $table->foreignId('role_id')->nullable()->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
