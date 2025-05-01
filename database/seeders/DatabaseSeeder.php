<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('m_level_user')->insert([
            ['level_nama' => 'Admin'],
            ['level_nama' => 'User'],
            ['level_nama' => 'Perusahaan'],
        ]);

        DB::table('m_provinsi')->insert([
            ['provinsi_nama' => 'Jawa Timur'],
            ['provinsi_nama' => 'Jawa Barat'],
            ['provinsi_nama' => 'DKI Jakarta'],
        ]);

        DB::table('m_kota')->insert([
            ['kota_nama' => 'Surabaya', 'provinsi_id' => 1],
            ['kota_nama' => 'Bandung', 'provinsi_id' => 2],
            ['kota_nama' => 'Jakarta Pusat', 'provinsi_id' => 3],
        ]);

        DB::table('m_user')->insert([
            [
                'nama_lengkap' => 'Melinda Winarno',
                'email' => 'lmahendra@hotmail.com',
                'password' => Hash::make('password'),
                'level_id' => 3,
                'created_at' => '2021-07-25 14:41:10',
            ],
            [
                'nama_lengkap' => 'Laila Winarno, S.I.Kom',
                'email' => 'mariaramadan@yahoo.com',
                'password' => Hash::make('password'),
                'level_id' => 1,
                'created_at' => '2020-10-03 09:01:36',
            ],
            // Tambah user lainnya sesuai kebutuhan
        ]);

        DB::table('user_preferensi_lokasi')->insert([
            ['user_id' => 1, 'kota_id' => 1, 'prioritas' => 1, 'created_at' => now()],
            ['user_id' => 1, 'kota_id' => 2, 'prioritas' => 2, 'created_at' => now()],
        ]);

        DB::table('m_kategori_skill')->insert([
            ['kategori_nama' => 'Programming'],
            ['kategori_nama' => 'Design'],
        ]);

        DB::table('m_detail_skill')->insert([
            ['skill_nama' => 'Laravel', 'kategori_skill_id' => 1],
            ['skill_nama' => 'Adobe XD', 'kategori_skill_id' => 2],
        ]);

        DB::table('user_skill')->insert([
            ['user_id' => 1, 'skill_id' => 1],
            ['user_id' => 1, 'skill_id' => 2],
        ]);

        DB::table('m_kategori_kompetensi')->insert([
            ['kategori_nama' => 'Teknik Informatika'],
            ['kategori_nama' => 'Manajemen'],
        ]);

        DB::table('m_detail_kompetensi')->insert([
            ['nama_matkul' => 'Basis Data', 'kategori_kompetensi_id' => 1],
            ['nama_matkul' => 'Kepemimpinan', 'kategori_kompetensi_id' => 2],
        ]);

        DB::table('user_kompetensi')->insert([
            ['user_id' => 1, 'kompetensi_id' => 1, 'nilai' => 3.5],
            ['user_id' => 1, 'kompetensi_id' => 2, 'nilai' => 3.8],
        ]);

        DB::table('m_kategori_industri')->insert([
            ['kategori_nama' => 'Teknologi'],
            ['kategori_nama' => 'Desain'],
        ]);

        DB::table('m_industri')->insert([
            ['industri_nama' => 'PT. Teknologi Canggih', 'kota_id' => 1, 'kategori_industri_id' => 1],
            ['industri_nama' => 'CV. Kreatif Desain', 'kota_id' => 2, 'kategori_industri_id' => 2],
        ]);

        DB::table('m_kategori_lowongan')->insert([
            ['kategori_nama' => 'Full Time'],
            ['kategori_nama' => 'Internship'],
        ]);

        DB::table('m_detail_lowongan')->insert([
            [
                'judul_lowongan' => 'Web Developer Laravel',
                'deskripsi' => 'Membangun aplikasi berbasis Laravel',
                'industri_id' => 1,
                'kategori_lowongan_id' => 1,
            ],
            [
                'judul_lowongan' => 'UI/UX Intern',
                'deskripsi' => 'Magang desain UI/UX',
                'industri_id' => 2,
                'kategori_lowongan_id' => 2,
            ],
        ]);

        DB::table('lowongan_skill')->insert([
            ['lowongan_id' => 1, 'skill_id' => 1],
            ['lowongan_id' => 2, 'skill_id' => 2],
        ]);

        DB::table('lowongan_kompetensi')->insert([
            ['lowongan_id' => 1, 'kompetensi_id' => 1],
            ['lowongan_id' => 2, 'kompetensi_id' => 2],
        ]);

        DB::table('user_ipk')->insert([
            ['user_id' => 1, 'ipk' => 3.75, 'created_at' => now()],
        ]);
    }
}
