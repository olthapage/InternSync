<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // Untuk membuat kode

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan path ke file CSV benar
        $csvFile = fopen(base_path("database/seeders/data/provinsi.csv"), "r");

        if ($csvFile === false) {
            $this->command->error("File provinsi.csv tidak ditemukan atau tidak bisa dibuka.");
            Log::error("File provinsi.csv tidak ditemukan atau tidak bisa dibuka.");
            return;
        }

        $now = Carbon::now();
        $dataToInsert = [];
        $firstline = true; // Untuk melewati baris header CSV jika ada

        DB::beginTransaction();
        try {
            // DB::table('m_provinsi')->delete(); // Hati-hati: hapus data lama jika diperlukan

            while (($row = fgetcsv($csvFile, 2000, ",")) !== false) {
                if (!$firstline) {
                    // Asumsi CSV: Kolom 0 = provinsi_id (numerik), Kolom 1 = provinsi_nama
                    if (count($row) < 2) {
                        Log::warning("Baris CSV provinsi dilewati karena kekurangan kolom: " . implode(",", $row));
                        continue;
                    }

                    $provinsiIdCsv = trim($row[0]);
                    $provinsiNamaCsv = trim($row[1]);

                    if (empty($provinsiIdCsv) || empty($provinsiNamaCsv) || !is_numeric($provinsiIdCsv)) {
                        Log::warning("Baris CSV provinsi dilewati karena ID tidak valid atau Nama kosong: " . implode(",", $row));
                        continue;
                    }

                    // Membuat provinsi_kode secara sederhana dari nama provinsi
                    // Contoh: "Jawa Timur" -> "JATIM", "DKI Jakarta" -> "DKIJAKARTA"
                    // Anda bisa membuat logika yang lebih canggih jika perlu.
                    $words = explode(' ', strtoupper($provinsiNamaCsv));
                    $provinsiKode = '';
                    if (count($words) > 1 && $words[0] !== 'KEPULAUAN') {
                        foreach ($words as $word) {
                            $provinsiKode .= substr($word, 0, 1);
                        }
                         // Jika kode terlalu panjang atau ingin lebih sederhana
                        if (strlen($provinsiKode) > 6 || count($words) > 2) {
                             $provinsiKode = strtoupper(substr(str_replace(' ', '', $provinsiNamaCsv), 0, 5));
                        }

                    } else {
                        $provinsiKode = strtoupper(substr(str_replace(' ', '', $provinsiNamaCsv), 0, 5));
                    }
                    $provinsiKode = preg_replace('/[^A-Z]/', '', $provinsiKode); // Hanya huruf kapital
                    $provinsiKode = substr($provinsiKode, 0, 10); // Batasi panjang kode


                    $dataToInsert[] = [
                        'provinsi_id'   => (int)$provinsiIdCsv, // Cast ke integer, ini akan jadi PK Anda
                        'provinsi_kode' => $provinsiKode,      // Kode yang di-generate
                        'provinsi_nama' => $provinsiNamaCsv,
                        'created_at'    => $now,
                        // 'updated_at' => $now, // Jika ada kolomnya
                    ];
                }
                $firstline = false;
            }
            fclose($csvFile);

            if (!empty($dataToInsert)) {
                // Hapus data yang mungkin sudah ada dengan ID yang sama jika provinsi_id adalah PK unik
                $existingIds = array_column($dataToInsert, 'provinsi_id');
                DB::table('m_provinsi')->whereIn('provinsi_id', $existingIds)->delete();

                DB::table('m_provinsi')->insert($dataToInsert);
                $this->command->info(count($dataToInsert) . ' provinsi dari CSV telah ditambahkan/diperbarui.');
            } else {
                $this->command->warn('Tidak ada data provinsi yang valid untuk dimasukkan dari CSV provinsi.csv.');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error seeding provinsi: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            $this->command->error("Error seeding provinsi: " . $e->getMessage());
        }
    }
}
