<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class IndustriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now   = Carbon::now();
        $faker = Factory::create('id_ID'); // Inisialisasi Faker

// 1. Ambil semua ID kota yang VALID dari tabel m_kota
        $validKotaIds = DB::table('m_kota')->pluck('kota_id')->toArray();

// 2. Ambil semua ID kategori industri yang VALID
        $validKategoriIndustriIds = DB::table('m_kategori_industri')->pluck('kategori_industri_id')->toArray();

// Handle jika data master (kota atau kategori industri) kosong
        if (empty($validKotaIds)) {
            $this->command->error('Tidak ada data kota ditemukan di tabel m_kota. IndustriSeeder tidak dapat dijalankan tanpa kota_id yang valid. Jalankan KotaSeeder terlebih dahulu.');
            return;
        }
        if (empty($validKategoriIndustriIds)) {
            $this->command->warn('Tidak ada data kategori industri ditemukan. Menggunakan ID kategori acak (1-5) sebagai fallback untuk IndustriSeeder.');
                                                     // Fallback jika tidak ada kategori industri, Anda bisa sesuaikan rentangnya atau buat KategoriIndustriSeeder
            $validKategoriIndustriIds = range(1, 5); // Asumsi ID 1-5 ada atau akan dibuat
        }

        $industriData        = [];
        $jumlahIndustriTotal = 25; // Misalnya kita ingin membuat 25 data industri

// Data eksplisit (4 data awal)
        $explicitIndustries = [
            ['industri_nama' => 'PT Teknologi Cerdas Nusantara', 'email_prefix' => 'teknocerdas', 'kategori_preset' => $validKategoriIndustriIds[array_rand($validKategoriIndustriIds)]],
            ['industri_nama' => 'PT Solusi Digital Integrasi', 'email_prefix' => 'solusidigital', 'kategori_preset' => $validKategoriIndustriIds[array_rand($validKategoriIndustriIds)]],
            ['industri_nama' => 'PT Mitra Infosarana Prima', 'email_prefix' => 'mitrainfo', 'kategori_preset' => $validKategoriIndustriIds[array_rand($validKategoriIndustriIds)]],
            ['industri_nama' => 'PT Datamax Teknologi Global', 'email_prefix' => 'datamaxglobal', 'kategori_preset' => $validKategoriIndustriIds[array_rand($validKategoriIndustriIds)]],
        ];

        foreach ($explicitIndustries as $index => $ind) {
            $industriData[] = [
                'industri_nama'        => $ind['industri_nama'],
                'kota_id'              => $validKotaIds[array_rand($validKotaIds)],
                'kategori_industri_id' => $ind['kategori_preset'],
                'email'                => $ind['email_prefix'] . ($index + 1) . '@example.com',
                'telepon'              => $faker->unique()->numerify('081#########'),
                'password'             => Hash::make('password123'),
                'level_id'             => 4,
                'created_at'           => $now,
            ];
        }

// Loop untuk membuat sisa data industri secara dinamis
        for ($i = count($industriData) + 1; $i <= $jumlahIndustriTotal; $i++) {
            $namaPerusahaan = $faker->company;
            $industriData[] = [
                'industri_nama'        => $namaPerusahaan,
                'kota_id'              => $validKotaIds[array_rand($validKotaIds)],
                'kategori_industri_id' => $validKategoriIndustriIds[array_rand($validKategoriIndustriIds)],
                'email'                => Str::slug(explode(' ', $namaPerusahaan)[0], '') . $i . '@example.net', // Email unik berbasis nama
                'telepon'              => $faker->unique()->numerify('08##########'),
                'password'             => Hash::make('industriPass'),
                'level_id'             => 4,            
                'created_at'           => $now,
                // 'updated_at'        => $now, // Jika ada
            ];
        }

// Hapus data lama jika perlu (opsional, hati-hati)
// DB::table('m_industri')->delete();

// Insert data baru secara chunk
        foreach (array_chunk($industriData, 50) as $chunk) {
            DB::table('m_industri')->insert($chunk);
        }

        $this->command->info(count($industriData) . ' data industri telah ditambahkan.');
    }
}
