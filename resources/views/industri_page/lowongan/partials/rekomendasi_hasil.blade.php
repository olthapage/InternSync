@if(isset($error_message))
    <div class="alert alert-danger">{{ $error_message }}</div>
@elseif(!isset($rankedMahasiswa) || $rankedMahasiswa->isEmpty())
    {{-- Tampilkan pesan jika tidak ada pendaftar --}}
    <div class="alert alert-info mt-3 text-center">
        <i class="fas fa-info-circle me-1"></i>
        {{ $message ?? 'Tidak ada pendaftar yang dapat dirangking.' }}
    </div>
@else
    {{-- Tampilan hasil utama --}}
    <div class="d-flex justify-content-between align-items-center">
        <h6 class="text-dark-blue fw-bold border-bottom pb-2 mb-3 mt-4">Hasil Peringkat Rekomendasi</h6>
        <button type="button" class="btn btn-sm btn-outline-secondary mb-3"
                id="btnLihatLangkahSpk-{{ $lowongan->lowongan_id }}"
                data-lowongan-id="{{ $lowongan->lowongan_id }}">
            <i class="fas fa-shoe-prints me-1"></i> Lihat Langkah Perhitungan
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover table-striped table-bordered caption-top">
            <caption class="small text-muted">Total Pendaftar Dievaluasi: {{ $rankedMahasiswa->count() }}</caption>
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 5%;">Rank</th>
                    <th>Nama Mahasiswa</th>
                    <th class="text-center" style="width: 15%;">Skor Akhir</th>
                    {{-- Header dinamis untuk nilai kriteria --}}
                    @if(isset($criteriaView) && !empty($criteriaView))
                        @foreach($criteriaView as $cv)
                            <th class="text-center small" title="Kriteria: {{ $cv['nama'] }}">
                                {{ Str::limit($cv['nama'], 15) }}
                            </th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($rankedMahasiswa as $index => $mahasiswaData)
                    @php
                        // Logika untuk highlight baris peringkat teratas
                        $rank = $index + 1;
                        $rowClass = '';
                        $rankIcon = '';
                        if ($rank == 1) { $rowClass = 'table-white'; $rankIcon = '<i class="fas fa-trophy text-warning me-1"></i>'; }
                        elseif ($rank == 2) { $rowClass = 'table-white'; $rankIcon = '<i class="fas fa-medal text-secondary me-1"></i>'; }
                        elseif ($rank == 3) { $rowClass = 'table-white'; $rankIcon = '<i class="fas fa-award text-brown me-1"></i>'; }
                    @endphp
                    <tr class="{{ $rowClass }}">
                        <td class="text-center fw-bold">{!! $rankIcon !!}{{ $rank }}</td>
                        <td>
                            <a href="{{ route('industri.lowongan.pendaftar.show_profil', $mahasiswaData['pengajuan_id']) }}" target="_blank">
                                {{ optional($mahasiswaData['mahasiswa'])->nama_lengkap }}
                            </a>
                        </td>
                        <td class="text-center fw-bolder">{{ number_format($mahasiswaData['skor_akhir_as'], 4) }}</td>

                        {{-- Nilai dinamis per kriteria --}}
                        @if(isset($criteriaView) && !empty($criteriaView))
                            @foreach($criteriaView as $cv)
                                <td class="text-center small">
                                    {{-- Tampilkan nilai asli sebelum dinormalisasi --}}
                                    {{-- (Logika untuk menampilkan nilai asli bisa ditambahkan di sini jika perlu) --}}
                                    {{ number_format($mahasiswaData['nilai_kriteria'][$cv['id']] ?? 0, 1) }}
                                </td>
                            @endforeach
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Placeholder untuk detail langkah perhitungan SPK --}}
    <div id="spkStepsContainer-{{ $lowongan->lowongan_id }}" class="mt-4" style="display:none;">
        <h6 class="text-dark-blue fw-bold">Detail Langkah Perhitungan:</h6>
        <div id="spkStepsContent-{{ $lowongan->lowongan_id }}" class="p-3 border rounded bg-light">
            Memuat langkah perhitungan...
        </div>
    </div>
@endif
