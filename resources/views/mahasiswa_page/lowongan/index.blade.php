@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Daftar Lowongan Magang</h2>
            <div class="row mb-4"> <!-- Ubah dari form ke div -->
                <div class="col-md-4">
                    <label for="lokasi">Lokasi (Kota)</label>
                    <select name="lokasi" id="lokasi" class="form-control">
                        <option value="">-- Semua Lokasi --</option>
                        @foreach ($listKota as $kota)
                            <option value="{{ $kota->kota_id }}">{{ $kota->kota_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="jenis">Jenis (Kategori Skill)</label>
                    <select name="jenis" id="jenis" class="form-control">
                        <option value="">-- Semua Jenis --</option>
                        @foreach ($listKategori as $kategori)
                            <option value="{{ $kategori->kategori_skill_id }}">{{ $kategori->kategori_nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button onclick="modalAction('{{ route('mahasiswa.create') }}')" class="btn btn-sm btn-primary">
                        Cari Magang
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-items-center mb-0 text-center" id="table_lowongan">
                    <thead>
                        <tr>
                            <th class="text-start">Industri</th>
                            <th>Jenis</th>
                            <th>Lowongan</th>
                            <th>Slot Tersedia</th>
                            <th>Periode</th>
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

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const dataLowongan = $('#table_lowongan').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('mahasiswa.lowongan.list') }}",
                    type: "POST",
                    data: function(d) {
                        d.lokasi = $('#lokasi').val();
                        d.jenis = $('#jenis').val();
                    }
                },
                columns: [{
                        data: 'industri',
                        name: 'industri.industri_nama',
                        className: 'text-start',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'jenis',
                        name: 'kategoriSkill.kategori_nama',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul',
                        name: 'm_detail_lowongan.judul_lowongan'
                    },
                    {
                        data: 'slot',
                        name: 'm_detail_lowongan.slot',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'periode',
                        name: 'm_detail_lowongan.tanggal_mulai',
                        orderable: true
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                language: {
                    search: "Cari:",
                    searchPlaceholder: "Judul/Industri...",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                order: [
                    [4, 'desc']
                ] // Default sorting by tanggal_mulai descending
            });

            // Real-time filtering
            $('#lokasi, #jenis').on('change', function() {
                dataLowongan.ajax.reload();
            });
        });
    </script>
@endpush
