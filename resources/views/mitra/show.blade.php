@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2>Detail Mitra</h2>

    <div class="mb-3">
        <label class="form-label">Nama Mitra</label>
        <p>{{ $mitra->nama }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label">Alamat</label>
        <p>{{ $mitra->alamat }}</p>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <p>{{ $mitra->status == 1 ? 'Aktif' : 'Nonaktif' }}</p>
    </div>

    <a href="{{ route('mitra.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
