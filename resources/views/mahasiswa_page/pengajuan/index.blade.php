@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Pengajuan Magang</h2>

            <div class="d-flex justify-content-end mb-3">
                <button onclick="modalAction('{{ route('mahasiswa.pengajuan.create') }}')" class="btn btn-sm btn-primary">
                    + Tambah Pengajuan
                </button>
            </div>

            <h5 class="mb-2">Riwayat Pengajuan</h5>

            @if ($pengajuan->isEmpty())
                <p>Belum ada pengajuan.</p>
            @else
                <div class="d-flex overflow-auto gap-3 pb-2">
                    @foreach ($pengajuan as $item)
                        <div class="border rounded p-3 min-width-card bg-light">
                            <h6 class="fw-bold mb-1">{{ $item->lowongan->judul_lowongan }}</h6>
                            <p class="mb-1">Industri: {{ $item->lowongan->industri->nama_industri ?? '-' }}</p>
                            <p class="mb-2">
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
                            <div class="text-end">
                                <a href="{{ route('mahasiswa.pengajuan.show', $item->pengajuan_id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <style>
        .min-width-card {
            min-width: 250px;
        }
    </style>
@endsection
