@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Daftar Lowongan</h2>
            <div class="d-flex justify-content-end mb-3">
                <button onclick="modalAction('{{ route('lowongan.create') }}')" class="btn btn-sm btn-primary">+ Tambah Lowongan</button>
            </div>
            <div class="form-group row">
                <label class="col-2 control-label col-form-label">Filter Periode:</label>
                <div class="col-3">
                    <select class="form-control" id="filter_bulan" name="filter_bulan">
                        <option value="">- Semua -</option>
                        @for ($i = 1; $i <= 12; $i++)
                            @php
                                $monthValue = str_pad($i, 2, '0', STR_PAD_LEFT);
                                $monthName = \Carbon\Carbon::createFromDate(null, $i, 1)->translatedFormat('F');
                            @endphp
                            <option value="{{ $monthValue }}">{{ $monthName }}</option>
                        @endfor
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

            <table class="table table-hover align-middle mb-0 text-center" id="table_lowongan">
                <thead>
                    <tr>
                        <th class="text-start">No</th>
                        <th>Lowongan</th>
                        <th>Industri</th>
                        <th class="text-center">Aksi</th>
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
                        d.filter_bulan = $('#filter_bulan').val();
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
                ],
                language: {
                    search: "", // Kosongkan default label
                    searchPlaceholder: "Cari Lowongan...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Tidak ditemukan lowongan yang sesuai",
                    info: "Menampilkan _START_-_END_ dari _TOTAL_ entri",
                    infoEmpty: "Data tidak tersedia",
                    infoFiltered: "(disaring dari _MAX_ total entri)",
                    paginate: {
                        first: "<i class='fas fa-angle-double-left'></i>",
                        last: "<i class='fas fa-angle-double-right'></i>",
                        next: "<i class='fas fa-angle-right'></i>",
                        previous: "<i class='fas fa-angle-left'></i>"
                    },
                    processing: '<div class="d-flex justify-content-center"><i class="fas fa-spinner fa-pulse fa-2x fa-fw text-primary"></i><span class="ms-2">Memuat data...</span></div>'
                },
            });
            $('#filter_bulan, #filter_industri').change(function() {
                dataLow.ajax.reload();
            });
        });
    </script>
@endpush
