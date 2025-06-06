<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipeKerjaModel;

class TipeKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipeKerja = [
            ['nama_tipe_kerja' => 'On-Site'],
            ['nama_tipe_kerja' => 'Hybrid'],
            ['nama_tipe_kerja' => 'Work From Home (WFH)'],
        ];

        foreach ($tipeKerja as $item) {
            // updateOrCreate akan membuat data jika belum ada, atau mengabaikannya jika sudah ada.
            // Ini aman untuk dijalankan berkali-kali.
            TipeKerjaModel::updateOrCreate(
                ['nama_tipe_kerja' => $item['nama_tipe_kerja']],
                $item
            );
        }
    }
}

