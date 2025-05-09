<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // tabel_prodi (Target: 10 data)
        $prodiData = [
            [
                'prodi_id'=> 1,
                'kode_prodi'=> 'TI',
                'nama_prodi'=> 'Teknik Informatika'
            ],
            [
                'prodi_id'=> 2,
                'kode_prodi'=> 'SI',
                'nama_prodi'=> 'Sistem Informasi'
            ],
            [
                'prodi_id'=> 3,
                'kode_prodi'=> 'MI',
                'nama_prodi'=> 'Manajemen Informatika'
            ],
        ];
        for ($i = 4; $i <= 10; $i++) {
            $prodiData[] = [
                'prodi_id' => $i,
                'kode_prodi' => 'P'.strtoupper(Str::random(2)).$i,
                'nama_prodi' => 'Prodi Contoh '.$i
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
            $levelUserData[] = ['level_kode' => $kode, 'level_nama' => 'Level '. $kode, 'created_at' => $now];
        }
        DB::table('m_level_user')->insert($levelUserData);
        // Level User IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_dosen (Target: 10 data)
        // Asumsi Dosen memiliki level_id = 3 (DSN)
        // Asumsi prodi_id merujuk pada tabel_prodi (ID 1-10)
        $dosenData = [
            [
                'nama_lengkap' => 'Dr. Budi Santoso',
                'email' => 'budi@example.com',
                'password' => Hash::make('password123'),
                'nip' => '19800101123456',
                'level_id' => 3, // DSN
                'prodi_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_lengkap' => 'Dr. Siti Aminah',
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'nip' => '19750506123456',
                'level_id' => 3, // DSN
                'prodi_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];
        for ($i = 3; $i <= 10; $i++) {
            $dosenData[] = [
                'nama_lengkap' => 'Dr. Dosen ' . Str::ucfirst(Str::random(5)),
                'email' => 'dosen'.$i.'@example.com',
                'password' => Hash::make('password123'),
                'nip' => '19'.rand(70,90).'0'.rand(1,12).'0'.rand(1,28).rand(100000,999999),
                'level_id' => 3, // DSN
                'prodi_id' => rand(1, 10),
                'created_at' => $now,
                'updated_at' => $now,
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
                'email' => 'andi@example.com',
                'password' => Hash::make('mahasiswa123'),
                'nim' => '22410001',
                'ipk' => 3.45,
                'status' => 1,
                'level_id' => 2, // MHS
                'prodi_id' => 1,
                'dosen_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_lengkap' => 'Rina Lestari',
                'email' => 'rina@example.com',
                'password' => Hash::make('mahasiswa123'),
                'nim' => '22410002',
                'ipk' => 3.80,
                'status' => 0,
                'level_id' => 2, // MHS
                'prodi_id' => 2,
                'dosen_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];
        for ($i = 3; $i <= 10; $i++) {
            $mahasiswaData[] = [
                'nama_lengkap' => 'Mahasiswa ' . Str::ucfirst(Str::random(7)),
                'email' => 'mahasiswa'.$i.'@example.com',
                'password' => Hash::make('mahasiswa123'),
                'nim' => '224100' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'ipk' => round(rand(275, 400) / 100, 2),
                'status' => rand(0, 1),
                'level_id' => 2, // MHS
                'prodi_id' => rand(1, 10),
                'dosen_id' => rand(1, 10),
                'created_at' => $now,
                'updated_at' => $now,
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
            $kotaData[] = ['kota_kode' => 'KOTA'.strtoupper(Str::random(3)), 'kota_nama' => 'Kota Contoh '.$i, 'provinsi_id' => rand(1, 10), 'created_at' => $now];
        }
        DB::table('m_kota')->insert($kotaData);
        // Kota IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_user (Target: 10 data)
        // Asumsi level_id merujuk pada m_level_user (ID 1-10)
        // Asumsi prodi_id merujuk pada tabel_prodi (ID 1-10) atau null
        $userData = [
            [
                'nama_lengkap' => 'Admin Satu',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'level_id' => 1, // ADM
                'prodi_id'=> null,
                'created_at' => $now
            ],
            [
                'nama_lengkap' => 'Mahasiswa Satu',
                'email' => 'mhs@example.com',
                'password' => Hash::make('password'),
                'level_id' => 2, // MHS
                'prodi_id'=> 1,
                'created_at' => $now
            ],
        ];
        for ($i = 3; $i <= 10; $i++) {
            $levelId = rand(1, 10); // Bisa level apa saja dari m_level_user
            $prodiId = ($levelId == 2 || $levelId == 3) ? rand(1, 10) : null; // Prodi hanya jika mahasiswa atau dosen
            $userData[] = [
                'nama_lengkap' => 'User ' . Str::ucfirst(Str::random(6)),
                'email' => 'user'.$i.'@example.com',
                'password' => Hash::make('password'),
                'level_id' => $levelId,
                'prodi_id'=> $prodiId,
                'created_at' => $now
            ];
        }
        DB::table('m_user')->insert($userData);
        // User IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_kategori_kompetensi (Target: 10 data)
        $kategoriKompetensiData = [
            ['kategori_kompetensi_id' => 1, 'kategori_kompetensi_kode' => 'KOMP1', 'kategori_nama' => 'Manajerial', 'created_at' => $now],
        ];
        for ($i = 2; $i <= 10; $i++) {
            $kategoriKompetensiData[] = ['kategori_kompetensi_id' => $i, 'kategori_kompetensi_kode' => 'KOMP'.$i, 'kategori_nama' => 'Kategori Kompetensi '.$i, 'created_at' => $now];
        }
        DB::table('m_kategori_kompetensi')->insert($kategoriKompetensiData);

        // m_kategori_skill (Target: 10 data)
        $kategoriSkillData = [
            ['kategori_skill_kode' => 'SKL1', 'kategori_nama' => 'Programming', 'created_at' => $now],
        ];
        for ($i = 2; $i <= 10; $i++) {
            $kategoriSkillData[] = ['kategori_skill_kode' => 'SKL'.$i, 'kategori_nama' => 'Kategori Skill '.$i, 'created_at' => $now];
        }
        DB::table('m_kategori_skill')->insert($kategoriSkillData);
        // Kategori Skill IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_kategori_lowongan (Target: 10 data)
        $kategoriLowonganData = [
            ['kategori_lowongan_kode' => 'LOW1', 'kategori_nama' => 'Magang', 'created_at' => $now],
        ];
        for ($i = 2; $i <= 10; $i++) {
            $kategoriLowonganData[] = ['kategori_lowongan_kode' => 'LOW'.$i, 'kategori_nama' => 'Kategori Lowongan '.$i, 'created_at' => $now];
        }
        DB::table('m_kategori_lowongan')->insert($kategoriLowonganData);
        // Kategori Lowongan IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_kategori_industri (Target: 10 data)
        $kategoriIndustriData = [
            ['kategori_industri_kode' => 'IND1', 'kategori_nama' => 'Teknologi Informasi', 'created_at' => $now],
        ];
        for ($i = 2; $i <= 10; $i++) {
            $kategoriIndustriData[] = ['kategori_industri_kode' => 'IND'.$i, 'kategori_nama' => 'Kategori Industri '.$i, 'created_at' => $now];
        }
        DB::table('m_kategori_industri')->insert($kategoriIndustriData);
        // Kategori Industri IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_detail_skill (Target: 10 data)
        // Asumsi kategori_skill_id merujuk pada m_kategori_skill (ID 1-10)
        $detailSkillData = [
            ['skill_nama' => 'Laravel', 'kategori_skill_id' => 1, 'created_at' => $now],
        ];
        for ($i = 2; $i <= 10; $i++) {
            $detailSkillData[] = ['skill_nama' => 'Skill Contoh '.$i, 'kategori_skill_id' => rand(1, 10), 'created_at' => $now];
        }
        DB::table('m_detail_skill')->insert($detailSkillData);
        // Detail Skill IDs (m_detail_skill_id) akan menjadi 1 s/d 10 secara auto-increment

        // user_preferensi_lokasi (Target: 10 data)
        // Asumsi mahasiswa_id merujuk pada m_mahasiswa (ID 1-10)
        // Asumsi kota_id merujuk pada m_kota (ID 1-10)
        $userPreferensiLokasiData = [
            ['mahasiswa_id' => 2, 'kota_id' => 1, 'prioritas' => 1, 'created_at' => $now], // Mahasiswa Rina Lestari, Kota Malang
        ];
        $usedUserLokasi = ['2-1' => true];
        for ($i = 0; $i < 9; $i++) { // Need 9 more
            do {
                $mahasiswaId = rand(1, 10);
                $kotaId = rand(1, 10);
                $combo = $mahasiswaId . '-' . $kotaId;
            } while (isset($usedUserLokasi[$combo]));
            $userPreferensiLokasiData[] = ['mahasiswa_id' => $mahasiswaId, 'kota_id' => $kotaId, 'prioritas' => rand(1, 3), 'created_at' => $now];
            $usedUserLokasi[$combo] = true;
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
                $skillId = rand(1, 10); // Merujuk ke m_detail_skill_id
                $combo = $mahasiswaId . '-' . $skillId;
            } while (isset($usedUserSkill[$combo]));
            $userSkillData[] = ['mahasiswa_id' => $mahasiswaId, 'skill_id' => $skillId, 'created_at' => $now];
            $usedUserSkill[$combo] = true;
        }
        DB::table('user_skill')->insert($userSkillData);

        // m_industri (Target: 10 data)
        // Asumsi kota_id merujuk pada m_kota (ID 1-10)
        // Asumsi kategori_industri_id merujuk pada m_kategori_industri (ID 1-10)
        $industriData = [
            ['industri_nama' => 'PT Teknologi Cerdas', 'kota_id' => 1, 'kategori_industri_id' => 1, 'created_at' => $now], 
            ['industri_nama' => 'PT DES Teknologi Informasi', 'kota_id' => 2, 'kategori_industri_id' => 1, 'created_at' => $now],
            ['industri_nama' => 'PT Mitra Infosarana', 'kota_id' => 2, 'kategori_industri_id' => 1, 'created_at' => $now],
            ['industri_nama' => 'PT Datamax Teknologi Indonesia', 'kota_id' => 1, 'kategori_industri_id' => 1, 'created_at' => $now],
        ];
        for ($i = 2; $i <= 10; $i++) {
            $industriData[] = [
                'industri_nama' => 'PT Industri Maju '.$i,
                'kota_id' => rand(1, 10),
                'kategori_industri_id' => rand(1, 10),
                'created_at' => $now // Added created_at
            ];
        }
        DB::table('m_industri')->insert($industriData);
        // Industri IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_detail_lowongan (Target: 10 data)
        // Asumsi industri_id merujuk pada m_industri (ID 1-10)
        // Asumsi kategori_lowongan_id merujuk pada m_kategori_lowongan (ID 1-10)
        $detailLowonganData = [
            [
                'judul_lowongan' => 'Magang Web Developer',
                'deskripsi' => 'Mengembangkan aplikasi Laravel.',
                'industri_id' => 1,
                'kategori_lowongan_id' => 1,
                'created_at' => $now
            ],
        ];
        for ($i = 2; $i <= 10; $i++) {
            $detailLowonganData[] = [
                'judul_lowongan' => 'Lowongan Contoh '.$i,
                'deskripsi' => 'Deskripsi untuk lowongan contoh '.$i.'. Mencari kandidat berbakat.',
                'industri_id' => rand(1, 10),
                'kategori_lowongan_id' => rand(1, 10),
                'created_at' => $now
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
                $skillId = rand(1, 10);    // Merujuk ke m_detail_skill_id
                $combo = $lowonganId . '-' . $skillId;
            } while (isset($usedLowonganSkill[$combo]));
            $lowonganSkillData[] = ['lowongan_id' => $lowonganId, 'skill_id' => $skillId, 'created_at' => $now];
            $usedLowonganSkill[$combo] = true;
        }
        DB::table('lowongan_skill')->insert($lowonganSkillData);

        // m_detail_kompetensi (Target: 10 data)
        // Asumsi kategori_kompetensi_id merujuk pada m_kategori_kompetensi (ID 1-10)
        // Nama kolom kompetensi_nama dan nama_matkul, saya pilih standardisasi ke 'kompetensi_nama'
        $detailKompetensiData = [
            [
                'kompetensi_id' => 1,
                'nama_matkul' => 'Bahasa Inggris',
                'kategori_kompetensi_id' => 1,
                'created_at' => $now,
            ],
            [
                'kompetensi_id' => 2,
                'nama_matkul' => 'PBO',
                'kategori_kompetensi_id' => 1,
                'created_at' => $now,
            ],
        ];
        for ($i = 3; $i <= 10; $i++) {
            $detailKompetensiData[] = [
                'kompetensi_id' => $i,
                'nama_matkul' => 'Kompetensi Uji '.$i,
                'kategori_kompetensi_id' => rand(1, 10),
                'created_at' => $now,
            ];
        }
        DB::table('m_detail_kompetensi')->insert($detailKompetensiData);

        // user_kompetensi (Target: 10 data)
        // Asumsi mahasiswa_id merujuk pada m_mahasiswa (ID 1-10)
        // Asumsi kompetensi_id merujuk pada m_detail_kompetensi (kompetensi_id, ID 1-10)
        $userKompetensiData = [
            [
                'mahasiswa_id' => 1, // Andi Nugroho
                'kompetensi_id' => 1, // Bahasa Inggris
                'nilai' => 85.50,
                'created_at' => $now
            ],
            [
                'mahasiswa_id' => 1, // Andi Nugroho
                'kompetensi_id' => 2, // PBO
                'nilai' => 86.50,
                'created_at' => $now
            ],
            [
                'mahasiswa_id' => 2, // Rina Lestari
                'kompetensi_id' => 2, // PBO
                'nilai' => 92.00,
                'created_at' => $now
            ],
        ];
        $usedUserKompetensi = ['1-1' => true, '1-2' => true, '2-2' => true];
        for ($i = 0; $i < 7; $i++) { // Need 7 more
            do {
                $mahasiswaId = rand(1, 10);
                $kompetensiId = rand(1, 10); // Merujuk ke kompetensi_id di m_detail_kompetensi
                $combo = $mahasiswaId . '-' . $kompetensiId;
            } while (isset($usedUserKompetensi[$combo]));
            $userKompetensiData[] = [
                'mahasiswa_id' => $mahasiswaId,
                'kompetensi_id' => $kompetensiId,
                'nilai' => round(rand(7000, 9950) / 100, 2),
                'created_at' => $now
            ];
            $usedUserKompetensi[$combo] = true;
        }
        DB::table('user_kompetensi')->insert($userKompetensiData);
    }
}
