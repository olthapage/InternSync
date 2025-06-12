@empty($lowongan)
  <div class="modal-dialog modal-xl" style="max-width:60%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kesalahan</h5>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
          <h5><i class="icon fas fa-ban"></i> Data Lowongan tidak ditemukan!</h5>
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
        <h5 class="modal-title">Detail Lowongan</h5>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped table-hover table-sm">
          <tr><th>ID</th>                <td>{{ $lowongan->lowongan_id }}</td></tr>
          <tr><th>Judul Lowongan</th>   <td>{{ $lowongan->judul_lowongan }}</td></tr>
          <tr><th>Deskripsi</th>        <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $lowongan->deskripsi ?? '-' }}</td></tr>
          <tr><th>Industri</th>         <td>{{ $lowongan->industri->industri_nama ?? '-' }}</td></tr>
          <tr><th>Dibuat pada</th>      <td>{{ $lowongan->created_at }}</td></tr>
          <tr><th>Diupdate pada</th>    <td>{{ $lowongan->updated_at }}</td></tr>
        </table>
      </div>
      <div class="modal-footer">
        <button onclick="modalAction('{{ url('/lowongan/' . $lowongan->lowongan_id . '/edit') }}')" class="btn btn-warning btn-sm">Edit</button>
        <button onclick="modalAction('{{ url('/lowongan/' . $lowongan->lowongan_id . '/delete') }}')" class="btn btn-danger btn-sm">Hapus</button>
        </form>
        <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
      </div>
    </div>
  </div>
@endempty
