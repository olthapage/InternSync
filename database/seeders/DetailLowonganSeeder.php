<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailLowonganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Mengambil data master untuk mapping
        $industriMap = DB::table('m_industri')->pluck('industri_id', 'industri_nama')->toArray();
        $skillMap = DB::table('m_kategori_skill')->pluck('kategori_skill_id', 'kategori_nama')->toArray();
        // Mengambil data lokasi untuk lowongan dengan lokasi spesifik
        $kotaMap = DB::table('m_kota')->pluck('kota_id', 'kota_nama')->toArray();

        // Validasi data master
        if (empty($industriMap) || empty($skillMap) || empty($kotaMap)) {
            $this->command->error('Pastikan data Industri, Kategori Skill, dan Kota sudah ada. Jalankan seeder yang sesuai terlebih dahulu.');
            return;
        }

        // Menentukan periode tanggal sesuai permintaan
        $pendaftaranMulai = $now->copy();
        $pendaftaranSelesai = $now->copy()->addMonth();
        $pelaksanaanMulai = $now->copy();
        $pelaksanaanSelesai = Carbon::create(2026, 12, 31);

        // Daftar lowongan dengan data yang lebih lengkap
        $lowonganList = [
            [
                'judul' => 'Backend Developer (PHP Laravel)',
                'deskripsi' => 'Merancang, membangun, dan memelihara API serta logika sisi server. Berpengalaman dengan database MySQL/PostgreSQL.',
                'industri_nama' => 'PT Cipta Solusi Digital',
                'skill_nama' => 'Pengembangan Web',
                'slot' => 3,
                'upah' => 3500000,
                'use_specific_location' => false,
            ],
            [
                'judul' => 'Frontend Developer (React.js)',
                'deskripsi' => 'Mengembangkan antarmuka pengguna yang interaktif dan responsif. Menguasai state management.',
                'industri_nama' => 'PT Aplikasi Karya Bangsa',
                'skill_nama' => 'Pengembangan Web',
                'slot' => 2,
                'upah' => 3200000,
                'use_specific_location' => true,
                'kota_nama' => 'Jakarta Selatan', // Nama kota harus ada di tabel m_kota
                'alamat_spesifik' => 'Gedung Gojek, Pasaraya Blok M, Jl. Iskandarsyah II No.7',
            ],
            [
                'judul' => 'Android Developer (Kotlin)',
                'deskripsi' => 'Mengembangkan dan memelihara aplikasi mobile banking untuk platform Android. Fokus pada keamanan dan performa.',
                'industri_nama' => 'Bank Digital Sejahtera',
                'skill_nama' => 'Pengembangan Aplikasi Mobile',
                'slot' => 2,
                'upah' => 4000000,
                'use_specific_location' => false,
            ],
            [
                'judul' => 'IT Support Specialist',
                'deskripsi' => 'Memberikan dukungan teknis terkait hardware, software, dan jaringan untuk seluruh staf.',
                'industri_nama' => 'Rumah Sakit Harapan Medika',
                'skill_nama' => 'Cloud Computing & DevOps',
                'slot' => 2,
                'upah' => 2500000,
                'use_specific_location' => false,
            ],
            [
                'judul' => 'UI/UX Designer',
                'deskripsi' => 'Melakukan riset pengguna, membuat wireframe, mockup, dan prototipe. Wajib melampirkan portofolio.',
                'industri_nama' => 'PT Cipta Solusi Digital',
                'skill_nama' => 'Desain UI/UX',
                'slot' => 2,
                'upah' => 2800000,
                'use_specific_location' => true,
                'kota_nama' => 'Bandung',
                'alamat_spesifik' => 'Jl. Dipati Ukur No.35, Lebakgede, Kecamatan Coblong',
            ],
            [
                'judul' => 'Cyber Security Analyst',
                'deskripsi' => 'Memonitor keamanan jaringan, menganalisis potensi ancaman, dan mengelola insiden keamanan.',
                'industri_nama' => 'Bank Digital Sejahtera',
                'skill_nama' => 'Keamanan Siber (Cybersecurity)',
                'slot' => 1,
                'upah' => 4500000,
                'use_specific_location' => false,
            ],
        ];

        $detailLowonganData = [];

        foreach ($lowonganList as $lowongan) {
            $industriId = $industriMap[$lowongan['industri_nama']] ?? null;
            $skillId = $skillMap[$lowongan['skill_nama']] ?? null;

            if (!$industriId || !$skillId) {
                $this->command->warn("Melewatkan lowongan '{$lowongan['judul']}' karena industri atau skill tidak ditemukan.");
                continue;
            }

            $data = [
                'judul_lowongan'    => $lowongan['judul'],
                'deskripsi'         => $lowongan['deskripsi'],
                'industri_id'       => $industriId,
                'kategori_skill_id' => $skillId,
                'slot'              => $lowongan['slot'],
                'upah'              => $lowongan['upah'],
                // Mengatur tanggal sesuai permintaan
                'pendaftaran_tanggal_mulai' => $pendaftaranMulai->toDateString(),
                'pendaftaran_tanggal_selesai' => $pendaftaranSelesai->toDateString(),
                'tanggal_mulai'     => $pelaksanaanMulai->toDateString(),
                'tanggal_selesai'   => $pelaksanaanSelesai->toDateString(),
                'use_specific_location' => $lowongan['use_specific_location'],
                // Default null untuk lokasi
                'lokasi_kota_id' => null,
                'lokasi_alamat_lengkap' => null,
                'created_at'        => $now,
            ];

            // Jika lowongan memiliki lokasi spesifik, tambahkan datanya
            if ($lowongan['use_specific_location']) {
                $kotaId = $kotaMap[$lowongan['kota_nama']] ?? null;
                if ($kotaId) {
                    $data['lokasi_kota_id'] = $kotaId;
                    // Asumsi provinsi_id bisa di-lookup dari kota jika ada relasinya di model.
                    // Jika tidak, Anda perlu menambahkan provinsi_id secara manual.
                    $data['lokasi_alamat_lengkap'] = $lowongan['alamat_spesifik'];
                }
            }

            $detailLowonganData[] = $data;
        }

        // Hapus data lama jika perlu, agar tidak duplikat saat seeder dijalankan ulang
        // DB::table('m_detail_lowongan')->delete();

        // Insert data baru
        DB::table('m_detail_lowongan')->insert($detailLowonganData);

        $this->command->info(count($detailLowonganData) . ' data lowongan IT lengkap telah ditambahkan.');
    }
}
