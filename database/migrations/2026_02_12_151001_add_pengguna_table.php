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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                // gunakan string agar mudah
                $table->string('role')->default('pegawai')->after('password')->comment('roles: owner|pegawai');
            });
        } else {
            // jika belum ada users table (jarang karena Laravel default membuatnya),
            // buat table users (fallback)
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('role')->default('pegawai')->comment('roles: owner|pegawai');
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('users')) { 
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'role')) {
                    $table->dropColumn('role');
                }
            });
        }
    }
};
