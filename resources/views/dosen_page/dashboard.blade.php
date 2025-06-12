@extends('layouts.template')

@php
    $roleDosen = $dosen->role_dosen;
    $namaDosen = $dosen->nama_lengkap;
@endphp

@push('css')
    <style>
        #table_mahasiswa_dpa th,
        #table_mahasiswa_dpa td {
            vertical-align: middle;
        }
        .avatar.avatar-sm { /* Ukuran avatar di tabel */
            width: 38px !important;
            height: 38px !important;
        }
        .table th, .table td { /* Mencegah text wrap agar rapi */
            white-space: nowrap;
        }
        .badge { /* Ukuran badge agar lebih pas */
            font-size: 0.85em;
            padding: 0.4em 0.65em;
        }

        /* ===== PERUBAHAN DI SINI ===== */
        /* Menambahkan class untuk membuat kontainer tabel bisa di-scroll */
        .table-container-scrollable {
            max-height: 400px; /* Anda bisa sesuaikan tinggi maksimalnya */
            overflow-y: auto;  /* Tambahkan scroll vertikal jika konten lebih tinggi dari max-height */
        }
    </style>
@endpush

@section('content')
    <div class="row">
        {{-- HEADER (Tetap Sama) --}}
        <div class="col-12">
            <div class="card bg-gradient-primary shadow-primary border-radius-lg mt-2"
                style="background-image: url('{{ asset('images/dosen.jpg') }}'); background-size: cover; background-position: center;">
                <span class="mask bg-gradient-dark opacity-6 border-radius-lg"></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-md-8 d-flex align-items-center">
                            <div>
                                @if ($roleDosen == 'pembimbing')
                                    <h5 class="text-white mb-0">Dashboard Dosen Pembimbing</h5>
                                    <h2 class="text-white font-weight-bolder">Manajemen Bimbingan Magang</h2>
                                @elseif ($roleDosen == 'dpa')
                                    <h5 class="text-white mb-0">Dashboard Dosen Pembimbing Akademik</h5>
                                    <h2 class="text-white font-weight-bolder">Manajemen Perwalian Mahasiswa</h2>
                                @endif
                                <p class="text-white text-sm mb-0">Selamat datang kembali, {{ $namaDosen }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- KONTEN UTAMA --}}
        <div class="col-lg-12 mt-4">
            {{-- STATS CARDS --}}
            <div class="row">
                @if ($roleDosen == 'pembimbing')
                    {{-- Kartu Statistik untuk Dosen Pembimbing --}}
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-sm mb-1 text-dark font-weight-bold">Total Bimbingan</p>
                                        <h4 class="font-weight-bolder text-dark mb-0">{{ $totalBimbingan }}</h4>
                                    </div>
                                    <div class="icon icon-shape bg-info text-white rounded-circle shadow text-center"><i
                                            class="fas fa-user-graduate"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-sm mb-1 text-dark font-weight-bold">Sedang Magang</p>
                                        <h4 class="font-weight-bolder text-dark mb-0">{{ $sedangMagang }}</h4>
                                    </div>
                                    <div class="icon icon-shape bg-success text-white rounded-circle shadow text-center"><i
                                            class="fas fa-user-check"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-sm mb-1 text-dark font-weight-bold">Selesai Magang</p>
                                        <h4 class="font-weight-bolder text-dark mb-0">{{ $selesaiMagang }}</h4>
                                    </div>
                                    <div class="icon icon-shape bg-primary text-white rounded-circle shadow text-center"><i
                                            class="fas fa-check-double"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($roleDosen == 'dpa')
                    {{-- Kartu Statistik untuk DPA --}}
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-sm mb-1 text-dark font-weight-bold">Total Mhs Perwalian</p>
                                        <h4 class="font-weight-bolder text-dark mb-0">{{ $totalPerwalian }}</h4>
                                    </div>
                                    <div class="icon icon-shape bg-secondary text-white rounded-circle shadow text-center">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-sm mb-1 text-dark font-weight-bold">Validasi Skill Tertunda</p>
                                        <h4 class="font-weight-bolder text-dark mb-0">{{ $skillMenungguValidasi }}</h4>
                                    </div>
                                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow text-center"><i
                                            class="fas fa-tasks"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- DETAIL KONTEN (Tabel & Timeline) --}}
        <div class="row mt-2">
            @if ($roleDosen == 'pembimbing')
                {{-- Konten Detail untuk Pembimbing --}}
                <div class="col-lg-12">
                    <div class="card h-100">
                        <div class="card-header pb-0 p-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Mahasiswa Bimbingan Magang</h6>
                            <a href="{{ route('mahasiswa-bimbingan.index') }}" class="btn btn-sm bg-gradient-primary mb-0">Lihat Semua</a>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive table-container-scrollable">
                                {{-- Tabel Mahasiswa Bimbingan --}}
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Mahasiswa
                                            </th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Tempat
                                                Magang</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">
                                                Status</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($mahasiswaBimbinganList as $mhs)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div>
                                                            {{-- Ganti dengan foto dinamis jika ada --}}
                                                            <img src=" {{ $foto = $mhs->foto ? asset('storage/mahasiswa/foto/' . $mhs->foto) : asset('assets/default-profile.png');}}"
                                                                class="avatar avatar-sm me-3" alt="user1">
                                                        </div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $mhs->nama_lengkap }}</h6>
                                                            <p class="text-xs text-secondary mb-0">NIM: {{ $mhs->nim }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        {{ optional(optional(optional($mhs->magang)->lowongan)->industri)->industri_nama ?? '-' }}
                                                    </p>
                                                    <p class="text-xs text-secondary mb-0">
                                                        {{ optional(optional($mhs->magang)->lowongan)->judul_lowongan ?? 'Belum ada data magang' }}
                                                    </p>
                                                </td>
                                                <td class="align-middle text-center">
                                                    @php
                                                        $status = optional($mhs->magang)->status;
                                                        $badgeClass = 'secondary';
                                                        if ($status == 'sedang') {
                                                            $badgeClass = 'success';
                                                        }
                                                        if ($status == 'selesai') {
                                                            $badgeClass = 'info';
                                                        }
                                                    @endphp
                                                    <span
                                                        class="badge badge-sm bg-gradient-{{ $badgeClass }}">{{ ucfirst($status) ?? 'N/A' }}</span>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <button onclick="modalAction('{{ route('mahasiswa-bimbingan.show', $mhs->mahasiswa_id) }}')"
                                                        class="btn btn-xs bg-gradient-info mb-0"><i class="fas fa-eye"></i> Detail</button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted p-4">
                                                    Belum ada mahasiswa bimbingan yang terdaftar.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($roleDosen == 'dpa')
                {{-- Konten Detail untuk DPA (Telah disesuaikan) --}}
                <div class="col-lg-8 mb-4">
                    <div class="card h-100">
                        <div class="card-header pb-0 p-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Mahasiswa Perwalian (Terverifikasi)</h6>
                            <a href="{{ route('dosen.mahasiswa-dpa.index') }}" class="btn btn-sm bg-gradient-primary mb-0">Lihat Semua</a>
                        </div>
                        <div class="card-body p-3">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Mahasiswa
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">
                                                Angkatan</th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder">
                                                IPK</th>
                                            <th class="text-end text-uppercase text-secondary text-xxs font-weight-bolder">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($mahasiswaWaliList as $mhs)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div><img
                                                                src="{{ $mhs->foto_url ?? asset('assets/default-profile.png') }}"
                                                                class="avatar avatar-sm me-3" alt="user1"></div>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">{{ $mhs->nama_lengkap }}</h6>
                                                            <p class="text-xs text-secondary mb-0">NIM:
                                                                {{ $mhs->nim }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle text-center">
                                                    <p class="text-sm font-weight-bold mb-0">{{ substr($mhs->nim, 0, 2) }}
                                                    </p>
                                                </td>
                                                <td class="align-middle text-center"><span
                                                        class="text-sm font-weight-bold">{{ number_format($mhs->ipk, 2) }}</span>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <a href="{{ route('dosen.mahasiswa-dpa.validasi.skill.show', ['mahasiswa' => $mhs->mahasiswa_id, 'from' => 'dashboard']) }}"
                                                        class="btn btn-xs bg-gradient-info mb-0">Detail</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted p-4">Belum ada mahasiswa
                                                    perwalian yang terverifikasi.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KARTU BARU: Skill Terbaru Diunggah --}}
                <div class="col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">Skill Terbaru Diunggah</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="timeline timeline-one-side">
                                @forelse ($skillTerbaru as $skill)
                                    <div class="timeline-block mb-3">
                                        <span class="timeline-step">
                                            @php
                                                $statusClass = 'text-secondary';
                                                if ($skill->status_verifikasi == 'Valid') {
                                                    $statusClass = 'text-success fas fa-check';
                                                }
                                                if ($skill->status_verifikasi == 'Pending') {
                                                    $statusClass = 'text-success fas fa-spinner';
                                                }
                                                if ($skill->status_verifikasi == 'Invalid') {
                                                    $statusClass = 'text-success fas fa-times';
                                                }
                                            @endphp
                                            <i class="{{ $statusClass }} text-gradient"></i>
                                        </span>
                                        <div class="timeline-content">
                                            <h6 class="text-dark text-sm font-weight-bold mb-0">
                                                {{ optional($skill->detailSkill)->skill_nama }}</h6>
                                            <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                                {{ optional($skill->mahasiswa)->nama_lengkap }}</p>
                                            <p class="text-xs text-secondary mb-0">
                                                {{ $skill->created_at->diffForHumans() }}</p>
                                            <span class="badge badge-dot me-4">
                                                <i
                                                    class="bg-{{ str_replace(' ', '-', strtolower($skill->status_verifikasi)) == 'valid' ? 'success' : (strtolower($skill->status_verifikasi) == 'belum diverifikasi' ? 'warning' : 'danger') }}"></i>
                                                <span class="text-dark text-xs">{{ $skill->status_verifikasi }}</span>
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted text-sm text-center">Tidak ada skill yang baru diunggah.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    {{-- Script untuk Chart Pembimbing, karena Chart DPA dihapus --}}
    @if ($roleDosen == 'pembimbing')
        <script src="{{ asset('softTemplate/assets/js/plugins/chartjs.min.js') }}"></script>
        <script>
            function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }
        </script>
    @endif
@endpush
