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
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->morphs('otpable');
            $table->string('changed_value')->nullable();
            $table->string('country_code')->nullable();
            $table->string('verification_code')->nullable();
            $table->timestamp('verification_code_expire_at')->nullable();
            $table->string('type')->nullable()->index();
            $table->string('status')->index();
            $table->unsignedTinyInteger('tries');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
