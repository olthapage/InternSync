@extends('layouts.template')

@section('content')
<div class="card border-dark shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <h3 class="mb-0">Daftar Lowongan - {{ $industri->industri_nama }}</h3>
        <a href="{{ route('industri.lowongan.create') }}" class="btn btn-outline-dark btn-sm">
            <i class="fas fa-plus mr-1"></i> Tambah Lowongan
        </a>
    </div>
    <div class="card-body">
        @if ($lowongan_industri->isEmpty())
            <div class="alert alert-secondary text-center">
                <i class="fas fa-info-circle mr-2"></i>
                Saat ini Anda belum memiliki lowongan pekerjaan yang dipublikasikan.
            </div>
        @else
            <div class="row">
                @foreach ($lowongan_industri as $lowongan)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 border rounded shadow-sm">
                            {{-- Atribut data-toggle dan data-target untuk modal bisa dipertahankan jika masih dipakai --}}
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h6 class="card-title font-weight-bold text-dark">
                                        {{ $lowongan->judul_lowongan }}
                                    </h6>
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-briefcase mr-2"></i>
                                        {{ $lowongan->kategoriSkill->kategori_nama ?? 'Umum' }}
                                    </p>
                                    <p class="mb-2 text-muted small">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($lowongan->deskripsi), 100) }}
                                    </p>
                                    <p class="mb-1">
                                        <i class="fas fa-users text-muted mr-2"></i>
                                        Slot: {{ $lowongan->slotTerisi() }}/{{ $lowongan->slot }}
                                    </p>
                                    <p class="mb-1">
                                        <i class="fas fa-user-check text-muted mr-2"></i>
                                        Pendaftar: {{ $lowongan->pengajuanMagangCount() }}
                                    </p>
                                    {{-- Periode Pelaksanaan Magang --}}
                                    <p class="mb-1">
                                        <i class="fas fa-calendar-alt text-muted mr-2"></i>
                                        Pelaksanaan:
                                        @if($lowongan->tanggal_mulai && $lowongan->tanggal_selesai)
                                            {{ $lowongan->tanggal_mulai->isoFormat('D MMM YY') }} - {{ $lowongan->tanggal_selesai->isoFormat('D MMM YY') }}
                                        @else
                                            Belum diatur
                                        @endif
                                    </p>
                                    {{-- Periode Pendaftaran --}}
                                    <p class="mb-0">
                                        <i class="fas fa-calendar-edit text-muted mr-2"></i>
                                        Pendaftaran:
                                        @if($lowongan->pendaftaran_tanggal_mulai && $lowongan->pendaftaran_tanggal_selesai)
                                            {{ $lowongan->pendaftaran_tanggal_mulai->isoFormat('D MMM YY') }} - {{ $lowongan->pendaftaran_tanggal_selesai->isoFormat('D MMM YY') }}
                                            <span class="ml-1 badge badge-{{ $lowongan->status_pendaftaran_badge_class }}">{{ $lowongan->status_pendaftaran_text }}</span>
                                        @else
                                            <span class="badge text-secondary badge-secondary">Periode Belum Diatur</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="mt-3">
                                    <a class="btn btn-outline-dark btn-sm w-100" href="{{ url('industri/lowongan/'. $lowongan->lowongan_id . '/show') }}">Buka</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
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
