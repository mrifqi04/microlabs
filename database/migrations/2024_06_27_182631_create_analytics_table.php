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
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_id');
            $table->foreign('sample_id')->references('id')->on('samples');
            $table->unsignedBigInteger('instrument_id');
            $table->foreign('instrument_id')->references('id')->on('instruments');
            $table->datetime('scan_in')->nullable();
            $table->datetime('scan_out')->nullable();
            $table->unsignedBigInteger('pic');
            $table->foreign('pic')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};
