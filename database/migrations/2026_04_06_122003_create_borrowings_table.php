<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->string('receipt_no')->unique()->nullable();
            $table->date('date_borrowed')->nullable();
            $table->date('due_date')->nullable();
            $table->date('date_returned')->nullable();
            $table->integer('school_days_loan')->nullable();
            $table->string('book_condition')->nullable();
            $table->string('borrow_status')->default('active');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};