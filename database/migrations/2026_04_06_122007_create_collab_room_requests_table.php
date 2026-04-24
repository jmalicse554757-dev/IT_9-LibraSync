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
    Schema::create('collab_room_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('collab_room_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->date('request_date');
        $table->string('time_slot');
        $table->integer('occupant_count');
        $table->text('occupant_names');
        $table->string('purpose')->nullable();
        $table->enum('status', ['pending', 'approved', 'declined'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collab_room_requests');
    }
};
