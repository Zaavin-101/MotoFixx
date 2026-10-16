<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('sparepart_orders')) {
            Schema::create('sparepart_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('kode_pesanan', 24)->unique();
                $table->string('payment_method', 30);
                $table->string('status_pembayaran', 30)->default('belum_dibayar');
                $table->string('status_pesanan', 30)->default('menunggu');
                $table->unsignedInteger('total_harga')->default(0);
                $table->timestamps();
                $table->index(['user_id', 'created_at']);
            });
        }

        if (!Schema::hasTable('sparepart_order_items')) {
            Schema::create('sparepart_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sparepart_order_id')->constrained('sparepart_orders')->cascadeOnDelete();
                $table->foreignId('sparepart_id')->nullable()->constrained('spareparts')->nullOnDelete();
                $table->string('nama_sparepart', 120);
                $table->unsignedInteger('harga_satuan');
                $table->unsignedInteger('jumlah');
                $table->unsignedInteger('subtotal');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Intentionally no destructive rollback: this is a repair migration for existing databases.
    }
};
