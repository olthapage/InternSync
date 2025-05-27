@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-dark shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Detail Lowongan: {{ $lowongan->judul_lowongan }}</h3>
                        <div class="d-flex align-items-center">
                            {{-- Menambahkan Status Pendaftaran di Header Card Detail Lowongan --}}
                            @if ($lowongan->pendaftaran_tanggal_mulai && $lowongan->pendaftaran_tanggal_selesai)
                                <span class="badge badge-{{ $lowongan->status_pendaftaran_badge_class }} mr-3"
                                      style="font-size: 0.9rem;">
                                    {{ $lowongan->status_pendaftaran_text }}
                                </span>
                            @endif
                            <a href="{{ route('industri.lowongan.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p><strong>Industri:</strong> {{ $lowongan->industri->industri_nama }}</p>
                        <p><strong>Kategori:</strong> {{ $lowongan->kategoriSkill->kategori_nama ?? 'Umum' }}</p>
                        <p><strong>Slot Tersedia:</strong> {{ $lowongan->slotTersedia() }} dari {{ $lowongan->slot }}</p>

                        {{-- Periode Pelaksanaan Magang --}}
                        <p><strong>Periode Pelaksanaan Magang:</strong>
                            @if ($lowongan->tanggal_mulai && $lowongan->tanggal_selesai)
                                {{ $lowongan->tanggal_mulai->isoFormat('D MMMM YYYY') }} -
                                {{ $lowongan->tanggal_selesai->isoFormat('D MMMM YYYY') }}
                            @else
                                Belum diatur
                            @endif
                        </p>

                        {{-- Periode Pendaftaran --}}
                        <p><strong>Periode Pendaftaran:</strong>
                            @if ($lowongan->pendaftaran_tanggal_mulai && $lowongan->pendaftaran_tanggal_selesai)
                                {{ $lowongan->pendaftaran_tanggal_mulai->isoFormat('D MMMM YYYY') }} -
                                {{ $lowongan->pendaftaran_tanggal_selesai->isoFormat('D MMMM YYYY') }}
                            @else
                                Belum diatur
                            @endif
                        </p>

                        {{-- Status Pendaftaran (jika tidak di header) --}}
                        {{-- <p><strong>Status Pendaftaran:</strong>
                            @if ($lowongan->pendaftaran_tanggal_mulai && $lowongan->pendaftaran_tanggal_selesai)
                                <span class="badge badge-{{ $lowongan->status_pendaftaran_badge_class }}">{{ $lowongan->status_pendaftaran_text }}</span>
                            @else
                                <span class="badge badge-secondary">Periode Belum Diatur</span>
                            @endif
                        </p> --}}

                        <div>
                            <strong>Deskripsi:</strong>
                            {!! $lowongan->deskripsi !!}
                        </div>
                        @if ($lowongan->lowonganSkill->isNotEmpty())
                            <div class="mt-3">
                                <strong>Skill yang Dibutuhkan:</strong>
                                <ul>
                                    @foreach ($lowongan->lowonganSkill as $item)
                                        <li>{{ $item->skill->skill_nama ?? 'Skill tidak tersedia' }}</li>
                                        {{-- Asumsi ada relasi skill() di LowonganSkillModel dan nama_skill di SkillModel --}}
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {{-- Tambahkan detail lowongan lainnya jika perlu --}}
                    </div>
                </div>

                {{-- Card Daftar Pendaftar --}}
                <div class="card border-dark shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Daftar Pendaftar ({{ $lowongan->pendaftar->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @if ($lowongan->pendaftar->isEmpty())
                            <div class="alert alert-secondary text-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Belum ada mahasiswa yang mendaftar pada lowongan ini.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-items-center mb-0 text-center">
                                    {{-- ... isi tabel pendaftar ... --}}
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Email Mahasiswa</th>
                                            <th>NIM</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Status Pengajuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lowongan->pendaftar as $index => $pengajuan)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $pengajuan->mahasiswa->nama_lengkap ?? 'Nama Tidak Tersedia' }}</td>
                                                <td>{{ $pengajuan->mahasiswa->email ?? '-' }}</td>
                                                <td>{{ $pengajuan->mahasiswa->nim ?? '-' }}</td>
                                                <td>{{ $pengajuan->created_at ? \Carbon\Carbon::parse($pengajuan->created_at)->isoFormat('D MMM YY, HH:mm') : '-' }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge text-dark badge-{{ $pengajuan->status == 'diterima' ? 'success' : ($pengajuan->status == 'ditolak' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($pengajuan->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('css')
    {{-- Jika ada CSS tambahan khusus halaman ini --}}
    <style>
        .card-header h5 {
            font-weight: 600;
        }
    </style>
@endpush

@push('js')
    {{-- Jika ada JS tambahan khusus halaman ini --}}
    <script>
        // Contoh: console.log('Halaman detail lowongan dimuat');
    </script>
@endpush
