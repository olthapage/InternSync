@empty($logharian)
<div class="modal-dialog modal-xl" style="max-width:60%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Data Log Harian tidak ditemukan!</h5>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
        </div>
    </div>
</div>
@else
<div class="modal-dialog modal-xl" style="max-width:90%;">
    <div class="modal-content">
        <div class="modal-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="modal-title">Detail Log Harian</h5>
            <button type="button" class="btn-close" onclick="$('#myModal').modal('hide')" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered mb-4">
                <tbody>
                    <tr>
                        <th style="width: 20%;">Mahasiswa</th>
                        <td>{{ $logharian->mahasiswaMagang->mahasiswa->nama_lengkap ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Log</th>
                        <td>{{ \Carbon\Carbon::parse($logharian->tanggal)->isoFormat('dddd, D MMMM YYYY') }}</td>
                    </tr>
                </tbody>
            </table>

            <h6 class="fw-bold mb-3">Daftar Aktivitas</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>Kegiatan</th>
                            <th>Tanggal Aktivitas</th>
                            <th>Lokasi</th>
                            <th>Status Industri</th>
                            <th>Catatan Industri</th>
                            <th>Status Dosen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logharian->detail as $detail)
                            <tr>
                                <td>{{ $detail->isi }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($detail->tanggal_kegiatan)->isoFormat('D MMM YY') }}</td>
                                <td class="text-center">{{ $detail->lokasi ?? '-' }}</td>
                                <td class="text-center">
                                    @switch($detail->status_approval_industri)
                                        @case('disetujui') <span class="badge bg-success">Disetujui</span> @break
                                        @case('ditolak') <span class="badge bg-danger">Ditolak</span> @break
                                        @default <span class="badge bg-warning text-dark">Pending</span>
                                    @endswitch
                                </td>
                                <td>{{ $detail->catatan_industri ?? '-' }}</td>
                                <td class="text-center">
                                    @switch($detail->status_approval_dosen)
                                        @case('disetujui') <span class="badge bg-success">Disetujui</span> @break
                                        @case('ditolak') <span class="badge bg-danger">Ditolak</span> @break
                                        @default <span class="badge bg-warning text-dark">Pending</span>
                                    @endswitch
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada aktivitas tercatat untuk tanggal ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
        </div>
    </div>
</div>
@endempty
