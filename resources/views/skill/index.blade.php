@extends('layouts.template')

@section('content')
<div class="mt-4">
    <h2 class="mb-4">Daftar Skill</h2>
    <div class="d-flex justify-content-end mb-3">
        <button onclick="modalAction('{{ url('skill/create') }}')" class="btn btn-sm btn-primary">+ Tambah Skill</button>
    </div>
    <div class="table-responsive text-sm">
        <table class="table table-bordered table-striped table-hover align-middle" id="table_skill">
            <thead class="table-dark text-center">
                <tr>
                    <th >No</th>
                    <th >Nama Skill</th>
                    <th >Kategori</th>
                    <th >Aksi</th>
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
            ]
        });
    });
</script>
@endpush 