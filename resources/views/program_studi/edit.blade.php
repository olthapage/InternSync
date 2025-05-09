@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Program Studi</h3>
    <form action="{{ route('program-studi.update', $prodi->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group mb-2">
            <label>Kode</label>
            <input type="text" name="kode" class="form-control" value="{{ $prodi->kode }}" required>
        </div>
        <div class="form-group mb-2">
            <label>Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ $prodi->nama }}" required>
        </div>
        <button class="btn btn-success">Update</button>
    </form>
</div>
@endsection
