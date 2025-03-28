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
        Schema::create('despensers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('shift_id')->constrained('shifts')->onDelete('cascade');
            $table->date('tanggal');
            $table->json('waktu_masuk')->nullable();
            $table->json('waktu_selesai')->nullable();
            $table->json('name');
            $table->json('nozzle');
            $table->integer('stok_awal')->default(0);
            $table->integer('jumlah')->default(0);
            $table->integer('stok_teoritis')->default(0);
            $table->integer('stok_akhir')->default(0);
            $table->integer('susut_despenser')->default(0);
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
        Schema::dropIfExists('despensers');
    }
};
