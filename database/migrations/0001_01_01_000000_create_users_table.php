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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('username', 50)->unique();
            $table->string('name', 100);
            $table->string('surname', 100);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->enum('org_type', ['university', 'high_school', 'opportunity_school', 'primary_school', 'government', 'private_company', 'personal', 'other'])->default('personal');
            $table->string('org_name', 255)->nullable();
            $table->string('province', 100)->nullable();
            $table->tinyInteger('is_lis_cmu')->default(0);
            $table->string('student_id', 20)->nullable();
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->string('token', 255)->nullable();
            $table->dateTime('token_expiry')->nullable();
            $table->string('reset_token', 255)->nullable();
            $table->dateTime('reset_token_expires')->nullable();
            $table->string('language', 2)->default('th');
            $table->tinyInteger('is_active')->default(1);
            $table->tinyInteger('is_verified')->default(0);
            $table->integer('bibliography_count')->default(0);
            $table->integer('project_count')->default(0);
            $table->dateTime('last_login')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->string('profile_picture', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
