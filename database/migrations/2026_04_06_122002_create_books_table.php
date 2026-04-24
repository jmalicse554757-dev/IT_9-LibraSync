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
    Schema::create('books', function (Blueprint $table) {
        $table->id();
        $table->string('book_id')->unique();
        $table->string('isbn')->nullable();
        $table->string('title');
        $table->string('author');
        $table->string('publisher')->nullable();
        $table->year('year_published')->nullable();
        $table->string('edition')->nullable();
        $table->foreignId('college_id')->nullable()->constrained()->nullOnDelete();
        $table->string('category')->nullable();
        $table->string('program')->nullable();
        $table->integer('stock')->default(0);
        $table->string('shelf_location')->nullable();
        $table->string('cover_image')->nullable();
        $table->text('description')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
