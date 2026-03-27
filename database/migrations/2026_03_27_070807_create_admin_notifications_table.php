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
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->enum('type', ['feedback', 'user', 'system', 'announcement'])->default('system');
            $table->string('title', 255);
            $table->text('message')->nullable();
            $table->string('link', 255)->nullable();
            $table->tinyInteger('is_read')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
