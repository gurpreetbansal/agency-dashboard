var BASE_URL = $('.base_url').val();
var color = Chart.helpers.color;
var configCustomerView = {
	type: 'line',
	data: {
		datasets: [{
			label: 'List on search',
			yAxisID: 'lineId',
			backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: false,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		},{
			label: 'List on Maps',
			// yAxisID: 'barId',
			backgroundColor: color(window.chartColors.orange).alpha(0.15).rgbString(),
			borderColor: window.chartColors.orange,
			data: [],
			fill: false,
			lineTension:0.0001,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		}

		]
	},
	options: {
		maintainAspectRatio:false,
		// responsive: false,
		// height:300,
		// width:1200,
		// maintainAspectRatio: this.maintainAspectRatio,
		scales: {
			xAxes: [{
				type: 'time',
				distribution: 'series',
				offset: true,
				ticks: {
					major: {
						enabled: true
					},
					source: 'data',
					autoSkip: true,
					autoSkipPadding: 30,
					maxRotation: 0,
					sampleSize: 30
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)"
				},

			}],
			yAxes: [
			{
				id: 'lineId',
				// gridLines: {
				// 	color: "rgba(0, 0, 0, 0)"
				// },
				scaleLabel: {
					// display: true,
					// labelString: 'Clicks'
				},
				ticks: {
					beginAtZero: true
				},
				position:'left'
			}
			
			]
		},
		tooltips: {
			intersect: false,
			mode: 'index',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            // titleSpacing:'2',
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
        	position: 'right'
        	/*align: 'right'*/
        },
        elements: {
        	point:{
        		radius: 0,
        		hitRadius	:1

        	}
        },
    }
};


var configCustomerAction = {
	type: 'line',
	data: {
		datasets: [{
			label: 'Visit Your website',
			labels: 'Visit Your website',
			backgroundColor: color(window.chartColors.yellow).alpha(0.15).rgbString(),
			borderColor: window.chartColors.yellow,
			data:[],
			fill: false,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		},{
			label: 'Request Directions',
			labels: 'Request Directions',
			backgroundColor: color(window.chartColors.green).alpha(0.15).rgbString(),
			borderColor: window.chartColors.green,
			data: [],
			fill: false,
			lineTension:0.0001,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		},{
			label: 'Call You',
			labels: 'Call You',
			backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data: [],
			fill: false,
			lineTension:0.0001,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		}

		]
	},
	options: {
		// responsive: false,
		// height:350,
		// width:1400,
		// maintainAspectRatio: this.maintainAspectRatio,
		maintainAspectRatio: false,
		scales: {
			xAxes: [{
				type: 'time',
				distribution: 'series',
				offset: true,
				ticks: {
					major: {
						enabled: true
					},
					source: 'data',
					autoSkip: true,
					autoSkipPadding: 30,
					maxRotation: 0,
					sampleSize: 30
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)"
				},

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
            		label += parseFloat(tooltipItem.value).toFixed(2);
            		return label;
            	},

            	labelTextColor: function(context) {
            		return '#000';
            	}
            }
        },
        legend: {
        	position: 'right'
        },
        elements: {
        	point:{
        		radius: 0,
        		hitRadius	:1

        	}
        },
    }
};

var phoneCalls = {
	type: 'bar',
	data: {
		labels: [],
		datasets: [
		{
			label: "Total Calls",
			backgroundColor: color(window.chartColors.royalBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:30
		}
		
		]
	},
	options: {
		title: {
			display: false
		},
		tooltips: {
			mode: 'index',
			intersect: false,
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
			callbacks: {
				labelTextColor: function(context) {
					return '#000';
				}
			}
		},
		maintainAspectRatio: false,
		// responsive: false,
		// height:350,
		// width:1400,
		// maintainAspectRatio: this.maintainAspectRatio,
		scales: {
			xAxes: [{
				stacked: true,
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				stacked: true
			}]
		},
		legend: { 
			align: 'start',
			position: 'right',
			labels :{  
				fontStyle: 'bold',
				boxWidth:10,
				padding:20  
			}

		}
	}
};

var photoViews = {
	type: 'horizontalBar',
	data: {
		labels: [],
		datasets: [
		{
			label: "Owner photos",
			backgroundColor: color(window.chartColors.royalBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:30
		},
		{
			label: "customer photos",
			backgroundColor: color(window.chartColors.orange).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:30
		}		
		]
	},
	options: {
		title: {
			display: false
		},
		legend: {
			position: 'right',
		},
		tooltips: {
			mode: 'index',
			intersect: false,
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
			callbacks: {
				labelTextColor: function(context) {
					return '#000';
				}
			}
		},
		responsive: false,
		height:350,
		width:1400,
		maintainAspectRatio: this.maintainAspectRatio,
		scales: {
			xAxes: [{
				stacked: true,
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			yAxes: [{
				stacked: true,
				 ticks: {
				  beginAtZero: false,
				  stepSize:1.25
				}
			}]
		}
	}
};

/*customer view data */
function customerViewOnload(campaignId,key,value){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_fetch_customer_view_graph",
		data:{campaignId,key,value},
		dataType:'json',
		success:function(result){
			if(result['status'] == 1){
				customerViewChart(result['search'],result['maps']);
				$('#customer-view-chartId').removeClass('ajax-loader');
			}	

		}
	});
}

function customerViewChart(clicks,impressions){
	if(window.myLineSearchConsole){
		window.myLineSearchConsole.destroy();
	}

	var ctxSearchConsole = document.getElementById('customer-views').getContext('2d');
	window.myLineSearchConsole = new Chart(ctxSearchConsole, configCustomerView);

	configCustomerView.data.datasets[0].data = clicks;
	configCustomerView.data.datasets[1].data = impressions;
	window.myLineSearchConsole.update();
}

$(document).on('click','.customer-view-range',function(e){
	e.preventDefault();
	var value = $(this).attr('data-value');
	var module = $(this).attr('data-module');
	var campaignId = $('.campaignID').val();
	var key = $('#encriptkey').val();

	$('.customer-view-range').removeClass('active');
	$(this).addClass('active');
	$('#customer-view-chartId').addClass('ajax-loader');
	$.ajax({
		type:'POST',
		url:BASE_URL+'/ajax_gmb_date_range',
		data:{value,module,campaignId,key,_token:$('meta[name="csrf-token"]').attr('content')},
		dataType:'json',
		success:function(response){
			if(response['status'] == 1){
				customerViewOnload(campaignId,key,value);
			}else{
				$('#customer-view-chartId').removeClass('ajax-loader');
			}
		}
	});
});

/* customer action */
function customerActionOnload(campaignId,key,value){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_fetch_customer_action_graph",
		data:{campaignId,key,value},
		dataType:'json',
		success:function(result){
			if(result['status'] == 0){
			} 

			if(result['status'] == 1){
				customerActionChart(result['website'],result['directions'],result['phone'],result['website_count'],result['direction_count'],result['phone_count']);
				$('#customer-actions-chartId').removeClass('ajax-loader');
			}	

		}
	});
}

function customerActionChart(website,directions,phone,website_count,direction_count,phone_count){
	if(window.myLineAction){
		window.myLineAction.destroy();
	}

	var ctxAction = document.getElementById('customer-actions').getContext('2d');
	window.myLineAction = new Chart(ctxAction, configCustomerAction);

	configCustomerAction.data.datasets[0].label = 'Visit Your website '+ website_count;
	configCustomerAction.data.datasets[0].data = website;
	configCustomerAction.data.datasets[1].label = 'Request Directions '+ direction_count;
	configCustomerAction.data.datasets[1].data = directions;
	configCustomerAction.data.datasets[2].label = 'Call You '+ phone_count;
	configCustomerAction.data.datasets[2].data = phone;
	window.myLineAction.update();
}

$(document).on('click','.customer-action-range',function(e){
	e.preventDefault();
	var value = $(this).attr('data-value');
	var module = $(this).attr('data-module');
	var campaignId = $('.campaignID').val();
	var key = $('#encriptkey').val();

	$('.customer-action-range').removeClass('active');
	$(this).addClass('active');
	$('#customer-actions-chartId').addClass('ajax-loader');
	$.ajax({
		type:'POST',
		url:BASE_URL+'/ajax_gmb_date_range',
		data:{value,module,campaignId,key,_token:$('meta[name="csrf-token"]').attr('content')},
		dataType:'json',
		success:function(response){
			if(response['status'] == 1){
				customerActionOnload(campaignId,key,value);
			}else{
				$('#customer-actions-chartId').removeClass('ajax-loader');
			}
		}
	});
});



/******  gmb ajax ******/






function phoneCallsBar(campaignId,key,value) {
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_get_phone_calls",
		data: { campaignId,key,value },
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				phoneCallsBarData(result);
				$('#phone-calls-bar-chartId').removeClass('ajax-loader');
			}
		}
	});
}

function phoneCallsBarData(result){
	if (window.myBar) {
		window.myBar.destroy();
	}

	var ctxs = document.getElementById('phone-calls-bar').getContext('2d');
	window.myBar = new Chart(ctxs, phoneCalls);

	phoneCalls.data.labels = result['labels'];
	phoneCalls.data.datasets[0].label = 'Total Calls: '+ result['total_calls'];
	phoneCalls.data.datasets[0].data = result['value'];
	window.myBar.update();
}


$(document).on('click','.phone-calls-range',function(e){
	e.preventDefault();
	var value = $(this).attr('data-value');
	var module = $(this).attr('data-module');
	var campaignId = $('.campaignID').val();
	var key = $('#encriptkey').val();

	$('.phone-calls-range').removeClass('active');
	$(this).addClass('active');
	$('#phone-calls-bar-chartId').addClass('ajax-loader');
	$.ajax({
		type:'POST',
		url:BASE_URL+'/ajax_gmb_date_range',
		data:{value,module,campaignId,key,_token:$('meta[name="csrf-token"]').attr('content')},
		dataType:'json',
		success:function(response){
			if(response['status'] == 1){
				phoneCallsBar(campaignId,key,value);
			}else{
				$('#phone-calls-bar-chartId').removeClass('ajax-loader');
			}
		}
	});
});


function sum( obj ) {
	var sum = 0;
	for( var el in obj ) {
		if( obj.hasOwnProperty( el ) ) {
			sum += parseFloat( obj[el] );
		}
	}
	return sum;
}




/* doughnut  */
var config = {
	type: 'doughnut',
	data: {
		datasets: [{
			data: [],
			backgroundColor: [
			window.chartColors.yellow,
			window.chartColors.DarkGreen,
			window.chartColors.blue,
			]
		}],
		labels: [
		'Branded',
		'Direct',
		'Discovery'
		]
	},
	options: {
		legend: {
			display: false
		},
		cutoutPercentage: 60,
		circumference: 2 * Math.PI,
		responsive: false,
		height:350,
		width:500,
		maintainAspectRatio: this.maintainAspectRatio,
		animation: {
			animateRotate: false,
			animateScale: true
		},
		tooltips: {
			enabled: false,
			custom: function(tooltip) {
			var tooltipEl = document.getElementById('chartjs-tooltip-text');


	        // Hide if no tooltip
	        if (tooltip.opacity === 0) {
	        	tooltipEl.style.color = "#464950";
	        	CustomerSearch_new($('.campaign_id').val());
	        	tooltipEl.style.opacity = 1;
	        	return;
	        }


	        // Set caret Position
	        tooltipEl.classList.remove('above', 'below', 'no-transform');
	        if (tooltip.yAlign) {
	        	tooltipEl.classList.add(tooltip.yAlign);
	        } else {
	        	tooltipEl.classList.add('no-transform');
	        }

	        function getBody(bodyItem) {
	        	return bodyItem.lines;
	        }

	        // Set Text
	        if (tooltip.body) {
	        	var bodyLines = tooltip.body.map(getBody);
	        	var innerHtml = '<p>';
	        	bodyLines.forEach(function (body, i) {
	        		var dataNumber = body[i].split(":");
	        		var textVal = dataNumber[0].trim();
	        		var dataValNum = parseInt(dataNumber[1].trim());
	        		var dataToPercent = (dataValNum / sumOfDataVal(config) * 100).toFixed(2) + '%';
	        		innerHtml += textVal+'</br>'+dataValNum+'</br>'+dataToPercent;
	        	});

	        	innerHtml += '</p>';

	        	var tableRoot = tooltipEl.querySelector('div');
	        	tableRoot.innerHTML = innerHtml;
	        }


	        tooltipEl.style.opacity = 1;
	        tooltipEl.style.color = "#FFF";
	    }      		
	}
}
};


function CustomerSearch(campaignId,key,value){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_Customer_search",
		data:{campaignId: campaignId,key:key,value:value},
		dataType:'json',
		success:function(result){
			if(result['status'] == 0){
				
			} 

			if(result['status'] == 1){
				$("#chartjs-tooltip-text div p").html("All Searches "+ "</br>"+ result['total']);
				customerActionChartPie(result['data']);
				$('#customer_search_pie_chart').removeClass('ajax-loader');
			}	

		}
	});
}

function CustomerSearch_new(campaignId){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_Customer_search",
		data:{campaignId: campaignId},
		dataType:'json',
		success:function(result){
			if(result['status'] == 0){
				
			} 

			if(result['status'] == 1){
				$("#chartjs-tooltip-text div p").html("All Searches "+ "</br>"+ result['total']);
		}	

		}
	});
}

$(document).on('click','.customer-search-range',function(e){
	e.preventDefault();
	var value = $(this).attr('data-value');
	var module = $(this).attr('data-module');
	var campaignId = $('.campaignID').val();
	var key = $('#encriptkey').val();

	$('.customer-search-range').removeClass('active');
	$(this).addClass('active');
	$('#customer_search_pie_chart').addClass('ajax-loader');
	$.ajax({
		type:'POST',
		url:BASE_URL+'/ajax_gmb_date_range',
		data:{value,module,campaignId,key,_token:$('meta[name="csrf-token"]').attr('content')},
		dataType:'json',
		success:function(response){
			if(response['status'] == 1){
				CustomerSearch(campaignId,key,value);
			}
		}
	});
});

function customerActionChartPie(result){
	var ctx = document.getElementById('customers_search').getContext('2d');
	window.myDoughnut = new Chart(ctx, config);

	config.data.datasets[0].data = result;
	window.myDoughnut.update();
}


function sumOfDataVal(dataArray) {
	return dataArray['data']['datasets'][0]['data'].reduce(function (sum, value) {
		return sum + value;
	}, 0);
}



/* GMB */

var configPhotoView = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: "You",
			fill: false,
			backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
			borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
			data: [],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		}]
	},
	options: {
		maintainAspectRatio: false,
		// responsive: false,
		// height:350,
		// width:1400,
		// maintainAspectRatio: this.maintainAspectRatio,
		elements: {
			line: {
				tension: 0.000001
			}
			,
			point:{
				radius: 0,
				hitRadius	:1

			}
		},
		title: {
			display: false
		},
		tooltips: {
			intersect: false,
			mode: 'index',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
            // titleSpacing:'2',
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

		hover: {
			mode: 'nearest',
			intersect: true
		},
		scales: {
			xAxes: [{
				display: true,
				scaleLabel: {
					display: false,
					labelString: 'Month'
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				},
				ticks: {
					autoSkip: true,
					autoSkipPadding: 35
				}
			}],
			yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Value'
				}
				, ticks: {
					min: 0,
				}
			}]
		},
		legend: { 
			align: 'start',
			position: 'right',
			labels :{  
				fontStyle: 'bold',
				boxWidth:10,
				padding:20  
			}

		}
	}
};

function highChartMapPhotoView(result){

	if (window.myLinePhotoView) {
		window.myLinePhotoView.destroy();
	}
	var ctxPhotoView = document.getElementById('photo-view-chart').getContext('2d');
	window.myLinePhotoView = new Chart(ctxPhotoView, configPhotoView);

	configPhotoView.data.labels =  result['from_datelabel'];
	configPhotoView.data.datasets[0].data = result['you'];
	configPhotoView.data.datasets[0].label = 'You: '+result['you_count'];

	window.myLinePhotoView.update();

}

function ajaxGooglePhotoView(campaignId,key,value){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_fetch_photo_views_graph",
		data:{campaignId,key,value},
		dataType:'json',
		success:function(result){
				if(result['status'] == 1){
					highChartMapPhotoView(result);
					$('#photo-view-chartId').removeClass('ajax-loader');
				}	
			}
		});
}		

$(document).on('click','.photo-views-range',function(e){
	e.preventDefault();
	var value = $(this).attr('data-value');
	var module = $(this).attr('data-module');
	var campaignId = $('.campaignID').val();
	var key = $('#encriptkey').val();

	$('.photo-views-range').removeClass('active');
	$(this).addClass('active');
	$('#photo-view-chartId').addClass('ajax-loader');
	$.ajax({
		type:'POST',
		url:BASE_URL+'/ajax_gmb_date_range',
		data:{value,module,campaignId,key,_token:$('meta[name="csrf-token"]').attr('content')},
		dataType:'json',
		success:function(response){
			if(response['status'] == 1){
				ajaxGooglePhotoView(campaignId,key,value);
			}else{
				$('#photo-view-chartId').removeClass('ajax-loader');
			}
		}
	});
});

/*Direction Requests*/
function DirectionRequest(campaignId,key,value){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_direction_requests",
		data:{campaignId,key,value},
		dataType:'json',
		success:function(result){
			if(result['status'] == 1 ){
				$('.direction-box-list').html(result['html']);
				$('.direction-map-box').html(result['map']);
			}else{
				$('.direction-box-list').html('<article>Not enough data for selected time period</article>');
				$('.direction-map-box').html('');
			}
			$('#direction-box-data').removeClass('ajax-loader');
		}
	});
}

$(document).on('click','.direction-requests-range',function(e){
	e.preventDefault();
	var value = $(this).attr('data-value');
	var module = $(this).attr('data-module');
	var campaignId = $('.campaignID').val();
	var key = $('#encriptkey').val();

	$('.direction-requests-range').removeClass('active');
	$(this).addClass('active');
	$('#direction-box-data').addClass('ajax-loader');
	$.ajax({
		type:'POST',
		url:BASE_URL+'/ajax_gmb_date_range',
		data:{value,module,campaignId,key,_token:$('meta[name="csrf-token"]').attr('content')},
		dataType:'json',
		success:function(response){
			if(response['status'] == 1){
				DirectionRequest(campaignId,key,value);
			}else{
				$('#direction-box-data').removeClass('ajax-loader');
			}
		}
	});
});

/*Photo Quantity*/
function PhotoQuantity(campaignId){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_photo_quantity",
		data:{campaignId: campaignId},
		dataType:'json',
		success:function(result){	
		
			if(result['status'] == 1){
				PhotoQuantityChart(result);
			}			
		}
	});
}

function PhotoQuantityChart(result){
	if (window.myLinePhoneCall) {
		window.myLineKeyword.destroy();
	}

	var ctxs = document.getElementById('photo-quantity-bar').getContext('2d');
	window.photoView = new Chart(ctxs, photoViews);

	photoView.data.labels = ['You'];
	photoView.data.datasets[1].data = result['customer_photos'];
	photoView.data.datasets[0].data = result['owner_photos'];
	window.photoView.update();
}

/*REviews*/
function Reviews(campaignId,page){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_gmb_reviews",
		data:{campaignId: campaignId,page: page},
		success:function(response){	
			$('#display_reviews').html('');
			$('#display_reviews').html(response);
		}
	});

	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_gmb_reviews_pagination",
		data:{campaignId: campaignId,page: page},
		success:function(response){	
			$('#display_reviews_pagination').html('');
			$('#display_reviews_pagination').html(response);
			$('#display_reviews_pagination').removeClass('ajax-loader');
		}
	});
} 

$(document).on('click','.reviews-pagination a',function(e){
	e.preventDefault();
	$('#display_reviews_pagination').addClass('ajax-loader');
	$('reviews-pagination ul li').removeClass('active');
	$(this).parent().addClass('active');
	var page = $(this).attr('href').split('page=')[1];
	Reviews($('.campaign_id').val(),page);
});

/*Media*/
function Media(campaignId){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_gmb_media",
		data:{campaignId: campaignId},
		success:function(response){	
			$('#latest_customer_photos').html('');
			$('#latest_customer_photos').html(response);
		}
	});
}




$(document).on('click','.readmore a.more', function(){
    var parent = $(this).parent();
    if(parent.data('visible')) {
        parent.data('visible', false).find('.ellipsis').show()
        .end().find('.moreText').hide()
        .end().find('a.more').text('read more');
    } else {
        parent.data('visible', true).find('.ellipsis').hide()
        .end().find('.moreText').show()
        .end().find('a.more').text('read less');
    }
});


$(document).on('click','#refresh_gmb_section',function(e){
	e.preventDefault();

	var campaign_id = $(this).attr('data-request-id');
	$(this).addClass('refresh-gif');
	gmb_laoders('addClass');

	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_get_latest_gmb',
		dataType:'json',
		data:{campaign_id},
		success:function(response){
			if(response['status'] == 'success'){
				GoogleUpdateTimeAgo('gmb');
				gmb_scripts(campaign_id);
			}

			if(response['status'] == 'google-error'){
				$('.GmbErrorHeading').append('');
				$('.GmbErrorHeading').append('<div class="alert alert-danger"><span>'+response['message']+'</span></div>');
			}

			if(response['status'] == 'error'){
				Command: toastr["error"]('Error, please try again later.');
			}
			$('#refresh_gmb_section').removeClass('refresh-gif');
			gmb_laoders('removeClass');
		}
	});
});


function gmb_laoders(classType){
	if(classType == 'addClass'){
		$('#customer_search_pie_chart').addClass('ajax-loader');
		$('#customer-view-chartId').addClass('ajax-loader');
		$('#customer-actions-chartId').addClass('ajax-loader');
		$('#direction-box-data').addClass('ajax-loader');
		$('#phone-calls-bar-chartId').addClass('ajax-loader');
		$('#photo-view-chartId').addClass('ajax-loader');
		$('#display_reviews').addClass('ajax-loader');
		$('#display_reviews_pagination').addClass('ajax-loader');
		$('#latest_customer_photos').addClass('ajax-loader');
	}


	if(classType == 'removeClass'){
		$('#customer_search_pie_chart').removeClass('ajax-loader');
		$('#customer-view-chartId').removeClass('ajax-loader');
		$('#customer-actions-chartId').removeClass('ajax-loader');
		$('#direction-box-data').removeClass('ajax-loader');
		$('#phone-calls-bar-chartId').removeClass('ajax-loader');
		$('#photo-view-chartId').removeClass('ajax-loader');
		$('#display_reviews').removeClass('ajax-loader');
		$('#display_reviews_pagination').removeClass('ajax-loader');
		$('#latest_customer_photos').removeClass('ajax-loader');
	}
	
}