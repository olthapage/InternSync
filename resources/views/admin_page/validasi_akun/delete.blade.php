@empty($akun)
  <div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Kesalahan</h5>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger">
          <h5><i class="icon fas fa-ban"></i> Data Akun tidak ditemukan.</h5>
        </div>
        <button class="btn btn-warning" onclick="$('#myModal').modal('hide')">Tutup</button>
      </div>
    </div>
  </div>
@else
  <form action="{{ url('/validasi_akun/' . $akun->id . '/delete') }}" method="POST" id="form-delete-akun">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Hapus Data Akun</h5>
        </div>
        <div class="modal-body">
          <div class="alert alert-warning">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5>
            Apakah Anda yakin ingin menghapus data di bawah ini?
          </div>
          <table class="table table-sm table-bordered table-striped">
            <tr>
              <th class="text-right col-3">Nama Lengkap:</th>
              <td class="col-9">{{ $akun->nama_lengkap }}</td>
            </tr>
            <tr>
              <th class="text-right col-3">Email:</th>
              <td>{{ $akun->email }}</td>
            </tr>
            <tr>
              <th class="text-right col-3">NIM/NIDN:</th>
              <td>{{ $akun->username }}</td>
            </tr>
            <tr>
              <th class="text-right col-3">Status:</th>
              <td>{{ $akun->status_validasi }}</td>
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
    $('#form-delete-akun').on('submit', function(e) {
      e.preventDefault();
      $.ajax({
        url:    this.action,
        type:   $(this).find('input[name="_method"]').val() || 'POST',
        data:   $(this).serialize(),
        success: function(res) {
          if (res.status) {
            $('#myModal').modal('hide');
            Swal.fire('Berhasil', res.message, 'success');
            dataMhs.ajax.reload();
          } else {
            Swal.fire('Gagal', res.message, 'error');
          }
        },
        error: function() {
          Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
        }
      });
    });
  });
  </script>
@endempty
