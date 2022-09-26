var BASE_URL = $('.base_url').val();
var color = Chart.helpers.color;
function gradientColor(chartId){
	var gradient = chartId.createLinearGradient(0, 0, 0,160);
	gradient.addColorStop(0, 'rgba(114,167, 253,0.8)');
	gradient.addColorStop(0.8, 'rgba(202, 222, 255,0.5)');
	gradient.addColorStop(1, 'rgba(218, 232, 255,0.2)');
	return gradient;
}

var configImpressions = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
		//	backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
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

var configCost = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
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

var configClicks = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
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

var configAverageCpc = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
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

var configCtr = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
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

var configConversions = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
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

var configConversionRate = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
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

var configCpcRate = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			backgroundColor: color(window.chartColors.lightGreyBlue).alpha(0.45).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: true
		}
		]
	},
	options: {
		maintainAspectRatio:false,
		scales: {
			xAxes: [{
				ticks: {
					display: false
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
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

function impressions_graph(account_id, campaign_id, response = null){

	
	if(response !== null){
		var compare = response['compare'];
		var endDate = response['endDate'];
		var preEndDate = response['preEndDate'];
		var preStartDate = response['preStartDate'];
		var startDate = response['startDate'];
		var duration = response['duration'];
	}else{
		var compare = null;
		var endDate = null;
		var preEndDate = null;
		var preStartDate = null;
		var startDate = null;
		var duration = null;
	}
	
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ppc/summaries",
		data: { account_id ,campaign_id,compare, endDate, preEndDate, preStartDate, startDate , duration },
		dataType: 'json',
		success: function(result) {

			// $('.adwords_time').html(result['firstDate']+' - '+result['lastDate']);
			/************* Summary ***********/
			
			$('.adwords_range').html(result['range']);
			$('.ads-currency').html('('+result['currencyCode']+')');

			if(result['compare'] == 1){

				// $('.adwords_compare').checked = true;
				$(".adwords_compare").attr("checked", true);

				$('.impressions-ads').html(result['summary']['impressionCount'] +' <span><cite>vs</cite><br> '+ result['summaryPrevious']['impressionCount']+'</span>');
				$('.cost-ads').html('<small>'+ result['currencyCode'] +'</small>'+result['summary']['costCount']+' <span><cite>vs</cite><br> '+result['summaryPrevious']['costCount']+'</span>');

				$('.click-ads').html(result['summary']['clickCount']+' <span><cite>vs</cite><br> '+result['summaryPrevious']['clickCount']+'</span>');
				$('.average-cpc-ads').html('<small>'+ result['currencyCode'] +'</small>'+result['summary']['average_cpc']+' <span><cite>vs</cite><br> '+result['summaryPrevious']['average_cpc']+'</span>');
				$('.ctr-ads').html(result['summary']['ctrCount']+'% <span><cite>vs</cite><br> '+result['summaryPrevious']['ctrCount']+'%</span>');
				$('.conversion-ads').html(result['summary']['conversionsCount']+' <span><cite>vs</cite><br> '+result['summaryPrevious']['conversionsCount']+'</span>');
				$('.conversion-rate-ads').html(result['summary']['conversion_rate']+'% <span><cite>vs</cite><br> '+result['summaryPrevious']['conversion_rate']+'%</span>');
				$('.cost-per-conversion-rate-ads').html('<small>'+ result['currencyCode'] +'</small>'+result['summary']['cpc_rate']+' <span><cite>vs</cite><br> '+result['summaryPrevious']['cpc_rate']+'</span>');	

				$('.impressions-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + result['impressions_percentage'] + '%');

			    if (result['impressions_percentage'] > '0') {
			      $('.impressions-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
			      $('.impressions-ads-percentage').removeClass("red");
			      $('.impressions-ads-percentage').addClass("green");
			    } else if (result['impressions_percentage'] < '0') {
			      $('.impressions-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
			      $('.impressions-ads-percentage').removeClass("green");
			      $('.impressions-ads-percentage').addClass("red");
			    } else {
			      	$('.impressions-ads-percentage').find('span').removeAttr("uk-icon");
			      	$('.impressions-ads-percentage').removeClass("red");
						$('.impressions-ads-percentage').removeClass("green");
			    }

			    $('.cost-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + result['costs_percentage'] + '%');

			    if (result['costs_percentage'] > '0') {
			      $('.cost-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
			      $('.cost-ads-percentage').removeClass("red");
			      $('.cost-ads-percentage').addClass("green");
			    } else if (result['costs_percentage'] < '0') {
			      $('.cost-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
			      $('.cost-ads-percentage').removeClass("green");
			      $('.cost-ads-percentage').addClass("red");
			    } else {
			    	$('.cost-ads-percentage').removeClass("red");
					$('.cost-ads-percentage').removeClass("green");
			      	$('.cost-ads-percentage').find('span').removeAttr("uk-icon");
			    }


			    $('.clicks-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + result['clicks_percentage'] + '%');

			    if (result['clicks_percentage'] > '0') {
			      $('.clicks-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
			      $('.clicks-ads-percentage').removeClass("red");
			      $('.clicks-ads-percentage').addClass("green");
			    } else if (result['clicks_percentage'] < '0') {
			      $('.clicks-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
			      $('.clicks-ads-percentage').removeClass("green");
			      $('.clicks-ads-percentage').addClass("red");
			    } else {
			    	$('.clicks-ads-percentage').removeClass("red");
					$('.clicks-ads-percentage').removeClass("green");
			      	$('.clicks-ads-percentage').find('span').removeAttr("uk-icon");
			    }


			    $('.average-cpc-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + result['average_cpc_percentage'] + '%');

			    if (result['average_cpc_percentage'] > '0') {
			      $('.average-cpc-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
			      $('.average-cpc-ads-percentage').removeClass("red");
			      $('.average-cpc-ads-percentage').addClass("green");
			    } else if (result['average_cpc_percentage'] < '0') {
			      $('.average-cpc-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
			      $('.average-cpc-ads-percentage').removeClass("green");
			      $('.average-cpc-ads-percentage').addClass("red");
			    } else {
			      	$('.average-cpc-ads-percentage').find('span').removeAttr("uk-icon");
			      	$('.average-cpc-ads-percentage').removeClass("red");
					$('.average-cpc-ads-percentage').removeClass("green");
			    }

			    $('.ctr-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + result['ctr_percentage'] + '%');

			    if (result['ctr_percentage'] > '0') {
			      $('.ctr-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
			      $('.ctr-ads-percentage').removeClass("red");
			      $('.ctr-ads-percentage').addClass("green");
			    } else if (result['ctr_percentage'] < '0') {
			      $('.ctr-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
			      $('.ctr-ads-percentage').removeClass("green");
			      $('.ctr-ads-percentage').addClass("red");
			    } else {
			      	$('.ctr-ads-percentage').find('span').removeAttr("uk-icon");
			      	$('.ctr-ads-percentage').removeClass("red");
					$('.ctr-ads-percentage').removeClass("green");
			    }

			  $('.conversion-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + result['conversions_percentage'] + '%');

			  if (result['conversions_percentage'] > '0') {
			    $('.conversion-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
			    $('.conversion-ads-percentage').removeClass("red");
			    $('.conversion-ads-percentage').addClass("green");
			  } else if (result['conversions_percentage'] < '0') {
			    $('.conversion-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
			    $('.conversion-ads-percentage').removeClass("green");
			    $('.conversion-ads-percentage').addClass("red");
			  } else {
			    $('.conversion-ads-percentage').find('span').removeAttr("uk-icon");
			    $('.conversion-ads-percentage').removeClass("red");
				$('.conversion-ads-percentage').removeClass("green");
			  }

			  $('.conversion-rate-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + result['conversion_rates_percentage'] + '%');

			  if (result['conversion_rates_percentage'] > '0') {
			    $('.conversion-rate-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
			    $('.conversion-rate-ads-percentage').removeClass("red");
			    $('.conversion-rate-ads-percentage').addClass("green");
			  } else if (result['conversion_rates_percentage'] < '0') {
			    $('.conversion-rate-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
			    $('.conversion-rate-ads-percentage').removeClass("green");
			    $('.conversion-rate-ads-percentage').addClass("red");
			  } else {
			    $('.conversion-rate-ads-percentage').find('span').removeAttr("uk-icon");
			    $('.conversion-rate-ads-percentage').removeClass("red");
				$('.conversion-rate-ads-percentage').removeClass("green");
			  }

			  $('.cost-per-conversion-rate-ads-percentage').html('<span uk-icon="icon: arrow-up"></span>' + result['cost_per_conversions_percentage'] + '%');

			  if (result['cost_per_conversions_percentage'] > '0') {
			    $('.cost-per-conversion-rate-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-up');
			    $('.cost-per-conversion-rate-ads-percentage').removeClass("red");
			    $('.cost-per-conversion-rate-ads-percentage').addClass("green");
			  } else if (result['cost_per_conversions_percentage'] < '0') {
			    $('.cost-per-conversion-rate-ads-percentage').find('span').attr("uk-icon", 'icon: arrow-down');
			    $('.cost-per-conversion-rate-ads-percentage').removeClass("green");
			    $('.cost-per-conversion-rate-ads-percentage').addClass("red");
			  } else {
			    $('.cost-per-conversion-rate-ads-percentage').find('span').removeAttr("uk-icon");
			    $('.cost-per-conversion-rate-ads-percentage').removeClass("red");
				$('.cost-per-conversion-rate-ads-percentage').removeClass("green");
			  }


			}else{
				$('.impressions-ads').html(result['summary']['impressionCount'] );
				$('.cost-ads').html('<small>'+ result['currencyCode'] +'</small>'+ result['summary']['costCount']);

				$('.click-ads').html(result['summary']['clickCount']);
				$('.average-cpc-ads').html('<small>'+ result['currencyCode'] +'</small>'+result['summary']['average_cpc']);
				$('.ctr-ads').html(result['summary']['ctrCount']+'%');
				$('.conversion-ads').html(result['summary']['conversionsCount']);
				$('.conversion-rate-ads').html(result['summary']['conversion_rate']+'%');
				$('.cost-per-conversion-rate-ads').html('<small>'+ result['currencyCode'] +'</small>'+result['summary']['cpc_rate']);

				$('.impressions-ads-percentage').html('');
				$('.cost-ads-percentage').html('');
				$('.clicks-ads-percentage').html('');
				$('.average-cpc-ads-percentage').html('');
				$('.ctr-ads-percentage').html('');
				$('.conversion-ads-percentage').html('');
				$('.conversion-rate-ads-percentage').html('');
				$('.cost-per-conversion-rate-ads-percentage').html('');
				
			}
		
			$('.sv-overview, .filter-list').removeClass('ajax-loader');

			$('.impressions-ads').removeClass('ajax-loader');
			$('.cost-ads').removeClass('ajax-loader');
			$('.click-ads').removeClass('ajax-loader');
			$('.average-cpc-ads').removeClass('ajax-loader');
			$('.ctr-ads').removeClass('ajax-loader');
			$('.conversion-ads').removeClass('ajax-loader');
			$('.conversion-rate-ads').removeClass('ajax-loader');
			$('.cost-per-conversion-rate-ads').removeClass('ajax-loader');
			

			configSummaryData(result['summaryGraph']);
			configPerformanceData(result['performanceGraph']);
			/************* Impressions ***********/
			if(window.myLineImpression){
				window.myLineImpression.destroy();
			}

			var ctx = document.getElementById('summary-impressions-chart').getContext('2d');
			window.myLineImpression = new Chart(ctx, configImpressions);
			var gradient = gradientColor(ctx);

			configImpressions.data.labels = result['from_datelabel'];
			configImpressions.data.datasets[0].data = result['impressions'];
			configImpressions.data.datasets[0].backgroundColor = gradient;

			window.myLineImpression.update();

			$('.impressions_graph').removeClass('ajax-loader');
			$('.impressions_graph').hide();

			/************* Cost ***********/

			if(window.myLineCost){
				window.myLineCost.destroy();
			}

			var ctx = document.getElementById('summary-cost-chart').getContext('2d');
			window.myLineCost = new Chart(ctx, configCost);
			var gradient = gradientColor(ctx);

			configCost.data.labels = result['from_datelabel'];
			configCost.data.datasets[0].data = result['cost'];
			configCost.data.datasets[0].backgroundColor = gradient;
			window.myLineCost.update();

			$('.costs_graph').removeClass('ajax-loader');
			$('.costs_graph').hide();

			/************* Clicks ***********/

			if(window.myLineClicks){
				window.myLineClicks.destroy();
			}

			var ctx = document.getElementById('summary-clicks-chart').getContext('2d');
			window.myLineClicks = new Chart(ctx, configClicks);
			var gradient = gradientColor(ctx);

			configClicks.data.labels = result['from_datelabel'];
			configClicks.data.datasets[0].data = result['clicks'];
			configClicks.data.datasets[0].backgroundColor = gradient;

			window.myLineClicks.update();

			$('.summary_clicks_graph').removeClass('ajax-loader');
			$('.summary_clicks_graph').hide();

			/************* Clicks ***********/

			if(window.myLineCtr){
				window.myLineCtr.destroy();
			}

			var ctx = document.getElementById('summary-ctrAds-chart').getContext('2d');
			window.myLineCtr = new Chart(ctx, configCtr);
			var gradient = gradientColor(ctx);
			configCtr.data.labels = result['from_datelabel'];
			configCtr.data.datasets[0].data = result['ctr'];
			configCtr.data.datasets[0].backgroundColor = gradient;

			window.myLineCtr.update();

			$('.ctr_graph').removeClass('ajax-loader');
			$('.ctr_graph').hide();

			/************* Clicks ***********/

			if(window.myLineConversion){
				window.myLineConversion.destroy();
			}

			var ctx = document.getElementById('summary-conversionAds-chart').getContext('2d');
			window.myLineConversion = new Chart(ctx, configConversions);
			var gradient = gradientColor(ctx);

			configConversions.data.labels = result['from_datelabel'];
			configConversions.data.datasets[0].data = result['conversions'];
			configConversions.data.datasets[0].backgroundColor = gradient;

			window.myLineConversion.update();

			$('.conversions_graph').removeClass('ajax-loader');
			$('.conversions_graph').hide();


			/************* Average CPC ***********/

			if(window.myLineaverageCpc){
				window.myLineaverageCpc.destroy();
			}

			var ctx = document.getElementById('summary-averageCpc-chart').getContext('2d');
			window.myLineaverageCpc = new Chart(ctx, configAverageCpc);
			var gradient = gradientColor(ctx);

			configAverageCpc.data.labels = result['from_datelabel'];
			configAverageCpc.data.datasets[0].data = result['average_cpc'];
			configAverageCpc.data.datasets[0].backgroundColor = gradient;
			window.myLineaverageCpc.update();

			$('.average_cpc_graph').removeClass('ajax-loader');
			$('.average_cpc_graph').hide();


			/************* Conversion Rate ***********/

			if(window.myLineConversionRate){
				window.myLineConversionRate.destroy();
			}

			var ctx = document.getElementById('summary-conversionRate-chart').getContext('2d');
			window.myLineConversionRate = new Chart(ctx, configConversionRate);
			var gradient = gradientColor(ctx);

			configConversionRate.data.labels = result['from_datelabel'];
			configConversionRate.data.datasets[0].data = result['conversion_rate'];
			configConversionRate.data.datasets[0].backgroundColor = gradient;
			window.myLineConversionRate.update();

			$('.conversion_rate_graph').removeClass('ajax-loader');
			$('.conversion_rate_graph').hide();

			/************* Cost Per Conversion Rate ***********/

			if(window.myLineCpcRate){
				window.myLineCpcRate.destroy();
			}

			var ctx = document.getElementById('summary-costPerConversionRate-chart').getContext('2d');
			window.myLineCpcRate = new Chart(ctx, configCpcRate);
			var gradient = gradientColor(ctx);

			configCpcRate.data.labels = result['from_datelabel'];
			configCpcRate.data.datasets[0].data = result['cpc_rate'];
			configCpcRate.data.datasets[0].backgroundColor = gradient;

			window.myLineCpcRate.update();

			$('.cpc_rate_graph').removeClass('ajax-loader');
			$('.cpc_rate_graph').hide();


		}
	});
}

/*function impressions_graph(account_id, campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ppc_summary_impressions_graph",
		data: { account_id ,campaign_id },
		dataType: 'json',
		success: function(result) {
			if(window.myLineImpression){
				window.myLineImpression.destroy();
			}

			var ctx = document.getElementById('summary-impressions-chart').getContext('2d');
			window.myLineImpression = new Chart(ctx, configImpressions);


			configImpressions.data.labels = result['from_datelabel'];
			configImpressions.data.datasets[0].data = result['impressions'];

			window.myLineImpression.update();

			$('.impressions_graph').removeClass('ajax-loader');
			$('.impressions_graph').hide();
		}
	});
}*/

function costs_graph(account_id, campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ppc_summary_cost_graph",
		data: { account_id ,campaign_id },
		dataType: 'json',
		success: function(result) {
			if(window.myLineCost){
				window.myLineCost.destroy();
			}

			var ctx = document.getElementById('summary-cost-chart').getContext('2d');
			window.myLineCost = new Chart(ctx, configCost);
			var gradient = gradientColor(ctx);
			configCost.data.labels = result['from_datelabel'];
			configCost.data.datasets[0].data = result['cost'];
			configCost.data.datasets[0].backgroundColor = gradient;

			window.myLineCost.update();

			$('.costs_graph').removeClass('ajax-loader');
			$('.costs_graph').hide();
		}
	});
}

function clicks_graph(account_id, campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ppc_summary_clicks_graph",
		data: { account_id ,campaign_id },
		dataType: 'json',
		success: function(result) {
			if(window.myLineClicks){
				window.myLineClicks.destroy();
			}

			var ctx = document.getElementById('summary-clicks-chart').getContext('2d');
			window.myLineClicks = new Chart(ctx, configClicks);
			var gradient = gradientColor(ctx);

			configClicks.data.labels = result['from_datelabel'];
			configClicks.data.datasets[0].data = result['clicks'];
			configClicks.data.datasets[0].backgroundColor = gradient;

			window.myLineClicks.update();

			$('.summary_clicks_graph').removeClass('ajax-loader');
			$('.summary_clicks_graph').hide();
			
		}
	});
}


function average_cpc_graph(account_id, campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ppc_summary_averageCpc_graph",
		data: { account_id ,campaign_id },
		dataType: 'json',
		success: function(result) {
			if(window.myLineaverageCpc){
				window.myLineaverageCpc.destroy();
			}

			var ctx = document.getElementById('summary-averageCpc-chart').getContext('2d');
			window.myLineaverageCpc = new Chart(ctx, configAverageCpc);
			var gradient = gradientColor(ctx);


			configAverageCpc.data.labels = result['from_datelabel'];
			configAverageCpc.data.datasets[0].data = result['average_cpc'];
			configAverageCpc.data.datasets[0].backgroundColor = gradient;
			window.myLineaverageCpc.update();

			$('.average_cpc_graph').removeClass('ajax-loader');
			$('.average_cpc_graph').hide();
		}
	});
}

function ctr_graph(account_id, campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ppc_summary_ctr_graph",
		data: { account_id ,campaign_id },
		dataType: 'json',
		success: function(result) {
			if(window.myLineCtr){
				window.myLineCtr.destroy();
			}

			var ctx = document.getElementById('summary-ctrAds-chart').getContext('2d');
			window.myLineCtr = new Chart(ctx, configCtr);
			var gradient = gradientColor(ctx);

			configCtr.data.labels = result['from_datelabel'];
			configCtr.data.datasets[0].data = result['ctr'];
			configCtr.data.datasets[0].backgroundColor = gradient;
			window.myLineCtr.update();

			$('.ctr_graph').removeClass('ajax-loader');
			$('.ctr_graph').hide();
		}
	});
}

function conversions_graph(account_id, campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ppc_summary_conversions_graph",
		data: { account_id ,campaign_id },
		dataType: 'json',
		success: function(result) {
			if(window.myLineConversion){
				window.myLineConversion.destroy();
			}

			var ctx = document.getElementById('summary-conversionAds-chart').getContext('2d');
			window.myLineConversion = new Chart(ctx, configConversions);
			var gradient = gradientColor(ctx);
			configConversions.data.labels = result['from_datelabel'];
			configConversions.data.datasets[0].data = result['conversions'];
			configConversions.data.datasets[0].backgroundColor = gradient;
			window.myLineConversion.update();

			$('.conversions_graph').removeClass('ajax-loader');
			$('.conversions_graph').hide();
		}
	});
}

function conversion_rate_graph(account_id, campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ppc_summary_conversion_rate_graph",
		data: { account_id ,campaign_id },
		dataType: 'json',
		success: function(result) {
			if(window.myLineConversionRate){
				window.myLineConversionRate.destroy();
			}

			var ctx = document.getElementById('summary-conversionRate-chart').getContext('2d');
			window.myLineConversionRate = new Chart(ctx, configConversionRate);
			var gradient = gradientColor(ctx);
		
			configConversionRate.data.labels = result['from_datelabel'];
			configConversionRate.data.datasets[0].data = result['conversion_rate'];
			configConversionRate.data.datasets[0].backgroundColor = gradient;
			window.myLineConversionRate.update();

			$('.conversion_rate_graph').removeClass('ajax-loader');
			$('.conversion_rate_graph').hide();
		}
	});
}

function cpc_rate_graph(account_id, campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ppc_summary_cpc_rate_graph",
		data: { account_id ,campaign_id },
		dataType: 'json',
		success: function(result) {
			if(window.myLineCpcRate){
				window.myLineCpcRate.destroy();
			}

			var ctx = document.getElementById('summary-costPerConversionRate-chart').getContext('2d');
			window.myLineCpcRate = new Chart(ctx, configCpcRate);
			var gradient = gradientColor(ctx);

			configCpcRate.data.labels = result['from_datelabel'];
			configCpcRate.data.datasets[0].data = result['cpc_rate'];
			configCpcRate.data.datasets[0].backgroundColor = gradient;

			window.myLineCpcRate.update();

			$('.cpc_rate_graph').removeClass('ajax-loader');
			$('.cpc_rate_graph').hide();
		}
	});
}