var BASE_URL = $('.base_url').val();

function social_overview_scripts(campaign_id){
	$('.viewkeypdf,.generate-pdf').hide();
	get_social_log_errors(campaign_id);
	facebook_overview(campaign_id);
}

function facebook_overview(campaign_id){
	$.ajax({
		type: 'GET',
		url:  BASE_URL + '/facebook_total_likes',
		data: {id:campaign_id},
		dataType: 'json',
		success: function(response) {
			if(response.status != 0){
				$('.fboverview').removeClass('disabled');
				$('.facebook_overview_likes').text(response.likes.toLocaleString());
				$('.facebook_overview_engagement').text(response.reach.toLocaleString());
				
				if(window.LineOverviewFacebook){
					window.LineOverviewFacebook.destroy();
				}
				var ctxfacebookOverviewLineChart = document.getElementById('facebook_overview_organic').getContext('2d');
				window.LineOverviewFacebook = new Chart(ctxfacebookOverviewLineChart, configFacebookOverview);
				var gradient = gradientColor(ctxfacebookOverviewLineChart);
				configFacebookOverview.data.labels =  response.labels;
				configFacebookOverview.data.datasets[0].data = response.data;
				configFacebookOverview.data.datasets[0].backgroundColor = gradient;
				window.LineOverviewFacebook.update();
				$(".facebook_overview_img img").remove()
				socialFilters();
			}
			$('.fboverview,.facebook_overview_likes,.facebook_overview_engagement,.facebook_overview_img').removeClass('ajax-loader');
		}
	});
}

function get_social_log_errors(campaign_id){
	$.ajax({
		type:'GET',
		url:BASE_URL +'/get_social_log_errors',
		data:{campaign_id,moduleType:6},
		dataType:'json',
		success:function(response){
			if(response.length > 0){
				$('#facebookHeading').html('');
		       $('#facebookHeading').append('<div class="alert alert-danger"><span><i class="fa fa-exclamation-triangle"></i>Facebook: '+response+' Try reconnecting your account.</span></div>');
		       // setTimeout(function(){displayFloatingDiv();},100);
		       // $('html,body').animate({scrollTop: $("#facebookHeading").offset().top},'slow');
			}
		}
	});
}


var configFacebookOverview = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			borderColor: '#369cfd',
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
		maintainAspectRatio:false,
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
				offset:true
			}]
		},
		tooltips: {
			intersect: false,
			mode: 'index',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
			callbacks: {
				label: function(tooltipItem, myData) {
					var label = myData.datasets[tooltipItem.datasetIndex].label || '';
					if (label) {
						label += ': ';
					}
					label += parseFloat(tooltipItem.value);
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

$(document).on('click','.facebook_view_more',function(e){
	e.preventDefault();
	$('.social_module,.common_class').removeClass('uk-active');
	$('#nav_facebook').addClass('uk-active');
	$('#nav_facebook').trigger('click');
	$('#facebook').addClass('uk-active');
	$('.facebook_view').addClass('uk-active active'); //use for view key
});


function socialFilters(){
	$.ajax({
		type: 'GET',
		url:  BASE_URL + '/social_filters',
		dataType: 'json',
		success: function(response) {
			$('.overviewFilter').html(response);
		}
	});
}


function stopScroller(){
	document.querySelectorAll('.stopScroll').forEach(item => {
	  item.addEventListener('wheel', e => {
		e.preventDefault();
		e.stopPropagation();
		return false;
	  });
	});
}