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
        $now   = Carbon::now();
        $faker = Factory::create('id_ID');

        // =================================================================
        // LANGKAH 1: PERSIAPAN DATA MASTER DAN MAPPING
        // =================================================================
        $this->command->info('Memuat data master Dosen, Prodi, Lowongan, dan Skill...');

        $dpaIds    = DB::table('m_dosen')->where('role_dosen', 'dpa')->pluck('dosen_id')->toArray();
        $pembimbingIds = DB::table('m_dosen')->where('role_dosen', 'pembimbing')->pluck('dosen_id')->toArray();

        // **PERUBAHAN DISINI**: Menyesuaikan nama tabel dan kolom prodi
        $prodiMap  = DB::table('tabel_prodi')->pluck('prodi_id', 'nama_prodi')->toArray();

        $skillMap  = DB::table('m_detail_skill')->pluck('skill_id', 'skill_nama')->toArray();

        // Mapping Lowongan ke Skill yang dibutuhkan
        $lowonganSkills = DB::table('lowongan_skill')->get(['lowongan_id', 'skill_id']);
        $lowonganToSkillsMap = [];
        foreach ($lowonganSkills as $ls) {
            $lowonganToSkillsMap[$ls->lowongan_id][] = $ls->skill_id;
        }

        // Validasi data master
        if (empty($dpaIds) || empty($prodiMap) || empty($skillMap) || empty($lowonganToSkillsMap)) {
            $this->command->error('Satu atau lebih data master (DPA, Prodi, Skill, Lowongan) kosong. Seeder dihentikan.');
            return;
        }

        // =================================================================
        // LANGKAH 2: DEFINISI PERSONA MAHASISWA DAN SKILL MEREKA
        // =================================================================
        // **PERUBAHAN DISINI**: Persona disesuaikan dengan 3 prodi yang tersedia.
        $personas = [
            'Backend Developer' => [
                'prodi' => 'Teknik Informatika',
                'skills' => ['PHP', 'Laravel', 'MySQL', 'RESTful API Design', 'Git & Version Control', 'Node.js', 'PostgreSQL'],
            ],
            'Frontend Developer' => [
                'prodi' => 'Sistem Informasi',
                'skills' => ['HTML5', 'CSS3', 'JavaScript (ES6+)', 'React.js', 'Vue.js', 'Tailwind CSS', 'Figma'],
            ],
            'Mobile Developer' => [
                'prodi' => 'Teknik Informatika', // Mobile dev lebih dekat ke computer science/teknik informatika
                'skills' => ['Kotlin (Android)', 'Swift (iOS)', 'Flutter (Dart)', 'Java (Android)', 'RESTful API Design'],
            ],
            'Data Analyst' => [
                'prodi' => 'Sistem Informasi', // Sistem Informasi sering mencakup analisis data bisnis
                'skills' => ['Python (Data - Pandas, NumPy, SciPy)', 'SQL (Analitik Data)', 'Tableau', 'Power BI', 'ETL (Extract, Transform, Load)'],
            ],
            'UI/UX Designer' => [
                'prodi' => 'Manajemen Informatika', // Prodi ini sering menjadi jembatan antara teknis dan pengguna
                'skills' => ['Figma', 'Adobe XD', 'User Research', 'Wireframing & Prototyping', 'Komunikasi Teknis'],
            ],
            'DevOps Engineer' => [
                'prodi' => 'Teknik Informatika',
                'skills' => ['Docker', 'Kubernetes', 'Amazon Web Services (AWS)', 'CI/CD (Jenkins, GitLab CI)', 'Network Security'],
            ],
        ];

        // Konversi nama skill di persona menjadi ID
        foreach ($personas as $name => &$persona) {
            $persona['prodi_id'] = $prodiMap[$persona['prodi']] ?? null;
            $persona['skill_ids'] = collect($persona['skills'])->map(fn ($skillName) => $skillMap[$skillName] ?? null)->filter()->toArray();
        }

        // =================================================================
        // LANGKAH 3: BUAT DATA MAHASISWA BERDASARKAN PERSONA
        // =================================================================
        $this->command->info('Membuat data mahasiswa berdasarkan persona...');
        $mahasiswaData = [];
        $mahasiswaToPersonaMap = []; // Menyimpan persona untuk setiap mahasiswa_id
        $jumlahMahasiswa = 50;
        $personaKeys = array_keys($personas);

        // Map kode prodi untuk pembuatan NIM
        $prodiKodeMap = DB::table('tabel_prodi')->pluck('kode_prodi', 'prodi_id')->toArray();

        for ($i = 1; $i <= $jumlahMahasiswa; $i++) {
            $personaName = $faker->randomElement($personaKeys);
            $currentPersona = $personas[$personaName];

            if (!$currentPersona['prodi_id']) continue; // Lewati jika prodi untuk persona ini tidak ditemukan

            $kodeProdi = $prodiKodeMap[$currentPersona['prodi_id']] ?? 'XX';
            $nim = $kodeProdi . '.' . (2022 - rand(0, 2)) . '.' . $faker->unique()->numerify('#####');

            $mahasiswaData[] = [
                'nama_lengkap'    => $faker->name,
                'email'           => $faker->unique()->safeEmail,
                'password'        => Hash::make('mahasiswa123'),
                'nim'             => $nim,
                'ipk'             => round($faker->randomFloat(2, 3.20, 4.00), 2),
                'status'          => 1,
                'status_verifikasi' => "Pending",
                'prodi_id'        => $currentPersona['prodi_id'],
                'dpa_id'          => $dpaIds[array_rand($dpaIds)],
                'dosen_id'        => null,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
            // Simpan sementara persona yang dipilih untuk mahasiswa ini
            $mahasiswaToPersonaMap[$i] = $currentPersona;
        }

        DB::table('m_mahasiswa')->insert($mahasiswaData);
        $this->command->info(count($mahasiswaData) . ' data mahasiswa telah ditambahkan.');
        $allCreatedMahasiswaIds = DB::table('m_mahasiswa')->pluck('mahasiswa_id')->toArray();


        // =================================================================
        // LANGKAH 4: BUAT DATA PENGAJUAN & MAGANG SECARA LOGIS
        // =================================================================
        $this->command->info('Membuat data pengajuan & magang secara logis...');
        $pengajuanDataToInsert = [];
        $magangDataToInsert = [];
        $mahasiswaSkillDataToInsert = [];
        $usedMahasiswaIds = [];

        foreach ($allCreatedMahasiswaIds as $index => $mahasiswaId) {
            $mahasiswaIndexForPersona = $index + 1;
            if (!isset($mahasiswaToPersonaMap[$mahasiswaIndexForPersona])) continue;

            $persona = $mahasiswaToPersonaMap[$mahasiswaIndexForPersona];
            $mahasiswaSkills = $persona['skill_ids'];

            // Buat data skill untuk mahasiswa ini
            foreach ($mahasiswaSkills as $skillId) {
                 $mahasiswaSkillDataToInsert[] = [
                    'mahasiswa_id'      => $mahasiswaId,
                    'skill_id'          => $skillId,
                    'level_kompetensi'  => $faker->randomElement(['Beginner', 'Intermediate']),
                    'status_verifikasi' => 'Pending',
                    'created_at'        => $now,
                ];
            }

            // Tentukan lowongan yang cocok untuk mahasiswa ini
            $cocokLowonganIds = [];
            foreach ($lowonganToSkillsMap as $lowonganId => $requiredSkills) {
                // Lowongan dianggap cocok jika minimal 50% skillnya dimiliki mahasiswa
                $matchCount = count(array_intersect($mahasiswaSkills, $requiredSkills));
                if ($matchCount > 0 && ($matchCount / count($requiredSkills)) >= 0.5) {
                    $cocokLowonganIds[] = $lowonganId;
                }
            }

            if (empty($cocokLowonganIds)) continue; // Tidak ada lowongan cocok, mahasiswa ini tidak melamar

            // Tentukan status akhir pengajuan
            $statusPengajuan = $faker->randomElement(['diterima', 'diterima', 'ditolak', 'belum', 'belum']);

            if (in_array($mahasiswaId, $usedMahasiswaIds)) continue; // Pastikan 1 mahasiswa 1 pengajuan

            $lowonganPilihanId = $faker->randomElement($cocokLowonganIds);

            $pengajuanDataToInsert[] = [
                'mahasiswa_id'    => $mahasiswaId,
                'lowongan_id'     => $lowonganPilihanId,
                'tanggal_mulai'   => Carbon::parse("2025-08-01")->toDateString(),
                'tanggal_selesai' => Carbon::parse("2025-12-31")->toDateString(),
                'status'          => $statusPengajuan,
                'alasan_penolakan'=> $statusPengajuan === 'ditolak' ? 'Kompetensi atau IPK belum memenuhi syarat.' : null,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];

            if ($statusPengajuan === 'diterima') {
                 $magangDataToInsert[] = [
                    'mahasiswa_id' => $mahasiswaId,
                    'lowongan_id'  => $lowonganPilihanId,
                    'status'       => $faker->randomElement(['belum', 'sedang']),
                    'evaluasi'     => null,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
                // Update dosen pembimbing & verifikasi skill untuk yg diterima
                if (!empty($pembimbingIds)) {
                     DB::table('m_mahasiswa')->where('mahasiswa_id', $mahasiswaId)->update(['dosen_id' => $faker->randomElement($pembimbingIds)]);
                }
                DB::table('mahasiswa_skill')->where('mahasiswa_id', $mahasiswaId)->update(['status_verifikasi' => 'Valid']);
            }
            $usedMahasiswaIds[] = $mahasiswaId;
        }

        // Insert semua data yang sudah disiapkan
        if (!empty($mahasiswaSkillDataToInsert)) DB::table('mahasiswa_skill')->insert($mahasiswaSkillDataToInsert);
        if (!empty($pengajuanDataToInsert)) DB::table('t_pengajuan')->insert($pengajuanDataToInsert);
        if (!empty($magangDataToInsert)) DB::table('mahasiswa_magang')->insert($magangDataToInsert);

        $this->command->info(count($pengajuanDataToInsert) . ' data pengajuan, ' . count($magangDataToInsert) . ' data magang, dan ' . count($mahasiswaSkillDataToInsert) . ' data skill mahasiswa telah ditambahkan.');
    }
}
