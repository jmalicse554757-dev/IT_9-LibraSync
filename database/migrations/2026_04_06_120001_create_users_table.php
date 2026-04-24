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
        $table->id();
        $table->string('student_id')->unique()->nullable();
        $table->string('employee_id')->unique()->nullable();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email')->unique();
        $table->string('password');
        $table->enum('role', ['student', 'librarian', 'admin']);
        $table->enum('status', ['pending', 'active', 'inactive', 'rejected'])->default('pending');
        $table->foreignId('college_id')->nullable()->constrained()->nullOnDelete();
        $table->string('program')->nullable();
        $table->string('year_level')->nullable();
        $table->string('section')->nullable();
        $table->date('date_of_birth')->nullable();
        $table->enum('gender', ['male', 'female', 'other'])->nullable();
        $table->string('contact_number')->nullable();
        $table->timestamp('approved_at')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
