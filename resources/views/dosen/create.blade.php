@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2>Tambah Dosen</h2>
    <form action="{{ route('dosen.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $dosen->nama_lengkap ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $dosen->email ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Password {{ isset($edit) ? '(Kosongkan jika tidak diganti)' : '' }}</label>
            <input type="password" name="password" class="form-control" {{ isset($edit) ? '' : 'required' }}>
        </div>
        <div class="mb-3">
            <label>NIDN</label>
            <input type="text" name="nip" class="form-control" value="{{ old('nip', $dosen->nip ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label>Level</label>
            <select name="level_id" class="form-control" required>
                <option value="">-- Pilih Level --</option>
                @foreach($level as $lvl)
                <option value="{{ $lvl->level_id }}" {{ old('level_id', $dosen->level_id ?? '') == $lvl->level_id ? 'selected' : '' }}>{{ $lvl->nama_level }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Program Studi</label>
            <select name="prodi_id" class="form-control" required>
                <option value="">-- Pilih Prodi --</option>
                @foreach($prodi as $prd)
                <option value="{{ $prd->prodi_id }}" {{ old('prodi_id', $dosen->prodi_id ?? '') == $prd->prodi_id ? 'selected' : '' }}>{{ $prd->nama_prodi }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
