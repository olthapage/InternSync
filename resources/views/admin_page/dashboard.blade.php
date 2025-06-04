@extends('layouts.template')
@section('content')
    {{-- ... (Bagian Welcome Banner dan Key Metrics Cards tidak berubah) ... --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary shadow-primary border-radius-lg">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-lg-8">
                            <h4 class="text-white mb-0">Selamat datang, Admin!</h4>
                            <p class="text-white opacity-8">Dashboard manajemen sistem magang kampus</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card hover-effect">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Terlibat Magang</p>
                                <h5 class="font-weight-bolder">{{ $mhsMagang }}</h5>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-sm bg-gradient-success me-2">
                                        @if ($mhsCount > 0)
                                            {{ round(($mhsMagang / $mhsCount) * 100) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                    <p class="text-xs mb-0">dari total mahasiswa</p>
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
                    <a href="{{ route('mahasiswa.index') }}" class="text-sm font-weight-bold"> Lihat detail <i
                            class="fa fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card hover-effect">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Lowongan Aktif</p>
                                <h5 class="font-weight-bolder">{{ $lowongan }}</h5>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-sm bg-gradient-info me-2">{{ $lowongan }} aktif</span>
                                    <p class="text-xs mb-0">total lowongan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow-info text-center rounded-circle">
                                <i class="fa-solid fa-briefcase text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer pt-0 bg-transparent">
                    <a href="{{ route('lowongan.index') }}" class="text-sm font-weight-bold">Kelola lowongan <i
                            class="fa fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card hover-effect">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Mahasiswa</p>
                                <h5 class="font-weight-bolder">{{ $mhsCount }}</h5>
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
                    <a href="{{ route('mahasiswa.index') }}" class="text-sm font-weight-bold">Lihat data <i
                            class="fa fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card hover-effect">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Perusahaan Mitra</p>
                                <h5 class="font-weight-bolder">{{ $industri }}</h5>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-sm bg-gradient-danger me-2">{{ $industri }}</span>
                                    <p class="text-xs mb-0">perusahaan</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                <i class="fa-solid fa-building text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer pt-0 bg-transparent">
                    <a href="{{ route('industri.index') }}" class="text-sm font-weight-bold">Kelola perusahaan <i
                            class="fa fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8 mb-lg-0 mb-4">
            <div class="card z-index-2 h-100">
                <div class="card-header pb-0 pt-3 bg-transparent">
                    <h6 class="text-capitalize">Statistik Proses Magang</h6>
                    <p class="text-sm mb-0">
                        <i class="fa fa-check text-info" aria-hidden="true"></i>
                        <span class="font-weight-bold ms-1">{{ $mhsMagang }} mahasiswa terlibat program magang</span>
                        dari
                        {{ $mhsCount }} total mahasiswa.
                    </p>
                </div>
                <div class="card-body p-3">
                    <div class="row mb-3">
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="card text-center shadow-sm">
                                <div class="card-body p-3">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Sedang Magang</p>
                                    <h5 class="font-weight-bolder mt-2">{{ $mahasiswaSedangMagang }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="card text-center shadow-sm">
                                <div class="card-body p-3">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Selesai Magang</p>
                                    <h5 class="font-weight-bolder mt-2">{{ $mahasiswaSelesaiMagang }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="card text-center shadow-sm">
                                <div class="card-body p-3">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Akan/Belum Mulai</p>
                                    <h5 class="font-weight-bolder mt-2">{{ $mahasiswaBelumMulaiMagang }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6 class="text-capitalize">Distribusi Status Magang Mahasiswa</h6>
                        <div style="height: 250px;">
                            <canvas id="statusMagangChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <h6>Distribusi Bidang Industri</h6>
                    <p class="text-sm mb-0">Perusahaan mitra berdasarkan bidang</p>
                </div>
                <div class="card-body p-3">
                    @if ($distribusiIndustri->isNotEmpty())
                        {{-- Untuk chart, tinggi disesuaikan agar proporsional di col-lg-4 --}}
                        {{-- Mungkin perlu tinggi lebih karena legenda bisa memakan tempat --}}
                        <div style="height: 320px;">
                            <canvas id="distribusiIndustriChart"></canvas>
                        </div>
                    @else
                        <div class="text-center p-3">
                            <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                            <p class="text-sm text-muted">Data distribusi bidang industri belum tersedia.</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer pt-0 bg-transparent"> {{-- bg-transparent agar menyatu jika chart menyentuh batas bawah --}}
                    <a href="{{ route('industri.index') }}" class="btn btn-sm bg-gradient-dark mb-0 w-100">Lihat Data
                        Perusahaan</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header pt-3 pb-0 bg-transparent d-flex justify-content-between align-items-center">
                    <h6 class="text-capitalize mb-0">Statistik Pengajuan Lowongan</h6>
                    <a href="{{ route('pengajuan.index') }}" class="text-sm font-weight-bold">
                        Lihat detail <i class="fa fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="card text-center shadow-sm">
                                <div class="card-body p-3">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Pengajuan Menunggu</p>
                                    <h5 class="font-weight-bolder mt-2">{{ $pengajuanMenunggu }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="card text-center shadow-sm">
                                <div class="card-body p-3">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Pengajuan Diterima</p>
                                    <h5 class="font-weight-bolder mt-2">{{ $pengajuanDiterima }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="card text-center shadow-sm">
                                <div class="card-body p-3">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Pengajuan Ditolak</p>
                                    <h5 class="font-weight-bolder mt-2">{{ $pengajuanDitolak }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        {{-- ... (Isi Quick Actions tidak berubah) ... --}}
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Aksi Cepat</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-3 col-6 text-center mb-4">
                            <button onclick="modalAction('{{ route('mahasiswa.create') }}')"
                                class="btn bg-gradient-primary btn-icon-only btn-rounded mb-0">
                                <i class="fas fa-user-plus" aria-hidden="true"></i>
                            </button>
                            <p class="text-sm mt-2 mb-0">Tambah Mahasiswa</p>
                        </div>
                        <div class="col-md-3 col-6 text-center mb-4">
                            <button onclick="modalAction('{{ route('industri.create') }}')"
                                class="btn bg-gradient-info btn-icon-only btn-rounded mb-0">
                                <i class="fas fa-building" aria-hidden="true"></i>
                            </button>
                            <p class="text-sm mt-2 mb-0">Tambah Perusahaan</p>
                        </div>
                        <div class="col-md-3 col-6 text-center">
                            <button onclick="modalAction('{{ route('lowongan.create') }}')"
                                class="btn bg-gradient-success btn-icon-only btn-rounded mb-0">
                                <i class="fas fa-briefcase" aria-hidden="true"></i>
                            </button>
                            <p class="text-sm mt-2 mb-0">Buat Lowongan</p>
                        </div>
                        <div class="col-md-3 col-6 text-center">
                            <button onclick="modalAction('{{ route('dosen.create') }}')"
                                class="btn bg-gradient-warning btn-icon-only btn-rounded mb-0">
                                <i class="fas fa-chalkboard-teacher" aria-hidden="true"></i>
                            </button>
                            <p class="text-sm mt-2 mb-0">Tambah Dosen</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false" aria-hidden="true"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        // Chart untuk Status Magang Mahasiswa (tidak berubah)
        var ctxStatusMagang = document.getElementById('statusMagangChart').getContext('2d');
        var statusMagangChart = new Chart(ctxStatusMagang, {
            type: 'doughnut',
            data: {
                labels: ['Sedang Magang', 'Selesai Magang', 'Akan/Belum Mulai'],
                datasets: [{
                    label: 'Status Magang',
                    data: [
                        {{ $mahasiswaSedangMagang }},
                        {{ $mahasiswaSelesaiMagang }},
                        {{ $mahasiswaBelumMulaiMagang }}
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(255, 206, 86, 0.8)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed + ' Mahasiswa';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });

        // BARU: Chart untuk Distribusi Bidang Industri
        @if ($distribusiIndustri->isNotEmpty())
            var ctxDistribusiIndustri = document.getElementById('distribusiIndustriChart').getContext('2d');
            // Fungsi untuk menghasilkan warna acak namun menarik
            function getRandomColor(index, total) {
                const hue = (index * (360 / total)) % 360; // Sebar hue secara merata
                return `hsla(${hue}, 70%, 60%, 0.8)`; // HSL lebih mudah dikontrol untuk warna yang serasi
            }

            const industriLabels = @json($labelsDistribusiIndustri);
            const industriData = @json($dataDistribusiIndustri);
            const industriColors = industriLabels.map((label, index) => getRandomColor(index, industriLabels.length));

            var distribusiIndustriChart = new Chart(ctxDistribusiIndustri, {
                type: 'pie', // atau 'doughnut' atau 'bar' jika lebih cocok
                data: {
                    labels: industriLabels,
                    datasets: [{
                        label: 'Jumlah Perusahaan',
                        data: industriData,
                        backgroundColor: industriColors,
                        borderColor: industriColors.map(color => color.replace('0.8',
                        '1')), // Versi lebih solid untuk border
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom', // 'right' atau 'left' mungkin lebih baik jika banyak kategori
                            labels: {
                                boxWidth: 12, // Ukuran kotak warna di legenda
                                padding: 10 // Jarak antar item legenda
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed !== null) {
                                        label += context.parsed + ' Perusahaan';
                                    }
                                    return label;
                                }
                            }
                        },
                        title: {
                            display: false, // Judul sudah ada di card header
                            // text: 'Distribusi Perusahaan per Kategori Industri'
                        }
                    }
                }
            });
        @endif
    </script>
@endpush
