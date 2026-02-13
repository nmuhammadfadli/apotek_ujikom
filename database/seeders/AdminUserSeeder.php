<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User as User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Owner (akses semua)
        User::updateOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Owner',
                'password' => Hash::make('password'), // ganti di production
                'role' => 'owner'
            ]
        );

        // Pegawai 
        User::updateOrCreate(
            ['email' => 'pegawai@example.com'],
            [
                'name' => 'Pegawai',
                'password' => Hash::make('password'),
                'role' => 'pegawai'
            ]
        );
    }
}
