{{-- Pastikan alert-errors-edit unik jika create modal juga bisa terbuka di halaman yang sama --}}
<div id="alert-errors-edit" class="alert alert-danger d-none">
    <ul class="mb-0" id="list-errors-edit"></ul>
</div>

<form id="formEditLogHarian_{{ $log->logHarian_id }}"> {{-- Beri ID unik jika ada banyak modal edit potensial --}}
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
                    <label for="tanggal_edit_{{ $log->logHarian_id }}" class="form-label">Tanggal Umum <span class="text-danger">*</span></label>
                    {{-- Format tanggal untuk input type="date" --}}
                    <input type="date" name="tanggal" id="tanggal_edit_{{ $log->logHarian_id }}" class="form-control" value="{{ old('tanggal', \Carbon\Carbon::parse($log->tanggal)->format('Y-m-d')) }}" required>
                </div>

                <label>Aktivitas Yang Dikerjakan <span class="text-danger">*</span></label>
                <div id="aktivitas-container-edit-{{ $log->logHarian_id }}">
                    @if($log->detail->isEmpty())
                        {{-- Jika tidak ada detail, tambahkan satu item default --}}
                        <div class="aktivitas-item border rounded p-3 mb-3">
                             <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6>Aktivitas 1</h6>
                                {{-- Tombol hapus dinonaktifkan jika hanya satu item --}}
                                <button type="button" class="btn btn-danger btn-sm remove-aktivitas-edit" disabled>&times;</button>
                            </div>
                            <div class="mb-2">
                                <label>Deskripsi Aktivitas <span class="text-danger">*</span></label>
                                <textarea name="aktivitas[0][deskripsi]" class="form-control" required></textarea>
                            </div>
                            <div class="mb-2">
                                <label>Lokasi <span class="text-danger">*</span></label>
                                {{-- Gunakan defaultLokasiMagang untuk item pertama jika log baru atau tidak ada detail --}}
                                <input type="text" name="aktivitas[0][lokasi]" class="form-control" value="{{ $defaultLokasiMagang ?? '' }}" required>
                            </div>
                            <div class="mb-2">
                                <label>Tanggal Kegiatan <span class="text-danger">*</span></label>
                                <input type="date" name="aktivitas[0][tanggal_kegiatan]" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                             {{-- Status approval tidak di-render untuk diedit mahasiswa --}}
                        </div>
                    @else
                        @foreach ($log->detail as $i => $detail)
                            <div class="aktivitas-item border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                   <h6>Aktivitas {{ $loop->iteration }}</h6>
                                   {{-- Tombol hapus dinonaktifkan jika hanya satu item dan itu adalah item pertama --}}
                                   <button type="button" class="btn btn-danger btn-sm remove-aktivitas-edit" {{ $log->detail->count() <= 1 ? 'disabled' : '' }}>&times;</button>
                               </div>
                                <div class="mb-2">
                                    <label>Deskripsi Aktivitas <span class="text-danger">*</span></label>
                                    <textarea name="aktivitas[{{ $i }}][deskripsi]" class="form-control" required>{{ old('aktivitas.'.$i.'.deskripsi', $detail->isi) }}</textarea>
                                </div>
                                <div class="mb-2">
                                    <label>Lokasi <span class="text-danger">*</span></label>
                                    <input type="text" name="aktivitas[{{ $i }}][lokasi]" class="form-control" value="{{ old('aktivitas.'.$i.'.lokasi', $detail->lokasi) }}" required>
                                </div>
                                <div class="mb-2">
                                    <label>Tanggal Kegiatan <span class="text-danger">*</span></label>
                                    {{-- Format tanggal untuk input type="date" --}}
                                    <input type="date" name="aktivitas[{{ $i }}][tanggal_kegiatan]" class="form-control" value="{{ old('aktivitas.'.$i.'.tanggal_kegiatan', \Carbon\Carbon::parse($detail->tanggal_kegiatan)->format('Y-m-d')) }}" required>
                                </div>
                                {{-- Status approval tidak di-render untuk diedit mahasiswa --}}
                                {{-- Jika perlu menyimpan status approval yang ada tanpa mengubahnya: --}}
                                @if(isset($detail->status_approval_dosen))
                                    <input type="hidden" name="aktivitas[{{ $i }}][status_approval_dosen]" value="{{ $detail->status_approval_dosen }}">
                                @endif
                                @if(isset($detail->status_approval_industri))
                                    <input type="hidden" name="aktivitas[{{ $i }}][status_approval_industri]" value="{{ $detail->status_approval_industri }}">
                                @endif
                                 @if(isset($detail->catatan_dosen))
                                    <input type="hidden" name="aktivitas[{{ $i }}][catatan_dosen]" value="{{ $detail->catatan_dosen }}">
                                @endif
                                @if(isset($detail->catatan_industri))
                                    <input type="hidden" name="aktivitas[{{ $i }}][catatan_industri]" value="{{ $detail->catatan_industri }}">
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>

                <button type="button" class="btn btn-sm btn-info" id="add-aktivitas-edit-{{ $log->logHarian_id }}">Tambah Aktivitas</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-warning">Perbarui</button>
            </div>
        </div>
    </div>
</form>

<script>
// Pastikan skrip ini dieksekusi setiap kali modal edit dimuat/ditampilkan,
// atau gunakan event delegation jika modal dimuat secara dinamis.
(function() {
    // Ambil ID unik log untuk skrip ini, agar tidak konflik jika ada banyak modal edit di satu halaman
    const logId = "{{ $log->logHarian_id }}";
    const formId = `formEditLogHarian_${logId}`;
    const aktivitasContainerId = `aktivitas-container-edit-${logId}`;
    const addAktivitasBtnId = `add-aktivitas-edit-${logId}`;

    const alertErrorsId = `alert-errors-edit`; // Jika ingin unik per modal: `alert-errors-edit-${logId}`
    const listErrorsId = `list-errors-edit`;   // Jika ingin unik per modal: `list-errors-edit-${logId}`


    // Index dihitung dari jumlah item yang sudah ada dari server
    // Jika tidak ada detail dari server, dan kita menambahkan satu item default di blade, index mulai dari 1
    let aktivitasIndexEdit = {{ $log->detail->isEmpty() ? 1 : $log->detail->count() }};

    // Ambil default lokasi magang dari controller (dikirim ke view sebagai $defaultLokasiMagang)
    const defaultLokasiMagangEditJs = @json($defaultLokasiMagang ?? 'Alamat tidak tersedia');
    const todayEditJs = new Date().toISOString().slice(0, 10);

    function checkMinAktivitasEdit() {
        const itemCount = $(`#${aktivitasContainerId} .aktivitas-item`).length;
        $(`#${aktivitasContainerId} .remove-aktivitas-edit`).prop('disabled', itemCount <= 1);
    }
    checkMinAktivitasEdit(); // Panggil saat inisialisasi

    $(`#${addAktivitasBtnId}`).off('click').on('click', function () {
        const newIndex = aktivitasIndexEdit;
        $(`#${aktivitasContainerId}`).append(`
            <div class="aktivitas-item border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                   <h6>Aktivitas ${$(`#${aktivitasContainerId} .aktivitas-item`).length + 1}</h6>
                   <button type="button" class="btn btn-danger btn-sm remove-aktivitas-edit">&times;</button>
               </div>
                <div class="mb-2">
                    <label>Deskripsi Aktivitas <span class="text-danger">*</span></label>
                    <textarea name="aktivitas[${newIndex}][deskripsi]" class="form-control" required></textarea>
                </div>
                <div class="mb-2">
                    <label>Lokasi <span class="text-danger">*</span></label>
                    <input type="text" name="aktivitas[${newIndex}][lokasi]" class="form-control" value="${defaultLokasiMagangEditJs}" required>
                </div>
                <div class="mb-2">
                    <label>Tanggal Kegiatan <span class="text-danger">*</span></label>
                    <input type="date" name="aktivitas[${newIndex}][tanggal_kegiatan]" class="form-control" value="${todayEditJs}" required>
                </div>
                {{-- Status approval defaultnya 'pending' dan tidak diubah oleh mahasiswa --}}
                <input type="hidden" name="aktivitas[${newIndex}][status_approval_dosen]" value="pending">
                <input type="hidden" name="aktivitas[${newIndex}][status_approval_industri]" value="pending">
            </div>
        `);
        aktivitasIndexEdit++;
        checkMinAktivitasEdit();
    });

    // Gunakan event delegation karena item aktivitas bisa dinamis
    $(document).off('click', `#${aktivitasContainerId} .remove-aktivitas-edit`).on('click', `#${aktivitasContainerId} .remove-aktivitas-edit`, function () {
        $(this).closest('.aktivitas-item').remove();
         // Re-number visual counter if necessary
        $(`#${aktivitasContainerId} .aktivitas-item`).each(function(idx, item) {
            $(item).find('h6').first().text(`Aktivitas ${idx + 1}`);
        });
        checkMinAktivitasEdit();
    });

    $(`#${formId}`).off('submit').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();
        // Ambil URL dari action form jika tidak mau hardcode
        const url = $(this).attr('action') || "{{ route('logHarian.update', $log->logHarian_id) }}";

        // Reset error messages
        $(`#${alertErrorsId}`).addClass('d-none');
        $(`#${listErrorsId}`).empty();

        $.ajax({
            url: url,
            method: 'POST', // Laravel akan menghandle PUT dari _method field
            data: formData,
            success: function (res) {
                $('#myModal').modal('hide'); // Asumsi ID modal global adalah myModal
                if (typeof $('#table_logharian').DataTable === 'function') {
                    $('#table_logharian').DataTable().ajax.reload();
                }
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: res.success || 'Log Harian Berhasil Diperbarui',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            },
            error: function (xhr) {
                $(`#${alertErrorsId}`).removeClass('d-none');
                if (xhr.status === 422) { // Validation error
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function (key, messages) {
                        let fieldName = key;
                         // Penanganan nama field array yang lebih baik untuk pesan error
                        const match = key.match(/aktivitas\.(\d+)\.(.+)/);
                        if (match) {
                            const index = parseInt(match[1]) + 1; // Index berbasis 1 untuk pengguna
                            const property = match[2].replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                            fieldName = `Aktivitas ${index} ${property}`;
                        } else {
                            fieldName = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                        }

                        messages.forEach(msg => {
                            // Ganti placeholder generik dengan nama field yang lebih spesifik
                            let userFriendlyMsg = msg.replace(key, fieldName);
                            // Ganti 'validation.required' atau pesan default lainnya jika ada
                            userFriendlyMsg = userFriendlyMsg.replace(/The (aktivitas\.\d+\..+?) field is required\./gi, `Kolom ${fieldName} wajib diisi.`);
                            $(`#${listErrorsId}`).append('<li>' + userFriendlyMsg + '</li>');
                        });
                    });
                } else if (xhr.responseJSON && xhr.responseJSON.error) {
                     $(`#${listErrorsId}`).append('<li>'   + xhr.responseJSON.error + '</li>');
                     Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: xhr.responseJSON.error
                    });
                }
                else {
                    const message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat memperbarui data.';
                    $(`#${listErrorsId}`).append('<li>' + message + '</li>');
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: message
                    });
                }
            }
        });
    });
})(); // IIFE untuk encapsulation
</script>
