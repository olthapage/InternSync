@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Daftar Lowongan</h2>
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('lowongan.create') }}" class="btn btn-primary">+ Tambah Lowongan</a>
            </div>
            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Filter Lowongan:</label>
                <div class="col-3">
                    <select class="form-control" id="filter_lowongan" name="filter_lowongan">
                        <option value="">- Semua -</option>
                        @foreach ($lowongan as $low)
                            <option value="{{ $low->lowongan_id }}">{{ $low->judul_lowongan }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="col-2 control-label col-form-label">Filter Industri:</label>
                <div class="col-3">
                    <select class="form-control" id="filter_industri" name="filter_industri">
                        <option value="">- Semua -</option>
                        @foreach ($industri as $ind)
                            <option value="{{ $ind->industri_id }}">{{ $ind->industri_nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover table-sm" id="table_lowongan">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Lowongan</th>
                        <th>Industri</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
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

        var dataLow;

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            });

            dataLow = $('#table_lowongan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('lowongan/list') }}",
                    type: "POST",
                    data: function(d) {
                        d.filter_lowongan = $('#filter_lowongan').val();
                        d.filter_industri = $('#filter_industri').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "judul_lowongan"
                    },
                    {
                        data: "industri_nama"
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            $('#filter_lowongan, #filter_industri').change(function() {
                dataLow.ajax.reload();
            });
        });
    </script>
@endpush
