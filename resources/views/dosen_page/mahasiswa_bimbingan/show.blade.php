{{-- dosen_page/mahasiswa_bimbingan/show.blade.php --}}
{{-- Menambahkan wrapper .modal-dialog untuk struktur Bootstrap Modal yang benar --}}
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Detail Mahasiswa Bimbingan</h5>
        </div>
        <div class="modal-body">
            @if ($mahasiswa)
                <div class="row">
                    <div class="col-md-4 text-center">
                        {{-- Pastikan path default image benar atau gunakan placeholder jika perlu --}}
                        <img src="{{ $mahasiswa->foto ? asset('storage/foto_mahasiswa/' . $mahasiswa->foto) : asset('assets/default-profile.png') }}"
                            alt="Foto Mahasiswa" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h5>{{ $mahasiswa->nama_lengkap }}</h5>
                        <p class="text-muted">{{ $mahasiswa->nim }}</p>
                    </div>
                    <div class="col-md-8">
                        <h6>Informasi Akademik:</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td style="width: 150px;"><strong>Program Studi</strong></td>
                                <td>: {{ $mahasiswa->prodi->nama_prodi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>IPK</strong></td>
                                <td>: {{ $mahasiswa->ipk ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Dosen Pembimbing</strong></td>
                                <td>: {{ $mahasiswa->dosenPembimbing->nama_lengkap ?? '-' }}</td>
                            </tr>
                             <tr>
                                <td><strong>DPA</strong></td>
                                <td>: {{ $mahasiswa->dpa->nama_lengkap ?? '-' }}</td>
                            </tr>
                        </table>
                        <hr>
                        <h6>Informasi Magang:</h6>
                        @if ($mahasiswa->magang)
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td style="width: 150px;"><strong>Tempat Magang</strong></td>
                                    <td>: {{ $mahasiswa->magang->lowongan->industri->industri_nama ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Posisi/Judul</strong></td>
                                    <td>: {{ $mahasiswa->magang->lowongan->judul_lowongan ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status Magang</strong></td>
                                    <td>:
                                        @php
                                            $statusMagang = $mahasiswa->magang->status;
                                            $badgeClass = 'badge-light';
                                            if ($statusMagang == 'selesai') $badgeClass = 'bg-gradient-success';
                                            elseif ($statusMagang == 'sedang') $badgeClass = 'bg-gradient-info';
                                            elseif ($statusMagang == 'belum') $badgeClass = 'bg-gradient-primary';
                                        @endphp
                                        <span class="badge badge-sm {{ $badgeClass }}">{{ ucfirst(htmlspecialchars($statusMagang)) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Evaluasi Mitra</strong></td>
                                    <td>: {{ $mahasiswa->magang->evaluasi ?? 'Belum ada evaluasi dari mitra.' }}</td>
                                </tr>
                            </table>

                            {{-- Form Feedback Dosen --}}
                            @if ($mahasiswa->magang->status == 'selesai')
                                <hr>
                                <h6>Feedback Dosen Pembimbing:</h6>
                                <form id="formFeedbackDosen" data-magang-id="{{ $mahasiswa->magang->mahasiswa_magang_id }}">
                                    @csrf {{-- Tetap sertakan ini sebagai fallback dan untuk non-JS --}}
                                    <div class="form-group">
                                        <textarea name="feedback_dosen" id="feedback_dosen" class="form-control" rows="4"
                                            placeholder="Berikan feedback Anda mengenai pelaksanaan magang mahasiswa...">{{ $mahasiswa->magang->feedback_dosen ?? '' }}</textarea>
                                        <small id="feedback_dosen_error" class="text-danger"></small>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-save mr-1"></i>
                                        {{ $mahasiswa->magang->feedback_dosen ? 'Update Feedback' : 'Simpan Feedback' }}
                                    </button>
                                </form>
                                <div id="feedbackSuccessMessage" class="alert alert-success mt-2" style="display: none;"></div>
                                <div id="feedbackErrorMessage" class="alert alert-danger mt-2" style="display: none;"></div>
                            @elseif($mahasiswa->magang->feedback_dosen)
                                 <hr>
                                <h6>Feedback Dosen Pembimbing:</h6>
                                <div class="alert alert-secondary">
                                    {{ $mahasiswa->magang->feedback_dosen }}
                                </div>
                            @else
                                <p class="text-muted mt-2"><em>Mahasiswa belum menyelesaikan magang. Feedback dapat diberikan setelah status magang "Selesai".</em></p>
                            @endif
                        @else
                            <p class="text-muted">Mahasiswa ini belum memiliki data magang.</p>
                        @endif
                    </div>
                </div>
            @else
                <p>Data mahasiswa tidak ditemukan.</p>
            @endif
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
    </div> {{-- Akhir .modal-content --}}
</div> {{-- Akhir .modal-dialog --}}

<script>
    $(document).ready(function() {
        var action = "{{ $action ?? '' }}";
        var magangStatus = @json($mahasiswa->magang->status ?? null);

        if (action === "feedback" && magangStatus === "selesai") {
            $('#feedback_dosen').focus();
            console.log("Fokus ke textarea feedback_dosen.");
        }

        $('#formFeedbackDosen').on('submit', function(e) {
            e.preventDefault();
            console.log("Form feedback disubmit.");
            $('#feedback_dosen_error').text('');
            $('#feedbackSuccessMessage').hide().text('');
            $('#feedbackErrorMessage').hide().text('');

            var magangId = $(this).data('magang-id');
            var formData = $(this).serialize(); // Ini akan mengambil _token dari @csrf juga
            var submitButton = $(this).find('button[type="submit"]');
            var originalButtonText = submitButton.html();

            submitButton.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...').prop('disabled', true);

            $.ajax({
                url: "{{ route('mahasiswa-bimbingan.feedback.update', ['mahasiswa_magang_id' => ':magang_id']) }}".replace(':magang_id', magangId),
                type: 'POST',
                data: formData,
                dataType: 'json',
                headers: { // Tambahkan header X-CSRF-TOKEN
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log("Feedback AJAX success:", response);
                    if (response.success) {
                        $('#feedbackSuccessMessage').text(response.message).show();
                        submitButton.html('<i class="fas fa-save mr-1"></i> Update Feedback');
                        if (typeof $('#table_mahasiswa_bimbingan').DataTable === 'function') {
                           // $('#table_mahasiswa_bimbingan').DataTable().ajax.reload(null, false);
                        }
                    } else {
                        $('#feedbackErrorMessage').text(response.message || 'Gagal menyimpan feedback. Respons tidak sukses.').show();
                    }
                },
                error: function(xhr) {
                    console.error("Feedback AJAX error:", xhr);
                    var errorMessage = 'Terjadi kesalahan server.';
                    if (xhr.status === 419) { // Status 419 biasanya untuk CSRF token mismatch atau session expired
                        errorMessage = 'Sesi Anda mungkin telah berakhir atau token keamanan tidak valid. Silakan muat ulang halaman dan coba lagi.';
                    } else if (xhr.responseJSON) {
                        if (xhr.status === 422 && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            if (errors.feedback_dosen) {
                                $('#feedback_dosen_error').text(errors.feedback_dosen[0]);
                                errorMessage = null;
                            } else {
                                errorMessage = 'Terjadi kesalahan validasi.';
                            }
                        } else if (xhr.responseJSON.message) {
                             errorMessage = xhr.responseJSON.message;
                        }
                    } else if (xhr.responseText) {
                        errorMessage = "Error: " + xhr.status + " " + xhr.statusText;
                    }

                    if(errorMessage){
                        $('#feedbackErrorMessage').text(errorMessage).show();
                    }
                },
                complete: function() {
                    console.log("Feedback AJAX complete.");
                     if (!$('#feedbackSuccessMessage').is(':visible') || submitButton.html().includes('Menyimpan')) {
                         submitButton.html(originalButtonText).prop('disabled', false);
                     } else {
                         submitButton.prop('disabled', false);
                     }
                }
            });
        });
        // Handler eksplisit untuk tombol close modal
        // Menargetkan tombol 'x' di header dan tombol 'Tutup' di footer dalam konten modal yang baru dimuat
        $('.modal-header .close[data-dismiss="modal"], .modal-footer button[data-dismiss="modal"]').on('click', function(e) {
            e.preventDefault(); // Mencegah perilaku default jika ada
            console.log('Tombol close modal diklik secara eksplisit. Menutup #myModal.');
            // Modal instance ada pada elemen #myModal di halaman index
            $('#myModal').modal('hide');
        });
    });
</script>
