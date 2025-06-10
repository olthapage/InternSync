@extends('layouts.template')

@section('content')
<div class="card border-dark shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <h3 class="mb-0">Manajemen Lowongan</h3>
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

<div class="card card-outline card-primary mt-4">
        <div class="card-body text-sm">
            <h3 class="mb-4">Semua Pelamar</h3>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center" id="table_pengajuan">
                    <thead>
                        <tr>
                            <th class="text-start">No</th>
                            <th>Nama Mahasiswa</th>
                            <th>Judul Lowongan</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
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
    var dataPengajuan;

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            dataPengajuan = $('#table_pengajuan').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('industri/lowongan/pendaftar/list') }}",
                    type: "POST",
                    dataType: "json",
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "mahasiswa",
                        className: "text-center"
                    },
                    {
                        data: "lowongan",
                        className: "text-center"
                    },
                    {
                        data: "tanggal_pengajuan",
                        className: "text-center"
                    },
                    {
                        data: "status_pengajuan",
                        className: "text-center"
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return data.replace(
                                /<a href="([^"]+)" class="btn btn-info btn-sm">Detail<\/a>/,
                                '<button class="btn btn-info btn-sm" onclick="modalAction(\'$1\')">Detail</button>'
                            );
                        }
                    }
                ],
                language: {
                    search: "", // Kosongkan default label
                    searchPlaceholder: "Cari Pengajuan...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Tidak ditemukan pengajuan yang sesuai",
                    info: "Menampilkan _START_-_END_ dari _TOTAL_ entri",
                    infoEmpty: "Data tidak tersedia",
                    infoFiltered: "(disaring dari _MAX_ total entri)",
                    paginate: {
                        first: "<i class='fas fa-angle-double-left'></i>",
                        last: "<i class='fas fa-angle-double-right'></i>",
                        next: "<i class='fas fa-angle-right'></i>",
                        previous: "<i class='fas fa-angle-left'></i>"
                    },
                    processing: '<div class="d-flex justify-content-center"><i class="fas fa-spinner fa-pulse fa-2x fa-fw text-primary"></i><span class="ms-2">Memuat data...</span></div>'
                },
            });
        });
</script>
@endpush
