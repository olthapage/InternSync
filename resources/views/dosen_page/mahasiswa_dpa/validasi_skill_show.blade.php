@extends('layouts.template')

@section('title', 'Validasi Skill - ' . $mahasiswa->nama_lengkap)

@push('css')
    <style>
        :root {
            --app-border-color: #e0e0e0;
            /* Warna border abu-abu sedikit lebih soft */
            --app-text-muted: #6c757d;
            --app-text-dark: #212529;
            --app-bg-light-section: #f9f9f9;
            /* Background untuk section/card yang lebih soft */
            --app-success-color: #198754;
            /* Warna hijau utama */
            --app-primary-text-color: #0d6efd;
            --app-info-badge-bg: #0dcaf0;
            --app-pending-badge-bg: #ffc107;
            --app-pending-badge-text: #212529;
            /* Teks lebih gelap untuk kontras di kuning */
            --app-invalid-badge-bg: #dc3545;
        }

        .main-card-validation {
            border: 1px solid var(--app-border-color);
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .05);
            /* Shadow lebih tipis */
        }

        .profile-header-validation {
            background-color: #fff;
            padding: 1.5rem;
            border-bottom: 1px solid var(--app-border-color);
            margin-bottom: 1.5rem;
            /* Kurangi margin bawah */
        }

        .profile-avatar-validation {
            width: 80px;
            /* Sedikit lebih kecil agar tidak dominan */
            height: 80px;
            object-fit: cover;
            border: 2px solid var(--app-border-color);
        }

        .section-title-validation {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            color: var(--app-text-dark);
            font-weight: 500;
            /* Sedikit lebih ringan dari 600 */
            font-size: 1.15rem;
            /* Sedikit lebih kecil */
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--app-success-color);
            /* Aksen hijau */
        }

        .section-title-validation:first-of-type {
            /* Hapus margin atas untuk judul section pertama */
            margin-top: 0;
        }

        .skill-validation-item-card {
            background-color: #fff;
            border: 1px solid var(--app-border-color);
            margin-bottom: 1rem;
            border-radius: .375rem;
            box-shadow: none;
        }

        .skill-validation-item-card.is-invalid-form {
            /* Untuk menandai jika ada error validasi di form ini */
            border-color: var(--app-invalid-badge-bg);
        }

        .skill-validation-item-card .card-body {
            padding: 1rem 1.25rem;
        }

        .portfolio-list-validation {
            list-style: none;
            padding-left: 0;
            margin-top: 0.75rem;
        }

        .portfolio-list-validation li {
            background-color: var(--app-bg-light-section);
            border: 1px solid #e7e7e7;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.5rem;
            border-radius: .25rem;
            font-size: 0.875rem;
        }

        .portfolio-title-validation {
            font-weight: 500;
        }

        .portfolio-type-validation {
            font-size: 0.8em;
            color: var(--app-text-muted);
        }

        .portfolio-link-validation a {
            word-break: break-all;
            font-size: 0.85em;
            text-decoration: none;
            color: var(--app-primary-text-color);
        }

        .portfolio-link-validation a:hover {
            text-decoration: underline;
        }

        .badge.status-badge-validation {
            font-size: 0.75rem;
            /* Ukuran badge diseragamkan */
            padding: .35em .6em;
            font-weight: 500;
            color: white;
            vertical-align: middle;
        }

        .badge.bg-level-validation {
            background-color: var(--app-info-badge-bg) !important;
        }

        .badge.bg-valid-validation {
            background-color: var(--app-success-color) !important;
        }

        .badge.bg-pending-validation {
            background-color: var(--app-pending-badge-bg) !important;
            color: var(--app-pending-badge-text) !important;
        }

        .badge.bg-invalid-validation {
            background-color: var(--app-invalid-badge-bg) !important;
        }

        .badge.bg-secondary-validation {
            background-color: #6c757d !important;
        }

        /* Untuk status default jika ada */

        .validation-form-column {
            background-color: var(--app-bg-light-section);
            padding: 1.25rem;
            border-radius: .25rem;
        }

        .validation-form-column .form-label {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .validation-form-column .form-select,
        .validation-form-column .form-control {
            font-size: 0.875rem;
        }

        .btn-save-validation {
            background-color: var(--app-success-color);
            border-color: var(--app-success-color);
            color: white;
        }

        .btn-save-validation:hover {
            background-color: #146c43;
            border-color: #13653f;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-3">
        <div class="row mb-3">
            <div class="col-12">
                @php
                    // Tentukan URL dan Teks Tombol berdasarkan parameter 'from'
                    $backUrl = route('home'); // Tujuan default jika 'from' tidak ada
                    $backText = 'Kembali ke Dashboard';

                    if (request('from') === 'validasi') {
                        // Jika datang dari halaman validasi, arahkan kembali ke sana
                        // PASTIKAN nama route ini benar sesuai dengan file web.php Anda
                        $backUrl = route('dosen.mahasiswa-dpa.index');
                        $backText = 'Kembali ke Validasi Skill';
                    }
                @endphp

                <a href="{{ $backUrl }}" class="btn btn-sm btn-outline-dark">
                    <i class="fas fa-arrow-left me-1"></i> {{ $backText }}
                </a>
            </div>
        </div>

        {{-- Notifikasi Sukses/Error Global --}}
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

        <div class="card main-card-validation">
            <div class="card-body p-lg-4">
                {{-- Header Informasi Mahasiswa --}}
                <div class="profile-header-validation rounded mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-auto text-center mb-3 mb-md-0">
                            <img src="{{ optional($mahasiswa)->foto ? asset('storage/mahasiswa/foto/' . $mahasiswa->foto) : asset('assets/default-profile.png') }}"
                                alt="Foto Mahasiswa" class="profile-avatar-validation rounded-circle">
                        </div>
                        <div class="col-md">
                            <h4 class="mb-1">{{ $mahasiswa->nama_lengkap ?? 'Nama Mahasiswa' }}</h4>
                            <p class="text-muted mb-1"><i class="fas fa-id-card fa-fw me-1"></i>NIM:
                                {{ $mahasiswa->nim ?? '-' }}</p>
                            <p class="text-muted mb-0"><i class="fas fa-graduation-cap fa-fw me-1"></i>Prodi:
                                {{ optional($mahasiswa->prodi)->nama_prodi ?? '-' }}</p>
                        </div>
                        {{-- Informasi DPA bisa ditambahkan di sini jika perlu --}}
                        {{-- <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <h6 class="mb-1 text-muted">Dosen Penasehat Akademik:</h6>
                        <p class="fw-bold mb-0">{{ $dpa->nama_lengkap }}</p>
                    </div> --}}
                    </div>
                </div>

                <h4 class="section-title-validation">Daftar Keahlian Mahasiswa</h4>
                @if ($skillsForValidation->isEmpty())
                    <div class="alert alert-secondary text-center">
                        <i class="fas fa-info-circle me-1"></i> Mahasiswa ini belum menambahkan skill apapun untuk
                        divalidasi.
                    </div>
                @else
                    @foreach ($skillsForValidation as $index => $mskill)
                        <div
                            class="card skill-validation-item-card {{ session('error_skill_id') == $mskill->mahasiswa_skill_id ? 'border-danger' : '' }}">
                            <div class="card-body">
                                <form
                                    action="{{ route('dosen.mahasiswa-dpa.skill.update_validasi', $mskill->mahasiswa_skill_id) }}"
                                    method="POST">
                                    @csrf
                                    <div class="row">
                                        {{-- Kolom Kiri: Detail Skill & Portofolio --}}
                                        <div class="col-md-7">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="mb-0">{{ $index + 1 }}.
                                                    {{ optional($mskill->detailSkill)->skill_nama ?? 'Tidak diketahui' }}
                                                </h5>
                                                <div>
                                                    <span class="badge custom-badge bg-level me-1"
                                                        title="Level diajukan mahasiswa">{{ $mskill->level_kompetensi }}</span>
                                                </div>
                                            </div>
                                            <p class="mb-1 small text-muted">
                                                Status Verifikasi Saat Ini:
                                                @php
                                                    $currentStatusClass = 'bg-secondary-validation'; // Default untuk Pending
                                                    if ($mskill->status_verifikasi === 'Valid') {
                                                        $currentStatusClass = 'bg-valid-validation';
                                                    }
                                                    if ($mskill->status_verifikasi === 'Invalid') {
                                                        $currentStatusClass = 'bg-invalid-validation';
                                                    }
                                                    if ($mskill->status_verifikasi === 'Pending') {
                                                        $currentStatusClass = 'bg-pending-validation';
                                                    }
                                                @endphp
                                                <span
                                                    class="badge custom-badge {{ $currentStatusClass }}">{{ $mskill->status_verifikasi }}</span>
                                            </p>

                                            @if ($mskill->linkedPortofolios->isNotEmpty())
                                                <p class="mt-2 mb-1 text-xs text-uppercase fw-bold text-muted">Bukti
                                                    Portofolio Terkait:</p>
                                                <ul class="portfolio-list-clean">
                                                    {{-- $portfolioLink di sini adalah instance dari PortofolioMahasiswa --}}
                                                    @foreach ($mskill->linkedPortofolios as $portfolioItem)
                                                        <li>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    {{-- Akses langsung atribut dari $portfolioItem --}}
                                                                    <span
                                                                        class="portfolio-title-clean">{{ $portfolioItem->judul_portofolio }}</span>
                                                                    <span class="portfolio-type-clean">
                                                                        ({{ ucfirst($portfolioItem->tipe_portofolio) }})
                                                                    </span>
                                                                </div>
                                                                <span class="portfolio-link-clean">
                                                                    @if (in_array($portfolioItem->tipe_portofolio, ['url', 'video']))
                                                                        <a href="{{ $portfolioItem->lokasi_file_atau_url }}"
                                                                            target="_blank"
                                                                            class="btn btn-sm btn-outline-dark py-0 px-1"
                                                                            title="Lihat Link"><i
                                                                                class="fas fa-external-link-alt"></i></a>
                                                                    @elseif(in_array($portfolioItem->tipe_portofolio, ['file', 'gambar']))
                                                                        <a href="{{ asset('storage/' . $portfolioItem->lokasi_file_atau_url) }}"
                                                                            target="_blank"
                                                                            class="btn btn-sm btn-outline-dark py-0 px-1"
                                                                            title="Lihat File"><i
                                                                                class="fas fa-download"></i></a>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            {{-- Akses data pivot melalui atribut 'pivot' pada objek $portfolioItem --}}
                                                            @if ($portfolioItem->pivot->deskripsi_penggunaan_skill)
                                                                <p class="small text-muted mt-1 mb-0 fst-italic">
                                                                    "{{ $portfolioItem->pivot->deskripsi_penggunaan_skill }}"
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
                                        {{-- Kolom Kanan: Form Validasi DPA --}}
                                        <div class="col-md-5 border-start-md ps-md-3 mt-3 mt-md-0 validation-form-column">
                                            <p class="fw-bold mb-2 text-center">Form Validasi DPA</p>
                                            <div class="mb-3">
                                                <label for="level_kompetensi_{{ $mskill->mahasiswa_skill_id }}"
                                                    class="form-label">Validasi Level Kompetensi <span
                                                        class="text-danger">*</span></label>
                                                <select name="level_kompetensi"
                                                    id="level_kompetensi_{{ $mskill->mahasiswa_skill_id }}"
                                                    class="form-select @error('level_kompetensi', $mskill->mahasiswa_skill_id . '_errors') is-invalid @enderror"
                                                    required>
                                                    <option value="Beginner"
                                                        {{ (old('level_kompetensi', $mskill->level_kompetensi) ?? $mskill->level_kompetensi) == 'Beginner' ? 'selected' : '' }}>
                                                        Beginner</option>
                                                    <option value="Intermediate"
                                                        {{ (old('level_kompetensi', $mskill->level_kompetensi) ?? $mskill->level_kompetensi) == 'Intermediate' ? 'selected' : '' }}>
                                                        Intermediate</option>
                                                    <option value="Expert"
                                                        {{ (old('level_kompetensi', $mskill->level_kompetensi) ?? $mskill->level_kompetensi) == 'Expert' ? 'selected' : '' }}>
                                                        Expert</option>
                                                </select>
                                                @error('level_kompetensi', $mskill->mahasiswa_skill_id . '_errors')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="status_verifikasi_{{ $mskill->mahasiswa_skill_id }}"
                                                    class="form-label">Ubah Status Verifikasi <span
                                                        class="text-danger">*</span></label>
                                                <select name="status_verifikasi"
                                                    id="status_verifikasi_{{ $mskill->mahasiswa_skill_id }}"
                                                    class="form-select @error('status_verifikasi', $mskill->mahasiswa_skill_id . '_errors') is-invalid @enderror"
                                                    required>
                                                    <option value="Pending"
                                                        {{ (old('status_verifikasi', $mskill->status_verifikasi) ?? $mskill->status_verifikasi) == 'Pending' ? 'selected' : '' }}>
                                                        Pending</option>
                                                    <option value="Valid"
                                                        {{ (old('status_verifikasi', $mskill->status_verifikasi) ?? $mskill->status_verifikasi) == 'Valid' ? 'selected' : '' }}>
                                                        Valid</option>
                                                    <option value="Invalid"
                                                        {{ (old('status_verifikasi', $mskill->status_verifikasi) ?? $mskill->status_verifikasi) == 'Invalid' ? 'selected' : '' }}>
                                                        Invalid</option>
                                                </select>
                                                @error('status_verifikasi', $mskill->mahasiswa_skill_id . '_errors')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-save-validation w-100"><i
                                                    class="fas fa-save me-1"></i> Simpan Validasi Skill</button>
                                        </div>
                                    </div>
                                </form>
                                {{-- Menampilkan error validasi per form skill --}}
                                @if (session('error_skill_id') == $mskill->mahasiswa_skill_id && $errors->any())
                                    <div class="alert alert-danger mt-2 py-2 small">
                                        <ul class="mb-0 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            @if (!$loop->last)
                                <hr class="my-3">
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // JS tidak ada yang spesifik dibutuhkan untuk styling ini.
        // Pastikan Bootstrap JS dimuat untuk fungsionalitas alert dismissal dan modal.
    </script>
@endpush
