<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('page_translations', function (Blueprint $table) {
            $table->text('content')->change();
        });

        Schema::table('seo_translations', function (Blueprint $table) {
            $table->text('meta_description')->change();
            $table->text('meta_keywords')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::table('page_translations')->whereRaw('CHAR_LENGTH(content) > 255')->exists()) {
            throw new \RuntimeException('Cannot rollback: page_translations.content contains values longer than 255 characters.');
        }

        if (DB::table('seo_translations')->whereRaw('CHAR_LENGTH(meta_description) > 255')->exists()) {
            throw new \RuntimeException('Cannot rollback: seo_translations.meta_description contains values longer than 255 characters.');
        }

        if (DB::table('seo_translations')->whereRaw('CHAR_LENGTH(meta_keywords) > 255')->exists()) {
            throw new \RuntimeException('Cannot rollback: seo_translations.meta_keywords contains values longer than 255 characters.');
        }

        Schema::table('page_translations', function (Blueprint $table) {
            $table->string('content')->change();
        });

        Schema::table('seo_translations', function (Blueprint $table) {
            $table->string('meta_description')->change();
            $table->string('meta_keywords')->change();
        });
    }
};
