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
    Schema::create('collab_rooms', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('key_code')->nullable();
        $table->integer('capacity')->default(12);
        $table->integer('min_capacity')->default(3);
        $table->integer('max_hours')->default(3);
        $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
        $table->text('rules')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collab_rooms');
    }
};
