@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2>Detail Dosen</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $dosen->nama_lengkap }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $dosen->email }}</p>
            <p class="card-text"><strong>NIP:</strong> {{ $dosen->nip }}</p>
            <p class="card-text"><strong>Program Studi:</strong> {{ $dosen->prodi->nama_prodi ?? '-' }}</p>
            <p class="card-text"><strong>Level:</strong> {{ $dosen->level->level_nama ?? '-' }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('dosen.edit', $dosen->dosen_id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('dosen.destroy', $dosen->dosen_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
            <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection
