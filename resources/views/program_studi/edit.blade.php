@extends('layouts.template')

@section('content')
<div class="container">
    <h3>Edit Program Studi</h3>
    <form action="{{ route('program-studi.update', $prodi->prodi_id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group mb-2">
            <label>Kode</label>
            <input type="text" name="kode_prodi" class="form-control" value="{{ $prodi->kode_prodi }}" required>
        </div>
        <div class="form-group mb-2">
            <label>Nama</label>
            <input type="text" name="nama_prodi" class="form-control" value="{{ $prodi->nama_prodi }}" required>
        </div>
        <button class="btn btn-success">Update</button>
    </form>
</div>
@endsection
