var BASE_URL = $('.base_url').val();

$(document).ready(function(){
	$('.breadcrumb-list').removeClass('ajax-loader');
    $('.setting-container').find('.uk-subnav').find('.ajax-loader').removeClass('ajax-loader');
    $('#project-general-div').find('.ajax-loader').removeClass('ajax-loader');
});

function onBeforeUnloadChnage(e) {
    // Cancel the event
    e.preventDefault();
    // Chrome requires returnValue to be set
    e.returnValue = '';
}

function onbeforeunload() {
    window.addEventListener('beforeunload', onBeforeUnloadChnage);
}

function deactivateReloader() {
    window.removeEventListener('beforeunload', onBeforeUnloadChnage);
}

$( function() {
	var added_date = $('.project_domain_register').val();
	$('.project_domain_register').datepicker({format: 'yyyy-mm-dd',endDate: new Date(),date :added_date,autoHide: true});
});

$(document).on('keyup change','.genralSettings',function(e){
	e.preventDefault();
	onbeforeunload();
});


$("#project_general_settings").on("submit", function(e){
	e.preventDefault();
	var domain_register = $('.project_domain_register').val();
	var domain_name = $('.project_domain_name').val();
	var domain_url = $('.project_domain_url').val();
	var client_name = $('.project_client_name').val();
	var summary = tinymce.get("summarydata").getContent();
	
	if(summary == ''){
		$('#summary_error').html('Summary field is required.');
		$('#summary_error').parent().css('display','block');
		$('#summary_error').parent().addClass('error')
	}else{
		$('#summary_error').html('');
		$('#summary_error').parent().css('display','none');
		$('#summary_error').parent().removeClass('error')
	}

	if(domain_register == '' || domain_register == null){
		$('.project_domain_register').addClass('error');
		$('#setting_project_date_error').parent().css('display','block');
		document.getElementById('setting_project_date_error').innerHTML = 'Field is required';
	}else{
		$('.project_domain_register').removeClass('error');
		$('#setting_project_date_error').parent().css('display','none');
		document.getElementById('setting_project_date_error').innerHTML = '';
	}

	if(domain_name == ''){
		$('.project_domain_name').addClass('error');
		$('#setting_project_name_error').parent().css('display','block');
		document.getElementById('setting_project_name_error').innerHTML = 'Field is required';
	}else{
		$('.project_domain_name').removeClass('error');
		$('#setting_project_name_error').parent().css('display','none');
		document.getElementById('setting_project_name_error').innerHTML = '';
	}

	if(domain_url == ''){
		$('.project_domain_url').addClass('error');
		$('#setting_project_url_error').parent().css('display','block');
		document.getElementById('setting_project_url_error').innerHTML = 'Field is required';
	}else{
		$('.project_domain_url').removeClass('error');
		$('#setting_project_url_error').parent().css('display','none');
		document.getElementById('setting_project_url_error').innerHTML = '';
	}

	if(client_name == ''){
		$('.project_client_name').addClass('error');
		$('#setting_client_name_error').parent().css('display','block');
		document.getElementById('setting_client_name_error').innerHTML = 'Field is required';
	}else{
		$('.project_client_name').removeClass('error');
		$('#setting_client_name_error').parent().css('display','none');
		document.getElementById('setting_client_name_error').innerHTML = '';
	}

	

	if(domain_register != '' && domain_register !=null && domain_name != '' && domain_url != '' && client_name != ''){
		
		if(!$('.project_domain_register').hasClass('error') && !$('.project_domain_name').hasClass('error') && !$('.project_domain_url').hasClass('error') && !$('.project_client_name').hasClass('error') && !$('#custom-file-div').attr('style') && !$('#summary_error').parent().hasClass('error')){
			$('#update_project_general_settings').attr('disabled','disabled');
			$('.projectGeneralSettings-progress-loader').css('display','block');
			var data = new FormData(this);
			$.ajax({
				url:BASE_URL + '/ajax_store_project_general_settings',
				type:'POST',
				cache: false,
				contentType: false,
				processData: false,
				data: data,
				dataType:'json',
				success:function(response){
					deactivateReloader();
					$('#update_project_general_settings').removeAttr('disabled','disabled');

					if(response['status'] == 0) {
						Command: toastr["error"]('Please try again getting error');
					}

					if (response['status'] == 1) {
						$("#general-project-logo-div").load(location.href+" #general-project-logo-div>*","");
						$(" #project-logo-div").load( " #projectLogo-img" );
						$( "#client_display_name_p" ).load(" #client_display_name" );
						$('#custom-file-div').addClass('selected');
						$("#integrationTab").load(location.href + " #project-integration-list");
						$('#setting_dashboard_error').parent().css('display','none');
						document.getElementById('setting_dashboard_error').innerHTML = '';
						Command: toastr["success"]('Your detail updated successfully');
					}

					if (response['status'] == 2) {
						if(response['message']['project_logo']){
							$('#custom-file-div').css('border-color','red');
							$('#project_image_preview_container').removeAttr('src'); 
							$('#project-logo-error').parent().css('display','block');
							document.getElementById('project-logo-error').innerHTML = response['message']['project_logo'];
						}

						if(response['message']['dashboard']){
							$('#setting_dashboard_error').parent().css('display','block');
							document.getElementById('setting_dashboard_error').innerHTML = response['message']['dashboard'];
						}
					}

					$('.projectGeneralSettings-progress-loader').addClass('complete');
					setTimeout(function(){
						$('.projectGeneralSettings-progress-loader').css('display','none');
						$('.projectGeneralSettings-progress-loader').removeClass('complete');
					}, 500);
				}
			});
		}
	}
});

$(document).on('change','#project_logo',function(){
	var reader = new FileReader();
	if(this.files.length == 1){
		if(this.files[0].type.match('image.*')){
			reader.onload = (e) => { 
				$('#project_image_preview_container').attr('src', e.target.result); 
			};
			$('#custom-file-div').addClass('selected');
			reader.readAsDataURL(this.files[0]); 
			$('#custom-file-div').removeAttr("style");
			$('#project-logo-error').parent().css('display','none');
			document.getElementById('project-logo-error').innerHTML = '';
		}else{
			$('#custom-file-div').removeClass('selected');
			$('#custom-file-div').css('border-color','red');
			$('#project_image_preview_container').removeAttr('src'); 
			$('#project-logo-error').parent().css('display','block');
			document.getElementById('project-logo-error').innerHTML = 'The field must be a file of type: jpg, jpeg, png';
		}
	}
});

$(document).on('click','#remove-project-logo',function(e){
	e.preventDefault();
	if (!confirm("Are you sure you want to remove project logo?")) {
		return false;
	}
	$('.projectGeneralSettings-progress-loader').css('display','block');
	$.ajax({
		type:'POST',
		url:BASE_URL+'/ajax_remove_project_logo',
		data:{project_logo:$('#project_image_preview_container').attr('src'),project_id:$(this).attr('data-id'),_token:$('meta[name="csrf-token"]').attr('content')},
		dataType:'json',
		success:function(response){
			if(response['status'] == 1){
				$("#general-project-logo-div").load(location.href+" #general-project-logo-div>*","");
				$(" #project-logo-div").load( " #projectLogo-img" );
				$( "#client_display_name_p" ).load(" #client_display_name" );
				$('.projectGeneralSettings-progress-loader').addClass('complete');
				Command: toastr["success"](response['message']);
			}

			if(response['status'] == 0){
				Command: toastr["error"](response['message']);
			}

			setTimeout(function(){
				$('.projectGeneralSettings-progress-loader').css('display','none');
				$('.projectGeneralSettings-progress-loader').removeClass('complete');
			}, 500);
		}
	});
});

function validateemail(email)  
{  
	var x=email;  
	var atposition=x.indexOf("@");  
	var dotposition=x.lastIndexOf(".");  
	if (atposition<1 || dotposition<atposition+2 || dotposition+2>=x.length){  
		return true;  
	} else{
		return false;
	} 
} 

//white label tab
$(document).on('keyup change','.whiteLabelSettings',function(e){
	e.preventDefault();
	onbeforeunload();
});

function isValidNumber(number) {
	return new libphonenumber.parsePhoneNumber(number).isValid();
}

$('#country_code').on("changed.bs.select", function() {
	var dataTypeAttribute = $('option:selected', this).attr("data-country-id");
	$('.country-code-val').val(dataTypeAttribute);
});

$(document).on("submit","#project_white_label", function(e){
	e.preventDefault();
	var company_name = $('.white_label_company_name').val();
	var client_name = $('.white_label_client_name').val();
	var email = $('.white_label_email').val();
	var phone = $('.white_label_phone').val();
	var country_code = $('.country-code-val').val();
	var final_phone = '+'+country_code+phone;

	if(company_name == ''){
		$('.white_label_company_name').addClass('error');
		$('#whiteLabel_companyName_error').parent().css('display','block');
		document.getElementById('whiteLabel_companyName_error').innerHTML = 'Field is required';
	}else{
		$('.white_label_company_name').removeClass('error');
		$('#whiteLabel_companyName_error').parent().css('display','none');
		document.getElementById('whiteLabel_companyName_error').innerHTML = '';
	}

	if(client_name == ''){
		$('.white_label_client_name').addClass('error');
		$('#whiteLabel_agencyOwner_error').parent().css('display','block');
		document.getElementById('whiteLabel_agencyOwner_error').innerHTML = 'Field is required';
	}else{
		$('.white_label_client_name').removeClass('error');
		$('#whiteLabel_agencyOwner_error').parent().css('display','none');
		document.getElementById('whiteLabel_agencyOwner_error').innerHTML = '';
	}

	if(phone == ''){
		$('.white_label_phone').addClass('error');
		$('#country_code').parent().addClass('error');
		$('#whiteLabel_phone_error').parent().css('display','block');
		document.getElementById('whiteLabel_phone_error').innerHTML = 'Field is required';
	}else if(final_phone !== ''){
		if(country_code == '' || country_code == undefined){
			$('.white_label_phone').addClass('error');
			$('#country_code').parent().addClass('error');
			$('#whiteLabel_phone_error').parent().css('display','block');
			document.getElementById('whiteLabel_phone_error').innerHTML = 'Country code is required';
		}else if(phone == ''){
			$('.white_label_phone').addClass('error');
			$('#country_code').parent().addClass('error');
			$('#whiteLabel_phone_error').parent().css('display','block');
			document.getElementById('whiteLabel_phone_error').innerHTML = 'Field is required';
		}else{
			if(isValidNumber(final_phone) == true){
				$('.white_label_phone').removeClass('error');
				$('#country_code').parent().removeClass('error');
				$('#whiteLabel_phone_error').parent().css('display','none');
				document.getElementById('whiteLabel_phone_error').innerHTML = '';
			}else if(isValidNumber(final_phone) == false){
				$('.white_label_phone').addClass('error');
				$('#country_code').parent().addClass('error');
				$('#whiteLabel_phone_error').parent().css('display','block');
				document.getElementById('whiteLabel_phone_error').innerHTML = 'Invalid number';
			}else{
				$('.white_label_phone').addClass('error');
				$('#country_code').parent().addClass('error');
				$('#whiteLabel_phone_error').parent().css('display','block');
				document.getElementById('whiteLabel_phone_error').innerHTML = 'Invalid number';
			}
		}
	}
	else {
		$('.white_label_phone').removeClass('error');
		$('#country_code').parent().removeClass('error');
		$('#whiteLabel_phone_error').parent().css('display','none');
		document.getElementById('whiteLabel_phone_error').innerHTML = '';
	}

	if(email == ''){
		$('.white_label_email').addClass('error');
		$('#whiteLabel_email_error').parent().css('display','block');
		document.getElementById('whiteLabel_email_error').innerHTML = 'Field is required';
	}else if (ValidateEmail(email)) {
		if(ValidateEmail(email) == 'error'){
			$('.white_label_email').addClass('error');
			$('#whiteLabel_email_error').parent().css('display','block');
			document.getElementById('whiteLabel_email_error').innerHTML = 'Not a valid email';
		}else if(ValidateEmail(email) == 'success'){
			$('.white_label_email').removeClass('error');
			$('#whiteLabel_email_error').parent().css('display','none');
			document.getElementById('whiteLabel_email_error').innerHTML = '';
		}
	}else{
		$('.white_label_email').removeClass('error');
		$('#whiteLabel_email_error').parent().css('display','none');
		document.getElementById('whiteLabel_email_error').innerHTML = '';

	}

	if(company_name != '' &&  client_name != '' && phone != '' && email!=''){
		if(!$('.white_label_company_name').hasClass('error') && !$('.white_label_client_name').hasClass('error') && !$('.white_label_phone').hasClass('error') && !$('.white_label_email').hasClass('error') && !$('#custom-file-agency-div').attr('style')){
			$('#update_project_white_label').attr('disabled','disabled');
			$('.projectWhiteLabelSettings-progress-loader').css('display','block');
			$.ajax({
				url:BASE_URL + '/ajax_store_project_white_label',
				type:'POST',
				cache: false,
				contentType: false,
				processData: false,
				data: new FormData(this),
				dataType:'json', 
				success:function(response){
					deactivateReloader();
					$('#update_project_white_label').removeAttr('disabled','disabled');

					if(response['status'] == 0) {
						Command: toastr["error"]('Please try again getting error');
					}

					if (response['status'] == 1) {
						$('#custom-file-agency-div').addClass('selected');
						$("#agency-contact-info_div").load(location.href + " #agency-contact-info");
						$("#whiteLabel-agency-logo-div").load(location.href+" #whiteLabel-agency-logo-div>*","");
						Command: toastr["success"]('Your detail updated successfully');
					}

					if (response['status'] == 2) {
						if(response['message']['white_label_logo']){
							$('#custom-file-agency-div').css('border-color','red');
							$('#agency_image_preview_container').removeAttr('src'); 
							$('#agency-logo-error').parent().css('display','block');
							document.getElementById('agency-logo-error').innerHTML = ['message']['white_label_logo'];
						}
					}

					if (response['status'] == 3) {
						$('#custom-file-agency-div').css('border-color','red');
						$('#agency-logo-error').parent().css('display','block');
						document.getElementById('agency-logo-error').innerHTML = response['message'];
	  				}

					$('.projectWhiteLabelSettings-progress-loader').addClass('complete');
					setTimeout(function(){
						$('.projectWhiteLabelSettings-progress-loader').css('display','none');
						$('.projectWhiteLabelSettings-progress-loader').removeClass('complete');
					}, 500);

				}
			});
		}
	}
});

$(document).on('change','#agency_logo',function(){
	var reader = new FileReader();
	if(this.files.length == 1){
		if(this.files[0].type.match('image.*')){
			reader.onload = (e) => { 
				$('#agency_image_preview_container').attr('src', e.target.result); 
			};
			reader.readAsDataURL(this.files[0]); 
			$('#custom-file-agency-div').addClass('selected');
			$('#custom-file-agency-div').removeAttr("style");
			$('#agency-logo-error').parent().css('display','none');
			document.getElementById('agency-logo-error').innerHTML = '';
		}else{
			$('#custom-file-agency-div').removeClass('selected');
			$('#custom-file-agency-div').css('border-color','red');
			$('#agency_image_preview_container').removeAttr('src'); 
			$('#agency-logo-error').parent().css('display','block');
			document.getElementById('agency-logo-error').innerHTML = 'The field must be a file of type: jpg, jpeg, png';
		}
	}
});

$(document).on('click','#remove-agency-logo',function(e){
	e.preventDefault();
	if (!confirm("Are you sure you want to remove agency logo?")) {
		return false;
	}
	$('.projectWhiteLabelSettings-progress-loader').css('display','block');
	$.ajax({
		type:'POST',
		url:BASE_URL+'/ajax_remove_project_agency_logo',
		data:{agency_logo:$('#agency_image_preview_container').attr('src'),project_id:$(this).attr('data-id'),_token:$('meta[name="csrf-token"]').attr('content')},
		dataType:'json',
		success:function(response){
			if(response['status'] == 1){
				$("#agency-contact-info_div").load(location.href + " #agency-contact-info");
				$("#whiteLabel-agency-logo-div").load(location.href+" #whiteLabel-agency-logo-div>*","");
				$('.projectWhiteLabelSettings-progress-loader').addClass('complete');
				Command: toastr["success"](response['message']);
			}

			if(response['status'] == 0){
				Command: toastr["error"](response['message']);
			}

			setTimeout(function(){
				$('.projectWhiteLabelSettings-progress-loader').css('display','none');
				$('.projectWhiteLabelSettings-progress-loader').removeClass('complete');
			}, 500);
		}
	});
});

//dashboard settings tab
$(document).on('change','.dashboard_toggle',function(){
	$('#update_project_dashboard_settings').removeAttr('disabled','disabled');
	onbeforeunload();
});

$(document).on('click','#update_project_dashboard_settings',function(e){
	e.preventDefault();
	$(this).attr('disabled','disabled');
	$.ajax({
		type: "POST",
		url: BASE_URL + "/ajax_update_dashboard_settings",
		data: $('form#project_dashboard_settings').serialize(),
		dataType: 'json',
		success: function(response) {
			$('#update_project_dashboard_settings').removeAttr('disabled','disabled');
			if(response['status'] == 1){
				$("#integrationTab").load(location.href + " #project-integration-list");
				Command: toastr["success"](response['message']);
			}
			if(response['status'] == 0){
				Command: toastr["error"](response['message']);
			}
		}
	});
});

//integration - analytics

$('#settings_analytics_existing_emails').on('show.bs.select', function (e, clickedIndex, isSelected, previousValue) {	
	$('#settings_analytics_existing_emails').removeClass('addAppend');
});

$(document).on('change','#settings_analytics_existing_emails',function(e){
	e.preventDefault();
	$('#settings_analytics_existing_emails').removeClass('addAppend');
	var email = $(this).val();
	var campaign_id = $('.campaign_id').val();

	fetch_last_updated(email,campaign_id,'google_analytics');
	$('.analytics_refresh_div').css('display','block');
	disableSelectPicker('#settings_analytics_accounts','.analytic-account-loader');

	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_analytics_accounts',
		data:{email,campaign_id},
		success:function(response){			
			enableSelectPicker('#settings_analytics_accounts','.analytic-account-loader');
			$('#settings_analytics_accounts').html(response);
			$('#settings_analytics_property').html('<option value="">Select Property</option>');
			$('#settings_analytics_view').html('<option value="">Select View</option>');
			$('#settings_analytics_accounts, #settings_analytics_property, #settings_analytics_view').selectpicker('refresh');
		}
	});
});

setInterval(function(){
	if($('#settings_analytics_existing_emails').hasClass('addAppend')){
		setTimeout(function(){
			getAnalyticsAccounts();
		}, 2000);
	}
}, 5000);

function getAnalyticsAccounts(){
	$.ajax({
		url:BASE_URL+'/ajax_google_analytics_accounts',
		type:'GET',
		success:function(response){
			$('#settings_analytics_existing_emails').html(response);
			$('#settings_analytics_existing_emails , #settings_analytics_accounts ,  #settings_analytics_property , #settings_analytics_view').selectpicker('refresh');
		}
	})
}


$(document).on('change','#settings_analytics_accounts',function(e){
	e.preventDefault();
	var account_id = $(this).val();
	var campaign_id = $('.campaign_id').val();
	disableSelectPicker('#settings_analytics_property','.analytic-property-loader');
	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_analytics_property',
		data:{account_id,campaign_id},
		success:function(response){
			enableSelectPicker('#settings_analytics_property','.analytic-property-loader');
			$('#settings_analytics_property').html(response);
			$('#settings_analytics_view').html('<option value="">Select View</option>');
			$('#settings_analytics_property, #settings_analytics_view').selectpicker('refresh');
		}
	});
});

$(document).on('change','#settings_analytics_property',function(e){
	e.preventDefault();
	var property_id = $(this).val();
	var campaign_id = $('.campaign_id').val();
	disableSelectPicker('#settings_analytics_view','.analytic-view-loader');
	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_analytics_view',
		data:{property_id,campaign_id},
		success:function(response){
			enableSelectPicker('#settings_analytics_view','.analytic-view-loader');
			$('#settings_analytics_view').html(response);
			$('#settings_analytics_view').selectpicker('refresh');
		}
	});
});

$(document).on('click','#save_settings_analytics_account',function(e){
	var campaign_id = $('.campaign_id').val();
	var email = $('#settings_analytics_existing_emails').val();
	var account = $('#settings_analytics_accounts').val();
	var property = $('#settings_analytics_property').val();
	var view = $('#settings_analytics_view').val();
	var e_com = $('.ecommerce_goals').prop('checked');
	

	if(email == ''){
		$('#settings_analytics_existing_emails').parent().addClass('error');
	}else{
		$('#settings_analytics_existing_emails').parent().removeClass('error');
	}
	if(account == ''){
		$('#settings_analytics_accounts').parent().addClass('error');
	}else{
		$('#settings_analytics_accounts').parent().removeClass('error');
	}
	if(property == ''){
		$('#settings_analytics_property').parent().addClass('error');
	}else{
		$('#settings_analytics_property').parent().removeClass('error');
	}
	if(view == ''){
		$('#settings_analytics_view').parent().addClass('error');
	}else{
		$('#settings_analytics_view').parent().removeClass('error');
	}
	

	if(email != '' && account !='' && property !='' && view !=''){
		$(this).attr('disabled','disabled');
		$('.analytics-progress-loader').css('display','block');
		$('.popup-inner').css('overflow','hidden');
		$.ajax({
			type:'POST',
			url:BASE_URL + '/ajax_update_analytics_data',
			data:{campaign_id,email,account,property,view,_token:$('meta[name="csrf-token"]').attr('content'),e_com},
			success:function(response){
				if(response['status'] == 'success'){
					$('.analytics-progress-loader').addClass('complete');
					Command: toastr["success"]('Google Analytics connected successfully');
					$("#project_setting_analytics_close").trigger("click");
					$("body").removeClass("popup-open");

					$('#project-integration-list').load(location.href + ' #project-integration-list');
				} 
				else if(response['status'] == 'google-error') {
					Command: toastr["error"](response['message']);
				}
				else if(response['status'] == 'error') {
					Command: toastr["error"](response['message']);
				}
				else {
					Command: toastr["error"]('Please try again getting error');
				}


				$('#save_settings_analytics_account').removeAttr('disabled','disabled');

				setTimeout(function(){	
					$('.analytics-progress-loader').css('display','none');	
					$('.analytics-progress-loader').removeClass('complete');	
					$('.popup-inner').css('overflow','auto');	
				}, 1000);
			}
		});
	}
});

//integration - search console
$('#settings_search_console_existing_emails').on('show.bs.select', function (e, clickedIndex, isSelected, previousValue) {		
	$('#settings_search_console_existing_emails').removeClass('addSearchAppend');	
});	
setInterval(function(){	
	if($('#settings_search_console_existing_emails').hasClass('addSearchAppend')){	
		getSearchConsoleAccounts();	
	}	
}, 3000);	
function getSearchConsoleAccounts(){	
	$.ajax({	
		url:BASE_URL+'/ajax_google_cnsole_accounts',	
		type:'GET',	
		success:function(response){	
			$('#settings_search_console_existing_emails').html(response);	
			$('#settings_search_console_existing_emails').selectpicker('refresh');
		}	
	})	
}	


$(document).on('change','#settings_search_console_existing_emails',function(e){
	e.preventDefault();
	$('#settings_search_console_existing_emails').removeClass('addSearchAppend');
	var email = $(this).val();
	var campaign_id = $('.campaign_id').val();
	fetch_last_updated(email,campaign_id,'search_console');
	$('.search_console_refresh_div').css('display','block');
	disableSelectPicker('#settings_search_console_urlaccounts','.sc-loader');

	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_get_console_urls',
		data:{email,campaign_id},
		success:function(response){
			enableSelectPicker('#settings_search_console_urlaccounts','.sc-loader');
			$('#settings_search_console_urlaccounts').html(response);
			$('#settings_search_console_urlaccounts').selectpicker('refresh');
		}
	});
});

//integration -adwords
$(document).on('click','#refresh_ppc_account',function(e){
	$(this).addClass('refresh-gif');
	$('#settings_save_adwords_account').attr('disabled','disabled');
	$('#settings_add_new_adwords_account').attr('disabled','disabled');

	$('.popup-inner').css('overflow','hidden');

	var email = $('#settings_adwords_existing_emails').val();
	var campaign_id = $('.campaign_id').val();
	$.ajax({
		type:'GET',
		// url:BASE_URL+'/ajax_refresh_ppc_acccount_list',
		url:BASE_URL+'/ppc/connect/update',
		data:{email,campaign_id},
		dataType:'json',
		success:function(response){
			$('.ppc-progress-loader').css('display','block');

			$('#show_ppc_last_time').parent().removeClass('error');
			$('#show_ppc_last_time').parent().removeClass('green');
			$('#show_ppc_last_time').parent().addClass('yellow');
			$('#show_ppc_last_time').parent().css('display','block');
			document.getElementById('show_ppc_last_time').innerHTML = '<p>Fetching list of accounts.</p>';

			if(response['status'] == 1){
				$.ajax({
					type:'GET',
					url:BASE_URL +'/ajax_get_adwords_accounts',
					data:{email:$('#settings_adwords_existing_emails').val(),campaign_id:$('.campaign_id').val()},
					success:function(response){
						$('#settings_adwords_accounts').html(response);
						$('#settings_adwords_accounts').selectpicker('refresh');
					}
				});


				$('.ppc-progress-loader').addClass('complete');
				
				$('#show_ppc_last_time').parent().removeClass('error');
				$('#show_ppc_last_time').parent().removeClass('yellow');
				$('#show_ppc_last_time').parent().addClass('green');
				$('#show_ppc_last_time').parent().css('display','block');
				document.getElementById('show_ppc_last_time').innerHTML = response['time'];
				
			}

			if(response['status'] == 0){
				
				$('#show_ppc_last_time').parent().removeClass('green');
				$('#show_ppc_last_time').parent().removeClass('yellow');
				$('#show_ppc_last_time').parent().addClass('error');
				$('#show_ppc_last_time').parent().css('display','block');
				document.getElementById('show_ppc_last_time').innerHTML = response['time'];				
			}

			if(response['status'] == 2){
				
				$('#show_ppc_last_time').parent().removeClass('green');
				$('#show_ppc_last_time').parent().removeClass('yellow');
				$('#show_ppc_last_time').parent().addClass('error');
				$('#show_ppc_last_time').parent().css('display','block');
				document.getElementById('show_ppc_last_time').innerHTML = response['time'];
			}

			setTimeout(function(){
				$('#refresh_ppc_account').removeClass('refresh-gif');
				$('.ppc-progress-loader').css('display','none');
				$('.ppc-progress-loader').removeClass('complete');
				$('.popup-inner').css('overflow','auto');
				$('#settings_save_adwords_account').removeAttr('disabled','disabled');
				$('#settings_add_new_adwords_account').removeAttr('disabled','disabled');
			}, 1000);
		}
	});
});

$('#settings_adwords_existing_emails').on('show.bs.select', function (e, clickedIndex, isSelected, previousValue) {	
	$('#settings_adwords_existing_emails').removeClass('addAdsAppend');
});

$(document).on('change','#settings_adwords_existing_emails',function(e){
	e.preventDefault();
	$('#settings_adwords_existing_emails').removeClass('addAdsAppend');
	var email = $(this).val();
	var campaign_id = $('.campaign_id').val();

	fetch_last_updated(email,campaign_id,'ppc');
	$('.ppc_refresh_div').css('display','block');
	disableSelectPicker('#settings_adwords_accounts','.adwords-loader');
	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_adwords_accounts',
		data:{email,campaign_id},
		success:function(response){
			enableSelectPicker('#settings_adwords_accounts','.adwords-loader');
			$('#settings_adwords_accounts').html(response);
			$('#settings_adwords_accounts').selectpicker('refresh');
		}
	});
});

//adwords integration
$(document).on('click','#settings_save_adwords_account',function(e){
	e.preventDefault();
	var email = $('#settings_adwords_existing_emails').val();
	var account = $('#settings_adwords_accounts').val();
	var campaign_id = $('.campaign_id').val();


	if(email == ''){
		$('#settings_adwords_existing_emails').parent().addClass('error');
	}else{
		$('#settings_adwords_existing_emails').parent().removeClass('error');
	}
	if(account == ''){
		$('#settings_adwords_accounts').parent().addClass('error');
	}else{
		$('#settings_adwords_accounts').parent().removeClass('error');
	}

	if(email != '' && account !=''){
		$('.ppc-progress-loader').css('display','block');
		$('.popup-inner').css('overflow','hidden');
		$(this).attr('disabled','disabled');
		$.ajax({
			type:'POST',
			url:BASE_URL + '/ppc/save/campaigns',
			data:{campaign_id,email,account,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if (response['status'] == 'success') {

					$("#integrationTab").load(location.href + " #project-integration-list");
					
					setTimeout(function(){
						$('.ppc-progress-loader').addClass('complete');
						$("#project_setting_adwords_close").trigger("click");
						$("body").removeClass("popup-open");
						document.getElementById("adwords_setting_popup").reset();
						Command: toastr["success"]('Adwords Account Connected successfully!');
					}, 1000);
		
				} else {
					$('#settings_save_adwords_account').removeAttr('disabled');
					Command: toastr["error"]('Please try again getting error');
				}

				setTimeout(function(){
					$('.ppc-progress-loader').css('display','none');
					$('.ppc-progress-loader').removeClass('complete');
					$('.popup-inner').css('overflow','auto');
				}, 1000);
			}
		});
	}
});

setInterval(function(){
	if($('#settings_adwords_existing_emails').hasClass('addAdsAppend')){
		setTimeout(function(){
			getAdwordsAccounts();
		}, 2000);
	}
}, 5000);


function getAdwordsAccounts(){
	$.ajax({
		url:BASE_URL+'/ajax_fetch_adwords_accounts',
		type:'GET',
		success:function(response){
			$('#settings_adwords_existing_emails').html(response);
			$('#settings_adwords_existing_emails').selectpicker('refresh');
		}
	})
}

$(document).on('click','.settings_analyticsAddBtn',function(e){
	e.preventDefault();
	var campaignId = $('.campaign_id').val();
	var currentRoute = $('.currentRoute').val();
	$('#settings_analytics_existing_emails').addClass('addAppend');
	var link = BASE_URL +'/connect_google_analytics?campaignId='+campaignId+'&provider=google&redirectPage='+currentRoute;
	connectPopup(link,"web","500","500");
});

$(document).on('click','.settings_searchConsoleAddBtn',function(e){
	e.preventDefault();
	var campaignId = $('.campaign_id').val();
	var currentRoute = $('.currentRoute').val();
	$('#settings_search_console_existing_emails').addClass('addSearchAppend');
	var link = BASE_URL +'/connect_search_console?campaignId='+campaignId+'&redirectPage='+currentRoute;
	connectPopup(link,"web","500","500");
});

$(document).on('click','.settings_AdwordsAddBtn',function(){
	var campaignId = $('.campaign_id').val();
	var currentRoute = $('.currentRoute').val();
	var link = BASE_URL +'/ppc/connect?campaignId='+campaignId+'&redirectPage='+currentRoute;
	connectPopup(link,"web","500","500");
});


function connectPopup(myURL, title, myWidth, myHeight) {
	var left = (screen.width - myWidth) / 2;
	var top = (screen.height - myHeight) / 4;
	window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
}

// summary settings
window.onload = function () {
	tinymce.init({
		selector: '#summarydata',
		height:500,
		setup: function (ed) {
			ed.on('keypress', function (e) { 
				if(CountCharacters() >= 500){
					e.preventDefault();
					return false;
				}
			});

			ed.on('keyup', function () { 
				var count = CountCharacters();
				$("#character_count").html("Characters: " + count);
			});

			
		}
	});
}
function CountCharacters() {
	var body = tinymce.get("summarydata").getBody();
	var content = tinymce.trim(body.innerText || body.textContent);
	return content.length;
};


$(document).on('click','#update_summary_settings',function(e){
	e.preventDefault();
	
	var summary = tinymce.get("summarydata").getContent();

	if ($('.summary_toggle').is(":checked"))
	{
		var summary_toggle =1;
	}else{
		var summary_toggle = 0;
	}
	
	if(summary == ''){
		$('#summary_error').html('Summary field is required.');
	}else{
		$('#summary_error').html('');
	}

	$(this).attr('disabled','disabled');
	if(summary !=''){
		$.ajax({
			type:'POST',
			url:BASE_URL +'/ajax_update_summary_data',
			data: {summary:summary,request_id:$('.request_id').val(),summary_toggle:summary_toggle,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				$('#update_summary_settings').removeAttr('disabled','disabled');
				if(response['status'] == 1){
					Command: toastr["success"](response['message']);
				}

				if(response['status'] == 0){
					Command: toastr["error"](response['message']);
				}
			}
		});
	}
});

$(document).on('click','#disconnectAnalytics',function(e){
	e.preventDefault();
	if($('.request_id').val() != ''){
		$.ajax({
			type:'POST',
			url:BASE_URL +'/ajax_disconnect_analaytics',
			data:{request_id:$('.request_id').val(),_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 'success')
				{
					$('#integrationTab').load(location.href + ' #project-integration-list');
				}else{
					Command: toastr["error"]('Error!! Please try again!');
				}
			}
		});
	}
});

$(document).on('click','#disconnectConsole',function(e){
	e.preventDefault();
	if($('.request_id').val() != ''){
		$.ajax({
			type:'POST',
			url:BASE_URL +'/ajax_disconnect_console',
			data:{request_id:$('.request_id').val(),_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 'success')
				{
					$('#integrationTab').load(location.href + ' #project-integration-list');
				}else{
					Command: toastr["error"]('Error!! Please try again!');
				}
			}
		});
	}
});

$(document).on('click','#disconnectAdwords',function(e){
	e.preventDefault();
	if($('.request_id').val() != ''){
		$.ajax({
			type:'POST',
			url:BASE_URL +'/ajax_disconnect_adwords',
			data:{request_id:$('.request_id').val(),_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 'success')
				{
					$('#integrationTab').load(location.href + ' #project-integration-list');
				}else{
					Command: toastr["error"]('Error!! Please try again!');
				}
			}
		});
	}
});


/* GMB Setting*/	
$(document).on('click','.settings_GmbAddBtn',function(){	
	var campaignId = $('.campaign_id').val();	
	var currentRoute = $('.currentRoute').val();	
	$('#settings_gmb_existing_emails').addClass('addgmbAppend');	
	var link = BASE_URL +'/gmb/connect?campaignId='+campaignId+'&redirectPage='+currentRoute;	
	// console.log(link);	
	connectPopup(link,"web","500","500");	
});	
$(document).on('click','#disconnectGMB',function(e){	
	e.preventDefault();	
	if($('.campaign_id').val() != ''){	
		$.ajax({	
			type:'POST',	
			url:BASE_URL +'/ajax_disconnect_gmb',	
			data:{request_id:$('.campaign_id').val(),_token:$('meta[name="csrf-token"]').attr('content')},	
			dataType:'json',	
			success:function(response){	
				if(response['status'] == 'success')	
				{	
					$('#integrationTab').load(location.href + ' #project-integration-list');	
					$('.default_gmb').show();	
					$('.gmb_connected').hide();	
				}else{	
					Command: toastr["error"]('Error!! Please try again!');	
				}	
			}	
		});	
	}	
});	
//integration -gmb	
$('#settings_gmb_existing_emails').on('show.bs.select', function (e, clickedIndex, isSelected, previousValue) {	
	$('#settings_gmb_existing_emails').removeClass('addgmbAppend');
});
$(document).on('change','#settings_gmb_existing_emails',function(e){	
	e.preventDefault();	
	$('#settings_gmb_existing_emails').find('.addgmbAppend').removeClass('addgmbAppend');
	var email = $(this).val();	
	var campaign_id = $('.campaign_id').val();	
	fetch_last_updated(email,campaign_id,'gmb');
	$('.gmb_refresh_div').css('display','block');
	disableSelectPicker('#settings_gmb_accounts','.gmb-loader');
	$.ajax({	
		type:'GET',	
		url:BASE_URL +'/ajax_get_gmb_accounts',	
		data:{email,campaign_id},	
		success:function(response){	
			enableSelectPicker('#settings_gmb_accounts','.gmb-loader');
			$('#settings_gmb_accounts').html(response);	
			$('#settings_gmb_accounts').selectpicker('refresh');	
		}	
	});	
});	
setInterval(function(){	
	if($('#settings_gmb_existing_emails').hasClass('addgmbAppend')){	
		setTimeout(function(){	
			getGmbAccounts();	
		}, 5000);	
	}	
}, 1000);	
function getGmbAccounts(){	
	$.ajax({	
		url:BASE_URL+'/ajax_gmb_accounts',	
		data:{user_id:$('.user_id').val()},	
		type:'GET',	
		success:function(response){	
			$('#settings_gmb_existing_emails').html(response);
			$('#settings_gmb_existing_emails').selectpicker('refresh');
		}	
	})	
}	
//adwords integration	
$(document).on('click','#settings_save_gmb_account',function(e){	
	e.preventDefault();	
	var email = $('#settings_gmb_existing_emails').val();	
	var account = $('#settings_gmb_accounts').val();	
	var campaign_id = $('.campaign_id').val();	
	
	
	if(email == ''){	
		$('#settings_gmb_existing_emails').parent().addClass('error');	
	}else{	
		$('#settings_gmb_existing_emails').parent().removeClass('error');	
	}	
	if(account == ''){	
		$('#settings_gmb_accounts').parent().addClass('error');	
	}else{	
		$('#settings_gmb_accounts').parent().removeClass('error');	
	}	
	if(email != '' && account !=''){	
		$(this).attr('disabled','disabled');	
		$('.gmb-progress-loader').css('display','block');
		$('.popup-inner').css('overflow','hidden');
		$.ajax({	
			type:'POST',	
			url:BASE_URL + '/ajax_update_gmb_data',	
			data:{campaign_id,email,account,_token:$('meta[name="csrf-token"]').attr('content')},	
			dataType:'json',	
			success:function(response){	
				$('.gmb-progress-loader').addClass('complete');
				if (response['status'] == 'success') {	
					Command: toastr["success"]('GMB Account Connected successfully! Fetching Data it may take some time.');
					$("#project_setting_gmb_close").trigger("click");
					$("body").removeClass("popup-open");
					$('.default_gmb').hide();
					$('.gmb_connected').show();
					$('#gmb_email').html(response['email']);
					$('#gmb_account').html(response['accountName']);
					$("#integrationTab").load(location.href + " #project-integration-list");

					if(response['type'] == 'new'){	
						$.ajax({	
							type:'GET',	
							url:BASE_URL+'/log_gmb_data',	
							data:{campaign_id:$('.campaign_id').val()},	
							successs:function(response){	
								// console.log(response);	
							}	
						});	

						//displaying preparing pop-up 
						$('#preparingGMBDashboard').trigger('click');
						$('#preparingGMBDashboard').css('display', 'block');
						$('body').addClass('popup-open');
						setTimeout(function() {
							$("#preparingGMBDashboard_close").trigger("click");
							$("body").removeClass("popup-open");
							$("#GMB").load('/campaign_gmb_content/' + $('.campaign_id').val(), function(responseTxt, statusTxt, xhr){
								if(statusTxt == "success")
									if ($('#GMB').find('.main-data').find('#gmb_add').length == 0) {
										gmb_scripts($('.campaign_id').val());
									}
									if(statusTxt == "error")
										console.log("Error: " + xhr.status + ": " + xhr.statusText);
								});

						},10000);				
					}	
				}  else if (response['status'] == 'google-error') {
					Command: toastr["error"](response['message']);
				} else {
					Command: toastr["error"]('Please try again getting error');
				}	

				$('#settings_save_gmb_account').removeAttr('disabled','disabled');

				setTimeout(function(){
					$('.gmb-progress-loader').css('display','none');
					$('.gmb-progress-loader').removeClass('complete');
					$('.popup-inner').css('overflow','auto');
				}, 1000);
			}	
		});	
	}	
});


//may 06
$(document).on('click','#SettingsAdwordsBtnId',function(e){
	disableSelectPicker('#adwords-emails','.project-setting-loaders');
	fetch_adwords_emails();
});

$(document).on('click','#SettingsGMBBtnId',function(e){
	disableSelectPicker('#gmb-email-loader','.project-setting-loaders');
	fetch_gmb_emails();
});

function refresh_analytics_popup(){	
	// $('#settings_analytics_existing_emails').selectpicker('refresh');	
	$('.selectpicker').selectpicker('refresh');	
	fetchAnalyticsAccounts();	
	fetchAnalyticsProperty();	
	fetchAnalyticsView();	
}	
function fetchAnalyticsAccounts(){	
	$.ajax({	
		url:BASE_URL+'/ajax_fetch_analytics_accounts',	
		type:'GET',	
		data:{campaign_id:$('.campaign_id').val()},	
		success:function(response){	
			$('#settings_analytics_existing_emails').html(response.emails);
			$('#settings_analytics_accounts').html(response.accounts);
			$('#settings_analytics_property').html(response.properties);
			$('#settings_analytics_view').html(response.views);
			enableSelectPicker('.analytic-property-loader','.project-setting-loaders');
			$('#settings_analytics_existing_emails, #settings_analytics_accounts, #settings_analytics_property, #settings_analytics_view').selectpicker('refresh');
		}	
	})	
}	
function fetchAnalyticsProperty(){	
	$.ajax({	
		url:BASE_URL+'/ajax_fetch_analytics_property',	
		type:'GET',	
		data:{campaign_id:$('.campaign_id').val()},	
		success:function(response){	
			$('#settings_analytics_property').html(response);	
			$('#settings_analytics_property').selectpicker('refresh');	
		}	
	})	
}	
function fetchAnalyticsView(){	
	$.ajax({	
		url:BASE_URL+'/ajax_fetch_analytics_view',	
		type:'GET',	
		data:{campaign_id:$('.campaign_id').val()},	
		success:function(response){	
			$('#settings_analytics_view').html(response);
			$('#settings_analytics_view').selectpicker('refresh');	
		}	
	})	
}	

$(document).on('click','#SettingsConsoleBtnId',function(e){
	disableSelectPicker('#sce-loader','.project-setting-loaders');
	fetch_console_urls();
});

$(document).on('click','#project_setting_console_close, #project_setting_adwords_close, #project_setting_gmb_close, #project_setting_facebook_close',function(e){
	
	$('.refresh-account-div').hide();
	$('#show_search_console_last_time').parent().removeClass('error');
	$('#show_search_console_last_time').parent().removeClass('yellow');
	$('#show_search_console_last_time').parent().removeClass('green');
	$('#show_search_console_last_time').parent().css('display','none');


});

function refresh_console_popup(){	
	$('.selectpicker').selectpicker('refresh');	
	fetch_console_urls();	
}

function fetch_console_urls(){	
	$.ajax({	
		type:'GET',	
		url:BASE_URL+'/ajax_fetch_console_urls',	
		data:{campaign_id:$('.campaign_id').val()},	
		success:function(response){	
			$('#settings_search_console_urlaccounts').html(response['emails']);
			$('#settings_search_console_existing_emails').html(response['url']);
			$('#settings_search_console_existing_emails, #settings_search_console_urlaccounts').selectpicker('refresh');
			enableSelectPicker('#sce-loader','.project-setting-loaders');
		}	
	});	
}	
	

function refresh_adwords_popup(){	
	fetch_adwords_emails();	
	$('#settings_adwords_existing_emails').selectpicker('refresh');	
	$('.selectpicker').selectpicker('refresh');	
	fetch_adwords_campaigns();	
}	

function fetch_adwords_emails(){	
	$.ajax({	
		url:BASE_URL+'/ajax_fetch_adwords_emails',	
		type:'GET',	
		data:{campaign_id:$('.campaign_id').val()},	
		success:function(response){	
			$('#save_settings_analytics_account').removeAttr('disabled','disabled');
			$('#settings_adwords_existing_emails').html(response['emails']);
			$('#settings_adwords_accounts').html(response['accounts']);
			$('#settings_adwords_existing_emails, #settings_adwords_accounts').selectpicker('refresh');

			enableSelectPicker('#adwords-emails','.project-setting-loaders');
		}	
	})	
}

function fetch_adwords_campaigns(){	
	$.ajax({	
		type:'GET',	
		url:BASE_URL+'/ajax_fetch_adwords_campaigns',	
		data:{campaign_id:$('.campaign_id').val()},	
		success:function(response){	
			$('#settings_adwords_accounts').html(response);	
			$('#settings_adwords_accounts').selectpicker('refresh');	
		}	
	});	
}


function refresh_gmb_popup(){	
	fetch_gmb_emails();	
	$('.selectpicker').selectpicker('refresh');	
	fetch_gmb_accounts();	
	
}	

function fetch_gmb_emails(){	
	$.ajax({	
		url:BASE_URL+'/ajax_fetch_gmb_emails',	
		data:{campaign_id:$('.campaign_id').val(),user_id:$('.user_id').val()},	
		type:'GET',	
		success:function(response){	
			$('#settings_gmb_existing_emails').html(response['emails']);
			$('#settings_gmb_accounts').html(response['accounts']);
			$('#settings_gmb_existing_emails, #settings_gmb_accounts').selectpicker('refresh');
			enableSelectPicker('#gmb-email-loader','.project-setting-loaders');
		}	
	})	
}

function fetch_gmb_accounts(){	
	$.ajax({	
		type:'GET',	
		url:BASE_URL +'/ajax_fetch_gmb_accounts',	
		data:{campaign_id:$('.campaign_id').val()},	
		success:function(response){	
			$('#settings_gmb_accounts').html(response);	
			$('#settings_gmb_accounts').selectpicker('refresh');	
		}	
	});	
}

$(document).on('click','#refresh_analytics_account',function(e){
	$(this).addClass('refresh-gif');
	$('#save_settings_analytics_account').attr('disabled','disabled');
	$('#settings_add_new_analytics_account').attr('disabled','disabled');
	$('.popup-inner').css('overflow','hidden');

	var email = $('#settings_analytics_existing_emails').val();
	var campaign_id = $('.campaign_id').val();

	$('.analytics-progress-loader').css('display','block');
	
	$('#show_analytics_last_time').parent().removeClass('error');
	$('#show_analytics_last_time').parent().removeClass('green');
	$('#show_analytics_last_time').parent().addClass('yellow');
	$('#show_analytics_last_time').parent().css('display','block');
	document.getElementById('show_analytics_last_time').innerHTML = 'Fetching list of accounts.';
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_refresh_analytics_acccount_list',
		data:{email,campaign_id},
		dataType:'json',
		success:function(response){

			if(response['status'] == 1){
				$.ajax({
					type:'GET',
					url:BASE_URL +'/ajax_get_analytics_accounts',
					data:{email:$('#settings_analytics_existing_emails').val(),campaign_id:$('.campaign_id').val()},
					success:function(result){
						$('#settings_analytics_accounts').html(result);
						var li    = '<option value="">Select Property</option>';
						$('#settings_analytics_property').html(li);
						var li    = '<option value="">Select View</option>';
						$('#settings_analytics_view').html(li);
						$('#settings_analytics_accounts, #settings_analytics_property, #settings_analytics_view').selectpicker('refresh');
					}
				});


				$('.analytics-progress-loader').addClass('complete');

				$('#refresh_analytics_account').removeClass('refresh-gif');
				$('#save_settings_analytics_account').removeAttr('disabled','disabled');
				$('#settings_add_new_analytics_account').removeAttr('disabled','disabled');

				$('#show_analytics_last_time').parent().removeClass('error');
				$('#show_analytics_last_time').parent().removeClass('yellow');
				$('#show_analytics_last_time').parent().addClass('green');
				$('#show_analytics_last_time').parent().css('display','block');
				document.getElementById('show_analytics_last_time').innerHTML = response['message'];
				
			}

			if(response['status'] == 0){
				$('#refresh_analytics_account').removeClass('refresh-gif');
				$('#save_settings_analytics_account').removeAttr('disabled','disabled');
				$('#settings_add_new_analytics_account').removeAttr('disabled','disabled');
				
				$('#show_analytics_last_time').parent().removeClass('yellow');
				$('#show_analytics_last_time').parent().removeClass('green');
				$('#show_analytics_last_time').parent().addClass('error');
				$('#show_analytics_last_time').parent().css('display','block');
				document.getElementById('show_analytics_last_time').innerHTML = response['message'];
			}

			if(response['status'] == 2){
				$('#refresh_analytics_account').removeClass('refresh-gif');
				$('#save_settings_analytics_account').removeAttr('disabled','disabled');
				$('#settings_add_new_analytics_account').removeAttr('disabled','disabled');

				$('#show_analytics_last_time').parent().removeClass('yellow');
				$('#show_analytics_last_time').parent().removeClass('green');
				$('#show_analytics_last_time').parent().addClass('error');
				$('#show_analytics_last_time').parent().css('display','block');
				document.getElementById('show_analytics_last_time').innerHTML = response['message'];

			}

			setTimeout(function(){
				$('.analytics-progress-loader').css('display','none');
				$('.analytics-progress-loader').removeClass('complete');
				$('.popup-inner').css('overflow','auto');
			}, 1000);
		}
	});
});


$(document).on('click','#refresh_search_console_account',function(e){
	e.preventDefault();
	$(this).addClass('refresh-gif');
	$('#settings_save_console_account').attr('disabled','disabled');
	$('#settings_searchConsoleAddBtn').attr('disabled','disabled');
	$('.popup-inner').css('overflow','hidden');

	var email = $('#settings_search_console_existing_emails').val();
	var campaign_id = $('.campaign_id').val();
	$('.searchConsole-progress-loader').css('display','block');
	
	$('#show_search_console_last_time').parent().removeClass('error');
	$('#show_search_console_last_time').parent().removeClass('green');
	$('#show_search_console_last_time').parent().addClass('yellow');
	$('#show_search_console_last_time').parent().css('display','block');
	document.getElementById('show_search_console_last_time').innerHTML = 'Fetching list of accounts.';

	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_refresh_search_console_urls',
		data:{email,campaign_id},
		dataType:'json',
		success:function(response){

			if(response['status'] == 1){
				$.ajax({
					type:'GET',
					url:BASE_URL +'/ajax_get_console_urls',
					data:{email:$('#settings_search_console_existing_emails').val(),campaign_id:$('.campaign_id').val()},
					success:function(result){
						$('#settings_search_console_urlaccounts').html(result);
						$('#settings_search_console_urlaccounts, #settings_search_console_urlaccounts').selectpicker('refresh');
					}
				});


				$('.searchConsole-progress-loader').addClass('complete');

				$('#refresh_search_console_account').removeClass('refresh-gif');
				$('#settings_save_console_account').removeAttr('disabled','disabled');
				$('#settings_searchConsoleAddBtn').removeAttr('disabled','disabled');

				$('#show_search_console_last_time').parent().removeClass('error');
				$('#show_search_console_last_time').parent().removeClass('yellow');
				$('#show_search_console_last_time').parent().addClass('green');
				$('#show_search_console_last_time').parent().css('display','block');
				document.getElementById('show_search_console_last_time').innerHTML = response['message'];
				
			}

			if(response['status'] == 0){
				$('#refresh_search_console_account').removeClass('refresh-gif');
				$('#settings_save_console_account').removeAttr('disabled','disabled');
				$('#settings_searchConsoleAddBtn').removeAttr('disabled','disabled');
				
				$('#show_search_console_last_time').parent().removeClass('yellow');
				$('#show_search_console_last_time').parent().removeClass('green');
				$('#show_search_console_last_time').parent().addClass('error');
				$('#show_search_console_last_time').parent().css('display','block');
				document.getElementById('show_search_console_last_time').innerHTML = response['message'];
			}

			if(response['status'] == 2){
				$('#refresh_search_console_account').removeClass('refresh-gif');
				$('#settings_save_console_account').removeAttr('disabled','disabled');
				$('#settings_searchConsoleAddBtn').removeAttr('disabled','disabled');

				$('#show_search_console_last_time').parent().removeClass('yellow');
				$('#show_search_console_last_time').parent().removeClass('green');
				$('#show_search_console_last_time').parent().addClass('error');
				$('#show_search_console_last_time').parent().css('display','block');
				document.getElementById('show_search_console_last_time').innerHTML = response['message'];

			}

			setTimeout(function(){
				$('.searchConsole-progress-loader').css('display','none');
				$('.searchConsole-progress-loader').removeClass('complete');
				$('.popup-inner').css('overflow','auto');
			}, 1000);
		}
	});
});

function fetch_last_updated(email_id,campaign_id,provider){
	if(email_id == '' && provider == 'gmb'){
		$('#show_ppc_last_time').parent().removeClass('error');
		$('#show_ppc_last_time').parent().removeClass('yellow');
		$('#show_ppc_last_time').parent().removeClass('green');
		$('#show_ppc_last_time').parent().css('display','none');
		document.getElementById('show_ppc_last_time').innerHTML = '';
		$('.ppc_refresh_div').css('display','none');
		return;
	}

	if(email_id == '' && provider == 'ppc'){
		$('#show_analytics_last_time').parent().removeClass('error');
		$('#show_analytics_last_time').parent().removeClass('yellow');
		$('#show_analytics_last_time').parent().removeClass('green');
		$('#show_analytics_last_time').parent().css('display','none');
		document.getElementById('show_analytics_last_time').innerHTML = '';
		$('.analytics_refresh_div').css('display','none');
		return;
	}


	if(email_id == '' && provider == 'google_analytics'){
		$('#show_analytics_last_time').parent().removeClass('error');
		$('#show_analytics_last_time').parent().removeClass('yellow');
		$('#show_analytics_last_time').parent().removeClass('green');
		$('#show_analytics_last_time').parent().css('display','none');
		document.getElementById('show_analytics_last_time').innerHTML = '';
		$('.analytics_refresh_div').css('display','none');
		return;
	}


	if(email_id == '' && provider == 'search_console'){
		$('#show_search_console_last_time').parent().removeClass('error');
		$('#show_search_console_last_time').parent().removeClass('yellow');
		$('#show_search_console_last_time').parent().removeClass('green');
		$('#show_search_console_last_time').parent().css('display','none');
		document.getElementById('show_search_console_last_time').innerHTML = '';
		$('.search_console_refresh_div').css('display','none');
		return;
	}

	if(email_id == '' && provider == 'facebook'){
		$('#show_facebook_last_time').parent().removeClass('error yellow green');
		$('#show_facebook_last_time').parent().css('display','none');
		document.getElementById('show_facebook_last_time').innerHTML = '';
		$('.facebook_refresh_div').css('display','none');
		return;
	}

	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_fetch_last_updated',
		data:{email_id,campaign_id,provider},
		dataType:'json',
		success:function(response){

			if(response['status'] == 1 && provider == 'gmb'){
				$('#show_gmb_last_time').parent().removeClass('error');
				$('#show_gmb_last_time').parent().removeClass('yellow');
				$('#show_gmb_last_time').parent().addClass('green');
				$('#show_gmb_last_time').parent().css('display','block');
				document.getElementById('show_gmb_last_time').innerHTML = 'The account was last synced: '+response['time'];
			}else if(response['status'] == 0 && provider == 'gmb'){
				$('#show_gmb_last_time').parent().addClass('error');
				$('#show_gmb_last_time').parent().removeClass('yellow');
				$('#show_gmb_last_time').parent().removeClass('green');
				$('#show_gmb_last_time').parent().css('display','block');
				document.getElementById('show_gmb_last_time').innerHTML = response['time'];
			}

			if(response['status'] == 1 && provider == 'ppc'){
				$('#show_ppc_last_time').parent().removeClass('error');
				$('#show_ppc_last_time').parent().removeClass('yellow');
				$('#show_ppc_last_time').parent().addClass('green');
				$('#show_ppc_last_time').parent().css('display','block');
				document.getElementById('show_ppc_last_time').innerHTML = 'The account was last synced: '+response['time'];
			}else if(response['status'] == 0 && provider == 'ppc'){
				$('#show_ppc_last_time').parent().addClass('error');
				$('#show_ppc_last_time').parent().removeClass('yellow');
				$('#show_ppc_last_time').parent().removeClass('green');
				$('#show_ppc_last_time').parent().css('display','block');
				document.getElementById('show_ppc_last_time').innerHTML = response['time'];
			}

			if(response['status'] == 1 && provider == 'google_analytics'){
				$('#show_analytics_last_time').parent().removeClass('error');
				$('#show_analytics_last_time').parent().removeClass('yellow');
				$('#show_analytics_last_time').parent().addClass('green');
				$('#show_analytics_last_time').parent().css('display','block');
				document.getElementById('show_analytics_last_time').innerHTML = 'The account was last synced: '+response['time'];
			}else if(response['status'] == 0 && provider == 'google_analytics'){
				$('#show_analytics_last_time').parent().addClass('error');
				$('#show_analytics_last_time').parent().removeClass('yellow');
				$('#show_analytics_last_time').parent().removeClass('green');
				$('#show_analytics_last_time').parent().css('display','block');
				document.getElementById('show_analytics_last_time').innerHTML = response['time'];
			}

			if(response['status'] == 1 && provider == 'search_console'){
				$('#show_search_console_last_time').parent().removeClass('error');
				$('#show_search_console_last_time').parent().removeClass('yellow');
				$('#show_search_console_last_time').parent().addClass('green');
				$('#show_search_console_last_time').parent().css('display','block');
				document.getElementById('show_search_console_last_time').innerHTML = 'The account was last synced: '+response['time'];
			}else if(response['status'] == 0 && provider == 'search_console'){
				$('#show_search_console_last_time').parent().addClass('error');
				$('#show_search_console_last_time').parent().removeClass('yellow');
				$('#show_search_console_last_time').parent().removeClass('green');
				$('#show_search_console_last_time').parent().css('display','block');
				document.getElementById('show_search_console_last_time').innerHTML = response['time'];
			}

			if(response['status'] == 1 && provider == 'facebook'){
				$('#show_facebook_last_time').parent().removeClass('error yellow');
				$('#show_facebook_last_time').parent().addClass('green');
				$('#show_facebook_last_time').parent().css('display','block');
				document.getElementById('show_facebook_last_time').innerHTML = 'The account was last synced: '+response['time'];
			}else if(response['status'] == 0 && provider == 'facebook'){
				$('#show_facebook_last_time').parent().addClass('error');
				$('#show_facebook_last_time').parent().removeClass('yellow green');
				$('#show_facebook_last_time').parent().css('display','block');
				document.getElementById('show_facebook_last_time').innerHTML = response['time'];
			}
		}
	});
}


$(document).on('click','#refresh_gmb_account',function(e){
	$(this).addClass('refresh-gif');
	$('#settings_save_gmb_account').attr('disabled','disabled');
	$('#settings_add_new_gmb_account').attr('disabled','disabled');
	$('.popup-inner').css('overflow','hidden');
	var email = $('#settings_gmb_existing_emails').val();
	var campaign_id = $('.campaign_id').val();
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_refresh_gmb_acccount_list',
		data:{email,campaign_id},
		dataType:'json',
		success:function(response){
			$('.gmb-progress-loader').css('display','block');
			

			$('#show_gmb_last_time').parent().removeClass('error');
			$('#show_gmb_last_time').parent().removeClass('green');
			$('#show_gmb_last_time').parent().addClass('yellow');
			$('#show_gmb_last_time').parent().css('display','block');
			document.getElementById('show_gmb_last_time').innerHTML = 'Fetching list of accounts.';

			if(response['status'] == 1){

				$.ajax({
					type:'GET',
					url:BASE_URL +'/ajax_get_gmb_accounts',
					data:{email:$('#settings_gmb_existing_emails').val(),campaign_id:$('.campaign_id').val()},
					success:function(result){
						$('#settings_gmb_accounts').html(result);
						$('#settings_gmb_existing_emails, #settings_gmb_accounts').selectpicker('refresh');
					}
				});


				$('.gmb-progress-loader').addClass('complete');
				
				$('#refresh_gmb_account').removeClass('refresh-gif');
				$('#settings_save_gmb_account').removeAttr('disabled','disabled');
				$('#settings_add_new_gmb_account').removeAttr('disabled','disabled');
				

				$('#show_gmb_last_time').parent().removeClass('yellow');
				$('#show_gmb_last_time').parent().removeClass('error');
				$('#show_gmb_last_time').parent().addClass('green');
				$('#show_gmb_last_time').parent().css('display','block');
				document.getElementById('show_gmb_last_time').innerHTML = response['message'];
				
			}

			if(response['status'] == 0){
				$('#refresh_gmb_account').removeClass('refresh-gif');
				$('#settings_save_gmb_account').removeAttr('disabled','disabled');
				$('#settings_add_new_gmb_account').removeAttr('disabled','disabled');
				

				$('#show_gmb_last_time').parent().removeClass('yellow');
				$('#show_gmb_last_time').parent().removeClass('green');
				$('#show_gmb_last_time').parent().addClass('error');
				$('#show_gmb_last_time').parent().css('display','block');
				document.getElementById('show_gmb_last_time').innerHTML = response['message'];
			}

			if(response['status'] == 2){
				$('#refresh_gmb_account').removeClass('refresh-gif');
				$('#settings_save_gmb_account').removeAttr('disabled','disabled');
				$('#settings_add_new_gmb_account').removeAttr('disabled','disabled');

				$('#show_gmb_last_time').parent().removeClass('yellow');
				$('#show_gmb_last_time').parent().removeClass('green');
				$('#show_gmb_last_time').parent().addClass('error');
				$('#show_gmb_last_time').parent().css('display','block');
				document.getElementById('show_gmb_last_time').innerHTML = response['message'];
			}

			setTimeout(function(){
				$('.gmb-progress-loader').css('display','none');
				$('.gmb-progress-loader').removeClass('complete');
				$('.popup-inner').css('overflow','auto');
			}, 1000);
		}
	});
});

//table settings
$(document).on('change','.liveKeyword_table_toggle',function(e){
	e.preventDefault();
	var switchStatus = false;
	var name = $(this).attr('data-name');
	var column = $(this).attr('data-column');
	if ($(this).is(':checked')) {
		switchStatus = $(this).is(':checked');
	}
	var campaign_id = $('.campaign_id').val();
	$.ajax({
		type:'post',
		data:{campaign_id,switchStatus,name,column,_token:$('meta[name="csrf-token"]').attr('content')},
		dataType:'json',
		url:BASE_URL+'/ajax_save_keyword_table_settings',
		success:function(response){
			if(response['status'] == 1){
				Command: toastr["success"](response['message']);
			} else{
				Command: toastr["error"]('Error, please try again.');
			}
		}
	});
});


function getTableSetting(campaign_id){
	$.ajax({
		type:'GET',
		data:{campaign_id},
		dataType:'json',
		url:BASE_URL+'/ajax_get_table_settings',
		success:function(response){
			if(response['start_rank_detail'] === 'checked'){
				$("input[data-name='start_rank'][data-column='detail']").attr("checked", true);
			}
			if(response['start_rank_viewkey'] === 'checked'){
				$("input[data-name='start_rank'][data-column='viewkey']").attr("checked", true);
			}
			if(response['start_rank_pdf'] === 'checked'){
				$("input[data-name='start_rank'][data-column='pdf']").attr("checked", true);
			}
			if(response['page_detail'] === 'checked'){
				$("input[data-name='page'][data-column='detail']").attr("checked", true);
			}
			if(response['page_viewkey'] === 'checked'){
				$("input[data-name='page'][data-column='viewkey']").attr("checked", true);
			}
			if(response['page_pdf'] === 'checked'){
				$("input[data-name='page'][data-column='pdf']").attr("checked", true);
			}
			if(response['google_rank_detail'] === 'checked'){
				$("input[data-name='google_rank'][data-column='detail']").attr("checked", true);
			}
			if(response['google_rank_viewkey'] === 'checked'){
				$("input[data-name='google_rank'][data-column='viewkey']").attr("checked", true);
			}
			if(response['google_rank_pdf'] === 'checked'){
				$("input[data-name='google_rank'][data-column='pdf']").attr("checked", true);
			}
			if(response['oneday_detail'] === 'checked'){
				$("input[data-name='oneday'][data-column='detail']").attr("checked", true);
			}
			if(response['oneday_viewkey'] === 'checked'){
				$("input[data-name='oneday'][data-column='viewkey']").attr("checked", true);
			}
			if(response['oneday_pdf'] === 'checked'){
				$("input[data-name='oneday'][data-column='pdf']").attr("checked", true);
			}
			if(response['weekly_detail'] === 'checked'){
				$("input[data-name='weekly'][data-column='detail']").attr("checked", true);
			}
			if(response['weekly_viewkey'] === 'checked'){
				$("input[data-name='weekly'][data-column='viewkey']").attr("checked", true);
			}
			if(response['weekly_pdf'] === 'checked'){
				$("input[data-name='weekly'][data-column='pdf']").attr("checked", true);
			}
			if(response['monthly_detail'] === 'checked'){
				$("input[data-name='monthly'][data-column='detail']").attr("checked", true);
			}
			if(response['monthly_viewkey'] === 'checked'){
				$("input[data-name='monthly'][data-column='viewkey']").attr("checked", true);
			}
			if(response['monthly_pdf'] === 'checked'){
				$("input[data-name='monthly'][data-column='pdf']").attr("checked", true);
			}
			if(response['lifetime_detail'] === 'checked'){
				$("input[data-name='lifetime'][data-column='detail']").attr("checked", true);
			}
			if(response['lifetime_viewkey'] === 'checked'){
				$("input[data-name='lifetime'][data-column='viewkey']").attr("checked", true);
			}
			if(response['lifetime_pdf'] === 'checked'){
				$("input[data-name='lifetime'][data-column='pdf']").attr("checked", true);
			}
			if(response['competition_detail'] === 'checked'){
				$("input[data-name='competition'][data-column='detail']").attr("checked", true);
			}
			if(response['competition_viewkey'] === 'checked'){
				$("input[data-name='competition'][data-column='viewkey']").attr("checked", true);
			}
			if(response['competition_pdf'] === 'checked'){
				$("input[data-name='competition'][data-column='pdf']").attr("checked", true);
			}
			if(response['sv_detail'] === 'checked'){
				$("input[data-name='sv'][data-column='detail']").attr("checked", true);
			}
			if(response['sv_viewkey'] === 'checked'){
				$("input[data-name='sv'][data-column='viewkey']").attr("checked", true);
			}
			if(response['sv_pdf'] === 'checked'){
				$("input[data-name='sv'][data-column='pdf']").attr("checked", true);
			}
			if(response['date_detail'] === 'checked'){
				$("input[data-name='date'][data-column='detail']").attr("checked", true);
			}
			if(response['date_viewkey'] === 'checked'){
				$("input[data-name='date'][data-column='viewkey']").attr("checked", true);
			}
			if(response['date_pdf'] === 'checked'){
				$("input[data-name='date'][data-column='pdf']").attr("checked", true);
			}
			if(response['url_detail'] === 'checked'){
				$("input[data-name='url'][data-column='detail']").attr("checked", true);
			}
			if(response['url_viewkey'] === 'checked'){
				$("input[data-name='url'][data-column='viewkey']").attr("checked", true);
			}
			if(response['url_pdf'] === 'checked'){
				$("input[data-name='url'][data-column='pdf']").attr("checked", true);
			}
			if(response['graph_detail'] === 'checked'){
				$("input[data-name='graph'][data-column='detail']").attr("checked", true);
			}
			if(response['graph_viewkey'] === 'checked'){
				$("input[data-name='graph'][data-column='viewkey']").attr("checked", true);
			}
			if(response['graph_pdf'] === 'checked'){
				$("input[data-name='graph'][data-column='pdf']").attr("checked", true);
			}
		}

	});
}

$(document).on('click','.lk-table-setting',function(e){
	e.preventDefault();
	getTableSetting($('.campaign_id').val());
});


/*Alerts settings*/

$(document).on('keyup change','.alertSettings',function(e){
	e.preventDefault();
	onbeforeunload();
});

$(document).on('change','.keyword_client_alerts',function(){
	if ($(this).is(":checked")){
		$('#alert_clients_div').removeClass('hide');
		
		/*manager fields*/
		$('#display_manager_div').removeClass('hide');
	}else{
		$('#alert_clients_div').addClass('hide');
		$('.keyword_alerts_email').val('');

		/*manager fields*/
		$('#display_manager_div').addClass('hide');
		$('.keyword_manager_alerts_email').val('');
	}
});

$(document).on('change','.keyword_manager_alerts',function(){
	if ($(this).is(":checked")){
		$('#alert_manager_div').removeClass('hide');
	}else{
		$('#alert_manager_div').addClass('hide');
		$('.keyword_manager_alerts_email').val('');
	}
});

$(document).on('click','#alerts-add-clients',function(){

	$('.alert_content_div').removeClass('hideTrash');
	if($('.alert_content_div .form-group').length <= 9){
		var email_img= BASE_URL +'/public/vendor/internal-pages/images/mail-icon.png';
		$('#alerts-add-clients').before('<div class="form-group"><div class="uk-flex"><span class="icon"><img src="'+email_img+'"></span><input type="text" name="keyword_alerts_client_email[]" class="keyword_alerts_client_email form-control alertSettings " placeholder="Enter email to send alerts"><figure class="remove-append-addEmail"><i class="fa fa-trash"></i></figure></div></div>');
	}else{
		confirm("No more email addresses can be added");
	}
});

$(document).on('click','.remove-append-addEmail',function(e){
	$(this).parent().parent().remove();
	if($('.keyword_alerts_client_email').length ==1){
		$('.alert_content_div').addClass('hideTrash');
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

$(document).on('keyup blur focusout','.keyword_alerts_client_email', function () {
	if($(this).val() !== ''){
		if(ValidateEmail($(this).val()) === 'error'){
			$(this).addClass('error');
		}else{
			$(this).removeClass('error');
		}
	}else{
		$(this).removeClass('error');
	}
});


$(document).on("keyup change", '#project_alert_settings input,#project_alert_settings radio', function(e) {
	
	if ($('.keyword_alerts_client_email').val() === '') {
		$('.keyword_alerts_client_email').addClass('error');
	}else{
		if(ValidateEmail($('.keyword_alerts_client_email').val()) === 'error'){
			$('.keyword_alerts_client_email').addClass('error');
		}else{
			$('.keyword_alerts_client_email').removeClass('error');
		}
	}

	if ($('.keyword_manager_alerts').is(":checked")){

		if ($('.keyword_manager_alerts_email').val() === '') {
			$('.keyword_manager_alerts_email').addClass('error');
		}else{
			if(ValidateEmail($('.keyword_manager_alerts_email').val()) === 'error'){
				$('.keyword_manager_alerts_email').addClass('error');
			}else{
				$('.keyword_manager_alerts_email').removeClass('error');
			}
		}
	}else{
		$('.keyword_manager_alerts_email').addClass('error');
	}
});

$(document).on('click','#update_project_alert_settings',function(){
	$(this).attr('disabled','disabled');
	var myform = document.getElementById("project_alert_settings");
	var fd = new FormData(myform);

	$.ajax({
		url: BASE_URL+'/ajax_update_project_alerts',
		data: fd,
		cache: false,
		processData: false,
		contentType: false,
		type: 'POST',
		success: function (dataofconfirm) {
			deactivateReloader();
			if(dataofconfirm['status'] === 0){
				if(dataofconfirm['keys'].length !== 0){
					Command: toastr["error"]('An invalid email was detected, please double check your email(s)!');
				} 

				if(dataofconfirm['message']['keyword_manager_alerts_email']){
					$(".keyword_manager_alerts_email").addClass('error');
				}
			}

			if(dataofconfirm['status'] === 1){
				Command: toastr["success"]('Changes saved successfully');
			}

			if(dataofconfirm['status'] === 2){
				Command: toastr["error"]('Error, try again later');
			}


			$('#update_project_alert_settings').removeAttr('disabled','disabled');		
			
		}	        
	});
});

function disableSelectPicker(target,loader){
	$(target).prop('disabled', true);
	$(target).selectpicker('refresh');
	$(loader).css('display','block');
}

function enableSelectPicker(target,loader){
	$(target).prop('disabled', false);
	$(loader).css('display','none');
}

/*new design changes*/
$(document).on('click','#settings_save_console_account',function(e){
	e.preventDefault();
	var campaign_id = $('.campaign_id').val();
	var email = $('#settings_search_console_existing_emails').val();
	var account = $('#settings_search_console_urlaccounts').val();
	
	
	if(email == ''){
		$('#settings_search_console_existing_emails').parent().addClass('error');
	}else{
		$('#settings_search_console_existing_emails').parent().removeClass('error');
	}
	if(account == ''){
		$('#settings_search_console_urlaccounts').parent().addClass('error');
	}else{
		$('#settings_search_console_urlaccounts').parent().removeClass('error');
	}


	if(email != '' && account !=''){
		$('.searchConsole-progress-loader').css('display','block');
		$('.popup-inner').css('overflow','hidden');
		$(this).attr('disabled','disabled');
		$.ajax({
			type:'POST',
			url:BASE_URL + '/ajax_update_console_data',
			data:{campaign_id,email,account,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				$('.searchConsole-progress-loader').addClass('complete');
				if (response['status'] == 'success') {
					Command: toastr["success"]('Console Account Connected successfully!');
					$("#project_setting_console_close").trigger("click");
					$("body").removeClass("popup-open");

					$('#integrationTab').load(location.href + ' #project-integration-list');

				} else if (response['status'] == 'google-error') {
					Command: toastr["error"](response['message'] +' Try reconnecting your account.');
				} 
				else {
					Command: toastr["error"]('Please try again getting error');
				}

				$('#settings_save_console_account').removeAttr('disabled','disabled');

				setTimeout(function(){
					$('#show_search_console_last_time').parent().removeClass('error');
					$('#show_search_console_last_time').parent().removeClass('yellow');
					$('#show_search_console_last_time').parent().removeClass('green');
					$('#show_search_console_last_time').parent().css('display','none');

					$('.searchConsole-progress-loader').css('display','none');
					$('.searchConsole-progress-loader').removeClass('complete');
					$('.popup-inner').css('overflow','auto');
				}, 1000);
			}
		});
	}
});


/* Facebook Setting*/

$(document).on('click','.settings_FacebookAddBtn',function(){
	var campaignId = $('.campaign_id').val();
	var currentRoute = $('.currentRoute').val();
	$('#settings_facebook_existing_accounts').addClass('addFacebookAppend')
	var link = BASE_URL +'/facebookcallback?campaignId='+campaignId+'&redirectPage='+currentRoute;
	connectPopup(link,"web","800","500");
});


function getFacebookAccounts(){
	disableSelectPicker('#settings_facebook_accounts','.facebook-account-loader');
	$.ajax({
		url:BASE_URL+'/ajax_get_facebook_existing_accounts',
		type:'GET',
		success:function(response){
			enableSelectPicker('#settings_facebook_accounts','.facebook-account-loader');
			$('#settings_facebook_existing_accounts').html(response);
			$('#settings_facebook_existing_accounts, #settings_facebook_accounts').selectpicker('refresh');
		}
	})
}

setInterval(function(){
	if($('#settings_facebook_existing_accounts').hasClass('addFacebookAppend')){
		setTimeout(function(){
			getFacebookAccounts();
		}, 2000);
	}
}, 5000);

$(document).on('change','#settings_facebook_existing_accounts',function(e){
	e.preventDefault();
	$('#settings_facebook_existing_accounts').removeClass('addFacebookAppend');

	var id = $(this).val();
	var campaign_id = $('.campaign_id').val();
	fetch_last_updated(id,campaign_id,'facebook');
    $('.facebook_refresh_div').css('display','block');
	disableSelectPicker('#settings_facebook_accounts','.facebook-loader');
	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_facebook_accounts',
		data:{id,campaign_id},
		success:function(response){
			enableSelectPicker('#settings_facebook_accounts','.facebook-loader');
			$('#settings_facebook_accounts').html(response);
			$('#settings_facebook_accounts').selectpicker('refresh');
		}
	});
});


$(document).on('click','#settings_save_facebook_account',function(e){
	e.preventDefault();
	let url = window.location.href;
	let position = url.search('campaign-detail');
	let addnewproject = url.search('add-new-project');

	var id = $('#settings_facebook_existing_accounts').val();
    var page = $('#settings_facebook_accounts').val();
    var campaign_id = $('.campaign_id').val();

    if(id == ''){
		$('#settings_facebook_existing_accounts').parent().addClass('error');
	}else{
		$('#settings_facebook_existing_accounts').parent().removeClass('error');
	}
	if(page == ''){
		$('#settings_facebook_accounts').parent().addClass('error');
	}else{
		$('#settings_facebook_accounts').parent().removeClass('error');
	}
	
	if(id != '' && page !=''){
		$('.facebook-progress-loader').css('display','block');
		$('.popup-inner').css('overflow','hidden');
		$(this).attr('disabled','disabled');

		$('#facebookSettingsBtnId').text('Connecting...');

		$('#preparingFb').css('display', 'flex');
		$.ajax({
			type:'POST',
			url:BASE_URL + '/ajax_update_facebook_data',
			data:{campaign_id,id,page,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if (response['status'] == 'success') {
					$('#preparingFb').css('display', 'flex');
					if(position == -1){
						$('.facebook-progress-loader').addClass('complete');
						Command: toastr["success"](response['message']);
					}
					

					$("#project_setting_facebook_close").trigger("click"); 
					$("body").removeClass("popup-open");

					$('.default_facebook').hide();
					$('.facebook_connected').show();
					$('#facebook_account').html(response['account']);
					$('#facebook_page').html(response['name']);

					//When not add new project
					if(addnewproject == -1){
						if(position == -1){
							$('#integrationTab').load(location.href + ' #project-integration-list');
							//displaying preparing pop-up 
							
						}

						// if(position > 0 ){
						// 	// $('#preparingFacebookDashboard').trigger('click');
						// 	// $('#preparingFacebookDashboard').css('display', 'block');
						// }


						//$('.facebook_overview,.fboverview').addClass('ajax-loader');

						$.ajax({
							type:'GET',
							url:BASE_URL+'/log_facebook_data',
							data:{campaign_id:$('.campaign_id').val()},
							success:function(logresponse){
								$('#preparingFb').css('display', 'none');
								$('.facebook_overview,.fboverview').removeClass('ajax-loader');
								$('.fboverview').removeClass('disabled');
								if(logresponse.status == 'success'){
									$('#preparingFacebookDashboard_close').trigger('click');
									$("body").removeClass("popup-open");
									if(position > 0){
										social_overview_scripts($('.campaign_id').val());
										$('#nav_facebook').removeClass('inactive');
										$('.facebook_overview').removeClass('disabled');
										$('#facebookSettingsBtnId').removeAttr('data-pd-popup-open');
										$('#facebookSettingsBtnId').attr("href", "#facebook");
										$('#facebookSettingsBtnId').addClass('facebook_view_more');
										$('#facebookSettingsBtnId').text('View More');
										$('#facebookSettingsBtnId').removeAttr('id');
										$('#nav_facebook').addClass('social_module');
									}
								}else{
									Command: toastr["error"](logresponse.message);
								}
							}
						});
					}
				}else {
					Command: toastr["error"](response['message']);
				}

				$('#settings_save_facebook_account').removeAttr('disabled','disabled');
				setTimeout(function(){
					$('.facebook-progress-loader').css('display','none');
					$('.facebook-progress-loader').removeClass('complete');

					

					$('.popup-inner').css('overflow','auto');
				}, 1000);
			}
		});
	}
});


$(document).on('click','#disconnectFacebook',function(e){
	e.preventDefault();

	if($('.campaign_id').val() != ''){
		$.ajax({
			type:'POST',
			url:BASE_URL +'/ajax_disconnect_facebook',
			data:{request_id:$('.campaign_id').val(),_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 'success')
				{
					$('#integrationTab').load(location.href + ' #project-integration-list');
					$('.default_facebook').show();
					$('.facebook_connected').hide();
				}else{
					Command: toastr["error"]('Error!! Please try again!');
				}
			}
		});
	}
});

$(document).on('click','#facebookSettingsBtnId',function(e){
	$('#show_facebook_last_time').parent().removeClass('error');
	$('#show_facebook_last_time').parent().removeClass('green');
	$('#show_facebook_last_time').parent().removeClass('yellow');
	$('.errorStyle').hide();
	document.getElementById('show_facebook_last_time').innerHTML = '';
	$('.facebook_refresh_div').css('display','none');
	disableSelectPicker('#settings_facebook_accounts','.facebook-account-loader,.facebook-loader');
	$.ajax({
		url:BASE_URL+'/ajax_get_facebook_connected_accounts',
		data:{id:$('.campaign_id').val()},
		type:'GET',
		success:function(response){
			enableSelectPicker('#settings_facebook_accounts','.facebook-account-loader,.facebook-loader');
			$('#settings_facebook_existing_accounts').html(response.accounts);
			$('#settings_facebook_accounts').html(response.pages);
			$('#settings_facebook_existing_accounts, #settings_facebook_accounts').selectpicker('refresh');
		}
	})
});



$(document).on('click','#refresh_facebook_account',function(e){
	$('#settings_save_facebook_account').attr('disabled','disabled');
	$('#settings_add_new_facebook_account').attr('disabled','disabled');
	$('.popup-inner').css('overflow','hidden');

	var id = $('#settings_facebook_existing_accounts').val();
	var campaign_id = $('.campaign_id').val();

	if(id != '' && campaign_id != ''){
		$(this).addClass('refresh-gif');
		$('.facebook-progress-loader').css('display','block');

		$('#show_facebook_last_time').parent().removeClass('error');
		$('#show_facebook_last_time').parent().removeClass('green');
		$('#show_facebook_last_time').parent().addClass('yellow');
		$('#show_facebook_last_time').parent().css('display','block');
		document.getElementById('show_facebook_last_time').innerHTML = 'Fetching list of accounts.';

		$.ajax({
			type:'GET',
			url:BASE_URL+'/ajax_refresh_facebook_acccount_list',
			data:{id,campaign_id},
			dataType:'json',
			success:function(response){

				if(response['status'] == 1){
					// getFacebookAccounts();

					$('.facebook-progress-loader').addClass('complete');

					$('#refresh_facebook_account').removeClass('refresh-gif');
					$('#settings_save_facebook_account').removeAttr('disabled','disabled');
					$('#settings_add_new_facebook_account').removeAttr('disabled','disabled');

					$('#show_facebook_last_time').parent().removeClass('error');
					$('#show_facebook_last_time').parent().removeClass('yellow');
					$('#show_facebook_last_time').parent().addClass('green');
					$('#show_facebook_last_time').parent().css('display','block');
					document.getElementById('show_facebook_last_time').innerHTML = response['message'];
					
				}

				if(response['status'] == 0){
					$('#refresh_facebook_account').removeClass('refresh-gif');
					$('#settings_save_facebook_account').removeAttr('disabled','disabled');
					$('#settings_add_new_facebook_account').removeAttr('disabled','disabled');
					
					$('#show_facebook_last_time').parent().removeClass('yellow');
					$('#show_facebook_last_time').parent().removeClass('green');
					$('#show_facebook_last_time').parent().addClass('error');
					$('#show_facebook_last_time').parent().css('display','block');
					document.getElementById('show_facebook_last_time').innerHTML = response['message'];
				}

				if(response['status'] == 2){
					$('#refresh_facebook_account').removeClass('refresh-gif');
					$('#settings_save_facebook_account').removeAttr('disabled','disabled');
					$('#settings_add_new_facebook_account').removeAttr('disabled','disabled');

					$('#show_facebook_last_time').parent().removeClass('yellow');
					$('#show_facebook_last_time').parent().removeClass('green');
					$('#show_facebook_last_time').parent().addClass('error');
					$('#show_facebook_last_time').parent().css('display','block');
					document.getElementById('show_facebook_last_time').innerHTML = response['message'];

				}

				setTimeout(function(){
					$('.facebook-progress-loader').css('display','none');
					$('.facebook-progress-loader').removeClass('complete');
					$('.popup-inner').css('overflow','auto');
				}, 1000);
			}
		});
	}
	
	
});

$(document).on('click','#connect_ua',function(){
	$('#show_analytics_last_time').innerHTML = '';
	$('#show_analytics_last_time').parent().removeClass('error green yellow');
	$('#settings_analytics_existing_emails ,#settings_analytics_accounts, #settings_analytics_property, #settings_analytics_view').html('');
	// $('#save_settings_analytics_account').attr('disabled','disabled');
	$('.errorStyle').hide();
	disableSelectPicker('.analytic-property-loader','.project-setting-loaders');
	fetchAnalyticsAccounts();
});


$(document).on('click','#connect_ga4 , #Settingsga4BtnId',function(){
	$('#show_ga4_last_time').parent().removeClass('error green yellow');
	$('#ga4_existing_emails ,#ga4_accounts, #ga4_property').html('');
	$('#save_ga4').attr('disabled','disabled');

	document.getElementById('show_ga4_last_time').innerHTML = '';
	disableSelectPicker('#ga4_existing_emails','.ga4-emails-loader');
	disableSelectPicker('#ga4_accounts','.ga4-account-loader');
	disableSelectPicker('#ga4_property','.ga4-property-loader');
	$.ajax({
		url:BASE_URL+'/ajax_get_ga4_connected_accounts',
		data:{id:$('.campaign_id').val()},
		type:'GET',
		success:function(response){
			enableSelectPicker('#ga4_existing_emails','.ga4-emails-loader');
			enableSelectPicker('#ga4_accounts','.ga4-account-loader');
			enableSelectPicker('#ga4_property','.ga4-property-loader');

			$('#ga4_existing_emails').html(response.emails);
			$('#ga4_accounts').html(response.accounts);
			$('#ga4_property').html(response.property);
			$('#save_ga4').removeAttr('disabled','disabled');
			$('#ga4_existing_emails, #ga4_accounts, #ga4_property').selectpicker('refresh');
		}
	});
});