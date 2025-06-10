<?php
namespace App\Services;

use App\Models\DetailLowonganModel;
use Illuminate\Support\Collection;

class SpkEdasService
{
    // Skor untuk level skill (Benefit: lebih tinggi lebih baik, disesuaikan dengan kebutuhan lowongan)
    private $matchingScores = [
        'Beginner'     => ['Beginner' => 40, 'Intermediate' => 70, 'Expert' => 100], // Jika butuh Beginner, mahasiswa Beginner paling cocok
        'Intermediate' => ['Beginner' => 40, 'Intermediate' => 70, 'Expert' => 100],
        'Expert'       => ['Beginner' => 40, 'Intermediate' => 70, 'Expert' => 100],
    ];

    // Skor untuk kriteria kategorikal (Benefit: skor lebih tinggi lebih baik)
    private $organisasiScores = ['tidak_ikut' => 30, 'aktif' => 70, 'sangat_aktif' => 100];
    private $lombaScores      = ['tidak_ikut' => 30, 'aktif' => 70, 'sangat_aktif' => 100];

    // Skor untuk Kasus (Cost: 'tidak_ada' lebih baik, jadi diberi skor tinggi untuk EDAS)
    private $kasusScores = ['tidak_ada' => 100, 'ada' => 10];

    public function getCriteriaView(DetailLowonganModel $lowongan, array $inputCriteriaWeights): array
    {
        $criteria = [];
        // Kriteria Skill
        foreach ($lowongan->lowonganSkill as $reqSkill) {
            if (isset($reqSkill->skill_id) && isset($inputCriteriaWeights['bobot_skill'][$reqSkill->skill_id])) {
                $bobot = floatval($inputCriteriaWeights['bobot_skill'][$reqSkill->skill_id]);
                if ($bobot > 0) {
                    $criteria[] = ['id' => 'skill_' . $reqSkill->skill_id, 'nama' => optional($reqSkill->skill)->skill_nama ?? 'N/A', 'tipe' => 'skill', 'bobot_awal' => $bobot];
                }
            }
        }
        // Kriteria Tambahan
        $additionalCriteriaConfig = [
            'ipk'        => ['nama' => 'Nilai Akademik (IPK)'],
            'organisasi' => ['nama' => 'Aktivitas Organisasi'],
            'lomba'      => ['nama' => 'Aktivitas Lomba'],
            'skor_ais'   => ['nama' => 'Skor AIS (Cost)'],
            'kasus'      => ['nama' => 'Status Kasus'],
        ];
        foreach ($additionalCriteriaConfig as $key => $config) {
            if ($inputCriteriaWeights['gunakan_' . $key] ?? false) {
                $bobot = floatval($inputCriteriaWeights['bobot_' . $key] ?? 0);
                if ($bobot > 0) {
                    $criteria[] = ['id' => $key, 'nama' => $config['nama'], 'tipe' => $key, 'bobot_awal' => $bobot];
                }
            }
        }
        return $criteria;
    }

    /**
     * Metode utama untuk menghitung rekomendasi.
     */
    public function calculateRekomendasi(DetailLowonganModel $lowongan, Collection $pendaftar, array $inputCriteriaWeights): array
    {
        $calculationData = $this->performEdasCalculations($lowongan, $pendaftar, $inputCriteriaWeights, false); // false untuk tidak mengembalikan semua langkah

        if (isset($calculationData['error_message'])) {
            return ['rankedMahasiswa' => collect(), 'criteriaView' => $calculationData['criteriaView'] ?? [], 'message' => $calculationData['error_message']];
        }

        return [
            'rankedMahasiswa' => $calculationData['rankedMahasiswa'] ?? collect(),
            'criteriaView'    => $calculationData['criteriaView'] ?? [],
        ];
    }

    /**
     * Method untuk mendapatkan semua langkah perhitungan EDAS.
     */
    public function getEdasCalculationSteps(DetailLowonganModel $lowongan, Collection $pendaftar, array $inputCriteriaWeights): array
    {
        return $this->performEdasCalculations($lowongan, $pendaftar, $inputCriteriaWeights, true); // true untuk mengembalikan semua langkah
    }

    /**
     * Inti dari kalkulasi EDAS, bisa mengembalikan hasil akhir atau semua langkah.
     */
    private function performEdasCalculations(DetailLowonganModel $lowongan, Collection $pendaftar, array $inputCriteriaWeights, bool $returnAllSteps = false): array
    {
        if ($pendaftar->isEmpty()) {
            return ['error_message' => 'Tidak ada pendaftar yang memenuhi syarat untuk dievaluasi.'];
        }

        // 1. Menentukan Kriteria (Cj) dan Bobot Awal (Wj)
        $criteria           = [];
        $totalInitialWeight = 0.0;

        foreach ($lowongan->lowonganSkill as $reqSkill) {
            if (isset($reqSkill->skill_id) && isset($inputCriteriaWeights['bobot_skill'][$reqSkill->skill_id])) {
                $bobot = floatval($inputCriteriaWeights['bobot_skill'][$reqSkill->skill_id]);
                if ($bobot > 0) {
                    $criteria[] = [
                        'id'             => 'skill_' . $reqSkill->skill_id,
                        'nama'           => optional($reqSkill->skill)->skill_nama ?? 'Skill Tdk Ditemukan',
                        'tipe'           => 'skill',
                        'skill_id'       => $reqSkill->skill_id,
                        'bobot_awal'     => $bobot,
                        'level_lowongan' => $reqSkill->level_kompetensi,
                        'jenis_kriteria' => 'benefit',
                    ];
                    $totalInitialWeight += $bobot;
                }
            }
        }
        $additionalCriteriaConfig = [
            'ipk'        => ['nama' => 'Nilai Akademik (IPK)', 'jenis' => 'benefit', 'scores_map' => null],
            'organisasi' => ['nama' => 'Aktivitas Organisasi', 'jenis' => 'benefit', 'scores_map' => $this->organisasiScores],
            'lomba'      => ['nama' => 'Aktivitas Lomba', 'jenis' => 'benefit', 'scores_map' => $this->lombaScores],
            'skor_ais'   => ['nama' => 'Skor AIS (Rendah Lebih Baik)', 'jenis' => 'cost', 'scores_map' => null],
            'kasus'      => ['nama' => 'Status Kasus Pelanggaran', 'jenis' => 'benefit', 'scores_map' => $this->kasusScores], // Sudah diubah jadi benefit di scores_map
        ];
        foreach ($additionalCriteriaConfig as $key => $config) {
            if ($inputCriteriaWeights['gunakan_' . $key] ?? false) {
                $bobot = floatval($inputCriteriaWeights['bobot_' . $key] ?? 0);
                if ($bobot > 0) {
                    $criteria[] = [
                        'id'             => $key, 'nama'       => $config['nama'],
                        'tipe'           => $key, 'bobot_awal' => $bobot,
                        'jenis_kriteria' => $config['jenis'],
                        'scores_map'     => $config['scores_map'],
                    ];
                    $totalInitialWeight += $bobot;
                }
            }
        }

        if (empty($criteria) || $totalInitialWeight <= 0) {
            return ['error_message' => 'Tidak ada kriteria yang diberi bobot atau total bobot adalah nol.', 'criteriaView' => $criteria];
        }

        foreach ($criteria as $idx => $c) {
            $criteria[$idx]['bobot_normalisasi'] = $c['bobot_awal'] / $totalInitialWeight;
        }
        $criteriaView = $criteria;

        $mahasiswaDetails = [];
        $alternatives     = [];
        foreach ($pendaftar as $p) {
            if ($p->mahasiswa) {
                $mahasiswaDetails[$p->mahasiswa_id] = $p->mahasiswa;
                $alternatives[$p->mahasiswa_id]     = $p->mahasiswa->nama_lengkap;
            }
        }
        if (empty($mahasiswaDetails)) {
            return ['error_message' => 'Tidak ada data mahasiswa yang valid dari pendaftar.', 'criteriaView' => $criteriaView];
        }

        $decisionMatrix          = [];
        $mahasiswaLevelsPerSkill = [];
        foreach ($mahasiswaDetails as $mahasiswaId => $mahasiswa) {
            $mahasiswaLevelsPerSkill[$mahasiswaId] = [];
            foreach ($criteria as $c) {
                $score               = 0;
                $studentLevelDisplay = '-';
                switch ($c['tipe']) {
                    case 'skill':
                        $mahasiswaSkill = $mahasiswa->skills->where('skill_id', $c['skill_id'])->where('status_verifikasi', 'Valid')->first();
                        if ($mahasiswaSkill) {
                            $targetLevel         = $c['level_lowongan'];
                            $studentActualLevel  = $mahasiswaSkill->level_kompetensi;
                            $studentLevelDisplay = $studentActualLevel;
                            $score               = $this->matchingScores[$targetLevel][$studentActualLevel] ?? 0;
                        } else { $studentLevelDisplay = 'Tidak Ada/Invalid';}
                        $mahasiswaLevelsPerSkill[$mahasiswaId][$c['id']] = $studentLevelDisplay;
                        break;
                    case 'ipk':
                        $score = floatval($mahasiswa->ipk ?? 9999);
                        break;
                    case 'organisasi':case 'lomba':case 'kasus':
                        $value = $mahasiswa->{$c['tipe']} ?? ($c['tipe'] === 'kasus' ? 'tidak_ada' : 'tidak_ikut');
                        $score = $c['scores_map'][$value] ?? 0;
                        break;
                    case 'skor_ais':
                        $score = floatval($mahasiswa->skor_ais ?? 9999);
                        break;
                }
                $decisionMatrix[$mahasiswaId][$c['id']] = $score;
            }
        }

        if (empty($decisionMatrix)) {
            return ['error_message' => 'Gagal membuat matriks keputusan.', 'criteriaView' => $criteriaView];
        }

        $averageSolution = [];
        foreach ($criteria as $c) {
            $sumScores              = 0;
            $countValidAlternatives = 0;
            foreach ($decisionMatrix as $scores) {
                if (isset($scores[$c['id']])) {$sumScores += $scores[$c['id']];
                    $countValidAlternatives++;}
            }
            $averageSolution[$c['id']] = $countValidAlternatives > 0 ? $sumScores / $countValidAlternatives : 0;
        }

        $pdaMatrix = [];
        $ndaMatrix = [];
        foreach ($decisionMatrix as $mahasiswaId => $scores) {
            foreach ($criteria as $c) {
                $x_ij = $scores[$c['id']] ?? 0;
                $av_j = $averageSolution[$c['id']];

                if ($c['jenis_kriteria'] === 'benefit') {
                    if ($av_j > 0) {
                        $pdaMatrix[$mahasiswaId][$c['id']] = max(0, ($x_ij - $av_j) / $av_j);
                        $ndaMatrix[$mahasiswaId][$c['id']] = max(0, ($av_j - $x_ij) / $av_j);
                    } else {
                        $pdaMatrix[$mahasiswaId][$c['id']] = ($x_ij > 0) ? 1 : 0;
                        $ndaMatrix[$mahasiswaId][$c['id']] = 0;
                    }
                } else { // Untuk kriteria 'cost'
                    if ($av_j > 0) {
                        // Rumus dibalik untuk kriteria cost
                        $pdaMatrix[$mahasiswaId][$c['id']] = max(0, ($av_j - $x_ij) / $av_j);
                        $ndaMatrix[$mahasiswaId][$c['id']] = max(0, ($x_ij - $av_j) / $av_j);
                    } else {
                        // Jika rata-rata 0, dan nilai mahasiswa > 0, itu adalah jarak negatif (buruk)
                        $pdaMatrix[$mahasiswaId][$c['id']] = 0;
                        $ndaMatrix[$mahasiswaId][$c['id']] = ($x_ij > 0) ? 1 : 0;
                    }
                }
            }
        }

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

        $maxSP     = ! empty($spValues) ? max($spValues) : 0;
        $maxSN     = ! empty($snValues) ? max($snValues) : 0;
        $nspValues = [];
        $nsnValues = [];
        foreach ($mahasiswaDetails as $mahasiswaId => $mahasiswa) {
            $nspValues[$mahasiswaId] = ($maxSP > 0) ? ($spValues[$mahasiswaId] / $maxSP) : 0;
            $nsnValues[$mahasiswaId] = ($maxSN > 0) ? (1 - ($snValues[$mahasiswaId] / $maxSN)) : 1;
        }

        $appraisalScores = [];
        foreach ($mahasiswaDetails as $mahasiswaId => $mahasiswa) {
            $appraisalScores[$mahasiswaId] = ($nspValues[$mahasiswaId] + $nsnValues[$mahasiswaId]) / 2;
        }

        $rankedMahasiswa = collect();
        foreach ($appraisalScores as $mahasiswaId => $as_score) {
            $pendaftarPengajuan = $pendaftar->firstWhere('mahasiswa_id', $mahasiswaId);
            if ($pendaftarPengajuan) {
                $rankedMahasiswa->push([
                    'mahasiswa'                 => $mahasiswaDetails[$mahasiswaId],
                    'pengajuan_id'              => $pendaftarPengajuan->pengajuan_id,
                    'skor_akhir_as'             => $as_score,
                    'nilai_kriteria'            => $decisionMatrix[$mahasiswaId],
                    'level_mahasiswa_per_skill' => $mahasiswaLevelsPerSkill[$mahasiswaId] ?? [],
                ]);
            }
        }
        $rankedMahasiswa = $rankedMahasiswa->sortByDesc('skor_akhir_as')->values();

        if ($returnAllSteps) {
            return [
                'criteria'        => $criteria, 'alternatives'           => $alternatives, 'decisionMatrix' => $decisionMatrix,
                'averageSolution' => $averageSolution, 'pdaMatrix'       => $pdaMatrix, 'ndaMatrix'         => $ndaMatrix,
                'spValues'        => $spValues, 'snValues'               => $snValues, 'nspValues'          => $nspValues, 'nsnValues' => $nsnValues,
                'appraisalScores' => $appraisalScores, 'rankedMahasiswa' => $rankedMahasiswa,
                'criteriaView'    => $criteriaView, 'mahasiswaDetails'   => $mahasiswaDetails,
            ];
        }
        return ['rankedMahasiswa' => $rankedMahasiswa, 'criteriaView' => $criteriaView];
    }
}
