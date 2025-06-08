@extends('layouts.template')

@section('title', 'Daftar Permintaan Akun - Admin') {{-- Title disesuaikan --}}

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Manajemen Permintaan Akun</h2>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center" id="table_akun"> {{-- Tambah table-bordered & text-sm --}}
                    <thead class=""> {{-- Thead lebih terang --}}
                        <tr>
                            <th class="text-center" style="width:5%;">No</th>
                            <th style="width:25%;">Nama Lengkap</th>
                            <th style="width:20%;">Email</th>
                            <th style="width:15%;">NIM/NIDN</th>
                            <th style="width:15%;">Perkiraan Role</th>
                            <th class="text-center" style="width:10%;">Status</th>
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
        $('#myModal').html(`
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body text-center p-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat konten...</p>
                    </div>
                </div>
            </div>
        `).modal('show');

        // Load konten modal dari URL
        $('#myModal').load(url);
    }

    let dataAkun; // Gunakan nama ini secara konsisten

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        dataAkun = $('#table_akun').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('validasi-akun.list') }}",
                type: "POST", // Ubah ke GET jika route GET
                dataType: "json",
                // Tambahkan parameter filter jika perlu
                data: function(d) {
                    // Contoh: d.level_id = $('#level_id_filter').val();
                }
            },
            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "nama_lengkap", name: "nama_lengkap", className: "text-start" },
                { data: "email", name: "email", className: "text-start" },
                { data: "username", name: "username", className: "text-start", orderable: false, searchable: false },
                { data: "perkiraan_role", name: "perkiraan_role", className: "text-start", orderable: false, searchable: false },
                { data: "status_validasi", name: "status_validasi", className: "text-center", orderable: false, searchable: false },
                { data: "aksi", className: "text-center", orderable: false, searchable: false }
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Cari Akun...",
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
            // order: [[1, 'asc']], // Aktifkan jika perlu urutan default
        });

        // Filter tambahan (jika ada)
        // $('#level_id_filter').on('change', function() {
        //     dataAkun.ajax.reload();
        // });
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