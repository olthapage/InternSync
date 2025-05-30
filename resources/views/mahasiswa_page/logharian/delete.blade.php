@empty($log)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Kesalahan</h5>
    </div>
    <div class="modal-body">
      <div class="alert alert-danger">
        <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
        Data Log Harian tidak ditemukan.
      </div>
      <button class="btn btn-warning" onclick="$('#myModal').modal('hide')">Tutup</button>
    </div>
  </div>
</div>
@else
<form action="{{ route('logHarian.delete', $log->id) }}" method="POST" id="form-delete-logharian">
  @csrf
  @method('DELETE')
  <div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Hapus Data Log Harian</h5>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning">
          <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5>
          Apakah Anda yakin ingin menghapus data log harian berikut ini?
        </div>
        <table class="table table-sm table-bordered table-striped">
          <tr>
            <th class="text-right col-3">Deskripsi:</th>
            <td>{{ $log->deskripsi ?? '-' }}</td>
          </tr>
          <tr>
            <th class="text-right">Tanggal:</th>
            <td>{{ $log->tanggal ?? '-' }}</td>
          </tr>
          <tr>
            <th class="text-right">Lokasi:</th>
            <td>{{ $log->lokasi ?? '-' }}</td>
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
  $('#form-delete-logharian').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: this.action,
      type: $(this).find('input[name="_method"]').val() || 'POST',
      data: $(this).serialize(),
      success: function (res) {
        if (res.status) {
          $('#myModal').modal('hide');
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: res.message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          });
          $('#table_logharian').DataTable().ajax.reload();
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
