var BASE_URL = $('.base_url').val();
var color = Chart.helpers.color;

var configSearchConsoleRanking = {
	type: 'line',
	data: {
		datasets: [{
			label: 'Clicks',
			yAxisID: 'lineId',
			backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
			borderColor: window.chartColors.blue,
			data:[],
			pointRadius: 0,
			fill: false,
			lineTension: 0,
			borderWidth: 2
		},{
			label: 'Impressions',
			yAxisID: 'barId',
			backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
			borderColor: window.chartColors.red,
			data: [],
			pointRadius: 0,
			fill: true,
			lineTension: 0,
			borderWidth: 2
		}

		]
	},
	options: {
		maintainAspectRatio:false,
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
				}

			}],
			yAxes: [
			{
				id: 'lineId',
				gridLines: {
					drawBorder: false
				},
				scaleLabel: {
					display: true,
					labelString: 'Clicks'
				},
				ticks: {
					beginAtZero: true
				},
				position:'left'
			},
			{
				id: 'barId',
				ticks: {
					beginAtZero: true
				},
				gridLines: {
					drawBorder: false
				},
				scaleLabel: {
					display: true,
					labelString: 'Impression'
				},
				position:'right'
			}
			]
		},
		tooltips: {
			intersect: false,
			mode: 'index',
			callbacks: {
				label: function(tooltipItem, myData) {
					var label = myData.datasets[tooltipItem.datasetIndex].label || '';
					if (label) {
						label += ': ';
					}
					label += parseFloat(tooltipItem.value).toFixed(2);
					return label;
				}
			}
		}
	}
};

var KeywordconfigRanking = {
	type: 'bar',
	data: {
		labels: [],
		datasets: [
		{
			label: 'Total Keywords',
			backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
			borderColor: window.chartColors.red,
			borderWidth: 1,
			data: [],
		}
		]
	},
	options: {
		responsive: true,
		title: {
			display: false,
			text: ''
		}
	}
};

var KeywordChartConfigRanking = {
	type: 'pie',
	data: {
		datasets: [{
			data: [],
			backgroundColor: [
			color(window.chartColors.red).alpha(1.0).rgbString(),
			color(window.chartColors.red).alpha(0.25).rgbString(),
			color(window.chartColors.orange).alpha(1.0).rgbString(),
			color(window.chartColors.orange).alpha(0.25).rgbString(),
			color(window.chartColors.yellow).alpha(1.0).rgbString(),
			color(window.chartColors.yellow).alpha(0.25).rgbString(),
			color(window.chartColors.green).alpha(1.0).rgbString(),
			color(window.chartColors.green).alpha(0.25).rgbString(),
			color(window.chartColors.blue).alpha(1.0).rgbString(),
			color(window.chartColors.blue).alpha(0.25).rgbString(),
			color(window.chartColors.purple).alpha(1.0).rgbString(),
			color(window.chartColors.purple).alpha(0.25).rgbString(),

			],
			label: ''
		}],
		labels: []
	},
	options: {
		responsive: true,
		legend: {
			position: 'right'
		},
	}
};

var configTrafficGrowthViewKey = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: "Current Period: ",
			fill: true,
			backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
			borderColor: color(window.chartColors.blue).alpha(1.0).rgbString(),
			data: [],
		}			 
		]
	},
	options: {
		maintainAspectRatio: false,
		title: {
			display: false,
			text: 'Chart.js Line Chart'
		},
		tooltips: {
			mode: 'index',
			intersect: false,
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
		}
	}
};

function ranking_chart(){
	var ctxSearchConsole = document.getElementById('canvas-search-console-ranking').getContext('2d');
	window.myLineSearchConsoleRank = new Chart(ctxSearchConsole, configSearchConsoleRanking);

	var ctxs = document.getElementById('keywordsCanvasRanking').getContext('2d');
	window.myLineKeywordRank = new Chart(ctxs, KeywordconfigRanking);

	var ctxPie = document.getElementById('keywordsCanvasChartAreaRanking').getContext('2d');
	window.myLinePieRank = new Chart(ctxPie, KeywordChartConfigRanking);
}

$(document).ready(function(){
	if(window.location.hash != ''){
		var dashboard_active = window.location.hash;
	}


	if((dashboard_active != null) && dashboard_active != undefined){
				if (dashboard_active.match('#')) {
					$('li.seoSidebar .mm-active').removeClass('mm-active');
					$('a[href="' + dashboard_active + '"]').parent().addClass('mm-active');
				} 
				

		if(dashboard_active == '#SEO#RANKING'){
			if($('#Ranking').find('.tabs-animation').length == 0){
				$("#DashboardSection").append('<div id="Ranking" class="mainDashboardSection tab-pane fade in show"></div>'); 			
				$("#Ranking").load('/view/ranking_content/'+$('.campaignID').val()); 		
				$('.mainDashboardSection').removeClass('active');
				$('#Ranking').addClass('in show active');	

				setTimeout(function(){		
					ranking_chart();		
					updateTimeAgo();				
					ajaxGoogleSearchConsoleRanking($('.campaignID').val());
					keywordsMetricBarChartRanking($('.campaignID').val());
					keywordsMetricPieChartRanking($('.campaignID').val());
					ranking_page_scripts($('.campaignID').val());
					keywordSince($('.campaignID').val());
				}, 2000);
				
			}else{
				$('.mainDashboardSection').removeClass('active');
				$('#Ranking').addClass('in show active');
			}		 
		}


		if(dashboard_active == '#SEO#TRAFFIC'){
			if($('#Traffic').find('.tabs-animation').length == 0){
				$("#DashboardSection").append('<div id="Traffic" class="mainDashboardSection tab-pane fade in show"></div>'); 			
				$("#Traffic").load('/view/traffic_content/'+$('.campaignID').val()); 		
				$('.mainDashboardSection').removeClass('active');
				$('#Traffic').addClass('in show active');	

				setTimeout(function(){		
					ajaxviewGoogleTrafficGrowth($('.campaignID').val());
				}, 2000);
				
			}else{
				$('.mainDashboardSection').removeClass('active');
				$('#Traffic').addClass('in show active');
			}		 
		}

		if(dashboard_active == '#SEO#BACKLINKS'){
			if($('#Backlinks').find('.tabs-animation').length == 0){
				$("#DashboardSection").append('<div id="Backlinks" class="mainDashboardSection tab-pane fade in show"></div>'); 			
				$("#Backlinks").load('/view/backlinks_content/'+$('.campaignID').val()); 		
				$('.mainDashboardSection').removeClass('active');
				$('#Backlinks').addClass('in show active');	

				setTimeout(function(){		
					backlinksDatatable($('.campaignID').val());
				}, 2000);
				
			}else{
				$('.mainDashboardSection').removeClass('active');
				$('#Backlinks').addClass('in show active');
			}		 
		}

		if(dashboard_active == '#SEO#LEADS'){
			if($('#Leads').find('.tabs-animation').length == 0){
				$("#DashboardSection").append('<div id="Leads" class="mainDashboardSection tab-pane fade in show"></div>'); 			
				$("#Leads").load('/view/leads_content/'+$('.campaignID').val()); 		
				$('.mainDashboardSection').removeClass('active');
				$('#Leads').addClass('in show active');	

				setTimeout(function(){		
					goalCompletionDatatable($('.campaignID').val());
				}, 2000);
				
			}else{
				$('.mainDashboardSection').removeClass('active');
				$('#Leads').addClass('in show active');
			}		 
	}
		
	}
});

$('.sidebarLinks').on('click',function(){
	var href  = $(this).attr('href');
	// console.log('href'+href);
	var splitValue = href.split('#');
	var url = '#'+ splitValue[1];

	if(href == '#SEO#RANKING'){
		if($('#Ranking').find('.tabs-animation').length == 0){
			$("#DashboardSection").append('<div id="Ranking" class="mainDashboardSection tab-pane fade in show"></div>'); 			
			$("#Ranking").load('/view/ranking_content/'+$('.campaignID').val()); 		
			$('.mainDashboardSection').removeClass('active');
			$('#Ranking').addClass('in show active');	

			setTimeout(function(){	
				ranking_chart();
				updateTimeAgo();						
				ajaxGoogleSearchConsoleRanking($('.campaignID').val());
				keywordsMetricBarChartRanking($('.campaignID').val());
				keywordsMetricPieChartRanking($('.campaignID').val());
				ranking_page_scripts($('.campaignID').val());
				keywordSince($('.campaignID').val());
			}, 2000);
		}else{
			$('.mainDashboardSection').removeClass('active');
			$('#Ranking').addClass('in show active');
		}
	}

	if(href == '#SEO#TRAFFIC'){
		if($('#Traffic').find('.tabs-animation').length == 0){
			$("#DashboardSection").append('<div id="Traffic" class="mainDashboardSection tab-pane fade in show"></div>'); 			
			$("#Traffic").load('/view/traffic_content/'+$('.campaignID').val()); 		
			$('.mainDashboardSection').removeClass('active');
			$('#Traffic').addClass('in show active');	
			setTimeout(function(){		
				ajaxviewGoogleTrafficGrowth($('.campaignID').val());
			}, 2000);

			
		}else{
			$('.mainDashboardSection').removeClass('active');
			$('#Traffic').addClass('in show active');
		}		 
	}

	if(href == '#SEO#BACKLINKS'){
			if($('#Backlinks').find('.tabs-animation').length == 0){
				$("#DashboardSection").append('<div id="Backlinks" class="mainDashboardSection tab-pane fade in show"></div>'); 			
				$("#Backlinks").load('/view/backlinks_content/'+$('.campaignID').val()); 		
				$('.mainDashboardSection').removeClass('active');
				$('#Backlinks').addClass('in show active');	

				setTimeout(function(){		
					backlinksDatatable($('.campaignID').val());
				}, 2000);
				
			}else{
				$('.mainDashboardSection').removeClass('active');
				$('#Backlinks').addClass('in show active');
			}		 
	}

	if(href == '#SEO#LEADS'){
			if($('#Leads').find('.tabs-animation').length == 0){
				$("#DashboardSection").append('<div id="Leads" class="mainDashboardSection tab-pane fade in show"></div>'); 			
				$("#Leads").load('/view/leads_content/'+$('.campaignID').val()); 		
				$('.mainDashboardSection').removeClass('active');
				$('#Leads').addClass('in show active');	

				setTimeout(function(){		
					goalCompletionDatatable($('.campaignID').val());
				}, 2000);
				
			}else{
				$('.mainDashboardSection').removeClass('active');
				$('#Leads').addClass('in show active');
			}		 
	}

});

$(document).on('click','.searchConsoleRank',function(){
	var value = $(this).attr('data-value');
	var module = $(this).attr('data-module');
	var campaignId = $('.campaignID').val();

	$('.sc_section').removeClass('active');
	$(this).addClass('active');
	$('.graph-loader.searchConsole').css('display','block');
	$('.searchConsoleLoader').show();

	$.ajax({
		type:"GET",
		url:BASE_URL+"/view/ajax_googleSearchConsole",
		data:{value: value,module:module,campaignId:campaignId},
		dataType:'json',
		success:function(result){
			if(result['message'] !=''){
				$('#console_add').css('display','block');
			} else{
				$('#console_add').css('display','none');
			}

			$('#console_loader').hide();
			if(result!=''){
				consoleChartranking(result['clicks'],result['impressions']);
				$('.graph-loader.searchConsole').css('display','none');
				$('#console_add').css('display','none');
				$('.searchConsoleLoader').hide();
			}
			

		}
	});			
});


function ajaxGoogleSearchConsoleRanking(campaignId){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/view/ajax_googleSearchConsole",
		data:{campaignId: campaignId},
		dataType:'json',
		success:function(result){
			if(result['status'] == 0){
				$('#console_add').css('display','block');
			} 

			if(result['status'] == 1){
				if(result['query'] !=''){
					$('.rank_query_table').html(result['query']);
					consoleChartranking(result['clicks'],result['impressions']);
					$('#console_add').css('display','none');
				}		
			}
			$('.searchConsoleLoader').css('display','none');			
		}
	});
}


function consoleChartranking(clicks,impressions){

	configSearchConsoleRanking.data.datasets[0].data = clicks;
	configSearchConsoleRanking.data.datasets[1].data = impressions;
	window.myLineSearchConsoleRank.update();
}

function keywordsMetricBarChartRanking(campaignId){
	$.ajax({
		type: "GET",
		url: BASE_URL+"/view/keywordsMetricBarChart",
		data: {campaignId:campaignId},
		dataType: 'json',
		success: function(result){
			KeywordconfigRanking.data.labels =  JSON.parse(result['names']);
			KeywordconfigRanking.data.datasets[0].data = JSON.parse(result['values']);
			window.myLineKeywordRank.update();
		}
	});
}

function keywordsMetricPieChartRanking(campaignId){
	$.ajax({
		type: "GET",
		url: BASE_URL+"/view/keywordsMetricPieChart",
		data: {campaignId:campaignId},
		dataType: 'json',
		success: function(result){
			KeywordChartConfigRanking.data.labels =  JSON.parse(result['names']);
			KeywordChartConfigRanking.data.datasets[0].data = JSON.parse(result['values']);
			window.myLinePieRank.update();
		}
	});
}

function ranking_page_scripts(campaignId){

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


	$('#LiveKeywordTrackingTableRanking').DataTable({
		processing: true,
		serverSide: true,
		"order": [[2, "asc"]],
		'ajax': {
			'url': BASE_URL + '/view/ajaxLiveKeywordTrackingData',
			'data': function (data) {
				data.campaign_id = campaignId;
			}
		} 			
	});		

	$('#google_organic_keywords_ranking').DataTable({
		processing: true,
		serverSide: true,
		"deferRender": true,
		"order": [[1, "asc"]],
		'ajax': {
			'url': BASE_URL + '/view/ajaxOrganicKeywords',
			'data': function (data) {
				data.campaign_id = campaignId;

			}
		}
	});
}

function  drawChartGraphRanking(requestId, days) {
	var keywordId = localStorage.getItem("keywordId");		
	drawChartRanking(keywordId, requestId, days)

}

function drawChartRanking(keyword_id, request_id, duration ) {
	$.ajax({
		type: "POST",
		url: BASE_URL + "/view/ajax_live_keyword_chart", 
		data: { keyword_id: keyword_id,request_id: request_id,duration: duration},
		success: function(result) {
			var series =  [{
				name: 'google',
				data: result['rank']
			},
			];
			createAnalyticsChartsRanking(series, result['month'], result['keyword']);
		}
	});
}

function createAnalyticsChartsRanking(seriesData, month, keyword) {
	Highcharts.chart('keywordchartConatinerRanking', {
		chart: {
			type: 'line'
		},
		title: {text: keyword},
		yAxis: {
			reversed: true,
			title: {
				text: 'Rank'
			},
			plotLines: [{
				value: 0,
				width: 1,
				color: '#808080'
			}]  
		},
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'middle',
			borderWidth: 0
		},
		xAxis: {categories:month},
		tooltip: {
			valueSuffix: ''
		},
		series: seriesData,
	});
}

$(document).on('click','#close-graph',function(){
	$('#liveKeywordTrackingChartRanking').addClass('hide');
});


$("#LiveKeywordTrackingTableRanking").on("click", ".chart-icon", function(){
	var keyword_id =  $(this).data('id');
	var request_id =  $(this).data('index');
	var duration =  '-30 day';
	$('#liveKeywordTrackingChartRanking').removeClass('hide');
	$('html, body').animate({
		scrollTop: $("#keywordchartConatinerRanking").offset().top
	}, 100);

	localStorage.setItem("keywordId", keyword_id);
	drawChartRanking(keyword_id,request_id, duration);
});

function keywordSince(campaignId){
	$.ajax({
		type: "GET",
		url: BASE_URL+"/view/keywordSince",
		data: {campaignId:campaignId},
		dataType: 'json',
		success: function(result){
			$('.total').html('/ '+result['total']);
			$('#lifetime').html(result['lifetime']);
			$('#three').html(result['three']);				
			$('#ten').html(result['ten']);
			$('#twenty').html(result['twenty']);
			$('#fifty').html(result['fifty']);
			$('#hundred').html(result['hundred']);

				//since values
				$('#since_three').html('+'+ result['since_three']);
				$('#since_ten').html('+'+ result['since_ten']);
				$('#since_twenty').html('+'+ result['since_twenty']);
				$('#since_fifty').html('+'+ result['since_fifty']);
				$('#since_hundred').html('+'+ result['since_hundred']);
			}
		});
}

function rank(evt, cityName) {

	var i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("tabcontent");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablinks");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");
	}
	document.getElementById(cityName).style.display = "block";
	evt.currentTarget.className += " active";
}

function ajaxviewGoogleTrafficGrowth(campaignId){
	$.ajax({
		type:"GET",
		url: BASE_URL+"/view/ajax_traffic_growth_data",
		data:{campaignId: campaignId},
		dataType:'json',
		success:function(result){

			if(result['status'] == 0){
				$('#analatic_add').css('display','block');
			}
			if(result['status'] == 1){
				$('.TrafficGrowth').text(result['total_sessions']);
				$('.comparedTrafficGrowth').text(result['final_session']);
				var trafficcGrowth = document.getElementsByClassName("TrafficGrowth");
				if(result['total_sessions'] > 0){
					$(trafficcGrowth).parent().find('i').addClass("fa-angle-up");	
					$(trafficcGrowth).parent().find('i').addClass("text-success");
				}else{
					$(trafficcGrowth).parent().find('i').addClass("fa-angle-down");
					$(trafficcGrowth).parent().find('i').addClass("text-danger");
				}
				
				
				
				$('.TotalSessions').text(result['total_users']);
				$('.comparedUsers').text(result['final_users']);
				
				var sess = document.getElementsByClassName("TotalSessions");
				if(result['total_users'] > 0 ){
					$(sess).parent().find('i').addClass("fa-angle-up");	
					$(sess).parent().find('i').addClass("text-success");
				}else{
					$(sess).parent().find('i').addClass("fa-angle-down");
					$(sess).parent().find('i').addClass("text-danger");
				}
				
				
				$('.TotalPageViews').text(result['total_pageview']);
				$('.comparedPageViews').text(result['final_pageView']);
				
				var pagesview = document.getElementsByClassName("TotalPageViews");
				if(result['total_pageview'] > 0 ){
					$(pagesview).parent().find('i').addClass("fa-angle-up");	
					$(pagesview).parent().find('i').addClass("text-success");
				}else{
					$(pagesview).parent().find('i').addClass("fa-angle-down");
					$(pagesview).parent().find('i').addClass("text-danger");
				}
				
				
				
				$('.GoogleTraffic_growth').text(result['traffic_growth']);
				$('.GoogleOrganicVisitors').text(result['current_session']);

				$('.trafficgrowthLoader').hide();
				highChartMaploadVK(result);

				$('#analatic_add').css('display','none');
			}
		}
	});
}

function highChartMaploadVK(result) {	  
	if (window.myLineTrafficViewkey) {
		window.myLineTrafficViewkey.destroy();
	}
	var ctxTrafficGrowth = document.getElementById('canvas-traffic-growth-viewkey').getContext('2d');
	window.myLineTrafficViewkey = new Chart(ctxTrafficGrowth, configTrafficGrowthViewKey);

	configTrafficGrowthViewKey.data.labels =  result['from_datelabel'];
	configTrafficGrowthViewKey.data.datasets[0].label = 'Current Period: '+result['current_period'];
	configTrafficGrowthViewKey.data.datasets[0].data = result['count_session'];

	if(result['compare_status'] == '1'){
		var newDataset = {
			label: 'Previous Period: '+result['previous_period'],
			borderColor: color(window.chartColors.red).alpha(1.0).rgbString(),
			backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
			data: result['combine_session'],
		};
		configTrafficGrowthViewKey.data.datasets.push(newDataset);

	}
	else{
		configTrafficGrowthViewKey.data.datasets.splice(1, 1);

	}
	window.myLineTrafficViewkey.update();

}


$(document).on('click','.graph_rangeViewKey',function(){
	var value = $(this).attr('data-value');
	var module = $(this).attr('data-module');
	var campaignId = $('.campaignID').val();

	$('.trafficSection').removeClass('active');
	$(this).addClass('active');
	$('.graph-loader.organic_traffic').css('display','block');
	$('#traffic_loader').show();
	$.ajax({
		type:"GET",
		url:BASE_URL+"/view/ajax_traffic_growth_date_range",
		data:{value: value,module:module,campaignId:campaignId},
		dataType:'json',
		success:function(result){

			$('.TrafficGrowth').text(result['total_sessions']);
			$('.comparedTrafficGrowth').text(result['final_session']);
			var trafficcGrowth = document.getElementsByClassName("TrafficGrowth");
			if(result['total_sessions'] > 0){
				$(trafficcGrowth).parent().find('i').addClass("fa-angle-up");	
				$(trafficcGrowth).parent().find('i').addClass("text-success");
			}else{
				$(trafficcGrowth).parent().find('i').addClass("fa-angle-down");
				$(trafficcGrowth).parent().find('i').addClass("text-danger");
			}



			$('.TotalSessions').text(result['total_users']);
			$('.comparedUsers').text(result['final_users']);

			var sess = document.getElementsByClassName("TotalSessions");
			if(result['total_users'] > 0 ){
				$(sess).parent().find('i').addClass("fa-angle-up");	
				$(sess).parent().find('i').addClass("text-success");
			}else{
				$(sess).parent().find('i').addClass("fa-angle-down");
				$(sess).parent().find('i').addClass("text-danger");
			}


			$('.TotalPageViews').text(result['total_pageview']);
			$('.comparedPageViews').text(result['final_pageView']);

			var pagesview = document.getElementsByClassName("TotalPageViews");
			if(result['total_pageview'] > 0 ){
				$(pagesview).parent().find('i').addClass("fa-angle-up");	
				$(pagesview).parent().find('i').addClass("text-success");
			}else{
				$(pagesview).parent().find('i').addClass("fa-angle-down");
				$(pagesview).parent().find('i').addClass("text-danger");
			}



			$('.GoogleTraffic_growth').text(result['traffic_growth']);
			$('.GoogleOrganicVisitors').text(result['current_session']);

			//load chart for organic traffic-growth
			highChartMapVK(result);
			$('#traffic_loader').hide();					
			$('.graph-loader.organic_traffic').css('display','none');
		}
	});
});

function highChartMapVK(result){
	window.myLineTrafficViewkey.data.labels =  result['from_datelabel'];
	window.myLineTrafficViewkey.data.datasets[0].label = 'Current Period: '+result['current_period'];
	window.myLineTrafficViewkey.data.datasets[0].data = result['count_session'];

	if(result['compare_status'] == '1'){

		window.myLineTrafficViewkey.data.datasets[1].label = 'Previous Period: '+result['previous_period'];
		window.myLineTrafficViewkey.data.datasets[1].data = result['combine_session'];
	}

	window.myLineTrafficViewkey.update();
}

function backlinksDatatable(campaignId){
	$('#backlink_profile_viewkey').DataTable({
		"processing":true,
		"serverSide":true,
		"ajax":{
			'url':BASE_URL + "/view/ajax_backlink_profile_datatable",
			'data': function (data) {
				data.campaign_id = campaignId;
			}
		},

		"order": [[ 6, "asc" ]],
		"columnDefs":[{ "targets":[6], "orderable":false, }],
	});
}

function goalCompletionDatatable(campaignId){
	$('#googleAnalyticsGoalCompletionVIewKey').DataTable({
		"processing":true,
		"serverSide":true,
		"searching": false,
		"bLengthChange": false,
		"ordering": false,
		"ajax":{
			'url':BASE_URL + "/view/ajax_google_analytics_goal_completion",
			'data': function (data) {
				data.campaign_id = campaignId;
			}
		}
	});
}