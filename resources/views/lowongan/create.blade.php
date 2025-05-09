@extends('layouts.template')

@section('content')
    <div class="container mt-4">
        <h2>Tambah Lowongan</h2>
        <form action="{{ route('lowongan.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="judul_lowongan" class="form-label">Judul Lowongan</label>
                        <input type="text" name="judul_lowongan" class="form-control" required
                            value="{{ old('judul_lowongan') }}">
                    </div>

                    <div class="mb-3">
                        <label for="industri_id" class="form-label">Industri</label>
                        <select name="industri_id" class="form-select" required>
                            <option value="">-- Pilih Industri --</option>
                            @foreach ($industri as $i)
                                <option value="{{ $i->industri_id }}"
                                    {{ old('industri_id') == $i->industri_id ? 'selected' : '' }}>
                                    {{ $i->industri_nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4" required>{{ old('deskripsi') }}</textarea>
                    </div>
                </div>

            </div>

            <button type="submit" class="btn btn-primary mt-3">Simpan</button>
            <a href="{{ route('lowongan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </form>
    </div>
@endsection
