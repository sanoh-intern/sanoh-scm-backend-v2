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
        Schema::table('subcont_transaction', function (Blueprint $table) {
            $table->date('actual_transaction_date')->nullable()->after('transaction_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subcont_transaction', function (Blueprint $table) {
            $table->dropColumn('actual_transaction_date');
        });
    }
};
