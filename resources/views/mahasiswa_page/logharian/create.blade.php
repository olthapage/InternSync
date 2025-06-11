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

                {{-- Hidden input mahasiswa_magang_id tidak diperlukan lagi di sini jika diambil dari Auth di controller --}}
                {{-- <input type="hidden" name="mahasiswa_magang_id" value="{{ auth()->user()->mahasiswaMagang->mahasiswa_magang_id ?? '' }}"> --}}

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
                                {{-- Pastikan name konsisten dengan validasi dan store method: aktivitas[0][tanggal] atau aktivitas[0][tanggal_kegiatan] --}}
                                <input type="date" name="aktivitas[0][tanggal]" class="form-control" required value="{{ old('aktivitas.0.tanggal', date('Y-m-d')) }}">
                            </td>
                            <td>
                                {{-- Input lokasi sekarang bisa diedit, value diisi default lokasi magang --}}
                                <input type="text" name="aktivitas[0][lokasi]" class="form-control" value="{{ old('aktivitas.0.lokasi', $defaultLokasiMagang ?? '') }}" required>
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
        // Tentukan batas maksimal aktivitas yang bisa ditambahkan
        const maxAktivitas = 3;

        const defaultLokasiMagangJs = @json($defaultLokasiMagang ?? 'Alamat tidak tersedia');

        // --- FUNGSI BARU ---
        // Fungsi untuk memeriksa dan mengatur status tombol "Tambah Aktivitas"
        function updateAddButtonState() {
            const rowCount = $('#aktivitasTable tbody tr').length;
            if (rowCount >= maxAktivitas) {
                // Jika jumlah baris sudah mencapai atau melebihi batas, nonaktifkan tombol
                $('#addAktivitas').prop('disabled', true);
            } else {
                // Jika belum, pastikan tombol aktif
                $('#addAktivitas').prop('disabled', false);
            }
        }

        // --- MODIFIKASI: Event click untuk Tambah Aktivitas ---
        $('#addAktivitas').click(function () {
            // Pengecekan dilakukan di dalam fungsi updateAddButtonState
            // jadi kita tidak perlu cek di sini lagi sebelum menambah
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
                        <input type="text" name="aktivitas[${aktivitasIndex}][lokasi]" class="form-control" value="${defaultLokasiMagangJs}" required>
                    </td>
                    <td>
                        <select name="aktivitas[${aktivitasIndex}][status_approval]" class="form-select" disabled>
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

            // Panggil fungsi untuk update status tombol setelah menambah baris baru
            updateAddButtonState();
        });

        // --- MODIFIKASI: Event click untuk Hapus Aktivitas ---
        $('#aktivitasTable').on('click', '.remove-row', function () {
            $(this).closest('tr').remove();

            // Panggil fungsi untuk update status tombol setelah menghapus baris
            updateAddButtonState();
        });

        // Panggil fungsi saat halaman pertama kali dimuat untuk memastikan status tombol sudah benar
        updateAddButtonState();

        // --- Bagian AJAX Submit Form (Tidak ada perubahan) ---
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
                    $('#myModal').modal('hide'); // Asumsi modal Anda memiliki id="myModal"
                    if (typeof $('#table_logharian').DataTable === 'function') {
                        $('#table_logharian').DataTable().ajax.reload();
                    }

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
                    if (xhr.status === 422) { // Validation error
                        const errors = xhr.responseJSON.errors;
                        $('#alert-errors').removeClass('d-none');
                        $.each(errors, function (key, messages) {
                            let fieldName = key;
                            if (key.includes('.')) {
                                fieldName = key.split('.').slice(1).join(' ');
                                fieldName = fieldName.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()); // Capitalize
                            }
                            messages.forEach(msg => {
                                $('#list-errors').append('<li>' + msg.replace(key, fieldName) + '</li>');
                            });
                        });
                    } else if (xhr.responseJSON && xhr.responseJSON.error) { // Custom error dari controller
                        $('#alert-errors').removeClass('d-none');
                        $('#list-errors').append('<li>' + xhr.responseJSON.error + '</li>');
                         Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON.error || 'Terjadi kesalahan saat menyimpan log.',
                        });
                    }
                    else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menyimpan log.',
                        });
                    }
                }
            });
        });
    });
</script>
