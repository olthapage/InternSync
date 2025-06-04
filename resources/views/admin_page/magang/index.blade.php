@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Daftar Mahasiswa Magang</h2>

            <div class="d-flex justify-content-between align-items-end mb-3 flex-wrap">
                <div class="form-group mb-0">
                    <label for="industri_id">Filter Industri</label>
                    <select id="industri_id" class="form-control form-control-sm">
                        <option value="">-- Semua Industri --</option>
                        @foreach ($industri as $industri)
                            <option value="{{ $industri->industri_id }}">{{ $industri->industri_nama }}</option>
                        @endforeach
                    </select>
                </div>

                <a href="#" class="btn btn-primary">+ Tambah Mahasiswa Magang</a>
            </div>


            <table class="table table-bordered table-striped table-hover table-sm" id="table_mahasiswa">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Mahasiswa</th>
                        <th>Magang</th>
                        <th>Status</th>
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
                    url: "{{ url('magang/list') }}",
                    dataType: "json",
                    type: "POST",
                    data: function(d) {
                        d.industri_id = $('#industri_id').val();
                    }
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "mahasiswa",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "lowongan",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "status",
                        className: "",
                        orderable: true,
                        searchable: true
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
                    searchPlaceholder: "Cari Magang...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Tidak ditemukan magang yang sesuai",
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

            $('#industri_id').on('change', function() {
                dataMhs.ajax.reload();
            });
        });
    </script>
@endpush
