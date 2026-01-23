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
        Schema::create('references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Core metadata
            $table->string('title');
            $table->json('authors')->nullable(); // Array of author names
            $table->enum('type', ['book', 'journal', 'website', 'conference', 'thesis', 'report', 'other'])->default('other');
            $table->string('year', 10)->nullable();

            // Identifiers
            $table->string('doi')->nullable()->index();
            $table->string('isbn')->nullable()->index();
            $table->string('url')->nullable();

            // Publication details
            $table->string('publisher')->nullable();
            $table->string('journal_name')->nullable();
            $table->string('volume')->nullable();
            $table->string('issue')->nullable();
            $table->string('pages')->nullable();
            $table->string('edition')->nullable();

            // Additional info
            $table->text('abstract')->nullable();
            $table->text('notes')->nullable();
            $table->json('tags')->nullable(); // For categorization

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('references');
    }
};
