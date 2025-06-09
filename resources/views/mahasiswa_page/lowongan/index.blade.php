@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Daftar Lowongan Magang</h2>
                <button class="btn btn-success" onclick="modalAction('{{ route('mahasiswa.rekomendasi.modal') }}')">
                    <i class="fas fa-star me-1"></i> Lihat Rekomendasi
                </button>
            </div>
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

    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            {{-- Konten dari AJAX akan dimuat di sini, di dalam .modal-dialog --}}
        </div>
    </div>
@endsection
@push('js')
    <script>
        function modalAction(url = '') {
            // Targetkan .modal-dialog di dalam #myModal untuk diisi konten
            $('#myModal .modal-dialog').load(url, function() {
                // Tetap panggil .modal('show') pada elemen modal utamanya
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
                        searchable: true
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
                        first: "<i class='fas fa-angle-double-left'></i>",
                        last: "<i class='fas fa-angle-double-right'></i>",
                        next: "<i class='fas fa-angle-right'></i>",
                        previous: "<i class='fas fa-angle-left'></i>"
                    },
                    processing: '<div class="d-flex justify-content-center"><i class="fas fa-spinner fa-pulse fa-2x fa-fw text-primary"></i><span class="ms-2">Memuat data...</span></div>'
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
