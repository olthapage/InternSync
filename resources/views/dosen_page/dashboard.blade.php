@extends('layouts.template')

@section('content')
@php
    // Asumsikan role_dosen tersimpan di properti user atau relasi
    // Sesuaikan ini jika struktur data Anda berbeda.
    // Misalnya: $roleDosen = Auth::user()->dosen->role_dosen ?? null;
    $roleDosen = Auth::user()->role_dosen ?? 'pembimbing'; // Default ke 'pembimbing' jika tidak ada, atau sesuaikan
    $namaDosen = Auth::user()->name ?? 'Dosen';

    // Variabel untuk data statistik (contoh, Anda akan memuat ini dari controller)
    // Untuk Pembimbing
    $totalBimbingan = 8; // Contoh
    $sedangMagang = 5;   // Contoh
    $menungguEvaluasi = 2; // Contoh
    $selesaiMagang = 1;    // Contoh

    // Untuk DPA (Dosen Penasihat Akademik) / Dosen Wali
    $totalPerwalian = 25; // Contoh
    $mahasiswaAktifWali = 20; // Contoh
    $pengajuanKRS = 3;       // Contoh
    $pengajuanCuti = 2;        // Contoh

    // Data Mahasiswa (Anda akan memuat ini dari controller)
    // $mahasiswaBimbingan = []; // Untuk Pembimbing
    // $mahasiswaPerwalian = []; // Untuk DPA
@endphp

<div class="row">
    <div class="col-12">
        <div class="card bg-gradient-primary shadow-primary border-radius-lg mt-4">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-8 d-flex align-items-center">
                        <div>
                            @if ($roleDosen == 'pembimbing')
                                <h5 class="text-white mb-0">Dashboard Dosen Pembimbing</h5>
                                <h2 class="text-white font-weight-bolder">Manajemen Bimbingan Magang</h2>
                            @elseif ($roleDosen == 'dpa')
                                <h5 class="text-white mb-0">Dashboard Dosen Pembimbing Akademik</h5>
                                <h2 class="text-white font-weight-bolder">Manajemen Perwalian Mahasiswa</h2>
                            @else
                                <h5 class="text-white mb-0">Dashboard Dosen</h5>
                                <h2 class="text-white font-weight-bolder">Informasi Akademik</h2>
                            @endif
                            <p class="text-white text-sm mb-0">Selamat datang kembali, {{ $namaDosen }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
                        {{-- Bisa ditambahkan tombol atau info lain di sini --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-4">
        <div class="row">
            @if ($roleDosen == 'pembimbing')
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-1 text-dark font-weight-bold">Total Bimbingan</p>
                                <h4 class="font-weight-bolder text-dark mb-0">{{ $totalBimbingan }}</h4>
                            </div>
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow text-center">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-1 text-dark font-weight-bold">Sedang Magang</p>
                                <h4 class="font-weight-bolder text-dark mb-0">{{ $sedangMagang }}</h4>
                            </div>
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow text-center">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-1 text-dark font-weight-bold">Menunggu Evaluasi</p>
                                <h4 class="font-weight-bolder text-dark mb-0">{{ $menungguEvaluasi }}</h4>
                            </div>
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow text-center">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-1 text-dark font-weight-bold">Selesai Magang</p>
                                <h4 class="font-weight-bolder text-dark mb-0">{{ $selesaiMagang }}</h4>
                            </div>
                            <div class="icon icon-shape bg-primary text-white rounded-circle shadow text-center">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif ($roleDosen == 'dpa')
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border shadow-sm p-3">
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
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-1 text-dark font-weight-bold">Mhs Aktif (Wali)</p>
                                <h4 class="font-weight-bolder text-dark mb-0">{{ $mahasiswaAktifWali }}</h4>
                            </div>
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow text-center">
                                <i class="fas fa-user-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-1 text-dark font-weight-bold">Pengajuan KRS</p>
                                <h4 class="font-weight-bolder text-dark mb-0">{{ $pengajuanKRS }}</h4>
                            </div>
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow text-center">
                                <i class="fas fa-file-signature"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-1 text-dark font-weight-bold">Pengajuan Cuti/Non-Aktif</p>
                                <h4 class="font-weight-bolder text-dark mb-0">{{ $pengajuanCuti }}</h4>
                            </div>
                            <div class="icon icon-shape bg-danger text-white rounded-circle shadow text-center">
                                <i class="fas fa-user-slash"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if ($roleDosen == 'pembimbing')
        <div class="col-lg-8 col-md-12 mt-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Mahasiswa Bimbingan Magang</h6>
                            <p class="text-sm mb-0">Daftar mahasiswa yang sedang Anda bimbing</p>
                        </div>
                        <a href="{{ route('mahasiswa-bimbingan.index') }}" class="btn btn-sm bg-gradient-primary mb-0">Lihat Semua</a>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">Mahasiswa</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">Tempat Magang</th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Progress</th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Status</th>
                                    <th class="text-end text-uppercase text-secondary text-xs font-weight-bolder">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loop data mahasiswa bimbingan di sini --}}
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{ asset('softTemplate/assets/img/team-2.jpg') }}" class="avatar avatar-sm me-3" alt="user1">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Andi Nugroho</h6>
                                                <p class="text-xs text-secondary mb-0">NIM: 22410001</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">PT Teknologi Cerdas</p>
                                        <p class="text-xs text-secondary mb-0">Web Developer</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="progress-wrapper w-100">
                                            <div class="progress-info">
                                                <div class="progress-percentage">
                                                    <span class="text-xs font-weight-bold">75%</span>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-info w-75" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-sm bg-gradient-success">Aktif</span>
                                    </td>
                                    <td class="align-middle text-end">
                                        <button class="btn btn-sm bg-gradient-info mb-0">Detail</button>
                                    </td>
                                </tr>
                                {{-- Akhir Loop --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 mt-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Aktivitas Terkini Bimbingan</h6>
                    <p class="text-sm mb-0">Update terbaru dari mahasiswa bimbingan</p>
                </div>
                <div class="card-body p-3 pt-0">
                    <div class="timeline timeline-one-side">
                        {{-- Contoh Aktivitas, loop data aktivitas di sini --}}
                        <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="ni ni-single-copy-04 text-success text-gradient"></i>
                            </span>
                            <div class="timeline-content">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">Laporan Mingguan</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Andi Nugroho - Minggu ke-4</p>
                                <span class="text-xs text-secondary">2 jam lalu</span>
                                <a href="#" class="text-xs text-info">Berikan Feedback</a>
                            </div>
                        </div>
                        {{-- Akhir Contoh --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mt-4">
             <div class="card">
                 <div class="card-header pb-0 p-3">
                     <h6 class="mb-0">Progress Mahasiswa Bimbingan</h6>
                     <p class="text-sm mb-0">Statistik perkembangan magang</p>
                 </div>
                 <div class="card-body p-3">
                     <div class="chart">
                         <canvas id="progress-chart" height="300"></canvas>
                     </div>
                 </div>
             </div>
         </div>

    @elseif ($roleDosen == 'dpa')
        <div class="col-lg-8 col-md-12 mt-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Mahasiswa Perwalian</h6>
                            <p class="text-sm mb-0">Daftar mahasiswa di bawah perwalian Anda</p>
                        </div>
                        <a href="#" class="btn btn-sm bg-gradient-primary mb-0">Lihat Semua</a> {{-- Sesuaikan rute --}}
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">Mahasiswa</th>
                                    <th class="text-uppercase text-secondary text-xs font-weight-bolder">Angkatan</th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">IPK</th>
                                    <th class="text-center text-uppercase text-secondary text-xs font-weight-bolder">Status Akademik</th>
                                    <th class="text-end text-uppercase text-secondary text-xs font-weight-bolder">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loop data mahasiswa perwalian di sini --}}
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{ asset('softTemplate/assets/img/team-1.jpg') }}" class="avatar avatar-sm me-3" alt="user1">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Budi Santoso</h6>
                                                <p class="text-xs text-secondary mb-0">NIM: 21410010</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">2021</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-sm font-weight-bold">3.75</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-sm bg-gradient-success">Aktif</span>
                                    </td>
                                    <td class="align-middle text-end">
                                        <button class="btn btn-sm bg-gradient-info mb-0">Lihat Detail</button>
                                    </td>
                                </tr>
                                {{-- Akhir Loop --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 mt-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Notifikasi Perwalian</h6>
                    <p class="text-sm mb-0">Update terbaru dari mahasiswa perwalian</p>
                </div>
                <div class="card-body p-3 pt-0">
                    <div class="timeline timeline-one-side">
                        {{-- Contoh Aktivitas DPA, loop data aktivitas di sini --}}
                        <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="ni ni-folder-17 text-primary text-gradient"></i>
                            </span>
                            <div class="timeline-content">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">Pengajuan KRS</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Budi Santoso - Perlu Verifikasi</p>
                                <span class="text-xs text-secondary">1 jam lalu</span>
                                <a href="#" class="text-xs text-info">Proses KRS</a>
                            </div>
                        </div>
                         <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="ni ni-bell-55 text-warning text-gradient"></i>
                            </span>
                            <div class="timeline-content">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">Permohonan Konsultasi</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Siti Aminah - Masalah Akademik</p>
                                <span class="text-xs text-secondary">Kemarin</span>
                                <a href="#" class="text-xs text-info">Atur Jadwal</a>
                            </div>
                        </div>
                        {{-- Akhir Contoh --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mt-4">
             <div class="card">
                 <div class="card-header pb-0 p-3">
                     <h6 class="mb-0">Statistik Akademik Perwalian</h6>
                     <p class="text-sm mb-0">Distribusi IPK, status mahasiswa, dll.</p>
                 </div>
                 <div class="card-body p-3">
                     <div class="chart">
                         <canvas id="progress-chart-dpa" height="300"></canvas> {{-- ID Chart berbeda untuk DPA --}}
                     </div>
                 </div>
             </div>
         </div>
    @endif

    <div class="col-md-6 mt-4">
        <div class="card h-100">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Profil Dosen</h6>
                <p class="text-sm mb-0">Informasi akun dan preferensi</p>
            </div>
            <div class="card-body p-3">
                <div class="d-flex align-items-center mb-4">
                    <div class="me-3">
                        {{-- Idealnya foto profil dinamis --}}
                        <img src="{{ Auth::user()->foto ?? asset('softTemplate/assets/img/team-1.jpg') }}" class="avatar avatar-lg rounded-circle" alt="profile">
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $namaDosen }}</h6>
                        <p class="text-sm text-secondary mb-0">{{ Auth::user()->email }}</p>
                        <a href="{{ route('profile.index') }}" class="text-xs text-info">Edit Profil</a>
                    </div>
                </div>
                <div class="card border card-plain border-radius-lg p-3 mb-3">
                    <h6 class="text-sm font-weight-bold mb-2">Bidang Keahlian</h6>
                    <div class="d-flex flex-wrap">
                        {{-- Data bidang keahlian dinamis --}}
                        <span class="badge badge-sm bg-gradient-info me-2 mb-2">Artificial Intelligence</span>
                        <span class="badge badge-sm bg-gradient-info me-2 mb-2">Data Science</span>
                    </div>
                </div>
                <div class="card border card-plain border-radius-lg p-3">
                     <h6 class="text-sm font-weight-bold mb-2">
                        @if ($roleDosen == 'pembimbing')
                            Preferensi Bimbingan Magang
                        @elseif ($roleDosen == 'dpa')
                            Fokus Perwalian
                        @else
                            Informasi Tambahan
                        @endif
                    </h6>
                    <ul class="list-group list-group-flush">
                        {{-- Data preferensi/fokus dinamis --}}
                        <li class="list-group-item px-0 py-1 border-0">
                            <div class="d-flex align-items-center">
                                <i class="ni ni-check-bold text-success me-2"></i>
                                <span>Perusahaan Teknologi</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        // Chart untuk Dosen Pembimbing
        @if ($roleDosen == 'pembimbing')
        var ctxPembimbing = document.getElementById("progress-chart").getContext("2d");
        if (ctxPembimbing) {
            new Chart(ctxPembimbing, {
                type: "bar",
                data: {
                    labels: ["Andi N", "Alexa L", "Laurent P", "Michael S", "Sarah K"], // Data dinamis
                    datasets: [{
                        label: "Progress Magang",
                        weight: 5,
                        borderWidth: 0,
                        borderRadius: 4,
                        backgroundColor: "#3A416F",
                        data: [75, 45, 100, 60, 30], // Data dinamis
                        fill: false,
                        maxBarThickness: 35
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: function(context) { return context.parsed.y + '%'; } } }
                    },
                    scales: {
                        y: {
                            beginAtZero: true, max: 100,
                            ticks: { callback: function(value) { return value + '%'; } },
                            grid: { drawBorder: false }
                        },
                        x: { grid: { display: false, drawBorder: false } }
                    }
                }
            });
        }
        @elseif ($roleDosen == 'dpa')
        // Chart untuk Dosen Wali (DPA)
        var ctxDpa = document.getElementById("progress-chart-dpa").getContext("2d");
        if (ctxDpa) {
            new Chart(ctxDpa, {
                type: "pie", // Contoh chart berbeda untuk DPA
                data: {
                    labels: ["IPK > 3.5", "IPK 3.0-3.5", "IPK < 3.0", "Status Cuti"], // Data dinamis
                    datasets: [{
                        label: "Statistik Perwalian",
                        backgroundColor: ["#4CAF50", "#2196F3", "#FFC107", "#E91E63"],
                        data: [10, 8, 5, 2] // Data dinamis
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed !== null) {
                                        label += context.parsed;
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
        @endif
    </script>
@endsection
