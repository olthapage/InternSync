@extends('layouts.template')

@section('content')
<div class="mt-4">
    <h2 class="mb-4">Daftar Admin</h2>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.create') }}" class="btn btn-primary">Tambah Admin</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle" id="table_admin">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
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

        var dataAdmin;

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            dataAdmin = $('#table_admin').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('admin/list') }}",
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
                        className: "text-center",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "email",
                        className: "text-center",
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
            $('#level_id').on('change', function() {
                dataAdmin.ajax.reload();
            });
        });
    </script>
@endpush
