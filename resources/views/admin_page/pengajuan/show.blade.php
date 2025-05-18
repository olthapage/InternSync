@empty($pengajuan)
  <div class="modal-dialog modal-xl" style="max-width:60%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kesalahan</h5>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
          <h5><i class="icon fas fa-ban"></i> Data Pengajuan tidak ditemukan!</h5>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
      </div>
    </div>
  </div>
@else
  <div class="modal-dialog modal-xl" style="max-width:60%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Pengajuan Magang</h5>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped table-hover table-sm">
          <tr>
            <th>ID Pengajuan</th>
            <td>{{ $pengajuan->pengajuan_id }}</td>
          </tr>
          <tr>
            <th>Nama Mahasiswa</th>
            <td>{{ $pengajuan->mahasiswa->nama_lengkap ?? '-' }}</td>
          </tr>
          <tr>
            <th>Judul Lowongan</th>
            <td>{{ $pengajuan->lowongan->judul_lowongan ?? '-' }}</td>
          </tr>
          <tr>
            <th>Tanggal Mulai</th>
            <td>
              @if($pengajuan->tanggal_mulai)
                {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d-m-Y') }}
              @else
                -
              @endif
            </td>
          </tr>
          <tr>
            <th>Tanggal Selesai</th>
            <td>
              @if($pengajuan->tanggal_selesai)
                {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d-m-Y') }}
              @else
                -
              @endif
            </td>
          </tr>
          <tr>
            <th>Status Pengajuan</th>
            <td>
              @php $status = $pengajuan->status; @endphp
              @if($status === 'approved')
                <span class="badge bg-success">Disetujui</span>
              @elseif($status === 'pending')
                <span class="badge bg-warning">Menunggu</span>
              @elseif($status === 'rejected')
                <span class="badge bg-danger">Ditolak</span>
              @else
                <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
              @endif
            </td>
          </tr>
          <tr>
            <th>Dibuat pada</th>
            <td>{{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d-m-Y H:i') }}</td>
          </tr>
          <tr>
            <th>Diupdate pada</th>
            <td>{{ \Carbon\Carbon::parse($pengajuan->updated_at)->format('d-m-Y H:i') }}</td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
      </div>
    </div>
  </div>
@endempty
