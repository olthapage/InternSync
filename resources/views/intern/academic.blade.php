@extends('layouts.template')

@section('content')
<div class="container py-4">
  <h3>Profil Akademik & Keterampilan</h3>
  <div id="academic-form-container">
    @include('intern.academic-form')
  </div>
</div>
@endsection

@push('js')
<script>
$(function(){
  $('#form-academic').on('submit', function(e){
    e.preventDefault();
    let data = new FormData(this);
    $('.text-danger').text('');
    $('#alertMessage').hide();

    $.ajax({
      url: "{{ route('intern.storeAcademicProfile') }}",
      type: "POST",
      data: data,
      processData: false,
      contentType: false,
      success(res) {
        $('#alertMessage')
          .removeClass('alert-danger').addClass('alert-success')
          .text(res.message).show();
      },
      error(xhr) {
        if (xhr.status === 422) {
          let errs = xhr.responseJSON.msgField;
          $.each(errs, function(field, msgs){
            let key = field.replace(/\.\d+$/, '');
            $('#error-' + key).text(msgs[0]);
          });
        } else {
          $('#alertMessage')
            .removeClass('alert-success').addClass('alert-danger')
            .text('Terjadi kesalahan pada server.').show();
        }
      }
    });
  });
 // fungsi untuk load via AJAX dipindah ke partial atau script global
});
</script>
@endpush