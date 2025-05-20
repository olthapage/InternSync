@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Pengajuan Magang</h2>
            {{-- Cek kelengkapan profil mahasiswa --}}
            @php
                $mahasiswa = auth()->user();
                $profilLengkap =
                    $mahasiswa &&
                    $mahasiswa->status_verifikasi == 'valid'
            @endphp

            @unless ($profilLengkap)
                <div class="border p-3 mb-3 rounded text-danger">
                    <strong>Profil belum lengkap atau invalid!</strong> Silakan lengkapi data verifikasi seperti KTP, KHS, Surat Izin Orang
                    Tua, dan CV sebelum mengajukan magang.
                </div>
            @else
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-sm btn-primary">
                        + Tambah Pengajuan
                    </a>
                </div>

                <h5 class="mb-2">Riwayat Pengajuan</h5>

                @if ($pengajuan->isEmpty())
                    <p>Belum ada pengajuan.</p>
                @else
                    <div class="row g-3">
                        @foreach ($pengajuan as $item)
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card card-blog card-plain border rounded">
                                    <div class="position-relative">
                                        <div class="image-container">
                                            <img src="{{ asset('softTemplate/assets/img/home-decor-3.jpg') }}"
                                                alt="Lowongan Image" class="img-fluid border-radius-lg px-3 pt-4 rounded">
                                        </div>
                                    </div>
                                    <div class="card-body px-3 pb-2">
                                        <p class="text-gradient text-primary mb-1 text-sm">
                                            {{ $item->lowongan->industri->industri_nama ?? '-' }}</p>
                                        <h5 class="font-weight-bold mb-2">
                                            {{ $item->lowongan->judul_lowongan }}
                                        </h5>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                                            <span
                                                class="text-sm">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}
                                                - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}</span>
                                        </div>
                                        <p class="mb-3 text-sm">
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
                                        </p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <a href="{{ route('mahasiswa.pengajuan.show', $item->pengajuan_id) }}"
                                                class="btn btn-outline-primary btn-sm mb-0">
                                                <i class="fas fa-eye me-1"></i> Lihat Detail
                                            </a>
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

    <style>
        .card-blog {
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.125) !important;
        }

        .card-blog:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .badge {
            font-size: 0.75rem;
        }

        .image-container {
            height: 180px;
            overflow: hidden;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
    </style>
@endsection
