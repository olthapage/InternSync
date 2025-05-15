@empty($industri)
  <div class="modal-dialog modal-xl" style="max-width:60%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kesalahan</h5>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
          <h5><i class="icon fas fa-ban"></i> Data Industri tidak ditemukan!</h5>
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
        <h5 class="modal-title">Detail Industri</h5>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped table-hover table-sm">
          <tr><th>ID</th>                   <td>{{ $industri->industri_id }}</td></tr>
          <tr><th>Nama Industri</th>        <td>{{ $industri->industri_nama }}</td></tr>
          <tr><th>Kota</th>                 <td>{{ $industri->kota->kota_nama ?? '-' }}</td></tr>
          <tr><th>Kategori Industri</th>    <td>{{ $industri->kategori_industri->kategori_nama ?? '-' }}</td></tr>
          <tr><th>Dibuat pada</th>          <td>{{ $industri->created_at }}</td></tr>
          <tr><th>Diupdate pada</th>        <td>{{ $industri->updated_at }}</td></tr>
        </table>
      </div>
      <div class="modal-footer">
        <button onclick="modalAction('{{ url('/industri/' . $industri->industri_id . '/edit') }}')" class="btn btn-warning btn-sm">Edit</button>
        <button onclick="modalAction('{{ url('/industri/' . $industri->industri_id . '/delete') }}')" class="btn btn-danger btn-sm">Hapus</button>
        </form>
        <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
      </div>
    </div>
  </div>
@endempty
