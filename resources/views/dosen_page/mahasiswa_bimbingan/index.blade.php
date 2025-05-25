@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-2">Mahasiswa Bimbingan</h2>
            <p class="mb-4">Melihat daftar mahasiswa yang dibimbing beserta detail magang mereka.</p>

            <div class="table-responsive">
                <table class="table table-hover align-items-center mb-0 text-center" id="table_mahasiswa_bimbingan">
                    <thead class="table-dark text-center">
                        <tr>
                            <th class="text-start">No</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Prodi</th>
                            <th>Tempat Magang</th>
                            <th>Judul Lowongan</th>
                            <th>Status</th>
                            <th class="text-start">Aksi</th>
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
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');
            });
        }

        $(document).ready(function () {
            $('#table_mahasiswa_bimbingan').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("mahasiswa-bimbingan.list") }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'nama_lengkap', name: 'nama_lengkap' },
                    { data: 'nim', name: 'nim' },
                    { data: 'prodi', name: 'prodi.nama_prodi', className: 'text-center' },
                    { data: 'tempat_magang', name: 'magang.lowongan.industri.nama_industri', className: 'text-center' },
                    { data: 'judul_lowongan', name: 'magang.lowongan.judul_lowongan', className: 'text-center' },
                    { data: 'status', name: 'magang.status', className: 'text-center' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' },
                ]
            });
        });
    </script>
@endpush
