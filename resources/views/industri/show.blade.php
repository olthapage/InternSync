@extends('layouts.template')

@section('content')
    <div class="container mt-4">
        <h2>Detail Industri</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $industri->industri_nama }}</h5>
                <p class="card-text"><strong>Kota:</strong> {{ $industri->kota->kota_nama ?? '-' }}</p>
                <p class="card-text"><strong>Kategori Industri:</strong> {{ $industri->kategori_industri->kategori_nama ?? '-' }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('industri.edit', $industri->industri_id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('industri.destroy', $industri->industri_id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Hapus</button>
                </form>
                <a href="{{ route('industri.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
@endsection
