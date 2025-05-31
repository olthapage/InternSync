@extends('layouts.template')

@section('title', 'Review Pendaftar: ' . ($pengajuan->mahasiswa->nama_lengkap ?? 'Mahasiswa') . ' - Lowongan: ' .
    ($pengajuan->lowongan->judul_lowongan ?? ''))

    @push('css')
        <style>
            :root {
                --app-border-color: #e9ecef;
                /* Warna border abu-abu muda yang umum */
                --app-text-muted: #6c757d;
                --app-text-dark: #212529;
                --app-bg-light-custom: #f8f9fa;
                /* Background abu-abu sangat muda */
                --app-success-color: #198754;
                /* Warna hijau btn-success Bootstrap */
                --app-primary-text-color: #0d6efd;
                /* Warna teks biru primary Bootstrap */
                --app-info-badge-bg: #0dcaf0;
                /* Warna info untuk badge level */
                --app-pending-badge-bg: #ffc107;
                /* Warna kuning untuk pending */
                --app-pending-badge-text: #000;
                --app-invalid-badge-bg: #dc3545;
                /* Warna merah untuk invalid */
            }

            .profile-header-clean {
                background-color: #fff;
                /* Bisa juga var(--app-bg-light-custom) jika ingin sedikit beda */
                padding: 1.5rem;
                border: 1px solid var(--app-border-color);
                border-radius: 0.375rem;
                /* Radius standar Bootstrap */
                margin-bottom: 2rem;
            }

            .profile-avatar-clean {
                width: 100px;
                height: 100px;
                object-fit: cover;
                border: 3px solid var(--app-border-color);
                /* Border abu-abu tipis */
            }

            .section-title-clean {
                margin-top: 2rem;
                margin-bottom: 1.25rem;
                color: var(--app-text-dark);
                font-weight: 600;
                font-size: 1.25rem;
                padding-bottom: 0.6rem;
                border-bottom: 3px solid var(--app-success-color);
                /* Aksen border hijau */
            }

            .section-title-clean:first-child {
                margin-top: 0;
            }


            .skill-card-item {
                border: 1px solid var(--app-border-color);
                box-shadow: none;
                /* Hilangkan shadow untuk tampilan lebih flat */
                margin-bottom: 1rem;
                background-color: #fff;
            }

            .skill-card-item .card-body {
                padding: 1rem 1.25rem;
            }

            .portfolio-list-clean {
                list-style: none;
                padding-left: 0;
                margin-top: 0.5rem;
            }

            .portfolio-list-clean li {
                background-color: var(--app-bg-light-custom);
                /* Background sedikit beda untuk item portofolio */
                border: 1px solid #e0e5e9;
                /* Border lebih soft */
                padding: 0.65rem 1rem;
                margin-bottom: 0.5rem;
                border-radius: .25rem;
            }

            .portfolio-title-clean {
                font-weight: 500;
                color: var(--app-text-dark);
            }

            .portfolio-type-clean {
                font-size: 0.8em;
                color: var(--app-text-muted);
            }

            .portfolio-link-clean a {
                font-size: 0.85em;
            }

            .badge.custom-badge {
                /* Pengganti badge-status-* agar lebih mudah dikontrol */
                font-size: 0.8em;
                padding: .4em .65em;
                font-weight: 500;
                color: white;
                /* Default teks putih */
            }

            .badge.bg-level {
                background-color: var(--app-info-badge-bg) !important;
            }

            .badge.bg-valid {
                background-color: var(--app-success-color) !important;
            }

            .badge.bg-pending {
                background-color: var(--app-pending-badge-bg) !important;
                color: var(--app-pending-badge-text) !important;
            }

            .badge.bg-invalid {
                background-color: var(--app-invalid-badge-bg) !important;
            }

            .badge.bg-default {
                background-color: #6c757d !important;
            }

            /* Untuk status lain */


            .action-panel .card {
                border: 1px solid var(--app-border-color);
                box-shadow: none;
            }

            .action-panel .card-body {
                background-color: var(--app-bg-light-custom);
            }

            .btn-action-main {
                /* Tombol aksi utama */
                border-color: var(--app-success-color);
                background-color: var(--app-success-color);
                color: white;
            }

            .btn-action-main:hover {
                background-color: #146c43;
                /* Darken success */
                border-color: #13653f;
            }

            .btn-action-secondary {
                border-color: #5a6268;
                background-color: #6c757d;
                color: white;
            }

            .btn-action-secondary:hover {
                background-color: #5a6268;
                border-color: #545b62;
            }
        </style>
    @endpush

@section('content')
    <div class="container-fluid py-3">
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('industri.lowongan.show', $pengajuan->lowongan->lowongan_id) }}"
                    class="btn btn-sm btn-outline-dark"> {{-- Ganti ke outline-dark untuk monokrom --}}
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Detail Lowongan
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm"> {{-- Main card bisa dengan shadow tipis atau border-0 jika template dasar sudah ada card --}}
            <div class="card-body p-lg-4">
                {{-- Header Informasi Lowongan dan Mahasiswa --}}
                <div class="profile-header-clean mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            <img src="{{ $pengajuan->mahasiswa->foto ? asset('storage/mahasiswa/' . $pengajuan->mahasiswa->foto) : asset('assets/default-profile.png') }}"
                                alt="Foto Mahasiswa" class="profile-avatar-clean rounded-circle">
                        </div>
                        <div class="col-md-6">
                            <h3 class="mb-1">{{ $pengajuan->mahasiswa->nama_lengkap ?? 'Nama Mahasiswa' }}</h3>
                            <p class="text-muted mb-1"><i class="fas fa-id-card fa-fw me-2"></i>NIM:
                                {{ $pengajuan->mahasiswa->nim ?? '-' }}</p>
                            <p class="text-muted mb-1"><i class="fas fa-envelope fa-fw me-2"></i>Email:
                                {{ $pengajuan->mahasiswa->email ?? '-' }}</p>
                            <p class="text-muted mb-0"><i class="fas fa-graduation-cap fa-fw me-2"></i>Prodi:
                                {{ optional($pengajuan->mahasiswa->prodi)->nama_prodi ?? '-' }}</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <h6 class="mb-1 text-muted">Melamar untuk Lowongan:</h6>
                            <p class="fw-bold mb-0" style="color: var(--app-primary-text-color);">
                                {{ $pengajuan->lowongan->judul_lowongan ?? 'Judul Lowongan' }}</p>
                            <p class="text-muted small">
                                {{ optional($pengajuan->lowongan->industri)->industri_nama ?? 'Nama Industri' }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Kolom Kiri: Skill Mahasiswa & Portofolio Terkait --}}
                    <div class="col-lg-7 border-end-lg pe-lg-4">
                        <h4 class="section-title-clean">Keahlian yang Diajukan Mahasiswa</h4>
                        @if ($pengajuan->mahasiswa->skills->isEmpty())
                            <div class="alert alert-secondary text-center">Mahasiswa ini belum mencantumkan skill atau belum
                                ada skill yang tervalidasi.</div>
                        @else
                            @foreach ($pengajuan->mahasiswa->skills as $mahasiswaSkill)
                                <div class="card detail-card"> {{-- Menggunakan class .detail-card --}}
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0 me-2">
                                                {{ optional($mahasiswaSkill->detailSkill)->skill_nama ?? 'Skill tidak diketahui' }}
                                            </h6>
                                            <div>
                                                <span
                                                    class="badge custom-badge bg-level me-1">{{ $mahasiswaSkill->level_kompetensi }}</span>
                                                @php
                                                    $statusClass = 'bg-default'; // Default
                                                    if ($mahasiswaSkill->status_verifikasi === 'Valid') {
                                                        $statusClass = 'bg-valid';
                                                    } elseif ($mahasiswaSkill->status_verifikasi === 'Pending') {
                                                        $statusClass = 'bg-pending';
                                                    } elseif ($mahasiswaSkill->status_verifikasi === 'Invalid') {
                                                        $statusClass = 'bg-invalid';
                                                    }
                                                @endphp
                                                <span
                                                    class="badge custom-badge {{ $statusClass }}">{{ $mahasiswaSkill->status_verifikasi }}</span>
                                            </div>
                                        </div>

                                        @if ($mahasiswaSkill->linkedPortofolios->isNotEmpty())
                                            <p class="mt-2 mb-1 text-xs text-uppercase fw-bold text-muted">Bukti Portofolio
                                                Terkait:</p>
                                            <ul class="portfolio-list-clean">
                                                @foreach ($mahasiswaSkill->linkedPortofolios as $portfolioLink)
                                                    <li>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <span
                                                                    class="portfolio-title-clean">{{ optional($portfolioLink->portofolio)->judul_portofolio }}</span>
                                                                <span class="portfolio-type-clean">
                                                                    ({{ ucfirst(optional($portfolioLink->portofolio)->tipe_portofolio) }})
                                                                </span>
                                                            </div>
                                                            <span class="portfolio-link-clean">
                                                                @if (in_array(optional($portfolioLink->portofolio)->tipe_portofolio, ['url', 'video']))
                                                                    <a href="{{ optional($portfolioLink->portofolio)->lokasi_file_atau_url }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-dark py-0 px-1"><i
                                                                            class="fas fa-external-link-alt"></i></a>
                                                                @elseif(in_array(optional($portfolioLink->portofolio)->tipe_portofolio, ['file', 'gambar']))
                                                                    <a href="{{ asset('storage/' . optional($portfolioLink->portofolio)->lokasi_file_atau_url) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-dark py-0 px-1"><i
                                                                            class="fas fa-download"></i></a>
                                                                @endif
                                                            </span>
                                                        </div>
                                                        @if (optional($portfolioLink->pivot)->deskripsi_penggunaan_skill)
                                                            <p class="small text-muted mt-1 mb-0 fst-italic">
                                                                "{{ $portfolioLink->pivot->deskripsi_penggunaan_skill }}"
                                                            </p>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="small text-muted fst-italic mt-2 mb-0">Tidak ada portofolio yang
                                                dikaitkan untuk skill ini.</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <h4 class="section-title-clean mt-4">Semua Item Portofolio Mahasiswa</h4>
                        @if ($allPortfolioItems->isEmpty())
                            <div class="alert alert-secondary text-center">Mahasiswa ini belum menambahkan item portofolio.
                            </div>
                        @else
                            <div class="row">
                                @foreach ($allPortfolioItems as $portfolio)
                                    <div class="col-md-6 card-portfolio-item">
                                        <div class="card detail-card h-100">
                                            <div class="card-header py-2 px-3">
                                                <h6 class="mb-0 portfolio-title-clean">{{ $portfolio->judul_portofolio }}
                                                </h6>
                                            </div>
                                            <div class="card-body py-2 px-3">
                                                <p class="small text-muted mb-1">
                                                    {{ Str::limit($portfolio->deskripsi_portofolio, 70) }}</p>
                                                <p class="small mb-1">
                                                    <strong>Tipe:</strong> <span
                                                        class="badge custom-badge bg-default">{{ ucfirst($portfolio->tipe_portofolio) }}</span>
                                                    @if (in_array($portfolio->tipe_portofolio, ['url', 'video']))
                                                        <a href="{{ $portfolio->lokasi_file_atau_url }}" target="_blank"
                                                            class="ms-2 small portfolio-link-clean"><i
                                                                class="fas fa-external-link-alt"></i> Kunjungi</a>
                                                    @elseif(in_array($portfolio->tipe_portofolio, ['file', 'gambar']))
                                                        <a href="{{ asset('storage/' . $portfolio->lokasi_file_atau_url) }}"
                                                            target="_blank" class="ms-2 small portfolio-link-clean"><i
                                                                class="fas fa-download"></i> Lihat</a>
                                                    @endif
                                                </p>
                                                @if ($portfolio->tanggal_pengerjaan_selesai)
                                                    <p class="small mb-0 text-muted">Selesai:
                                                        {{ Carbon::parse($portfolio->tanggal_pengerjaan_selesai)->isoFormat('MMM YYYY') }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Kolom Kanan: Skill yang Dibutuhkan Lowongan & Aksi Industri --}}
                    <div class="col-lg-5 ps-lg-4">
                        <div class="position-sticky" style="top: 20px;">
                            <h4 class="section-title-clean">Keahlian yang Dibutuhkan Lowongan</h4>
                            @if ($pengajuan->lowongan->lowonganSkill->isEmpty())
                                <div class="alert alert-secondary text-center">Lowongan ini tidak mencantumkan skill
                                    spesifik.</div>
                            @else
                                <ul class="list-group list-group-flush mb-3">
                                    @foreach ($pengajuan->lowongan->lowonganSkill as $lowSkill)
                                        <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                            <span><i
                                                    class="fas fa-check-circle text-muted me-2"></i>{{ optional($lowSkill->skill)->skill_nama ?? 'N/A' }}</span>
                                            <span
                                                class="badge custom-badge bg-level">{{ $lowSkill->level_kompetensi }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <h4 class="section-title-clean mt-4">Tindakan Pengajuan</h4>
                            <div class="card detail-card action-panel">
                                <div class="card-body text-center">
                                    <p class="mb-2">Status Pengajuan Saat Ini:
                                        @php
                                            $currentPengajuanStatus = strtolower($pengajuan->status);
                                            $pengajuanStatusClass = 'bg-default'; // Default untuk status 'belum'
                                            if ($currentPengajuanStatus === 'diterima') {
                                                $pengajuanStatusClass = 'bg-valid';
                                            } elseif ($currentPengajuanStatus === 'ditolak') {
                                                $pengajuanStatusClass = 'bg-invalid';
                                            } elseif ($currentPengajuanStatus === 'belum') {
                                                $pengajuanStatusClass = 'bg-pending';
                                            } // Atau 'bg-warning'
                                        @endphp
                                        <span
                                            class="badge custom-badge {{ $pengajuanStatusClass }} fs-6">{{ ucfirst($pengajuan->status) }}</span>
                                    </p>
                                    <hr>

                                    {{-- Tampilkan tombol aksi hanya jika status pengajuan masih 'belum' --}}
                                    @if (strtolower($pengajuan->status) === 'belum')
                                        <p class="mb-2 fw-bold">Ubah Status Menjadi:</p>
                                        <div class="d-grid gap-2">
                                            <form
                                                action="{{ route('industri.lowongan.pengajuan.terima', $pengajuan->pengajuan_id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin MENERIMA pengajuan mahasiswa ini?');">
                                                @csrf
                                                <button type="submit" class="btn btn-action-main w-100 mb-2">
                                                    <i class="fas fa-user-check me-1"></i> Terima Pengajuan
                                                </button>
                                            </form>

                                            <form
                                                action="{{ route('industri.lowongan.pengajuan.tolak', $pengajuan->pengajuan_id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK pengajuan mahasiswa ini?');">
                                                @csrf
                                                {{-- AKTIFKAN INPUT ALASAN PENOLAKAN --}}
                                                <div class="mb-2 text-start">
                                                    <label for="alasan_penolakan_{{ $pengajuan->pengajuan_id }}"
                                                        class="form-label small text-muted">Alasan Penolakan
                                                        (Opsional):</label>
                                                    <textarea name="alasan_penolakan" id="alasan_penolakan_{{ $pengajuan->pengajuan_id }}"
                                                        class="form-control form-control-sm @error('alasan_penolakan') is-invalid @enderror" rows="3"
                                                        placeholder="Berikan alasan jika perlu...">{{ old('alasan_penolakan') }}</textarea>
                                                    @error('alasan_penolakan')
                                                        <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <button type="submit" class="btn btn-action-danger w-100">
                                                    <i class="fas fa-user-times me-1"></i> Tolak Pengajuan
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <p class="text-muted">Status pengajuan ini sudah final
                                            ({{ ucfirst($pengajuan->status) }}).</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // JS tidak ada yang spesifik untuk styling ini, hanya untuk fungsionalitas jika ada
    </script>
@endpush
