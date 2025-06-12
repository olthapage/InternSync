<?php

namespace Database\Seeders;

use App\Models\MahasiswaModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Gunakan Hash facade untuk password

class MahasiswaBaruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Data Mahasiswa 1
        MahasiswaModel::create([
            'nama_lengkap'      => 'Mahmud Santoso',
            'email'             => 'mhs@example.com',
            'password'          => Hash::make('12345678'), // Enkripsi password
            'nim'               => '2151507001',
            'prodi_id'          => 1, // Pastikan prodi dengan ID 1 ada
            'status_verifikasi' => 'pending',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Data Mahasiswa 2
        MahasiswaModel::create([
            'nama_lengkap'      => 'Citra Lestari',
            'email'             => 'spkmhs@example.com',
            'password'          => Hash::make('12345678'),
            'nim'               => '2151507002',
            'prodi_id'          => 1,
            'status_verifikasi' => 'valid',
            'dpa_id'          => 1,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        // Data Mahasiswa 3
        MahasiswaModel::create([
            'nama_lengkap'      => 'Dewi Anggraini',
            'email'             => 'dewi.anggraini@example.com',
            'password'          => Hash::make('12345678'),
            'nim'               => '2151507003',
            'prodi_id'          => 1,
            'status_verifikasi' => 'pending', // Contoh dengan status pending
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $this->command->info('Seeder untuk 3 mahasiswa baru berhasil dijalankan!');
    }
}
