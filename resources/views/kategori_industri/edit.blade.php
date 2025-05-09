@extends('layouts.template')

@section('content')
    <div class="container mt-4">
        <h2>Edit Kategori Industri</h2>
        <form action="{{ route('kategori-industri.update', $kategori->kategori_industri_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label>Nama Kategori Industri</label>
                <input type="text" name="kategori_nama" class="form-control"
                    value="{{ old('kategori_nama', $kategori->kategori_nama ?? '') }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('kategori-industri.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
