<?php

namespace Database\Seeders;

use App\Models\MahasiswaModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Untuk logging proses

class CleanNimSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk membersihkan dan memperbarui NIM mahasiswa.
     *
     * @return void
     */
    public function run()
    {
        // Nonaktifkan event model untuk mempercepat proses update massal
        MahasiswaModel::unsetEventDispatcher();

        // Mengambil mahasiswa dalam chunk (potongan) untuk efisiensi memori
        MahasiswaModel::chunk(200, function ($mahasiswas) {
            $this->command->info("Memproses " . $mahasiswas->count() . " data mahasiswa...");

            foreach ($mahasiswas as $mahasiswa) {
                $newNim = null;
                $isUnique = false;

                // Loop untuk memastikan NIM yang dihasilkan benar-benar unik
                do {
                    // Hasilkan 10 digit angka acak
                    $newNim = str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);

                    // Cek apakah NIM ini sudah ada di database
                    $exists = DB::table('m_mahasiswa')->where('nim', $newNim)->exists();

                    if (!$exists) {
                        $isUnique = true;
                    }

                } while (!$isUnique);

                // Update NIM mahasiswa yang sedang diproses
                DB::table('m_mahasiswa')
                  ->where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                  ->update(['nim' => $newNim]);

                // Log untuk melacak perubahan (opsional)
                Log::info("NIM untuk mahasiswa ID {$mahasiswa->mahasiswa_id} ({$mahasiswa->nama_lengkap}) diubah menjadi {$newNim}.");
            }
        });

        $this->command->info('Proses pembersihan NIM mahasiswa telah selesai!');
    }
}
