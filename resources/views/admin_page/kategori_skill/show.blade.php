@empty($kategori)
  <div class="modal-dialog modal-xl" style="max-width:60%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kesalahan</h5>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
          <h5><i class="icon fas fa-ban"></i> Data Kategori tidak ditemukan!</h5>
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
        <h5 class="modal-title">Detail Kategori Skill</h5>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped table-hover table-sm">
          <tr><th style="width: 25%">ID</th><td>{{ $kategori->kategori_skill_id }}</td></tr>
          <tr><th>Nama Kategori</th><td>{{ $kategori->kategori_nama }}</td></tr>
        </table>
      </div>
      <div class="modal-footer">
        <button onclick="modalAction('{{ url('kategori_skill/' . $kategori->kategori_skill_id . '/edit') }}')" class="btn btn-warning btn-sm">Edit</button>
        <button onclick="modalAction('{{ url('kategori_skill/' . $kategori->kategori_skill_id . '/delete') }}')" class="btn btn-danger btn-sm">Hapus</button>
        <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
      </div>
    </div>
  </div>
@endempty
