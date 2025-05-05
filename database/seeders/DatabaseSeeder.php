<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // tabel_prodi
        DB::table('tabel_prodi')->insert([
            [
                'prodi_id'=> 1,
                'kode_prodi'=> 'TI',
                'nama_prodi'=> 'Teknik Informatika'
            ],
            [
                'prodi_id'=> 2,
                'kode_prodi'=> 'SI',
                'nama_prodi'=> 'Sistem Informasi'
            ],
            [
                'prodi_id'=> 3,
                'kode_prodi'=> 'MI',
                'nama_prodi'=> 'Manajemen Informatika'
            ],
            ]);

        // m_level_user
        DB::table('m_level_user')->insert([
            ['level_kode' => 'ADM', 'level_nama' => 'Admin', 'created_at' => $now],
            ['level_kode' => 'MHS', 'level_nama' => 'Mahasiswa', 'created_at' => $now],
            ['level_kode' => 'DSN', 'level_nama' => 'Dosen', 'created_at' => $now],
        ]);

        // m_provinsi
        DB::table('m_provinsi')->insert([
            ['provinsi_kode' => 'JTM', 'provinsi_nama' => 'Jawa Timur', 'created_at' => $now],
            ['provinsi_kode' => 'JTG', 'provinsi_nama' => 'Jawa Tengah', 'created_at' => $now],
        ]);

        // m_kota
        DB::table('m_kota')->insert([
            ['kota_kode' => 'MLG', 'kota_nama' => 'Malang', 'provinsi_id' => 1, 'created_at' => $now],
            ['kota_kode' => 'SMG', 'kota_nama' => 'Semarang', 'provinsi_id' => 2, 'created_at' => $now],
        ]);

        // m_user
        DB::table('m_user')->insert([
            [
                'nama_lengkap' => 'Admin Satu',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'level_id' => 1,
                'prodi_id'=> null,
                'created_at' => $now
            ],
            [
                'nama_lengkap' => 'Mahasiswa Satu',
                'email' => 'mhs@example.com',
                'password' => Hash::make('password'),
                'level_id' => 2,
                'prodi_id'=> 1,
                'created_at' => $now
            ],
        ]);

        // m_kategori_kompetensi
        DB::table('m_kategori_kompetensi')->insert([
            ['kategori_kompetensi_kode' => 'KOMP1', 'kategori_nama' => 'Manajerial', 'created_at' => $now],
        ]);

        // m_kategori_skill
        DB::table('m_kategori_skill')->insert([
            ['kategori_skill_kode' => 'SKL1', 'kategori_nama' => 'Programming', 'created_at' => $now],
        ]);

        // m_kategori_lowongan
        DB::table('m_kategori_lowongan')->insert([
            ['kategori_lowongan_kode' => 'LOW1', 'kategori_nama' => 'Magang', 'created_at' => $now],
        ]);

        // m_kategori_industri
        DB::table('m_kategori_industri')->insert([
            ['kategori_industri_kode' => 'IND1', 'kategori_nama' => 'Teknologi Informasi', 'created_at' => $now],
        ]);

        // m_detail_skill
        DB::table('m_detail_skill')->insert([
            ['skill_nama' => 'Laravel', 'kategori_skill_id' => 1, 'created_at' => $now],
        ]);

        // user_preferensi_lokasi
        DB::table('user_preferensi_lokasi')->insert([
            ['user_id' => 2, 'kota_id' => 1, 'prioritas' => 1, 'created_at' => $now],
        ]);

        // user_skill
        DB::table('user_skill')->insert([
            ['user_id' => 2, 'skill_id' => 1, 'created_at' => $now],
        ]);

        // m_industri
        DB::table('m_industri')->insert([
            ['industri_nama' => 'PT Teknologi Cerdas', 'kota_id' => 1, 'kategori_industri_id' => 1],
        ]);

        // m_detail_lowongan
        DB::table('m_detail_lowongan')->insert([
            [
                'judul_lowongan' => 'Magang Web Developer',
                'deskripsi' => 'Mengembangkan aplikasi Laravel.',
                'industri_id' => 1,
                'kategori_lowongan_id' => 1,
                'created_at' => $now
            ],
        ]);

        // lowongan_skill
        DB::table('lowongan_skill')->insert([
            ['lowongan_id' => 1, 'skill_id' => 1, 'created_at' => $now],
        ]);

        DB::table('m_dosen')->insert([
            [
                'nama_lengkap' => 'Dr. Budi Santoso',
                'email' => 'budi@example.com',
                'password' => Hash::make('password123'),
                'nip' => '19800101123456',
                'level_id' => 3,
                'prodi_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lengkap' => 'Dr. Siti Aminah',
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'nip' => '19750506123456',
                'level_id' => 3,
                'prodi_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        DB::table('m_mahasiswa')->insert([
            [
                'nama_lengkap' => 'Andi Nugroho',
                'email' => 'andi@example.com',
                'password' => Hash::make('mahasiswa123'),
                'nim' => '22410001',
                'ipk' => 3.45,
                'status' => 1,
                'level_id' => 2, // level mahasiswa
                'prodi_id' => 1,
                'dosen_id' => 1, // pastikan dosen_id = 1 sudah ada
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lengkap' => 'Rina Lestari',
                'email' => 'rina@example.com',
                'password' => Hash::make('mahasiswa123'),
                'nim' => '22410002',
                'ipk' => 3.80,
                'status' => 0,
                'level_id' => 2,
                'prodi_id' => 2,
                'dosen_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

    }
}
