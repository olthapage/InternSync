@extends('layouts.template')

@section('content')
<div class="mt-4">
    <h2 class="mb-4">Daftar Skill</h2>
    <div class="d-flex justify-content-end mb-3">
        <button onclick="modalAction('{{ url('skill/create') }}')" class="btn btn-sm btn-primary">+ Tambah Skill</button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 text-center" id="table_skill">
            <thead>
                <tr>
                    <th class="text-start">No</th>
                    <th >Nama Skill</th>
                    <th >Kategori</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog"
    data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true">
</div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }

    var dataSkill;

    $(document).ready(function () {
        dataSkill = $('#table_skill').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('skill/list') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
                { data: 'skill_nama', name: 'skill_nama' },
                { data: 'kategori', name: 'kategori', className: 'text-center' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' }
            ],
            language: {
                    search: "", // Kosongkan default label
                    searchPlaceholder: "Cari Skill...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Tidak ditemukan skill yang sesuai",
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
