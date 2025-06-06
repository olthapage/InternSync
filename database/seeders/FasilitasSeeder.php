<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FasilitasModel;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fasilitas = [
            ['nama_fasilitas' => 'Disediakan PC / Laptop untuk Bekerja'],
            ['nama_fasilitas' => 'Pendampingan Mentor Selama Magang'],
            ['nama_fasilitas' => 'Ruang Kerja yang Nyaman dan Kondusif'],
            ['nama_fasilitas' => 'Akses Wi-Fi Cepat dan Stabil'],
            ['nama_fasilitas' => 'Keterlibatan dalam Proyek Nyata (Real Project)'],
        ];

        foreach ($fasilitas as $item) {
            FasilitasModel::updateOrCreate(
                ['nama_fasilitas' => $item['nama_fasilitas']],
                $item
            );
        }
    }
}
