<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Database\Seeders\KotaSeeder;
use Database\Seeders\SkillSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\IndustriSeeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\FasilitasSeeder;
use Database\Seeders\TipeKerjaSeeder;
use Database\Seeders\LowonganSkillSeeder;
use Database\Seeders\DetailLowonganSeeder;
use Database\Seeders\PreferensiLokasiSeeder;
use Database\Seeders\MahasiswaMagangPengajuanSkillSeeder;

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
        DB::table('tabel_prodi')->insert($prodiData);

        $dosenData = [
            [
                'nama_lengkap' => 'Dr. Budi Santoso',
                'email'        => 'budi.dpa@example.com', // Email diubah agar unik & menandakan DPA
                'password'     => Hash::make('12345678'),
                'nip'          => '19800101123456',
                'role_dosen'   => 'dpa', // <-- DITAMBAHKAN: Peran Dosen
                'prodi_id'     => 1,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [
                'nama_lengkap' => 'Dr. Siti Aminah',
                'email'        => 'siti.pembimbing@example.com', // Email diubah
                'password'     => Hash::make('12345678'),
                'nip'          => '19750506123456',
                'role_dosen'   => 'pembimbing', // <-- DITAMBAHKAN: Peran Dosen
                'prodi_id'     => 2,
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [ // Tambahkan satu DPA lagi secara eksplisit
                'nama_lengkap' => 'Prof. Dr. Ahmad Dahlan',
                'email'        => 'ahmad.dpa@example.com',
                'password'     => Hash::make('12345678'),
                'nip'          => '197803152005011001',
                'role_dosen'   => 'dpa',
                'prodi_id'     => 3, // Contoh prodi
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
            [ // Tambahkan satu pembimbing lagi secara eksplisit
                'nama_lengkap' => 'Dr. Indah Permata',
                'email'        => 'indah.pembimbing@example.com',
                'password'     => Hash::make('12345678'),
                'nip'          => '198207202008012002',
                'role_dosen'   => 'pembimbing',
                'prodi_id'     => 3, // Contoh prodi
                'created_at'   => $now,
                'updated_at'   => $now,
            ],
        ];

        DB::table('m_dosen')->insert($dosenData);

        // Mahasiswa IDs akan menjadi 1 s/d 10 secara auto-increment

        $this->call(ProvinsiSeeder::class);

        $this->call(KotaSeeder::class);

        $userData = [
            [
                'nama_lengkap' => 'Admin Satu',
                'email'        => 'admin@example.com',
                'password'     => Hash::make('password'),
                'created_at'   => $now,
            ],
        ];
        DB::table('m_user')->insert($userData);

        $this->call(SkillSeeder::class);

        $kategoriIndustriData = [
            ['kategori_industri_kode' => 'IND1', 'kategori_nama' => 'Teknologi Informasi dan Komunikasi', 'created_at' => $now],
            ['kategori_industri_kode' => 'IND2', 'kategori_nama' => 'Keuangan dan Asuransi', 'created_at' => $now],
            ['kategori_industri_kode' => 'IND3', 'kategori_nama' => 'Kesehatan dan Farmasi', 'created_at' => $now],
            ['kategori_industri_kode' => 'IND4', 'kategori_nama' => 'Manufaktur dan Pengolahan', 'created_at' => $now],
            ['kategori_industri_kode' => 'IND5', 'kategori_nama' => 'Perdagangan dan Ritel', 'created_at' => $now],
            ['kategori_industri_kode' => 'IND6', 'kategori_nama' => 'Konstruksi dan Properti', 'created_at' => $now],
            ['kategori_industri_kode' => 'IND7', 'kategori_nama' => 'Pariwisata dan Perhotelan', 'created_at' => $now],
            ['kategori_industri_kode' => 'IND8', 'kategori_nama' => 'Transportasi dan Logistik', 'created_at' => $now],
            ['kategori_industri_kode' => 'IND9', 'kategori_nama' => 'Pendidikan', 'created_at' => $now],
            ['kategori_industri_kode' => 'IND10', 'kategori_nama' => 'Energi dan Pertambangan', 'created_at' => $now],
        ];

        DB::table('m_kategori_industri')->insert($kategoriIndustriData);

        $this->call(IndustriSeeder::class);

        $this->call(DetailLowonganSeeder::class);
        // Detail Lowongan IDs (m_detail_lowongan_id) akan menjadi 1 s/d 10 secara auto-increment

        // lowongan_skill (Target: 10 data)
        // Asumsi lowongan_id merujuk pada m_detail_lowongan (m_detail_lowongan_id, ID 1-10)
        // Asumsi skill_id merujuk pada m_detail_skill (m_detail_skill_id, ID 1-10)
        $this->call(LowonganSkillSeeder::class);

        $this->call(MahasiswaMagangPengajuanSkillSeeder::class);

        $this->call(PreferensiLokasiSeeder::class);

        $this->call([
            TipeKerjaSeeder::class,
            FasilitasSeeder::class,
        ]);

//         // user_skill (Target: 10 data)
//         // Asumsi mahasiswa_id merujuk pada m_mahasiswa (ID 1-10)
//         // Asumsi skill_id merujuk pada m_detail_skill (m_detail_skill_id, ID 1-10)
//         $now   = Carbon::now();
//         $faker = Factory::create('id_ID'); // Inisialisasi Faker untuk data Indonesia

// // 1. Ambil semua ID mahasiswa yang ada
//         $mahasiswaIds = DB::table('m_mahasiswa')->pluck('mahasiswa_id')->toArray();

// // 2. Ambil semua ID skill yang ada dari m_detail_skill
//         $skillIds = DB::table('m_detail_skill')->pluck('skill_id')->toArray();

// // Jika tidak ada mahasiswa atau skill, hentikan seeder untuk tabel ini
//         if (empty($mahasiswaIds)) {
//             $this->command->warn('Tidak ada data mahasiswa ditemukan. Seeder MahasiswaSkill tidak dapat dijalankan.');
//             return;
//         }
//         if (empty($skillIds)) {
//             $this->command->warn('Tidak ada data detail skill ditemukan. Seeder MahasiswaSkill tidak dapat dijalankan.');
//             return;
//         }

//         $mahasiswaSkillData = [];
//         $possibleLevels     = ['Beginner', 'Intermediate', 'Expert'];

// // Distribusi status verifikasi: lebih banyak Pending dan Valid
//         $verificationStatuses = [
//             'Pending', 'Pending', 'Pending', 'Pending',  // 40% Pending
//             'Valid', 'Valid', 'Valid', 'Valid', 'Valid', // 50% Valid
//             'Invalid',                                   // 10% Invalid
//         ];

//         foreach ($mahasiswaIds as $mahasiswaId) {
//             // Setiap mahasiswa akan memiliki antara 2 sampai 5 skill (acak)
//             // Pastikan jumlah skill yang di-assign tidak melebihi jumlah skill yang tersedia
//             $jumlahSkillPerMahasiswa = rand(2, min(5, count($skillIds)));

//             // Ambil sejumlah skill unik secara acak untuk mahasiswa ini
//             $assignedSkillIds = $faker->randomElements($skillIds, $jumlahSkillPerMahasiswa, false);

//             foreach ($assignedSkillIds as $skillId) {
//                 $mahasiswaSkillData[] = [
//                     'mahasiswa_id'      => $mahasiswaId,
//                     'skill_id'          => $skillId,
//                     'level_kompetensi'  => $faker->randomElement($possibleLevels),
//                     'status_verifikasi' => $faker->randomElement($verificationStatuses),
//                     'created_at'        => $now,
//                     // 'updated_at' tidak ada di tabel Anda, jadi tidak perlu diisi
//                 ];
//             }
//         }

// // Hapus data lama jika perlu (opsional, hati-hati jika sudah ada data penting)
// // DB::table('mahasiswa_skill')->delete();

// // Insert data baru secara chunk untuk performa yang lebih baik
//         foreach (array_chunk($mahasiswaSkillData, 100) as $chunk) { // Misal, insert per 100 baris
//             DB::table('mahasiswa_skill')->insert($chunk);
//         }

        // $this->command->info(count($mahasiswaSkillData) . ' data relasi skill mahasiswa telah ditambahkan ke tabel mahasiswa_skill.');

        // Industri IDs akan menjadi 1 s/d 10 secara auto-increment

        // m_detail_lowongan (Target: 10 data)

        // DB::table('mahasiswa_magang')->insert([
        //     [
        //         'mahasiswa_id' => 1,
        //         'lowongan_id'  => 1,
        //         'status'       => 'sedang',
        //         'evaluasi'     => '"InternSync sangat membantu saya menemukan magang yang sesuai dengan minat saya di bidang AI. Rekomendasinya akurat dan prosesnya cepat!"',
        //         'created_at'   => Carbon::now(),
        //         'updated_at'   => Carbon::now(),
        //     ],
        //     [
        //         'mahasiswa_id' => 2,
        //         'lowongan_id'  => 2,
        //         'status'       => 'belum',
        //         'evaluasi'     => '"Saya sangat puas dengan pengalaman magang saya di InternSync. Prosesnya mudah dan banyak pilihan lowongan yang relevan."',
        //         'created_at'   => Carbon::now(),
        //         'updated_at'   => Carbon::now(),
        //     ],
        //     [
        //         'mahasiswa_id' => 3,
        //         'lowongan_id'  => 2,
        //         'status'       => 'belum',
        //         'evaluasi'     => '"InternSync adalah platform yang sangat membantu dalam mencari magang. Saya menemukan banyak lowongan yang sesuai dengan minat saya."',
        //         'created_at'   => Carbon::now(),
        //         'updated_at'   => Carbon::now(),
        //     ],
        // ]);

        // DB::table('t_pengajuan')->insert([
        //     [
        //         'mahasiswa_id'    => 1,
        //         'lowongan_id'     => 1,
        //         'tanggal_mulai'   => '2025-07-01',
        //         'tanggal_selesai' => '2025-09-30',
        //         'status'          => 'belum',
        //         'created_at'      => Carbon::now(),
        //         'updated_at'      => Carbon::now(),
        //     ],
        //     [
        //         'mahasiswa_id'    => 2,
        //         'lowongan_id'     => 2,
        //         'tanggal_mulai'   => '2025-08-01',
        //         'tanggal_selesai' => '2025-10-31',
        //         'status'          => 'belum',
        //         'created_at'      => Carbon::now(),
        //         'updated_at'      => Carbon::now(),
        //     ],
        //     [
        //         'mahasiswa_id'    => 3,
        //         'lowongan_id'     => 1,
        //         'tanggal_mulai'   => '2025-06-15',
        //         'tanggal_selesai' => '2025-09-15',
        //         'status'          => 'belum',
        //         'created_at'      => Carbon::now(),
        //         'updated_at'      => Carbon::now(),
        //     ],
        // ]);
        $kotaNama = DB::table('m_kota')->where('kota_id', 1101)->value('kota_nama');

        DB::table('m_logharian')->insert([
            [
                'mahasiswa_magang_id' => 1,
                'tanggal'             => '2025-05-29',
                'created_at'          => Carbon::now(),
                'updated_at'          => Carbon::now(),
            ],
        ]);

        $logHarian1Id = DB::table('m_logharian')
            ->where('mahasiswa_magang_id', 1)
            ->where('tanggal', '2025-05-29')
            ->value('logHarian_id');

        DB::table('m_logharian_detail')->insert([
            [
                'logHarian_id'     => $logHarian1Id,
                'isi'              => 'mengerjakan project 1',
                'tanggal_kegiatan' => '2025-05-29',
                'lokasi'           => $kotaNama,
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
            ],
            [
                'logharian_id'     => $logHarian1Id,
                'isi'              => 'Membuat dokumentasi kegiatan',
                'tanggal_kegiatan' => '2025-05-29',
                'lokasi'           => $kotaNama,
                'created_at'       => Carbon::now(),
                'updated_at'       => Carbon::now(),
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
