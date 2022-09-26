var BASE_URL = $('.base_url').val();

//loading google maps api for step 3
google.maps.event.addDomListener(window, 'load', initializeAddProject);
function initializeAddProject() {
	var input = document.getElementById('add_project_locations'); 
	var autocomplete = new google.maps.places.Autocomplete(input);
	autocomplete.addListener('place_changed', function () {
		var place = autocomplete.getPlace();
		$('#add_project_lat').val(place.geometry['location'].lat());
		$('#add_project_long').val(place.geometry['location'].lng());
	});
}

/*step 1*/
var typingTime; 

//on keyup, start the countdown
$(document).on('keyup', '#check_domain_url',function (e) {
	e.preventDefault();
	clearTimeout(typingTime);
	if($(this).val()!=''){
		$('.refresh-icon').css('display','block');
		$('.add-new-cross , .add-new-check').css('display','none');
		typingTime = setTimeout(checkdnsrr($(this).val()), 500);
	}
});

//on keydown, clear the countdown 
$(document).on('keydown', '#check_domain_url',function () {
	clearTimeout(typingTime);
});


//user is "finished typing," do something
function checkdnsrr (searchKey) {
	
	if (!is_url(searchKey)) {
		$('.domain_url').addClass('error');
		$('#domain_url_error').parent().css('display','block');
		$('.domain_url').removeClass('valid-input');
		document.getElementById('domain_url_error').innerHTML = 'Not a Valid url.';
		setTimeout(function(){
			$('.refresh-icon').css('display','none');
		},1000);
		return false;
	} 
	$('.refresh-icon').css('display','block');
	$('.add-new-cross , .add-new-check').css('display','none');
	$('.domain_url').removeClass('error');
	$('#domain_url_error').parent().css('display','none');
	document.getElementById('domain_url_error').innerHTML = '';

	$.ajax({
		type:'GET',
		data:{search:searchKey},
		dataType:'json',
		url:BASE_URL +'/checkdnsrr',
		success:function(result){
			$('.refresh-icon').css('display','none');
			$('.add-new-cross , .add-new-check').css('display','none');
			if(result['status'] == 'error'){
				$('.add-new-cross').css('display','block');
				$('.add-new-check').css('display','none');
				$('.domain_url').addClass('error');
				$('.domain_url').removeClass('valid-input');
				$('#domain_url_error').parent().css('display','block');
				document.getElementById('domain_url_error').innerHTML = result['message'];
			}else{
				$('.add-new-check').css('display','block');
				$('.add-new-cross').css('display','none');
				$('.domain_url').removeClass('error');
				$('.domain_url').addClass('valid-input');
				$('#domain_url_error').parent().css('display','none');
				document.getElementById('domain_url_error').innerHTML = '';
			}
		}
	});

}




$(document).on('click','#submit_project_info',function(e){
	e.preventDefault();

	var dashboardType = [];
	var project_name = $('.project_name').val();
	var domain_url = $('.domain_url').val();
	var regional_db = $('#regional_db').val();
	var url_type = $('.addNew_url_type').text();

	$('input:checkbox.dashboardType').each(function () {
		if(this.checked){
			dashboardType.push($(this).val());
		}
	});

	document.getElementById('project_name_error').innerHTML = '';
	document.getElementById('domain_url_error').innerHTML = '';
	document.getElementById('regional_db_error').innerHTML = '';
	document.getElementById('dashboardType_error').innerHTML = '';

	if (project_name == '') {
		$('.project_name').addClass('error');
	}else{
		$('.project_name').removeClass('error');
	}

	if (domain_url == '') {
		$('.domain_url').addClass('error');
	} else if (!is_url(domain_url)) {
		$('.domain_url').addClass('error');
		$('#domain_url_error').parent().css('display','block');
		document.getElementById('domain_url_error').innerHTML = 'Not a Valid url.';
		return false;
	}else{
		$('.domain_url').removeClass('error');
		$('.errorStyle').css('display','none');
		document.getElementById('domain_url_error').innerHTML = '';
	}

	if (regional_db == '') {
		$('.regional_db').addClass('error');
	}  else{
		$('.regional_db').removeClass('error');
	}

	if (dashboardType == '') {
		$('.dashboardType').addClass('error');
	}else{
		$('.dashboardType').removeClass('error');
	}




	if (project_name != '' && domain_url !== '' && regional_db != '' && dashboardType !='') {
		$(this).attr('disabled','disabled');
		submitForm();
		return false;
	}

});

function is_url(str)
{
	regexp = /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
	if (regexp.test(str))
	{
		return true;
	} else
	{
		return false;
	}
}


function submitForm() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: "POST",
		url: BASE_URL +"/store_project_info",
		cache: false,
		data: $('form#create-project-info').serialize(),
		dataType: 'json',
		success: function (response) {


			if(response['status'] == 'error' && response['field'] == 'project_name'){
				$('#submit_project_info').removeAttr('disabled');
				$('#project_name_error').parent().css('display','block');
				document.getElementById('project_name_error').innerHTML = response['message'];
				// return false;
			}

			if(response['status'] == 'error' && response['field'] == 'domain_url'){
				$('#submit_project_info').removeAttr('disabled');
				$('.domain_url').addClass('error');
				$('#domain_url_error').parent().css('display','block');
				document.getElementById('domain_url_error').innerHTML = response['message'];
				// return false;
			}

			if(response['status'] == 'error' && response['field'] == 'general'){
				$('#submit_project_info').removeAttr('disabled');
				Command: toastr["error"](response['message']);
				return false;
			}
			if(response['status'] == 'success'){
				if(response['actual'] == 1){
					complete_steps(response['last_id'],response['step']);
					// $("#defaultCampaignList").load(location.href + " #defaultCampaignList");
					sidebar_section($('.campaign_id').val());
				}
				$('.last-project-id').val(response['last_id']);
				$('.analytics_campaign_id').val(response['last_id']);
				$('.campaign_id').val(response['last_id']);
				$('#project-info').css('display','none');
				$('#integrations').css('display','block');

				//display domain url value
				$('#domain_url_value').html('('+response['domain_url_value']+')');

				//display connect pop-up conditionally
				var str = response['dashboards'];
				if(str.indexOf("1") == '-1'){
					$('#addProject-search-console').css('display','none');
					$('#addProject-analytics').css('display','none');
				}else{
					$('#addProject-search-console').css('display','flex');
					$('#addProject-analytics').css('display','flex');
				}

				if(str.indexOf("2") == '-1'){
					$('#addProject-adwords').css('display','none');
				}else{
					$('#addProject-adwords').css('display','flex');
				}

				if(str.indexOf("3") == '-1'){	
					$('#addProject-gmb').css('display','none');	
				}else{	
					$('#addProject-gmb').css('display','flex');	
				}

				if(str.indexOf("4") == '-1'){
					$('#addFacebook-social').css('display','none');
				}else{
					$('#addFacebook-social').css('display','flex');
				}

				//active integration tab
				$('#add-new-step1').removeClass('active');
				$('#add-new-step1').addClass('complete');
				$('#add-new-step2').addClass('active');

			}
		}       
	});
}

$(document).on('click','#store_integrations',function(e){
	e.preventDefault();
	var last_id = $('.last-project-id').val();
	$('.last-project-id-settings').val(last_id);
	$('#integrations').css('display','none');
	$('#rank-tracking-settings').css('display','block');

	//active rank tracking tab
	$('#add-new-step2').addClass('active complete');
	$('#add-new-step3').addClass('active');
});

$(document).on('click','#store_rank_tracking_settings',function(e){
	e.preventDefault();
	var projectId = $('.last-project-id-settings').val();
	if($('#add_project_locations').val() != ''){
		var lat = $('#add_project_lat').val();
		var long = $('#add_project_long').val();
		if(lat == '' || long == ''){
			$('.address_location').addClass('error');
			$('.errorStyle').css('display','block');
			document.getElementById('address_location_error').innerHTML = 'Invalid location';
			return false;
		}else{
			$('.address_location').removeClass('error');
			$('.errorStyle').css('display','none');
			document.getElementById('address_location_error').innerHTML = '';

		}
	}
	$(this).attr('disabled','disabled');
	$.ajax({
		url:BASE_URL +'/ajax_store_ranking_details',
		type:'POST',
		data:$('form#create-rank-tracking-settings').serialize(),
		dataType:'json',
		success:function(response){
			logfacebookData(projectId);
			if(response['status'] == 1){
				complete_steps(response['last_id'],response['step']);
				window.location.href = BASE_URL +'/dashboard';
			}else{
				Command: toastr["error"](response['message']);
			}
		}
	});
});

function complete_steps(project_id,steps){
	$.ajax({
		type:'POST',
		data:{project_id,steps,_token:$('meta[name="csrf-token"]').attr('content')},
		url:BASE_URL+'/complete_steps',
		success:function(result){

		}
	});
}

function logfacebookData(campaign_id){
	$.ajax({
		type:'GET',
		url:BASE_URL+'/log_facebook_data',
		data:{campaign_id:campaign_id},
		success:function(logresponse){
			
		}
	});
}

// analytics section
$('#analytics_existing_emails').on('show.bs.select', function (e, clickedIndex, isSelected, previousValue) {	
	$('#analytics_existing_emails').removeClass('addAppend');
});

$(document).on('click','#refresh_analytics_account_addNew',function(e){
	$(this).addClass('refresh-gif');
	$('#save_analytics_account').attr('disabled','disabled');
	$('#add_new_analytics_account').attr('disabled','disabled');
	$('.popup-inner').css('overflow','hidden');

	var email = $('#analytics_existing_emails').val();
	var campaign_id = $('.analytics_campaign_id').val();

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
					data:{email:$('#analytics_existing_emails').val(),campaign_id:$('.analytics_campaign_id').val()},
					success:function(result){
						$('#analytics_accounts').html(result);
						$('.selectpicker').selectpicker('refresh');
						var li    = '<option value="">Select Property</option>';
						$('#analytics_property').html(li);
						var li    = '<option value="">Select View</option>';
						$('#analytics_view').html(li);
					}
				});


				$('.analytics-progress-loader').addClass('complete');

				$('#show_analytics_last_time').parent().removeClass('error');
				$('#show_analytics_last_time').parent().removeClass('yellow');
				$('#show_analytics_last_time').parent().addClass('green');
				$('#show_analytics_last_time').parent().css('display','block');
				document.getElementById('show_analytics_last_time').innerHTML = response['message'];
				
			}

			if(response['status'] == 0){
				$('#show_analytics_last_time').parent().removeClass('yellow');
				$('#show_analytics_last_time').parent().removeClass('green');
				$('#show_analytics_last_time').parent().addClass('error');
				$('#show_analytics_last_time').parent().css('display','block');
				document.getElementById('show_analytics_last_time').innerHTML = response['message'];
			}

			if(response['status'] == 2){
				$('#show_analytics_last_time').parent().removeClass('yellow');
				$('#show_analytics_last_time').parent().removeClass('green');
				$('#show_analytics_last_time').parent().addClass('error');
				$('#show_analytics_last_time').parent().css('display','block');
				document.getElementById('show_analytics_last_time').innerHTML = response['message'];

			}

			setTimeout(function(){
				$('#refresh_analytics_account_addNew').removeClass('refresh-gif');
				$('#save_analytics_account').removeAttr('disabled','disabled');
				$('#add_new_analytics_account').removeAttr('disabled','disabled');

				$('.analytics-progress-loader').css('display','none');
				$('.analytics-progress-loader').removeClass('complete');
				$('.popup-inner').css('overflow','auto');
			}, 1000);
		}
	});
});

$(document).on('change','#analytics_existing_emails',function(e){
	e.preventDefault();
	$('#analytics_existing_emails').removeClass('addAppend');
	var email = $(this).val();
	var campaign_id = $('.analytics_campaign_id').val();

	fetch_last_updated(email,campaign_id,'google_analytics');
	$('.analytics_refresh_div').css('display','block');
	disableSelectPicker('#analytics_accounts','.analytic-account-addNew-loader');
	$('.add_nw_ecommerce_goals').prop('checked',false);

	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_analytics_accounts',
		data:{email,campaign_id},

		success:function(response){
			enableSelectPicker('#analytics_accounts','.analytic-account-addNew-loader');
			$('#analytics_accounts').html(response);
			$('#analytics_property').html('<option value="">Select Property</option>');
			$('#analytics_view').html('<option value="">Select View</option>');
			$('.selectpicker').selectpicker('refresh');
		}
	});
});

$(document).on('change','#analytics_accounts',function(e){

	e.preventDefault();
	var account_id = $(this).val();
	var campaign_id = $('.analytics_campaign_id').val();
	disableSelectPicker('#analytics_property','.analytic-property-addNew-loader');

	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_analytics_property',
		data:{account_id,campaign_id},
		success:function(response){
			enableSelectPicker('#analytics_property','.analytic-property-addNew-loader');
			$('#analytics_property').html(response);
			$('#analytics_view').html('<option value="">Select View</option>');
			$('.selectpicker').selectpicker('refresh');
		}
	});
});


$(document).on('change','#analytics_property',function(e){
	e.preventDefault();
	var property_id = $(this).val();
	var campaign_id = $('.analytics_campaign_id').val();
	disableSelectPicker('#analytics_view','.analytic-view-addNew-loader');

	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_analytics_view',
		data:{property_id,campaign_id},
		success:function(response){
			enableSelectPicker('#analytics_view','.analytic-view-addNew-loader');
			$('#analytics_view').html(response);
			$('.selectpicker').selectpicker('refresh');
		}
	});
});

$(document).on('click','#save_analytics_account',function(e){
	var campaign_id = $('.analytics_campaign_id').val();
	var email = $('#analytics_existing_emails').val();
	var account = $('#analytics_accounts').val();
	var property = $('#analytics_property').val();
	var view = $('#analytics_view').val();
	var e_com = $('.add_nw_ecommerce_goals').prop('checked');

	if(email == ''){
		$('#analytics_existing_emails').parent().addClass('error');
	}else{
		$('#analytics_existing_emails').parent().removeClass('error');
	}
	if(account == ''){
		$('#analytics_accounts').parent().addClass('error');
	}else{
		$('#analytics_accounts').parent().removeClass('error');
	}
	if(property == ''){
		$('#analytics_property').parent().addClass('error');
	}else{
		$('#analytics_property').parent().removeClass('error');
	}
	if(view == ''){
		$('#analytics_view').parent().addClass('error');
	}else{
		$('#analytics_view').parent().removeClass('error');
	}
	

	if(email != '' && account !='' && property !='' && view !=''){
		$(this).attr('disabled','disabled');
		$('.analytics-progress-loader').css('display','block');	
		$('.popup-inner').css('overflow','hidden');
		$.ajax({
			type:'POST',
			url:BASE_URL + '/ajax_save_new_project_analytics_data',
			data:{campaign_id,email,account,property,view,_token:$('meta[name="csrf-token"]').attr('content'),e_com},
			success:function(response){
				if(response['status'] == 'success'){
					$('.analytics-progress-loader').addClass('complete');
					Command: toastr["success"]('Google Analytics connected successfully');
					$("#analytics_close").trigger("click");
					$("body").removeClass("popup-open");
					$('.default_analytics').css('display','none');
					$('#analytics_account').html(response['account']);
					$('#analyticsproperty').html(response['property']);
					$('#analyticsprofile').html(response['view']);
					$('.analytics_connected').css('display','block');
					complete_steps(response['project_id'],response['step']);
				} 
				if(response['status'] == 'google-error') {
					Command: toastr["error"](response['message']);
				}

				if(response['status'] == 'error') {
					Command: toastr["error"]('Please try again getting error');
				}

				$('#save_analytics_account').attr('disabled','disabled');

				setTimeout(function(){
					$('.analytics-progress-loader').css('display','none');
					$('.analytics-progress-loader').removeClass('complete');
					$('.popup-inner').css('overflow','auto');
				}, 1000);
			}
		});
	}
});

$(document).on('click','#disconnectNewAnalytics',function(e){
	e.preventDefault();
	if($('.last-project-id').val() != ''){
		$.ajax({
			type:'POST',
			url:BASE_URL +'/ajax_disconnect_analaytics',
			data:{request_id:$('.last-project-id').val(),_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 'success')
				{
					$('#new-integration-section').load(location.href + ' #integration-list');
				}else{
					Command: toastr["error"]('Error!! Please try again!');
				}
			}
		});
	}
});

//search console section

$('#search_console_existing_emails').on('show.bs.select', function (e, clickedIndex, isSelected, previousValue) {	
	$('#search_console_existing_emails').removeClass('addSearchAppend');
});

$(document).on('change','#search_console_existing_emails',function(e){
	e.preventDefault();
	$('#search_console_existing_emails').removeClass('addSearchAppend');
	var email = $(this).val();
	var campaign_id = $('.analytics_campaign_id').val();

	fetch_last_updated(email,campaign_id,'search_console');
	$('.search_console_refresh_div').css('display','block');
	disableSelectPicker('#search_console_urlaccounts','.sc-addNew-loader');

	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_console_urls',
		data:{email,campaign_id},
		success:function(response){
			enableSelectPicker('#search_console_urlaccounts','.sc-addNew-loader');
			$('#search_console_urlaccounts').html(response);
			$('.selectpicker').selectpicker('refresh');
		}
	});
});

$(document).on('click','#refresh_search_console_addNew',function(e){
	e.preventDefault();

	$(this).addClass('refresh-gif');
	$('#save_console_account').attr('disabled','disabled');
	$('#searchConsoleAddBtn').attr('disabled','disabled');
	$('.popup-inner').css('overflow','hidden');

	var email = $('#search_console_existing_emails').val();
	var campaign_id = $('.analytics_campaign_id').val();
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
					data:{email:$('#search_console_existing_emails').val(),campaign_id:$('.analytics_campaign_id').val()},
					success:function(result){
						$('#search_console_urlaccounts').html(result);
						$('.selectpicker').selectpicker('refresh');
					}
				});


				$('.searchConsole-progress-loader').addClass('complete');

				$('#refresh_search_console_addNew').removeClass('refresh-gif');
				$('#save_console_account').removeAttr('disabled','disabled');
				$('#searchConsoleAddBtn').removeAttr('disabled','disabled');


				$('#show_search_console_last_time').parent().removeClass('error');
				$('#show_search_console_last_time').parent().removeClass('yellow');
				$('#show_search_console_last_time').parent().addClass('green');
				$('#show_search_console_last_time').parent().css('display','block');
				document.getElementById('show_search_console_last_time').innerHTML = response['message'];
				
			}

			if(response['status'] == 0){
				$('#refresh_search_console_addNew').removeClass('refresh-gif');
				$('#save_console_account').removeAttr('disabled','disabled');
				$('#searchConsoleAddBtn').removeAttr('disabled','disabled');
				
				$('#show_search_console_last_time').parent().removeClass('yellow');
				$('#show_search_console_last_time').parent().removeClass('green');
				$('#show_search_console_last_time').parent().addClass('error');
				$('#show_search_console_last_time').parent().css('display','block');
				document.getElementById('show_search_console_last_time').innerHTML = response['message'];
			}

			if(response['status'] == 2){
				$('#refresh_search_console_addNew').removeClass('refresh-gif');
				$('#save_console_account').removeAttr('disabled','disabled');
				$('#searchConsoleAddBtn').removeAttr('disabled','disabled');

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

$(document).on('click','#save_console_account',function(e){
	e.preventDefault();
	var campaign_id = $('.analytics_campaign_id').val();
	var email = $('#search_console_existing_emails').val();
	var account = $('#search_console_urlaccounts').val();

	
	
	if(email == ''){
		$('#search_console_existing_emails').parent().addClass('error');
	}else{
		$('#search_console_existing_emails').parent().removeClass('error');
	}
	if(account == ''){
		$('#search_console_urlaccounts').parent().addClass('error');
	}else{
		$('#search_console_urlaccounts').parent().removeClass('error');
	}



	if(email != '' && account !=''){
		$(this).attr('disabled','disabled');
		$('.searchConsole-progress-loader').css('display','block');
		$('.popup-inner').css('overflow','hidden');
		$.ajax({
			type:'POST',
			url:BASE_URL + '/ajax_save_new_project_console_data',
			data:{campaign_id,email,account,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if (response['status'] == 'success') {
					$('.searchConsole-progress-loader').addClass('complete');
					Command: toastr["success"]('Console Account Connected successfully!');
					$("#console_close").trigger("click");
					$("body").removeClass("popup-open");
					$('.console_default').css('display','none');
					$('#console_connected_email').html(response['email']);
					$('#console_account').html(response['value']);
					$('.console_connected').css('display','block');
					complete_steps(response['project_id'],response['step']);
				}
				else if (response['status'] == 'google-error') {
					Command: toastr["error"](response['message']);
				} else {
					Command: toastr["error"]('Please try again getting error');
				}
				$('#save_console_account').attr('disabled','disabled');

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

$(document).on('click','#disconnectNewConsole',function(e){
	e.preventDefault();
	if($('.last-project-id').val() != ''){
		$.ajax({
			type:'POST',
			url:BASE_URL +'/ajax_disconnect_console',
			data:{request_id:$('.last-project-id').val(),_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 'success')
				{
					$('#new-integration-section').load(location.href + ' #integration-list');
				}else{
					Command: toastr["error"]('Error!! Please try again!');
				}
			}
		});
	}
});

//adwords section
$(document).on('click','#refresh_ppc_account_addNew',function(e){
	$(this).addClass('refresh-gif');
	$('#save_adwords_account').attr('disabled','disabled');
	$('#add_new_adwords_account').attr('disabled','disabled');

	$('.popup-inner').css('overflow','hidden');

	var email = $('#adwords_existing_emails').val();
	var campaign_id = $('.analytics_campaign_id').val();
	$.ajax({
		type:'GET',
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
					data:{email:$('#adwords_existing_emails').val(),campaign_id:$('.analytics_campaign_id').val()},
					success:function(response){
						$('#adwords_accounts').html(response);
						$('.selectpicker').selectpicker('refresh');
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
				$('#refresh_ppc_account, #refresh_ppc_account_addNew').removeClass('refresh-gif');
				$('.ppc-progress-loader').css('display','none');
				$('.ppc-progress-loader').removeClass('complete');
				$('.popup-inner').css('overflow','auto');
				$('#save_adwords_account').removeAttr('disabled','disabled');
				$('#add_new_adwords_account').removeAttr('disabled','disabled');
			}, 1000);
		}
	});
});

$('#adwords_existing_emails').on('show.bs.select', function (e, clickedIndex, isSelected, previousValue) {	
	$('#adwords_existing_emails').removeClass('addAdsAppend');
});

$(document).on('change','#adwords_existing_emails',function(e){
	e.preventDefault();
	$(this).removeClass('addAdsAppend');
	var email = $(this).val();
	var campaign_id = $('.analytics_campaign_id').val();

	fetch_last_updated(email,campaign_id,'ppc');
	$('.ppc_refresh_div').css('display','block');
	disableSelectPicker('#adwords_accounts','.adwords-addNew-loader');

	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_adwords_accounts',
		data:{email,campaign_id},
		success:function(response){
			enableSelectPicker('#adwords_accounts','.adwords-addNew-loader');
			$('#adwords_accounts').html(response);
			$('.selectpicker').selectpicker('refresh');
		}
	});
});

$(document).on('click','#save_adwords_account',function(e){
	e.preventDefault();
	var email = $('#adwords_existing_emails').val();
	var account = $('#adwords_accounts').val();
	var campaign_id = $('.analytics_campaign_id').val();

	


	if(email == ''){
		$('#adwords_existing_emails').parent().addClass('error');
	}else{
		$('#adwords_existing_emails').parent().removeClass('error');
	}
	if(account == ''){
		$('#adwords_accounts').parent().addClass('error');
	}else{
		$('#adwords_accounts').parent().removeClass('error');
	}

	if(email != '' && account !=''){
		$('.ppc-progress-loader').css('display','block');
		$('.popup-inner').css('overflow','hidden');
		$.ajax({
			type:'POST',
			url:BASE_URL + '/ajax_save_new_project_adwords_data',
			data:{campaign_id,email,account,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if (response['status'] == 'success') {
					$('.ppc-progress-loader').addClass('complete');
					Command: toastr["success"]('Adwords Account Connected successfully!');
					$("#adwords_close").trigger("click");
					$("body").removeClass("popup-open");
					$('.default_adwords').css('display','none');
					$('#adwords_connected_email').html(response['email']);
					$('#adwords_account').html(response['value']);
					$('.adword_connected').css('display','block');
					complete_steps(response['project_id'],response['step']);

				} else {
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


$(document).on('click','#disconnectNewAdwords',function(e){
	e.preventDefault();
	if($('.last-project-id').val() != ''){
		$.ajax({
			type:'POST',
			url:BASE_URL +'/ajax_disconnect_adwords',
			data:{request_id:$('.last-project-id').val(),_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 'success')
				{
					$('#integration-list').load(location.href + ' #addProject-adwords');
				}else{
					Command: toastr["error"]('Error!! Please try again!');
				}
			}
		});
	}
});

//pop-up buttons

$(document).on('click','.searchConsoleAddBtn',function(){
	var campaignId = $('.analytics_campaign_id').val();
	var currentRoute = $('.currentRoute').val();
	$('#search_console_existing_emails').addClass('addSearchAppend');
	var link = BASE_URL +'/connect_search_console?campaignId='+campaignId+'&redirectPage='+currentRoute;
	myPopup(link,"web","500","500");
});

$(document).on('click','.analyticsAddBtn',function(){
	var campaignId = $('.analytics_campaign_id').val();
	var currentRoute = $('.currentRoute').val();
	$('#analytics_existing_emails').addClass('addAppend');
	var link = BASE_URL +'/connect_google_analytics?campaignId='+campaignId+'&provider=google&redirectPage='+currentRoute;
	myPopup(link,"web","500","500");
	
});



$(document).on('click','.AdwordsAddBtn',function(){
	var campaignId = $('.analytics_campaign_id').val();
	var currentRoute = $('.currentRoute').val();
	$('#adwords_existing_emails').addClass('addAdsAppend');
	// var link = BASE_URL +'/connect_google_ads?campaignId='+campaignId+'&redirectPage='+currentRoute;
	var link = BASE_URL +'/ppc/connect?campaignId='+campaignId+'&redirectPage='+currentRoute;
	myPopup(link,"web","500","500");
});

$(document).on('click','#previous_integrations',function(e){
	$('#integrations').css('display','none');
	$('#project-info').css('display','block');
	$('#submit_project_info').removeAttr('disabled');
	var last_id = $('.last-project-id').val();
	$('.existed_id').val(last_id);


	//active project info tab
	$('#add-new-step2').removeClass('active complete');
	$('#add-new-step1').removeClass('complete');
	$('#add-new-step1').addClass('active');
});

function myPopup(myURL, title, myWidth, myHeight) {
	var left = (screen.width - myWidth) / 2;
	var top = (screen.height - myHeight) / 4;
	window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
}

setInterval(function(){
	if($('#analytics_existing_emails').hasClass('addAppend')){
			getAnalyticsAccounts();
	}
}, 3000);

function getAnalyticsAccounts(){
	$.ajax({
		url:BASE_URL+'/ajax_google_analytics_accounts',
		data:{user_id:$('.user_id').val()},
		type:'GET',
		success:function(response){
			$('.selectpicker').selectpicker('refresh');
			$('#analytics_existing_emails').html(response);
			$('.selectpicker').selectpicker('refresh');
		}
	})
}


setInterval(function(){
	if($('#search_console_existing_emails').hasClass('addSearchAppend')){
			getSearchConsoleAccounts();
	}
}, 3000);


function getSearchConsoleAccounts(){
	$.ajax({
		url:BASE_URL+'/ajax_google_cnsole_accounts',
		data:{user_id:$('.user_id').val()},
		type:'GET',
		success:function(response){
			$('.selectpicker').selectpicker('refresh');
			$('#search_console_existing_emails').html(response);
			$('.selectpicker').selectpicker('refresh');
		}
	})
}

setInterval(function(){
	if($('#adwords_existing_emails').hasClass('addAdsAppend')){
		setTimeout(function(){
			getAdwordsAccounts();
		}, 5000);
	}
}, 1000);

function getAdwordsAccounts(){
	$.ajax({
		url:BASE_URL+'/ajax_adwords_accounts',
		data:{user_id:$('.user_id').val()},
		type:'GET',
		success:function(response){
			$('.selectpicker').selectpicker('refresh');
			$('#adwords_existing_emails').html(response);
			$('.selectpicker').selectpicker('refresh');
		}
	})
}

//step 3
$(document).on('click','#previous_stores',function(){
	$('#rank-tracking-settings').css('display','none');
	$('#integrations').css('display','block');
	var last_id = $('.last-project-id-settings').val();
	$('.last-project-id').val(last_id);

	//active integration tab
	$('#add-new-step2').addClass('active');
	$('#add-new-step2').removeClass('complete');
	$('#add-new-step3').removeClass('active complete');
});

//cancel and delete project

$(document).on('click','#cancel_project',function(){
	var last_id =$('.last-project-id').val();
	$('.lastprojectid').val(last_id);
});
$(document).on('click','#DeleteAddProject',function(e){
	e.preventDefault();
	var last_id = $('.lastprojectid').val();
	console.log(last_id);
	if(last_id!=''){
		$.ajax({
			type:'GET',
			url:BASE_URL +'/ajax_delete_added_project',
			data:{last_id},
			dataType:'json',
			success:function(response){
				if(response['status'] == 1){
					location.reload();
				}
			}
		});
	}
});

/*
gmb connection*/
$(document).on('click','.settings_GmbAddBtn',function(){	
	var campaignId = $('.analytics_campaign_id').val();	
	var currentRoute = $('.currentRoute').val();	
	$('#settings_gmb_existing_emails').addClass('addgmbAppend');	
	var link = BASE_URL +'/gmb/connect?campaignId='+campaignId+'&redirectPage='+currentRoute;	
	console.log(link);	
	connectPopup(link,"web","500","500");	
});	

$(document).on('click','#add_new_save_gmb_account',function(e){	
	e.preventDefault();	
	var email = $('#settings_gmb_existing_emails').val();	
	var account = $('#settings_gmb_accounts').val();	
	var campaign_id =  $('.analytics_campaign_id').val();
	console.log('campaign_id' + campaign_id);
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
		$.ajax({	
			type:'POST',	
			url:BASE_URL + '/ajax_update_gmb_data',	
			data:{campaign_id,email,account,_token:$('meta[name="csrf-token"]').attr('content')},	
			dataType:'json',	
			success:function(response){	
				if (response['status'] == 'success') {	
					if(response['type'] == 'new'){	
						$.ajax({	
							type:'GET',	
							url:BASE_URL+'/log_gmb_data',	
							data:{campaign_id:$('.analytics_campaign_id').val()},	
							successs:function(response){	
								console.log(response);	
							}	
						});	
					}	
					Command: toastr["success"]('GMB Account Connected successfully! Fetching Data it may take some time.');	
					$("#project_setting_gmb_close").trigger("click");	
					$("body").removeClass("popup-open");	
					$('.default_gmb').hide();	
					$('.gmb_connected').show();	
					$('#gmb_connected_email').html(response['email']);	
					$('#gmb_account').html(response['accountName']);	
					/*document.getElementById("gmb_setting_popup").reset();*/	
					$("#integrationTab").load(location.href + " #project-integration-list");	
				} else {	
					$('#settings_save_adwords_account').removeAttr('disabled');	
					Command: toastr["error"]('Please try again getting error');	
				}	
			}	
		});	
	}	
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

/*GMB refresh*/
$(document).on('click','#refresh_gmb_account_addNew',function(e){
	$(this).addClass('refresh-gif');
	$('#settings_save_gmb_account').attr('disabled','disabled');
	$('#settings_add_new_gmb_account').attr('disabled','disabled');
	$('.popup-inner').css('overflow','hidden');
	var email = $('#settings_gmb_existing_emails').val();
	var campaign_id = $('.analytics_campaign_id').val();
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
					data:{email:$('#settings_gmb_existing_emails').val(),campaign_id:$('.analytics_campaign_id').val()},
					success:function(result){
						$('#settings_gmb_accounts').html(result);
						$('.selectpicker').selectpicker('refresh');
					}
				});


				$('.gmb-progress-loader').addClass('complete');
			
				$('#show_gmb_last_time').parent().removeClass('yellow');
				$('#show_gmb_last_time').parent().removeClass('error');
				$('#show_gmb_last_time').parent().addClass('green');
				$('#show_gmb_last_time').parent().css('display','block');
				document.getElementById('show_gmb_last_time').innerHTML = response['message'];
				
			}

			if(response['status'] == 0){
				$('#show_gmb_last_time').parent().removeClass('yellow');
				$('#show_gmb_last_time').parent().removeClass('green');
				$('#show_gmb_last_time').parent().addClass('error');
				$('#show_gmb_last_time').parent().css('display','block');
				document.getElementById('show_gmb_last_time').innerHTML = response['message'];
			}

			if(response['status'] == 2){
				$('#show_gmb_last_time').parent().removeClass('yellow');
				$('#show_gmb_last_time').parent().removeClass('green');
				$('#show_gmb_last_time').parent().addClass('error');
				$('#show_gmb_last_time').parent().css('display','block');
				document.getElementById('show_gmb_last_time').innerHTML = response['message'];
			}

			setTimeout(function(){
				$('#refresh_gmb_account_addNew').removeClass('refresh-gif');
				$('#settings_save_gmb_account').removeAttr('disabled','disabled');
				$('#settings_add_new_gmb_account').removeAttr('disabled','disabled');


				$('.gmb-progress-loader').css('display','none');
				$('.gmb-progress-loader').removeClass('complete');
				$('.popup-inner').css('overflow','auto');
			}, 1000);
		}
	});
});

$(".domain-dropDownBox>button").on("click", function(){
  $(".domain-dropDownMenu").toggleClass("show");
});

$(document).on('click','.addNew-url-type-list',function(e){
  var selected = $(this).find('h6').text();
  $('.addNew_url_type').text(selected);
  $('#addNew_url_type_input').val(selected);
  $('#addNew-url-dropDownMenu').removeClass('show');
  $('.addNew-url-type-ul li').removeClass('active');
  $(this).addClass('active');
  e.stopPropagation();
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


/*google analytics 4*/
$(document).on('change','#ga4_addNew_existing_emails',function(e){
	e.preventDefault();
	//$('#ga4_addNew_existing_emails').removeClass('addAppend');
	var email = $(this).val();
	var campaign_id = $('.analytics_campaign_id').val();

	fetch_last_updated(email,campaign_id,'ga4');
	$('.ga4_refresh_div').css('display','block');
	disableSelectPicker('#ga4_addNew_accounts','.ga4-account-loader');

	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_ga4_accounts',
		data:{email,campaign_id},
		success:function(response){
			enableSelectPicker('#ga4_addNew_accounts','.ga4-account-loader');
			$('#ga4_addNew_accounts').html(response);
			$('#ga4_addNew_property').html('<option value="">Select Property</option>');
			$('.selectpicker').selectpicker('refresh');
		}
	});
});

$(document).on('change','#ga4_addNew_accounts',function(e){
	e.preventDefault();
	var account_id = $(this).val();
	var campaign_id = $('.analytics_campaign_id').val();
	disableSelectPicker('#ga4_addNew_property','.ga4-property-loader');

	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_ga4_properties',
		data:{account_id,campaign_id},
		success:function(response){
			enableSelectPicker('#ga4_addNew_property','.ga4-property-loader');
			$('#ga4_addNew_property').html(response);
			$('.selectpicker').selectpicker('refresh');
		}
	});
});

$(document).on('click','#addNew_connect_ga4',function(){
	var campaignId = $('.analytics_campaign_id').val();
	var currentRoute = $('.currentRoute').val();
	$('#ga4_addNew_existing_emails').addClass('addAppend');
	var link = BASE_URL +'/connect_google_analytics_4?campaignId='+campaignId+'&provider=ga4&redirectPage='+currentRoute;
	myPopup(link,"web","500","500");	
});

$('#ga4_addNew_existing_emails').on('show.bs.select', function (e, clickedIndex, isSelected, previousValue) {	
	$('#ga4_addNew_existing_emails').removeClass('addAppend');
});

setInterval(function(){
	if($('#ga4_addNew_existing_emails').hasClass('addAppend')){
		getAnalytics4NewProjectEmails();
	}
}, 3000);

function getAnalytics4NewProjectEmails(){
  $.ajax({
    url:BASE_URL+'/ajax_get_ga4_emails',
    data:{user_id:$('.user_id').val()},
    type:'GET',
     dataType:'json',
    success:function(response){
      $('#ga4_addNew_existing_emails').html(response);
      $('.selectpicker').selectpicker('refresh');
    }
  });
}

$(document).on('click','#save_addNew_ga4',function(e){
	e.preventDefault();
	  var campaign_id = $('.analytics_campaign_id').val();
	  var email = $('#ga4_addNew_existing_emails').val();
	  var account = $('#ga4_addNew_accounts').val();
	  var property = $('#ga4_addNew_property').val();

  	if(email == ''){
	   $('#ga4_addNew_existing_emails').parent().addClass('error');
	}else{
	   $('#ga4_addNew_existing_emails').parent().removeClass('error');
	}

	if(account == ''){
	   $('#ga4_addNew_accounts').parent().addClass('error');
	}else{
	   $('#ga4_addNew_accounts').parent().removeClass('error');
	}

	if(property == ''){
	   $('#ga4_addNew_property').parent().addClass('error');
	}else{
	   $('#ga4_addNew_property').parent().removeClass('error');
	}


 if(email != '' && account !=''){
  $('.ga4-progress-loader').css('display','block');
   $('.popup-inner').css('overflow','hidden');
   $(this).attr('disabled','disabled');
   $.ajax({
    type:'POST',
    url:BASE_URL + '/ajax_store_ga4_data',
    data:{campaign_id,email,account,property,_token:$('meta[name="csrf-token"]').attr('content')},
    dataType:'json',
    success:function(response){
    if (response['status'] == 'success') {
	    $('.ga4-progress-loader').addClass('complete');
	    Command: toastr["success"](response['message']);
	    $("#connectIntepopupGa4_close").trigger("click");
	    $("body").removeClass("popup-open");
	     
	    $('.default_analytics').css('display','none');
		$('#analytics4_connectedEmail').html(response['email']);
		$('#analytics4_account').html(response['account']);
		$('#analytics4property').html(response['property']);
		$('.analytics4_connected').css('display','block');
		complete_steps(response['project_id'],response['step']);

    } else if (response['status'] == 'google-error') {
    	Command: toastr["error"](response['message'] +' Try reconnecting your account.');
    } 
    else {
    	Command: toastr["error"]('Please try again getting error');
    }

    $('#save_addNew_ga4').removeAttr('disabled','disabled');
    
    setTimeout(function(){
      $('.ga4-progress-loader').css('display','none');
      $('.ga4-progress-loader').removeClass('complete');
      $('.popup-inner').css('overflow','auto');
    }, 100);
  }
});
 }
});

$(document).on('click','#disconnectNewAnalytics4',function(e){
	e.preventDefault();
	if($('.last-project-id').val() != ''){
		$.ajax({
			type:'POST',
			url:BASE_URL +'/ajax_disconnect_ga4',
			data:{request_id:$('.last-project-id').val(),_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 'success'){
					$('#new-integration-section').load(location.href + ' #integration-list');
				}else{
					Command: toastr["error"]('Error!! Please try again!');
				}
			}
		});
	}
});

$(document).on('click','#refresh_ga4_account_addNew',function(e){
	e.preventDefault();
	$(this).addClass('refresh-gif');

	$('#save_addNew_ga4').attr('disabled','disabled');
	$('.popup-inner').css('overflow','hidden');

	var email = $('#ga4_addNew_existing_emails').val();
	var campaign_id = $('.analytics_campaign_id').val();

	$('.ga4-progress-loader').css('display','block');

	$('#show_ga4_last_time').parent().removeClass('error ,green');
	$('#show_ga4_last_time').parent().addClass('yellow');
	$('#show_ga4_last_time').parent().css('display','block');

	document.getElementById('show_ga4_last_time').innerHTML = 'Fetching list of accounts.';

	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_refresh_ga4_list',
		data:{email,campaign_id},
		dataType:'json',
		success:function(response){

			if(response['status'] == 1){
				$.ajax({
					type:'GET',
					url:BASE_URL +'/ajax_get_ga4_accounts',
					data:{email},
					success:function(result){
						$('#ga4_addNew_accounts').html(result);
						$('.selectpicker').selectpicker('refresh');
						var li = '<option value="">Select Property</option>';
						$('#ga4_addNew_property').html(li);
					}
				});

				$('.ga4-progress-loader').addClass('complete');

				$('#refresh_ga4_account_addNew').removeClass('refresh-gif');
				$('#show_ga4_last_time').parent().removeClass('error , yellow');
				$('#show_ga4_last_time').parent().addClass('green');
				$('#show_ga4_last_time').parent().css('display','block');
				document.getElementById('show_ga4_last_time').innerHTML = response['message'];
			}

			if(response['status'] == 0){
				$('#refresh_ga4_account_addNew').removeClass('refresh-gif');				
				$('#show_ga4_last_time').parent().removeClass('yellow , green');
				$('#show_ga4_last_time').parent().addClass('error');
				$('#show_ga4_last_time').parent().css('display','block');
				document.getElementById('show_ga4_last_time').innerHTML = response['message'];
			}

			if(response['status'] == 2){
				$('#refresh_ga4_account_addNew').removeClass('refresh-gif');
				$('#show_ga4_last_time').parent().removeClass('yellow ,green');
				$('#show_ga4_last_time').parent().addClass('error');
				$('#show_ga4_last_time').parent().css('display','block');
				document.getElementById('show_ga4_last_time').innerHTML = response['message'];
			}

			setTimeout(function(){
				$('.ga4-progress-loader').css('display','none');
				$('.ga4-progress-loader').removeClass('complete');
				$('#save_addNew_ga4').removeAttr('disabled','disabled');
				$('.popup-inner').css('overflow','auto');
			}, 500);
		}
	});
});

$(document).on('click','#connect_addNew_ua ,#connect_addNew_ga4',function(e){
  e.preventDefault();
  $('#addNew_analytics_popup_close').trigger('click');
});