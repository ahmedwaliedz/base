<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->boolean('is_read')->default(false)->after('message');
            $table->softDeletes();
        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->boolean('is_read')->default(false)->after('complaint');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn('is_read');
            $table->dropSoftDeletes();
        });

        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn('is_read');
            $table->dropSoftDeletes();
        });
    }
};
