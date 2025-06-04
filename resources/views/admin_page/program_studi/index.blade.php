@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Daftar Program Studi</h2>

            <!-- Tombol Tambah Program Studi -->
            <div class="d-flex justify-content-end mb-3">
                <button onclick="modalAction('{{ url('program-studi/create') }}')" class="btn btn-sm btn-primary">+ Tambah
                    Program Studi</button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center" id="tabel_prodi" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-start">No</th>
                            <th>Nama Program Studi</th>
                            <th>Kode</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>


    <!-- Modal Dinamis -->
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

        var dataProdi;

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            dataProdi = $('#tabel_prodi').DataTable({
                serverSide: true,
                ajax: {
                    url: "{{ url('program-studi/list') }}",
                    type: "GET",
                    dataType: "json",
                    error: function(xhr, error, thrown) {
                        console.log('Error: ' + error);
                        console.log(xhr);
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "nama_prodi",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "kode_prodi",
                        className: "text-center",
                        orderable: false,
                        searchable: false
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
                    searchPlaceholder: "Cari Prodi...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Tidak ditemukan prodi yang sesuai",
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
        });
    </script>
@endpush
