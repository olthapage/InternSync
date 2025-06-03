@extends('layouts.template')

@section('title', 'Review Pendaftar: ' . (optional($pengajuan->mahasiswa)->nama_lengkap ?? 'Mahasiswa') . ' - Lowongan: ' . (optional($pengajuan->lowongan)->judul_lowongan ?? ''))

@push('css')
<style>
    :root {
        --app-border-color: #e9ecef;
        --app-text-muted: #6c757d;
        --app-text-dark: #212529;
        --app-bg-light-custom: #f8f9fa;
        --app-success-color: #198754;
        --app-primary-text-color: #0d6efd;
        --app-info-badge-bg: #0dcaf0;
        --app-pending-badge-bg: #ffc107;
        --app-pending-badge-text: #212529;
        --app-invalid-badge-bg: #dc3545;
        --app-danger-color: #dc3545;
    }
    .profile-header-clean { background-color: #fff; padding: 1.5rem; border: 1px solid var(--app-border-color); border-radius: .375rem; margin-bottom: 2rem; }
    .profile-avatar-clean { width: 100px; height: 100px; object-fit: cover; border: 3px solid var(--app-border-color); }
    .section-title-clean { margin-top: 2rem; margin-bottom: 1.25rem; color: var(--app-text-dark); font-weight: 600; font-size: 1.25rem; padding-bottom: 0.6rem; border-bottom: 3px solid var(--app-success-color); }
    .section-title-clean:first-of-type { margin-top: 0; }
    .detail-card { border: 1px solid var(--app-border-color); box-shadow: none; margin-bottom: 1rem; background-color: #fff; }
    .detail-card .card-header { background-color: var(--app-bg-light-custom); border-bottom: 1px solid var(--app-border-color); padding: 0.75rem 1.25rem; }
    .detail-card .card-body { padding: 1.25rem; }
    .portfolio-list-clean { list-style: none; padding-left: 0; margin-top: 0.5rem; }
    .portfolio-list-clean li { background-color: var(--app-bg-light-custom); border: 1px solid #e0e5e9; padding: 0.65rem 1rem; margin-bottom: 0.5rem; border-radius: .25rem; }
    .portfolio-title-clean { font-weight: 500; color: var(--app-text-dark); }
    .portfolio-type-clean { font-size: 0.8em; color: var(--app-text-muted); }
    .portfolio-link-clean a { font-size: 0.85em; text-decoration: none; }
    .portfolio-link-clean a:hover { text-decoration: underline; }
    .badge.custom-badge { font-size: 0.8em; padding: .4em .65em; font-weight: 500; color: white; }
    .badge.bg-level { background-color: var(--app-info-badge-bg) !important; }
    .badge.bg-valid { background-color: var(--app-success-color) !important; }
    .badge.bg-pending { background-color: var(--app-pending-badge-bg) !important; color: var(--app-pending-badge-text) !important; }
    .badge.bg-invalid { background-color: var(--app-invalid-badge-bg) !important; }
    .badge.bg-default { background-color: #6c757d !important; }
    .action-panel .card { border: 1px solid var(--app-border-color); box-shadow: none; }
    .action-panel .card-body { background-color: #fff; }
    .btn-action-main { background-color: var(--app-success-color); border-color: var(--app-success-color); color: white; }
    .btn-action-main:hover { background-color: #157347; border-color: #146c43; }
    .btn-action-danger { background-color: var(--app-danger-color); border-color: var(--app-danger-color); color: white; }
    .btn-action-danger:hover { background-color: #bb2d3b; border-color: #b02a37; }

    .info-item { margin-bottom: 0.5rem; }
    .info-item strong { display: inline-block; width: 150px; color: var(--app-text-muted); }
    .info-item span { color: var(--app-text-dark); }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('industri.lowongan.show', $pengajuan->lowongan->lowongan_id) }}" class="btn btn-sm btn-outline-dark">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Detail Lowongan
            </a>
        </div>
    </div>

    {{-- Alert Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
         <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error_form_tolak_pengajuan_id') == $pengajuan->pengajuan_id && $errors->has('alasan_penolakan'))
        <div class="alert alert-danger mt-2 py-2">
            <ul class="mb-0">
                @foreach ($errors->get('alasan_penolakan') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <div class="card border-0 shadow-sm">
        <div class="card-body p-lg-4">
            {{-- Header Informasi Lowongan dan Mahasiswa --}}
            <div class="profile-header-clean mb-4">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center mb-3 mb-md-0">
                        <img src="{{ optional($pengajuan->mahasiswa)->foto ? asset('storage/mahasiswa/' . $pengajuan->mahasiswa->foto) : asset('assets/default-profile.png') }}"
                             alt="Foto Mahasiswa" class="profile-avatar-clean rounded-circle">
                    </div>
                    <div class="col-md-6">
                        <h3 class="mb-1">{{ optional($pengajuan->mahasiswa)->nama_lengkap ?? 'Nama Mahasiswa' }}</h3>
                        <p class="text-muted mb-1"><i class="fas fa-id-card fa-fw me-2"></i>NIM: {{ optional($pengajuan->mahasiswa)->nim ?? '-' }}</p>
                        <p class="text-muted mb-1"><i class="fas fa-envelope fa-fw me-2"></i>Email: {{ optional($pengajuan->mahasiswa)->email ?? '-' }}</p>
                        <p class="text-muted mb-0"><i class="fas fa-graduation-cap fa-fw me-2"></i>Prodi: {{ optional(optional($pengajuan->mahasiswa)->prodi)->nama_prodi ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <h6 class="mb-1 text-muted">Melamar untuk Lowongan:</h6>
                        <p class="fw-bold mb-0" style="color: var(--app-primary-text-color);">{{ optional($pengajuan->lowongan)->judul_lowongan ?? 'Judul Lowongan' }}</p>
                        <p class="text-muted small">{{ optional(optional($pengajuan->lowongan)->industri)->industri_nama ?? 'Nama Industri' }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Kolom Kiri: Detail Mahasiswa, Skill, & Portofolio --}}
                <div class="col-lg-7 border-end-lg pe-lg-4">
                    <h4 class="section-title-clean">Informasi Tambahan Mahasiswa</h4>
                    <div class="card detail-card mb-4">
                        <div class="card-body">
                            <div class="info-item">
                                <strong>IPK:</strong>
                                <span>{{ optional($pengajuan->mahasiswa)->ipk ? number_format($pengajuan->mahasiswa->ipk, 2) : '-' }}</span>
                            </div>
                            <div class="info-item">
                                <strong>Skor AIS:</strong>
                                <span>{{ optional($pengajuan->mahasiswa)->skor_ais ?? 'Belum Dinilai' }}</span>
                            </div>
                            <div class="info-item">
                                <strong>Aktivitas Organisasi:</strong>
                                @php
                                    $org = optional($pengajuan->mahasiswa)->organisasi;
                                    $orgText = 'Tidak Ikut';
                                    if ($org === 'aktif') $orgText = 'Aktif';
                                    elseif ($org === 'sangat_aktif') $orgText = 'Sangat Aktif';
                                @endphp
                                <span>{{ $orgText }}</span>
                            </div>
                            <div class="info-item">
                                <strong>Aktivitas Lomba:</strong>
                                @php
                                    $lomba = optional($pengajuan->mahasiswa)->lomba;
                                    $lombaText = 'Tidak Ikut';
                                    if ($lomba === 'aktif') $lombaText = 'Pernah Ikut/Finalis';
                                    elseif ($lomba === 'sangat_aktif') $lombaText = 'Sering Ikut & Juara';
                                @endphp
                                <span>{{ $lombaText }}</span>
                            </div>
                            <div class="info-item">
                                <strong>Status Kasus Pelanggaran:</strong>
                                @php
                                    $kasus = optional($pengajuan->mahasiswa)->kasus;
                                    $kasusText = 'Tidak Ada';
                                    $kasusClass = 'text-success';
                                    if ($kasus === 'ada') {
                                        $kasusText = 'Ada Kasus';
                                        $kasusClass = 'text-danger fw-bold';
                                    }
                                @endphp
                                <span class="{{ $kasusClass }}">{{ $kasusText }}</span>
                            </div>
                        </div>
                    </div>

                    <h4 class="section-title-clean">Keahlian yang Diajukan (Valid)</h4>
                    @php
                        $validSkills = optional(optional($pengajuan->mahasiswa)->skills)->filter(function ($skill) {
                            return $skill->status_verifikasi === 'Valid';
                        });
                    @endphp
                    @if(!$validSkills || $validSkills->isEmpty())
                        <div class="alert alert-secondary text-center">Mahasiswa ini belum memiliki skill yang tervalidasi.</div>
                    @else
                        @foreach ($validSkills as $mahasiswaSkill)
                            <div class="card detail-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0 me-2">{{ optional(optional($mahasiswaSkill)->detailSkill)->skill_nama ?? 'Skill tidak diketahui' }}</h6>
                                        <div>
                                            <span class="badge custom-badge bg-level me-1">{{ $mahasiswaSkill->level_kompetensi }}</span>
                                            <span class="badge custom-badge bg-valid">{{ $mahasiswaSkill->status_verifikasi }}</span>
                                        </div>
                                    </div>
                                    @if(optional($mahasiswaSkill->linkedPortofolios)->isNotEmpty())
                                        <p class="mt-2 mb-1 text-xs text-uppercase fw-bold text-muted">Bukti Portofolio Terkait:</p>
                                        <ul class="portfolio-list-clean">
                                            @foreach($mahasiswaSkill->linkedPortofolios as $portfolioItem)
                                                <li>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <span class="portfolio-title-clean">{{ $portfolioItem->judul_portofolio }}</span>
                                                            <span class="portfolio-type-clean"> ({{ ucfirst($portfolioItem->tipe_portofolio) }})</span>
                                                        </div>
                                                        <span class="portfolio-link-clean">
                                                            @if(in_array($portfolioItem->tipe_portofolio, ['url', 'video']))
                                                                <a href="{{ $portfolioItem->lokasi_file_atau_url }}" target="_blank" class="btn btn-sm btn-outline-dark py-0 px-1" title="Lihat Link"><i class="fas fa-external-link-alt"></i></a>
                                                            @elseif(in_array($portfolioItem->tipe_portofolio, ['file', 'gambar']))
                                                                <a href="{{ asset('storage/' . $portfolioItem->lokasi_file_atau_url) }}" target="_blank" class="btn btn-sm btn-outline-dark py-0 px-1" title="Lihat File"><i class="fas fa-download"></i></a>
                                                            @endif
                                                        </span>
                                                    </div>
                                                    @if($portfolioItem->pivot && $portfolioItem->pivot->deskripsi_penggunaan_skill)
                                                        <p class="small text-muted mt-1 mb-0 fst-italic">"{{ $portfolioItem->pivot->deskripsi_penggunaan_skill }}"</p>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="small text-muted fst-italic mt-2 mb-0">Tidak ada portofolio yang dikaitkan untuk skill ini.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <h4 class="section-title-clean mt-4">Semua Item Portofolio Mahasiswa</h4>
                     @if($allPortfolioItems->isEmpty())
                        <div class="alert alert-secondary text-center">Mahasiswa ini belum menambahkan item portofolio.</div>
                    @else
                        <div class="row">
                        @foreach($allPortfolioItems as $portfolio)
                            <div class="col-md-6 card-portfolio-item">
                                <div class="card detail-card h-100">
                                     <div class="card-header py-2 px-3">
                                        <h6 class="mb-0 portfolio-title-clean">{{ $portfolio->judul_portofolio }}</h6>
                                    </div>
                                    <div class="card-body py-2 px-3">
                                        <p class="small text-muted mb-1">{{ Str::limit($portfolio->deskripsi_portofolio, 70) }}</p>
                                        <p class="small mb-1">
                                            <strong>Tipe:</strong> <span class="badge custom-badge bg-default">{{ ucfirst($portfolio->tipe_portofolio) }}</span>
                                            @if(in_array($portfolio->tipe_portofolio, ['url', 'video']))
                                                <a href="{{ $portfolio->lokasi_file_atau_url }}" target="_blank" class="ms-2 small portfolio-link-clean"><i class="fas fa-external-link-alt"></i> Kunjungi</a>
                                            @elseif(in_array($portfolio->tipe_portofolio, ['file', 'gambar']))
                                                 <a href="{{ asset('storage/' . $portfolio->lokasi_file_atau_url) }}" target="_blank" class="ms-2 small portfolio-link-clean"><i class="fas fa-download"></i> Lihat</a>
                                            @endif
                                        </p>
                                         @if($portfolio->tanggal_pengerjaan_selesai)
                                            <p class="small mb-0 text-muted">Selesai: {{ $portfolio->tanggal_pengerjaan_selesai->isoFormat('MMM YY') }}</p>
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
                        @if(optional(optional($pengajuan->lowongan)->lowonganSkill)->isEmpty())
                            <div class="alert alert-secondary text-center">Lowongan ini tidak mencantumkan skill spesifik.</div>
                        @else
                            <ul class="list-group list-group-flush mb-3">
                                @foreach($pengajuan->lowongan->lowonganSkill as $lowSkill)
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-check-circle text-muted me-2"></i>{{ optional(optional($lowSkill)->skill)->skill_nama ?? 'N/A' }}</span>
                                    <span class="badge custom-badge bg-level">{{ $lowSkill->level_kompetensi }}</span>
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
                                        $pengajuanStatusClass = 'bg-default';
                                        if ($currentPengajuanStatus === 'diterima') $pengajuanStatusClass = 'bg-valid';
                                        elseif ($currentPengajuanStatus === 'ditolak') $pengajuanStatusClass = 'bg-invalid';
                                        elseif ($currentPengajuanStatus === 'belum') $pengajuanStatusClass = 'bg-pending';
                                    @endphp
                                    <span class="badge custom-badge {{ $pengajuanStatusClass }} fs-6">{{ ucfirst($pengajuan->status) }}</span>
                                </p>
                                <hr>
                                @if (strtolower($pengajuan->status) === 'belum')
                                    <p class="mb-2 fw-bold">Ubah Status Menjadi:</p>
                                    <div class="d-grid gap-2">
                                        <form action="{{ route('industri.lowongan.pengajuan.terima', $pengajuan->pengajuan_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENERIMA pengajuan mahasiswa ini?');">
                                            @csrf
                                            <button type="submit" class="btn btn-action-main w-100 mb-2">
                                                <i class="fas fa-user-check me-1"></i> Terima Pengajuan
                                            </button>
                                        </form>
                                        <form action="{{ route('industri.lowongan.pengajuan.tolak', $pengajuan->pengajuan_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENOLAK pengajuan mahasiswa ini?');">
                                            @csrf
                                            <div class="mb-2 text-start">
                                                <label for="alasan_penolakan_{{ $pengajuan->pengajuan_id }}" class="form-label small text-muted">Alasan Penolakan (Opsional):</label>
                                                <textarea name="alasan_penolakan" id="alasan_penolakan_{{ $pengajuan->pengajuan_id }}" class="form-control form-control-sm @error('alasan_penolakan') is-invalid @enderror" rows="3" placeholder="Berikan alasan jika perlu...">{{ old('alasan_penolakan') }}</textarea>
                                                @error('alasan_penolakan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                            </div>
                                            <button type="submit" class="btn btn-action-danger w-100">
                                                <i class="fas fa-user-times me-1"></i> Tolak Pengajuan
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <p class="text-muted">Status pengajuan ini sudah final ({{ ucfirst($pengajuan->status) }}).</p>
                                     @if(strtolower($pengajuan->status) === 'ditolak' && $pengajuan->alasan_penolakan)
                                        <div class="mt-3 text-start">
                                            <p class="fw-bold mb-1 small text-danger"><i class="fas fa-comment-dots me-1"></i>Alasan Penolakan:</p>
                                            <p class="text-muted small bg-light p-2 rounded fst-italic">"{{ nl2br(htmlspecialchars($pengajuan->alasan_penolakan)) }}"</p>
                                        </div>
                                    @endif
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
    // JS tidak ada yang spesifik dibutuhkan untuk tampilan ini saat ini.
</script>
@endpush

