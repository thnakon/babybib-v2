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
        Schema::table('project_comments', function (Blueprint $table) {
            $table->json('attachments')->nullable()->after('content');
            $table->dropColumn('attachment_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_comments', function (Blueprint $table) {
            $table->string('attachment_path')->nullable()->after('content');
            $table->dropColumn('attachments');
        });
    }
};
