<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // tabel_prodi (Target: 10 data)
        $prodiData = [
            [
                'prodi_id'   => 1,
                'kode_prodi' => 'TI',
                'nama_prodi' => 'Teknik Informatika',
            ],
            [
                'prodi_id'   => 2,
                'kode_prodi' => 'SI',
                'nama_prodi' => 'Sistem Informasi',
            ],
            [
                'prodi_id'   => 3,
                'kode_prodi' => 'MI',
                'nama_prodi' => 'Manajemen Informatika',
            ],
        ];
        for ($i = 4; $i <= 10; $i++) {
            $prodiData[] = [
                'prodi_id'   => $i,
                'kode_prodi' => 'P' . strtoupper(Str::random(2)) . $i,
                'nama_prodi' => 'Prodi Contoh ' . $i,
            ];
        }
        DB::table('tabel_prodi')->insert($prodiData);

        // m_level_user (Target: 10 data)
        // Asumsi ID auto-increment. ADM, MHS, DSN akan jadi ID 1, 2, 3
        $levelUserData = [
            ['level_kode' => 'ADM', 'level_nama' => 'Admin', 'created_at' => $now],
            ['level_kode' => 'MHS', 'level_nama' => 'Mahasiswa', 'created_at' => $now],
            ['level_kode' => 'DSN', 'level_nama' => 'Dosen', 'created_at' => $now],
        ];
        $additionalLevels = ['STF', 'GAA', 'SPV', 'HRD', 'FIN', 'CSO', 'OPR'];
        foreach ($additionalLevels as $kode) {
            $levelUserData[] = ['level_kode' => $kode, 'level_nama' => 'Level ' . $kode, 'created_at' => $now];
        }
        DB::table('m_level_user')->insert($levelUserData);
        // Level User IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_dosen (Target: 10 data)
        // Asumsi Dosen memiliki level_id = 3 (DSN)
        // Asumsi prodi_id merujuk pada tabel_prodi (ID 1-10)
        $dosenData = [
            [
                'nama_lengkap' => 'Dr. Budi Santoso',
                'email'        => 'budi@example.com',
                'password'     => Hash::make('password123'),
                'nip'          => '19800101123456',
                'level_id'     => 3, // DSN
                'prodi_id'     => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'nama_lengkap' => 'Dr. Siti Aminah',
                'email'        => 'siti@example.com',
                'password'     => Hash::make('password123'),
                'nip'          => '19750506123456',
                'level_id'     => 3, // DSN
                'prodi_id'     => 2,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];
        for ($i = 3; $i <= 10; $i++) {
            $dosenData[] = [
                'nama_lengkap' => 'Dr. Dosen ' . Str::ucfirst(Str::random(5)),
                'email'        => 'dosen' . $i . '@example.com',
                'password'     => Hash::make('password123'),
                'nip'          => '19' . rand(70, 90) . '0' . rand(1, 12) . '0' . rand(1, 28) . rand(100000, 999999),
                'level_id'     => 3, // DSN
                'prodi_id'     => rand(1, 10),
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }
        DB::table('m_dosen')->insert($dosenData);
        // Dosen IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_mahasiswa (Target: 10 data)
        // Asumsi Mahasiswa memiliki level_id = 2 (MHS)
        // Asumsi prodi_id merujuk pada tabel_prodi (ID 1-10)
        // Asumsi dosen_id merujuk pada m_dosen (ID 1-10)
        $mahasiswaData = [
            [
                'nama_lengkap' => 'Andi Nugroho',
                'email'        => 'andi@example.com',
                'password'     => Hash::make('mahasiswa123'),
                'nim'          => '22410001',
                'ipk'          => 3.45,
                'status'       => 1,
                'level_id'     => 2, // MHS
                'prodi_id'     => 1,
                'dosen_id'     => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'nama_lengkap' => 'Rina Lestari',
                'email'        => 'rina@example.com',
                'password'     => Hash::make('mahasiswa123'),
                'nim'          => '22410002',
                'ipk'          => 3.80,
                'status'       => 0,
                'level_id'     => 2, // MHS
                'prodi_id'     => 2,
                'dosen_id'     => 2,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];
        for ($i = 3; $i <= 10; $i++) {
            $mahasiswaData[] = [
                'nama_lengkap' => 'Mahasiswa ' . Str::ucfirst(Str::random(7)),
                'email'        => 'mahasiswa' . $i . '@example.com',
                'password'     => Hash::make('mahasiswa123'),
                'nim'          => '224100' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'ipk'          => round(rand(275, 400) / 100, 2),
                'status'       => rand(0, 1),
                'level_id'     => 2, // MHS
                'prodi_id'     => rand(1, 10),
                'dosen_id'     => rand(1, 10),
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }
        DB::table('m_mahasiswa')->insert($mahasiswaData);
        // Mahasiswa IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_provinsi (Target: 10 data)
        $provinsiData = [
            ['provinsi_kode' => 'JTM', 'provinsi_nama' => 'Jawa Timur', 'created_at' => $now],
            ['provinsi_kode' => 'JTG', 'provinsi_nama' => 'Jawa Tengah', 'created_at' => $now],
        ];
        $additionalProvinsi = ['JBR', 'DIY', 'DKI', 'BTN', 'BAL', 'NTB', 'KALTIM', 'SUMUT'];
        for ($i = 0; $i < 8; $i++) {
            $provinsiData[] = ['provinsi_kode' => $additionalProvinsi[$i], 'provinsi_nama' => 'Provinsi ' . $additionalProvinsi[$i], 'created_at' => $now];
        }
        DB::table('m_provinsi')->insert($provinsiData);
        // Provinsi IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_kota (Target: 10 data)
        // Asumsi provinsi_id merujuk pada m_provinsi (ID 1-10)
        $kotaData = [
            ['kota_kode' => 'MLG', 'kota_nama' => 'Malang', 'provinsi_id' => 1, 'created_at' => $now],
            ['kota_kode' => 'SMG', 'kota_nama' => 'Semarang', 'provinsi_id' => 2, 'created_at' => $now],
        ];
        for ($i = 3; $i <= 10; $i++) {
            $kotaData[] = ['kota_kode' => 'KOTA' . strtoupper(Str::random(3)), 'kota_nama' => 'Kota Contoh ' . $i, 'provinsi_id' => rand(1, 10), 'created_at' => $now];
        }
        DB::table('m_kota')->insert($kotaData);
        // Kota IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_user (Target: 10 data)
        // Asumsi level_id merujuk pada m_level_user (ID 1-10)
        // Asumsi prodi_id merujuk pada tabel_prodi (ID 1-10) atau null
        $userData = [
            [
                'nama_lengkap' => 'Admin Satu',
                'email'        => 'admin@example.com',
                'password'     => Hash::make('password'),
                'level_id'     => 1, // ADM
                'prodi_id'     => null,
                'created_at'   => $now,
            ],
            [
                'nama_lengkap' => 'Mahasiswa Satu',
                'email'        => 'mhs@example.com',
                'password'     => Hash::make('password'),
                'level_id'     => 2, // MHS
                'prodi_id'     => 1,
                'created_at'   => $now,
            ],
        ];
        for ($i = 3; $i <= 10; $i++) {
            $levelId    = rand(1, 10);                                           // Bisa level apa saja dari m_level_user
            $prodiId    = ($levelId == 2 || $levelId == 3) ? rand(1, 10) : null; // Prodi hanya jika mahasiswa atau dosen
            $userData[] = [
                'nama_lengkap' => 'User ' . Str::ucfirst(Str::random(6)),
                'email'        => 'user' . $i . '@example.com',
                'password'     => Hash::make('password'),
                'level_id'     => $levelId,
                'prodi_id'     => $prodiId,
                'created_at'   => $now,
            ];
        }
        DB::table('m_user')->insert($userData);
        // User IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_kategori_skill (Target: 10 data)
        $kategoriSkillData = [
            ['kategori_skill_kode' => 'SKL1', 'kategori_nama' => 'Programming', 'created_at' => $now],
        ];
        for ($i = 2; $i <= 10; $i++) {
            $kategoriSkillData[] = ['kategori_skill_kode' => 'SKL' . $i, 'kategori_nama' => 'Kategori Skill ' . $i, 'created_at' => $now];
        }
        DB::table('m_kategori_skill')->insert($kategoriSkillData);
        // Kategori Skill IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_kategori_industri (Target: 10 data)
        $kategoriIndustriData = [
            ['kategori_industri_kode' => 'IND1', 'kategori_nama' => 'Teknologi Informasi', 'created_at' => $now],
        ];
        for ($i = 2; $i <= 10; $i++) {
            $kategoriIndustriData[] = ['kategori_industri_kode' => 'IND' . $i, 'kategori_nama' => 'Kategori Industri ' . $i, 'created_at' => $now];
        }
        DB::table('m_kategori_industri')->insert($kategoriIndustriData);
        // Kategori Industri IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_detail_skill (Target: 10 data)
        // Asumsi kategori_skill_id merujuk pada m_kategori_skill (ID 1-10)
        $detailSkillData = [
            ['skill_nama' => 'Laravel', 'kategori_skill_id' => 1, 'created_at' => $now],
        ];
        for ($i = 2; $i <= 10; $i++) {
            $detailSkillData[] = ['skill_nama' => 'Skill Contoh ' . $i, 'kategori_skill_id' => rand(1, 10), 'created_at' => $now];
        }
        DB::table('m_detail_skill')->insert($detailSkillData);
        // Detail Skill IDs (m_detail_skill_id) akan menjadi 1 s/d 10 secara auto-increment

        // user_preferensi_lokasi (Target: 10 data)
        // Asumsi mahasiswa_id merujuk pada m_mahasiswa (ID 1-10)
        // Asumsi kota_id merujuk pada m_kota (ID 1-10)
        $userPreferensiLokasiData = [
            ['mahasiswa_id' => 2, 'kota_id' => 1, 'prioritas' => 1, 'created_at' => $now], // Mahasiswa Rina Lestari, Kota Malang
            ['mahasiswa_id' => 1, 'kota_id' => 1, 'prioritas' => 1, 'created_at' => $now], // Mahasiswa Andi, Kota Malang
        ];
        $usedUserLokasi = ['2-1' => true];
        for ($i = 0; $i < 9; $i++) { // Need 9 more
            do {
                $mahasiswaId = rand(1, 10);
                $kotaId      = rand(1, 10);
                $combo       = $mahasiswaId . '-' . $kotaId;
            } while (isset($usedUserLokasi[$combo]));
            $userPreferensiLokasiData[] = ['mahasiswa_id' => $mahasiswaId, 'kota_id' => $kotaId, 'prioritas' => rand(1, 3), 'created_at' => $now];
            $usedUserLokasi[$combo]     = true;
        }
        DB::table('user_preferensi_lokasi')->insert($userPreferensiLokasiData);

        // user_skill (Target: 10 data)
        // Asumsi mahasiswa_id merujuk pada m_mahasiswa (ID 1-10)
        // Asumsi skill_id merujuk pada m_detail_skill (m_detail_skill_id, ID 1-10)
        $userSkillData = [
            ['mahasiswa_id' => 2, 'skill_id' => 1, 'created_at' => $now], // Mahasiswa Rina Lestari, Skill Laravel
        ];
        $usedUserSkill = ['2-1' => true];
        for ($i = 0; $i < 9; $i++) { // Need 9 more
            do {
                $mahasiswaId = rand(1, 10);
                $skillId     = rand(1, 10); // Merujuk ke m_detail_skill_id
                $combo       = $mahasiswaId . '-' . $skillId;
            } while (isset($usedUserSkill[$combo]));
            $userSkillData[]       = ['mahasiswa_id' => $mahasiswaId, 'skill_id' => $skillId, 'created_at' => $now];
            $usedUserSkill[$combo] = true;
        }
        DB::table('mahasiswa_skill')->insert($userSkillData);

        // m_industri (Target: 10 data)
        // Asumsi kota_id merujuk pada m_kota (ID 1-10)
        // Asumsi kategori_industri_id merujuk pada m_kategori_industri (ID 1-10)
        $industriData = [
            ['industri_nama' => 'PT Teknologi Cerdas', 'kota_id' => 1, 'kategori_industri_id' => 1, 'email' => 'industri1@example.com', 'telepon' => '081234560001', 'password' => Hash::make('12345678'), 'created_at' => $now],
            ['industri_nama' => 'PT DES Teknologi Informasi', 'kota_id' => 2, 'kategori_industri_id' => 1, 'email' => 'industri2@example.com', 'telepon' => '081234560002', 'password' => Hash::make('12345678'), 'created_at' => $now],
            ['industri_nama' => 'PT Mitra Infosarana', 'kota_id' => 2, 'kategori_industri_id' => 1, 'email' => 'industri3@example.com', 'telepon' => '081234560003', 'password' => Hash::make('12345678'), 'created_at' => $now],
            ['industri_nama' => 'PT Datamax Teknologi Indonesia', 'kota_id' => 1, 'kategori_industri_id' => 1, 'email' => 'industri4@example.com', 'telepon' => '081234560004', 'password' => Hash::make('12345678'), 'created_at' => $now],
        ];

        for ($i = 5; $i <= 14; $i++) {
            $industriData[] = [
                'industri_nama'        => 'PT Industri Maju ' . $i,
                'kota_id'              => rand(1, 10),
                'kategori_industri_id' => rand(1, 10),
                'email'                => 'industri' . $i . '@example.com',
                'telepon'              => '08123456' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'password'             => Hash::make('password' . $i),
                'created_at'           => $now,
            ];
        }

        DB::table('m_industri')->insert($industriData);

        // Industri IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_detail_lowongan (Target: 10 data)
        // Asumsi industri_id merujuk pada m_industri (ID 1-10)
        $detailLowonganData = [
            [
                'judul_lowongan'    => 'Magang Web Developer',
                'deskripsi'         => 'Mengembangkan aplikasi Laravel.',
                'industri_id'       => 1,
                'tanggal_mulai'     => '2025-08-01',
                'tanggal_selesai'   => '2025-12-31',
                'kategori_skill_id' => 1,
                'slot'              => 3,
                'created_at'        => $now,
            ],
        ];
        for ($i = 2; $i <= 10; $i++) {
            $detailLowonganData[] = [
                'judul_lowongan'    => 'Lowongan Contoh ' . $i,
                'deskripsi'         => 'Deskripsi untuk lowongan contoh ' . $i . '. Mencari kandidat berbakat.',
                'industri_id'       => rand(1, 10),
                'tanggal_mulai'     => '2025-08-01',
                'tanggal_selesai'   => '2025-12-31',
                'kategori_skill_id' => rand(1, 10),
                'slot'              => rand(5, 10),
                'created_at'        => $now,
            ];
        }
        DB::table('m_detail_lowongan')->insert($detailLowonganData);
        // Detail Lowongan IDs (m_detail_lowongan_id) akan menjadi 1 s/d 10 secara auto-increment

        // lowongan_skill (Target: 10 data)
        // Asumsi lowongan_id merujuk pada m_detail_lowongan (m_detail_lowongan_id, ID 1-10)
        // Asumsi skill_id merujuk pada m_detail_skill (m_detail_skill_id, ID 1-10)
        $lowonganSkillData = [
            ['lowongan_id' => 1, 'skill_id' => 1, 'created_at' => $now], // Lowongan Magang Web Dev, Skill Laravel
        ];
        $usedLowonganSkill = ['1-1' => true];
        for ($i = 0; $i < 9; $i++) { // Need 9 more
            do {
                $lowonganId = rand(1, 10); // Merujuk ke m_detail_lowongan_id
                $skillId    = rand(1, 10); // Merujuk ke m_detail_skill_id
                $combo      = $lowonganId . '-' . $skillId;
            } while (isset($usedLowonganSkill[$combo]));
            $lowonganSkillData[]       = ['lowongan_id' => $lowonganId, 'skill_id' => $skillId, 'created_at' => $now];
            $usedLowonganSkill[$combo] = true;
        }
        DB::table('lowongan_skill')->insert($lowonganSkillData);
        DB::table('mahasiswa_magang')->insert([
            [
                'mahasiswa_id' => 1,
                'lowongan_id'  => 1,
                'status'       => 'sedang',
                'evaluasi'     => '"InternSync sangat membantu saya menemukan magang yang sesuai dengan minat saya di bidang AI. Rekomendasinya akurat dan prosesnya cepat!"',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'mahasiswa_id' => 2,
                'lowongan_id'  => 2,
                'status'       => 'belum',
                'evaluasi'     => '"Saya sangat puas dengan pengalaman magang saya di InternSync. Prosesnya mudah dan banyak pilihan lowongan yang relevan."',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
            [
                'mahasiswa_id' => 3,
                'lowongan_id'  => 2,
                'status'       => 'belum',
                'evaluasi'     => '"InternSync adalah platform yang sangat membantu dalam mencari magang. Saya menemukan banyak lowongan yang sesuai dengan minat saya."',
                'created_at'   => Carbon::now(),
                'updated_at'   => Carbon::now(),
            ],
        ]);

        DB::table('t_pengajuan')->insert([
            [
                'mahasiswa_id'    => 1,
                'lowongan_id'     => 1,
                'tanggal_mulai'   => '2025-07-01',
                'tanggal_selesai' => '2025-09-30',
                'status'          => 'belum',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'mahasiswa_id'    => 2,
                'lowongan_id'     => 2,
                'tanggal_mulai'   => '2025-08-01',
                'tanggal_selesai' => '2025-10-31',
                'status'          => 'belum',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
            [
                'mahasiswa_id'    => 3,
                'lowongan_id'     => 1,
                'tanggal_mulai'   => '2025-06-15',
                'tanggal_selesai' => '2025-09-15',
                'status'          => 'belum',
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ],
        ]);
//         DB::table('kriteria_magang')->insert([
//     [
//         'lowongan_id' => 1,
//         'relevansi_bidang' => 3,
//         'uang_saku' => 4,
//         'peluang_direkrut' => 4,
//         'reputasi' => 5,
//         'fleksibilitas' => 4,
//         'pembimbing_aktif' => 2,
//         'proyek_nyata' => 4,
//         'kecocokan_teknologi' => 5,
//         'belajar_teknologi_baru' => 4,
//         'akses_tools' => 5,
//         'deskripsi_uang_saku' => 'Rp1.000.000/bulan dibayarkan per akhir bulan',
//         'deskripsi_relevansi' => 'Menggunakan Laravel 10, React, Tailwind, dan GitHub Actions',
//         'deskripsi_peluang' => 'Peluang direkrut jika performa bagus setelah 6 bulan',
//         'deskripsi_reputasi' => 'Sudah mengikuti 3 proyek pemerintah dan menang hackathon',
//         'deskripsi_fleksibilitas' => 'Remote 2 hari/minggu, jam kerja fleksibel',
//         'deskripsi_pembimbing' => 'Dimentori langsung oleh CTO, ada evaluasi mingguan',
//         'deskripsi_proyek' => 'Terlibat dalam pengembangan dashboard admin real-time',
//         'deskripsi_kecocokan' => 'Tech stack cocok dengan kurikulum kampus',
//         'deskripsi_belajar' => 'Diberi pelatihan CI/CD, testing, dan deployment',
//         'deskripsi_tools' => 'Diberi akses ke Jira, GitHub Enterprise, dan VPS staging',
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'lowongan_id' => 2,
//         'relevansi_bidang' => 4,
//         'uang_saku' => 3,
//         'peluang_direkrut' => 5,
//         'reputasi' => 4,
//         'fleksibilitas' => 5,
//         'pembimbing_aktif' => 4,
//         'proyek_nyata' => 4,
//         'kecocokan_teknologi' => 4,
//         'belajar_teknologi_baru' => 5,
//         'akses_tools' => 4,
//         'deskripsi_uang_saku' => 'Rp800.000/bulan, insentif tambahan berdasarkan performa',
//         'deskripsi_relevansi' => 'Fokus pada pengembangan backend dengan Node.js dan Express.js',
//         'deskripsi_peluang' => 'Sangat besar peluang direkrut, mencari talenta untuk tim inti',
//         'deskripsi_reputasi' => 'Startup yang sedang berkembang pesat, didanai oleh venture capital ternama',
//         'deskripsi_fleksibilitas' => 'Full remote, jam kerja sangat fleksibel, meeting mingguan',
//         'deskripsi_pembimbing' => 'Dibimbing oleh Senior Developer, sesi mentoring terjadwal',
//         'deskripsi_proyek' => 'Pengembangan API untuk aplikasi mobile baru',
//         'deskripsi_kecocokan' => 'Sesuai untuk yang ingin mendalami arsitektur microservices',
//         'deskripsi_belajar' => 'Kesempatan belajar DevOps dan implementasi cloud (AWS)',
//         'deskripsi_tools' => 'Akses ke GitLab, Docker, Kubernetes, dan Slack Premium',
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'lowongan_id' => 3,
//         'relevansi_bidang' => 5,
//         'uang_saku' => 5,
//         'peluang_direkrut' => 3,
//         'reputasi' => 5,
//         'fleksibilitas' => 3,
//         'pembimbing_aktif' => 5,
//         'proyek_nyata' => 5,
//         'kecocokan_teknologi' => 5,
//         'belajar_teknologi_baru' => 4,
//         'akses_tools' => 5,
//         'deskripsi_uang_saku' => 'Rp1.500.000/bulan, tunjangan transportasi dan makan siang',
//         'deskripsi_relevansi' => 'Pengembangan aplikasi mobile native Android (Kotlin) dan iOS (Swift)',
//         'deskripsi_peluang' => 'Ada peluang, namun tergantung kebutuhan perusahaan setelah program selesai',
//         'deskripsi_reputasi' => 'Perusahaan multinasional dengan produk yang digunakan jutaan orang',
//         'deskripsi_fleksibilitas' => 'WFO, jam kerja standar 9-5, ada opsi WFH jika diperlukan',
//         'deskripsi_pembimbing' => 'Mentor didedikasikan per tim, review kode rutin',
//         'deskripsi_proyek' => 'Ikut dalam pengembangan fitur baru untuk aplikasi utama',
//         'deskripsi_kecocokan' => 'Sangat cocok untuk yang fokus pada mobile development',
//         'deskripsi_belajar' => 'Pelatihan internal mengenai UI/UX dan best practices mobile dev',
//         'deskripsi_tools' => 'Akses ke Android Studio, Xcode, Figma, Zeplin, dan Jenkins',
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'lowongan_id' => 4,
//         'relevansi_bidang' => 3,
//         'uang_saku' => 2,
//         'peluang_direkrut' => 4,
//         'reputasi' => 3,
//         'fleksibilitas' => 4,
//         'pembimbing_aktif' => 3,
//         'proyek_nyata' => 4,
//         'kecocokan_teknologi' => 3,
//         'belajar_teknologi_baru' => 3,
//         'akses_tools' => 3,
//         'deskripsi_uang_saku' => 'Rp500.000/bulan, lebih fokus pada pengalaman',
//         'deskripsi_relevansi' => 'Maintenance dan pengembangan minor website perusahaan berbasis WordPress',
//         'deskripsi_peluang' => 'Peluang direkrut sebagai junior web developer jika cocok',
//         'deskripsi_reputasi' => 'UKM yang bergerak di bidang e-commerce lokal',
//         'deskripsi_fleksibilitas' => 'Hybrid, 3 hari WFO, 2 hari WFH',
//         'deskripsi_pembimbing' => 'Dibimbing oleh satu-satunya IT support di perusahaan',
//         'deskripsi_proyek' => 'Optimasi SEO dan penambahan fitur di website e-commerce',
//         'deskripsi_kecocokan' => 'Cocok untuk pemula yang ingin belajar dasar web development',
//         'deskripsi_belajar' => 'Belajar dasar PHP, HTML, CSS, JavaScript, dan manajemen hosting',
//         'deskripsi_tools' => 'Akses ke cPanel, Google Analytics, dan tema premium WordPress',
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'lowongan_id' => 5,
//         'relevansi_bidang' => 4,
//         'uang_saku' => 4,
//         'peluang_direkrut' => 4,
//         'reputasi' => 4,
//         'fleksibilitas' => 3,
//         'pembimbing_aktif' => 4,
//         'proyek_nyata' => 5,
//         'kecocokan_teknologi' => 4,
//         'belajar_teknologi_baru' => 4,
//         'akses_tools' => 4,
//         'deskripsi_uang_saku' => 'Rp1.200.000/bulan, benefit makan siang di kantor',
//         'deskripsi_relevansi' => 'Data analysis dan visualization menggunakan Python (Pandas, Matplotlib) dan Tableau',
//         'deskripsi_peluang' => 'Kemungkinan besar direkrut jika mampu memberikan insight berharga',
//         'deskripsi_reputasi' => 'Konsultan data dengan klien perusahaan besar',
//         'deskripsi_fleksibilitas' => 'WFO, namun ada kelonggaran jam datang dan pulang',
//         'deskripsi_pembimbing' => 'Dibimbing oleh Data Scientist berpengalaman, proyek berbasis tim',
//         'deskripsi_proyek' => 'Analisis data penjualan klien untuk identifikasi tren pasar',
//         'deskripsi_kecocokan' => 'Sesuai untuk yang tertarik pada karir di bidang data',
//         'deskripsi_belajar' => 'Pelatihan SQL, teknik-teknik machine learning dasar',
//         'deskripsi_tools' => 'Akses ke Jupyter Notebook, Tableau Desktop, dan database internal',
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'lowongan_id' => 6,
//         'relevansi_bidang' => 5,
//         'uang_saku' => 3,
//         'peluang_direkrut' => 3,
//         'reputasi' => 4,
//         'fleksibilitas' => 5,
//         'pembimbing_aktif' => 4,
//         'proyek_nyata' => 4,
//         'kecocokan_teknologi' => 5,
//         'belajar_teknologi_baru' => 5,
//         'akses_tools' => 4,
//         'deskripsi_uang_saku' => 'Rp900.000/bulan, bonus proyek jika target tercapai',
//         'deskripsi_relevansi' => 'Pengembangan game 2D menggunakan Unity dan C#',
//         'deskripsi_peluang' => 'Peluang ada jika ada proyek game baru setelah magang',
//         'deskripsi_reputasi' => 'Studio game indie dengan beberapa game yang sudah rilis di Steam',
//         'deskripsi_fleksibilitas' => 'Full remote, jadwal meeting fleksibel menyesuaikan tim global',
//         'deskripsi_pembimbing' => 'Dibimbing oleh Game Director dan Lead Programmer',
//         'deskripsi_proyek' => 'Pembuatan prototype game baru dan optimasi game yang sudah ada',
//         'deskripsi_kecocokan' => 'Sangat cocok untuk calon game developer',
//         'deskripsi_belajar' => 'Belajar game design, level design, dan monetisasi game',
//         'deskripsi_tools' => 'Akses ke Unity Pro, Aseprite, Git (via Sourcetree), dan Trello',
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'lowongan_id' => 7,
//         'relevansi_bidang' => 4,
//         'uang_saku' => 5,
//         'peluang_direkrut' => 5,
//         'reputasi' => 5,
//         'fleksibilitas' => 2,
//         'pembimbing_aktif' => 5,
//         'proyek_nyata' => 5,
//         'kecocokan_teknologi' => 4,
//         'belajar_teknologi_baru' => 3,
//         'akses_tools' => 5,
//         'deskripsi_uang_saku' => 'Rp2.000.000/bulan, asuransi kesehatan, dan fasilitas kantor lengkap',
//         'deskripsi_relevansi' => 'Cybersecurity, penetration testing, dan security audit untuk sistem internal',
//         'deskripsi_peluang' => 'Peluang sangat tinggi untuk menjadi Junior Security Analyst',
//         'deskripsi_reputasi' => 'Lembaga keuangan besar dengan standar keamanan tinggi',
//         'deskripsi_fleksibilitas' => 'Full WFO, jam kerja ketat karena terkait keamanan data',
//         'deskripsi_pembimbing' => 'Dibimbing langsung oleh Head of IT Security, program terstruktur',
//         'deskripsi_proyek' => 'Melakukan vulnerability assessment pada aplikasi web dan mobile internal',
//         'deskripsi_kecocokan' => 'Ideal untuk yang ingin berkarir di bidang cybersecurity',
//         'deskripsi_belajar' => 'Pelatihan sertifikasi keamanan (misal CEH مقدماتی)',
//         'deskripsi_tools' => 'Akses ke Kali Linux, Burp Suite Pro, Nessus, dan SIEM tools',
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'lowongan_id' => 8,
//         'relevansi_bidang' => 3,
//         'uang_saku' => 3,
//         'peluang_direkrut' => 3,
//         'reputasi' => 3,
//         'fleksibilitas' => 4,
//         'pembimbing_aktif' => 3,
//         'proyek_nyata' => 3,
//         'kecocokan_teknologi' => 3,
//         'belajar_teknologi_baru' => 4,
//         'akses_tools' => 3,
//         'deskripsi_uang_saku' => 'Rp750.000/bulan',
//         'deskripsi_relevansi' => 'UI/UX Design menggunakan Figma dan Adobe XD untuk website agensi',
//         'deskripsi_peluang' => 'Tergantung performa dan kebutuhan desain grafis setelahnya',
//         'deskripsi_reputasi' => 'Agensi digital marketing skala kecil menengah',
//         'deskripsi_fleksibilitas' => 'Remote, dengan beberapa kali pertemuan offline untuk brainstorming',
//         'deskripsi_pembimbing' => 'Dibimbing oleh Creative Director, feedback reguler',
//         'deskripsi_proyek' => 'Redesign halaman landing page klien dan pembuatan aset visual',
//         'deskripsi_kecocokan' => 'Bagi yang tertarik pada desain antarmuka dan pengalaman pengguna',
//         'deskripsi_belajar' => 'Belajar prinsip desain, user research, dan prototyping',
//         'deskripsi_tools' => 'Akses ke Figma Pro, Adobe Creative Cloud (subset), dan Miro',
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'lowongan_id' => 9,
//         'relevansi_bidang' => 5,
//         'uang_saku' => 4,
//         'peluang_direkrut' => 4,
//         'reputasi' => 4,
//         'fleksibilitas' => 4,
//         'pembimbing_aktif' => 5,
//         'proyek_nyata' => 5,
//         'kecocokan_teknologi' => 4,
//         'belajar_teknologi_baru' => 5,
//         'akses_tools' => 4,
//         'deskripsi_uang_saku' => 'Rp1.300.000/bulan, voucher makan siang',
//         'deskripsi_relevansi' => 'Cloud engineering dengan fokus pada AWS (EC2, S3, Lambda, RDS)',
//         'deskripsi_peluang' => 'Peluang bagus untuk posisi Junior Cloud Engineer',
//         'deskripsi_reputasi' => 'Perusahaan penyedia solusi cloud untuk enterprise',
//         'deskripsi_fleksibilitas' => 'Hybrid, 2 hari WFO, 3 hari WFH, jam fleksibel',
//         'deskripsi_pembimbing' => 'Dibimbing oleh Certified AWS Solutions Architect, ada jalur sertifikasi',
//         'deskripsi_proyek' => 'Migrasi infrastruktur on-premise klien ke cloud AWS',
//         'deskripsi_kecocokan' => 'Sangat relevan untuk yang ingin spesialisasi di cloud computing',
//         'deskripsi_belajar' => 'Belajar Infrastructure as Code (Terraform), monitoring (CloudWatch)',
//         'deskripsi_tools' => 'Akses ke AWS Management Console, Terraform, Ansible, dan Datadog',
//         'created_at' => now(),
//         'updated_at' => now(),
//     ],
//     [
//         'lowongan_id' => 10,
//         'relevansi_bidang' => 4,
//         'uang_saku' => 3,
//         'peluang_direkrut' => 3,
//         'reputasi' => 3,
//         'fleksibilitas' => 3,
//         'pembimbing_aktif' => 4,
//         'proyek_nyata' => 4,
//         'kecocokan_teknologi' => 3,
//         'belajar_teknologi_baru' => 3,
//         'akses_tools' => 3,
//         'deskripsi_uang_saku' => 'Rp850.000/bulan',
//         'deskripsi_relevansi' => 'Quality Assurance (QA) manual dan otomatisasi dasar dengan Selenium',
//         'deskripsi_peluang' => 'Ada kemungkinan jika tim QA membutuhkan anggota baru',
//         'deskripsi_reputasi' => 'Software house yang mengerjakan proyek untuk berbagai klien',
//         'deskripsi_fleksibilitas' => 'WFO, jam standar, namun bisa pulang lebih awal jika tugas selesai',
//         'deskripsi_pembimbing' => 'Dibimbing oleh QA Lead, terlibat dalam siklus testing',
//         'deskripsi_proyek' => 'Testing fungsional dan non-fungsional aplikasi web klien',
//         'deskripsi_kecocokan' => 'Cocok untuk yang teliti dan tertarik menjadi QA Engineer',
//         'deskripsi_belajar' => 'Belajar membuat test case, test plan, dan reporting bug',
//         'deskripsi_tools' => 'Akses ke Selenium IDE, Jira, TestRail, dan browser testing tools',
//         'created_at' => now(),
//         'updated_at' => now(),
//     ]
// ]);
//     $dataKriteriaMahasiswa = [];

//             for ($i = 1; $i <= 10; $i++) {
//                 $dataKriteriaMahasiswa[] = [
//                     'mahasiswa_id' => $i,
//                     'skill' => rand(60, 100),
//                     'pengalaman' => rand(60, 100),
//                     'kompetensi' => rand(60, 100),
//                     'sertifikasi' => rand(60, 100),
//                     'komunikasi' => rand(60, 100),
//                     'created_at' => now(),
//                     'updated_at' => now(),
//                 ];
//             }

//             DB::table('kriteria_mahasiswa')->insert($dataKriteriaMahasiswa);
    }
}
