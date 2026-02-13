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
            Schema::create('penjualan_detail', function (Blueprint $table) {
            $table->id();
            $table->string('nota');
            $table->unsignedBigInteger('kd_obat');
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('nota')->references('nota')->on('penjualan')->onDelete('cascade');
            $table->foreign('kd_obat')->references('kd_obat')->on('obat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_detail');
    }
};
