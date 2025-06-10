<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LowonganSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeder ini akan menghubungkan lowongan dengan skill yang relevan.
     * PASTIKAN DetailLowonganSeeder dan SkillSeeder sudah dijalankan sebelumnya.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Ambil data lowongan dan skill yang ada untuk membuat peta referensi.
        // Peta ini mengubah nama yang mudah dibaca menjadi ID yang dibutuhkan database.
        $lowonganMap = DB::table('m_detail_lowongan')->pluck('lowongan_id', 'judul_lowongan')->toArray();
        $skillMap = DB::table('m_detail_skill')->pluck('skill_id', 'skill_nama')->toArray();

        // Validasi: Hentikan seeder jika data master tidak ada.
        if (empty($lowonganMap)) {
            $this->command->error('Data lowongan tidak ditemukan. Jalankan DetailLowonganSeeder terlebih dahulu.');
            return;
        }
        if (empty($skillMap)) {
            $this->command->error('Data detail skill tidak ditemukan. Jalankan SkillSeeder terlebih dahulu.');
            return;
        }

        // 2. Definisikan hubungan antara lowongan dan skill secara hardcoded dan jelas.
        // Setiap judul lowongan dipetakan ke array berisi nama-nama skill yang dibutuhkan.
        $linkData = [
            [
                'judul_lowongan' => 'Backend Developer (PHP Laravel)',
                'required_skills' => ['PHP', 'Laravel', 'MySQL', 'RESTful API Design', 'Git & Version Control']
            ],
            [
                'judul_lowongan' => 'Frontend Developer (React.js)',
                'required_skills' => ['HTML5', 'CSS3', 'JavaScript (ES6+)', 'React.js', 'Tailwind CSS', 'RESTful API Design']
            ],
            [
                'judul_lowongan' => 'Android Developer (Kotlin)',
                'required_skills' => ['Kotlin (Android)', 'Android SDK', 'Jetpack Compose', 'RESTful API Design', 'Git & Version Control']
            ],
            [
                'judul_lowongan' => 'IT Support Specialist',
                'required_skills' => ['Network Security', 'Problem Solving (Pemecahan Masalah)', 'Komunikasi Teknis', 'Manajemen Waktu']
            ],
            [
                'judul_lowongan' => 'Data Analyst',
                'required_skills' => ['SQL (Analitik Data)', 'Tableau', 'Power BI', 'Python (Data - Pandas, NumPy, SciPy)', 'Visualisasi Data']
            ],
            [
                'judul_lowongan' => 'DevOps Engineer',
                'required_skills' => ['Amazon Web Services (AWS)', 'Docker', 'Kubernetes', 'CI/CD (Jenkins, GitLab CI)', 'Terraform', 'Git & Version Control']
            ],
            [
                'judul_lowongan' => 'UI/UX Designer',
                'required_skills' => ['Figma', 'User Research', 'Wireframing & Prototyping', 'Kerja Sama Tim (Teamwork)']
            ],
            [
                'judul_lowongan' => 'Cyber Security Analyst',
                'required_skills' => ['Network Security', 'Penetration Testing', 'Ethical Hacking', 'Cryptography', 'Problem Solving (Pemecahan Masalah)']
            ],
            [
                'judul_lowongan' => 'Full-Stack Developer (MERN)',
                'required_skills' => ['JavaScript (ES6+)', 'React.js', 'Node.js', 'Express.js', 'MongoDB', 'RESTful API Design']
            ],
            [
                'judul_lowongan' => 'Data Engineer',
                'required_skills' => ['Python (Data - Pandas, NumPy, SciPy)', 'SQL (Analitik Data)', 'ETL (Extract, Transform, Load)', 'Amazon Web Services (AWS)', 'PostgreSQL']
            ],
            [
                'judul_lowongan' => 'IT Project Manager',
                'required_skills' => ['Agile Methodologies', 'Scrum Framework', 'JIRA / Trello', 'Komunikasi Teknis', 'Manajemen Waktu']
            ],
        ];

        $lowonganSkillData = [];

        // 3. Proses data yang sudah didefinisikan untuk di-insert ke database.
        foreach ($linkData as $link) {
            $judulLowongan = $link['judul_lowongan'];

            if (!isset($lowonganMap[$judulLowongan])) {
                $this->command->warn("Lowongan dengan judul '{$judulLowongan}' tidak ditemukan. Hubungan skill untuk lowongan ini dilewati.");
                continue;
            }
            $lowonganId = $lowonganMap[$judulLowongan];

            foreach ($link['required_skills'] as $skillName) {
                if (!isset($skillMap[$skillName])) {
                    $this->command->warn("Skill dengan nama '{$skillName}' tidak ditemukan. Hubungan ini dilewati.");
                    continue;
                }
                $skillId = $skillMap[$skillName];

                $lowonganSkillData[] = [
                    'lowongan_id' => $lowonganId,
                    'skill_id'    => $skillId,
                    'created_at'  => $now,
                ];
            }
        }

        // Hapus data lama untuk menghindari duplikat jika seeder dijalankan ulang
        // DB::table('lowongan_skill')->delete();

        // Insert data baru ke tabel pivot
        DB::table('lowongan_skill')->insert($lowonganSkillData);

        $this->command->info(count($lowonganSkillData) . ' data hubungan lowongan-skill telah ditambahkan.');
    }
}
