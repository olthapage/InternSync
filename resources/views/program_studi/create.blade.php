@extends('layouts.template')

@section('content')
<div class="container">
    <h3>Tambah Program Studi</h3>
    <form id="formTambahProdi">
        @csrf
        <div class="form-group mb-2">
            <label>Kode</label>
            <input type="text" name="kode_prodi" class="form-control" required>
        </div>
        <div class="form-group mb-2">
            <label>Nama</label>
            <input type="text" name="nama_prodi" class="form-control" required>
        </div>
        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function () {
        $('#formTambahProdi').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: "{{ url('program-studi') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    $('#myModal').modal('hide');

                    iziToast.success({
                        title: 'Sukses',
                        message: response.success,
                        position: 'topRight'
                    });

                    dataProdi.ajax.reload();
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            iziToast.error({
                                title: 'Validasi Gagal',
                                message: errors[field][0],
                                position: 'topRight'
                            });
                        }
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: 'Terjadi kesalahan saat menyimpan data!',
                            position: 'topRight'
                        });
                    }
                }
            });
        });
    });
</script>
@endpush