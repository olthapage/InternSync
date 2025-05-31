@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <div class="mb-4 d-flex flex-column align-items-start gap-4">
                <h2>Pengajuan Magang</h2>

                {{-- Cek kelengkapan profil mahasiswa --}}
                @php
                    $mahasiswa = auth()->user();
                    $profilLengkap = $mahasiswa && $mahasiswa->status_verifikasi == 'valid';
                @endphp
                <p class="text-sm text-secondary">Sebelum mengajukan magang, lebih baik melengkapi portofoliomu dan usahakan sesuai dengan apa yang dibutuhkan perusahaan <br>
                untuk mendapatkan peluang diterima yang lebih besar!</p>

                @if ($profilLengkap)
                    <a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-plus me-2"></i>Tambah Pengajuan
                    </a>
                @endif
            </div>


            @unless ($profilLengkap)
                <div class="p-3 mb-3 rounded-xl text-danger shadow-sm">
                    <strong>Profil belum lengkap atau invalid!</strong> Silakan lengkapi data verifikasi seperti KTP, KHS, Surat
                    Izin Orang
                    Tua, dan CV sebelum mengajukan magang.
                    <p class="text-secondary">Lengkapi juga portofolio beserta skill yang kamu kuasai pada halaman <a href="{{ route('mahasiswa.portofolio.index') }}" class="text-info">Portofolio Saya</a></p>
                </div>
            @else
                <h5 class="mb-2">Riwayat Pengajuan</h5>

                @if ($pengajuan->isEmpty())
                    <p>Belum ada pengajuan.</p>
                @else
                    <div class="row mt-4 mb-4">
                        @foreach ($pengajuan as $item)
                            <div class="col-xl-3 col-md-6 mb-4 d-flex">
                                <div class="card card-blog card-plain shadow pengajuan-card rounded-xl py-3 w-100">
                                    <div class="position-relative">
                                        <div class="image-container py-3 pt-6">
                                            {{-- Gambar industri --}}
                                            <img src="{{ $item->lowongan->industri->logo ? asset('storage/logo_industri/' . $item->lowongan->industri->logo) : asset('assets/default-industri.png') }}"
                                                alt="Lowongan Image" class="img-fluid border-radius-lg rounded">
                                        </div>
                                    </div>
                                    <div class="card-body px-3 d-flex flex-column justify-content-between"
                                        style="min-height: 280px;">
                                        <div>
                                            <p class="text-gradient text-primary mb-1 text-sm">
                                                {{ $item->lowongan->industri->industri_nama ?? '-' }}
                                            </p>
                                            <h5 class="font-weight-bold mb-2">
                                                {{ $item->lowongan->judul_lowongan }}
                                            </h5>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                <span class="text-sm">
                                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }} -
                                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                                                </span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-info-circle text-primary me-2"></i>
                                                <span class="text-sm">
                                                    Status:
                                                    @if ($item->status == 'diproses')
                                                        <span class="badge bg-warning text-dark">Diproses</span>
                                                    @elseif ($item->status == 'diterima')
                                                        <span class="badge bg-success">Diterima</span>
                                                    @elseif ($item->status == 'ditolak')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-3">
                                            <button
                                                onclick="modalAction('{{ url('/mahasiswa/pengajuan/' . $item->pengajuan_id . '/show') }}')"
                                                class="btn btn-primary btn-sm mb-0 px-3 w-100 text-lg">
                                                Lihat Detail
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endunless
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
    </script>
@endpush

@push('css')
    <style>
        .pengajuan-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .pengajuan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .pengajuan-card .image-container img {
            max-height: 120px;
            width: auto;
            display: block;
            margin: 0 auto;
        }

        .deskripsi-terbatas {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endpush
