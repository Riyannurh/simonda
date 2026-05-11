<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::firstOrCreate(['nip' => '1234567890'], [
            'name' => 'Admin SIMONDA',
            'email' => 'admin@simonda.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Pendamping User
        User::firstOrCreate(['nip' => '0987654321'], [
            'name' => 'Pendamping UMKM',
            'email' => 'pendamping@simonda.com',
            'password' => Hash::make('password'),
            'role' => 'pendamping',
            'wilayah_kode_kecamatan' => '3201010',
            'email_verified_at' => now(),
        ]);

        // Kepala Bagian User
        User::firstOrCreate(['nip' => '1122334455'], [
            'name' => 'Kepala Bagian',
            'email' => 'kepala@simonda.com',
            'password' => Hash::make('password'),
            'role' => 'kepala_bagian',
            'email_verified_at' => now(),
        ]);
    }
}
