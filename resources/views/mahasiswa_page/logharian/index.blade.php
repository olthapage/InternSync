@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Log Harian Magang</h2>

            <div class="row mb-4">
                <div class="col-md-3">
                    <input type="date" id="tanggal" class="form-control" placeholder="Filter tanggal...">
                </div>
                <div class="col-md-9 d-flex justify-content-end">
                    <button onclick="modalAction('{{ route('logHarian.create') }}')" class="btn btn-sm btn-primary me-2">
                        Tambah Log Harian
                    </button>
                    <a href="{{ route('logHarian.export_pdf') }}" class="btn btn-sm btn-secondary">
                        <i class="fa fa-file-pdf"></i> Export Log Book (pdf)
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center" id="table_logharian" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-start">Tanggal</th>
                            <th>Isi Log</th>
                            <th>Lokasi Kegiatan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
        aria-hidden="true"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        function deleteLog(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus log ini?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/log-harian/' + id + '/delete',
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#table_logharian').DataTable().ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'Log berhasil dihapus.'
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus log.'
                            });
                        }
                    });
                }
            });
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const tableLogHarian = $('#table_logharian').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('logHarian.list') }}",
                    type: "POST",
                    data: function(d) {
                        d.tanggal = $('#tanggal').val();
                    }
                },
                columns: [{
                        data: 'tanggal',
                        name: 'tanggal',
                        className: 'text-start'
                    },
                    {
                        data: 'isi',
                        name: 'isi',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'lokasi_kegiatan',
                        name: 'lokasi_kegiatan'
                    },
                    {
                        data: 'status_approval_dosen',
                        name: 'status_approval_dosen',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                language: {
                    search: "Cari:",
                    searchPlaceholder: "Isi log...",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ada data log ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                order: [
                    [0, 'desc']
                ]
            });

            $('#tanggal').on('change', function() {
                tableLogHarian.ajax.reload(null, false);
            });
        });
    </script>
@endpush
