@if(isset($error_message))
    <div class="alert alert-danger mt-3 text-center">
        <i class="fas fa-exclamation-triangle me-1"></i>
        {{ $error_message }}
    </div>
@elseif(!isset($rankedMahasiswa) || $rankedMahasiswa->isEmpty())
    <div class="alert alert-info mt-3 text-center">
        <i class="fas fa-info-circle me-1"></i>
        @if(isset($message) && $message)
            {{ $message }}
        @else
            Tidak ada pendaftar yang dapat dirangking berdasarkan kriteria yang diberikan, atau belum ada pendaftar pada lowongan ini dengan status yang sesuai.
        @endif
    </div>
@else
    {{-- Pastikan variabel $lowongan (DetailLowonganModel instance) tersedia di view ini --}}
    @php
        $currentLowonganId = optional($lowongan)->lowongan_id ?? ($rankedMahasiswa->first()['pengajuan_id'] ? optional($rankedMahasiswa->first()['mahasiswa']->pengajuan->first()->lowongan)->lowongan_id : 'default');
        // Jika $lowongan tidak di-pass, coba ambil dari pengajuan pertama (kurang ideal, tapi sebagai fallback)
        if ($currentLowonganId === 'default' && $rankedMahasiswa->isNotEmpty()) {
            $firstPengajuan = \App\Models\PengajuanModel::find($rankedMahasiswa->first()['pengajuan_id']);
            if ($firstPengajuan) {
                $currentLowonganId = $firstPengajuan->lowongan_id;
            }
        }
    @endphp

    <div class="d-flex justify-content-between align-items-center">
        <h6 class="text-dark-blue fw-bold border-bottom pb-2 mb-3 mt-4">Hasil Peringkat Rekomendasi (Metode EDAS)</h6>
        @if($currentLowonganId !== 'default')
            <button type="button" class="btn btn-sm btn-outline-secondary mb-3"
                    id="btnLihatLangkahEdas-{{ $currentLowonganId }}"
                    data-lowongan-id="{{ $currentLowonganId }}">
                <i class="fas fa-shoe-prints me-1"></i> Lihat Langkah Perhitungan
            </button>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover table-striped table-bordered caption-top">
            <caption class="small text-muted">Total Pendaftar Dievaluasi: {{ $rankedMahasiswa->count() }}</caption>
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 5%;">Rank</th>
                    <th>Nama Mahasiswa</th>
                    <th class="text-center" style="width: 10%;">NIM</th>
                    <th class="text-center" style="width: 10%;">Skor Akhir (AS)</th>
                    @if(isset($criteriaView) && !empty($criteriaView))
                        @php
                            // Hitung sisa lebar untuk kolom kriteria dinamis
                            // 5% (Rank) + 30% (Nama) + 10% (NIM) + 10% (Skor AS) = 55%
                            // Sisa 45% untuk kolom kriteria
                            $remainingWidthPercentage = 45;
                            $criteriaColumnCount = count($criteriaView);
                            $dynamicColWidth = ($criteriaColumnCount > 0) ? ($remainingWidthPercentage / $criteriaColumnCount) : $remainingWidthPercentage;
                        @endphp
                        @foreach($criteriaView as $cv)
                            <th class="text-center small"
                                title="Kriteria: {{ $cv['nama'] }} (Bobot: {{ number_format($cv['bobot_normalisasi'] * 100, 1) }}%)"
                                style="width: {{ max(8, $dynamicColWidth) }}%;">
                                {{ Str::limit($cv['nama'], 15) }}
                            </th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($rankedMahasiswa as $index => $mahasiswaData)
                    @php
                        $rank = $index + 1;
                        $rowClass = '';
                        $rankIcon = '';
                        if ($rank == 1) { $rowClass = 'table-success-light'; $rankIcon = '<i class="fas fa-trophy text-warning me-1" title="Peringkat 1"></i>'; }
                        elseif ($rank == 2) { $rowClass = 'table-info-light'; $rankIcon = '<i class="fas fa-medal text-secondary me-1" title="Peringkat 2"></i>'; }
                        elseif ($rank == 3) { $rowClass = 'table-warning-light'; $rankIcon = '<i class="fas fa-award text-brown me-1" title="Peringkat 3"></i>'; }
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td class="text-center fw-bold">
                            {!! $rankIcon !!}{{ $rank }}
                        </td>
                        <td>
                            <img src="{{ optional($mahasiswaData['mahasiswa'])->foto ? asset('storage/foto/' . $mahasiswaData['mahasiswa']->foto) : asset('assets/default-profile.png') }}"
                                 alt="Foto" class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;">
                            <a href="{{ route('industri.lowongan.pendaftar.show_profil', $mahasiswaData['pengajuan_id']) }}" target="_blank" title="Lihat Profil Pendaftar {{ optional($mahasiswaData['mahasiswa'])->nama_lengkap }}">
                                {{ optional($mahasiswaData['mahasiswa'])->nama_lengkap }}
                            </a>
                        </td>
                        <td class="text-center">{{ optional($mahasiswaData['mahasiswa'])->nim }}</td>
                        <td class="text-center fw-bolder {{ $rank <=3 ? 'text-dark' : 'fw-medium' }}">{{ number_format($mahasiswaData['skor_akhir_as'], 4) }}</td>

                        @if(isset($criteriaView) && !empty($criteriaView))
                            @foreach($criteriaView as $cv)
                                <td class="text-center small">
                                    @php
                                        $nilaiKriteria = $mahasiswaData['nilai_kriteria'][$cv['id']] ?? 0;
                                        $levelMahasiswa = $mahasiswaData['level_mahasiswa_per_skill'][$cv['id']] ?? '-';
                                        $displayValue = $nilaiKriteria; // Default

                                        // Konversi kembali nilai yang dinormalisasi ke skala aslinya untuk tampilan
                                        if ($cv['tipe'] == 'ipk') {
                                            // Jika nilai Xij untuk IPK adalah hasil normalisasi (0-100), tampilkan nilai IPK asli
                                            $displayValue = number_format(optional($mahasiswaData['mahasiswa'])->ipk, 2);
                                        } elseif ($cv['tipe'] == 'skor_ais') {
                                            $displayValue = number_format(optional($mahasiswaData['mahasiswa'])->skor_ais, 0);
                                        } elseif (in_array($cv['tipe'], ['organisasi', 'lomba', 'kasus'])) {
                                            $originalValueKey = optional($mahasiswaData['mahasiswa'])->{$cv['tipe']}; // Ambil nilai enum asli dari mahasiswa
                                            if ($originalValueKey) {
                                                $displayValue = ucfirst(str_replace('_', ' ', $originalValueKey));
                                            } else {
                                                $displayValue = ($cv['tipe'] === 'kasus' ? 'Tidak Ada' : 'Tidak Ikut');
                                            }
                                        } else { // Untuk skill, tampilkan skor 0-100
                                            $displayValue = number_format($nilaiKriteria,0);
                                        }
                                    @endphp
                                    {{ $displayValue }}
                                    @if($cv['tipe'] == 'skill' && $levelMahasiswa !== '-' && $levelMahasiswa !== 'Tidak Ada/Invalid')
                                     <span class="badge bg-light text-dark border ms-1" style="font-size:0.7em;">{{ $levelMahasiswa }}</span>
                                    @endif
                                </td>
                            @endforeach
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <p class="small text-muted mt-2">
        <strong>Keterangan:</strong>
        <br> - Level Mahasiswa per Skill: (Level aktual yang diklaim & divalidasi).
        <br> - Skor Akhir (AS): Semakin tinggi skor (mendekati 1), semakin direkomendasikan.
        <br> - Nilai IPK, Skor AIS, Organisasi, Lomba, dan Kasus ditampilkan dalam skala/kategori aslinya.
    </p>

    {{-- Placeholder untuk detail langkah perhitungan EDAS --}}
    <div id="edasStepsContainer-{{ $currentLowonganId }}" class="mt-4" style="display:none;">
        <h6 class="text-dark-blue fw-bold">Detail Langkah Perhitungan EDAS:</h6>
        <div id="edasStepsContent-{{ $currentLowonganId }}" class="p-3 border rounded bg-white">
            Memuat langkah perhitungan...
        </div>
    </div>
@endif
