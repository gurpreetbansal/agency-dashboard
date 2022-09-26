var BASE_URL = $('.base_url').val();

$(document).on("submit","#adminProfileSettingForm", function(e){
    e.preventDefault();
    var  phoneNum = $('.profile_phone').val();

    if($('.profile_name').val() == ''){
        $('.profile_name').addClass('error');
        $('#profileErrorName').parent().css('display','block');
        document.getElementById('profileErrorName').innerHTML = 'Field is required.';
    }else{
        $('.profile_name').removeClass('error');
        $('#profileErrorName').parent().css('display','none');
        document.getElementById('profileErrorName').innerHTML = '';
    }


    if($('.profile_phone').val() == ''){
        $('.profile_phone').addClass('error');
        $('#ProfileErrorPhone').parent().css('display','block');
        document.getElementById('ProfileErrorPhone').innerHTML = 'Field is required.';
    }else if(phoneNum.match(/\d/g).length !== 10) { 
        $('.profile_phone').addClass('error');
        $('#ProfileErrorPhone').parent().css('display','block');
        document.getElementById('ProfileErrorPhone').innerHTML = 'Not a valid number.';
    } else{
        $('.profile_phone').removeClass('error');
        $('#ProfileErrorPhone').parent().css('display','none');
        document.getElementById('ProfileErrorPhone').innerHTML = '';
    }

    if($('.profile_address_line_1').val() == ''){
        $('.profile_address_line_1').addClass('error');
        $('#ProfileErrorAddress1').parent().css('display','block');
        document.getElementById('ProfileErrorAddress1').innerHTML = 'Field is required.';
    }else{
        $('.profile_address_line_1').removeClass('error');
        $('#ProfileErrorAddress1').parent().css('display','none');
        document.getElementById('ProfileErrorAddress1').innerHTML = '';
    }

    if($('.profile_city').val() == ''){
        $('.profile_city').addClass('error');
        $('#ProfileErrorCity').parent().css('display','block');
        document.getElementById('ProfileErrorCity').innerHTML = 'Field is required.';
    }else{
        $('.profile_city').removeClass('error');
        $('#ProfileErrorCity').parent().css('display','none');
        document.getElementById('ProfileErrorCity').innerHTML = '';
    }

    if($('.profile_zip').val() == ''){
        $('.profile_zip').addClass('error');
        $('#ProfileErrorZip').parent().css('display','block');
        document.getElementById('ProfileErrorZip').innerHTML = 'Field is required.';
    }else{
        $('.profile_zip').removeClass('error');
        $('#ProfileErrorZip').parent().css('display','none');
        document.getElementById('ProfileErrorZip').innerHTML = '';
    }

    if(($('.profile_name').val() !== '') && ($('.profile_phone').val() !== '') && ($('.profile_address_line_1').val() !== '') && ($('.profile_city').val() !== '') && ($('.profile_zip').val() !== '') && !$('#custom-profile-file-div').attr('style')){
        $('#save_admin_profile_settings').attr('disabled',true);
        $('.admin-profileSetting-progress-loader').css('display','block');
        var data = new FormData(this);

        $.ajax({
            type: 'POST',
            data: data,
            url:BASE_URL +'/admin/post_profile_settings',
            contentType: false, 
            processData: false, 
            cache: false, 
            dataType:'json',
            success: function (response){
                $('#save_admin_profile_settings').attr('disabled',false);
                if(response['status'] == 1){
                    $("#admin-header-detail-li").load(location.href+" #admin-header-detail-li>*","");
                    $("#admin-profile-image-section").load(location.href+" #admin-profile-image-section>*","");
                    $('.selectpicker').selectpicker('refresh');

                    Command: toastr["success"](response['message']);
                } 
                if(response['status'] == 2){
                    Command: toastr["error"](response['message']);
                }

                if(response['status'] == 3){
                    if(response['message']['phone']){
                        $('.profile_phone').addClass('error');
                        $('#ProfileErrorPhone').parent().css('display','block');
                        document.getElementById('ProfileErrorPhone').innerHTML = response['message']['phone'];
                    }

                    if(response['message']['profile_image']){
                        $('#admin-custom-profile-file-div').removeClass('selected');
                        $('#admin-custom-profile-file-div').css('border-color','red');
                        $('#admin_profile_image_preview_container').removeAttr('src'); 
                        $('#profile-logo-error').parent().css('display','block');
                        document.getElementById('profile-logo-error').innerHTML =response['message']['profile_image'];
                    }
                } 

                $('.admin-profileSetting-progress-loader').addClass('complete');
                setTimeout(function(){
                  $('.admin-profileSetting-progress-loader').css('display','none');
                  $('.admin-profileSetting-progress-loader').removeClass('complete');
              }, 500);
            }
        });
    }

});


$(document).on('change','#admin_profile_image',function(){
    var reader = new FileReader();
    if(this.files.length == 1){
        if(this.files[0].type.match('image.*')){
            reader.onload = (e) => { 
                $('#admin_profile_image_preview_container').attr('src', e.target.result); 
            };
            $('#admin-custom-profile-file-div').addClass('selected');
            reader.readAsDataURL(this.files[0]); 
            $('#admin-custom-profile-file-div').removeAttr("style");
            $('#profile-logo-error').parent().css('display','none');
            document.getElementById('profile-logo-error').innerHTML = '';
        }else{
            $('#admin-custom-profile-file-div').removeClass('selected');
            $('#admin-custom-profile-file-div').css('border-color','red');
            $('#admin_profile_image_preview_container').removeAttr('src'); 
            $('#profile-logo-error').parent().css('display','block');
            document.getElementById('profile-logo-error').innerHTML = 'The field must be a file of type: jpg, jpeg, png';
        }
    }
});


$(document).on('click','#admin-remove-profile-picture',function(e){
    e.preventDefault();
    if (!confirm("Are you sure you want to remove profile picture?")) {
        return false;
    }
    $('.admin-profileSetting-progress-loader').css('display','block');
    $.ajax({
        type:'POST',
        url:BASE_URL+'/admin/ajax_remove_profile_picture',
        data:{profile_image:$('#admin_profile_image_preview_container').attr('src'),user_id:$(this).attr('data-id'),_token:$('meta[name="csrf-token"]').attr('content')},
        dataType:'json',
        success:function(response){
            if(response['status'] == 1){
                $("#admin-header-detail-li").load(location.href+" #admin-header-detail-li>*","");
                $("#admin-profile-image-section").load(location.href+" #admin-profile-image-section>*","");
                $('.admin-profileSetting-progress-loader').addClass('complete');
                Command: toastr["success"](response['message']);
            }

            if(response['status'] == 0){
                Command: toastr["error"](response['message']);
            }

            setTimeout(function(){
              $('.admin-profileSetting-progress-loader').css('display','none');
              $('.admin-profileSetting-progress-loader').removeClass('complete');
          }, 500);
        }
    });
});




$(document).on('click','#admin_store_change_password',function(e){

    e.preventDefault();
    var current_password = $('.current_password').val();
    var new_password = $('.new_password').val();
    var confirm_password = $('.confirm_password').val();
    
    if(current_password == ''){
        $('.current_password').addClass('error');
        $('#ChangePasswordErrorCurrent').parent().css('display','block');
        document.getElementById('ChangePasswordErrorCurrent').innerHTML = 'Field is required.';
    }else{
        $('.current_password').removeClass('error');
        $('#ChangePasswordErrorCurrent').parent().css('display','none');
        document.getElementById('ChangePasswordErrorCurrent').innerHTML = '';
    }

    if(new_password == ''){
        $('.new_password').addClass('error');
        $('#ChangePasswordErrorNew').parent().css('display','block');
        document.getElementById('ChangePasswordErrorNew').innerHTML = 'Field is required.';
    }else{
        $('.new_password').removeClass('error');
        $('#ChangePasswordErrorNew').parent().css('display','none');
        document.getElementById('ChangePasswordErrorNew').innerHTML = '';
    }

    if(confirm_password == ''){
        $('.confirm_password').addClass('error');
        $('#ChangePasswordErrorConfirm').parent().css('display','block');
        document.getElementById('ChangePasswordErrorConfirm').innerHTML = 'Field is required.';
    }else{
        $('.confirm_password').removeClass('error');
        $('#ChangePasswordErrorConfirm').parent().css('display','none');
        document.getElementById('ChangePasswordErrorConfirm').innerHTML = '';
    }

    if(current_password != '' && new_password != '' && confirm_password != ''){

        $('#admin_store_change_password').attr('disabled',true);

        var form_data = $('#admin_form_change_password').serialize();

        $.ajax({
            type:'POST',
            url:BASE_URL + '/admin/update_change_password',
            data:form_data,
            dataType:'json',
            success:function(response){
                $('#admin_store_change_password').attr('disabled',false);
                if(response['status'] == 0){
                    if(response['message']['current_password']){
                        $('.current_password').addClass('error');
                        $('#ChangePasswordErrorCurrent').parent().css('display','block');
                        document.getElementById('ChangePasswordErrorCurrent').innerHTML = response['message']['current_password'];
                    }

                    if(response['message']['confirm_password']){
                        $('.new_password').addClass('error');
                        $('#ChangePasswordErrorNew').parent().css('display','block');
                        document.getElementById('ChangePasswordErrorNew').innerHTML = response['message']['confirm_password'];
                    }
                }

                if(response['status'] == 1){
                    $('#admin_form_change_password')[0].reset();
                    Command: toastr["success"](response['message']);
                    return false;
                }
            }
        });
    }

});