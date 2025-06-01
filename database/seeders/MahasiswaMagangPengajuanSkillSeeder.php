<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaMagangPengajuanSkillSeeder extends Seeder
{
    public function run(): void
    {
        // Di dalam method run():
        $now   = Carbon::now();
        $faker = Factory::create('id_ID');

// --- PRASYARAT: Pastikan Seeder Dosen, Prodi, DetailSkill, Lowongan sudah berjalan ---
        $this->command->info('Memastikan data master Dosen, Prodi, DetailSkill, Lowongan sudah ada...');

        $dpaIds = DB::table('m_dosen')->where('role_dosen', 'dpa')->pluck('dosen_id')->toArray();
        if (empty($dpaIds)) {
            $this->command->error('Tidak ada Dosen DPA. Hentikan seeder Mahasiswa karena semua butuh DPA.');
            return;
        }

        $pembimbingIds = DB::table('m_dosen')->where('role_dosen', 'pembimbing')->pluck('dosen_id')->toArray();
        if (empty($pembimbingIds)) {
            $this->command->warn('Tidak ada Dosen Pembimbing. Mahasiswa magang tidak akan memiliki pembimbing dari seeder ini.');
            // $pembimbingIds = DB::table('m_dosen')->pluck('dosen_id')->toArray(); // Fallback jika ingin tetap ada dosen_id
        }

        $lowonganIds = DB::table('m_detail_lowongan')->pluck('lowongan_id')->toArray();
        if (empty($lowonganIds)) {
            $this->command->warn('Tidak ada data lowongan. Pengajuan dan Magang tidak bisa dibuat.');
        }

        $skillIds = DB::table('m_detail_skill')->pluck('skill_id')->toArray();
        if (empty($skillIds)) {
            $this->command->warn('Tidak ada data detail skill. MahasiswaSkill tidak bisa dibuat.');
        }

// --- SEEDER UNTUK m_mahasiswa ---
        $this->command->info('Memulai seeding tabel m_mahasiswa...');
        $mahasiswaData   = [];
        $jumlahMahasiswa = 50;

        for ($i = 1; $i <= $jumlahMahasiswa; $i++) {
            $mahasiswaData[] = [
                'nama_lengkap'      => $faker->name,
                'email'             => $faker->unique()->safeEmail,
                'password'          => Hash::make('mahasiswa123'),
                'nim'               => 'MHS' . str_pad($i, 4, '0', STR_PAD_LEFT) . $faker->numerify('##'),
                'ipk'               => round($faker->randomFloat(2, 2.80, 4.00), 2),
                'status'            => 1,
                'status_verifikasi' => "Pending",
                'alasan'            => null,
                'level_id'          => 2,           // MHS
                'prodi_id'          => rand(1, 10), // Asumsi ada 10 prodi
                'dpa_id'            => $dpaIds[array_rand($dpaIds)],
                'dosen_id'          => null, // Dosen Pembimbing diisi nanti
                'created_at'        => $now,
                'updated_at'        => $now,
            ];
        }
// DB::table('m_mahasiswa')->delete(); // Hati-hati
        DB::table('m_mahasiswa')->insert($mahasiswaData);
        $this->command->info(count($mahasiswaData) . ' data mahasiswa telah ditambahkan.');

        $allCreatedMahasiswaIds = DB::table('m_mahasiswa')->pluck('mahasiswa_id')->toArray();

// --- SEEDER UNTUK t_pengajuan DAN mahasiswa_magang ---
        $this->command->info('Memulai seeding t_pengajuan dan mahasiswa_magang...');
        $pengajuanDataToInsert           = [];
        $magangDataToInsert              = [];
        $mahasiswaSudahAdaPengajuanFinal = []; // Melacak mahasiswa yg sudah punya status akhir di pengajuan/magang
        $mahasiswaIdsDenganMagangAktif   = []; // Untuk mahasiswa yang statusnya 'belum' atau 'sedang' di MagangModel

        if (! empty($lowonganIds)) {
                                                                                                                          // 1. Mahasiswa DITERIMA magang (dan masuk ke MagangModel)
            $jumlahDiterima = ! empty($allCreatedMahasiswaIds) ? min(15, floor(count($allCreatedMahasiswaIds) * 0.3)) : 0; // 30% diterima

            for ($i = 0; $i < $jumlahDiterima; $i++) {
                if (empty($allCreatedMahasiswaIds) || empty($pembimbingIds)) {
                    break;
                }

                $mahasiswaId = $faker->unique(true)->randomElement(array_diff($allCreatedMahasiswaIds, $mahasiswaSudahAdaPengajuanFinal));
                if (! $mahasiswaId) {$faker->unique(false);
                    $mahasiswaId = $faker->unique(true)->randomElement(array_diff($allCreatedMahasiswaIds, $mahasiswaSudahAdaPengajuanFinal));if (! $mahasiswaId) {
                        continue;
                    }
                }                      // Reset unique jika semua sudah terpakai
                $faker->unique(false); // Penting untuk reset unique agar bisa dipakai di loop selanjutnya

                $lowonganId   = $lowonganIds[array_rand($lowonganIds)];
                $statusMagang = $faker->randomElement(['belum', 'sedang']); // Status awal di MagangModel

                $magangDataToInsert[] = [
                    'mahasiswa_id' => $mahasiswaId,
                    'lowongan_id'  => $lowonganId,
                    'status'       => $statusMagang,
                    'evaluasi'     => null,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
                $mahasiswaIdsDenganMagangAktif[$mahasiswaId] = true; // Tandai mahasiswa ini aktif/akan magang

                $pengajuanDataToInsert[] = [
                    'mahasiswa_id'     => $mahasiswaId,
                    'lowongan_id'      => $lowonganId,
                    'tanggal_mulai'    => Carbon::parse($now)->addDays(rand(5, 20))->toDateString(),
                    'tanggal_selesai'  => Carbon::parse($now)->addDays(rand(90, 110))->toDateString(),
                    'status'           => 'diterima',
                    'alasan_penolakan' => null,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];

                // Update dosen_id (pembimbing) di m_mahasiswa
                if (! empty($pembimbingIds)) {
                    DB::table('m_mahasiswa')
                        ->where('mahasiswa_id', $mahasiswaId)
                        ->update(['dosen_id' => $pembimbingIds[array_rand($pembimbingIds)], 'updated_at' => $now]);
                }
                $mahasiswaSudahAdaPengajuanFinal[] = $mahasiswaId;
            }

                                                                                                                         // 2. Mahasiswa DITOLAK pengajuannya
            $jumlahDitolak = ! empty($allCreatedMahasiswaIds) ? min(10, floor(count($allCreatedMahasiswaIds) * 0.2)) : 0; // 20% ditolak
            for ($i = 0; $i < $jumlahDitolak; $i++) {
                if (empty($allCreatedMahasiswaIds)) {
                    break;
                }

                $mahasiswaId = $faker->unique(true)->randomElement(array_diff($allCreatedMahasiswaIds, $mahasiswaSudahAdaPengajuanFinal));
                if (! $mahasiswaId) {$faker->unique(false);
                    $mahasiswaId = $faker->unique(true)->randomElement(array_diff($allCreatedMahasiswaIds, $mahasiswaSudahAdaPengajuanFinal));if (! $mahasiswaId) {
                        continue;
                    }
                }
                $faker->unique(false);

                $lowonganId              = $lowonganIds[array_rand($lowonganIds)];
                $pengajuanDataToInsert[] = [
                    'mahasiswa_id'     => $mahasiswaId,
                    'lowongan_id'      => $lowonganId,
                    'tanggal_mulai'    => Carbon::parse($now)->addDays(rand(5, 20))->toDateString(),
                    'tanggal_selesai'  => Carbon::parse($now)->addDays(rand(90, 110))->toDateString(),
                    'status'           => 'ditolak',
                    'alasan_penolakan' => $faker->boolean(70) ? $faker->sentence(8) : null, // 70% ada alasan
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];
                $mahasiswaSudahAdaPengajuanFinal[] = $mahasiswaId;
            }

                                                                                                                       // 3. Mahasiswa dengan pengajuan 'BELUM' (pending)
            $jumlahBelum = ! empty($allCreatedMahasiswaIds) ? min(15, floor(count($allCreatedMahasiswaIds) * 0.3)) : 0; // 30% pending
            for ($i = 0; $i < $jumlahBelum; $i++) {
                if (empty($allCreatedMahasiswaIds)) {
                    break;
                }

                $mahasiswaId = $faker->unique(true)->randomElement(array_diff($allCreatedMahasiswaIds, $mahasiswaSudahAdaPengajuanFinal));
                if (! $mahasiswaId) {$faker->unique(false);
                    $mahasiswaId = $faker->unique(true)->randomElement(array_diff($allCreatedMahasiswaIds, $mahasiswaSudahAdaPengajuanFinal));if (! $mahasiswaId) {
                        continue;
                    }
                }
                $faker->unique(false);

                $lowonganId              = $lowonganIds[array_rand($lowonganIds)];
                $pengajuanDataToInsert[] = [
                    'mahasiswa_id'     => $mahasiswaId,
                    'lowongan_id'      => $lowonganId,
                    'tanggal_mulai'    => Carbon::parse($now)->addDays(rand(5, 20))->toDateString(),
                    'tanggal_selesai'  => Carbon::parse($now)->addDays(rand(90, 110))->toDateString(),
                    'status'           => 'belum',
                    'alasan_penolakan' => null,
                    'created_at'       => $now,
                    'updated_at'       => $now,
                ];
                $mahasiswaSudahAdaPengajuanFinal[] = $mahasiswaId; // Masukkan juga yang 'belum' agar tidak ada pengajuan ganda
            }
        } // End if !empty($lowonganIds)

// Insert data magang dan pengajuan
        if (! empty($magangDataToInsert)) {
            DB::table('mahasiswa_magang')->insert($magangDataToInsert);
            $this->command->info(count($magangDataToInsert) . ' data magang mahasiswa telah ditambahkan.');
        }
        if (! empty($pengajuanDataToInsert)) {
            DB::table('t_pengajuan')->insert($pengajuanDataToInsert);
            $this->command->info(count($pengajuanDataToInsert) . ' data pengajuan telah ditambahkan.');
        }

// --- SEEDER UNTUK mahasiswa_skill ---
        $this->command->info('Memulai seeding tabel mahasiswa_skill...');
        $mahasiswaSkillDataToInsert = [];
        $possibleLevels             = ['Beginner', 'Intermediate', 'Expert'];
        $verificationStatusesRandom = ['Pending', 'Pending', 'Valid', 'Invalid']; // Distribusi untuk yang tidak magang

        if (! empty($skillIds)) { // Hanya jalan jika ada master skill
            foreach ($allCreatedMahasiswaIds as $mahasiswaId) {
                $jumlahSkillPerMahasiswa     = rand(2, min(6, count($skillIds)));
                $assignedSkillIdsThisStudent = $faker->randomElements($skillIds, $jumlahSkillPerMahasiswa, false);

                foreach ($assignedSkillIdsThisStudent as $skillId) {
                    $statusVerifikasiFinal = 'Pending'; // Default
                                                        // Jika mahasiswa ini termasuk yang magang aktif/akan datang
                    if (isset($mahasiswaIdsDenganMagangAktif[$mahasiswaId])) {
                        $statusVerifikasiFinal = 'Valid'; // Semua skillnya valid
                    } else {
                        $statusVerifikasiFinal = $faker->randomElement($verificationStatusesRandom);
                    }

                    $mahasiswaSkillDataToInsert[] = [
                        'mahasiswa_id'      => $mahasiswaId,
                        'skill_id'          => $skillId,
                        'level_kompetensi'  => $faker->randomElement($possibleLevels),
                        'status_verifikasi' => $statusVerifikasiFinal,
                        'created_at'        => $now,
                        // 'updated_at'      => $now, // Jika tabel mahasiswa_skill Anda punya kolom ini
                    ];
                }
            }
        }

        if (! empty($mahasiswaSkillDataToInsert)) {
            // DB::table('mahasiswa_skill')->delete(); // Hati-hati
            foreach (array_chunk($mahasiswaSkillDataToInsert, 100) as $chunk) {
                DB::table('mahasiswa_skill')->insert($chunk);
            }
            $this->command->info(count($mahasiswaSkillDataToInsert) . ' data skill mahasiswa telah ditambahkan.');
        } else {
            $this->command->warn('Tidak ada data skill mahasiswa yang di-generate (mungkin karena tidak ada Mahasiswa atau Detail Skill).');
        }
    }
}
