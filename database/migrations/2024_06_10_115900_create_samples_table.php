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
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('no_sample');
            $table->string('no_batch');
            $table->text('deskripsi_sample');
            $table->datetime('tanggal_terima');
            $table->unsignedBigInteger('parameter_testing_id');
            $table->foreign('parameter_testing_id')->references('id')->on('parameter_testings');
            $table->datetime('tenggat_testing');
            $table->integer('jumlah_sampel');
            $table->unsignedBigInteger('pic');
            $table->foreign('pic')->references('id')->on('users');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('samples');
    }
};
