@empty($industri)
  <div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kesalahan</h5>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
          <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
          Data industri tidak ditemukan.
        </div>
        <button class="btn btn-warning" onclick="$('#myModal').modal('hide')">Tutup</button>
      </div>
    </div>
  </div>
@else
  <form action="{{ route('industri.destroy', $industri->industri_id) }}" method="POST" id="form-delete-industri">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Hapus Data Industri</h5>
        </div>
        <div class="modal-body">
          <div class="alert alert-warning">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5>
            Apakah Anda yakin ingin menghapus data industri berikut ini?
          </div>
          <table class="table table-sm table-bordered table-striped">
            <tr>
              <th class="text-right col-3">Nama Industri:</th>
              <td class="col-9">{{ $industri->industri_nama }}</td>
            </tr>
            <tr>
              <th class="text-right">Kota:</th>
              <td>{{ $industri->kota->kota_nama ?? '-' }}</td>
            </tr>
            <tr>
              <th class="text-right">Kategori Industri:</th>
              <td>{{ $industri->kategori_industri->kategori_nama ?? '-' }}</td>
            </tr>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
          <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        </div>
      </div>
    </div>
  </form>

  <script>
    $(document).ready(function () {
      $('#form-delete-industri').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
          url: this.action,
          type: $(this).find('input[name="_method"]').val() || 'POST',
          data: $(this).serialize(),
          success: function (res) {
            if (res.status) {
              $('#myModal').modal('hide');
              Swal.fire('Berhasil', res.message, 'success');
              dataIndustri.ajax.reload(); // pastikan variable ini tersedia di file utama
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
