@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Daftar Mahasiswa</h2>
            <div class="d-flex justify-content-end mb-3">
                <button onclick="modalAction('{{ route('mahasiswa.create') }}')" class="btn btn-sm btn-primary">
                    + Tambah Mahasiswa
                </button>
            </div>
            <div class="table-responsive text-sm">
                <table class="table table-bordered table-striped table-hover align-middle" id="table_mahasiswa">
                    <thead class="table-dark text-center">
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Nama Lengkap</th>
                            <th scope="col">Email</th>
                            <th scope="col">Program Studi</th>
                            <th scope="col">Status Magang</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
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
                    url: "{{ url('mahasiswa/list') }}",
                    type: "POST",
                    dataType: "json",
                    data: function(d) {
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
                        className: ""
                    },
                    {
                        data: "email",
                        className: ""
                    },
                    {
                        data: "prodi",
                        className: "text-center",
                        orderable: false
                    },
                    {
                        data: "status",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return data == 1 ? 'Aktif' : 'Tidak Aktif';
                        }
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return data;
                        }
                    }
                ]
            });

            $('#level_id').on('change', function() {
                dataMhs.ajax.reload();
            });
        });
    </script>
@endpush
