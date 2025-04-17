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
        Schema::create('seo_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seo_id')->constrained()->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('meta_keywords');
            $table->unique(['seo_id', 'locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_translations');
    }
};
