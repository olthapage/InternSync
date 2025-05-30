<div class="modal-dialog" role="document" style="max-width: 90%;">
    <div class="modal-content border-dark shadow-sm">
        <div class="modal-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="modal-title">Tambah Log Harian</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- Error Handling --}}
            <div id="alert-errors" class="alert alert-danger d-none">
                <ul class="mb-0" id="list-errors"></ul>
            </div>

            <form id="formTambahLog" action="{{ route('logHarian.store') }}" method="POST" autocomplete="off">
                @csrf

                {{-- Hidden input mahasiswa_magang_id --}}
                <input type="hidden" name="mahasiswa_magang_id" value="{{ auth()->user()->mahasiswaMagang->mahasiswa_magang_id ?? '' }}">

                <div class="mb-3">
                    <label for="tanggal" class="form-label">Tanggal Log <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                </div>

                {{-- Table aktivitas dinamis --}}
                <label>Aktivitas Yang Dikerjakan <span class="text-danger">*</span></label>
                <table class="table table-bordered" id="aktivitasTable">
                    <thead>
                        <tr>
                            <th style="width: 35%;">Deskripsi Aktivitas</th>
                            <th style="width: 20%;">Tanggal Kegiatan</th>
                            <th style="width: 20%;">Lokasi Kegiatan</th>
                            <th style="width: 15%;">Status Approval</th>
                            <th style="width: 10%;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <textarea name="aktivitas[0][deskripsi]" class="form-control" rows="2" required>{{ old('aktivitas.0.deskripsi') }}</textarea>
                            </td>
                            <td>
                                <input type="date" name="aktivitas[0][tanggal]" class="form-control" required value="{{ old('aktivitas.0.tanggal', date('Y-m-d')) }}">
                            </td>
                            <td>
                                <input type="hidden" name="aktivitas[0][lokasi]" value="{{ $lokasi->kota_nama ?? '' }}">
                                <input type="text" class="form-control" value="{{ $lokasi->kota_nama ?? '-' }}" readonly>
                            </td>
                            <td>
                                <select name="aktivitas[0][status_approval]" class="form-select" disabled>
                                    <option value="pending" selected>Pending</option>
                                </select>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row" disabled>&times;</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" class="btn btn-sm btn-primary mb-3" id="addAktivitas">Tambah Aktivitas</button>

                <div class="text-end">
                    <button type="submit" class="btn btn-dark">
                        <i class="fas fa-save me-1"></i> Simpan Log
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        let aktivitasIndex = 1;
        const lokasiNama = @json($lokasi->kota_nama ?? '');

        // Tambah baris aktivitas
        $('#addAktivitas').click(function () {
            const today = new Date().toISOString().slice(0, 10);
            const newRow = `
                <tr>
                    <td>
                        <textarea name="aktivitas[${aktivitasIndex}][deskripsi]" class="form-control" rows="2" required></textarea>
                    </td>
                    <td>
                        <input type="date" name="aktivitas[${aktivitasIndex}][tanggal]" class="form-control" required value="${today}">
                    </td>
                    <td>
                        <input type="hidden" name="aktivitas[${aktivitasIndex}][lokasi]" value="${lokasiNama}">
                        <input type="text" class="form-control" value="${lokasiNama}" readonly>
                    </td>
                    <td>
                        <select name="aktivitas[${aktivitasIndex}][status_approval_dosen]" class="form-select" disabled>
                            <option value="pending" selected>Pending</option>
                        </select>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-row">&times;</button>
                    </td>
                </tr>
            `;
            $('#aktivitasTable tbody').append(newRow);
            aktivitasIndex++;
        });

        // Hapus baris aktivitas
        $('#aktivitasTable').on('click', '.remove-row', function () {
            $(this).closest('tr').remove();
        });

        // Submit form dengan AJAX
        $('#formTambahLog').submit(function (e) {
            e.preventDefault();
            const form = $(this);
            $('#alert-errors').addClass('d-none');
            $('#list-errors').empty();

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: form.serialize(),
                success: function (response) {
                    $('#myModal').modal('hide');
                    $('#table_logharian').DataTable().ajax.reload();

                    // TOAST - Berhasil Simpan
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: response.success || 'Log harian berhasil disimpan.',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        $('#alert-errors').removeClass('d-none');
                        $.each(errors, function (key, messages) {
                            messages.forEach(msg => {
                                $('#list-errors').append('<li>' + msg + '</li>');
                            });
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menyimpan log.',
                        });
                    }
                }
            });
        });

        // Hapus data (contoh jika ada tombol hapus AJAX di luar form)
        $('#table_logharian').on('click', '.btn-hapus', function () {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data log harian ini akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/logHarian/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            $('#table_logharian').DataTable().ajax.reload();

                            // TOAST - Berhasil Hapus
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: response.success || 'Log harian berhasil dihapus.',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            });
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menghapus log.'
                            });
                        }
                    });
                }
            });
        });

    });
</script>
