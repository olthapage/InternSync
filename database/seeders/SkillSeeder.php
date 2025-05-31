<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $kategoriSkillData = [
            // Kode, Nama Kategori, Timestamps
            ['kategori_skill_kode' => 'WEBDEV', 'kategori_nama' => 'Pengembangan Web', 'created_at' => $now],
            ['kategori_skill_kode' => 'MOBDEV', 'kategori_nama' => 'Pengembangan Aplikasi Mobile', 'created_at' => $now],
            ['kategori_skill_kode' => 'DATA', 'kategori_nama' => 'Ilmu Data & Analitik', 'created_at' => $now],
            ['kategori_skill_kode' => 'AIML', 'kategori_nama' => 'Kecerdasan Buatan (AI) & Pembelajaran Mesin (ML)', 'created_at' => $now],
            ['kategori_skill_kode' => 'CLOUD', 'kategori_nama' => 'Cloud Computing & DevOps', 'created_at' => $now],
            ['kategori_skill_kode' => 'CYBERSEC', 'kategori_nama' => 'Keamanan Siber (Cybersecurity)', 'created_at' => $now],
            ['kategori_skill_kode' => 'UIUX', 'kategori_nama' => 'Desain UI/UX', 'created_at' => $now],
            ['kategori_skill_kode' => 'GAMEDEV', 'kategori_nama' => 'Pengembangan Game', 'created_at' => $now],
            ['kategori_skill_kode' => 'IOT', 'kategori_nama' => 'Internet of Things (IoT)', 'created_at' => $now],
            ['kategori_skill_kode' => 'DIGIMARKET', 'kategori_nama' => 'Pemasaran Digital (Teknis)', 'created_at' => $now],
            ['kategori_skill_kode' => 'PROJECTMAN', 'kategori_nama' => 'Manajemen Proyek (Perangkat Lunak)', 'created_at' => $now],
            ['kategori_skill_kode' => 'QA', 'kategori_nama' => 'Penjaminan Kualitas (QA) & Pengujian Perangkat Lunak', 'created_at' => $now],
            ['kategori_skill_kode' => 'SOFTSKILLS', 'kategori_nama' => 'Soft Skills Relevan TI', 'created_at' => $now], // Kategori tambahan
        ];

// Hapus data lama jika ingin memulai dari bersih (opsional)
// DB::table('m_kategori_skill')->delete(); // Hati-hati jika sudah ada relasi

// Insert data kategori
        DB::table('m_kategori_skill')->insert($kategoriSkillData);

        $this->command->info(count($kategoriSkillData) . ' kategori skill telah ditambahkan.');

        $kategoriMap = DB::table('m_kategori_skill')->pluck('kategori_skill_id', 'kategori_nama');

        $detailSkillData = [
            // --- Pengembangan Web ---
            ['skill_nama' => 'HTML5', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'CSS3', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'JavaScript (ES6+)', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'TypeScript', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'React.js', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'Angular', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'Vue.js', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'Next.js', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'Node.js', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'Express.js', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'PHP', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'Laravel', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'Symfony', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'Python (Web - Django/Flask)', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'Ruby on Rails', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'Java (Web - Spring Boot)', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'C# (.NET Core Web API)', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'MySQL', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'PostgreSQL', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'MongoDB', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'RESTful API Design', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'GraphQL', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'Bootstrap', 'kategori_nama_ref' => 'Pengembangan Web'],
            ['skill_nama' => 'Tailwind CSS', 'kategori_nama_ref' => 'Pengembangan Web'],

            // --- Pengembangan Aplikasi Mobile ---
            ['skill_nama' => 'Java (Android)', 'kategori_nama_ref' => 'Pengembangan Aplikasi Mobile'],
            ['skill_nama' => 'Kotlin (Android)', 'kategori_nama_ref' => 'Pengembangan Aplikasi Mobile'],
            ['skill_nama' => 'Android SDK', 'kategori_nama_ref' => 'Pengembangan Aplikasi Mobile'],
            ['skill_nama' => 'Jetpack Compose', 'kategori_nama_ref' => 'Pengembangan Aplikasi Mobile'],
            ['skill_nama' => 'Swift (iOS)', 'kategori_nama_ref' => 'Pengembangan Aplikasi Mobile'],
            ['skill_nama' => 'SwiftUI (iOS)', 'kategori_nama_ref' => 'Pengembangan Aplikasi Mobile'],
            ['skill_nama' => 'Flutter (Dart)', 'kategori_nama_ref' => 'Pengembangan Aplikasi Mobile'],
            ['skill_nama' => 'React Native', 'kategori_nama_ref' => 'Pengembangan Aplikasi Mobile'],

            // --- Ilmu Data & Analitik ---
            ['skill_nama' => 'Python (Data - Pandas, NumPy, SciPy)', 'kategori_nama_ref' => 'Ilmu Data & Analitik'],
            ['skill_nama' => 'R (Statistik & Data Mining)', 'kategori_nama_ref' => 'Ilmu Data & Analitik'],
            ['skill_nama' => 'SQL (Analitik Data)', 'kategori_nama_ref' => 'Ilmu Data & Analitik'],
            ['skill_nama' => 'Tableau', 'kategori_nama_ref' => 'Ilmu Data & Analitik'],
            ['skill_nama' => 'Power BI', 'kategori_nama_ref' => 'Ilmu Data & Analitik'],
            ['skill_nama' => 'Visualisasi Data', 'kategori_nama_ref' => 'Ilmu Data & Analitik'],
            ['skill_nama' => 'Pemodelan Statistik', 'kategori_nama_ref' => 'Ilmu Data & Analitik'],
            ['skill_nama' => 'ETL (Extract, Transform, Load)', 'kategori_nama_ref' => 'Ilmu Data & Analitik'],

            // --- AI & Machine Learning ---
            ['skill_nama' => 'Python (AI/ML - Scikit-learn, TensorFlow, PyTorch, Keras)', 'kategori_nama_ref' => 'Kecerdasan Buatan (AI) & Pembelajaran Mesin (ML)'],
            ['skill_nama' => 'Natural Language Processing (NLP)', 'kategori_nama_ref' => 'Kecerdasan Buatan (AI) & Pembelajaran Mesin (ML)'],
            ['skill_nama' => 'Computer Vision', 'kategori_nama_ref' => 'Kecerdasan Buatan (AI) & Pembelajaran Mesin (ML)'],
            ['skill_nama' => 'Deep Learning', 'kategori_nama_ref' => 'Kecerdasan Buatan (AI) & Pembelajaran Mesin (ML)'],

            // --- Cloud Computing & DevOps ---
            ['skill_nama' => 'Amazon Web Services (AWS)', 'kategori_nama_ref' => 'Cloud Computing & DevOps'],
            ['skill_nama' => 'Microsoft Azure', 'kategori_nama_ref' => 'Cloud Computing & DevOps'],
            ['skill_nama' => 'Google Cloud Platform (GCP)', 'kategori_nama_ref' => 'Cloud Computing & DevOps'],
            ['skill_nama' => 'Docker', 'kategori_nama_ref' => 'Cloud Computing & DevOps'],
            ['skill_nama' => 'Kubernetes', 'kategori_nama_ref' => 'Cloud Computing & DevOps'],
            ['skill_nama' => 'CI/CD (Jenkins, GitLab CI)', 'kategori_nama_ref' => 'Cloud Computing & DevOps'],
            ['skill_nama' => 'Git & Version Control', 'kategori_nama_ref' => 'Cloud Computing & DevOps'],
            ['skill_nama' => 'Terraform', 'kategori_nama_ref' => 'Cloud Computing & DevOps'],
            ['skill_nama' => 'Ansible', 'kategori_nama_ref' => 'Cloud Computing & DevOps'],

            // --- Keamanan Siber ---
            ['skill_nama' => 'Network Security', 'kategori_nama_ref' => 'Keamanan Siber (Cybersecurity)'],
            ['skill_nama' => 'Penetration Testing', 'kategori_nama_ref' => 'Keamanan Siber (Cybersecurity)'],
            ['skill_nama' => 'Ethical Hacking', 'kategori_nama_ref' => 'Keamanan Siber (Cybersecurity)'],
            ['skill_nama' => 'Cryptography', 'kategori_nama_ref' => 'Keamanan Siber (Cybersecurity)'],

            // --- Desain UI/UX ---
            ['skill_nama' => 'Figma', 'kategori_nama_ref' => 'Desain UI/UX'],
            ['skill_nama' => 'Adobe XD', 'kategori_nama_ref' => 'Desain UI/UX'],
            ['skill_nama' => 'Sketch', 'kategori_nama_ref' => 'Desain UI/UX'],
            ['skill_nama' => 'User Research', 'kategori_nama_ref' => 'Desain UI/UX'],
            ['skill_nama' => 'Wireframing & Prototyping', 'kategori_nama_ref' => 'Desain UI/UX'],

            // --- Pengembangan Game ---
            ['skill_nama' => 'Unity (C#)', 'kategori_nama_ref' => 'Pengembangan Game'],
            ['skill_nama' => 'Unreal Engine (C++/Blueprints)', 'kategori_nama_ref' => 'Pengembangan Game'],

            // --- Internet of Things (IoT) ---
            ['skill_nama' => 'Arduino Programming', 'kategori_nama_ref' => 'Internet of Things (IoT)'],
            ['skill_nama' => 'Raspberry Pi', 'kategori_nama_ref' => 'Internet of Things (IoT)'],
            ['skill_nama' => 'MQTT', 'kategori_nama_ref' => 'Internet of Things (IoT)'],

            // --- Pemasaran Digital (Teknis) ---
            ['skill_nama' => 'SEO (Search Engine Optimization)', 'kategori_nama_ref' => 'Pemasaran Digital (Teknis)'],
            ['skill_nama' => 'SEM (Search Engine Marketing)', 'kategori_nama_ref' => 'Pemasaran Digital (Teknis)'],
            ['skill_nama' => 'Google Analytics', 'kategori_nama_ref' => 'Pemasaran Digital (Teknis)'],

            // --- Manajemen Proyek (Perangkat Lunak) ---
            ['skill_nama' => 'Agile Methodologies', 'kategori_nama_ref' => 'Manajemen Proyek (Perangkat Lunak)'],
            ['skill_nama' => 'Scrum Framework', 'kategori_nama_ref' => 'Manajemen Proyek (Perangkat Lunak)'],
            ['skill_nama' => 'JIRA / Trello', 'kategori_nama_ref' => 'Manajemen Proyek (Perangkat Lunak)'],

            // --- QA & Pengujian Perangkat Lunak ---
            ['skill_nama' => 'Manual Testing', 'kategori_nama_ref' => 'Penjaminan Kualitas (QA) & Pengujian Perangkat Lunak'],
            ['skill_nama' => 'Automated Testing (Selenium/Cypress)', 'kategori_nama_ref' => 'Penjaminan Kualitas (QA) & Pengujian Perangkat Lunak'],
            ['skill_nama' => 'Test Planning & Design', 'kategori_nama_ref' => 'Penjaminan Kualitas (QA) & Pengujian Perangkat Lunak'],

            // --- Soft Skills Relevan TI ---
            ['skill_nama' => 'Komunikasi Teknis', 'kategori_nama_ref' => 'Soft Skills Relevan TI'],
            ['skill_nama' => 'Problem Solving (Pemecahan Masalah)', 'kategori_nama_ref' => 'Soft Skills Relevan TI'],
            ['skill_nama' => 'Kerja Sama Tim (Teamwork)', 'kategori_nama_ref' => 'Soft Skills Relevan TI'],
            ['skill_nama' => 'Adaptabilitas', 'kategori_nama_ref' => 'Soft Skills Relevan TI'],
            ['skill_nama' => 'Manajemen Waktu', 'kategori_nama_ref' => 'Soft Skills Relevan TI'],
        ];

        $preparedDetailSkillData = [];
        foreach ($detailSkillData as $skill) {
            if (isset($kategoriMap[$skill['kategori_nama_ref']])) {
                $preparedDetailSkillData[] = [
                    'skill_nama'        => $skill['skill_nama'],
                    'kategori_skill_id' => $kategoriMap[$skill['kategori_nama_ref']],
                    'created_at'        => $now,
                ];
            } else {
                // Handle jika kategori_nama_ref tidak ditemukan di $kategoriMap (seharusnya tidak terjadi jika data benar)
                $this->command->warn("Kategori '{$skill['kategori_nama_ref']}' untuk skill '{$skill['skill_nama']}' tidak ditemukan. Skill ini dilewati.");
            }
        }


        DB::table('m_detail_skill')->insert($preparedDetailSkillData);

        $this->command->info(count($preparedDetailSkillData) . ' detail skill telah ditambahkan.');
    }
}
