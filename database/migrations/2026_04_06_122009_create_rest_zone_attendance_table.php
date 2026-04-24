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
    Schema::create('rest_zone_attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('rest_zone_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->date('attendance_date');
        $table->time('check_in_time');
        $table->time('expected_checkout');
        $table->time('check_out_time')->nullable();
        $table->time('actual_checkout')->nullable();
        $table->string('reason')->nullable();
        $table->enum('status', ['pending', 'confirmed', 'completed', 'declined'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rest_zone_attendances');
    }
};
