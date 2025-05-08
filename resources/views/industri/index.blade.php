@extends('layouts.template')

@section('content')
<div class="mt-4">
    <h2 class="mb-4">Daftar Industri</h2>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('industri.create') }}" class="btn btn-primary">+ Tambah Industri</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle" id="table_industri">
            <thead class="table-dark text-center">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Industri</th>
                    <th scope="col">Kota</th>
                    <th scope="col">Kategori Industri</th>
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

    var dataIndustri;

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        dataIndustri = $('#table_industri').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('industri/list') }}",
                type: "POST",
                dataType: "json",
                data: function(d) {
                    d.kota_id = $('#kota_id').val(); // kalau ingin disiapkan filter
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "industri_nama",
                    className: "text-center"
                },
                {
                    data: "kota",
                    className: "text-center"
                },
                {
                    data: "kategori",
                    className: "text-center"
                },
                {
                    data: "aksi",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#kota_id').on('change', function() {
            dataIndustri.ajax.reload();
        });
    });
</script>
@endpush
