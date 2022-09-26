var BASE_URL = $('.base_url').val();


$('select').selectpicker({
	noneSelectedText:'Select Projects'
});
$("#AddNewUserBtn").on("click", function(){
	$('#shared_access_form')[0].reset();
  $("#AddNewUserBox").toggleClass("open");
  $('#AddNewUserBox').css('display','block');
  if($("#AddNewUserBox").hasClass("open")){
  	$('.shared-access-list').css('display','none');
  	$(this).html('<span uk-icon="icon: arrow-left" class="uk-icon"></span> Back to List');
  }else{
  	$('.shared-access-list').css('display','block');
  	$(this).html('<span uk-icon="icon: user" class="uk-icon"></span><span uk-icon="icon: plus" class="uk-icon" style="width: 10px;height: 10px;margin: 0;position: relative;top: -4px;left: -2px;"></span> Add User');
  }

  if($('#editExistingUserBox').hasClass('open')){
	$("#editExistingUserBox").removeClass("open");
  }
  	$('#editExistingUserBox').css('display','none');
});

$(document).on("click", "#EditExistingUserBtn", function(){
  var user_id = $(this).attr('data-id');
  $("#editExistingUserBox").load('/render_shared_user/' + user_id, function(responseTxt, statusTxt, xhr){
  	$(".form-group").each(function(){
    var Label = $(this).find("label");
    if(Label.length >= 1){
      Label.parent().addClass("hasLabel");
    }
  });
  	$("#editExistingUserBox").toggleClass("open");
  	$('#editExistingUserBox').css('display','block');

  	if($("#editExistingUserBox").hasClass("open")){
  		$('.shared-access-list').css('display','none');
	 }else{
	 	$('.shared-access-list').css('display','block');
	 }

  	$('#AddNewUserBox').css('display','none');
  	if($('#AddNewUserBox').hasClass('open')){
		$("#AddNewUserBox").removeClass("open");
	}
  	if(statusTxt == "success")
		$(".selectpicker").selectpicker("refresh");
	});
});

$(".shared-access-list table ul.uk-dropdown-nav").mCustomScrollbar({
  axis: "y"
});


$(document).on("keyup change", '#shared_access_form input', function(e) {
	
	var name = $('.shared_access_new_user_name').val();
	var email = $('.shared_access_new_user_email').val();
	var password = $('.shared_access_new_user_password').val();
	var projects = $('#shared_selected_id').val();

	if (name == '') {
		$('#shared_name_error').html('<p>Field is required</p>');
		$('#shared_name_error').show();
	}else{
		$('#shared_name_error').hide();
	}

	if (email == '') {
		$('#shared_email_error').html('<p>Field is required</p>');
		$('#shared_email_error').show();
	}
	else{
		$('#shared_email_error').hide();
	}

	if (password == '') {
		$('#shared_password_error').html('<p>Field is required</p>');
		$('#shared_password_error').show();
	}else{
		$('#shared_password_error').hide();
	}

	// if (projects == '') {
	// 	$('#shared_projects_error').html('<p>Field is required</p>');
	// 	$('#shared_projects_error').show();
	// }else{
	// 	$('#shared_projects_error').hide();
	// }

	$('#create_new_user_access').removeAttr('disabled','disabled');
});

$(document).on('keyup','.shared_access_new_user_password',function(e){
	var string = $(this).val();
	if(string.length < 6){
		$('#shared_password_error').html('<p>The password must be at least 6 characters.</p>');
		$('#shared_password_error').show();
	}else{
		$('#shared_password_error').hide();
	}
	
});


function ValidateEmail(email) {
	// var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	var expr = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	if (!expr.test(email)) {
		return 'error';
	}else{
		return 'success';
	}
}

$(document).on('change','.shared_access_new_user_projects',function(){
	var options  = $('.shared_access_new_user_projects option:selected');
	var selected = [];
	$(options).each(function(){
		selected.push($(this).val()); 
	});
	$('#shared_selected_id').val(selected);

	if ($('#shared_selected_id').val() == '') {
		$('#shared_projects_error').html('<p>Field is required</p>');
		$('#shared_projects_error').show();
	}else{
		$('#shared_projects_error').hide();
	}
});

 $(document).on('keyup blur','.shared_access_new_user_email', function () {
  if(ValidateEmail($(this).val()) == 'error'){
  	$('#shared_email_error').html('<p>Invalid email</p>');
  	$('#shared_email_error').show();
  }else{
  	$('#shared_email_error').hide();
  	check_email($(this).val());
  	return false;
  }
});

 function check_email(email){
 	$.ajax({
  		url:BASE_URL + '/ajax_check_shared_email_exists',
  		data:{email},
  		type:'GET',
  		dataType:'json',
  		success:function(response){
  			if(response['status'] == 1){
  				$('#shared_email_error').html('<p>Email already exists</p>');
  				$('#shared_email_error').show();
  				return;
  			}
  			if(response['status'] == 0){
  				$('#shared_email_error').hide();
  			}
  		}
  	});
 }

$("#shared_access_form").on("submit", function(event){
	event.preventDefault();
	var name = $('.shared_access_new_user_name').val();
	var email = $('.shared_access_new_user_email').val();
	var password = $('.shared_access_new_user_password').val();
	var projects = $('#shared_selected_id').val();

	if (name != '' && email !== '' && password != '' && projects !='' && !$('#profile_image_error').attr('style')) {
		$('#create_new_user_access').attr("disabled", "disabled");
		$('.add-sharedAccess-progress-loader').css('display','block');
		var data = new FormData(this);
		jQuery.each($('input[name^="profile_image"]')[0].files, function(i, file) {
			data.append(i, file);
		});

		$.ajax({
			type: "POST",
			url: BASE_URL + '/ajax_add_user_shared_access',
			cache: false,
		    contentType: false,
		    processData: false,
		    data: data,
		    dataType: 'json',
			success: function(result) {
				if(result['status'] == 0){

					if(result['message']['password']){
						$('#shared_password_error').html(result['message']['password']);
						$('#shared_password_error').show();
					}
					if(result['message']['email']){
						$('#shared_email_error').html(result['message']['email']);
						$('#shared_email_error').show();
					}
				}
				if(result['status'] == 1){
					Command: toastr["success"](result['message']);
					setTimeout(function(){ location.reload(); }, 1000); 
				}

				if(result['status'] == 2){
					Command: toastr["error"](result['message']);
				}

				$('#create_new_user_access').removeAttr("disabled", "disabled");
				$('.add-sharedAccess-progress-loader').addClass('complete');
                setTimeout(function(){
                  $('.add-sharedAccess-progress-loader').css('display','none');
                  $('.add-sharedAccess-progress-loader').removeClass('complete');
                }, 500);
			}
		});
	}
});


$(document).on('click','.delete_shared_access',function(e){
	e.preventDefault();
	if(!confirm("Are you sure you want to delete this access ?")){
	    return false;
	}

	var user_id = $(this).attr('data-id');
	if(user_id != '' || user_id != 'undefined'){
		$.ajax({
			type:'POST',
			data:{user_id,_token: $('meta[name="csrf-token"]').attr('content')},
			url: BASE_URL+ "/ajax_remove_shared_access",
			dataType:'json',
			success:function(response){
				if(response['status'] == 1){
					Command: toastr["success"](response['message']);
					setTimeout(function(){ location.reload(); }, 1000);
				}

				if(response['status'] == 0){
					Command: toastr["error"](response['message']);
				}
			}
		});
	}
});


//edit user
$(document).on("keyup change", '#shared_access_form_edit input,#shared_access_form_edit select', function(e) {
	
	var name = $('.shared_access_name').val();
	var email = $('.shared_access_email').val();
	var projects = $('#shared_edit_selected_id').val();

	if (name == '') {
		$('#shared_access_name_error').html('<p>Field is required</p>');
		$('#shared_access_name_error').show();
	}else{
		$('#shared_access_name_error').hide();
	}

	if (email == '') {
		$('#shared_access_email_error').html('<p>Field is required</p>');
		$('#shared_access_email_error').show();
	}
	else{
		$('#shared_access_email_error').hide();
	}


	if (projects == '') {
		$('#shared_access_projects_error').html('<p>Field is required</p>');
		$('#shared_access_projects_error').show();
	}else{
		$('#shared_access_projects_error').hide();
	}

	$('#edit_user_access').removeAttr('disabled','disabled');
});



$(document).on('change','.shared_access_edit_projects',function(){
	var options  = $('.shared_access_edit_projects option:selected');
	var selected = [];
	$(options).each(function(){
		selected.push($(this).val()); 
	});
	$('#shared_edit_selected_id').val(selected);
});

 $(document).on('keyup blur','.shared_access_email', function () {
  if(ValidateEmail($(this).val()) == 'error'){
  	$('#shared_access_email_error').html('<p>Invalid email</p>');
  	$('#shared_access_email_error').show();
  }else{
  	$('#shared_access_email_error').hide();
  	check_existing_email($(this).val(),$('.shared_user_id').val());
  	return false;
  }
});


function check_existing_email(email,user_id){
 	$.ajax({
  		url:BASE_URL + '/ajax_check_shared_email',
  		data:{email,user_id},
  		type:'GET',
  		dataType:'json',
  		success:function(response){
  			if(response['status'] == 1){
  				$('#shared_access_email_error').html('<p>Email already exists</p>');
  				$('#shared_access_email_error').show();
  				return;
  			}
  			if(response['status'] == 0){
  				$('#shared_access_email_error').hide();
  			}
  		}
  	});
}

$(document).on("submit", "#shared_access_form_edit",function(e){
    e.preventDefault();
    

	var name = $('.shared_access_name').val();
	var email = $('.shared_access_email').val();
	var projects = $('#shared_edit_selected_id').val();

	if (name != '' && email !== '' && projects !='' && !$('#custom-div-updateShared').attr('style')) {
		$('#edit_user_access').attr("disabled", "disabled");
		$('.update-sharedAccess-progress-loader').css('display','block');
		if($('#shared_access_email_error').css('display') == 'none'){
			var data = new FormData(this);
			jQuery.each($('input[name^="profile_image"]')[0].files, function(i, file) {
				data.append(i, file);
			});

			$.ajax({
				type: "POST",
				url: BASE_URL + '/ajax_update_existing_shared_user',
				cache: false,
			    contentType: false,
			    processData: false,
			    data: data,
			    dataType: 'json',
				success: function(result) {
					if(result['status'] == 0){
						if(result['message']['profile_image']){
							$('#shared_access_image_error').html(result['message']['profile_image']);
							$('#shared_access_image_error').parent().css('display','block');
						}

						if(result['message']['email']){
							$('#shared_access_email_error').html(result['message']['email']);
							$('#shared_access_email_error').show();
						}
					}

					if(result['status'] == 1){
						Command: toastr["success"](result['message']);
						setTimeout(function(){ location.reload(); }, 1000); 
					}


					$('.update-sharedAccess-progress-loader').addClass('complete');
	                setTimeout(function(){
	                  $('.update-sharedAccess-progress-loader').css('display','none');
	                  $('.update-sharedAccess-progress-loader').removeClass('complete');
	                }, 500);

				}
			});
		}
	}
});

/*May 12*/

$(document).on('change','#shared_newUser_accessType',function(e){
	$('#sharedAccessProject').trigger('click');
	$('#sharedAccessProject').css('display', 'block');
	$('body').addClass('popup-open');
	$.ajax({
		type:'GET',
		data:{role_id:$(this).val()},
		dataType:'json',
		url:BASE_URL+'/ajax_get_role_based_projects',
		success:function(response){
     		$('#shared_access_new_user_projects_append').html('');
     		$('#shared_access_new_user_projects_append').html(response);
     		$('.selectpicker').selectpicker('refresh');
		}
	});
});

$(document).on('change','#shared_existingUser_accessType',function(e){
	$('#sharedAccessProject').trigger('click');
	$('#sharedAccessProject').css('display', 'block');
	$('body').addClass('popup-open');
	$.ajax({
		type:'GET',
		data:{role_id:$(this).val(),selected_user_id:$('.shared_user_id').val()},
		dataType:'json',
		url:BASE_URL+'/ajax_get_role_based_projects_existing_user',
		success:function(response){
     		$('#shared_access_edit_projects_append').html('');
     		$('#shared_access_edit_projects_append').html(response);
     		$('.selectpicker').selectpicker('refresh');
		}
	});
});


$(document).on('click','#add-user-cancel',function(){
	$('#AddNewUserBox').removeClass('open');
	$('#AddNewUserBox').css('display','none');
	$('.shared-access-list').css('display','block');
});

$(document).on('click','#edit-user-cancel',function(){
	$('#editExistingUserBox').removeClass('open');
	$('#editExistingUserBox').css('display','none');
	$('.shared-access-list').css('display','block');

});


$(document).on('change','#update_shared_access_image',function(){
    var reader = new FileReader();
    if(this.files.length == 1){
        if(this.files[0].type.match('image.*')){
            reader.onload = (e) => { 
                $('#shared_access_update_preview').attr('src', e.target.result); 
            };
            $('#custom-div-updateShared').addClass('selected');
            reader.readAsDataURL(this.files[0]); 
            $('#custom-div-updateShared').removeAttr("style");
            $('#shared_access_image_error').parent().css('display','none');
            document.getElementById('shared_access_image_error').innerHTML = '';
        }else{
            $('#custom-div-updateShared').removeClass('selected');
            $('#custom-div-updateShared').css('border-color','red');
            $('#shared_access_update_preview').removeAttr('src'); 
            $('#shared_access_image_error').parent().css('display','block');
            document.getElementById('shared_access_image_error').innerHTML = 'The field must be a file of type: jpg, jpeg, png';
        }
    }
});

$(document).on('change','#shared_access_image',function(){
    var reader = new FileReader();
    if(this.files.length == 1){
        if(this.files[0].type.match('image.*')){
            reader.onload = (e) => { 
                $('#shared_access_add_preview').attr('src', e.target.result); 
            };
            $('#custom-div-addShared').addClass('selected');
            reader.readAsDataURL(this.files[0]); 
            $('#custom-div-addShared').removeAttr("style");
            $('#profile_image_error').parent().css('display','none');
            document.getElementById('profile_image_error').innerHTML = '';
        }else{
            $('#custom-div-addShared').removeClass('selected');
            $('#custom-div-addShared').css('border-color','red');
            $('#shared_access_add_preview').removeAttr('src'); 
            $('#profile_image_error').parent().css('display','block');
            document.getElementById('profile_image_error').innerHTML = 'The field must be a file of type: jpg, jpeg, png';
        }
    }
});