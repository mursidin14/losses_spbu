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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('stok_awal')->default(0);
            $table->string('no_tangki')->default('-');
            $table->string('no_pnbp')->default('-');
            $table->integer('vol_sebelum_penerimaan')->default(0);
            $table->integer('vol_penerimaan_pnbp')->default(0);
            $table->integer('vol_penerimaan_aktual')->default(0);
            $table->integer('susut_tangki')->default(0);
            $table->integer('pengeluaran')->default(0);
            $table->integer('stok_teoritis')->default(0);
            $table->integer('stok_aktual')->default(0);
            $table->integer('susut_pengeluaran')->default(0);
            $table->decimal('toleransi', 3, 1)->default(0.5);
            $table->integer('total_susut')->default(0);
            $table->decimal('susut_harian', 5, 2)->default(0.0);
            $table->decimal('susut_bulanan', 5, 2)->default(0.0);
            $table->decimal('susut_tahunan', 5, 2)->default(0.0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
