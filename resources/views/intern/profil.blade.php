@extends('layouts.template')

@section('content')
<div class="container py-4">
  <h3>Profil Akademik & Keterampilan</h3>

  @include('intern.partials.academic-form')
</div>
@endsection
@push('js')
<script>
$(function(){
  $('#form-academic').on('submit', function(e){
    e.preventDefault();
    let form = document.getElementById('form-academic');
    let data = new FormData(form);

    $('.text-danger').text('');
    $('#alertMessage').hide();

    $.ajax({
      url:   "{{ url('/intern/academic-profile') }}",
      type:  "POST",
      data:  data,
      processData: false,
      contentType: false,
      success: function(res){
        $('#alertMessage').removeClass('alert-danger').addClass('alert-success')
          .text(res.message).show();
      },
      error: function(xhr){
        if (xhr.status === 422) {
          let errors = xhr.responseJSON.errors;
          $.each(errors, function(field, msgs){
            let key = field.replace(/\.\d+$/, '');
            $('#error-' + key).text(msgs[0]);
          });
        } else {
          $('#alertMessage').removeClass('alert-success').addClass('alert-danger')
            .text('Terjadi kesalahan pada server.').show();
        }
      }
    });
  });
  $('#btn-close-academic').on('click', function() {
    console.log('Tombol Tutup Form Akademik diklik');
    $('#form-academic').hide(); 
  });
});
</script>
@endpush
