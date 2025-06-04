@extends('layouts.template')
@section('content')
<div class="row">
    <!-- Header -->
    <div class="col-lg-12">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient-primary shadow-primary border-radius-lg">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-lg-8">
                                <h4 class="text-white mb-0">Selamat datang, PT Teknologi Cerdas!</h4>
                                <p class="text-white opacity-8">Dashboard manajemen sistem magang kampus</p>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu Dashboard -->
        <div class="row">
        <!-- Mahasiswa Magang Card -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card hover-effect">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <h5 class="mb-1">Mahasiswa Magang</h5>
                                <p class="text-muted small">Kelola mahasiswa yang sedang magang</p>
                                <div class="d-flex align-items-center">
                                    <div class="mx-2 text-center">
                                    <h3 class="text-primary mb-0">1</h3>
                                    <small class="text-muted">Aktif</small>
                                </div>
                                <div class="mx-2 text-center">
                                    <h3 class="text-success mb-0">0</h3>
                                    <small class="text-muted">Selesai</small>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                <i class="fa fa-user-check text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer pt-0 bg-transparent">
                    <a href="{{ route('industri.magang.index') }}" class="text-sm font-weight-bold"> Lihat Mahasiswa Magang <i class="fa fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Total Mahasiswa Card -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card hover-effect">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <h5 class="mb-1">Total Mahasiswa</h5>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-sm bg-gradient-warning me-2">{{ $mhsCount }}</span>
                                    <p class="text-xs mb-0">mahasiswa terdaftar</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer pt-0 bg-transparent">
                    <a href="{{ route('mahasiswa.index') }}" class="text-sm font-weight-bold">Lihat data <i class="fa fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Logbook Card -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card hover-effect">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <h5 class="mb-1">Logbook</h5>
                                <p class="text-muted small">Pantau aktivitas harian mahasiswa</p>
                                <div class="d-flex align-items-center">

                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer pt-0 bg-transparent">
                    <a href="{{ route('logharian_industri.index') }}" class="text-sm font-weight-bold"> Lihat Logbook <i class="fa fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Evaluasi Card -->
        <div class="col-xl-3 col-sm-6">
            <div class="card hover-effect">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <h5 class="mb-1">Evaluasi</h5>
                                <p class="text-muted small">Laporan harian</p>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-danger shadow-warning text-center rounded-circle">
                                <i class="fa-solid fa-clipboard-check fa-lg" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer pt-0 bg-transparent">
                    <a href="{{ route('logharian_industri.index') }}" class="text-sm font-weight-bold"> Lihat Evaluasi <i class="fa fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>


        <!-- Aktivitas Terkini -->
        <div class="card mb-4">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Aktivitas Terkini</h5>
                <p class="text-muted mb-0">Update terbaru dari mahasiswa magang</p>
            </div>
            <div class="card-body">
                <div class="activity-item">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-1">Carla Purwanti mengirim logbook mingguan</h6>
                        <small class="text-muted">2 jam lalu</small>
                    </div>
                    <p class="mb-0 text-muted">Minggu ke-1 magang di divisi Pengembangan Web</p>
                    <div class="mt-2">
                        <span class="badge bg-primary-subtle text-primary">Logbook</span>
                        <span class="badge bg-info-subtle text-info">Menunggu Review</span>
                    </div>
                </div>
                
                <div class="activity-item pending mt-3">
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-1">Pendaftaran magang baru diterima</h6>
                        <small class="text-muted">1 hari lalu</small>
                    </div>
                    <p class="mb-0 text-muted">2 mahasiswa mendaftar lowongan Web Developer</p>
                    <p class="mb-0 text-muted">1 mahasiswa mendaftar lowongan Pemasaran Digital (teknis) </p>
                    <div class="mt-2">
                        <span class="badge bg-warning-subtle text-warning">Pendaftaran</span>
                        <span class="badge bg-info-subtle text-info">Menunggu Review</span>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                    <a href="{{ route('logharian_industri.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua Aktivitas</a>
                </div>
            </div>
        </div>

        <!-- Mahasiswa Magang Aktif -->
        <div class="card">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Mahasiswa Magang</h5>
                <p class="text-muted mb-0">Daftar mahasiswa yang sedang magang</p>
            </div>
            <div class="card-body">
                <div class="student-item">
                    <div class="flex-grow-1">
                        <h6 class="mb-0">Carla Purwanti</h6>
                        <small class="text-muted">Politeknik Negeri Malang - Web Developer</small>
                    </div>
                    <div class="text-end">
                    </div>
                </div>
                
                {{-- <div class="student-item mt-3">
                    <div class="student-avatar">BS</div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">Budi Santoso</h6>
                        <small class="text-muted">Institut Sains - Data Analyst</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-warning-subtle text-warning">Minggu 2</span>
                        <div class="progress progress-sm mt-1" style="width: 100px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 25%;"></div>
                        </div>
                    </div>
                </div>
                
                <div class="student-item mt-3">
                    <div class="student-avatar">CW</div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">Citra Wijaya</h6>
                        <small class="text-muted">Politeknik Negeri - UI/UX Designer</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary-subtle text-primary">Minggu 3</span>
                        <div class="progress progress-sm mt-1" style="width: 100px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 45%;"></div>
                        </div>
                    </div>
                </div>
                
                <div class="student-item mt-3">
                    <div class="student-avatar">DR</div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">Dian Rahayu</h6>
                        <small class="text-muted">Universitas Komputer - Mobile Dev</small>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success-subtle text-success">Minggu 8</span>
                        <div class="progress progress-sm mt-1" style="width: 100px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 95%;"></div>
                        </div>
                    </div>
                </div> --}}
                
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                    <a href="{{ route('industri.magang.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua Mahasiswa</a>
                </div>
            </div>
        </div>
    </div>

        <!-- Lowongan Magang -->
        <div class="card mb-4">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Lowongan Magang</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle mb-2">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">PT Teknologi Cerdas</h6>
                        <p class="text-muted mb-0">Web Developer</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                        <i class="fas fa-building"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">PT Teknologi Cerdas</h6>
                        <p class="text-muted mb-0">Pemasaran Digital (Teknis)</p>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                    <a href="{{ route('industri.lowongan.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua Lowongan</a>
                    <a href="{{ route('industri.lowongan.create') }}" class="btn btn-sm btn-primary">Buat Lowongan Baru</a>
                </div>
            </div>
        </div>

        <!-- Profil Industri -->
        <div class="card mb-4">
            <div class="card-header bg-transparent">
                <h5 class="mb-0">Profil Industri</h5>
                <p class="text-muted mb-0">Informasi akun dan preferensi</p>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar me-3">
                        @if(optional(Auth::user()->industri)->logo)
                            <img src="{{ asset('storage/' . Auth::user()->industri->logo) }}" 
                                 alt="Logo Industri" 
                                 class="rounded-circle" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                <i class="fas fa-building"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                    </div>
                </div>

                <div class="border-top pt-3">
                    <h6 class="text-uppercase text-secondary mb-3">Kontak</h6>
                    <p class="mb-1">Email: {{ Auth::user()->email }}</p>
                    <p class="mb-0">Telepon: 081594598599</p>
                </div>

                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                    <a href="{{ route('profile.index') }}" class="btn btn-sm btn-outline-primary">Edit Profil</a>
                </div>
            </div>
        </div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.03);
        border: none;
        margin-bottom: 24px;
    }
    
    .card-header {
        border-bottom: 1px solid #e9ecef;
        padding: 16px 20px;
        background-color: #fff;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .icon-shape {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
    }
    
    .badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    
    .bg-primary-subtle {
        background-color: rgba(32, 107, 196, 0.1);
        color: #206bc4;
    }
    
    .bg-success-subtle {
        background-color: rgba(46, 125, 50, 0.1);
        color: #2e7d32;
    }
    
    .bg-warning-subtle {
        background-color: rgba(237, 108, 2, 0.1);
        color: #ed6c02;
    }
    
    .bg-info-subtle {
        background-color: rgba(2, 136, 209, 0.1);
        color: #0288d1;
    }
    
    .activity-item {
        border-left: 3px solid #206bc4;
        padding-left: 15px;
        margin-bottom: 20px;
    }
    
    .activity-item.completed {
        border-left-color: #2e7d32;
    }
    
    .activity-item.pending {
        border-left-color: #ed6c02;
    }
    
    .student-item {
        display: flex;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .student-item:last-child {
        border-bottom: none;
    }
    
    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: rgba(32, 107, 196, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: #206bc4;
        font-weight: bold;
    }
    
    .progress-sm {
        height: 6px;
    }
    
    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        font-size: 0.7rem;
        padding: 3px 6px;
    }
    
    .avatar {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        background-color: rgba(32, 107, 196, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #206bc4;
        font-size: 1.5rem;
    }
    
    .chart-container {
        position: relative;
        height: 200px;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #206bc4 0%, #1a56db 100%);
    }
</style>
@endpush

@push('scripts')
<script>
    // Status Chart
    var ctx2 = document.getElementById('statusChart')?.getContext('2d');
    if (ctx2) {
        var statusChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Selesai', 'Belum Mulai'],
                datasets: [{
                    data: [12, 8, 5],
                    backgroundColor: [
                        'rgba(32, 107, 196, 0.9)',
                        'rgba(46, 125, 50, 0.9)',
                        'rgba(253, 126, 20, 0.9)'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    }
                }
            }
        });
    }

    // Magang Chart
    var ctx1 = document.getElementById('magangChart')?.getContext('2d');
    if (ctx1) {
        var magangChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Mahasiswa Magang',
                    data: [12, 19, 15, 20, 18, 25, 22, 30, 28, 32, 30, 35],
                    backgroundColor: 'rgba(32, 107, 196, 0.05)',
                    borderColor: '#206bc4',
                    borderWidth: 2,
                    pointBackgroundColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
</script>
@endpush