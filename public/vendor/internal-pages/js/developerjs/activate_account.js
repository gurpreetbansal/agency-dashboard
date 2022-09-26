	$(document).on('click','#ResendEmail',function(e){
    e.preventDefault();
    $(this).attr('disabled','disabled');
    $.ajax({
      type:'POST',
      url:BASE_URL + '/resend_verification_email',
      data:{user_id: $('#user_id').val(), _token:$('meta[name="csrf-token"]').attr('content')},
      dataType:'json',
      success:function(response){
        $('#ResendEmail').removeAttr('disabled');
        if (response['status'] == 0) {
          Command: toastr["warning"](response['message']);
          return false;
        }

        if (response['status'] == 1) {
          Command: toastr["success"](response['message']);
          return false;
        }

      }
    })
  });