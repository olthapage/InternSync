@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Program Studi</h3>
    <form action="{{ route('program-studi.store') }}" method="POST">
        @csrf
        <div class="form-group mb-2">
            <label>Kode</label>
            <input type="text" name="kode" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
