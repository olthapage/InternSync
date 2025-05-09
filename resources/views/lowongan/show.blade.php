@extends('layouts.template')

@section('content')
    <div class="container mt-4">
        <h2>Detail Lowongan</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $lowongan->judul_lowongan }}</h5>
                <p class="card-text"><strong>Deskripsi:</strong></p>
                <p>{{ $lowongan->deskripsi ?? '-' }}</p>
                <p class="card-text"><strong>Industri:</strong> {{ $lowongan->industri->industri_nama ?? '-' }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('lowongan.edit', $lowongan->lowongan_id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('lowongan.destroy', $lowongan->lowongan_id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Hapus</button>
                </form>
                <a href="{{ route('lowongan.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
@endsection
