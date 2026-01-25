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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('status')->default('planning')->after('description'); // planning, active, completed, on_hold
            $table->string('priority')->default('medium')->after('status'); // low, medium, high, urgent
            $table->date('due_date')->nullable()->after('priority');
            $table->integer('progress')->default(0)->after('due_date'); // 0-100
            $table->string('visibility')->default('private')->after('progress'); // private, team
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['status', 'priority', 'due_date', 'progress', 'visibility']);
        });
    }
};
