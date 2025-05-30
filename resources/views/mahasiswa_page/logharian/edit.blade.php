<form id="formEditLogHarian">
    @csrf
    @method('PUT')
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Edit Log Harian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body text-sm">
                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Umum</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $log->tanggal }}" required>
                </div>

                <div id="aktivitas-container">
                    @foreach ($log->detail as $i => $detail)
                        <div class="aktivitas-item border rounded p-3 mb-3">
                            <div class="mb-2">
                                <label>Deskripsi Aktivitas</label>
                                <textarea name="aktivitas[{{ $i }}][deskripsi]" class="form-control" required>{{ $detail->isi }}</textarea>
                            </div>
                            <div class="mb-2">
                                <label>Lokasi</label>
                                <input type="text" name="aktivitas[{{ $i }}][lokasi]" class="form-control" value="{{ $detail->lokasi }}" required>
                            </div>
                            <div class="mb-2">
                                <label>Tanggal Kegiatan</label>
                                <input type="date" name="aktivitas[{{ $i }}][tanggal_kegiatan]" class="form-control" value="{{ $detail->tanggal_kegiatan }}" required>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-aktivitas">Hapus</button>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-sm btn-info" id="add-aktivitas">Tambah Aktivitas</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-warning">Perbarui</button>
            </div>
        </div>
    </div>
</form>

<script>
    let aktivitasIndex = {{ count($log->detail) }};

    $('#add-aktivitas').on('click', function () {
        $('#aktivitas-container').append(`
            <div class="aktivitas-item border rounded p-3 mb-3">
                <div class="mb-2">
                    <label>Deskripsi Aktivitas</label>
                    <textarea name="aktivitas[${aktivitasIndex}][deskripsi]" class="form-control" required></textarea>
                </div>
                <div class="mb-2">
                    <label>Lokasi</label>
                    <input type="text" name="aktivitas[${aktivitasIndex}][lokasi]" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Tanggal Kegiatan</label>
                    <input type="date" name="aktivitas[${aktivitasIndex}][tanggal_kegiatan]" class="form-control" required>
                </div>
                <button type="button" class="btn btn-danger btn-sm remove-aktivitas">Hapus</button>
            </div>
        `);
        aktivitasIndex++;
    });

    $(document).on('click', '.remove-aktivitas', function () {
        $(this).closest('.aktivitas-item').remove();
    });

    $('#formEditLogHarian').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const url = "{{ route('logHarian.update', $log->logHarian_id) }}";

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            success: function (res) {
                $('#myModal').modal('hide');
                $('#table_logharian').DataTable().ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: res.success
                });
            },
            error: function (xhr) {
                let message = 'Terjadi kesalahan saat menyimpan data.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: message
                });
            }
        });
    });
</script>
