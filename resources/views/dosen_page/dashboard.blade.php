@extends('layouts.template')
@section('content')
    <div class="row">
        <!-- Header -->
        <div class="col-12">
            <div class="card bg-gradient-primary shadow-primary border-radius-lg mt-4">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-8 d-flex align-items-center">
                            <div>
                                <h5 class="text-white mb-0">Dashboard Dosen Pembimbing</h5>
                                <h2 class="text-white font-weight-bolder">Manajemen Bimbingan Magang</h2>
                                <p class="text-white text-sm mb-0">Selamat datang kembali, Prof. {{ Auth::user()->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-end d-flex align-items-center justify-content-end">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Utama -->
        <div class="col-lg-12 mt-4">
            <div class="row">
                <!-- Total Bimbingan -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-1 text-dark font-weight-bold">Total Bimbingan</p>
                                <h4 class="font-weight-bolder text-dark mb-0">8</h4>
                            </div>
                            <div class="icon icon-shape bg-info text-white rounded-circle shadow text-center">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sedang Magang -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-1 text-dark font-weight-bold">Sedang Magang</p>
                                <h4 class="font-weight-bolder text-dark mb-0">5</h4>
                            </div>
                            <div class="icon icon-shape bg-success text-white rounded-circle shadow text-center">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Menunggu Evaluasi -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-1 text-dark font-weight-bold">Menunggu Evaluasi</p>
                                <h4 class="font-weight-bolder text-dark mb-0">2</h4>
                            </div>
                            <div class="icon icon-shape bg-warning text-white rounded-circle shadow text-center">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selesai -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-sm mb-1 text-dark font-weight-bold">Selesai</p>
                                <h4 class="font-weight-bolder text-dark mb-0">1</h4>
                            </div>
                            <div class="icon icon-shape bg-primary text-white rounded-circle shadow text-center">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manajemen Mahasiswa Bimbingan -->
        <div class="col-lg-8 col-md-12 mt-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Mahasiswa Bimbingan</h6>
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
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{ asset('softTemplate/assets/img/team-3.jpg') }}" class="avatar avatar-sm me-3" alt="user2">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Alexa Liras</h6>
                                                <p class="text-xs text-secondary mb-0">NIM: 22410002</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">PT XYZ Technology</p>
                                        <p class="text-xs text-secondary mb-0">UI/UX Designer</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="progress-wrapper w-100">
                                            <div class="progress-info">
                                                <div class="progress-percentage">
                                                    <span class="text-xs font-weight-bold">45%</span>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-info w-45" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-sm bg-gradient-warning">Evaluasi</span>
                                    </td>
                                    <td class="align-middle text-end">
                                        <button class="btn btn-sm bg-gradient-info mb-0">Detail</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{ asset('softTemplate/assets/img/team-4.jpg') }}" class="avatar avatar-sm me-3" alt="user3">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">Laurent Perrier</h6>
                                                <p class="text-xs text-secondary mb-0">NIM: 22410003</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">PT Data Analytics</p>
                                        <p class="text-xs text-secondary mb-0">Data Scientist</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="progress-wrapper w-100">
                                            <div class="progress-info">
                                                <div class="progress-percentage">
                                                    <span class="text-xs font-weight-bold">100%</span>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-success w-100" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-sm bg-gradient-secondary">Selesai</span>
                                    </td>
                                    <td class="align-middle text-end">
                                        <button class="btn btn-sm bg-gradient-info mb-0">Detail</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monitoring & Evaluasi -->
        <div class="col-lg-4 col-md-12 mt-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Aktivitas Terkini</h6>
                    <p class="text-sm mb-0">Update terbaru dari mahasiswa bimbingan</p>
                </div>
                <div class="card-body p-3 pt-0">
                    <div class="timeline timeline-one-side">
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
                        <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="ni ni-notification-70 text-danger text-gradient"></i>
                            </span>
                            <div class="timeline-content">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">Kendala Magang</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Alexa Liras - Proyek UI/UX</p>
                                <span class="text-xs text-secondary">1 hari lalu</span>
                                <a href="#" class="text-xs text-info">Berikan Solusi</a>
                            </div>
                        </div>
                        <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="ni ni-check-bold text-primary text-gradient"></i>
                            </span>
                            <div class="timeline-content">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">Magang Selesai</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Laurent Perrier</p>
                                <span class="text-xs text-secondary">3 hari lalu</span>
                                <a href="#" class="text-xs text-info">Input Nilai</a>
                            </div>
                        </div>
                        <div class="timeline-block">
                            <span class="timeline-step">
                                <i class="ni ni-calendar-grid-58 text-info text-gradient"></i>
                            </span>
                            <div class="timeline-content">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">Jadwal Bimbingan</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Andi Nugroho - Besok 10:00</p>
                                <span class="text-xs text-secondary">1 minggu lalu</span>
                                <a href="#" class="text-xs text-info">Lihat Jadwal</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress & Grafik -->
        <div class="col-md-6 mt-4">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Progress Mahasiswa</h6>
                    <p class="text-sm mb-0">Statistik perkembangan magang</p>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="progress-chart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manajemen Profil -->
        <div class="col-md-6 mt-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Profil Dosen</h6>
                    <p class="text-sm mb-0">Informasi akun dan preferensi</p>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3">
                            <img src="{{ asset('softTemplate/assets/img/team-1.jpg') }}" class="avatar avatar-lg rounded-circle" alt="profile">
                        </div>
                        <div>
                            <h6 class="mb-0">Prof. {{ Auth::user()->name }}</h6>
                            <p class="text-sm text-secondary mb-0">{{ Auth::user()->email }}</p>
                            <a href="{{ route('profile.index') }}" class="text-xs text-info">Edit Profil</a>
                        </div>
                    </div>
                    <div class="card border card-plain border-radius-lg p-3 mb-3">
                        <h6 class="text-sm font-weight-bold mb-2">Bidang Keahlian</h6>
                        <div class="d-flex flex-wrap">
                            <span class="badge badge-sm bg-gradient-info me-2 mb-2">Artificial Intelligence</span>
                            <span class="badge badge-sm bg-gradient-info me-2 mb-2">Data Science</span>
                            <span class="badge badge-sm bg-gradient-info me-2 mb-2">Machine Learning</span>
                        </div>
                    </div>
                    <div class="card border card-plain border-radius-lg p-3">
                        <h6 class="text-sm font-weight-bold mb-2">Preferensi Bimbingan</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0 py-1 border-0">
                                <div class="d-flex align-items-center">
                                    <i class="ni ni-check-bold text-success me-2"></i>
                                    <span>Perusahaan Teknologi</span>
                                </div>
                            </li>
                            <li class="list-group-item px-0 py-1 border-0">
                                <div class="d-flex align-items-center">
                                    <i class="ni ni-check-bold text-success me-2"></i>
                                    <span>Startup Digital</span>
                                </div>
                            </li>
                            <li class="list-group-item px-0 py-1 border-0">
                                <div class="d-flex align-items-center">
                                    <i class="ni ni-check-bold text-success me-2"></i>
                                    <span>Perusahaan Multinasional</span>
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
        // Progress Chart
        var ctx = document.getElementById("progress-chart").getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Andi N", "Alexa L", "Laurent P", "Michael S", "Sarah K"],
                datasets: [{
                    label: "Progress Magang",
                    weight: 5,
                    borderWidth: 0,
                    borderRadius: 4,
                    backgroundColor: "#3A416F",
                    data: [75, 45, 100, 60, 30],
                    fill: false,
                    maxBarThickness: 35
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + '%';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        },
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });
    </script>
@endsection