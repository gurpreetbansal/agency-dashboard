var BASE_URl = $('.base_url').val();

var configSummary = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			backgroundColor: window.chartColors.orange,
			borderColor: window.chartColors.orange,
			data: [],
			label: 'Clicks',
			fill: false,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth: 2
		},
		{
			backgroundColor: window.chartColors.greyBlue,
			borderColor: window.chartColors.greyBlue,
			data: [],
			label: 'Conversions',
			fill: false,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth: 2
		},
		{
			backgroundColor: window.chartColors.lightGreen,
			borderColor: window.chartColors.lightGreen,
			data: [],
			label: 'Impressions',
			fill: false,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth: 2
		}		
		]
	},
	options: {
		maintainAspectRatio: false,
		spanGaps: false,
		elements: {
			line: {
				tension: 0.000001
			}
		},
		scales: {
			yAxes: [{}],
      xAxes: [{
        type: 'time',
        distribution: 'series',
        offset: true,
        ticks: {
          autoSkip: true,
          maxRotation: 0,
          minRotation: 0,
          maxTicksLimit: 10
        },
        gridLines: {
          color: "rgba(0, 0, 0, 0)"
        },

      }]
		},
		tooltips: {
			intersect: false,
			mode: 'index',
			backgroundColor: 'rgb(255, 255, 255)',
			titleFontColor: '#000',
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
			labels: {
				boxWidth: 10
			}
		}

	}
};

var configPerformance = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			backgroundColor: window.chartColors.orange,
			borderColor: window.chartColors.orange,
			data: [0],
			label: 'Cost',
			fill: false,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth: 2
    },
    {
     backgroundColor: window.chartColors.greyBlue,
     borderColor: window.chartColors.greyBlue,
     data: [0],
     label: 'Cost per Click',
     fill: false,
     pointHoverRadius: 5,
     pointHoverBackgroundColor: 'white',
     borderWidth: 2
   },
   {
     backgroundColor: window.chartColors.lightGreen,
     borderColor: window.chartColors.lightGreen,
     data: [0],
     label: 'Cost per 1000 Impressions',
     fill: false,
     pointHoverRadius: 5,
     pointHoverBackgroundColor: 'white',
     borderWidth: 2,
   },
   {
     backgroundColor: window.chartColors.lightPurple,
     borderColor: window.chartColors.lightPurple,
     data: [0],
     label: 'Revenue Per Click',
     fill: false,
     pointHoverRadius: 5,
     pointHoverBackgroundColor: 'white',
     borderWidth: 2
   },
   {
     backgroundColor: window.chartColors.bottleGreen,
     borderColor: window.chartColors.bottleGreen,
     data: [0],
     label: 'Total Value',
     fill: false,
     pointHoverRadius: 5,
     pointHoverBackgroundColor: 'white',
     borderWidth: 2
   }
   ]
 },
 options: {
   maintainAspectRatio: false,
   spanGaps: false,
   elements: {
    line: {
     tension: 0.000001
   }
 },
 scales: {
  yAxes: [{}],
  xAxes: [{
    type: 'time',
    distribution: 'series',
    offset: true,
    ticks: {
      autoSkip: true,
      maxRotation: 0,
      minRotation: 0,
      maxTicksLimit: 10
    },
    gridLines: {
      color: "rgba(0, 0, 0, 0)"
    },

  }]
},
tooltips: {
  intersect: false,
  mode: 'index',
  backgroundColor: 'rgb(255, 255, 255)',
  titleFontColor: '#000',
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
  labels: {
   boxWidth: 10
 }
}
}
};


var configPerformanceDatas = {
  type: 'line',
  data: {
    labels: [],
    datasets: [{
      backgroundColor: window.chartColors.orange,
      borderColor: window.chartColors.orange,
      data: [0],
      label: 'Cost',
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth: 2
    },
    {
     backgroundColor: window.chartColors.greyBlue,
     borderColor: window.chartColors.greyBlue,
     data: [0],
     label: 'Cost per Click',
     fill: false,
     pointHoverRadius: 5,
     pointHoverBackgroundColor: 'white',
     borderWidth: 2
   },
   {
     backgroundColor: window.chartColors.lightGreen,
     borderColor: window.chartColors.lightGreen,
     data: [0],
     label: 'Cost per 1000 Impressions',
     fill: false,
     pointHoverRadius: 5,
     pointHoverBackgroundColor: 'white',
     borderWidth: 2,
   },
   {
     backgroundColor: window.chartColors.lightPurple,
     borderColor: window.chartColors.lightPurple,
     data: [0],
     label: 'Revenue Per Click',
     fill: false,
     pointHoverRadius: 5,
     pointHoverBackgroundColor: 'white',
     borderWidth: 2
   },
   {
     backgroundColor: window.chartColors.bottleGreen,
     borderColor: window.chartColors.bottleGreen,
     data: [0],
     label: 'Total Value',
     fill: false,
     pointHoverRadius: 5,
     pointHoverBackgroundColor: 'white',
     borderWidth: 2
   }
   ]
 },
 options: {
   maintainAspectRatio: false,
   spanGaps: false,
   elements: {
    line: {
     tension: 0.000001
   }
 },
 scales: {
  yAxes: [{}],
  xAxes: [{
    type: 'time',
    distribution: 'series',
    offset: true,
    ticks: {
      autoSkip: true,
      maxRotation: 0,
      minRotation: 0,
      maxTicksLimit: 10
    },
    gridLines: {
      color: "rgba(0, 0, 0, 0)"
    },

  }]
},
tooltips: {
  intersect: false,
  mode: 'index',
  backgroundColor: 'rgb(255, 255, 255)',
  titleFontColor: '#000',
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
  labels: {
   boxWidth: 10
 }
}
}
};


$(document).on('click','#refresh_ppc_section',function(e){
  e.preventDefault();

  var campaign_id = $(this).attr('data-request-id');

  var currenthis = $(this);
  
  currenthis.addClass('refresh-gif');
  ppc_laoders('addClass');
  $('.progress-loader').show();
  Command: toastr["success"]('Request has been sent successfully.');
  $.ajax({
    type:'GET',
    // url:BASE_URL+'/ajax_refresh_adwords_json',
    url:BASE_URL+'/ppc/refresh/campaigns',
    dataType:'json',
    data:{campaign_id},
    success:function(response){

      currenthis.removeClass('refresh-gif');
      $('.progress-loader').hide();
      $('.white-box-head .right').removeClass('ajax-loader');
      ppc_laoders('removeClass');
      ppc_Scripts(response['account_id'],campaign_id);


      if(response['status'] == 'google-error'){
        $('.GmbErrorHeading').append('');
        $('.GmbErrorHeading').append('<div class="alert alert-danger"><span>'+response['message']+'</span></div>');
      }

      if(response['status'] == 'error'){
        Command: toastr.remove();
        Command: toastr["error"](response['message']);
      }
      
      
    }
  });
});


function ppc_laoders(classType){

  if(classType == 'addClass'){

      $('.white-box-head .right').addClass('ajax-loader');
      $('.impressions-ads').addClass('ajax-loader');
      $('.cost-ads').addClass('ajax-loader');
      $('.click-ads').addClass('ajax-loader');
      $('.average-cpc-ads').addClass('ajax-loader');
      $('.ctr-ads').addClass('ajax-loader');
      $('.conversion-ads').addClass('ajax-loader');
      $('.conversion-rate-ads').addClass('ajax-loader');
      $('.cost-per-conversion-rate-ads').addClass('ajax-loader');
      
      $('.sv-overview-avg').addClass('ajax-loader');

      $('.summary_chart').addClass('ajax-loader');
      $('.performance_chart').addClass('ajax-loader');

      $('.costs_graph').addClass('ajax-loader');
      $('.costs_graph').show();
      $('#summary-cost-chart').hide();

      $('.impressions_graph').addClass('ajax-loader');
      $('.impressions_graph').show();
      $('#summary-impressions-chart').hide();

      $('.summary_clicks_graph').addClass('ajax-loader');
      $('.summary_clicks_graph').show();
      $('#summary-clicks-chart').hide(); 

      $('.average_cpc_graph').addClass('ajax-loader');
      $('.average_cpc_graph').show();
      $('#summary-averageCpc-chart').hide();

      $('.ctr_graph').addClass('ajax-loader');
      $('.ctr_graph').show();
      $('#summary-ctrAds-chart').hide();
        
      $('.conversion_rate_graph').addClass('ajax-loader');
      $('.conversion_rate_graph').show();
      $('#summary-conversionRate-chart').hide();

      $('.conversions_graph').addClass('ajax-loader');
      $('.conversions_graph').show();
      $('#summary-conversionAds-chart').hide();
          
      $('.cpc_rate_graph').addClass('ajax-loader');
      $('.cpc_rate_graph').show();
      $('#summary-costPerConversionRate-chart').hide();

      $('#ads-campaign-list tbody tr td').addClass('ajax-loader');
      $('#adGroup-list tbody tr td').addClass('ajax-loader');
      $('#ads-keyword-list tbody tr td').addClass('ajax-loader');
      $('#ads-list tbody tr td').addClass('ajax-loader');
      $('#ads_performce_network-list tbody tr td').addClass('ajax-loader');
      $('#ads_performce_device-list tbody tr td').addClass('ajax-loader');
      $('#ads_performce_clickType-list tbody tr td').addClass('ajax-loader');
      $('#performance-adSlot-list tbody tr td').addClass('ajax-loader');

      $('.pagination').addClass('ajax-loader');
      $('.project-entries').addClass('ajax-loader');

      
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


function summary_overview(account_id, campaign_id) {
 $.ajax({
  type: "GET",
  url: BASE_URL + "/summary_statistics",
  data: {
   account_id,
   campaign_id
 },
 dataType: 'json',
 beforeSend: function() {
   $('.loader').fadeIn(600, function() {
    $('.loader').add();
  });
 },
 success: function(response) {

   var prev_imp = prev_clicks = previous_cost = previous_conversions = previous_cost_per_conversion = previous_ctr = previous_conversion_rate = previous_average_cpc = compare_date = '';
   if (response['compare'] == true) {


    if (response['previous_impressions'] != "") {
     var prev_imp = ' vs ' + response['previous_impressions'];
     }
     if (response['previous_clicks'] != "") {
       var prev_clicks = ' vs ' + response['previous_clicks'];
     }
     if (response['previous_cost'] != "") {
       var previous_cost = ' vs ' + response['previous_cost'];
     }
     if (response['previous_conversions'] != "") {
       var previous_conversions = ' vs ' + response['previous_conversions'];
     }
     if (response['previous_cost_per_conversion'] != "") {
       var previous_cost_per_conversion = ' vs ' + response['previous_cost_per_conversion'];
     }
     if (response['previous_ctr'] != "") {
       var previous_ctr = ' vs ' + response['previous_ctr'];
     }
     if (response['previous_conversion_rate'] != "") {
       var previous_conversion_rate = ' vs ' + response['previous_conversion_rate'];
     }
     if (response['previous_average_cpc'] != "") {
       var previous_average_cpc = ' vs ' + response['previous_average_cpc'];
     }

     if (response['compare_date'] != "") {
       var compare_date = ' (compared to  ' + response['compare_date'] + ' )';
     }

     $('.percentage-values').css('display', 'block');
  }




   $('.impressions-ads').html(response['impressions'] + prev_imp);
   $('.cost-ads').html(response['cost'] + previous_cost);
   $('.click-ads').html(response['clicks'] + prev_clicks);
   $('.average-cpc-ads').html(response['average_cpc'] + previous_average_cpc);
   $('.ctr-ads').html(response['ctr'] + previous_ctr);
   $('.conversion-ads').html(response['conversions'] + previous_conversions);
   $('.conversion-rate-ads').html(response['conversion_rate'] + previous_conversion_rate);
   $('.cost-per-conversion-rate-ads').html(response['cost_per_conversion'] + previous_cost_per_conversion);


   $('.impressions-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + response['impressions_percentage'] + '%');

    if (response['impressions_percentage'] > '0') {
      $('.impressions-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
      $('.impressions-ads-percentage').removeClass("red");
      $('.impressions-ads-percentage').addClass("green");
    } else if (response['impressions_percentage'] < '0') {
      $('.impressions-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
      $('.impressions-ads-percentage').removeClass("green");
      $('.impressions-ads-percentage').addClass("red");
    } else {
      $('.impressions-ads-percentage').find('span').removeAttr("uk-icon");
    }

    $('.cost-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + response['costs_percentage'] + '%');

    if (response['costs_percentage'] > '0') {
      $('.cost-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
      $('.cost-ads-percentage').removeClass("red");
      $('.cost-ads-percentage').addClass("green");
    } else if (response['costs_percentage'] < '0') {
      $('.cost-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
      $('.cost-ads-percentage').removeClass("green");
      $('.cost-ads-percentage').addClass("red");
    } else {
      $('.cost-ads-percentage').find('span').removeAttr("uk-icon");
    }


    $('.clicks-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + response['clicks_percentage'] + '%');

    if (response['clicks_percentage'] > '0') {
      $('.clicks-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
      $('.clicks-ads-percentage').removeClass("red");
      $('.clicks-ads-percentage').addClass("green");
    } else if (response['clicks_percentage'] < '0') {
      $('.clicks-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
      $('.clicks-ads-percentage').removeClass("green");
      $('.clicks-ads-percentage').addClass("red");
    } else {
      $('.clicks-ads-percentage').find('span').removeAttr("uk-icon");
    }


    $('.average-cpc-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + response['average_cpc_percentage'] + '%');

    if (response['average_cpc_percentage'] > '0') {
      $('.average-cpc-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
      $('.average-cpc-ads-percentage').removeClass("red");
      $('.average-cpc-ads-percentage').addClass("green");
    } else if (response['average_cpc_percentage'] < '0') {
      $('.average-cpc-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
      $('.average-cpc-ads-percentage').removeClass("green");
      $('.average-cpc-ads-percentage').addClass("red");
    } else {
      $('.average-cpc-ads-percentage').find('span').removeAttr("uk-icon");
    }

    $('.ctr-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + response['ctr_percentage'] + '%');

    if (response['ctr_percentage'] > '0') {
      $('.ctr-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
      $('.ctr-ads-percentage').removeClass("red");
      $('.ctr-ads-percentage').addClass("green");
    } else if (response['ctr_percentage'] < '0') {
      $('.ctr-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
      $('.ctr-ads-percentage').removeClass("green");
      $('.ctr-ads-percentage').addClass("red");
    } else {
      $('.ctr-ads-percentage').find('span').removeAttr("uk-icon");
    }

  $('.conversion-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + response['conversions_percentage'] + '%');

  if (response['conversions_percentage'] > '0') {
    $('.conversion-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
    $('.conversion-ads-percentage').removeClass("red");
    $('.conversion-ads-percentage').addClass("green");
  } else if (response['conversions_percentage'] < '0') {
    $('.conversion-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
    $('.conversion-ads-percentage').removeClass("green");
    $('.conversion-ads-percentage').addClass("red");
  } else {
    $('.conversion-ads-percentage').find('span').removeAttr("uk-icon");
  }

  $('.conversion-rate-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + response['conversion_rates_percentage'] + '%');

  if (response['conversion_rates_percentage'] > '0') {
    $('.conversion-rate-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
    $('.conversion-rate-ads-percentage').removeClass("red");
    $('.conversion-rate-ads-percentage').addClass("green");
  } else if (response['conversion_rates_percentage'] < '0') {
    $('.conversion-rate-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
    $('.conversion-rate-ads-percentage').removeClass("green");
    $('.conversion-rate-ads-percentage').addClass("red");
  } else {
    $('.conversion-rate-ads-percentage').find('span').removeAttr("uk-icon");
  }

  $('.cost-per-conversion-rate-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + response['cost_per_conversions_percentage'] + '%');

  if (response['cost_per_conversions_percentage'] > '0') {
    $('.cost-per-conversion-rate-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
    $('.cost-per-conversion-rate-ads-percentage').removeClass("red");
    $('.cost-per-conversion-rate-ads-percentage').addClass("green");
  } else if (response['cost_per_conversions_percentage'] < '0') {
    $('.cost-per-conversion-rate-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
    $('.cost-per-conversion-rate-ads-percentage').removeClass("green");
    $('.cost-per-conversion-rate-ads-percentage').addClass("red");
  } else {
    $('.cost-per-conversion-rate-ads-percentage').find('span').removeAttr("uk-icon");
  }

  $('.sv-overview').removeClass('ajax-loader');
}

});
}

function summary_chart(account_id, campaign_id) {

	var ctx = document.getElementById('chart-summary').getContext('2d');
	window.myLine = new Chart(ctx, configSummary);

	$.ajax({
		type: "GET",
		url: BASE_URL + "/ppc_date_range_data",
		data: {account_id,campaign_id},
		dataType: 'json',
		success: function(result) {
			configSummaryData(result);

		}
	});
}

function summary_chart_keyword(account_id, campaign_id) {
  
  var ctx = document.getElementById('chart-summary-keywords').getContext('2d');
  window.myLine = new Chart(ctx, configSummary);


  $.ajax({
    type: "GET",
    url: BASE_URL + "/ppc/summaries",
    data: { account_id ,campaign_id },
    dataType: 'json',
    success: function(result) {
      configSummaryDataSub(result);

    }
  });
}

function configSummaryDataSub(result) {
  
	if (window.myLine) {
		window.myLine.destroy();
	}
	var ctx = document.getElementById('chart-summary-keywords').getContext('2d');
	window.myLine = new Chart(ctx, configSummary);

	window.myLine.data.labels = result['summaryGraph']['date_range'];
	window.myLine.data.datasets[0].data = result['summaryGraph']['clicks'];
	window.myLine.data.datasets[1].data = result['summaryGraph']['conversions'];
	window.myLine.data.datasets[2].data = result['summaryGraph']['impressions'];

	if(result['compare'] == 1){
    configSummary.data.datasets.splice(3,3);
    configSummary.data.datasets.splice(4,4);
    configSummary.data.datasets.splice(5,5);

    var Dataset = {
     backgroundColor: window.chartColors.mauve,
     borderColor: window.chartColors.mauve,
     data: result['summaryPrevious']['clicks_previous'],
     label: 'Clicks:Previous',
     fill: false,
     pointHoverRadius: 5,
     pointHoverBackgroundColor: 'white',
     borderWidth: 2
   };

   var Dataset1 = 	{
     backgroundColor: window.chartColors.fuschiapink,
     borderColor: window.chartColors.fuschiapink,
     data: result['summaryPrevious']['conversions_previous'],
     label: 'Conversions:Previous',
     fill: false,
     pointHoverRadius: 5,
     pointHoverBackgroundColor: 'white',
     borderWidth: 2
   };
   var Dataset2 = {
     backgroundColor: window.chartColors.pink,
     borderColor: window.chartColors.pink,
     data: result['summaryPrevious']['impressions_previous'],
     label: 'Impressions:Previous',
     fill: false,
     pointHoverRadius: 5,
     pointHoverBackgroundColor: 'white',
     borderWidth: 2
   };

     configSummary.data.datasets.push(Dataset);
     configSummary.data.datasets.push(Dataset1);
     configSummary.data.datasets.push(Dataset2);
   }else{
    configSummary.data.datasets.splice(3,3);
    configSummary.data.datasets.splice(4,4);
    configSummary.data.datasets.splice(5,5);
  }
  window.myLine.update();
  $('.summary_chart').removeClass('ajax-loader');
}


function configSummaryData(result) {

  if (window.myLine) {
    window.myLine.destroy();
  }
  var ctx = document.getElementById('chart-summary').getContext('2d');
  window.myLine = new Chart(ctx, configSummary);

  window.myLine.data.labels = result['date_range'];
  window.myLine.data.datasets[0].data = result['clicks'];
  window.myLine.data.datasets[1].data = result['conversions'];
  window.myLine.data.datasets[2].data = result['impressions'];

  if(result['compare'] == 1){
    
    configSummary.data.datasets.splice(3,3);
    configSummary.data.datasets.splice(4,4);
    configSummary.data.datasets.splice(5,5);

    var Dataset = {
       backgroundColor: window.chartColors.mauve,
       borderColor: window.chartColors.mauve,
       data: result['clicks_previous'],
       label: 'Clicks:Previous',
       fill: false,
       pointHoverRadius: 5,
       pointHoverBackgroundColor: 'white',
       borderWidth: 2
     };

     var Dataset1 =   {
       backgroundColor: window.chartColors.fuschiapink,
       borderColor: window.chartColors.fuschiapink,
       data: result['conversions_previous'],
       label: 'Conversions:Previous',
       fill: false,
       pointHoverRadius: 5,
       pointHoverBackgroundColor: 'white',
       borderWidth: 2
     };
     var Dataset2 = {
       backgroundColor: window.chartColors.pink,
       borderColor: window.chartColors.pink,
       data: result['impressions_previous'],
       label: 'Impressions:Previous',
       fill: false,
       pointHoverRadius: 5,
       pointHoverBackgroundColor: 'white',
       borderWidth: 2
     };

     configSummary.data.datasets.push(Dataset);
     configSummary.data.datasets.push(Dataset1);
     configSummary.data.datasets.push(Dataset2);
   }else{
    configSummary.data.datasets.splice(3,3);
    configSummary.data.datasets.splice(4,4);
    configSummary.data.datasets.splice(5,5);
  }
  window.myLine.update();
  $('.summary_chart').removeClass('ajax-loader');
}


function performance_chart(account_id, campaign_id) {
	var ctxPerformance = document.getElementById('performance-chart').getContext('2d');
	window.myLinePerformance = new Chart(ctxPerformance, configPerformance);
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ppc_date_range_data",
		data: {account_id,campaign_id},
		dataType: 'json',
		success: function(result) {
			configPerformanceData(result);
		}
	});
}

function performance_chartData(account_id, campaign_id) {

  
  var ctxPerformance = document.getElementById('performance-chart-data').getContext('2d');
  window.myLinePerformanceDatas = new Chart(ctxPerformance, configPerformanceDatas);
  
   $.ajax({
    type: "GET",
    url: BASE_URL + "/ppc/summaries",
    data: { account_id ,campaign_id },
    dataType: 'json',
    success: function(result) {
      configPerformanceDataSub(result['performanceGraph']);
    }
  });
}

function configPerformanceDataSub(result) {

  if (window.myLinePerformanceDatas) {
    window.myLinePerformanceDatas.destroy();
  }

  var ctxPerformance = document.getElementById('performance-chart-data').getContext('2d');
  window.myLinePerformanceDatas = new Chart(ctxPerformance, configPerformanceDatas);

  window.myLinePerformanceDatas.data.labels = result['date_range'];

  window.myLinePerformanceDatas.data.datasets[0].data = result['cost'];
  window.myLinePerformanceDatas.data.datasets[1].data = result['cpc'];
  window.myLinePerformanceDatas.data.datasets[2].data = result['averagecpm'];
  window.myLinePerformanceDatas.data.datasets[3].data = result['revenue_per_click'];
  window.myLinePerformanceDatas.data.datasets[4].data = result['total_value'];

  if(result['compare'] == 1){
       configPerformanceDatas.data.datasets.splice(5,5);
       configPerformanceDatas.data.datasets.splice(6,6);
       configPerformanceDatas.data.datasets.splice(7,7);
       configPerformanceDatas.data.datasets.splice(8,8);
       configPerformanceDatas.data.datasets.splice(9,9);
       var dataset1 = {
         backgroundColor: window.chartColors.mauve,
         borderColor: window.chartColors.mauve,
         data: result['cost_previous'],
         label: 'Cost:Previous',
         fill: false,
         pointHoverRadius: 5,
         pointHoverBackgroundColor: 'white',
         borderWidth: 2
       };

       var dataset2 =  {
         backgroundColor: window.chartColors.fuschiapink,
         borderColor: window.chartColors.fuschiapink,
         data: result['cpc_previous'],
         label: 'Cost per Click:Previous',
         fill: false,
         pointHoverRadius: 5,
         pointHoverBackgroundColor: 'white',
         borderWidth: 2
       };
       var dataset3 = {
         backgroundColor: window.chartColors.pink,
         borderColor: window.chartColors.pink,
         data: result['averagecpm_previous'],
         label: 'Cost per 1000 Impressions:Previous',
         fill: false,
         pointHoverRadius: 5,
         pointHoverBackgroundColor: 'white',
         borderWidth: 2
       };
       var dataset4 =  {
         backgroundColor: window.chartColors.darkBlue,
         borderColor: window.chartColors.darkBlue,
         data: result['revenue_per_click_previous'],
         label: 'Revenue Per Click:Previous',
         fill: false,
         pointHoverRadius: 5,
         pointHoverBackgroundColor: 'white',
         borderWidth: 2
       };
       var dataset5 = {
         backgroundColor: window.chartColors.pearGreen,
         borderColor: window.chartColors.pearGreen,
         data: result['total_value_previous'],
         label: 'Total Value:Previous',
         fill: false,
         pointHoverRadius: 5,
         pointHoverBackgroundColor: 'white',
         borderWidth: 2
       };

       configPerformanceDatas.data.datasets.push(dataset1);
       configPerformanceDatas.data.datasets.push(dataset2);
       configPerformanceDatas.data.datasets.push(dataset3);
       configPerformanceDatas.data.datasets.push(dataset4);
       configPerformanceDatas.data.datasets.push(dataset5);



 }else{
    configPerformanceDatas.data.datasets.splice(5,5);
    configPerformanceDatas.data.datasets.splice(6,6);
    configPerformanceDatas.data.datasets.splice(7,7);
    configPerformanceDatas.data.datasets.splice(8,8);
    configPerformanceDatas.data.datasets.splice(9,9);
}

  window.myLinePerformanceDatas.update();
  $('.performance_chart').removeClass('ajax-loader');
  $('.performance-chart-data').removeClass('ajax-loader');
}

function configPerformanceData(result) {
	if (window.myLinePerformanceDatas) {
		window.myLinePerformanceDatas.destroy();
	}

	var ctxPerformance = document.getElementById('performance-chart').getContext('2d');
	window.myLinePerformanceDatas = new Chart(ctxPerformance, configPerformanceDatas);

	window.myLinePerformanceDatas.data.labels = result['date_range'];

	window.myLinePerformanceDatas.data.datasets[0].data = result['cost'];
	window.myLinePerformanceDatas.data.datasets[1].data = result['cpc'];
	window.myLinePerformanceDatas.data.datasets[2].data = result['averagecpm'];
	window.myLinePerformanceDatas.data.datasets[3].data = result['revenue_per_click'];
	window.myLinePerformanceDatas.data.datasets[4].data = result['total_value'];

	if(result['compare'] == 1){
     configPerformanceDatas.data.datasets.splice(5,5);
     configPerformanceDatas.data.datasets.splice(6,6);
     configPerformanceDatas.data.datasets.splice(7,7);
     configPerformanceDatas.data.datasets.splice(8,8);
     configPerformanceDatas.data.datasets.splice(9,9);
     var dataset1 = {
       backgroundColor: window.chartColors.mauve,
       borderColor: window.chartColors.mauve,
       data: result['cost_previous'],
       label: 'Cost:Previous',
       fill: false,
       pointHoverRadius: 5,
       pointHoverBackgroundColor: 'white',
       borderWidth: 2
     };

     var dataset2 =  {
       backgroundColor: window.chartColors.fuschiapink,
       borderColor: window.chartColors.fuschiapink,
       data: result['cpc_previous'],
       label: 'Cost per Click:Previous',
       fill: false,
       pointHoverRadius: 5,
       pointHoverBackgroundColor: 'white',
       borderWidth: 2
     };
     var dataset3 = {
       backgroundColor: window.chartColors.pink,
       borderColor: window.chartColors.pink,
       data: result['averagecpm_previous'],
       label: 'Cost per 1000 Impressions:Previous',
       fill: false,
       pointHoverRadius: 5,
       pointHoverBackgroundColor: 'white',
       borderWidth: 2
     };
     var dataset4 =  {
       backgroundColor: window.chartColors.darkBlue,
       borderColor: window.chartColors.darkBlue,
       data: result['revenue_per_click_previous'],
       label: 'Revenue Per Click:Previous',
       fill: false,
       pointHoverRadius: 5,
       pointHoverBackgroundColor: 'white',
       borderWidth: 2
     };
     var dataset5 = {
       backgroundColor: window.chartColors.pearGreen,
       borderColor: window.chartColors.pearGreen,
       data: result['total_value_previous'],
       label: 'Total Value:Previous',
       fill: false,
       pointHoverRadius: 5,
       pointHoverBackgroundColor: 'white',
       borderWidth: 2
     };

     configPerformanceDatas.data.datasets.push(dataset1);
     configPerformanceDatas.data.datasets.push(dataset2);
     configPerformanceDatas.data.datasets.push(dataset3);
     configPerformanceDatas.data.datasets.push(dataset4);
     configPerformanceDatas.data.datasets.push(dataset5);



  }else{
    configPerformanceDatas.data.datasets.splice(5,5);
    configPerformanceDatas.data.datasets.splice(6,6);
    configPerformanceDatas.data.datasets.splice(7,7);
    configPerformanceDatas.data.datasets.splice(8,8);
    configPerformanceDatas.data.datasets.splice(9,9);
  }

  window.myLinePerformanceDatas.update();
  $('.performance_chart').removeClass('ajax-loader');
}


$(document).on('click','.adwords_list',function(e){
	e.preventDefault();
	var compare = false;
	var value = $(this).attr('data-value');
	var campaign_id = $('.campaign_id').val();
	var account_id = $('.account_id').val();
  var key = $('#encriptkey').val();
 
  sectional_loaders();
  
	$('.adwords_list').removeClass('active');
	$(this).addClass('active');

	if ($('.adwords_compare').prop("checked") == true) {
		var compare = true;
	}
  page_data(value,campaign_id,compare,account_id,key);



});

function sectional_loaders(){

  $('.impressions-ads').addClass('ajax-loader');
  $('.cost-ads').addClass('ajax-loader');
  $('.click-ads').addClass('ajax-loader');
  $('.average-cpc-ads').addClass('ajax-loader');
  $('.ctr-ads').addClass('ajax-loader');
  $('.conversion-ads').addClass('ajax-loader');
  $('.conversion-rate-ads').addClass('ajax-loader');
  $('.cost-per-conversion-rate-ads').addClass('ajax-loader');

  $('.sv-overview-avg').addClass('ajax-loader');

  $('.summary_chart').addClass('ajax-loader');
  $('.performance_chart').addClass('ajax-loader');

  $('.costs_graph').addClass('ajax-loader');
  $('.costs_graph').show();
  $('#summary-cost-chart').hide();

  $('.impressions_graph').addClass('ajax-loader');
  $('.impressions_graph').show();
  $('#summary-impressions-chart').hide();

  $('.summary_clicks_graph').addClass('ajax-loader');
  $('.summary_clicks_graph').show();
  $('#summary-clicks-chart').hide(); 

  $('.average_cpc_graph').addClass('ajax-loader');
  $('.average_cpc_graph').show();
  $('#summary-averageCpc-chart').hide();

  $('.ctr_graph').addClass('ajax-loader');
  $('.ctr_graph').show();
  $('#summary-ctrAds-chart').hide();
    
  $('.conversion_rate_graph').addClass('ajax-loader');
  $('.conversion_rate_graph').show();
  $('#summary-conversionRate-chart').hide();

  $('.conversions_graph').addClass('ajax-loader');
  $('.conversions_graph').show();
  $('#summary-conversionAds-chart').hide();
      
  $('.cpc_rate_graph').addClass('ajax-loader');
  $('.cpc_rate_graph').show();
  $('#summary-costPerConversionRate-chart').hide();
  $('.project-table-foot .pagination').addClass('ajax-loader');
  $('.project-table-foot .project-entries').addClass('ajax-loader');
}


function sectional_loaders_compare(){
  $('.impressions-ads').addClass('ajax-loader');
  $('.cost-ads').addClass('ajax-loader');
  $('.click-ads').addClass('ajax-loader');
  $('.average-cpc-ads').addClass('ajax-loader');
  $('.ctr-ads').addClass('ajax-loader');
  $('.conversion-ads').addClass('ajax-loader');
  $('.conversion-rate-ads').addClass('ajax-loader');
  $('.cost-per-conversion-rate-ads').addClass('ajax-loader');

 
  $('.sv-overview-avg').addClass('ajax-loader');

  $('.summary_chart').addClass('ajax-loader');
  $('.performance_chart').addClass('ajax-loader');

  $('.project-table-foot .pagination').addClass('ajax-loader');
  $('.project-table-foot .project-entries').addClass('ajax-loader');

  /*$('.sv-overview').addClass('ajax-loader');

  $('.summary_chart').addClass('ajax-loader');
  $('.performance_chart').addClass('ajax-loader');

  $('.costs_graph').addClass('ajax-loader');
  $('.costs_graph').show();
  $('#summary-cost-chart').hide();

  $('.impressions_graph').addClass('ajax-loader');
  $('.impressions_graph').show();
  $('#summary-impressions-chart').hide();

  $('.summary_clicks_graph').addClass('ajax-loader');
  $('.summary_clicks_graph').show();
  $('#summary-clicks-chart').hide(); 

  $('.average_cpc_graph').addClass('ajax-loader');
  $('.average_cpc_graph').show();
  $('#summary-averageCpc-chart').hide();

  $('.ctr_graph').addClass('ajax-loader');
  $('.ctr_graph').show();
  $('#summary-ctrAds-chart').hide();
    
  $('.conversion_rate_graph').addClass('ajax-loader');
  $('.conversion_rate_graph').show();
  $('#summary-conversionRate-chart').hide();

  $('.conversions_graph').addClass('ajax-loader');
  $('.conversions_graph').show();
  $('#summary-conversionAds-chart').hide();
      
  $('.cpc_rate_graph').addClass('ajax-loader');
  $('.cpc_rate_graph').show();
  $('#summary-costPerConversionRate-chart').hide();*/
}


$(document).on('change','.adwords_compare',function(e){
  e.preventDefault();
  var campaign_id = $('.campaign_id').val();
  var compare = $(this).is(':checked');

  var account_id = $('.account_id').val();
  var value = $('.adwords_list.active').attr('data-value');
    var key = $('#encriptkey').val();

  sectional_loaders();
  page_data_compare(value,campaign_id,compare,account_id,key);
});

function page_data_compare(value,campaign_id,compare,account_id,key){

   
      $.ajax({
        type: "GET",
        url: BASE_URL + "/ajax_fetch_ppc_summary_statistics",
        data: {value,campaign_id,compare,account_id,key},
        dataType: 'json',
        success: function(response) {

            impressions_graph(account_id,campaign_id,response);
            $('.project-table-foot .pagination').removeClass('ajax-loader');
            $('.project-table-foot .project-entries').removeClass('ajax-loader');
            
        }
      });
      
}

function page_data(value,campaign_id,compare,account_id,key){

   
      $.ajax({
        type: "GET",
        url: BASE_URL + "/ajax_fetch_ppc_summary_statistics",
        data: {value,campaign_id,compare,account_id,key},
        dataType: 'json',
        success: function(response) {
            impressions_graph(account_id,campaign_id,response);
            ads_campaign_list(account_id,response);
            ads_keywords_list(account_id,response);
            ads_list(account_id,response);
            ads_groups_list(account_id,response);
            ads_performance_network_list(account_id,response);
            ads_performance_device_list(account_id,response);
            ads_performance_clickType_list(account_id,response);
            ads_performance_adSlot_list(account_id,response);

        }
      });
      
}