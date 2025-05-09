@extends('layouts.template')

@section('content')
    <div class="mt-4">
        <h2 class="mb-4">Daftar Mahasiswa</h2>

        <!-- Container tombol di bawah judul -->
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">+ Tambah Mahasiswa</a>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="table_mahasiswa">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Program Studi</th>
                    <th>Status Magang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        var dataMhs;

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            dataMhs = $('#table_mahasiswa').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('mahasiswa/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function(d) {
                        d.level_id = $('#level_id').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "nama_lengkap",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "email",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "prodi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "status",
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
                ]

            });
            $('#level_id').on('change', function() {
                dataMhs.ajax.reload();
            });
        });
    </script>
@endpush
