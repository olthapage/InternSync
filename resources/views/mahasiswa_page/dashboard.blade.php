@extends('layouts.template')
@section('content')
    <div class="row">
        <!-- Header -->
        <div class="col-12">
            <div class="card bg-gradient-primary shadow-primary border-radius-xl mt-4">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-8 d-flex align-items-center">
                            <div>
                                <h5 class="text-white mb-0">Dashboard Mahasiswa</h5>
                                <h2 class="text-white font-weight-bolder">Magang & Karir</h2>
                                <p class="text-white text-sm mb-0">Selamat datang kembali, {{ Auth::user()->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Utama -->
        <div class="col-lg-12 mt-4">
            <div class="row">
                <!-- Lowongan Tersedia -->
                <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">
                    <div class="card border shadow-sm p-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-sm mb-1 text-capitalize font-weight-bold">Lowongan</p>
                                <h4 class="font-weight-bolder mb-0 text-dark">{{ $lowongan }}</h4>
                            </div>
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow text-center">
                                <i class="fas fa-briefcase text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- Perusahaan -->
        <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">
            <div class="card border shadow-sm p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-1 text-capitalize font-weight-bold">Perusahaan</p>
                        <h4 class="font-weight-bolder mb-0 text-dark">{{ $industri }}</h4>
                    </div>
                    <div class="icon icon-shape bg-secondary text-white rounded-circle shadow text-center">
                        <i class="fas fa-building text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mahasiswa Magang -->
        <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">
            <div class="card border shadow-sm p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-1 text-capitalize font-weight-bold">Mahasiswa Magang</p>
                        <h4 class="font-weight-bolder mb-0 text-dark">{{ $mhsMagang }}</h4>
                    </div>
                    <div class="icon icon-shape bg-success text-white rounded-circle shadow text-center">
                        <i class="fas fa-users text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Mahasiswa -->
        <div class="col-xl-3 col-md-6">
            <div class="card border shadow-sm p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-sm mb-1 text-capitalize font-weight-bold">Total Mahasiswa</p>
                        <h4 class="font-weight-bolder mb-0 text-dark">{{ $mhsCount }}
                    </div>
                    <div class="icon icon-shape bg-warning text-white rounded-circle shadow text-center">
                        <i class="fas fa-user-graduate text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


        <!-- Status Magang -->
        <div class="col-lg-8 col-md-12 mt-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Status Magang Anda</h6>
                        @if(Auth::user()->mahasiswa && Auth::user()->mahasiswa->status)
                            <span class="badge bg-gradient-success">Sedang Magang</span>
                        @else
                            <div class="text-end">
                                <span class="badge bg-gradient-warning mb-2">Belum Magang</span><br>
                            </div>
                        @endif
                    </div>
                </div>
            <div class="card-body p-3 bg-white">
                @if(Auth::user()->mahasiswa && Auth::user()->mahasiswa->status)
                    <div class="alert alert-success">
                        <div class="row">
                            <div class="col-md-8">
                                <h6>Anda sedang dalam program magang</h6>
                                <p class="text-sm mb-0">Jangan lupa mengisi logbook secara berkala</p>
                            </div>
                            <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
                                <a href="{{ route('logbook.index') }}" class="btn btn-sm btn-success mb-0">Logbook</a>
                            </div>
                        </div>
                    </div>
                @else
                <div class="card card-body border card-plain border-radius-lg">
                    <h6>Anda belum memiliki program magang</h6>
                    <p class="text-sm mb-3">Temukan lowongan magang yang sesuai dengan minat Anda</p>
                    <div class="col-md-8">
                        <a href="{{ route('mahasiswa.lowongan.index') }}" class="btn btn-sm btn-warning text-white">Cari Magang</a>
                    </div>
                </div>
            @endif
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                        <div class="card-body p-3">
                            <h6 class="text-dark">Progress Magang</h6>
                            <div class="progress mt-3">
                                <div class="progress-bar bg-gradient-info" role="progressbar"
                                    style="width: {{ Auth::user()->mahasiswa && Auth::user()->mahasiswa->progress ? Auth::user()->mahasiswa->progress : '0' }}%;"
                                    aria-valuenow="{{ Auth::user()->mahasiswa && Auth::user()->mahasiswa->progress ? Auth::user()->mahasiswa->progress : '0' }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <p class="text-sm mt-2 mb-0 text-dark">
                                {{ Auth::user()->mahasiswa && Auth::user()->mahasiswa->progress ? Auth::user()->mahasiswa->progress : '0' }}% selesai
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-md-0 mt-4">
                    <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                        <div class="card-body p-3">
                            <h6 class="text-dark">Aktivitas Terakhir</h6>
                            <p class="text-sm mt-2 mb-0 text-dark">
                                @if(Auth::user()->mahasiswa && Auth::user()->mahasiswa->last_activity)
                                    {{ Auth::user()->mahasiswa->last_activity }}
                                @else
                                    Belum ada aktivitas
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Quick Actions -->
        <div class="col-lg-4 col-md-12 mt-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Aksi Cepat</h6>
                </div>
                <div class="card-body p-3">
                    <div class="list-group">
                        <a href="{{ route('mahasiswa.lowongan.index') }}" class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg hover-scale">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-info shadow text-center">
                                    <i class="fas fa-search text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">Cari Lowongan</h6>
                                    <p class="text-xs text-secondary mb-0">Temukan magang yang sesuai</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-sm opacity-8"></i>
                        </a>
                        <a href="{{ route('profile.index') }}" class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg hover-scale">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-success shadow text-center">
                                    <i class="fas fa-user-edit text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">Lengkapi Profil</h6>
                                    <p class="text-xs text-secondary mb-0">Perbarui data diri Anda</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-sm opacity-8"></i>
                        </a>
                        @if(Auth::user()->mahasiswa && Auth::user()->mahasiswa->status)
                        <a href="{{ route('logbook.index') }}" class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg hover-scale">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-warning shadow text-center">
                                    <i class="fas fa-book text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">Logbook</h6>
                                    <p class="text-xs text-secondary mb-0">Catat aktivitas magang</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-sm opacity-8"></i>
                        </a>
                        @endif
                        <a href="{{ route('mahasiswa.pengajuan.index') }}" class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg hover-scale">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-danger shadow text-center">
                                    <i class="fas fa-file-upload text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">Unggah Dokumen</h6>
                                    <p class="text-xs text-secondary mb-0">CV, sertifikat, dll</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-sm opacity-8"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengumuman & Informasi -->
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Pengumuman Terbaru</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-6 mb-md-0 mb-4">
                            <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-lg">
                                    <i class="fas fa-bullhorn text-white opacity-10"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Pendaftaran Magang Semester Genap</h6>
                                    <p class="text-sm mb-0">Batas akhir pendaftaran: 30 Januari 2023</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-lg">
                                    <i class="fas fa-calendar-check text-white opacity-10"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-1">Workshop Persiapan Magang</h6>
                                    <p class="text-sm mb-0">Jumat, 20 Januari 2023 - 13.00 WIB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Inisialisasi tooltip
        if (typeof $ !== 'undefined') {
            $(document).ready(function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
            });
        }
    </script>
@endsection
