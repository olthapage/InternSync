<form action="{{ isset($lowongan) ? route('mahasiswa.lowongan.update', $lowongan->lowongan_id) : route('mahasiswa.lowongan.store') }}" method="POST">
    @csrf
    @if (isset($lowongan))
        @method('PUT')
    @endif

    <div class="mb-3">
        <label class="form-label">Judul Lowongan</label>
        <input type="text" name="judul_lowongan" class="form-control"
               value="{{ old('judul_lowongan', $lowongan->judul_lowongan ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Jumlah Slot</label>
        <input type="number" name="slot" class="form-control"
               value="{{ old('slot', $lowongan->slot ?? '') }}" required min="1">
    </div>

    <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3" required>{{ old('deskripsi', $lowongan->deskripsi ?? '') }}</textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" class="form-control"
               value="{{ old('tanggal_mulai', $lowongan->tanggal_mulai ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" class="form-control"
               value="{{ old('tanggal_selesai', $lowongan->tanggal_selesai ?? '') }}" required>
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-success">Simpan</button>
        <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Batal</button>
    </div>
</form>
