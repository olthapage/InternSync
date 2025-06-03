@if(isset($error_message))
    <div class="alert alert-danger">{{ $error_message }}</div>
@elseif(isset($criteria) && isset($alternatives) && isset($decisionMatrix))
    <p class="small text-muted">Berikut adalah langkah-langkah perhitungan metode EDAS untuk menghasilkan rekomendasi. Semua nilai skor kriteria (kecuali IPK dan Skor AIS) telah dinormalisasi ke skala 0-100 untuk perhitungan.</p>
    <div class="accordion" id="accordionEdasSteps-{{ $criteria[0]['id'] ?? rand() }}"> {{-- ID unik --}}

        {{-- 1. Kriteria dan Bobot Ternormalisasi (Wj) --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingKriteriaBobot-{{ $lowongan->lowongan_id ?? '' }}">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKriteriaBobot-{{ $lowongan->lowongan_id ?? '' }}" aria-expanded="true" aria-controls="collapseKriteriaBobot-{{ $lowongan->lowongan_id ?? '' }}">
                    <strong>Langkah 1:</strong> Kriteria (C<sub>j</sub>) dan Bobot Ternormalisasi (W<sub>j</sub>)
                </button>
            </h2>
            <div id="collapseKriteriaBobot-{{ $lowongan->lowongan_id ?? '' }}" class="accordion-collapse collapse show" aria-labelledby="headingKriteriaBobot-{{ $lowongan->lowongan_id ?? '' }}">
                <div class="accordion-body p-2 table-responsive">
                    <table class="table table-sm table-bordered small align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">ID Kriteria</th>
                                <th>Nama Kriteria</th>
                                <th class="text-center text-nowrap">Bobot Awal (%)</th>
                                <th class="text-center text-nowrap">Bobot Ternormalisasi (W<sub>j</sub>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($criteria as $c)
                            <tr>
                                <td class="text-nowrap">{{ $c['id'] }}</td>
                                <td>{{ $c['nama'] }}</td>
                                <td class="text-center">{{ number_format($c['bobot_awal'], 0) }}</td>
                                <td class="text-center">{{ number_format($c['bobot_normalisasi'], 4) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 2. Matriks Keputusan (Xij) --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingMatriksKeputusan-{{ $lowongan->lowongan_id ?? '' }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMatriksKeputusan-{{ $lowongan->lowongan_id ?? '' }}" aria-expanded="false" aria-controls="collapseMatriksKeputusan-{{ $lowongan->lowongan_id ?? '' }}">
                    <strong>Langkah 2:</strong> Matriks Keputusan (X<sub>ij</sub>) - Nilai Alternatif pada Setiap Kriteria (Skala 0-100)
                </button>
            </h2>
            <div id="collapseMatriksKeputusan-{{ $lowongan->lowongan_id ?? '' }}" class="accordion-collapse collapse" aria-labelledby="headingMatriksKeputusan-{{ $lowongan->lowongan_id ?? '' }}">
                <div class="accordion-body p-2 table-responsive">
                    <table class="table table-sm table-bordered small align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">Alternatif (Mahasiswa)</th>
                                @foreach($criteria as $c)
                                    <th class="text-center text-nowrap" title="{{ $c['nama'] }} (ID: {{ $c['id'] }})">{{ Str::limit($c['nama'],10) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($decisionMatrix as $mhsId => $scores)
                            <tr>
                                <td class="text-nowrap">{{ $alternatives[$mhsId] ?? 'Mhs '.$mhsId }}</td>
                                @foreach($criteria as $c)
                                    <td class="text-center">{{ number_format($scores[$c['id']] ?? 0, 2) }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 3. Solusi Rata-rata (AVj) --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingAverageSolution-{{ $lowongan->lowongan_id ?? '' }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAverageSolution-{{ $lowongan->lowongan_id ?? '' }}" aria-expanded="false" aria-controls="collapseAverageSolution-{{ $lowongan->lowongan_id ?? '' }}">
                    <strong>Langkah 3:</strong> Solusi Rata-rata (AV<sub>j</sub>) per Kriteria
                </button>
            </h2>
            <div id="collapseAverageSolution-{{ $lowongan->lowongan_id ?? '' }}" class="accordion-collapse collapse" aria-labelledby="headingAverageSolution-{{ $lowongan->lowongan_id ?? '' }}">
                <div class="accordion-body p-2 table-responsive">
                     <table class="table table-sm table-bordered small align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">ID Kriteria</th>
                                <th>Nama Kriteria</th>
                                <th class="text-center text-nowrap">Nilai Rata-rata (AV<sub>j</sub>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($criteria as $c)
                            <tr>
                                <td class="text-nowrap">{{ $c['id'] }}</td>
                                <td>{{ $c['nama'] }}</td>
                                <td class="text-center">{{ number_format($averageSolution[$c['id']] ?? 0, 4) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 4. Matriks Jarak Positif (PDAij) --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingPdaMatrix-{{ $lowongan->lowongan_id ?? '' }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePdaMatrix-{{ $lowongan->lowongan_id ?? '' }}" aria-expanded="false" aria-controls="collapsePdaMatrix-{{ $lowongan->lowongan_id ?? '' }}">
                    <strong>Langkah 4:</strong> Matriks Jarak Positif dari Rata-rata (PDA<sub>ij</sub>)
                </button>
            </h2>
            <div id="collapsePdaMatrix-{{ $lowongan->lowongan_id ?? '' }}" class="accordion-collapse collapse" aria-labelledby="headingPdaMatrix-{{ $lowongan->lowongan_id ?? '' }}">
                <div class="accordion-body p-2 table-responsive">
                    <table class="table table-sm table-bordered small align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">Alternatif</th>
                                @foreach($criteria as $c) <th class="text-center text-nowrap" title="{{ $c['nama'] }} (ID: {{ $c['id'] }})">{{ Str::limit($c['nama'],10) }}</th> @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pdaMatrix as $mhsId => $pdaScores)
                            <tr>
                                <td class="text-nowrap">{{ $alternatives[$mhsId] ?? 'Mhs '.$mhsId }}</td>
                                @foreach($criteria as $c) <td class="text-center">{{ number_format($pdaScores[$c['id']] ?? 0, 4) }}</td> @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 5. Matriks Jarak Negatif (NDAij) --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingNdaMatrix-{{ $lowongan->lowongan_id ?? '' }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNdaMatrix-{{ $lowongan->lowongan_id ?? '' }}" aria-expanded="false" aria-controls="collapseNdaMatrix-{{ $lowongan->lowongan_id ?? '' }}">
                    <strong>Langkah 5:</strong> Matriks Jarak Negatif dari Rata-rata (NDA<sub>ij</sub>)
                </button>
            </h2>
            <div id="collapseNdaMatrix-{{ $lowongan->lowongan_id ?? '' }}" class="accordion-collapse collapse" aria-labelledby="headingNdaMatrix-{{ $lowongan->lowongan_id ?? '' }}">
                <div class="accordion-body p-2 table-responsive">
                     <table class="table table-sm table-bordered small align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">Alternatif</th>
                                @foreach($criteria as $c) <th class="text-center text-nowrap" title="{{ $c['nama'] }} (ID: {{ $c['id'] }})">{{ Str::limit($c['nama'],10) }}</th> @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ndaMatrix as $mhsId => $ndaScores)
                            <tr>
                                <td class="text-nowrap">{{ $alternatives[$mhsId] ?? 'Mhs '.$mhsId }}</td>
                                @foreach($criteria as $c) <td class="text-center">{{ number_format($ndaScores[$c['id']] ?? 0, 4) }}</td> @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 6. SPi, SNi, NSPi, NSNi, ASi --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFinalScores-{{ $lowongan->lowongan_id ?? '' }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFinalScores-{{ $lowongan->lowongan_id ?? '' }}" aria-expanded="false" aria-controls="collapseFinalScores-{{ $lowongan->lowongan_id ?? '' }}">
                    <strong>Langkah 6-8:</strong> SP<sub>i</sub>, SN<sub>i</sub>, NSP<sub>i</sub>, NSN<sub>i</sub>, dan Skor Akhir (AS<sub>i</sub>)
                </button>
            </h2>
            <div id="collapseFinalScores-{{ $lowongan->lowongan_id ?? '' }}" class="accordion-collapse collapse" aria-labelledby="headingFinalScores-{{ $lowongan->lowongan_id ?? '' }}">
                <div class="accordion-body p-2 table-responsive">
                    <table class="table table-sm table-bordered small align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-nowrap">Alternatif (Mahasiswa)</th>
                                <th class="text-center text-nowrap" title="Sum of Positive Distances (weighted)">SP<sub>i</sub></th>
                                <th class="text-center text-nowrap" title="Sum of Negative Distances (weighted)">SN<sub>i</sub></th>
                                <th class="text-center text-nowrap" title="Normalized SP">NSP<sub>i</sub></th>
                                <th class="text-center text-nowrap" title="Normalized SN">NSN<sub>i</sub></th>
                                <th class="text-center text-nowrap fw-bold">Skor Akhir (AS<sub>i</sub>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appraisalScores as $mhsId => $asScore)
                            <tr>
                                <td class="text-nowrap">{{ $alternatives[$mhsId] ?? 'Mhs '.$mhsId }}</td>
                                <td class="text-center">{{ number_format($spValues[$mhsId] ?? 0, 4) }}</td>
                                <td class="text-center">{{ number_format($snValues[$mhsId] ?? 0, 4) }}</td>
                                <td class="text-center">{{ number_format($nspValues[$mhsId] ?? 0, 4) }}</td>
                                <td class="text-center">{{ number_format($nsnValues[$mhsId] ?? 0, 4) }}</td>
                                <td class="text-center fw-bold">{{ number_format($asScore, 4) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 7. Hasil Peringkat Akhir (sama seperti di view hasil utama) --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingRanking-{{ $lowongan->lowongan_id ?? '' }}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRanking-{{ $lowongan->lowongan_id ?? '' }}" aria-expanded="false" aria-controls="collapseRanking-{{ $lowongan->lowongan_id ?? '' }}">
                    <strong>Langkah 9:</strong> Hasil Peringkat Akhir
                </button>
            </h2>
            <div id="collapseRanking-{{ $lowongan->lowongan_id ?? '' }}" class="accordion-collapse collapse" aria-labelledby="headingRanking-{{ $lowongan->lowongan_id ?? '' }}">
                <div class="accordion-body p-2 table-responsive">
                    @if(isset($rankedMahasiswa) && $rankedMahasiswa->isNotEmpty())
                        <table class="table table-sm table-striped table-bordered small align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">Peringkat</th>
                                    <th>Nama Mahasiswa</th>
                                    <th class="text-center">Skor Akhir (AS)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rankedMahasiswa as $index => $mahasiswaData)
                                <tr>
                                    <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                    <td>{{ $mahasiswaData['mahasiswa']->nama_lengkap }}</td>
                                    <td class="text-center fw-bold">{{ number_format($mahasiswaData['skor_akhir_as'], 4) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Tidak ada data peringkat.</p>
                    @endif
                </div>
            </div>
        </div>

    </div>
@else
    <div class="alert alert-warning">Tidak ada data langkah perhitungan yang dapat ditampilkan saat ini. Pastikan kriteria dan pendaftar sudah ada.</div>
@endif
