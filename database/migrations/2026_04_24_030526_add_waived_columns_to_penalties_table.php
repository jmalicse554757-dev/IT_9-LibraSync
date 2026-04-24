<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penalties', function (Blueprint $table) {
            $table->timestamp('waived_at')->nullable()->after('paid_at');
            $table->foreignId('waived_by')->nullable()->constrained('users')->nullOnDelete()->after('waived_at');
        });
    }

    public function down(): void
    {
        Schema::table('penalties', function (Blueprint $table) {
            $table->dropColumn(['waived_at', 'waived_by']);
        });
    }
};