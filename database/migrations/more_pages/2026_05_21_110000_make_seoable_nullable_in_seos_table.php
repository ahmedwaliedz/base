<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seos', function (Blueprint $table) {
            $table->string('seoable_type')->nullable()->change();
            $table->unsignedBigInteger('seoable_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('seos', function (Blueprint $table) {
            $table->string('seoable_type')->nullable(false)->change();
            $table->unsignedBigInteger('seoable_id')->nullable(false)->change();
        });
    }
};
