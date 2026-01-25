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
            $table->string('default_citation_style')->default('apa7')->nullable();
            $table->string('ai_language')->default('th')->nullable(); // th, en
            $table->string('default_ai_tone')->default('academic')->nullable(); // academic, simple, professional
            $table->string('theme_preference')->default('system')->nullable(); // light, dark, system
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
