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
    Schema::create('rest_zones', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->integer('capacity')->default(15);
        $table->integer('max_hours')->default(3);
        $table->foreignId('assigned_librarian_id')->nullable()->constrained('users')->nullOnDelete();
        $table->enum('status', ['available', 'full', 'closed'])->default('available');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rest_zones');
    }
};
