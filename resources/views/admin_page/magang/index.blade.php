@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Daftar Mahasiswa Magang</h2>

            <!-- Container tombol di bawah judul -->
            <div class="d-flex justify-content-end mb-3">
                <a href="#" class="btn btn-primary">+ Tambah Mahasiswa Magang</a>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="table_mahasiswa">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Mahasiswa</th>
                        <th>Magang</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
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

        var dataMagang;

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            dataMhs = $('#table_mahasiswa').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('magang/list') }}",
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
                        data: "mahasiswa",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "lowongan",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "status",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ]

            });
            $('#lowongan_id').on('change', function() {
                dataMagang.ajax.reload();
            });
        });
    </script>
@endpush
