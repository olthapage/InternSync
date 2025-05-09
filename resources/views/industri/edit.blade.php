@extends('layouts.template')

@section('content')
    <div class="container mt-4">
        <h2>Edit Industri</h2>
        <form action="{{ route('industri.update', $industri->industri_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="industri_nama" class="form-label">Nama Industri</label>
                        <input type="text" name="industri_nama" class="form-control" required
                            value="{{ old('industri_nama', $industri->industri_nama ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label for="kota_id" class="form-label">Kota</label>
                        <select name="kota_id" class="form-select" required>
                            <option value="">-- Pilih Kota --</option>
                            @foreach ($kota as $k)
                                <option value="{{ $k->kota_id }}"
                                    {{ old('kota_id', $industri->kota_id ?? '') == $k->kota_id ? 'selected' : '' }}>
                                    {{ $k->kota_nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="kategori_industri_id" class="form-label">Kategori Industri</label>
                        <select name="kategori_industri_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategori as $kat)
                                <option value="{{ $kat->kategori_industri_id }}"
                                    {{ old('kategori_industri_id', $industri->kategori_industri_id ?? '') == $kat->kategori_industri_id ? 'selected' : '' }}>
                                    {{ $kat->kategori_nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success mt-3">Update</button>
            <a href="{{ route('industri.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </form>
    </div>
@endsection
