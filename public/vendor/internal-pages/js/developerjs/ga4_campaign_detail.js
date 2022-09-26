$(document).on('click','.detail_analytics4AddBtn',function(){
	var campaignId = $('.campaign_id').val();
	var currentRoute = $('.currentRoute').val();
	$('#detail_analytics4_existing_emails').addClass('addAnalytics4Detail');
	var link = BASE_URL +'/connect_google_analytics_4?campaignId='+campaignId+'&provider=ga4&redirectPage='+currentRoute;
	myDetailPopup(link,"web","500","500");
});

$('#detail_analytics4_existing_emails').on('show.bs.select', function (e, clickedIndex, isSelected, previousValue) {	
	$('#detail_analytics4_existing_emails').removeClass('addAnalytics4Detail');
});

setInterval(function(){
	if($('#detail_analytics4_existing_emails').hasClass('addAnalytics4Detail')){
		getAnalytics4CampaignEmails();
	}
}, 3000);

function getAnalytics4CampaignEmails(){
  $.ajax({
    url:BASE_URL+'/ajax_get_ga4_emails',
    data:{user_id:$('.user_id').val()},
    type:'GET',
    dataType:'json',
    success:function(response){
      $('.selectpicker').selectpicker('refresh');
      $('#detail_analytics4_existing_emails').html(response);
      $('.selectpicker').selectpicker('refresh');
    }
  });
}

$(document).on('change','#detail_analytics4_existing_emails',function(e){
	e.preventDefault();
	$('#detail_analytics4_existing_emails').removeClass('addAnalytics4Detail');
	var email = $(this).val();
	var campaign_id = $('.campaign_id').val();

	fetch_last_updated(email,campaign_id,'ga4');
	$('.ga4_detail_refresh_div').css('display','block');
	$('.analytic4-account-detail-loader').css('display','block');
	
	disableSelectPicker('#detail_analytics4_accounts');
	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_ga4_accounts',
		data:{email,campaign_id},
		success:function(response){
			enableSelectPicker('#detail_analytics4_accounts');
			$('#detail_analytics4_accounts').html(response);
			$('#detail_analytics4_property').html('<option value="">Select Property</option>');
			$('.selectpicker').selectpicker('refresh');

			$('.analytic4-account-detail-loader').css('display','none');
		}
	});
});

$(document).on('change','#detail_analytics4_accounts',function(e){
	e.preventDefault();
	var account_id = $(this).val();
	var campaign_id = $('.campaign_id').val();
	disableSelectPicker('#detail_analytics4_property');
	$('.analytic4-property-detail-loader').css('display','block');
	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_ga4_properties',
		data:{account_id,campaign_id},
		success:function(response){
			enableSelectPicker('#detail_analytics4_property');
			$('#detail_analytics4_property').html(response);
			$('.selectpicker').selectpicker('refresh');
			$('.analytic4-property-detail-loader').css('display','none');
		}
	});
});

$(document).on('click','#save_detail_analytics4',function(e){
	e.preventDefault();
	var backlinkSelectdChart = $('.backlinkSelectdChart').val();
	var currentUrl = $('.currentRoute').val();
	var campaign_id = $('.campaign_id').val();

	var email = $('#detail_analytics4_existing_emails').val();
	var account = $('#detail_analytics4_accounts').val();
	var property = $('#detail_analytics4_property').val();

	if(email == ''){
		$('#detail_analytics4_existing_emails').parent().addClass('error');
	}else{
		$('#detail_analytics4_existing_emails').parent().removeClass('error');
	}
	if(account == ''){
		$('#detail_analytics4_accounts').parent().addClass('error');
	}else{
		$('#detail_analytics4_accounts').parent().removeClass('error');
	}
	if(property == ''){
		$('#detail_analytics4_property').parent().addClass('error');
	}else{
		$('#detail_analytics4_property').parent().removeClass('error');
	}
	

	if(email != '' && account !='' && property !=''){

		$('.analytics4-progress-loader').css('display','block');
		$('.popup-inner').css('overflow','hidden');
		$(this).attr('disabled','disabled');

		$.ajax({
			type:'POST',
			url:BASE_URL + '/ajax_update_ga4_data',
			data:{campaign_id,email,account,property,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if(response['status'] == 'success'){
					$('.analytics4-progress-loader').addClass('complete');
					//Command: toastr["success"](response['message']);

					$("#detailAnalytics4_close").trigger("click");
					$("body").removeClass("popup-open");
					$('#preparingAnalytics4').css('display', 'block');
					$('body').addClass('popup-open');
					setTimeout(function() {
						$('#preparingAnalytics4').css('display', 'none');
						$("body").removeClass("popup-open");

						$("#SEO").load('/campaign_seo_content/' + campaign_id, function(responseTxt, statusTxt, xhr){
						if(statusTxt == "success")
							if ($('#SEO').find('.main-data').find('#seo_add').length == 0) {
								setTimeout(function () {
									seo_Scripts(campaign_id,currentUrl,'',$('.backlinkSelectdChart').val());		
								},1000);
							}
							
							if(statusTxt == "error")
								console.log("Error: " + xhr.status + ": " + xhr.statusText);
						});
					},10000);
				} 

				if(response['status'] == 'google-error') {
					Command: toastr["error"](response['message']);
				}

				if(response['status'] == 'error') {
					Command: toastr["error"](response['message']);
				}

				$('#save_detail_analytics4').removeAttr('disabled','disabled');
				
				setTimeout(function(){
					$('.analytics4-progress-loader').css('display','none');
					$('.analytics4-progress-loader').removeClass('complete');
					$('.popup-inner').css('overflow','auto');
				}, 1000);
			}
		});
	}
});


$(document).on('click','#refresh_ga4_account_detail',function(e){
	e.preventDefault();
	$(this).addClass('refresh-gif');

	$('#save_detail_analytics4').attr('disabled','disabled');
	$('.popup-inner').css('overflow','hidden');

	var email = $('#detail_analytics4_existing_emails').val();
	var campaign_id = $('.campaign_id').val();

	$('.analytics4-progress-loader').css('display','block');

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
						$('#detail_analytics_accounts').html(result);
						$('.selectpicker').selectpicker('refresh');
						var li = '<option value="">Select Property</option>';
						$('#detail_analytics_property').html(li);
					}
				});

				$('.analytics4-progress-loader').addClass('complete');

				$('#refresh_ga4_account_detail').removeClass('refresh-gif');
				$('#show_ga4_last_time').parent().removeClass('error , yellow');
				$('#show_ga4_last_time').parent().addClass('green');
				$('#show_ga4_last_time').parent().css('display','block');
				document.getElementById('show_ga4_last_time').innerHTML = response['message'];
			}

			if(response['status'] == 0){
				$('#refresh_ga4_account_detail').removeClass('refresh-gif');				
				$('#show_ga4_last_time').parent().removeClass('yellow , green');
				$('#show_ga4_last_time').parent().addClass('error');
				$('#show_ga4_last_time').parent().css('display','block');
				document.getElementById('show_ga4_last_time').innerHTML = response['message'];
			}

			if(response['status'] == 2){
				$('#refresh_ga4_account_detail').removeClass('refresh-gif');
				$('#show_ga4_last_time').parent().removeClass('yellow ,green');
				$('#show_ga4_last_time').parent().addClass('error');
				$('#show_ga4_last_time').parent().css('display','block');
				document.getElementById('show_ga4_last_time').innerHTML = response['message'];
			}

			setTimeout(function(){
				$('.analytics4-progress-loader').css('display','none');
				$('.analytics4-progress-loader').removeClass('complete');
				$('#save_detail_analytics4').removeAttr('disabled','disabled');
				$('.popup-inner').css('overflow','auto');
			}, 500);
		}
	});
});

$(document).on('click','#connect_detail_ua ,#connect_detail_ga4',function(e){
  e.preventDefault();
  $('#googleAnalytics_detail_popup_close').trigger('click');
});


function ga4_overview_allUser_Chart(campaign_id){
	$.ajax({
		url:BASE_URL +'/ajax_ga4_au_chart',
		dataType:'json',
		data:{campaign_id},
		success:function(response){
			if(window.line_au_allUsers_chart){
				window.line_au_allUsers_chart.destroy();
			}

			var ctxGa4_AllUser = document.getElementById('canvas-ga4-allUser').getContext('2d');
			window.line_au_allUsers_chart = new Chart(ctxGa4_AllUser, config_allUser_ga4);
			var gradient = gradientColor(ctxGa4_AllUser);

			config_allUser_ga4.data.labels =  response['labels'];
			config_allUser_ga4.data.datasets[0].data = response['active_users'];
			config_allUser_ga4.data.datasets[0].backgroundColor = gradient;
			window.line_au_allUsers_chart.update();

			$('.au-graph').removeClass('ajax-loader');
		}
	});
}

var config_allUser_ga4 = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: false,
			borderWidth:2
		}
		]
	},
	options: {
		elements: {
			point:{
				radius: 0,
				hoverRadius:5
			}
		},
		// responsive: true,
		maintainAspectRatio: false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				},
				offset:true
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				},
				offset: true
			}]
		},
		tooltips: {
			intersect: false,
			mode: 'label',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
			callbacks: {
				label: function(tooltipItem, myData) {
					var label = myData.datasets[tooltipItem.datasetIndex].label || '';
					if (label) {
						label += ': ';
					}
					label += parseFloat(tooltipItem.value).toFixed(2);
					return label;
				},

				labelTextColor: function(context) {
					return '#000';
				}
			}
		},
		legend: {
			display:false
		}
	}
};


function ga4_overview_conversions_Chart(campaign_id){
	$.ajax({
		url:BASE_URL +'/ajax_ga4_conversions_chart',
		dataType:'json',
		data:{campaign_id},
		success:function(response){
			if(window.line_nu_allUsers_chart){
				window.line_nu_allUsers_chart.destroy();
			}

			var ctxGa4_NewUser = document.getElementById('canvas-ga4-conversions').getContext('2d');
			window.line_nu_allUsers_chart = new Chart(ctxGa4_NewUser, config_newUser_ga4);
			var gradient = gradientColor(ctxGa4_NewUser);

			config_newUser_ga4.data.labels =  response['labels'];
			config_newUser_ga4.data.datasets[0].data = response['conversions'];
			config_newUser_ga4.data.datasets[0].backgroundColor = gradient;
			window.line_nu_allUsers_chart.update();

			$('.ga4-overview-conversions').removeClass('ajax-loader');
		}
	});
}

var config_newUser_ga4 = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: false,
			borderWidth:2
		}
		]
	},
	options: {
		elements: {
			point:{
				radius: 0,
				hoverRadius:5
			}
		},
		// responsive: true,
		maintainAspectRatio: false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				},
				offset:true
			}],
			yAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				},
				offset: true
			}]
		},
		tooltips: {
			intersect: false,
			mode: 'label',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
			callbacks: {
				label: function(tooltipItem, myData) {
					var label = myData.datasets[tooltipItem.datasetIndex].label || '';
					if (label) {
						label += ': ';
					}
					label += parseFloat(tooltipItem.value).toFixed(2);
					return label;
				},

				labelTextColor: function(context) {
					return '#000';
				}
			}
		},
		legend: {
			display:false
		}
	}
};


function ga4_overview_allUser_stats(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_alluser_statistics",
		data:{campaign_id},
		dataType:'json',
		success:function(result){
			if(result['total_users'] != '??'){
				var total_users_string = result['total_users'].toString();
				total_users_string = total_users_string.replace(/,/g, "");
				if(total_users_string > 0 ){
					$('.allUsers-count').html(result['current_active_users']);
					$('.allUsers_growth').addClass("green");
					$('.allUsers_growth').html('<img src="/public/vendor/internal-pages/images/up-stats-arrow.png" alt="up-stats-arrow"><span>'+result['total_users']+'% </span>Since Start');
				}else if(total_users_string < 0 ){
					var replace_total_users = total_users_string.replace('-', '');
					$('.allUsers-count').html(result['current_active_users']);
					$('.allUsers_growth').addClass("red");
					$('.allUsers_growth').html('<img src="/public/vendor/internal-pages/images/down-stats-arrow.png" alt="down-stats-arrow"><span>'+replace_total_users+'% </span>Since Start');
				}else{
					$('.allUsers-count').html(result['current_active_users']);
				}
			}else{
				$('.allUsers-count').html(result['current_active_users']);
			}

			$('.au-total').removeClass("ajax-loader");
		}
	});
}

function ga4_overview_conversions_stats(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_conversions_statstics",
		data:{campaign_id},
		dataType:'json',
		success:function(result){
			if(result['total_conversions'] != '??'){
				var total_conversions_string = result['total_conversions'].toString();
				total_conversions_string = total_conversions_string.replace(/,/g, "");
				if(total_conversions_string > 0 ){
					$('.Google-analytics4-conversions').html(result['current_conversions']);
					$('.conversions-result').addClass("green");
					$('.conversions-result').html('<img src="/public/vendor/internal-pages/images/up-stats-arrow.png" alt="up-stats-arrow"><span>'+result['total_conversions']+'% </span>Since Start');
				}else if(total_conversions_string < 0 ){
					var replace_total_conversions = total_conversions_string.replace('-', '');
					$('.Google-analytics4-conversions').html(result['current_conversions']);
					$('.conversions-result').addClass("red");
					$('.conversions-result').html('<img src="/public/vendor/internal-pages/images/down-stats-arrow.png" alt="down-stats-arrow"><span>'+replace_total_conversions+'% </span>Since Start');
				}else{
					$('.Google-analytics4-conversions').html(result['current_conversions']);
				}
			}else{
				$('.Google-analytics4-conversions').html(result['current_conversions']);
			}

			$('.ga4-conversions').removeClass("ajax-loader");
		}
	});
}

$(document).on('click','#connect_detail_ga4',function(){
	$('#show_ga4_last_time').parent().removeClass('error green yellow');
	$('#detail_analytics4_existing_emails ,#detail_analytics4_accounts, #detail_analytics4_property').html('');
	$('#save_detail_analytics4').attr('disabled','disabled');
	$('.errorStyle').css('display','none');

	document.getElementById('show_ga4_last_time').innerHTML = '';
	disableSelectPicker('#detail_analytics4_existing_emails','.analytic4-emails-detail-loader');
	disableSelectPicker('#detail_analytics4_accounts','.analytic4-account-detail-loader');
	disableSelectPicker('#detail_analytics4_property','.analytic4-property-detail-loader');
	$.ajax({
		url:BASE_URL+'/ajax_get_ga4_connected_accounts',
		data:{id:$('.campaign_id').val()},
		type:'GET',
		success:function(response){
			enableSelectPicker('#detail_analytics4_existing_emails','.analytic4-emails-detail-loader');
			enableSelectPicker('#detail_analytics4_accounts','.analytic4-account-detail-loader');
			enableSelectPicker('#detail_analytics4_property','.analytic4-property-detail-loader');

			$('#detail_analytics4_existing_emails').html(response.emails);
			$('#detail_analytics4_accounts').html(response.accounts);
			$('#detail_analytics4_property').html(response.property);
			$('#save_detail_analytics4').removeAttr('disabled','disabled');
			$('.selectpicker').selectpicker('refresh');
		}
	});
});