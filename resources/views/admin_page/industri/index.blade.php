@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Daftar Industri</h2>
            <div class="d-flex justify-content-end mb-3">
                <button onclick="modalAction('{{ route('industri.create') }}')" class="btn btn-sm btn-primary">+ Tambah
                    Industri</button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center" id="table_industri">
                    <thead>
                        <tr>
                            <th class="text-start">No</th>
                            <th>Nama Industri</th>
                            <th>Kota</th>
                            <th>Kategori Industri</th>
                            <th class="text-center">Aksi</th>
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
                columns: [{
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
                ],
                language: {
                    search: "", // Kosongkan default label
                    searchPlaceholder: "Cari Industri...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Tidak ditemukan industri yang sesuai",
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

            $('#kota_id').on('change', function() {
                dataIndustri.ajax.reload();
            });
        });
    </script>
@endpush
