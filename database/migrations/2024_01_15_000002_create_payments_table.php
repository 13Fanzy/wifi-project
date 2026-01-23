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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('bulan_tagihan', 7); // Format: YYYY-MM
            $table->datetime('tanggal_bayar');
            $table->decimal('jumlah_bayar', 10, 2);
            $table->enum('status_pembayaran', ['Lebih Awal', 'Tepat Waktu', 'Terlambat']);
            $table->text('catatan')->nullable();
            $table->timestamps();

            // Unique constraint: satu pembayaran per pelanggan per bulan
            $table->unique(['customer_id', 'bulan_tagihan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
