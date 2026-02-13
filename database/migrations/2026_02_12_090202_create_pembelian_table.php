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
            Schema::create('pembelian', function (Blueprint $table) {
        $table->string('nota')->primary();
        $table->date('tgl_nota');
        $table->unsignedBigInteger('kd_supplier')->nullable();
        $table->decimal('diskon', 8, 2)->default(0);
        $table->timestamps();

        $table->foreign('kd_supplier')->references('id')->on('supplier')->onDelete('set null');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
