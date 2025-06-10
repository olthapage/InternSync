<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
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
        $faker = Factory::create('id_ID');

        // 1. Ambil semua ID kota yang VALID dari tabel m_kota
        $validKotaIds = DB::table('m_kota')->pluck('kota_id')->toArray();

        // Handle jika data master kota kosong
        if (empty($validKotaIds)) {
            $this->command->error('Tidak ada data kota ditemukan. Jalankan KotaSeeder terlebih dahulu.');
            return;
        }

        // 2. Ambil semua kategori industri dan buat MAP [nama => id] untuk referensi yang mudah
        $kategoriMap = DB::table('m_kategori_industri')->pluck('kategori_industri_id', 'kategori_nama')->toArray();

        // Handle jika data kategori industri kosong
        if (empty($kategoriMap)) {
            $this->command->error('Tidak ada data kategori industri ditemukan. Jalankan KategoriIndustriSeeder terlebih dahulu.');
            return;
        }

        // 3. Definisikan daftar industri secara hardcoded dengan kategori yang relevan
        $industriList = [
            // Teknologi Informasi dan Komunikasi
            ['nama' => 'PT Cipta Solusi Digital', 'email_prefix' => 'kontak.csd', 'kategori_nama' => 'Teknologi Informasi dan Komunikasi'],
            ['nama' => 'PT Siber Kreasi Nusantara', 'email_prefix' => 'info.skn', 'kategori_nama' => 'Teknologi Informasi dan Komunikasi'],
            ['nama' => 'PT Aplikasi Karya Bangsa', 'email_prefix' => 'support.akb', 'kategori_nama' => 'Teknologi Informasi dan Komunikasi'],

            // Keuangan dan Asuransi
            ['nama' => 'Bank Digital Sejahtera', 'email_prefix' => 'care.bds', 'kategori_nama' => 'Keuangan dan Asuransi'],
            ['nama' => 'PT Asuransi Garda Amanah', 'email_prefix' => 'klaim.aga', 'kategori_nama' => 'Keuangan dan Asuransi'],

            // Kesehatan dan Farmasi
            ['nama' => 'Rumah Sakit Harapan Medika', 'email_prefix' => 'admisi.rshm', 'kategori_nama' => 'Kesehatan dan Farmasi'],
            ['nama' => 'PT Bio Farma Sehat', 'email_prefix' => 'cs.bfs', 'kategori_nama' => 'Kesehatan dan Farmasi'],
            ['nama' => 'Klinik Utama Sentosa', 'email_prefix' => 'pendaftaran.kus', 'kategori_nama' => 'Kesehatan dan Farmasi'],

            // Manufaktur dan Pengolahan
            ['nama' => 'PT Indofood Sukses Makmur Tbk', 'email_prefix' => 'hrd.indofood', 'kategori_nama' => 'Manufaktur dan Pengolahan'],
            ['nama' => 'PT Garmen Jaya Abadi', 'email_prefix' => 'produksi.gja', 'kategori_nama' => 'Manufaktur dan Pengolahan'],

            // Perdagangan dan Ritel
            ['nama' => 'PT Ramayana Lestari Sentosa Tbk', 'email_prefix' => 'store.ramayana', 'kategori_nama' => 'Perdagangan dan Ritel'],
            ['nama' => 'Toko Swalayan Maju Bersama', 'email_prefix' => 'grosir.mb', 'kategori_nama' => 'Perdagangan dan Ritel'],

            // Konstruksi dan Properti
            ['nama' => 'PT Adhi Karya Persada Properti', 'email_prefix' => 'proyek.adhi', 'kategori_nama' => 'Konstruksi dan Properti'],
            ['nama' => 'PT Wijaya Karya Bangun Gedung', 'email_prefix' => 'tender.wika', 'kategori_nama' => 'Konstruksi dan Properti'],

            // Pariwisata dan Perhotelan
            ['nama' => 'Hotel Santika Premiere', 'email_prefix' => 'reservasi.santika', 'kategori_nama' => 'Pariwisata dan Perhotelan'],
            ['nama' => 'Pesona Nusantara Tour & Travel', 'email_prefix' => 'paket.pesona', 'kategori_nama' => 'Pariwisata dan Perhotelan'],
            ['nama' => 'Java Heritage Hotel', 'email_prefix' => 'booking.javaheritage', 'kategori_nama' => 'Pariwisata dan Perhotelan'],

            // Transportasi dan Logistik
            ['nama' => 'PT Garuda Indonesia Logistik', 'email_prefix' => 'cargo.garuda', 'kategori_nama' => 'Transportasi dan Logistik'],
            ['nama' => 'JNE Express Utama', 'email_prefix' => 'tracking.jne', 'kategori_nama' => 'Transportasi dan Logistik'],

            // Pendidikan
            ['nama' => 'Universitas Pelita Bangsa', 'email_prefix' => 'info.upb', 'kategori_nama' => 'Pendidikan'],
            ['nama' => 'Bimbingan Belajar Cendekia', 'email_prefix' => 'kelas.cendekia', 'kategori_nama' => 'Pendidikan'],

            // Energi dan Pertambangan
            ['nama' => 'PT Pertamina Hulu Energi', 'email_prefix' => 'corporate.phe', 'kategori_nama' => 'Energi dan Pertambangan'],
            ['nama' => 'PT Adaro Energy Tbk', 'email_prefix' => 'relations.adaro', 'kategori_nama' => 'Energi dan Pertambangan'],
            ['nama' => 'PT Bukit Asam Tbk', 'email_prefix' => 'csr.bukitasam', 'kategori_nama' => 'Energi dan Pertambangan'],
        ];

        $industriData = [];
        foreach ($industriList as $industri) {
            // Cocokkan nama kategori dengan ID yang ada di map
            $kategoriId = $kategoriMap[$industri['kategori_nama']] ?? null;

            // Lewati jika karena suatu hal nama kategori di array tidak cocok dengan yang ada di DB
            if (!$kategoriId) {
                $this->command->warn("Kategori '{$industri['kategori_nama']}' tidak ditemukan. Melewatkan industri '{$industri['nama']}'.");
                continue;
            }

            $industriData[] = [
                'industri_nama'        => $industri['nama'],
                'kota_id'              => $faker->randomElement($validKotaIds),
                'kategori_industri_id' => $kategoriId,
                'email'                => $industri['email_prefix'] . '@example.com',
                'telepon'              => $faker->unique()->numerify('081#########'),
                'password'             => Hash::make('12345678'),
                'created_at'           => $now,
            ];
        }

        // Insert data baru secara chunk untuk efisiensi
        foreach (array_chunk($industriData, 50) as $chunk) {
            DB::table('m_industri')->insert($chunk);
        }

        $this->command->info(count($industriData) . ' data industri hardcoded telah ditambahkan.');
    }
}
