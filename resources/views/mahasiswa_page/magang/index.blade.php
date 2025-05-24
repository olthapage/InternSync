@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2>Status Magang Saya</h2>
            <hr>

            @if ($magang->isEmpty())
                <div class="alert alert-info text-center" role="alert">
                    <h4 class="alert-heading">Informasi</h4>
                    <p>Anda saat ini belum terdaftar atau diterima pada program magang manapun.</p>
                    <p class="mb-0">Silakan coba untuk mengajukan diri (apply) pada lowongan yang tersedia.</p>
                    <a href="{{ route('mahasiswa.lowongan.index') }}" class="btn btn-primary mt-3">Lihat Lowongan Magang</a>
                    {{-- Ganti href="#" dengan route yang sesuai untuk melihat lowongan --}}
                </div>
            @else
                <div class="card">
                    <div class="card-header">
                        Detail Magang Anda
                    </div>
                    <div class="card-body">
                        @foreach ($magang as $item)
                            <div class="mb-4 p-3 border rounded">
                                <h5>
                                    @if ($item->lowongan && $item->lowongan->lowongan)
                                        {{-- Asumsi DetailLowonganModel punya relasi ke LowonganModel yang punya nama_lowongan --}}
                                        {{-- Atau jika DetailLowonganModel langsung punya judul/nama lowongan --}}
                                        Posisi: {{ $item->lowongan->judul_lowongan ?? 'Informasi Lowongan Tidak Tersedia' }}
                                        {{-- Sesuaikan dengan atribut yang benar di DetailLowonganModel untuk nama/judul lowongan --}}
                                    @else
                                        Informasi Lowongan Tidak Tersedia
                                    @endif
                                </h5>
                                <p>
                                    <strong>Status:</strong>
                                    @if ($item->status == 'diterima')
                                        <span class="badge bg-success text-capitalize">{{ $item->status }}</span>
                                    @elseif ($item->status == 'ditolak')
                                        <span class="badge bg-danger text-capitalize">{{ $item->status }}</span>
                                    @elseif ($item->status == 'pending' || $item->status == 'diajukan')
                                        <span class="badge bg-warning text-capitalize">{{ $item->status }}</span>
                                    @else
                                        <span
                                            class="badge bg-secondary text-capitalize">{{ $item->status ?? 'Belum ada status' }}</span>
                                    @endif
                                </p>

                                {{-- Menampilkan detail mahasiswa (jika perlu, tapi biasanya tidak di halaman status magang sendiri) --}}
                                {{-- <p><strong>Mahasiswa:</strong> {{ $item->mahasiswa->nama_lengkap ?? 'N/A' }}</p> --}}

                                @if ($item->evaluasi)
                                    <p><strong>Evaluasi:</strong></p>
                                    <div class="p-2 bg-light border rounded">
                                        {!! nl2br(e($item->evaluasi)) !!}
                                    </div>
                                @else
                                    <p><strong>Evaluasi:</strong> Belum ada evaluasi.</p>
                                @endif

                                {{-- Anda bisa menambahkan informasi lain dari $item->lowongan di sini --}}
                                {{-- Contoh:
                                @if ($item->lowongan)
                                    <p><strong>Perusahaan:</strong> {{ $item->lowongan->perusahaan->nama_perusahaan ?? 'N/A' }}</p>
                                    <p><strong>Durasi:</strong> {{ $item->lowongan->durasi ?? 'N/A' }}</p>
                                @endif
                                --}}
                            </div>
                            @if (!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
