<style>
    /* Menambahkan style agar tabel bisa di-scroll jika terlalu lebar */
    .table-responsive { max-height: 500px; }
</style>

<h4>Hasil Rekomendasi Lowongan Magang</h4>
<p>Berikut adalah daftar lowongan yang paling direkomendasikan untuk Anda berdasarkan preferensi yang telah diisi, diurutkan menggunakan metode COPRAS.</p>

<div class="accordion" id="accordionPerhitungan">
    {{-- Hasil Akhir --}}
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingHasil">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHasil" aria-expanded="true" aria-controls="collapseHasil">
                <strong><i class="fas fa-trophy me-2"></i> Peringkat Akhir Rekomendasi</strong>
            </button>
        </h2>
        <div id="collapseHasil" class="accordion-collapse collapse show" aria-labelledby="headingHasil" data-bs-parent="#accordionPerhitungan">
            <div class="accordion-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>Peringkat</th>
                                <th>Lowongan</th>
                                <th>Perusahaan</th>
                                <th>Nilai Utilitas ($N_i$)</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $rank = 1; @endphp
                            @foreach($nilai_N as $id => $skor)
                                @php
                                    $lowongan = $alternatives->find($id);

                                    // --- LOGIKA UNTUK MENENTUKAN STATUS TOMBOL AJUKAN ---
                                    $mahasiswa = auth()->user();
                                    $bisaMelamar = false;
                                    $pesanDisabled = '';

                                    if ($mahasiswa) {
                                        // Validasi #1: Profil mahasiswa harus sudah terverifikasi.
                                        $profilLengkap = $mahasiswa->status_verifikasi == 'valid';

                                        // Validasi #2: Slot lowongan harus tersedia.
                                        $slotTersedia = $lowongan->slotTersedia() > 0;

                                        // Validasi #3: Belum pernah mengajukan di lowongan ini.
                                        $sudahMengajukanDiLowonganIni = $mahasiswa->pengajuan()->where('lowongan_id', $lowongan->lowongan_id)->exists();

                                        // Validasi #4: Tidak sedang memiliki magang aktif.
                                        $punyaMagangAktif = $mahasiswa->magang()->whereIn('status', ['belum', 'sedang'])->exists();

                                        // Validasi #5: Tidak memiliki pengajuan lain yang pending.
                                        $punyaPengajuanPendingLain = $mahasiswa->pengajuan()->where('status', 'belum')->exists();

                                        // Logika untuk menonaktifkan tombol berdasarkan urutan prioritas
                                        if (!$profilLengkap) {
                                            $pesanDisabled = 'Profil Anda harus terverifikasi untuk melamar.';
                                        } elseif (!$slotTersedia) {
                                            $pesanDisabled = 'Slot untuk lowongan ini sudah penuh.';
                                        } elseif ($sudahMengajukanDiLowonganIni) {
                                            $pesanDisabled = 'Anda sudah melamar di lowongan ini.';
                                        } elseif ($punyaMagangAktif) {
                                            $pesanDisabled = 'Anda tidak bisa melamar karena sedang dalam periode magang aktif.';
                                        } elseif ($punyaPengajuanPendingLain) {
                                            $pesanDisabled = 'Anda masih memiliki pengajuan lain yang sedang diproses.';
                                        } else {
                                            $bisaMelamar = true;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td><span class="badge bg-success">{{ $rank++ }}</span></td>
                                    <td>{{ $lowongan->judul_lowongan }}</td>
                                    <td>{{ $lowongan->industri->industri_nama }}</td>
                                    <td><strong>{{ number_format($skor, 2) }}%</strong></td>
                                    <td class="text-center">
                                        @if ($bisaMelamar)
                                            <a href="{{ route('mahasiswa.pengajuan.create', $lowongan->lowongan_id) }}"
                                               class="btn btn-sm btn-info"
                                               title="Ajukan magang untuk lowongan ini">
                                               <i class="fas fa-paper-plane me-1"></i> Ajukan
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled title="{{ $pesanDisabled }}">
                                                <i class="fas fa-paper-plane me-1"></i> Ajukan
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Perhitungan --}}
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingDetail">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDetail" aria-expanded="false" aria-controls="collapseDetail">
                <i class="fas fa-calculator me-2"></i> Lihat Detail Perhitungan COPRAS
            </button>
        </h2>
        <div id="collapseDetail" class="accordion-collapse collapse" aria-labelledby="headingDetail" data-bs-parent="#accordionPerhitungan">
            <div class="accordion-body">
                <h6>Langkah 1: Matriks Keputusan (X)</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Alternatif</th>
                                @foreach($kriteriaList as $k) <th>{{ ucfirst($k) }} (C{{$loop->iteration}})</th> @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matriks_X as $id => $nilai)
                            <tr>
                                <td>{{ $alternatives->find($id)->judul_lowongan }}</td>
                                @foreach($nilai as $v) <td>{{ round($v, 2) }}</td> @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr>

                <h6>Langkah 2 & 4: Bobot (W) dan Matriks Terbobot (V)</h6>
                <div class="table-responsive">
                     <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Alternatif</th>
                                @foreach($kriteriaList as $k) <th>{{ ucfirst($k) }} (C{{$loop->iteration}})</th> @endforeach
                            </tr>
                            <tr>
                                <th>Bobot (W)</th>
                                @foreach($bobot_W as $w) <th>{{ round($w, 4) }}</th> @endforeach
                            </tr>
                        </thead>
                        <tbody>
                             @foreach($matriks_V as $id => $nilai)
                            <tr>
                                <td>{{ $alternatives->find($id)->judul_lowongan }}</td>
                                @foreach($nilai as $v) <td>{{ round($v, 4) }}</td> @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr>

                <h6>Langkah 5, 6, 7: Nilai S, Q, dan N</h6>
                 <div class="table-responsive">
                     <table class="table table-bordered table-sm">
                         <thead>
                             <tr>
                                 <th>Alternatif</th>
                                 <th>$S_+$</th>
                                 <th>$S_-$</th>
                                 <th>$Q_i$</th>
                                 <th>Nilai Utilitas ($N_i$)</th>
                             </tr>
                         </thead>
                         <tbody>
                            @foreach($nilai_Q as $id => $q_val)
                                <tr>
                                    <td>{{ $alternatives->find($id)->judul_lowongan }}</td>
                                    <td>{{ round($nilai_S_plus[$id], 4) }}</td>
                                    <td>{{ round($nilai_S_minus[$id], 4) }}</td>
                                    <td>{{ round($q_val, 4) }}</td>
                                    <td>{{ round($nilai_N[$id], 2) }}%</td>
                                </tr>
                            @endforeach
                         </tbody>
                     </table>
                 </div>

            </div>
        </div>
    </div>
</div>
