@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Detail Admin</h2>
    <div class="card">
        <div class="card-body">
            <p><strong>Nama Lengkap:</strong> {{ $admin->nama_lengkap }}</p>
            <p><strong>Email:</strong> {{ $admin->email }}</p>
            <p><strong>Level:</strong> {{ $admin->level->level_nama ?? '-' }}</p>
        </div>
    </div>
    <a href="{{ route('admin.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
