@extends('layouts.template')

@section('title', 'Mahasiswa DPA - ' . ($dpa->nama_lengkap ?? 'Dosen'))

@push('css')
    {{-- Asumsikan CSS DataTables sudah dimuat oleh layouts.template --}}
    <style>
        #table_mahasiswa_dpa th,
        #table_mahasiswa_dpa td {
            vertical-align: middle;
        }
        .avatar.avatar-sm { /* Ukuran avatar di tabel */
            width: 38px !important;
            height: 38px !important;
        }
        .table th, .table td { /* Mencegah text wrap agar rapi */
            white-space: nowrap;
        }
        .badge { /* Ukuran badge agar lebih pas */
            font-size: 0.85em;
            padding: 0.4em 0.65em;
        }
    </style>
@endpush

@section('content')
<div class="container-fluid py-3">
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-dark-blue">
                <i class="fas fa-users-cog me-2"></i> Daftar Mahasiswa untuk Validasi Skill DPA
            </h5>
            @if(isset($prodiName) && $dpa->prodi_id) {{-- Tampilkan prodi jika DPA punya prodi --}}
                <span class="text-muted">Program Studi: <strong>{{ $prodiName }}</strong></span>
            @elseif(isset($dpa) && !$dpa->prodi_id)
                 <span class="text-danger">Anda tidak terasosiasi dengan Program Studi tertentu.</span>
            @endif
        </div>
        <div class="card-body text-sm">
            @if(is_null(Auth::user()->prodi_id))
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Anda saat ini tidak terhubung dengan Program Studi tertentu. Hubungi administrator untuk penetapan Program Studi Anda agar dapat melihat daftar mahasiswa.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center" id="table_mahasiswa_dpa" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 40%;">Mahasiswa</th>
                                <th style="width: 30%;">Program Studi</th>
                                <th style="width: 15%;">Skill Pending</th>
                                <th style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data akan diisi oleh DataTables --}}
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('js')
    {{-- Asumsikan jQuery dan DataTables JS sudah dimuat oleh layouts.template --}}
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Hanya inisialisasi DataTables jika DPA memiliki prodi_id
            // (atau sesuaikan kondisi ini jika DPA tanpa prodi boleh melihat data lain)
            @if(Auth::user()->prodi_id)
                const dataMahasiswaDpa = $('#table_mahasiswa_dpa').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true, // Penting untuk tabel yang responsif
                    ajax: {
                        url: "{{ route('dosen.mahasiswa-dpa.list') }}", // Pastikan nama rute ini benar
                        type: "POST",
                        data: function(d) {
                            // Kirim filter tambahan jika ada
                        }
                    },
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
                        { data: 'nama_lengkap_mahasiswa', name: 'nama_lengkap', className: 'text-start' },
                        { data: 'prodi_mahasiswa', name: 'prodi.nama_prodi' }, // Untuk sorting/searching server-side by prodi.nama_prodi
                        { data: 'skill_pending_count', name: 'skill_pending_count', className: 'text-center', orderable: false, searchable: false },
                        { data: 'aksi', name: 'aksi', className: 'text-center', orderable: false, searchable: false }
                    ],
                    language: { // Kustomisasi bahasa DataTables
                        search: "",
                        searchPlaceholder: "Cari Nama / NIM Mahasiswa...",
                        lengthMenu: "Tampilkan _MENU_ mahasiswa",
                        zeroRecords: "Tidak ditemukan mahasiswa untuk Program Studi Anda.",
                        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ mahasiswa",
                        infoEmpty: "Tidak ada data mahasiswa yang dapat ditampilkan",
                        infoFiltered: "(disaring dari _MAX_ total mahasiswa)",
                        paginate: {
                            first: "<i class='fas fa-angle-double-left'></i>",
                            last: "<i class='fas fa-angle-double-right'></i>",
                            next: "<i class='fas fa-angle-right'></i>",
                            previous: "<i class='fas fa-angle-left'></i>"
                        },
                        processing: '<div class="d-flex justify-content-center align-items-center p-3"><i class="fas fa-spinner fa-pulse fa-lg fa-fw text-primary me-2"></i><span class="fst-italic">Memuat data mahasiswa...</span></div>'
                    },
                    order: [[1, 'asc']], // Default sorting by nama mahasiswa
                });
            @endif
        });
    </script>
@endpush
