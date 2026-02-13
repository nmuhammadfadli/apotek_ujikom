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
            Schema::create('penjualan', function (Blueprint $table) {
            $table->string('nota')->primary(); // jika nota string
            $table->date('tgl_nota');
            $table->unsignedBigInteger('kd_pelanggan')->nullable();
            $table->decimal('diskon', 8, 2)->default(0);
            $table->timestamps();

            $table->foreign('kd_pelanggan')->references('kd_pelanggan')->on('pelanggan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
