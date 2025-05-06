@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2>Edit Mitra</h2>

    <form action="{{ route('mitra.update', $mitra->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama" class="form-label">Nama Mitra</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $mitra->nama) }}" required>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $mitra->alamat) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="1" {{ $mitra->status == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $mitra->status == 0 ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('mitra.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
