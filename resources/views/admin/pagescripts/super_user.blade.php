<script type="text/javascript">
	var BASE_URL = "<?php echo url('/') ?>";
	CKEDITOR.replace('editor1');

	$(document).ready(function(){
		fill_datatable();
	});
	$(document).on('click','.suspend_user',function(e){
		e.preventDefault();
		
		if (!confirm("Are you sure you want to suspend?")) {
			return false;
		} 

		$.ajax({
			type:'POST',
			url:BASE_URL +'/admin/ajax_suspend_user',
			data:{user_id:$(this).attr('data-id'),value:$(this).attr('data-value'),_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 1){
					Command: toastr["success"](response['message']);
					$('#SuperUser').DataTable().destroy();
					fill_datatable();
					return false;
				}else if(response['status'] == 0){
					Command: toastr["error"](response['message']);
					return false;
				}else{
					Command: toastr["error"]('Error!!');
					return false;
				}
			}
		});
	});

	function fill_datatable(){
		$('#SuperUser').DataTable({
			pageLength:25,
			processing: true,
			serverSide: true,
			"deferRender": true,
			"order": [
			[1, "asc"]
			],
			'ajax': {
				'url': BASE_URL + '/admin/ajax_client_details'
			}
		});
	}

	$(document).on('click','.EmailUser',function(e){
		$('.userId').val($(this).attr('data-id'));
		$.ajax({
			type:'GET',
			url:BASE_URL +'/admin/get_user_email',
			data:{user_id:$(this).attr('data-id')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 1){
					$('.userEmail').val(response['email']);
					$('.emailSubject').val('');
					$('.emailMsg').val('');
					 CKEDITOR.instances.editor1.setData('');
				}else{
					$('.errorMsg').html('');
					$('.errorMsg').html('Error getting User email.');
				}
			}
		});
	});

	$(document).on('click','#sendEmailMessage',function(e){
		var email = $('.userEmail').val();
		var subject = $('.emailSubject').val();
		var user_id = $('.userId').val();
		var msg = CKEDITOR.instances.editor1.getData();
		
		
		
		if(subject == ''){
			document.getElementById('emailSubject_error').innerHTML = '';
			document.getElementById('emailSubject_error').innerHTML = 'Subject is required.';
		}
		if(msg == ''){
			document.getElementById('emailMsg_error').innerHTML = '';
			document.getElementById('emailMsg_error').innerHTML = 'Message is required.';
		}
		

		if(msg !='' && subject !=''){
			$.ajax({
				type:'POST',
				url:BASE_URL +'/admin/send_email_message',
				dataType:'json',
				data:{user_id,email,subject,msg,_token:$('meta[name="csrf-token"]').attr('content')},
				success:function(response){
					//console.log(response);
					if(response['status'] == 1){
						Command: toastr["success"](response['message']);
						return false;
					}else if(response['status'] == 2){
						Command: toastr["error"](response['message']);
						return false;
					}else if(response['status'] == 0){
						Command: toastr["error"](response['message']);
						return false;
					}
				}
			})
		}
	});


$(document).on('click','.messageUser',function(e){
	$('.userMsgId').val($(this).attr('data-id'));
	$('#theForm')[0].reset();
	$.ajax({
		type:'GET',
		data:{user_id:$(this).attr('data-id')},
		url:BASE_URL + '/admin/get_user_message',
		dataType:'json',
		success:function(response){
			if(response['status'] == 1){
				$('.msgText').val(response['msg']);
				$('.banner').val(response['banner']);
			}else if(response['status'] == 0){
				document.getElementById('emailMsgtext_error').innerHTML = '';
				document.getElementById('emailMsgtext_error').innerHTML = 'Error getting message.';
			}
		}
	});

});

$(document).on('click','#sendMessage',function(e){
	e.preventDefault();
	var user_id = $('.userMsgId').val();
	var msg = $('.msgText').val();
	var banner =  $("input[name='banner']:checked").val();
	 
	
	if(msg == ''){
		document.getElementById('emailMsgtext_error').innerHTML = '';
		document.getElementById('emailMsgtext_error').innerHTML = 'Message is required.';
	}
	if(banner == undefined){
		document.getElementById('banner_Error').innerHTML = '';
		document.getElementById('banner_Error').innerHTML = 'Select one banner type';
	}

	if(msg !=''){
		$.ajax({
			type:'POST',
			data:{user_id,msg,banner,_token:$('meta[name="csrf-token"]').attr('content')},
			url:BASE_URL +'/admin/send_message',
			dataType:'json',
			success:function(response){
				if(response['status'] == 1){
					Command: toastr["success"](response['message']);
					return false;
				}else if(response['status'] == 0){
					Command: toastr["error"](response['message']);
					return false;
				}
			}
		});

	}
		
});
 
$(document).on('click','.loginAsClient',function(e){

$.ajax({
	type:'GET',
	dataType:'json',
	data:{user_id:$(this).attr('data-id')},
	url:BASE_URL +'/admin/login_as_client',
	success:function(res){
		if(res['status'] == 1){
			window.location.href = res['link'];
		}else{
			Command: toastr["error"]('Error!! Please try again.');
			return false;
		}
		
	}
})
});
                   
</script>