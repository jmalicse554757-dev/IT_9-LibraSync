<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            if (!Schema::hasColumn('borrowings', 'borrow_status')) {
                $table->enum('borrow_status', ['pending', 'approved', 'declined'])
                      ->default('pending')
                      ->after('receipt_no');
            }
        });
    }

    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            if (Schema::hasColumn('borrowings', 'borrow_status')) {
                $table->dropColumn('borrow_status');
            }
        });
    }
};