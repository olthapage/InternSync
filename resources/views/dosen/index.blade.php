@extends('layouts.template')

@section('content')
<div class="mt-4">
    <h2 class="mb-4">Daftar Dosen</h2>
    <div class="d-flex justify-content-end mb-3">
        <button onclick="modalAction('{{ route('dosen.create') }}')" class="btn btn-sm btn-primary">+ Tambah Dosen</button>
    </div>
    <div class="table-responsive text-sm">
        <table class="table table-bordered table-striped table-hover align-middle" id="table_dosen">
            <thead class="table-dark text-center">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Lengkap</th>
                    <th scope="col">Email</th>
                    <th scope="col">NIP</th>
                    <th scope="col">Program Studi</th>
                    <th scope="col">Aksi</th>
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

    var dataDosen;

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        dataDosen = $('#table_dosen').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('dosen/list') }}",
                dataType: "json",
                type: "POST",
                data: function(d) {
                    d.level_id = $('#level_id').val();
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "nama_lengkap", className: "text-center" },
                { data: "email", className: "text-center" },
                { data: "nip", className: "text-center", orderable: false },
                { data: "prodi", className: "text-center", orderable: false },
                {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        var btn = data
                            .replace(
                                /<a href="([^"]+)" class="btn btn-info btn-sm">Detail<\/a>/,
                                '<button class="btn btn-info btn-sm" onclick="modalAction(\'$1\')">Detail</button>'
                            );
                        return btn;
                    }
                }
            ]
        });

        $('#level_id').on('change', function() {
            dataDosen.ajax.reload();
        });
    });
</script>
@endpush