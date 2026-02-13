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
        Schema::create('supplier', function (Blueprint $table) {
            $table->id('id'); // kdSupplier original -> gunakan id
            $table->string('nm_supplier');
            $table->text('alamat')->nullable();
            $table->string('kota')->nullable();
            $table->string('telpon')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier');
    }
};
