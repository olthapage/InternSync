@extends('layouts.template')
@section('content')
    <!-- Welcome Banner -->
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

    <!-- Key Metrics Cards -->
    <div class="row">
        <!-- Mahasiswa Magang Card -->
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card hover-effect">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-uppercase font-weight-bold">Mahasiswa Magang</p>
                                <h5 class="font-weight-bolder">{{ $mhsMagang }}</h5>
                                <div class="d-flex align-items-center">
                                    <span class="badge badge-sm bg-gradient-success me-2">{{ round(($mhsMagang/$mhsCount)*100) }}%</span>
                                    <p class="text-xs mb-0">dari total</p>
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
                    <a href="{{ route('mahasiswa.magang.index') }}" class="text-sm font-weight-bold"> Lihat detail <i class="fa fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Lowongan Card -->
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
                    <a href="{{ route('lowongan.index') }}" class="text-sm font-weight-bold">Kelola lowongan <i class="fa fa-arrow-right ms-1"></i></a>
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
                    <a href="{{ route('mahasiswa.pengajuan.index') }}" class="text-sm font-weight-bold">Lihat data <i class="fa fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Perusahaan Mitra Card -->
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
                    <a href="{{ route('industri.index') }}" class="text-sm font-weight-bold">Kelola perusahaan <i class="fa fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row - Charts and Progress -->
    <div class="row mt-4">
        <!-- Statistik Magang Chart -->
        <div class="col-lg-8 mb-lg-0 mb-4">
            <div class="card z-index-2 h-100">
                <div class="card-header pb-0 pt-3 bg-transparent">
                    <h6 class="text-capitalize">Statistik Magang</h6>
                    <p class="text-sm mb-0">
                        <i class="fa fa-check text-info" aria-hidden="true"></i>
                        <span class="font-weight-bold ms-1">{{ $mhsMagang }} mahasiswa magang</span> dari {{ $mhsCount }} mahasiswa
                    </p>
                </div>
                <div class="card-body p-3">
                    <div class="card border shadow-sm p-3">
                        Data statistik magang tidak tersedia dalam controller saat ini
                    </div>
                </div>
            </div>
        </div>

        <!-- Distribusi Bidang Industri -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <h6>Distribusi Bidang Industri</h6>
                    <p class="text-sm mb-0">Perusahaan mitra berdasarkan bidang</p>
                </div>
                <div class="card-body p-3">
                    <div class="card border shadow-sm p-3">
                        Data distribusi bidang industri tidak tersedia dalam controller saat ini
                    </div>
                </div>
                <div class="card-footer pt-0">
                    <a href="{{ route('industri.index') }}" class="btn btn-sm bg-gradient-dark mb-0 w-100">Lihat Data Perusahaan</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Fourth Row - Quick Actions -->
    <div class="row mt-4">
        <!-- Quick Actions -->
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Aksi Cepat</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-3 col-6 text-center mb-4">
                            <a href="{{ route('mahasiswa.create') }}" class="btn bg-gradient-primary btn-icon-only btn-rounded mb-0">
                                <i class="fas fa-user-plus" aria-hidden="true"></i>
                            </a>
                            <p class="text-sm mt-2 mb-0">Tambah Mahasiswa</p>
                        </div>
                        <div class="col-md-3 col-6 text-center mb-4">
                            <a href="{{ route('industri.create') }}" class="btn bg-gradient-info btn-icon-only btn-rounded mb-0">
                                <i class="fas fa-building" aria-hidden="true"></i>
                            </a>
                            <p class="text-sm mt-2 mb-0">Tambah Perusahaan</p>
                        </div>
                        <div class="col-md-3 col-6 text-center">
                            <a href="{{ route('lowongan.create') }}" class="btn bg-gradient-success btn-icon-only btn-rounded mb-0">
                                <i class="fas fa-briefcase" aria-hidden="true"></i>
                            </a>
                            <p class="text-sm mt-2 mb-0">Buat Lowongan</p>
                        </div>
                        <div class="col-md-3 col-6 text-center">
                            <a href="{{ route('dosen.create') }}" class="btn bg-gradient-warning btn-icon-only btn-rounded mb-0">
                                <i class="fas fa-chalkboard-teacher" aria-hidden="true"></i>
                            </a>
                            <p class="text-sm mt-2 mb-0">Tambah Dosen</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection