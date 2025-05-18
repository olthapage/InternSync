@empty($detail)
  <div class="modal-dialog modal-xl" style="max-width:60%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kesalahan</h5>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
          <h5><i class="icon fas fa-ban"></i> Data Skill tidak ditemukan!</h5>
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
        <h5 class="modal-title">Detail Skill</h5>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped table-hover table-sm">
          <tr><th>ID</th>               <td>{{ $detail->skill_id }}</td></tr>
          <tr><th>Nama Skill</th>       <td>{{ $detail->skill_nama }}</td></tr>
          <tr><th>Kategori Skill</th>   <td>{{ $detail->kategori->kategori_nama }}</td></tr>
          <tr><th>Dibuat pada</th>      <td>{{ $detail->created_at }}</td></tr>
          <tr><th>Diupdate pada</th>    <td>{{ $detail->updated_at }}</td></tr>
        </table>
      </div>
      <div class="modal-footer">
        <button onclick="modalAction('{{ url('skill/' . $detail->skill_id . '/edit') }}')" class="btn btn-warning btn-sm">Edit</button>
        <button onclick="modalAction('{{ url('skill/' . $detail->skill_id . '/delete') }}')" class="btn btn-danger btn-sm">Hapus</button>
        <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
      </div>
    </div>
  </div>
@endempty
