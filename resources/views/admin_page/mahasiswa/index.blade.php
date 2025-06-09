@extends('layouts.template')

@section('title', 'Daftar Mahasiswa - Admin') {{-- Title disesuaikan --}}

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Manajemen Data Mahasiswa</h2>
            <div class="d-flex justify-content-end mb-3">
                <button onclick="modalAction('{{ route('mahasiswa.create') }}')" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-1"></i> Tambah Mahasiswa
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center" id="table_mahasiswa"> {{-- Tambah table-bordered & text-sm --}}
                    <thead class=""> {{-- Thead lebih terang --}}
                        <tr>
                            <th class="text-center" style="width:5%;">No</th>
                            <th style="width:25%;">Nama Mahasiswa & NIM</th>
                            <th style="width:20%;">Email & Prodi</th>
                            <th style="width:15%;">DPA</th>
                            <th style="width:15%;">Dosen Pembimbing Magang</th>
                            <th class="text-center" style="width:10%;">Status Magang</th>
                            <th class="text-center" style="width:10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- DataTables akan mengisi ini --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- Modal container --}}
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-hidden="true"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            // Kosongkan modal dulu untuk menghindari konten lama muncul saat loading
            $('#myModal').html(
                '<div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-body text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Memuat konten...</p></div></div></div>'
                ).modal('show');
            $('#myModal').load(url, function() {
                // Modal sudah ditampilkan oleh .modal('show') di atas.
                // Jika Anda menggunakan instance Bootstrap 5 manual:
                // const modalElement = document.getElementById('myModal');
                // const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                // modal.show();
            });
        }

        var dataMhs; // dataMhs harusnya dataTableInstance

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            dataTableInstance = $('#table_mahasiswa').DataTable({ // Simpan instance ke variabel yang benar
                processing: true, // Tampilkan pesan processing
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('mahasiswa.list') }}", // Pastikan nama route ini benar
                    type: "POST", // Atau GET jika method list Anda GET
                    dataType: "json",
                    data: function(d) {
                        // d.level_id = $('#level_id_filter').val(); // Jika ada filter
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "nama_lengkap_detail",
                        name: "nama_lengkap",
                        className: "text-start"
                    }, // nama_lengkap untuk searching/ordering
                    {
                        data: "email",
                        name: "email",
                        className: "text-start",
                        render: function(data, type, row) {
                            return `${row.email}<br><small class="text-muted">${row.prodi_nama}</small>`;
                        }
                    },
                    {
                        data: "dpa_nama",
                        name: "dpa.nama_lengkap",
                        className: "text-start",
                        orderable: false,
                        searchable: false
                    }, // 'dpa.nama_lengkap' untuk sort jika relasi 'dpa'
                    {
                        data: "pembimbing_nama",
                        name: "dosenPembimbing.nama_lengkap",
                        className: "text-start",
                        orderable: false,
                        searchable: false
                    }, // 'dosenPembimbing.nama_lengkap'
                    {
                        data: "status_magang_display",
                        name: "status_magang_display",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    /* ... Kustomisasi bahasa DataTables Anda ... */
                    search: "_INPUT_",
                    searchPlaceholder: "Cari Mahasiswa...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    infoEmpty: "Tidak ada data untuk ditampilkan",
                    infoFiltered: "(disaring dari _MAX_ total entri)",
                    zeroRecords: "Tidak ditemukan data yang cocok",
                    processing: '<i class="fas fa-spinner fa-spin fa-2x fa-fw text-primary"></i><span class="ms-2">Memuat...</span>',
                    paginate: {
                        first: "<i class='fas fa-angle-double-left'></i>",
                        last: "<i class='fas fa-angle-double-right'></i>",
                        next: "<i class='fas fa-angle-right'></i>",
                        previous: "<i class='fas fa-angle-left'></i>"
                    }
                },
            });
        });
    </script>
@endpush

@push('css')
    <style>
        /* ... (CSS Anda yang sudah ada) ... */
        .table th,
        .table td {
            white-space: normal;
            word-break: break-word;
        }

        /* Agar teks bisa wrap */
        .avatar-sm-table {
            width: 32px;
            height: 32px;
            object-fit: cover;
        }
    </style>
@endpush
