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
        Schema::create('user_ratings', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->integer('user_id')->unsigned()->nullable();
            $table->tinyInteger('rating');
            $table->string('page_url', 255)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('session_id', 128)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('rating');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_ratings');
    }
};
