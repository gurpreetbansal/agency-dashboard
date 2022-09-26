$(document).on('click','#saveAnnouncement',function(e){
	e.preventDefault();

	var announcement_text = $('#announcement').val();
	var announcement_type =  $("input[name='announcement_type']:checked").val();

	if(announcement_text == ''){
		document.getElementById('announcement_error').innerHTML = '';
		document.getElementById('announcement_error').innerHTML = 'Text is required.';
	}
	if(announcement_type == undefined){
		document.getElementById('announcement_type_error').innerHTML = '';
		document.getElementById('announcement_type_error').innerHTML = 'Select one announcement type';
	}

	if(announcement_text != '' || announcement_type != undefined){
		$.ajax({
			type:'POST',
			data:{announcement_text,announcement_type,_token:$('meta[name="csrf-token"]').attr('content')},
			url:BASE_URL +'/admin/ajax_save_announcement',
			dataType:'json',
			success:function(response){
				if(response['status'] == 1){
					Command: toastr["success"](response['msg']);
					$('#announcementForm')[0].reset();
					$("#announcements-section").load(location.href + " #announcements-section");
					return false;
				}else if(response['status'] == 0){
					Command: toastr["error"](response['msg']);
					return false;
				}
			}
		});
	}
});

$(document).on('click','.closeAnnouncement',function(e){
	 if(!confirm("Are you sure you want to delete this announcement?")){
        return false;
    }
	$.ajax({
			type:'POST',
			data:{id:$(this).attr('data-id'),_token:$('meta[name="csrf-token"]').attr('content')},
			url:BASE_URL +'/admin/ajax_delete_announcement',
			dataType:'json',
			success:function(response){
				if(response['status'] == 1){
					Command: toastr["success"](response['msg']);
					$("#announcements-section").load(location.href + " #announcements-section");
					return false;
				}else if(response['status'] == 0){
					Command: toastr["error"](response['msg']);
					return false;
				}
			}
		});
});


$(document).on('click','#globalsettings',function(e){
	$.ajax({
		type:'POST',
		url:BASE_URL +'/admin/ajax_save_global_settings',
		data:$('#globalsettingsForm').serialize(),
		dataType:'json',
		success:function(response){
			console.log(response);
			if(response['status'] ==1){
				Command: toastr["success"](response['message']);
				return false;
			}else{
				Command: toastr["error"](response['message']);
				return false;
			}
		}
	});
});