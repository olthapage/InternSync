@empty($admin)
  <div class="modal-dialog modal-xl" style="max-width:60%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kesalahan</h5>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
          <h5><i class="icon fas fa-ban"></i> Data Admin tidak ditemukan!</h5>
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
        <h5 class="modal-title">Detail Admin</h5>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped table-hover table-sm">
          <tr><th>ID</th>            <td>{{ $admin->user_id }}</td></tr>
          <tr><th>Nama Lengkap</th> <td>{{ $admin->nama_lengkap }}</td></tr>
          <tr><th>Email</th>        <td>{{ $admin->email }}</td></tr>
          <tr><th>Telepon</th>        <td>{{ $admin->telepon }}</td></tr>
          <tr><th>Level</th>        <td>{{ $admin->level->level_nama ?? '-' }}</td></tr>
          <tr><th>Dibuat pada</th>  <td>{{ $admin->created_at }}</td></tr>
          <tr><th>Diupdate pada</th><td>{{ $admin->updated_at }}</td></tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
      </div>
    </div>
  </div>
@endempty
