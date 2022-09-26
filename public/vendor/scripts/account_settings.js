var BASE_URL = $('.base_url').val();
$(document).on('change','#cancel_subscription',function(e){
e.preventDefault();
	if ($(this).is(':checked') == true) {
        $.ajax({
        	type:'POST',
        	data:{status:'true',_token:$('meta[name="csrf-token"]').attr('content')},
        	url:BASE_URL +'/cancel_stripe_subscription',
        	dataType:'json',
        	success:function(response){
        		console.log(response);
        	}
        });
    } else if ($(this).is(':checked') == false) { 
       console.log('false');// To verify
    }
 
	
});