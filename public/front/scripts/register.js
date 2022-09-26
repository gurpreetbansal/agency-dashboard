var typingTime;
var typingTime1;
$(document).on('keyup','.regEmail', function (e) {
	e.preventDefault();
	clearTimeout(typingTime);
	clearTimeout(typingTime1);
	var email = $(this).val();
	typingTime = setTimeout(ValidateEmail(email), 1000);
	typingTime = setTimeout(check_email_exists(email), 1000);

});
 

$(document).on('keydown', '.regEmail',function () {
	clearTimeout(typingTime);
	clearTimeout(typingTime1);
});

 function ValidateEmail(email) {
    var lblError = document.getElementById("lblError");
    lblError.innerHTML = "";
    // var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    var expr = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!expr.test(email)) {
        $('#lblError.errorStyle').css('display','block');
        lblError.innerHTML = "This email address is already in use.";
        $('.regEmail').addClass('error');
        return;
    }else{
    	$('#lblError.errorStyle').css('display','none');
        lblError.innerHTML = "";
        $('.regEmail').removeClass('error');
    }
}

function check_email_exists(email){
	$.ajax({
		url: 'check_email_exists',
		type: 'GET',
		data: {email},
		success: function (response) {
			if (response.trim() == 'taken') {
				$('#lblError.errorStyle').css('display','block');
				var lblError = document.getElementById("lblError");
				lblError.innerHTML = "";
				lblError.innerHTML = "This email address is already in use.";
				$('#email').addClass('error');
				return false;
			}else{
				$('#email').removeClass('error');				
			}
		}
	});
}

$(document).on('keyup', '#company',function () {
	var company = $(this).val();
	$.ajax({
		url: 'check_company_exists',
		type: 'GET',
		data: {company},
		success: function (response) {
			if (response.trim() == 'taken') {
				$('#lblErrorCompany.errorStyle').css('display','block');
				var lblError = document.getElementById("lblErrorCompany");
				lblError.innerHTML = "";
				lblError.innerHTML = "Company Name already taken";
				$('#company').addClass('error');
			}
			else{
				$('#lblErrorCompany.errorStyle').css('display','none');
				var lblError = document.getElementById("lblErrorCompany");
				lblError.innerHTML = "";
				$('#company').removeClass('error');
			}
		}
	});
});


$(document).on('keyup', '#company_name',function () {
	var aa = $(this).val();
	var company_name = aa.split(' ').join('');
	$.ajax({
		url: 'check_company_name_exists',
		type: 'GET',
		data: {company_name},
		success: function (response) {
			if (response.trim() == 'taken') {
				$('#lblErrorCompanyName.errorStyle').css('display','block');
				var lblError = document.getElementById("lblErrorCompanyName");
				lblError.innerHTML = "";
				lblError.innerHTML = "Vanity Url already taken";
				$('#company_name').addClass('error');
			}
			else{
				$('#lblErrorCompanyName.errorStyle').css('display','none');
				var lblError = document.getElementById("lblErrorCompanyName");
				lblError.innerHTML = "";
				$('#company_name').removeClass('error');
			}
		}
	});
});

//coupon code starts
var coupontypingTime;
$(document).on('keyup','#coupon',function(e){
	e.preventDefault();
	clearTimeout(coupontypingTime);
	coupontypingTime = setTimeout(check_coupon_code($(this).val()), 1000);
});

$(document).on('keydown', '#coupon',function () {
	clearTimeout(coupontypingTime);
});

function check_coupon_code(code){
	$.ajax({
		url: 'check_coupon_code',
		type: 'GET',
		data: {code},
		success: function (response) {
			if (response.trim() == 'not_exists') {
				$('#lblErrorCoupon.errorStyle').css('display','block');
				var lblError = document.getElementById("lblErrorCoupon");
				lblError.innerHTML = "";
				lblError.innerHTML = "Coupon code does not exists.";
			}
			else if(response.trim() == 'empty'){
				$('#lblErrorCoupon.errorStyle').css('display','none');
				var lblError = document.getElementById("lblErrorCoupon");
				lblError.innerHTML = "";
				
			}else{
				$('#lblErrorCoupon.errorStyle').css('display','none');
				var lblError = document.getElementById("lblErrorCoupon");
				lblError.innerHTML = "";
			}
		}
	});
}
//coupon code ends

$(document).on('click',"#reg_btn" ,function () {
	$(this).attr('disabled','disabled');
	var company = $('#company').val();
	var company_name = $('#company_name').val();
	var email = $('#email').val();
	var password = $('#password').val();
	var package_id = $('.package_id').val();
	var state_value = $('.state_value').val();
	var coupon = $('#coupon').val();
	

	if (company_name == '' || email == '' || password == '' || company == '') {
		$('#reg_btn').removeAttr('disabled');
		if(company_name == ''){
			$('#company_name').addClass('error');
		}else{
			$('#company_name').removeClass('error');			
		}

		if(email == ''){
			$('#email').addClass('error');
		}else{
			$('#email').removeClass('error');			
		}

		if(password == ''){
			$('#password').addClass('error');
		}else{
			$('#password').removeClass('error');			
		}

		if(company == ''){
			$('#company').addClass('error');
		}else{
			$('#company').removeClass('error');			
		}
		return false;
	} else {
		if($('#lblErrorCoupon').length > 0){
			var validationFields = (document.getElementById('lblError').innerHTML == '' && document.getElementById('lblError1').innerHTML == '' && document.getElementById('lblErrorCompany').innerHTML == '' && document.getElementById('lblErrorCompanyName').innerHTML == '' && document.getElementById('lblErrorCoupon').innerHTML == '');
		}else{
			var validationFields = (document.getElementById('lblError').innerHTML == '' && document.getElementById('lblError1').innerHTML == '' && document.getElementById('lblErrorCompany').innerHTML == '' && document.getElementById('lblErrorCompanyName').innerHTML == '');
		}

		if (validationFields) {
			$.ajax({
				url: 'doRegister',
				type: 'post',
				data: {
					'email': email,
					'password': password,
					'company': company,
					'company_name': company_name,
					'package_id': package_id,
					'state_value': state_value,
					'coupon': coupon,
					_token:$('meta[name="csrf-token"]').attr('content')
				},
				dataType:'json',
				success: function (response) {
					window.location.href = 'subscription?reg_id='+response['string'];
				}
			});
		}else{
			$('#reg_btn').removeAttr('disabled');
		}
	}
});

$("#toggle_pwd").mousedown(function(){
	    $('#password').attr("type", "text");
	    $(this).addClass("fa-eye");
	    $(this).removeClass("fa-eye-slash");
	}).mouseup(function(){
	    $('#password').attr("type", "password");
	    $(this).removeClass("fa-eye");
	    $(this).addClass("fa-eye-slash");
	}).mouseout(function(){
	    $('#password').attr("type", "password");
	    $(this).removeClass("fa-eye");
	    $(this).addClass("fa-eye-slash");
	});