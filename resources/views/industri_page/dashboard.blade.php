@extends('layouts.template')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-gradient-primary shadow-primary border-radius-lg mt-2"
                        style="background-image: url('{{ asset('images/slide1.jpg') }}'); background-size: cover; background-position: center;">
                        <span class="mask bg-gradient-dark opacity-6 border-radius-lg"></span>
                        <div class="card-body p-3 position-relative">
                            <div class="row">
                                <div class="col-lg-8">
                                    <h4 class="text-white mb-0">Selamat datang, {{ Auth::user()->industri_nama }}</h4>
                                    <p class="text-white opacity-8">Dashboard Manajemen Pelamar dan Lowongan Magang</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Kartu 1: Lowongan Aktif --}}
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                {{-- TAMBAHKAN KELAS DI SINI --}}
                <div class="card hover-effect h-100 d-flex flex-column justify-content-between">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Lowongan Aktif</p>
                                    <h5 class="font-weight-bolder">{{ $lowonganAktif }}</h5>
                                    <p class="text-xs mb-0 text-muted">Lowongan yang Anda buka</p>
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

            {{-- Kartu 2: Total Pelamar --}}
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                {{-- TAMBAHKAN KELAS DI SINI --}}
                <div class="card hover-effect h-100 d-flex flex-column justify-content-between">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Pelamar</p>
                                    <h5 class="font-weight-bolder">{{ $totalPelamar }}</h5>
                                    <p class="text-xs mb-0 text-muted">Mendaftar di perusahaan Anda</p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i class="fas fa-users-line text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer pt-0 bg-transparent">
                        <a href="{{ route('industri.lowongan.index') }}#semua_pelamar" class="text-sm font-weight-bold">Lihat data pelamar <i
                                class="fa fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>

            {{-- Kartu 3: Magang Saat Ini --}}
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                {{-- TAMBAHKAN KELAS DI SINI --}}
                <div class="card hover-effect h-100 d-flex flex-column justify-content-between">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Magang Saat Ini</p>
                                    <h5 class="font-weight-bolder">{{ $mahasiswaMagangAktif }}</h5>
                                    <p class="text-xs mb-0 text-muted">Mahasiswa sedang aktif</p>
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
                        <a href="{{ route('industri.magang.index') }}" class="text-sm font-weight-bold">Lihat detail <i
                                class="fa fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>

            {{-- Kartu 4: Alumni Magang --}}
            <div class="col-xl-3 col-sm-6">
                {{-- TAMBAHKAN KELAS DI SINI --}}
                <div class="card hover-effect h-100 d-flex flex-column justify-content-between">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Alumni Magang</p>
                                    <h5 class="font-weight-bolder">{{ $totalAlumni }}</h5>
                                    <p class="text-xs mb-0 text-muted">Telah menyelesaikan magang</p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="fa-solid fa-user-graduate text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer pt-0 bg-transparent">
                        <a href="{{ route('industri.magang.index') }}" class="text-sm font-weight-bold">Lihat riwayat <i
                                class="fa fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>

        {{-- DIUBAH: Bagian Statistik dan Chart diganti menjadi info yang lebih relevan --}}
        <div class="row mt-4">
            {{-- Bagian ini menampilkan status pengajuan HANYA untuk industri ini --}}
            <div class="col-lg-5 mb-lg-0 mb-4">
                <div class="card h-100">
                    <div class="card-header pt-3 pb-0 bg-transparent d-flex justify-content-between align-items-center">
                        <h6 class="text-capitalize mb-0">Status Pengajuan Pelamar</h6>
                        <a href="{{ route('industri.lowongan.index') }}" class="text-sm font-weight-bold">
                            Kelola Pelamar <i class="fa fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body p-3 d-flex flex-column justify-content-center">
                        <ul class="list-group list-group-flush">

                            {{-- 1. Menunggu Review --}}
                            <li class="list-group-item border-0 d-flex align-items-center px-0 mb-3">
                                <div class="col-auto">
                                    <div
                                        class="icon icon-shape icon-sm shadow border-radius-md bg-gradient-primary text-center me-3">
                                        <i class="fas fa-hourglass-half text-white"></i>
                                    </div>
                                </div>
                                {{-- TAMBAHKAN KELAS 'text-end' DI SINI --}}
                                <div class="col ps-0 text-end">
                                    <p class="text-xs mb-0 text-uppercase font-weight-bold">Menunggu Review</p>
                                    <h6 class="font-weight-bolder mb-0">{{ $pengajuanMenunggu }}
                                        <span class="text-sm text-muted font-weight-normal">Pelamar</span>
                                    </h6>
                                </div>
                            </li>

                            {{-- 2. Diterima --}}
                            <li class="list-group-item border-0 d-flex align-items-center px-0 mb-3">
                                <div class="col-auto">
                                    <div
                                        class="icon icon-shape icon-sm shadow border-radius-md bg-gradient-success text-center me-3">
                                        <i class="fas fa-check-circle text-white"></i>
                                    </div>
                                </div>
                                {{-- TAMBAHKAN KELAS 'text-end' DI SINI --}}
                                <div class="col ps-0 text-end">
                                    <p class="text-xs mb-0 text-uppercase font-weight-bold">Diterima</p>
                                    <h6 class="font-weight-bolder mb-0">{{ $pengajuanDiterima }}
                                        <span class="text-sm text-muted font-weight-normal">Pelamar</span>
                                    </h6>
                                </div>
                            </li>

                            {{-- 3. Ditolak --}}
                            <li class="list-group-item border-0 d-flex align-items-center px-0">
                                <div class="col-auto">
                                    <div
                                        class="icon icon-shape icon-sm shadow border-radius-md bg-gradient-danger text-center me-3">
                                        <i class="fas fa-times-circle text-white"></i>
                                    </div>
                                </div>
                                {{-- TAMBAHKAN KELAS 'text-end' DI SINI --}}
                                <div class="col ps-0 text-end">
                                    <p class="text-xs mb-0 text-uppercase font-weight-bold">Ditolak</p>
                                    <h6 class="font-weight-bolder mb-0">{{ $pengajuanDitolak }}
                                        <span class="text-sm text-muted font-weight-normal">Pelamar</span>
                                    </h6>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            {{-- Bagian ini menampilkan daftar pelamar terbaru, menggantikan chart distribusi industri --}}
            <div class="col-lg-7">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <h6>Pelamar Terbaru</h6>
                    </div>
                    <div class="card-body p-3">
                        <ul class="list-group">
                            @forelse ($pelamarTerbaru as $pelamar)
                                <li
                                    class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        @php
                                            // Cek apakah ada file foto yang valid
                                            $fotoExists =
                                                $pelamar->mahasiswa->foto &&
                                                Illuminate\Support\Facades\Storage::disk('public')->exists(
                                                    'mahasiswa/foto/' . $pelamar->mahasiswa->foto,
                                                );
                                            $fotoUrl = $fotoExists
                                                ? asset('storage/mahasiswa/foto/' . $pelamar->mahasiswa->foto)
                                                : asset('assets/default-profile.png');
                                        @endphp

                                        <img src="{{ $fotoUrl }}"
                                            alt="Foto {{ $pelamar->mahasiswa->nama_mahasiswa }}"
                                            class="avatar avatar-sm me-3 shadow-sm">
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark text-sm">{{ $pelamar->mahasiswa->nama_lengkap }}
                                            </h6>
                                            <span class="text-xs">Mendaftar untuk <span
                                                    class="font-weight-bold">{{ $pelamar->lowongan->judul_lowongan }}</span></span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('industri.lowongan.pendaftar.show_profil', [
                                            'pengajuan' => $pelamar->pengajuan_id,
                                            'from' => 'dashboard', // Menandakan datang dari halaman detail lowongan
                                        ]) }}"
                                            class="btn btn-link btn-sm text-dark p-0" title="Lihat Detail Profil">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </li>
                            @empty
                                <div class="text-center p-3">
                                    <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                                    <p class="text-sm text-muted">Belum ada pelamar baru.</p>
                                </div>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- DIUBAH: Aksi Cepat yang relevan untuk Industri --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">Aksi Cepat</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-md-3 col-6 text-center mb-4">
                                <a href="{{ route('industri.lowongan.create') }}"
                                    class="btn bg-gradient-success btn-icon-only btn-rounded mb-0">
                                    <i class="fas fa-briefcase" aria-hidden="true"></i>
                                </a>
                                <p class="text-sm mt-2 mb-0">Buat Lowongan Baru</p>
                            </div>
                            <div class="col-md-3 col-6 text-center mb-4">
                                <a href="{{ route('industri.lowongan.index') }}"
                                    class="btn bg-gradient-info btn-icon-only btn-rounded mb-0">
                                    <i class="fas fa-tasks" aria-hidden="true"></i>
                                </a>
                                <p class="text-sm mt-2 mb-0">Kelola Pelamar</p>
                            </div>
                            <div class="col-md-3 col-6 text-center">
                                <a href="{{ route('profile.index') }}"
                                    class="btn bg-gradient-primary btn-icon-only btn-rounded mb-0">
                                    <i class="fas fa-id-card" aria-hidden="true"></i>
                                </a>
                                <p class="text-sm mt-2 mb-0">Profil Perusahaan</p>
                            </div>
                            <div class="col-md-3 col-6 text-center">
                                <a href="{{ route('industri.magang.index') }}"
                                    class="btn bg-gradient-warning btn-icon-only btn-rounded mb-0">
                                    <i class="fas fa-history" aria-hidden="true"></i>
                                </a>
                                <p class="text-sm mt-2 mb-0">Kelola Magang</p>
                            </div>
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
        // Fungsi modal bisa tetap digunakan jika diperlukan untuk create/edit lowongan
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
    </script>
@endpush
