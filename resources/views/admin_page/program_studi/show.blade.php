@empty($prodi)
<div class="modal-dialog modal-xl" style="max-width: 60%;">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Kesalahan</h5>
    </div>
    <div class="modal-body">
      <div class="alert alert-danger">
        <h5><i class="icon fas fa-ban"></i> Data Program Studi tidak ditemukan!</h5>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
    </div>
  </div>
</div>
@else
<div class="modal-dialog modal-xl" style="max-width: 60%;">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Detail Program Studi</h5>
    </div>
    <div class="modal-body">
      <table class="table table-bordered table-striped table-hover table-sm">
        <tr><th>ID</th>           <td>{{ $prodi->prodi_id }}</td></tr>
        <tr><th>Kode Prodi</th>   <td>{{ $prodi->kode_prodi }}</td></tr>
        <tr><th>Nama Prodi</th>   <td>{{ $prodi->nama_prodi }}</td></tr>
        <tr><th>Dibuat pada</th>  <td>{{ $prodi->created_at }}</td></tr>
        <tr><th>Diupdate pada</th><td>{{ $prodi->updated_at }}</td></tr>
      </table>
    </div>
    <div class="modal-footer">
      <button onclick="modalAction('{{ url('program-studi/' . $prodi->prodi_id . '/edit') }}')" class="btn btn-warning btn-sm">Edit</button>
      <button onclick="modalAction('{{ url('program-studi/' . $prodi->prodi_id . '/delete') }}')" class="btn btn-danger btn-sm">Hapus</button>
      <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
    </div>
  </div>
</div>
@endempty
