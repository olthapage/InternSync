<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class KotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan path ke file CSV benar
        $csvFile = fopen(base_path("database/seeders/data/kabupaten_kota.csv"), "r");

        if ($csvFile === false) {
            $this->command->error("File kabupaten_kota.csv tidak ditemukan atau tidak bisa dibuka.");
            Log::error("File kabupaten_kota.csv tidak ditemukan atau tidak bisa dibuka.");
            return;
        }

        $now = Carbon::now();
        $dataToInsert = [];
        $firstline = true; // Untuk melewati baris header CSV jika ada

        // Ambil semua provinsi_id yang valid dari database untuk verifikasi foreign key
        $validProvinsiIds = DB::table('m_provinsi')->pluck('provinsi_id')->toArray();

        DB::beginTransaction();
        try {
            // Jika Anda ingin menghapus data lama setiap kali seeder dijalankan
            // Lakukan dengan hati-hati jika sudah ada data yang berelasi
            // DB::table('m_kota')->delete();

            while (($row = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
                if (!$firstline) {
                    // Asumsi CSV: Kolom 0 = kode_gabungan (misal '11.01'), Kolom 1 = nama_kota
                    if (count($row) < 2) {
                        Log::warning("Baris CSV kota/kabupaten dilewati karena kekurangan kolom: " . implode(",", $row));
                        continue;
                    }

                    $kodeGabungan = trim($row[0]);
                    $namaKotaCsv = trim($row[1]);

                    if (empty($kodeGabungan) || empty($namaKotaCsv) || !str_contains($kodeGabungan, '.')) {
                        Log::warning("Baris CSV kota/kabupaten dilewati karena format kode gabungan salah atau nama kosong: " . implode(",", $row));
                        continue;
                    }

                    $parts = explode('.', $kodeGabungan);
                    if (count($parts) !== 2 || !is_numeric($parts[0]) || !is_numeric($parts[1])) {
                        Log::warning("Format kode gabungan tidak valid (harus 'id_provinsi.id_lokal_kota'): " . $kodeGabungan . " pada baris " . implode(",", $row));
                        continue;
                    }

                    $provinsiIdFromCsv = (int)$parts[0];
                    $kotaLokalIdFromCsv = $parts[1]; // Ini string, misal "01", "73"

                    // Verifikasi apakah provinsi_id dari CSV ada di tabel m_provinsi
                    if (!in_array($provinsiIdFromCsv, $validProvinsiIds)) {
                        Log::warning("provinsi_id '{$provinsiIdFromCsv}' dari CSV (kode gabungan: {$kodeGabungan}) tidak ditemukan di tabel m_provinsi untuk kota '{$namaKotaCsv}'. Kota ini dilewati.");
                        continue;
                    }

                    // Membuat kota_id (PK) dengan menggabungkan provinsi_id dan id lokal kota tanpa titik
                    // Misal: "11.01" -> 1101, "35.73" -> 3573
                    // Pastikan tipe data kolom kota_id di tabel m_kota adalah integer/bigInteger yang cukup
                    $kotaIdPk = (int)($provinsiIdFromCsv . $kotaLokalIdFromCsv);


                    $dataToInsert[] = [
                        'kota_id'       => $kotaIdPk,         // Ini akan menjadi Primary Key
                        'provinsi_id'   => $provinsiIdFromCsv,
                        'kota_nama'     => $namaKotaCsv,
                        // Kolom 'kota_kode' (textual) tidak diisi lagi sesuai permintaan Anda
                        'created_at'    => $now,
                        // 'updated_at'    => $now, // Tambahkan jika tabel m_kota Anda memiliki kolom ini
                    ];
                }
                $firstline = false;
            }
            fclose($csvFile);

            if (!empty($dataToInsert)) {
                // Untuk menghindari error jika menjalankan seeder berulang kali dengan data yang sama (PK conflict)
                // Hapus dulu data yang ID nya sama dengan yang akan diinsert
                $existingKotaIds = array_column($dataToInsert, 'kota_id');
                DB::table('m_kota')->whereIn('kota_id', $existingKotaIds)->delete();

                // Insert data dalam chunk untuk performa lebih baik
                foreach (array_chunk($dataToInsert, 200) as $chunk) {
                    DB::table('m_kota')->insert($chunk);
                }
                $this->command->info(count($dataToInsert) . ' kota/kabupaten dari CSV telah ditambahkan/diperbarui.');
            } else {
                $this->command->warn('Tidak ada data kota/kabupaten yang valid untuk dimasukkan dari CSV kabupaten_kota.csv.');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error seeding kota/kabupaten: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $this->command->error("Error seeding kota/kabupaten: " . $e->getMessage());
        }
    }
}
