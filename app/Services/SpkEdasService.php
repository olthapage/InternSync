<?php

namespace App\Services;

use App\Models\DetailLowonganModel;
use App\Models\MahasiswaModel; // Mungkin tidak perlu di-use langsung jika diakses via relasi
use App\Models\PengajuanModel; // Jika $pendaftar adalah koleksi PengajuanModel
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SpkEdasService
{
    // Anda bisa mendefinisikan skala skor di sini agar mudah diubah
    private $matchingScores = [
        'Beginner' => [ // Industri Butuh Beginner
            'Beginner'     => 100, // Mahasiswa Beginner (Perfect Match)
            'Intermediate' => 70,  // Mahasiswa Intermediate (Overqualified, still good)
            'Expert'       => 40,  // Mahasiswa Expert (Sangat Overqualified, mungkin kurang cocok/bosan)
        ],
        'Intermediate' => [ // Industri Butuh Intermediate
            'Beginner'     => 40,  // Mahasiswa Beginner (Underqualified)
            'Intermediate' => 100, // Mahasiswa Intermediate (Perfect Match)
            'Expert'       => 70,  // Mahasiswa Expert (Slightly Overqualified, good)
        ],
        'Expert' => [ // Industri Butuh Expert
            'Beginner'     => 10,  // Mahasiswa Beginner (Significantly Underqualified)
            'Intermediate' => 70,  // Mahasiswa Intermediate (Underqualified, but potential)
            'Expert'       => 100, // Mahasiswa Expert (Perfect Match)
        ],
    ];

    public function calculateRekomendasi(DetailLowonganModel $lowongan, Collection $pendaftar, array $inputCriteriaWeights): array
    {
        if ($pendaftar->isEmpty()) {
            return ['rankedMahasiswa' => collect(), 'criteriaView' => [], 'message' => 'Tidak ada pendaftar pada lowongan ini.'];
        }

        // 1. Menentukan Kriteria (Cj) dan Bobot Awal (Wj)
        $criteria = [];
        $totalInitialWeight = 0;

        foreach ($lowongan->lowonganSkill as $reqSkill) {
            // Pastikan skill_id ada dan bobot dari input ada
            if (isset($reqSkill->skill_id) && isset($inputCriteriaWeights['bobot_skill'][$reqSkill->skill_id])) {
                $bobot = floatval($inputCriteriaWeights['bobot_skill'][$reqSkill->skill_id]);
                if ($bobot > 0) {
                    $criteria[] = [
                        'id'             => 'skill_' . $reqSkill->skill_id,
                        'nama'           => optional($reqSkill->skill)->skill_nama ?? 'Skill Tdk Ditemukan',
                        'tipe'           => 'skill',
                        'skill_id'       => $reqSkill->skill_id,
                        'bobot_awal'     => $bobot,
                        'level_lowongan' => $reqSkill->level_kompetensi // Level yang dibutuhkan lowongan
                    ];
                    $totalInitialWeight += $bobot;
                }
            }
        }

        $gunakanIpk = $inputCriteriaWeights['gunakan_ipk'] ?? false;
        if ($gunakanIpk) {
            $bobotIpk = floatval($inputCriteriaWeights['bobot_ipk'] ?? 0);
            if ($bobotIpk > 0) {
                $criteria[] = [
                    'id'         => 'ipk',
                    'nama'       => 'Nilai Akademik (IPK)',
                    'tipe'       => 'ipk',
                    'bobot_awal' => $bobotIpk,
                ];
                $totalInitialWeight += $bobotIpk;
            }
        }

        if (empty($criteria) || $totalInitialWeight <= 0) { // Cek jika total bobot > 0
             return ['rankedMahasiswa' => collect(), 'criteriaView' => $criteria, 'message' => 'Tidak ada kriteria yang diberi bobot atau total bobot adalah nol. Silakan tentukan bobot kriteria di form.'];
        }

        foreach ($criteria as $key => $c) {
            $criteria[$key]['bobot_normalisasi'] = $c['bobot_awal'] / $totalInitialWeight;
        }

        $criteriaView = $criteria;

        // 3. Matriks Keputusan (Xij)
        $decisionMatrix = [];
        $mahasiswaDetails = [];
        $mahasiswaLevelsPerSkill = [];

        foreach ($pendaftar as $pengajuan) { // Asumsi $pendaftar adalah koleksi PengajuanModel
            $mahasiswa = $pengajuan->mahasiswa; // Akses objek mahasiswa dari pengajuan
            if (!$mahasiswa) continue;

            $mahasiswaId = $mahasiswa->mahasiswa_id;
            $mahasiswaDetails[$mahasiswaId] = $mahasiswa;
            $mahasiswaLevelsPerSkill[$mahasiswaId] = [];

            foreach ($criteria as $c) {
                $score = 0; // Default score
                if ($c['tipe'] === 'skill') {
                    $mahasiswaSkill = $mahasiswa->skills->where('skill_id', $c['skill_id'])->where('status_verifikasi', 'Valid')->first(); // Ambil skill mahasiswa yang valid
                    $studentLevelDisplay = 'Tidak Ada / Belum Valid';

                    if ($mahasiswaSkill) {
                        $targetLevel = $c['level_lowongan'];
                        $studentActualLevel = $mahasiswaSkill->level_kompetensi;
                        $studentLevelDisplay = $studentActualLevel;

                        // Logika scoring pencocokan dinamis
                        if (isset($this->matchingScores[$targetLevel][$studentActualLevel])) {
                            $score = $this->matchingScores[$targetLevel][$studentActualLevel];
                        } else {
                            // Fallback jika kombinasi level tidak terdefinisi di $matchingScores
                            // Mungkin jika level student tidak ada di ['Beginner', 'Intermediate', 'Expert']
                            $score = 0;
                        }
                    }
                    $mahasiswaLevelsPerSkill[$mahasiswaId][$c['id']] = $studentLevelDisplay;
                } elseif ($c['tipe'] === 'ipk') {
                    $score = floatval($mahasiswa->ipk);
                    // Normalisasi IPK ke skala 0-100 jika perlu, atau biarkan bobot yang menyeimbangkan
                    // Contoh: $score = ($mahasiswa->ipk / 4) * 100; (jika skala IPK 0-4)
                    // Untuk EDAS, nilai aktual bisa dipakai langsung, bobot yang akan berperan.
                }
                $decisionMatrix[$mahasiswaId][$c['id']] = $score;
            }
        }

        if (empty($decisionMatrix)) {
             return ['rankedMahasiswa' => collect(), 'criteriaView' => $criteriaView, 'message' => 'Tidak ada data pendaftar yang memenuhi syarat untuk diproses SPK.'];
        }

        // 4. Solusi Rata-rata (AVj)
        $averageSolution = [];
        foreach ($criteria as $c) {
            $sumScores = 0;
            $countAlternativesForCriterion = 0;
            foreach ($decisionMatrix as $mahasiswaId => $scores) {
                if (isset($scores[$c['id']])) { // Pastikan skor ada untuk alternatif ini pada kriteria ini
                    $sumScores += $scores[$c['id']];
                    $countAlternativesForCriterion++;
                }
            }
            $averageSolution[$c['id']] = $countAlternativesForCriterion > 0 ? $sumScores / $countAlternativesForCriterion : 0;
        }

        // 5. Jarak Positif (PDA) dan Jarak Negatif (NDA) dari Rata-rata
        $pdaMatrix = [];
        $ndaMatrix = [];
        foreach ($decisionMatrix as $mahasiswaId => $scores) {
            foreach ($criteria as $c) {
                $x_ij = $scores[$c['id']];
                $av_j = $averageSolution[$c['id']];

                // Semua kriteria diasumsikan beneficial (semakin besar semakin baik)
                if ($av_j > 0) {
                    $pdaMatrix[$mahasiswaId][$c['id']] = max(0, ($x_ij - $av_j) / $av_j);
                    $ndaMatrix[$mahasiswaId][$c['id']] = max(0, ($av_j - $x_ij) / $av_j);
                } else { // av_j adalah 0 atau negatif (seharusnya 0 jika skor minimal 0)
                    $pdaMatrix[$mahasiswaId][$c['id']] = ($x_ij > 0) ? 1 : 0; // Jika punya nilai saat avg 0, itu signifikan positif
                    $ndaMatrix[$mahasiswaId][$c['id']] = 0; // Tidak ada jarak negatif jika avg sudah 0
                }
            }
        }

        // 6. Jumlah Terbobot PDA (SPi) dan NDA (SNi)
        // ... (logika SP, SN tetap sama, menggunakan $c['bobot_normalisasi']) ...
        $spValues = [];
        $snValues = [];
        foreach ($decisionMatrix as $mahasiswaId => $scores) {
            $sp_i = 0;
            $sn_i = 0;
            foreach ($criteria as $c) {
                $w_j = $c['bobot_normalisasi'];
                $sp_i += $w_j * ($pdaMatrix[$mahasiswaId][$c['id']] ?? 0);
                $sn_i += $w_j * ($ndaMatrix[$mahasiswaId][$c['id']] ?? 0);
            }
            $spValues[$mahasiswaId] = $sp_i;
            $snValues[$mahasiswaId] = $sn_i;
        }

        // 7. Normalisasi SPi dan SNi (NSPi dan NSNi)
        // ... (logika NSP, NSN tetap sama) ...
        $maxSP = !empty($spValues) ? max($spValues) : 0;
        $maxSN = !empty($snValues) ? max($snValues) : 0;

        $nspValues = [];
        $nsnValues = [];
        foreach ($mahasiswaDetails as $mahasiswaId => $mahasiswa) {
            $nspValues[$mahasiswaId] = ($maxSP > 0) ? ($spValues[$mahasiswaId] / $maxSP) : 0;
            $nsnValues[$mahasiswaId] = ($maxSN > 0) ? (1 - ($snValues[$mahasiswaId] / $maxSN)) : 1; // jika maxSN 0, berarti tidak ada nilai negatif, jadi NSN = 1
        }

        // 8. Skor Penilaian (ASi)
        // ... (logika AS tetap sama) ...
        $appraisalScores = [];
        foreach ($mahasiswaDetails as $mahasiswaId => $mahasiswa) {
            $appraisalScores[$mahasiswaId] = ($nspValues[$mahasiswaId] + $nsnValues[$mahasiswaId]) / 2;
        }

        // 9. Peringkat Alternatif
        // ... (logika peringkat tetap sama) ...
        $rankedMahasiswa = collect();
        foreach ($appraisalScores as $mahasiswaId => $as_score) {
            $pendaftarPengajuan = $pendaftar->firstWhere('mahasiswa_id', $mahasiswaId); // Ambil objek pengajuan yang sesuai
            $rankedMahasiswa->push([
                'mahasiswa'      => $mahasiswaDetails[$mahasiswaId],
                'pengajuan_id'   => $pendaftarPengajuan->pengajuan_id,
                'skor_akhir_as'  => $as_score,
                'nilai_kriteria' => $decisionMatrix[$mahasiswaId],
                'level_mahasiswa_per_skill' => $mahasiswaLevelsPerSkill[$mahasiswaId] ?? [],
            ]);
        }
        $rankedMahasiswa = $rankedMahasiswa->sortByDesc('skor_akhir_as')->values(); // values() untuk reset keys


        return ['rankedMahasiswa' => $rankedMahasiswa, 'criteriaView' => $criteriaView];
    }
}
