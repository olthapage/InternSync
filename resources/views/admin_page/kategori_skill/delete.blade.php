@empty($kategori)
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kesalahan</h5>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
          <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
          Data kategori tidak ditemukan.
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
      </div>
    </div>
  </div>
@else
  <form action="{{ url('/kategori_skill/' . $kategori->kategori_skill_id . '/delete') }}" method="POST" id="form-delete-kategori">
    @csrf
    @method('DELETE')
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Hapus Kategori Skill</h5>
        </div>
        <div class="modal-body">
          @if($kategori->skills_count > 0)
            <div class="alert alert-danger">
              <h5><i class="icon fas fa-ban"></i> Tidak Dapat Dihapus</h5>
              Kategori ini tidak dapat dihapus karena masih digunakan oleh <strong>{{ $kategori->skills_count }} skill</strong>. Hapus atau ubah skill yang bersangkutan terlebih dahulu.
            </div>
          @else
            <div class="alert alert-warning">
              <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi!</h5>
              Apakah Anda yakin ingin menghapus kategori skill berikut ini?
            </div>
            <table class="table table-sm table-bordered table-striped">
              <tr>
                <th class="text-right col-4">Nama Kategori:</th>
                <td class="col-8">{{ $kategori->kategori_nama }}</td>
              </tr>
            </table>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
          @if($kategori->skills_count == 0)
            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
          @endif
        </div>
      </div>
    </div>
  </form>

  <script>
    $(document).ready(function () {
      $('#form-delete-kategori').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
          url: this.action,
          type: $(this).find('input[name="_method"]').val() || 'POST',
          data: $(this).serialize(),
          success: function (res) {
            if (res.status) {
              $('#myModal').modal('hide');
              Swal.fire('Berhasil', res.message, 'success');
              dataKategori.ajax.reload();
            } else {
              Swal.fire('Gagal', res.message, 'error');
            }
          },
          error: function () {
            Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
          }
        });
      });
    });
  </script>
@endempty
