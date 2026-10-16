<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking', 30)->unique();
            $table->string('nama_pelanggan', 120)->default('Pelanggan');
            $table->string('merek_motor', 80);
            $table->string('tipe_motor', 100);
            $table->string('nomor_plat', 20);
            $table->text('keluhan');
            $table->date('tanggal');
            $table->time('jam');
            $table->foreignId('service_id')->constrained('services')->restrictOnDelete();
            $table->string('mekanik_name', 120);
            $table->enum('status_pengerjaan', ['antrian', 'pengecekan', 'pengerjaan', 'test_drive', 'selesai'])->default('antrian');
            $table->enum('status_pembayaran', ['belum_dibayar', 'lunas'])->default('belum_dibayar');
            $table->unsignedInteger('total_harga')->default(0);
            $table->timestamps();

            $table->index(['tanggal', 'jam']);
            $table->index('status_pengerjaan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
