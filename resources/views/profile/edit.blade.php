<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Edit Profile</h4>
        </div>
        <div class="card-body">
            <form id="editProfileForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="{{ $user->nama_lengkap }}">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                </div>
 
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto Profil</label><br>
                    <img id="profileImage" src="{{ asset('storage/foto/' . $user->foto) }}" width="80" class="rounded mb-2" alt="Foto Profil">
                    <input type="file" name="foto" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('profile.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
            <div id="errorMessage" class="alert alert-danger mt-3" style="display:none;"></div>
            <div id="successMessage" class="alert alert-success mt-3" style="display:none;"></div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#editProfileForm').on('submit', function (e) {
    e.preventDefault();
    let formData = new FormData(this);

    $.ajax({
        url: "{{ route('profile.update') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            toastr.success(res.message, 'Sukses');

            $('#profileImage').attr('src', res.foto + '?' + new Date().getTime());

            $('.navbar-profile-image').attr('src', res.foto + '?' + new Date().getTime());
        },
        error: function () {
            toastr.error("Gagal memperbarui profil");
        }
    });
});
</script>

