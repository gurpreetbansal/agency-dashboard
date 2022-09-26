<script>
  var BASE_URL = $('.base_url').val();
  $(document).ready(function(){
    fill_datatable();
  });




  $(function(){
    $('.js-example-basic-multiple').select2({
     placeholder: "Enter tag name",
     tags:true,
     tokenSeparators: [','],
     ajax:{
       url: BASE_URL + "/ajax_filter_campaigns",
       dataType: 'json',
       type: "GET",
       data: function (query) {
        return {
         query: query
       };
     },
     processResults: function (data) {
      if(data.length !== 0){
       return {
        results: $.map(data, function (item) {
          return {
            text: item.name,
            id: item.name
          }
        })       
      };
    }
    
    
  }

}
});

    $('.js-example-basic-multiple').on('change', function() {
      if($(".js-example-basic-multiple").select2('data').length > 0){
          var selected = [];  
          $.each($(".js-example-basic-multiple").select2('data'), function(key, item){
            selected.push(item.text);
          });

          $('.selectedCam').val(selected);
          $('#campaigns').DataTable().destroy();
          fill_datatable('tag',selected);
      }else{
          $('#campaigns').DataTable().destroy();
          fill_datatable(); 
      }
    
    })
  });


  function fill_datatable(field = '',tag_name = '')
  {
   var dataTable = $('#campaigns').DataTable({
    "serverSide" : true,
    "pageLength": 10,
    "ajax" : {
     url:BASE_URL + "/ajax_active_campaigns",
     type:"GET",
     data:{field:field,tag_name:tag_name}
   }
 });
 }

 $('#ManagerList').on('change',function(){
  $('#campaigns').DataTable().destroy();
  fill_datatable('manager',$(this).val()); 
});


$(document).on('click','#ResendEmail',function(e){
  e.preventDefault();
  $('#VerificationEmail').show();
  $.ajax({
    type:'POST',
    url:BASE_URL + '/resend_verification_email',
    data:{user_id: $('#user_id').val(), _token:$('meta[name="csrf-token"]').attr('content')},
    dataType:'json',
    success:function(response){
      $('#VerificationEmail').hide();
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

$(document).on('click','.archive_row',function(e){
    e.preventDefault();

    if (!confirm("Are you sure you want to delete?")) {
        return false;
    } 

    var request_id = $(this).attr('data-id');
    if(request_id !=''){
      $.ajax({
        type:'POST',
        url:BASE_URL + '/ajax_archive_campaign',
        data:{request_id:request_id,_token:$('meta[name="csrf-token"]').attr('content')},
        dataType:'json',
        success:function(response){
          if(response['status'] == 1){
            Command: toastr["success"](response['message']);
            $('#campaigns').DataTable().destroy();
            fill_datatable();
            return false;
          }else if(response['status'] == 0){
            Command: toastr["error"](response['message']);
            return false;
          }else{
            Command: toastr["error"]('Getting Error!');
            return false;
          }
        }
      });
    }
});
</script>