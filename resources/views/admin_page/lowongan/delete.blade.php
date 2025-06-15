@empty($lowongan)
  <div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kesalahan</h5>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
          <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
          Data lowongan tidak ditemukan.
        </div>
        <button class="btn btn-warning" onclick="$('#myModal').modal('hide')">Tutup</button>
      </div>
    </div>
  </div>
@else
  <form action="{{ route('lowongan.destroy', $lowongan->lowongan_id) }}" method="POST" id="form-delete-lowongan">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Hapus Data Lowongan</h5>
        </div>
        <div class="modal-body">
          <div class="alert alert-warning">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5>
            Apakah Anda yakin ingin menghapus data lowongan berikut ini?
          </div>
          <table class="table table-sm table-bordered table-striped">
            <tr>
              <th class="text-right col-3">Judul Lowongan:</th>
              <td class="col-9">{{ $lowongan->judul_lowongan }}</td>
            </tr>
            <tr>
              <th class="text-right">Industri:</th>
              <td>{{ $lowongan->industri->industri_nama ?? '-' }}</td>
            </tr>
            <tr>
              <th class="text-right">Deskripsi:</th>
              <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $lowongan->deskripsi ?? '-' }}</td>
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
    $('#form-delete-lowongan').on('submit', function (e) {
      e.preventDefault();
      $.ajax({
        url: this.action,
        type: $(this).find('input[name="_method"]').val() || 'POST',
        data: $(this).serialize(),
        success: function (res) {
          if (res.status) {
            $('#myModal').modal('hide');
            Swal.fire('Berhasil', res.message, 'success');
            dataLow.ajax.reload();
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
