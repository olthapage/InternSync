@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header"> {{-- Menggunakan class card-header standar --}}
            <h3>Magang Saya</h3>
        </div>
        <div class="card-body text-sm">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            @if ($magang->isEmpty())
                <div class="alert bg-white text-center" role="alert">
                    <h4 class="alert-heading">Informasi</h4>
                    <p>Anda saat ini belum terdaftar atau diterima pada program magang manapun.</p>
                    <p class="mb-0">Silakan coba untuk mengajukan diri (apply) pada lowongan yang tersedia.</p>
                    <a href="{{ route('mahasiswa.lowongan.index') }}" class="btn bg-gradient-info mt-3">Lihat Lowongan Magang</a>
                </div>
            @else
                {{-- Tidak perlu div card lagi di sini karena sudah ada di luar --}}
                @foreach ($magang as $item)
                    <div class="mb-4 mt-2 p-3 border rounded"> {{-- Memberi sedikit background --}}
                        <h5>
                            @if ($item->lowongan && $item->lowongan->judul_lowongan)
                                Posisi: {{ $item->lowongan->judul_lowongan }}
                            @else
                                Informasi Lowongan Tidak Tersedia
                            @endif
                        </h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p>
                                    <strong>Status Magang:</strong>
                                    @if ($item->status == 'diterima' || $item->status == 'sedang')
                                        <span
                                            class="badge bg-success text-capitalize">{{ $item->status == 'diterima' ? 'Diterima (Akan Berjalan)' : 'Sedang Berjalan' }}</span>
                                    @elseif ($item->status == 'selesai')
                                        <span class="badge bg-primary text-capitalize">Selesai</span>
                                    @elseif ($item->status == 'ditolak')
                                        <span class="badge bg-danger text-capitalize">{{ $item->status }}</span>
                                    @elseif ($item->status == 'pending' || $item->status == 'diajukan' || $item->status == 'belum')
                                        <span class="badge bg-warning text-capitalize">Belum Mulai</span>
                                    @else
                                        <span
                                            class="badge bg-secondary text-capitalize">{{ $item->status ?? 'Belum ada status' }}</span>
                                    @endif
                                </p>

                                @if ($item->lowongan)
                                    <p><strong>Perusahaan:</strong> {{ $item->lowongan->industri->industri_nama ?? 'N/A' }}
                                    </p>
                                    {{-- Periode Magang dari Pengajuan --}}
                                    @if ($item->pengajuan)
                                        <p><strong>Periode Magang:</strong>
                                            {{ $item->pengajuan->tanggal_mulai ? \Carbon\Carbon::parse($item->pengajuan->tanggal_mulai)->isoFormat('D MMMM YYYY') : 'N/A' }}
                                            -
                                            {{ $item->pengajuan->tanggal_selesai ? \Carbon\Carbon::parse($item->pengajuan->tanggal_selesai)->isoFormat('D MMMM YYYY') : 'N/A' }}
                                        </p>
                                    @else
                                        <p><strong>Periode Magang:</strong> Informasi periode dari pengajuan tidak
                                            ditemukan.</p>
                                    @endif
                                    <p><strong>Kategori Keahlian:</strong>
                                        {{ $item->lowongan->kategoriSkill->kategori_nama ?? 'N/A' }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if ($item->lowongan)
                                    <p><strong>Lokasi:</strong>
                                        {{ $item->lowongan->alamat_lengkap_display ?? 'Lokasi tidak ditentukan.' }}</p>
                                    <p><strong>Deskripsi Magang:</strong></p>
                                    <div style="max-height: 100px; overflow-y: auto; padding: 5px; border: 1px solid #eee;">
                                        {!! nl2br(e($item->lowongan->deskripsi ?? 'Tidak ada deskripsi.')) !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <hr>

                        {{-- Bagian Evaluasi --}}
                        @if ($item->status == 'selesai')
                            <div class="d-flex justify-content-between align-items-start border-top pt-3 mt-3">
                                <div>
                                    <p class="mb-2"><strong>Surat Keterangan Selesai Magang.</strong></p>
                                    {{-- TOMBOL UNDUH SURAT KETERANGAN --}}
                                    <a href="{{ route('mahasiswa.magang.surat_keterangan.download', ['magang_id' => $item->mahasiswa_magang_id]) }}"
                                        class="btn btn-success" target="_blank">
                                        <i class="fas fa-certificate"></i> Unduh Surat Keterangan
                                    </a>
                                </div>

                                {{-- Form Evaluasi yang sudah ada --}}
                                <div style="width: 60%;">
                                    @if ($item->evaluasi)
                                        <div class="mt-0">
                                            <p><strong>Evaluasi Anda:</strong></p>
                                            <div class="p-3 bg-white border rounded shadow-sm">
                                                {!! nl2br(e($item->evaluasi)) !!}
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-0">
                                            <p><strong>Magang Anda telah selesai. Silakan isi evaluasi Anda:</strong></p>
                                            <form method="POST"
                                                action="{{ route('mahasiswa.magang.evaluasi.store', $item->mahasiswa_magang_id) }}">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="evaluasi-{{ $item->mahasiswa_magang_id }}">Tulis Evaluasi
                                                        Anda</label>
                                                    <textarea class="form-control @error('evaluasi') is-invalid @enderror" id="evaluasi-{{ $item->mahasiswa_magang_id }}"
                                                        name="evaluasi" rows="4" required>{{ old('evaluasi') }}</textarea>
                                                    @error('evaluasi')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <button type="submit" class="btn btn-success mt-2">Kirim Evaluasi</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif ($item->evaluasi)
                            <div class="mt-3">
                                <p><strong>Evaluasi Telah Diberikan:</strong></p>
                                <div class="p-3 bg-white border rounded shadow-sm">
                                    {!! nl2br(e($item->evaluasi)) !!}
                                </div>
                            </div>
                        @endif
                        {{-- Akhir Bagian Evaluasi --}}

                    </div>
                    @if (!$loop->last)
                        {{-- <hr class="my-4"> --}} {{-- Memberi jarak lebih antar item jika perlu --}}
                    @endif
                @endforeach
            @endif
        </div>
    </div>
    @if ($magang->isEmpty())
        <div class="text-center mt-4" role="alert">
            <small><em>Semangat !</em></small>
        </div>
    @else
        <div class="card card-outline card-primary mt-4">
            <div class="card-body text-sm">
                <h4 class="mb-4">Log Harian</h4>

                <div class="row mb-4">
                    <div class="col-md-3">
                        <input type="date" id="tanggal" class="form-control" placeholder="Filter tanggal...">
                    </div>
                    <div class="col-md-9 d-flex justify-content-end">
                        {{-- ===== PERUBAHAN DIMULAI DI SINI ===== --}}
                        @if ($magang->whereIn('status', ['diterima', 'sedang'])->isNotEmpty())
                            <button onclick="modalAction('{{ route('logHarian.create') }}')"
                                class="btn btn-sm btn-primary me-2">
                                Tambah Log Harian
                            </button>
                        @endif
                        {{-- ===== PERUBAHAN SELESAI DI SINI ===== --}}

                        <a href="{{ route('logHarian.export_pdf') }}" class="btn btn-sm btn-secondary">
                            <i class="fa fa-file-pdf"></i> Export Log Book (pdf)
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center" id="table_logharian" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-start">Tanggal</th>
                                <th>Isi Log</th>
                                <th>Lokasi Kegiatan</th>
                                <th>Status Dosen</th>
                                <th>Status Industri</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div id="myModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false"
            aria-hidden="true"></div>
    @endif
@endsection

@push('css')
    {{-- Tambahkan CSS jika perlu --}}
    <style>
        .card-title {
            /* Memastikan judul kartu konsisten */
            float: left;
            font-size: 1.1rem;
            font-weight: 400;
            margin: 0;
        }
    </style>
@endpush

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }

        function deleteLog(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus log ini?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Menggunakan helper route() dari Blade untuk URL yang lebih aman
                    let deleteUrl = '{{ route('logHarian.delete', ['id' => ':id_placeholder']) }}';
                    deleteUrl = deleteUrl.replace(':id_placeholder', id);

                    const csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: deleteUrl, // Menggunakan URL yang di-generate dari nama route
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            // ... (sama seperti sebelumnya)
                            if (response.status) {
                                $('#table_logharian').DataTable().ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message || 'Log berhasil dihapus.'
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message || 'Gagal menghapus log.'
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Terjadi kesalahan saat menghapus log.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.statusText && xhr.status) {
                                errorMessage = `Error ${xhr.status}: ${xhr.statusText}`;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorMessage
                            });
                        }
                    });
                }
            });
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const tableLogHarian = $('#table_logharian').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('logHarian.list') }}",
                    type: "POST",
                    data: function(d) {
                        d.tanggal = $('#tanggal').val();
                    }
                },
                columns: [{
                        data: 'tanggal',
                        name: 'tanggal',
                        className: 'text-center'
                    },
                    {
                        data: 'isi',
                        name: 'isi',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'lokasi_kegiatan',
                        name: 'lokasi_kegiatan'
                    },
                    {
                        data: 'status_approval_dosen',
                        name: 'status_approval_dosen',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status_approval_industri',
                        name: 'status_approval_industri',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                language: {
                    search: "", // Kosongkan default label
                    searchPlaceholder: "Cari Log...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Tidak ditemukan log yang sesuai",
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
                order: [
                    [0, 'desc']
                ]
            });

            $('#tanggal').on('change', function() {
                tableLogHarian.ajax.reload(null, false);
            });
        });
    </script>
@endpush
