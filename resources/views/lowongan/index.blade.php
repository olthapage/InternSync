@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Lowongan</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-2 control-label col-form-label">Filter Industri:</label>
                        <div class="col-3">
                            <select class="form-control" id="filter_industri" name="filter_industri">
                                <option value="">- Semua -</option>
                                @foreach($industries as $industry)
                                    <option value="{{ $industry->id }}">{{ $industry->industri_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Pilih industri untuk melihat lowongan</small>
                        </div>
                    </div>
                </div>
            </div> 

            <table class="table table-bordered table-striped table-hover table-sm" id="table_lowongan">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Industri</th>
                        <th>Lowongan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true"></div>
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
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });

            dataLow = $('#table_lowongan').DataTable({
                serverSide: true,
                ajax: { url: "{{ url('lowongan/list') }}", type: "POST" },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "industri_nama", className: "" },
                    { data: "judul_lowongan", className: "" },
                    { data: "aksi", className: "text-center", orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
