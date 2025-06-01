@if(!isset($rankedMahasiswa) || $rankedMahasiswa->isEmpty())
    <div class="alert alert-info mt-3 text-center">
        <i class="fas fa-info-circle me-1"></i>
        @if(isset($message) && $message)
            {{ $message }}
        @else
            Tidak ada pendaftar yang dapat dirangking berdasarkan kriteria yang diberikan, atau belum ada pendaftar pada lowongan ini dengan status yang sesuai.
        @endif
    </div>
@else
    <h6 class="text-dark-blue fw-bold border-bottom pb-2 mb-3 mt-4">Hasil Peringkat Rekomendasi (Metode EDAS)</h6>
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered caption-top"> {{-- table-hover ditambahkan --}}
            <caption class="small text-muted">Total Pendaftar Dievaluasi: {{ $rankedMahasiswa->count() }}</caption>
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 5%;">Peringkat</th>
                    <th>Nama Mahasiswa</th>
                    <th class="text-center" style="width: 12%;">NIM</th>
                    <th class="text-center" style="width: 12%;">Skor Akhir (AS)</th>
                    @if(isset($criteriaView) && !empty($criteriaView))
                        @foreach($criteriaView as $cv)
                            <th class="text-center small" title="Kriteria: {{ $cv['nama'] }} (Bobot Awal: {{ number_format($cv['bobot_awal'],0) }}%)" style="width: {{ 70 / count($criteriaView) }}%;">
                                {{ Str::limit($cv['nama'], 12) }}
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
                        if ($rank == 1) {
                            $rowClass = 'table-success-light'; // Warna custom untuk juara 1 (misal: hijau muda)
                            $rankIcon = '<i class="fas fa-trophy text-warning me-1" title="Peringkat 1"></i>';
                        } elseif ($rank == 2) {
                            $rowClass = 'table-info-light';    // Warna custom untuk juara 2 (misal: biru muda)
                            $rankIcon = '<i class="fas fa-medal text-secondary me-1" title="Peringkat 2"></i>';
                        } elseif ($rank == 3) {
                            $rowClass = 'table-warning-light'; // Warna custom untuk juara 3 (misal: kuning muda)
                            $rankIcon = '<i class="fas fa-award text-brown me-1" title="Peringkat 3"></i>'; // text-brown adalah contoh, mungkin perlu custom
                        }
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td class="text-center fw-bold">
                            {!! $rankIcon !!}{{ $rank }}
                        </td>
                        <td>
                            <img src="{{ $mahasiswaData['mahasiswa']->foto ? asset('storage/mahasiswa/' . $mahasiswaData['mahasiswa']->foto) : asset('assets/images/default-avatar.png') }}" {{-- Pastikan path default avatar benar --}}
                                 alt="Foto" class="rounded-circle me-2" style="width: 28px; height: 28px; object-fit: cover;">
                            <a href="{{ route('industri.lowongan.pendaftar.show_profil', $mahasiswaData['pengajuan_id']) }}" target="_blank" title="Lihat Profil Pendaftar {{ $mahasiswaData['mahasiswa']->nama_lengkap }}">
                                {{ $mahasiswaData['mahasiswa']->nama_lengkap }}
                            </a>
                        </td>
                        <td class="text-center">{{ $mahasiswaData['mahasiswa']->nim }}</td>
                        <td class="text-center fw-bolder {{ $rank <=3 ? 'text-dark' : 'fw-medium' }}">{{ number_format($mahasiswaData['skor_akhir_as'], 4) }}</td>
                        @if(isset($criteriaView) && !empty($criteriaView))
                            @foreach($criteriaView as $cv)
                                <td class="text-center small">
                                    @php
                                        $nilaiKriteria = $mahasiswaData['nilai_kriteria'][$cv['id']] ?? '0';
                                        $levelMahasiswa = $mahasiswaData['level_mahasiswa_per_skill'][$cv['id']] ?? '-';
                                    @endphp
                                    {{ is_numeric($nilaiKriteria) ? number_format((float)$nilaiKriteria, ($cv['tipe'] == 'ipk' ? 2 : 0)) : $nilaiKriteria }}
                                    @if($cv['tipe'] == 'skill' && $levelMahasiswa !== '-' && $levelMahasiswa !== 'Tidak Ada')
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
        <strong>Keterangan Level Mahasiswa per Skill:</strong> Nilai yang diperoleh (Level aktual mahasiswa yang diklaim).
        <br>
        <strong>Keterangan Skor Akhir (AS):</strong> Semakin tinggi skor (mendekati 1), semakin direkomendasikan.
    </p>
@endif

@push('css')
<style>
    /* ... (CSS Anda yang sudah ada untuk variabel :root, dll.) ... */

    /* Warna custom untuk baris peringkat teratas */
    .table-success-light {
        background-color: #d1e7dd !important; /* Hijau muda, sesuaikan dengan tema Anda */
        --bs-table-striped-bg: #c8e0d5 !important; /* Untuk striping jika ada */
        border-left: 4px solid var(--app-success-color) !important; /* Aksen border kiri */
    }
    .table-info-light {
        background-color: #cff4fc !important; /* Biru muda */
        --bs-table-striped-bg: #c5ecf8 !important;
        border-left: 4px solid #0dcaf0 !important; /* Warna info Bootstrap */
    }
    .table-warning-light {
        background-color: #fff3cd !important; /* Kuning muda */
        --bs-table-striped-bg: #fbecbf !important;
        border-left: 4px solid #ffc107 !important; /* Warna warning Bootstrap */
    }

    /* Styling tambahan untuk ikon peringkat */
    .fa-trophy, .fa-medal, .fa-award {
        font-size: 0.9em;
    }
    .text-brown { /* Contoh jika Anda ingin warna coklat untuk peringkat 3 */
        color: #8B4513;
    }

    /* Hover pada baris tabel (opsional, jika menggunakan table-hover) */
    .table-hover tbody tr:hover {
        background-color: #f0f0f0 !important; /* Warna hover yang lebih netral */
    }
    .table-hover .table-success-light:hover {
        background-color: #badee0 !important; /* Warna hover spesifik untuk juara 1 */
    }
    .table-hover .table-info-light:hover {
        background-color: #b8e3f1 !important;
    }
    .table-hover .table-warning-light:hover {
        background-color: #f8e3b0 !important;
    }

</style>
@endpush
