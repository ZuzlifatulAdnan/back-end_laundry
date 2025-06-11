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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('service_type');
            $table->foreignId('mesin_id')->constrained('mesins')->cascadeOnDelete()->cascadeOnUpdate()->nullable();
            $table->date('tanggal_order');
            $table->time('jam_order');
            $table->time('durasi')->nullable();
            $table->string('koin')->nullable();
            $table->string('berat')->nullable();
            $table->string('detergent')->nullable();
            $table->string('catatan')->nullable();
            $table->date('tanggal_ambil')->nullable();
            $table->string('status');
            $table->string('total_biaya');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
