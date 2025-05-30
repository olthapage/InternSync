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
            <table class="table table-bordered table-sm mb-3">
                <tr><th>ID Log</th>         <td>{{ $logharian->logHarian_id }}</td></tr>
                <tr><th>Mahasiswa</th>     <td>{{ $logharian->mahasiswa->nama_lengkap ?? '-' }}</td></tr>
                <tr><th>Tanggal Log</th>   <td>{{ $logharian->tanggal }}</td></tr>
                <tr><th>Lokasi</th>        <td>{{ $logharian->lokasi_kegiatan->kota_nama ?? '-' }}</td></tr>
                <tr><th>Dibuat pada</th>   <td>{{ $logharian->created_at }}</td></tr>
                <tr><th>Diupdate pada</th> <td>{{ $logharian->updated_at }}</td></tr>
            </table>

            <h6 class="fw-bold">Daftar Aktivitas</h6>
            <table class="table table-bordered table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th style="width:20%;">Isi</th>
                        <th style="width:15%;">Tanggal Aktivitas</th> 
                        <th style="width:15%;">Lokasi</th>
                        <th style="width:15%;">Approval Dosen</th>
                        <th style="width:15%;">Approval Industri</th>
                        <th style="width:10%;">Catatan Dosen</th>
                        <th style="width:10%;">Catatan Industri</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logharian->detail as $detail)
                        <tr>
                            <td>{{ $detail->isi }}</td>
                            <td>{{ $detail->tanggal_kegiatan }}</td> 
                            <td>{{ $detail->lokasi ?? '-' }}</td>
                            <td>
                                @switch($detail->status_approval_dosen)
                                    @case('disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                        @break
                                    @case('ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                        @break
                                    @default
                                        <span class="badge bg-warning text-dark">Pending</span>
                                @endswitch
                            </td>
                            <td>
                                @switch($detail->status_approval_industri)
                                    @case('disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                        @break
                                    @case('ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                        @break
                                    @default
                                        <span class="badge bg-warning text-dark">Pending</span>
                                @endswitch
                            </td>
                            <td>{{ $detail->catatan_dosen ?? '-' }}</td>
                            <td>{{ $detail->catatan_industri ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada aktivitas tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
        </div>
    </div>
</div>
@endempty
