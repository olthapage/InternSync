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
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center" id="table_mahasiswa">
                    <thead>
                        <tr>
                            <th class="text-start">No</th>
                            <th>Nama Lengkap</th>
                            <th>Email</th>
                            <th>Program Studi</th>
                            <th>Status Magang</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog"
        data-backdrop="static" data-keyboard="false" aria-hidden="true"></div>
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
