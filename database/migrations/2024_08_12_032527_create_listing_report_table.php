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
        Schema::connection('mysql')->create('listing_report', function (Blueprint $table) {
            $table->id('po_listing_no');
            $table->string('bp_code', 25)->nullable();
            $table->foreign('bp_code')->references('bp_code')->on('business_partner')->onDelete('cascade');
            $table->datetime('date')->nullable();
            $table->string('file', 255)->nullable();
            $table->datetime('upload_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listing_report');
    }
};
