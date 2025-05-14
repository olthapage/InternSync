@empty($mahasiswa)
  <div class="modal-dialog modal-xl" style="max-width:60%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kesalahan</h5>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
          <h5><i class="icon fas fa-ban"></i> Data Mahasiswa tidak ditemukan!</h5>
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
        <h5 class="modal-title">Detail Mahasiswa</h5>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped table-hover table-sm">
          <tr><th>ID</th>               <td>{{ $mahasiswa->mahasiswa_id }}</td></tr>
          <tr><th>Nama Lengkap</th>    <td>{{ $mahasiswa->nama_lengkap }}</td></tr>
          <tr><th>Email</th>           <td>{{ $mahasiswa->email }}</td></tr>
          <tr><th>NIM</th>             <td>{{ $mahasiswa->nim }}</td></tr>
          <tr><th>IPK</th>             <td>{{ $mahasiswa->ipk ?? '-' }}</td></tr>
          <tr><th>Status Magang</th>   <td>
            @if($mahasiswa->status == 1)
              <span class="badge bg-success">Sudah Magang</span>
            @else
              <span class="badge bg-secondary">Belum Magang</span>
            @endif
          </td></tr>
          <tr><th>Program Studi</th>   <td>{{ $mahasiswa->prodi->nama_prodi ?? '-' }}</td></tr>
          <tr><th>Dosen Pembimbing</th><td>{{ $mahasiswa->dosen->nama_lengkap ?? '-' }}</td></tr>
          <tr><th>Level</th>           <td>{{ $mahasiswa->level->level_nama ?? '-' }}</td></tr>
          <tr><th>Dibuat pada</th>     <td>{{ $mahasiswa->created_at }}</td></tr>
          <tr><th>Diupdate pada</th>   <td>{{ $mahasiswa->updated_at }}</td></tr>
        </table>

        <hr>

        <h6>Preferensi Lokasi:</h6>
        <ul>
          @forelse($mahasiswa->preferensiLokasi as $lokasi)
            <li>{{ $lokasi->nama_lokasi }}</li>
          @empty
            <li>Tidak ada data preferensi lokasi.</li>
          @endforelse
        </ul>

        <h6>Skills:</h6>
        <ul>
          @forelse($mahasiswa->skills as $skill)
            <li>{{ $skill->nama_skill }}</li>
          @empty
            <li>Belum memiliki skill.</li>
          @endforelse
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
      </div>
    </div>
  </div>
@endempty
