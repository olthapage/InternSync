@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2>Tambah Mitra</h2>

    <form action="{{ route('mitra.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Mitra</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="1" selected>Aktif</option>
                <option value="0">Nonaktif</option>
            </select>
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('mitra.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
