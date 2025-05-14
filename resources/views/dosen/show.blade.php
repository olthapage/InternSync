@empty($dosen)
  <div class="modal-dialog modal-xl" style="max-width:60%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kesalahan</h5>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
          <h5><i class="icon fas fa-ban"></i> Data Dosen tidak ditemukan!</h5>
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
        <h5 class="modal-title">Detail Dosen</h5>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped table-hover table-sm">
          <tr><th>ID</th>            <td>{{ $dosen->dosen_id }}</td></tr>
          <tr><th>Nama Lengkap</th> <td>{{ $dosen->nama_lengkap }}</td></tr>
          <tr><th>Email</th>         <td>{{ $dosen->email }}</td></tr>
          <tr><th>NIP</th>           <td>{{ $dosen->nip }}</td></tr>
          <tr><th>Program Studi</th> <td>{{ $dosen->prodi->nama_prodi ?? '-' }}</td></tr>
          <tr><th>Level</th>         <td>{{ $dosen->level->level_nama ?? '-' }}</td></tr>
          <tr><th>Dibuat pada</th>   <td>{{ $dosen->created_at }}</td></tr>
          <tr><th>Diupdate pada</th> <td>{{ $dosen->updated_at }}</td></tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
      </div>
    </div>
  </div>
@endempty
