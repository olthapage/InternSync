@extends('layouts.template')

@section('title', 'Manajemen Magang - ' . ($userIndustri->industri_nama ?? 'Industri'))

@push('css')
    <style>
        #table_manajemen_magang th,
        #table_manajemen_magang td {
            vertical-align: middle; /* Vertically align content in cells */
        }
        .avatar.avatar-sm {
            width: 36px !important; /* Slightly larger avatar for clarity */
            height: 36px !important;
        }
        .table th, .table td {
            white-space: nowrap; /* Prevent text wrapping, use with responsive tables */
        }
        .badge {
            font-size: 0.85em; /* Slightly larger badge text */
            padding: 0.4em 0.6em;
        }
        .btn-xs { /* Custom class for smaller buttons if needed */
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
    </style>
@endpush

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h3 class="mb-0 text-dark-blue">
            <i class="fas fa-tasks me-2"></i> Manajemen Mahasiswa Magang
            @if(isset($userIndustri) && $userIndustri->industri_nama)
                <span class="text-muted">| {{ $userIndustri->industri_nama }}</span>
            @endif
        </h3>
        {{-- Tombol Tambah Aksi Global (jika ada) --}}
        {{-- <a href="#" class="btn btn-sm btn-success"><i class="fas fa-plus me-1"></i> Tambah Penilaian Global</a> --}}
    </div>
    <div class="card-body text-sm">
        {{-- Baris Filter --}}
        <div class="row gx-2 gy-2 mb-3 align-items-end">
            <div class="col-md-4">
                <label for="filter_lowongan_id" class="form-label">Filter Lowongan:</label>
                <select name="filter_lowongan_id" id="filter_lowongan_id" class="form-select form-select-sm">
                    <option value="">-- Semua Lowongan --</option>
                    @foreach ($listLowonganIndustri as $lowongan)
                        <option value="{{ $lowongan->lowongan_id }}">{{ Str::limit($lowongan->judul_lowongan, 50) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="filter_status_magang" class="form-label">Filter Status Magang:</label>
                <select name="filter_status_magang" id="filter_status_magang" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    @foreach ($listStatusMagang as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1"> {{-- Spacer --}} </div>
            {{-- Tambahkan filter lain jika perlu, misal periode --}}
            {{-- <div class="col-md-2">
                <button type="button" id="btnResetFilter" class="btn btn-sm btn-outline-secondary w-100"><i class="fas fa-undo me-1"></i> Reset</button>
            </div> --}}
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-items-center mb-0 text-center" id="table_manajemen_magang" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-start">Mahasiswa</th>
                        <th>Lowongan</th>
                        <th class="text-center">Periode</th>
                        <th class="text-center">Status Magang</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Data diisi oleh DataTables --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const dataManajemenMagang = $('#table_manajemen_magang').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('industri.magang.list') }}",
                    type: "POST",
                    data: function(d) {
                        d.filter_lowongan_id = $('#filter_lowongan_id').val();
                        d.filter_status_magang = $('#filter_status_magang').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
                    { data: 'mahasiswa_detail', name: 'mahasiswa.nama_lengkap', className: 'text-start' }, // Sorting/searching berdasarkan nama
                    { data: 'lowongan_judul', name: 'lowongan.judul_lowongan' },
                    { data: 'periode_magang', name: 'lowongan.tanggal_mulai', className: 'text-center' }, // Sorting berdasarkan tanggal mulai lowongan
                    { data: 'status_magang', name: 'status', className: 'text-center' }, // Sorting berdasarkan status di MagangModel
                    { data: 'aksi', name: 'aksi', className: 'text-center', orderable: false, searchable: false }
                ],
                language: {
                    search: "", // Kosongkan default label
                    searchPlaceholder: "Cari Mahasiswa / Lowongan...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Tidak ditemukan mahasiswa magang yang sesuai",
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
                order: [[1, 'asc']], // Default sorting by nama mahasiswa
                // Menyesuaikan DOM untuk penempatan elemen DataTables (opsional)
                // dom:  "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                //       "<'row'<'col-sm-12'tr>>" +
                //       "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            });

            // Handler untuk filter
            $('#filter_lowongan_id, #filter_status_magang').on('change', function() {
                dataManajemenMagang.ajax.reload();
            });

            // $('#btnResetFilter').on('click', function() {
            //     $('#filter_lowongan_id').val('').trigger('change');
            //     $('#filter_status_magang').val(''); // Tidak perlu trigger change jika change berikutnya akan reload
            //     // dataManajemenMagang.ajax.reload(); // Dihandle oleh change event di atas
            // });
        });
    </script>
@endpush
