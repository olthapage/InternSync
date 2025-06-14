@extends('layouts.template')
@section('content')
    <div class="row">
        <!-- Header -->
        <div class="col-12">
            <div class="card bg-gradient-primary shadow-primary border-radius-lg mt-2"
                style="background-image: url('{{ asset('images/mahasiswa.jpg') }}'); background-size: cover; background-position: center;">
                <span class="mask bg-gradient-dark opacity-6 border-radius-lg"></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-md-8 d-flex align-items-center">
                            <div>
                                <h5 class="text-white mb-0">Dashboard Mahasiswa</h5>
                                <h2 class="text-white font-weight-bolder">Magang & Karir</h2>
                                <p class="text-white text-sm mb-0">Selamat datang kembali, {{ Auth::user()->nama_lengkap }}
                                </p>
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
                <div class="col-xl-4 col-md-6 mb-xl-0 mb-4">
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
                <div class="col-xl-4 col-md-6 mb-xl-0 mb-4">
                    <div class="card border shadow-sm p-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-sm mb-1 text-capitalize font-weight-bold">Portofolio</p>
                                <h4 class="font-weight-bolder mb-0 text-dark">{{ $jumlahPortofolio }}</h4>
                            </div>
                            <div class="icon icon-shape bg-secondary text-white rounded-circle shadow text-center">
                                <i class="fas fa-book text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mahasiswa Magang -->
                <div class="col-xl-4 col-md-6 mb-xl-0 mb-4">
                    <div class="card border shadow-sm p-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-sm mb-1 text-capitalize font-weight-bold">Skillmu</p>
                                <h4 class="font-weight-bolder mb-0 text-dark">{{ $jumlahSkill }}</h4>
                            </div>
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow text-center">
                                <i class="fas fa-gears text-white"></i>
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
                        @if (Auth::user()->magang && Auth::user()->magang->status == 'sedang')
                            <span class="badge bg-gradient-info">Sedang Magang</span>
                        @elseif(Auth::user()->magang && Auth::user()->magang->status == 'selesai')
                            <span class="badge bg-gradient-success">Magang Selesai</span>
                        @elseif(Auth::user()->magang && Auth::user()->magang->status == 'belum')
                            <span class="badge bg-gradient-primary">Belum Mulai</span>
                        @else
                            <div class="text-end">
                                <span class="badge bg-gradient-warning mb-2">Belum Magang</span><br>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body p-3 bg-white">
                    @if (Auth::user()->magang && Auth::user()->magang->status == 'sedang')
                        <div class="alert alert-info">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="text-white">Anda sedang dalam program magang</h6>
                                    <p class="text-sm mb-0 text-white">Jangan lupa mengisi logbook secara berkala</p>
                                </div>
                                <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
                                    <a href="{{ route('mahasiswa.magang.index') }}" class="btn btn-sm btn-outline-white mb-0">Logbook</a>
                                </div>
                            </div>
                        </div>
                    @elseif(Auth::user()->magang && Auth::user()->magang->status == 'selesai')
                        <div class="alert alert-success">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="text-white">Apa kata industri</h6>
                                    <p class="text-sm mb-0 text-white">{{ Auth::user()->magang->feedback_industri ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @elseif(Auth::user()->magang && Auth::user()->magang->status == 'belum')
                    <div class="alert alert-primary">
                            <div class="row">
                                <div class="col-md-8">
                                    <h6 class="text-white">Kamu sudah diterima {{ Auth::user()->magang->lowongan->industri->industri_nama ?? '' }} !</h6>
                                    <p class="text-sm mb-0 text-white">Tunggu periode magangmu atau diaktifkan oleh industri</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card card-body border card-plain border-radius-lg">
                            <h6>Anda belum memiliki program magang</h6>
                            <p class="text-sm mb-3">Temukan lowongan magang yang sesuai dengan minat Anda</p>
                            <div class="col-md-8">
                                <a href="{{ route('mahasiswa.lowongan.index') }}"
                                    class="btn btn-sm btn-warning text-white">Cari Magang</a>
                            </div>
                        </div>
                    @endif
                    <div class="row mt-4 px-3">

                            <div class="card card-body border card-plain border-radius-lg p-3">
                                <h6 class="text-dark">Log Harian Terakhir</h6>

                                {{-- Gunakan @forelse untuk loop dan menangani kasus kosong --}}
                                @forelse ($latestLogs as $log)
                                    <div class="border-top pt-2 mt-2">
                                        {{-- Tampilkan tanggal dari LogHarianModel --}}
                                        <p class="text-sm fw-bold mb-1 text-dark">
                                            {{ \Carbon\Carbon::parse($log->tanggal)->isoFormat('dddd, D MMMM YYYY') }}
                                        </p>

                                        {{-- Karena relasi detail adalah hasMany, kita perlu loop lagi --}}
                                        @foreach ($log->detail as $detailItem)
                                            <p class="text-sm mb-0 text-dark">
                                                - {{ $detailItem->isi }}
                                            </p>
                                        @endforeach
                                    </div>
                                @empty
                                    {{-- Ini akan ditampilkan jika $latestLogs kosong --}}
                                    <p class="text-sm mt-2 mb-0 text-dark">
                                        Belum ada log harian yang diisi.
                                    </p>
                                @endforelse

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
                        <a href="{{ route('mahasiswa.lowongan.index') }}"
                            class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg hover-scale">
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
                        <a href="{{ route('profile.index') }}"
                            class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg hover-scale">
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
                        @if (Auth::user()->mahasiswa && Auth::user()->mahasiswa->status)
                            <a href="{{ route('logbook.index') }}"
                                class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg hover-scale">
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
                        <a href="{{ route('mahasiswa.portofolio.index') }}"
                            class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg hover-scale">
                            <div class="d-flex align-items-center">
                                <div class="icon icon-shape icon-sm me-3 bg-gradient-danger shadow text-center">
                                    <i class="fas fa-file-upload text-white opacity-10"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">Unggah Portofolio</h6>
                                    <p class="text-xs text-secondary mb-0">Skill yang kamu miliki</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-sm opacity-8"></i>
                        </a>
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
