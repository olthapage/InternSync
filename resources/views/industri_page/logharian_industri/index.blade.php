@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Log Harian Mahasiswa</h2>

            <div class="row mb-4">
                <div class="col-md-3">
                    <input type="text" id="filterNama" class="form-control" placeholder="Cari nama mahasiswa...">
                </div>
                <div class="col-md-9 d-flex justify-content-end">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center" id="table_logharian_industri" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Kegiatan</th>
                            <th>Status Industri</th>
                            <th>Status Dosen</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
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
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const tableLogIndustri = $('#table_logharian_industri').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('logharian_industri.list') }}',
                type: 'POST',
                data: function (d) {
                    d.nama = $('#filterNama').val();
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                { data: 'tanggal', name: 'tanggal' },
                { data: 'mahasiswa', name: 'mahasiswa' },
                { data: 'kegiatan', name: 'kegiatan' },
                { data: 'status_industri', name: 'status_industri', orderable: false, searchable: false },
                { data: 'status_dosen', name: 'status_dosen', orderable: false, searchable: false },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' },
            ],
            language: {
                search: "Cari:",
                searchPlaceholder: "Cari log...",
                lengthMenu: "Tampilkan MENU data per halaman",
                zeroRecords: "Tidak ada data log ditemukan",
                info: "Menampilkan START sampai END dari TOTAL data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari MAX total data)",
                paginate: {
                    first: "<i class='fas fa-angle-double-left'></i>",
                    last: "<i class='fas fa-angle-double-right'></i>",
                    next: "<i class='fas fa-angle-right'></i>",
                    previous: "<i class='fas fa-angle-left'></i>"
                },
            },
            order: [[1, 'desc']]
        });

        $('#filterNama').on('input', function () {
            tableLogIndustri.ajax.reload(null, false);
        });
    });
</script>
@endpush
