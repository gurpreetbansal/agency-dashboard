var BASE_URL = $('.base_url').val();

$(document).on('change','#country',function(){    
  if($(this).val() == ''){
    $("#country").addClass('is-invalid'); 
  }else{
    $("#country").removeClass('is-invalid'); 
  }
});

/*free-forever-section*/
$(document).on('click','.place-order-free-forever',function(){
 var billing_name = $("input[name='billing_name']").val();
 var billing_email = $("input[name='billing_email']").val();
 var address_line_1 = $("input[name='address_line_1']").val();
 var address_line_2 = $("input[name='address_line_2']").val();
 var city = $("input[name='city']").val();
 var country = $("#free-forever-country").val();
 var postal_code = $("input[name='postal_code']").val();
 var package_id = $('input[name="package_id"]').val();
 var data_key = $('input[name="data-key"]').val();
 var existing_user = $('input[name="existing_user"]').val();

 if(billing_name == ''){
  $('#billing_name').addClass('is-invalid');
}else{
  $('#billing_name').removeClass('is-invalid');  
}
if(address_line_1 == ''){
  $('input[name="address_line_1"]').addClass('is-invalid');
}else{
  $('input[name="address_line_1"]').removeClass('is-invalid');  
}
if(city == ''){
  $("input[name='city']").addClass('is-invalid');
}else{
  $("input[name='city']").removeClass('is-invalid');  
}
if(country == undefined || country == 'undefined' || country == ''){
  $("#country").addClass('is-invalid');
}else{
  $("#country").removeClass('is-invalid');
}
if(postal_code == ''){
  $("input[name='postal_code']").addClass('is-invalid');
}else{
  $("input[name='postal_code']").removeClass('is-invalid');  
}


if(billing_name != '' && billing_email != '' && address_line_1 != '' && city != '' && country != '' && postal_code != ''){
  $.ajax({
   url: BASE_URL+'/create_free_forever_subscription',
   type: 'post',
   data: {package_id,data_key ,existing_user,billing_email,billing_name,address_line_1,address_line_2,city,country,postal_code,_token:$('meta[name="csrf-token"]').attr('content')},  
   dataType: 'json',
   success: function (json) {
    if(json['status'] == 1){
    window.location.href = json['url'];
    }else{
      Command: toastr["error"]('Error, kindly select the plan again.');
    }
  }
});
}
});



$(document).on('change','#free-forever-country',function(){    
  if($(this).val() == '' || $(this).val()==undefined){
    $("#country").addClass('is-invalid'); 
  }else{
    $("#country").removeClass('is-invalid'); 
  }
});