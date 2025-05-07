@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Edit Admin</h2>
    <form action="{{ route('admin.update', $admin->user_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" name="nama_lengkap" value="{{ $admin->nama_lengkap }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="{{ $admin->email }}" required>
        </div>
        <div class="mb-3">
            <label for="level_id" class="form-label">Level</label>
            <input type="number" class="form-control" name="level_id" value="{{ $admin->level_id }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('admin.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
