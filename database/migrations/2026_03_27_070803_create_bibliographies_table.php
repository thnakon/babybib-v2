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
        Schema::create('bibliographies', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('user_id')->nullable();
            $table->integer('resource_type_id');
            $table->integer('project_id')->nullable();
            $table->longText('data');
            $table->text('bibliography_text');
            $table->text('citation_parenthetical')->nullable();
            $table->text('citation_narrative')->nullable();
            $table->string('language', 2)->default('th');
            $table->string('author_sort_key', 255)->nullable();
            $table->integer('year')->nullable();
            $table->string('year_suffix', 5)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('resource_type_id')->references('id')->on('resource_types');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bibliographies');
    }
};
