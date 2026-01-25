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
        Schema::table('project_members', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('role'); // pending, accepted
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('invite_token')->nullable()->unique()->after('color');
            $table->string('invite_role')->default('contributor')->after('invite_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_members', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['invite_token', 'invite_role']);
        });
    }
};
