<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreferensiLokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

// 1. Ambil semua ID mahasiswa yang ada
        $mahasiswaIds = DB::table('m_mahasiswa')->pluck('mahasiswa_id')->toArray();

// 2. Ambil semua ID kota yang VALID dari tabel m_kota
        $validKotaIds = DB::table('m_kota')->pluck('kota_id')->toArray();

// Jika tidak ada mahasiswa atau kota, hentikan seeder ini
        if (empty($mahasiswaIds)) {
            $this->command->warn('Tidak ada data mahasiswa. Seeder UserPreferensiLokasi tidak dapat dijalankan.');
            return;
        }
        if (empty($validKotaIds)) {
            $this->command->warn('Tidak ada data kota di m_kota. Seeder UserPreferensiLokasi tidak dapat dijalankan.');
            return;
        }

        $userPreferensiLokasiData = [];
        $usedUserLokasi           = []; // Untuk memastikan kombinasi mahasiswa_id dan kota_id unik per preferensi

// Jumlah preferensi per mahasiswa (misalnya 1-3 preferensi)
        $jumlahPreferensiPerMahasiswa = 2; // Atau rand(1,3)

        foreach ($mahasiswaIds as $mahasiswaId) {
            $assignedKotaForThisMahasiswa = []; // Untuk melacak kota yang sudah dipilih untuk mahasiswa ini
            for ($j = 0; $j < $jumlahPreferensiPerMahasiswa; $j++) {
                if (count($assignedKotaForThisMahasiswa) >= count($validKotaIds)) {
                    break; // Tidak ada lagi kota unik yang bisa dipilih untuk mahasiswa ini
                }

                $kotaId   = null;
                $combo    = '';
                $maxTries = count($validKotaIds) * 2; // Batas percobaan untuk menghindari infinite loop
                $tries    = 0;

                // Cari kombinasi mahasiswa_id & kota_id yang unik
                do {
                    $kotaId = $validKotaIds[array_rand($validKotaIds)]; // Pilih kota_id secara acak dari yang valid
                    $combo  = $mahasiswaId . '-' . $kotaId;
                    $tries++;
                    if ($tries > $maxTries) { // Jika terlalu banyak percobaan dan tidak menemukan kombinasi unik
                        $kotaId = null;           // Tandai untuk dilewati
                        break;
                    }
                } while (isset($usedUserLokasi[$combo]));

                if ($kotaId !== null) {
                    $userPreferensiLokasiData[] = [
                        'mahasiswa_id' => $mahasiswaId,
                        'kota_id'      => $kotaId,
                        'prioritas'    => $j + 1, // Prioritas 1, 2, 3...
                        'created_at'   => $now,
                        // 'updated_at' tidak ada di skema tabel user_preferensi_lokasi Anda
                    ];
                    $usedUserLokasi[$combo]         = true;
                    $assignedKotaForThisMahasiswa[] = $kotaId; // Tandai kota sudah dipilih untuk mahasiswa ini
                }
            }
        }

// Hapus data lama jika perlu (opsional)
// DB::table('user_preferensi_lokasi')->delete();

        if (! empty($userPreferensiLokasiData)) {
            DB::table('user_preferensi_lokasi')->insert($userPreferensiLokasiData);
            $this->command->info(count($userPreferensiLokasiData) . ' data preferensi lokasi pengguna telah ditambahkan.');
        } else {
            $this->command->warn('Tidak ada data preferensi lokasi yang dihasilkan.');
        }

    }
}
