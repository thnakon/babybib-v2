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
        Schema::create('resource_types', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('code', 50)->unique();
            $table->string('name_th', 255);
            $table->string('name_en', 255);
            $table->string('category', 100);
            $table->string('icon', 50)->default('fa-file');
            $table->json('fields_config');
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_types');
    }
};
