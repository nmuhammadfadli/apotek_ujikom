<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::create('obat', function (Blueprint $table) {
            $table->id('kd_obat'); // primary key numeric auto-increment
            $table->string('nm_obat');
            $table->string('jenis')->nullable();
            $table->string('satuan')->nullable();
            $table->decimal('harga_beli', 12, 2)->default(0);
            $table->decimal('harga_jual', 12, 2)->default(0);
            $table->integer('stok')->default(0);
            $table->unsignedBigInteger('kd_supplier')->nullable();
            $table->timestamps();

            $table->foreign('kd_supplier')->references('id')->on('supplier')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('obat');
    }
};
