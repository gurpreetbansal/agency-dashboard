var ecomconfigGoalCompletion = {
	type: 'line',
	data: {
		labels: [],
		datasets: [
		{
			label: " Ecommerce Conversion Rate (All Users)",
			labels: [],
			fill: true,
			backgroundColor: color(window.chartColors.brightBLue).alpha(0.05).rgbString(),
			borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
			data: [],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		},
		{
			label: " Ecommerce Conversion Rate (Organic Traffic)",
			labels: [],
			fill: false,
			backgroundColor: color(window.chartColors.lightGreen).alpha(0.15).rgbString(),
			borderColor: color(window.chartColors.lightGreen).alpha(1.0).rgbString(),
			data: [],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		}
		]
	},
	options: {
		maintainAspectRatio: false,
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
		tooltips: {
			intersect: false,
			mode: 'index',
			backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000',
			bodyFontStyle: 'bold',	
			callbacks: {
				labelTextColor: function(context) {
					return '#000';
				}
				,
				title: function() {}
				,
				beforeLabel: function(tooltipItem, data) {
					if(tooltipItem.datasetIndex === 0){
						return data.datasets[0].labels[tooltipItem.index];
					}
					else if(tooltipItem.datasetIndex === 2){
						return data.datasets[2].labels[tooltipItem.index];	
					}
				}
			}
		},
		legend: {
			labels: {
				boxWidth: 10
			}
		},
		hover: {
			mode: 'nearest',
			intersect: true
		},
		scales: {
			xAxes: [{
				display: true,
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				},
				ticks: {
					maxRotation: 0,
					minRotation: 0,
					autoSkip: true,
					maxTicksLimit: 10
				}
			}],
			yAxes: [{
				display: true,
				ticks: {
					min: 0,
				}
			}]
		}
	}
};

var configEcom_conversionRate_users = {
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
				}
			}]
		},
		tooltip: {
			mode: 'index',
			intersect: false,
		},
		legend: {
			display:false
		}
	}
};

var configEcom_conversionRate_organic = {
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
				}
			}]
		},
		tooltip: {
			mode: 'index',
			intersect: false,
		},
		legend: {
			display:false
		}
	}
};

var configEcom_transaction_users = {
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
				}
			}]
		},
		tooltip: {
			mode: 'index',
			intersect: false,
		},
		legend: {
			display:false
		}
	}
};

var configEcom_transaction_organic = {
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
				}
			}]
		},
		tooltip: {
			mode: 'index',
			intersect: false,
		},
		legend: {
			display:false
		}
	}
};

var configEcom_revenue_users = {
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
				}
			}]
		},
		tooltip: {
			mode: 'index',
			intersect: false,
		},
		legend: {
			display:false
		}
	}
};

var configEcom_revenue_organic = {
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
				}
			}]
		},
		tooltip: {
			mode: 'index',
			intersect: false,
		},
		legend: {
			display:false
		}
	}
};

var configEcom_avg_orderValue_users = {
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
				}
			}]
		},
		tooltip: {
			mode: 'index',
			intersect: false,
		},
		legend: {
			display:false
		}
	}
};

var configEcom_avg_orderValue_organic = {
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
				}
			}]
		},
		tooltip: {
			mode: 'index',
			intersect: false,
		},
		legend: {
			display:false
		}
	}
};

function ecom_goalCompletionChart(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_ecom_goal_completion_chart",
		data:{campaign_id},
		dataType:'json',
		success:function(result){
			if(result['status'] == 0){	
				$('#ecom_analytics_data_goal').css('display','none');	
			}
			if(result['status'] == 1){
				ecom_goal_graph(result);
				$('#ecom_analytics_data_goal').css('display','block');
			}

			$('.ecom-goal-completion-graph').removeClass('ajax-loader');
			
		}
	});
}

function ecom_goal_graph(result){
	if (window.myLineEcomGoalCompletion) {
		window.myLineEcomGoalCompletion.destroy();
	}
	var EcomGoalCompletion = document.getElementById('ecom-canvas-goal-completion').getContext('2d');
	window.myLineEcomGoalCompletion = new Chart(EcomGoalCompletion, ecomconfigGoalCompletion);

	ecomconfigGoalCompletion.data.labels =  result['from_datelabel'];
	ecomconfigGoalCompletion.data.datasets[0].data = result['users'];
	ecomconfigGoalCompletion.data.datasets[0].labels = result['from_datelabels'];
	ecomconfigGoalCompletion.data.datasets[1].data = result['organic'];
	ecomconfigGoalCompletion.data.datasets[1].labelString = result['from_datelabels'];

	if(result['compare_status'] == 1){
		ecomconfigGoalCompletion.data.datasets.splice(2,2);
		ecomconfigGoalCompletion.data.datasets.splice(3,3);

		var dataset_1 = {
			label: " Ecommerce Conversion Rate (All Users)",
			fill: false,
			backgroundColor: window.chartColors.orange,
			borderColor: window.chartColors.orange,
			data: result['previous_users'],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2,
			labels: result['prev_from_datelabels']
		};
		var dataset_2 = {
			label: " Ecommerce Conversion Rate (Organic Traffic)",
			fill: false,
			backgroundColor: window.chartColors.pink,
			borderColor: window.chartColors.pink,
			data: result['previous_organic'],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2,
			labels: result['prev_from_datelabels']
		}

		ecomconfigGoalCompletion.data.datasets.push(dataset_1);
		ecomconfigGoalCompletion.data.datasets.push(dataset_2);

	} else{	
		ecomconfigGoalCompletion.data.datasets.splice(2,2);
		ecomconfigGoalCompletion.data.datasets.splice(3,3);

	}

	window.myLineEcomGoalCompletion.update();
}


function ecom_goalCompletionStats(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_ecom_goal_completion_overview",
		data:{campaign_id},
		dataType:'json',
		success:function(result){
			if(result['status'] !== 0){
			//overview field
			$('#ecom-conversionRate-users').html(result['current_conversionRate']+'%');
			$('#ecom-conversionRate-organic').html(result['current_conversionRate_organic']+'%');
			$('#ecom_transction_users').html(result['current_transactions']);
			$('#ecom_transction_organic').html(result['current_transactions_organic']);
			$('#ecom_revenue_users').html(result['current_revenue']);
			$('#ecom_revenue_organic').html(result['current_revenue_organic']);
			$('#ecom_avg_orderValue_users').html(result['current_avg_orderVal']);
			$('#ecom_avg_orderValue_organic').html(result['current_avg_orderVal_organic']);
			
			if(result['compare_status'] == 1){
				$('.ecom-chart-box').addClass('ecom-compare-section');	
				//conversion rate  users
				$('#ecom-conversionRate-users').append(' <span><cite>vs</cite> '+result['previous_conversionRate']+ '% </span>');
				if(result['conversionRate_percentage'] < 0){
					var string_ecom_conversionRate = result['conversionRate_percentage'].toString();
					var replace_ecom_conversionRate = string_ecom_conversionRate.replace('-', '');
					var arrow = 'down'; var color = 'red';
				}else if(result['conversionRate_percentage'] > 0){
					var replace_ecom_conversionRate = result['conversionRate_percentage'];
					var arrow = 'up'; var color = 'green';
				}else {
					var replace_ecom_conversionRate = result['conversionRate_percentage'];
					var arrow = ''; var color = '';
				}
				$('.ecom-conversion-rate-users-percentage').html('<cite class='+color+'><span uk-icon="icon: arrow-'+arrow+'"></span>'+ replace_ecom_conversionRate +'%</cite>');

				//conversion rate  organic
				$('#ecom-conversionRate-organic').append(' <span><cite>vs</cite> '+result['previous_conversionRate_organic']+ '% </span>');
				if(result['conversionRate_percentage_organic'] < 0){
					var string_ecom_conversionRate_organic = result['conversionRate_percentage_organic'].toString();
					var replace_ecom_conversionRate_organic = string_ecom_conversionRate_organic.replace('-', '');
					var arrow1 = 'down'; var color1 = 'red';
				}else if(result['conversionRate_percentage_organic'] > 0){
					var replace_ecom_conversionRate_organic = result['conversionRate_percentage_organic'];
					var arrow1 = 'up'; var color1 = 'green';
				}else{
					var replace_ecom_conversionRate_organic = result['conversionRate_percentage_organic'];
					var arrow1 = ''; var color1 = '';
				}
				$('.ecom-conversion-rate-organic-percentage').html('<cite class='+color1+'><span uk-icon="icon: arrow-'+arrow1+'"></span>'+ replace_ecom_conversionRate_organic +'%</cite>');

				//transactions  users
				$('#ecom_transction_users').append(' <span><cite>vs</cite> '+result['previous_transactions']+ '</span>');
				if(result['transactions_percentage'] < 0){
					var string_ecom_transaction = result['transactions_percentage'].toString();
					var replace_ecom_transactions = string_ecom_transaction.replace('-', '');
					var arrow2 = 'down'; var color2 = 'red';
				}else if(result['transactions_percentage'] > 0){
					var replace_ecom_transactions = result['transactions_percentage'];
					var arrow2 = 'up'; var color2 = 'green';
				}else{
					var replace_ecom_transactions = result['transactions_percentage'];
					var arrow2 = ''; var color2 = '';
				}
				$('.ecom-transaction-users-percentage').html('<cite class='+color2+'><span uk-icon="icon: arrow-'+arrow2+'"></span>'+ replace_ecom_transactions +'%</cite>');

				//transactions  organic
				$('#ecom_transction_organic').append(' <span><cite>vs</cite> '+result['previous_transactions_organic']+ '</span>');
				if(result['transactions_percentage_organic'] < 0){
					var string_ecom_transaction_organic = result['transactions_percentage_organic'].toString();
					var replace_ecom_transactions_organic = string_ecom_transaction_organic.replace('-', '');
					var arrow3 = 'down'; var color3 = 'red';
				}else if(result['transactions_percentage_organic'] > 0){
					var replace_ecom_transactions_organic = result['transactions_percentage_organic'];
					var arrow3 = 'up'; var color3 = 'green';
				}else{
					var replace_ecom_transactions_organic = result['transactions_percentage_organic'];
					var arrow3 = ''; var color3 = '';
				}
				$('.ecom-transaction-organic-percentage').html('<cite class='+color3+'><span uk-icon="icon: arrow-'+arrow3+'"></span>'+replace_ecom_transactions_organic+'%</cite>');
				
				//revenue  users
				$('#ecom_revenue_users').append(' <span><cite>vs</cite> '+result['previous_revenue']+ '</span>');
				if(result['revenue_percentage'] < 0){
					var string_ecom_revnue = result['revenue_percentage'].toString();
					var replace_ecom_revenue = string_ecom_revnue.replace('-', '');
					var arrow4 = 'down'; var color4 = 'red';
				}else if(result['revenue_percentage'] > 0){
					var replace_ecom_revenue = result['revenue_percentage'];
					var arrow4 = 'up'; var color4 = 'green';
				}else{
					var replace_ecom_revenue = result['revenue_percentage'];
					var arrow4 = ''; var color4 = '';
				}
				$('.ecom-revenue-users-percentage').html('<cite class='+color4+'><span uk-icon="icon: arrow-'+arrow4+'"></span>'+replace_ecom_revenue+'%</cite>');

				//revenue  organic
				$('#ecom_revenue_organic').append(' <span><cite>vs</cite> '+result['previous_revenue_organic']+ '</span>');
				if(result['revenue_percentage_organic'] < 0){
					var string_ecom_revnue_organic = result['revenue_percentage_organic'].toString();
					var replace_ecom_revenue_organic = string_ecom_revnue_organic.replace('-', '');
					var arrow5 = 'down'; var color5 = 'red';
				}else if(result['revenue_percentage_organic'] > 0){
					var replace_ecom_revenue_organic = result['revenue_percentage_organic'];
					var arrow5 = 'up'; var color5 = 'green';
				}else{
					var replace_ecom_revenue_organic = result['revenue_percentage_organic'];
					var arrow5 = ''; var color5 = '';
				}
				$('.ecom-revenue-organic-percentage').html('<cite class='+color5+'><span uk-icon="icon: arrow-'+arrow5+'"></span>'+replace_ecom_revenue_organic+'%</cite>');

				//order value  users
				$('#ecom_avg_orderValue_users').append(' <span><cite>vs</cite> '+result['previous_avg_orderVal']+ '</span>');
				if(result['avg_orderVal_percentage'] < 0){
					var string_ecom_orderVal = result['avg_orderVal_percentage'].toString();
					var replace_ecom_orderVal = string_ecom_orderVal.replace('-', '');
					var arrow6 = 'down'; var color6 = 'red';
				}else if(result['avg_orderVal_percentage'] > 0){
					var replace_ecom_orderVal = result['avg_orderVal_percentage'];
					var arrow6 = 'up'; var color6 = 'green';
				}else{
					var replace_ecom_orderVal = result['avg_orderVal_percentage'];
					var arrow6 = ''; var color6 = '';
				}
				$('.ecom-orderValue-users-percentage').html('<cite class='+color6+'><span uk-icon="icon: arrow-'+arrow6+'"></span>'+replace_ecom_orderVal+'%</cite>');

				//order value  organic
				$('#ecom_avg_orderValue_organic').append(' <span><cite>vs</cite> '+result['previous_avg_orderVal_organic']+ '</span>');
				if(result['avg_orderVal_percentage_organic'] < 0){
					var string_ecom_orderVal_organic = result['avg_orderVal_percentage_organic'].toString();
					var replace_ecom_orderVal_organic = string_ecom_orderVal_organic.replace('-', '');
					var arrow7 = 'down'; var color7 = 'red';
				}else if(result['avg_orderVal_percentage_organic'] > 0){
					var replace_ecom_orderVal_organic = result['avg_orderVal_percentage_organic'];
					var arrow7 = 'up'; var color7 = 'green';
				}else{
					var replace_ecom_orderVal_organic = result['avg_orderVal_percentage_organic'];
					var arrow7 = 'up'; var color7 = 'green';
				}
				$('.ecom-orderValue-organic-percentage').html('<cite class='+color7+'><span uk-icon="icon: arrow-'+arrow7+'"></span>'+replace_ecom_orderVal_organic+'%</cite>');

			}else{
				$('.ecom-chart-box').removeClass('ecom-compare-section');	
				$('.ecom-conversion-rate-users-percentage').html('');
				$('.ecom-conversion-rate-organic-percentage').html('');
				$('.ecom-transaction-users-percentage').html('');
				$('.ecom-transaction-organic-percentage').html('');
				$('.ecom-revenue-users-percentage').html('');
				$('.ecom-revenue-organic-percentage').html('');
				$('.ecom-orderValue-users-percentage').html('');
				$('.ecom-orderValue-organic-percentage').html('');
			}
			$('.compare').removeClass('ajax-loader');
			$('.ecom_conversion_percentage').removeClass('ajax-loader');
			$('.ecom_conversion_percentage_organic').removeClass('ajax-loader');
			$('.ecom_transaction_percentage_users').removeClass('ajax-loader');
			$('.ecom_transaction_percentage_organic').removeClass('ajax-loader');
			$('.ecom_revenue_users_percentage').removeClass('ajax-loader');
			$('.ecom_revenue_organic_percentage').removeClass('ajax-loader');
			$('.ecom_orderValue_users_percentage').removeClass('ajax-loader');
			$('.ecom_orderValue_organic_percentage').removeClass('ajax-loader');


		}
	}
});
}

function ecom_conversion_rate_users(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_conversion_rate_users",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-conversion-rate-graph-users').getContext('2d');
				window.ecom_conversionRate_users = new Chart(ctx, configEcom_conversionRate_users);


				configEcom_conversionRate_users.data.labels = result['from_datelabel'];
				configEcom_conversionRate_users.data.datasets[0].data = result['data'];

				window.ecom_conversionRate_users.update();

				$('.ecom_conversion').removeClass('ajax-loader');
				$('.ecom_conversion').hide();
			}
		}
	});
}

function ecom_conversionRate_organic(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_conversion_rate_organic",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-conversion-rate-graph-organic').getContext('2d');
				window.ecom_conversion_rate_organic = new Chart(ctx, configEcom_conversionRate_organic);


				configEcom_conversionRate_organic.data.labels = result['from_datelabel'];
				configEcom_conversionRate_organic.data.datasets[0].data = result['data'];

				window.ecom_conversion_rate_organic.update();

				$('.ecom_conversionOrganic').removeClass('ajax-loader');
				$('.ecom_conversionOrganic').hide();
			}
		}
	});
}

function ecom_transactionUsers(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_transaction_users",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-transaction-users').getContext('2d');
				window.ecom_transaction_users = new Chart(ctx, configEcom_transaction_users);


				configEcom_transaction_users.data.labels = result['from_datelabel'];
				configEcom_transaction_users.data.datasets[0].data = result['data'];

				window.ecom_transaction_users.update();

				$('.ecom_transactionUsers').removeClass('ajax-loader');
				$('.ecom_transactionUsers').hide();
			}
		}
	});
}

function ecom_transactionOrganic(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_transaction_organic",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-transaction-organic').getContext('2d');
				window.ecom_transaction_organic = new Chart(ctx, configEcom_transaction_organic);


				configEcom_transaction_organic.data.labels = result['from_datelabel'];
				configEcom_transaction_organic.data.datasets[0].data = result['data'];

				window.ecom_transaction_organic.update();

				$('.ecom_transactionOrganic').removeClass('ajax-loader');
				$('.ecom_transactionOrganic').hide();
			}
		}
	});
}

function ecom_revenueUsers(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_revenue_users",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-revenue-users').getContext('2d');
				window.ecom_revenue_users = new Chart(ctx, configEcom_revenue_users);


				configEcom_revenue_users.data.labels = result['from_datelabel'];
				configEcom_revenue_users.data.datasets[0].data = result['data'];

				window.ecom_revenue_users.update();

				$('.ecom_RevenueUsers').removeClass('ajax-loader');
				$('.ecom_RevenueUsers').hide();
			}
		}
	});
}

function ecom_revenueOrganic(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_revenue_organic",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-revenue-organic').getContext('2d');
				window.ecom_revenue_organic = new Chart(ctx, configEcom_revenue_organic);


				configEcom_revenue_organic.data.labels = result['from_datelabel'];
				configEcom_revenue_organic.data.datasets[0].data = result['data'];

				window.ecom_revenue_organic.update();

				$('.ecom_RevenueOrganic').removeClass('ajax-loader');
				$('.ecom_RevenueOrganic').hide();
			}
		}
	});
}

function ecom_avg_orderValue_users(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_avg_orderValue_users",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-orderValue-users').getContext('2d');
				window.ecom_orderValue_users = new Chart(ctx, configEcom_avg_orderValue_users);


				configEcom_avg_orderValue_users.data.labels = result['from_datelabel'];
				configEcom_avg_orderValue_users.data.datasets[0].data = result['data'];

				window.ecom_orderValue_users.update();

				$('.ecom_avgorderValue_users').removeClass('ajax-loader');
				$('.ecom_avgorderValue_users').hide();
			}
		}
	});
}

function ecom_avg_orderValue_organic(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_avg_orderValue_organic",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-orderValue-organic').getContext('2d');
				window.ecom_orderValue_organic = new Chart(ctx, configEcom_avg_orderValue_organic);


				configEcom_avg_orderValue_organic.data.labels = result['from_datelabel'];
				configEcom_avg_orderValue_organic.data.datasets[0].data = result['data'];

				window.ecom_orderValue_organic.update();

				$('.ecom_avgorderValue_organic').removeClass('ajax-loader');
				$('.ecom_avgorderValue_organic').hide();
			}
		}
	});
}



function ecom_product_list(campaign_id,page){
	$('#ecom_product tr td').addClass('ajax-loader');
	$('.ecom-product').addClass('ajax-loader');
	$('#ecom_showing_pagination').addClass('ajax-loader');
	$.ajax({
		type:'GET',
		data:{campaign_id,page},
		url:BASE_URL +'/ajax_ecom_product',
		success:function(response){
			if(response['status'] == 0){
				$('#ecom_product tbody').html('<tr><td colspan="3"><center><h6>There is no data for this view</h6></center></td></tr>');
			}else{
				$('#ecom_product tbody').html(response);
			}
			$('#ecom_product tr').removeClass('ajax-loader');
			$('#ecom_product tr td').removeClass('ajax-loader');
		}
	});

	$.ajax({
		type:'GET',
		data:{campaign_id,page},
		url:BASE_URL +'/ajax_ecom_product_pagination',
		success:function(response){
			$('.ecom_product-foot').html('');
			$('.ecom_product-foot').html(response);
			$('#ecom_showing_pagination').removeClass('ajax-loader');
			$('.ecom-product').removeClass('ajax-loader');
			
		}
	});
}

$(document).on('click','.ecom-product a',function(e){
	e.preventDefault();
	$('ecom-product ul li').removeClass('active');
	$(this).parent().addClass('active');
	var page = $(this).attr('href').split('page=')[1];
	var selected = $("a",'.view-sidebar .active').attr('href');
	
	
	if(selected == '#goals'){
		$('ecom-product-viewkey ul li').removeClass('active');
		$(this).parent().addClass('active');

		var compare_status = $('.analyticsGraphCompare_view').prop('checked');
		if(compare_status == true){
			var compare_value = 1;
		}else{
			var compare_value = 0;
		}
		ecom_product_list_vk($('.graph_range_view_goal.active').attr('data-value'),compare_value,$('.campaign_id').val(),$('#encriptkey').val(),$('.traffic_display_type_goal.blue-btn').attr('data-type'),page);
	}else{
		ecom_product_list($('.campaign_id').val(),page);
	}
});


/*August 30*/

function ecom_goalCompletionChart_vk(value,compare_value,campaignId,key,type){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_ecom_goal_completion_chart_viewkey",
		data:{value,compare_value,campaign_id:campaignId,key,type},
		dataType:'json',
		success:function(result){
			if(result['status'] == 0){	
				$('#ecom_analytics_data_goal').css('display','none');	
			}	
			if(result['status'] == 1){	
				ecom_goal_graph(result);	
				$('#ecom_analytics_data_goal').css('display','block');	
			}
			
			$('.ecom-goal-completion-graph').removeClass('ajax-loader');
			
		}
	});
}


function ecom_goalCompletionStats_vk(value,compare_value,campaignId,key,type){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_ecom_goal_completion_overview_viewkey",
		data:{value,compare_value,campaign_id:campaignId,key,type},
		dataType:'json',
		success:function(result){
			//overview field
			$('#ecom-conversionRate-users').html(result['current_conversionRate']+'%');
			$('#ecom-conversionRate-organic').html(result['current_conversionRate_organic']+'%');
			$('#ecom_transction_users').html(result['current_transactions']);
			$('#ecom_transction_organic').html(result['current_transactions_organic']);
			$('#ecom_revenue_users').html(result['current_revenue']);
			$('#ecom_revenue_organic').html(result['current_revenue_organic']);
			$('#ecom_avg_orderValue_users').html(result['current_avg_orderVal']);
			$('#ecom_avg_orderValue_organic').html(result['current_avg_orderVal_organic']);
			
			if(result['compare_status'] == 1){
				$('.ecom-chart-box').addClass('ecom-compare-section');
				//conversion rate  users
				$('#ecom-conversionRate-users').append(' <span><cite>vs</cite> '+result['previous_conversionRate']+ '% </span>');
				if(result['conversionRate_percentage'] < 0){
					var string_ecom_conversionRate = result['conversionRate_percentage'].toString();
					var replace_ecom_conversionRate = string_ecom_conversionRate.replace('-', '');
					var arrow = 'down'; var color = 'red';
				}else if(result['conversionRate_percentage'] > 0){
					var replace_ecom_conversionRate = result['conversionRate_percentage'];
					var arrow = 'up'; var color = 'green';
				}else {
					var replace_ecom_conversionRate = result['conversionRate_percentage'];
					var arrow = ''; var color = '';
				}
				$('.ecom-conversion-rate-users-percentage').html('<cite class='+color+'><span uk-icon="icon: arrow-'+arrow+'"></span>'+ replace_ecom_conversionRate +'%</cite>');

				//conversion rate  organic
				$('#ecom-conversionRate-organic').append(' <span><cite>vs</cite> '+result['previous_conversionRate_organic']+ '% </span>');
				if(result['conversionRate_percentage_organic'] < 0){
					var string_ecom_conversionRate_organic = result['conversionRate_percentage_organic'].toString();
					var replace_ecom_conversionRate_organic = string_ecom_conversionRate_organic.replace('-', '');
					var arrow1 = 'down'; var color1 = 'red';
				}else if(result['conversionRate_percentage_organic'] > 0){
					var replace_ecom_conversionRate_organic = result['conversionRate_percentage_organic'];
					var arrow1 = 'up'; var color1 = 'green';
				}else{
					var replace_ecom_conversionRate_organic = result['conversionRate_percentage_organic'];
					var arrow1 = ''; var color1 = '';
				}
				$('.ecom-conversion-rate-organic-percentage').html('<cite class='+color1+'><span uk-icon="icon: arrow-'+arrow1+'"></span>'+ replace_ecom_conversionRate_organic +'%</cite>');

				//transactions  users
				$('#ecom_transction_users').append(' <span><cite>vs</cite> '+result['previous_transactions']+ '</span>');
				if(result['transactions_percentage'] < 0){
					var string_ecom_transaction = result['transactions_percentage'].toString();
					var replace_ecom_transactions = string_ecom_transaction.replace('-', '');
					var arrow2 = 'down'; var color2 = 'red';
				}else if(result['transactions_percentage'] > 0){
					var replace_ecom_transactions = result['transactions_percentage'];
					var arrow2 = 'up'; var color2 = 'green';
				}else{
					var replace_ecom_transactions = result['transactions_percentage'];
					var arrow2 = ''; var color2 = '';
				}
				$('.ecom-transaction-users-percentage').html('<cite class='+color2+'><span uk-icon="icon: arrow-'+arrow2+'"></span>'+ replace_ecom_transactions +'%</cite>');

				//transactions  organic
				$('#ecom_transction_organic').append(' <span><cite>vs</cite> '+result['previous_transactions_organic']+ '</span>');
				if(result['transactions_percentage_organic'] < 0){
					var string_ecom_transaction_organic = result['transactions_percentage_organic'].toString();
					var replace_ecom_transactions_organic = string_ecom_transaction_organic.replace('-', '');
					var arrow3 = 'down'; var color3 = 'red';
				}else if(result['transactions_percentage_organic'] > 0){
					var replace_ecom_transactions_organic = result['transactions_percentage_organic'];
					var arrow3 = 'up'; var color3 = 'green';
				}else{
					var replace_ecom_transactions_organic = result['transactions_percentage_organic'];
					var arrow3 = ''; var color3 = '';
				}
				$('.ecom-transaction-organic-percentage').html('<cite class='+color3+'><span uk-icon="icon: arrow-'+arrow3+'"></span>'+replace_ecom_transactions_organic+'%</cite>');
				
				//revenue  users
				$('#ecom_revenue_users').append(' <span><cite>vs</cite> '+result['previous_revenue']+ '</span>');
				if(result['revenue_percentage'] < 0){
					var string_ecom_revnue = result['revenue_percentage'].toString();
					var replace_ecom_revenue = string_ecom_revnue.replace('-', '');
					var arrow4 = 'down'; var color4 = 'red';
				}else if(result['revenue_percentage'] > 0){
					var replace_ecom_revenue = result['revenue_percentage'];
					var arrow4 = 'up'; var color4 = 'green';
				}else{
					var replace_ecom_revenue = result['revenue_percentage'];
					var arrow4 = ''; var color4 = '';
				}
				$('.ecom-revenue-users-percentage').html('<cite class='+color4+'><span uk-icon="icon: arrow-'+arrow4+'"></span>'+replace_ecom_revenue+'%</cite>');

				//revenue  organic
				$('#ecom_revenue_organic').append(' <span><cite>vs</cite> '+result['previous_revenue_organic']+ '</span>');
				if(result['revenue_percentage_organic'] < 0){
					var string_ecom_revnue_organic = result['revenue_percentage_organic'].toString();
					var replace_ecom_revenue_organic = string_ecom_revnue_organic.replace('-', '');
					var arrow5 = 'down'; var color5 = 'red';
				}else if(result['revenue_percentage_organic'] > 0){
					var replace_ecom_revenue_organic = result['revenue_percentage_organic'];
					var arrow5 = 'up'; var color5 = 'green';
				}else{
					var replace_ecom_revenue_organic = result['revenue_percentage_organic'];
					var arrow5 = ''; var color5 = '';
				}
				$('.ecom-revenue-organic-percentage').html('<cite class='+color5+'><span uk-icon="icon: arrow-'+arrow5+'"></span>'+replace_ecom_revenue_organic+'%</cite>');

				//order value  users
				$('#ecom_avg_orderValue_users').append(' <span><cite>vs</cite> '+result['previous_avg_orderVal']+ '</span>');
				if(result['avg_orderVal_percentage'] < 0){
					var string_ecom_orderVal = result['avg_orderVal_percentage'].toString();
					var replace_ecom_orderVal = string_ecom_orderVal.replace('-', '');
					var arrow6 = 'down'; var color6 = 'red';
				}else if(result['avg_orderVal_percentage'] > 0){
					var replace_ecom_orderVal = result['avg_orderVal_percentage'];
					var arrow6 = 'up'; var color6 = 'green';
				}else{
					var replace_ecom_orderVal = result['avg_orderVal_percentage'];
					var arrow6 = ''; var color6 = '';
				}
				$('.ecom-orderValue-users-percentage').html('<cite class='+color6+'><span uk-icon="icon: arrow-'+arrow6+'"></span>'+replace_ecom_orderVal+'%</cite>');

				//order value  organic
				$('#ecom_avg_orderValue_organic').append(' <span><cite>vs</cite> '+result['previous_avg_orderVal_organic']+ '</span>');
				if(result['avg_orderVal_percentage_organic'] < 0){
					var string_ecom_orderVal_organic = result['avg_orderVal_percentage_organic'].toString();
					var replace_ecom_orderVal_organic = string_ecom_orderVal_organic.replace('-', '');
					var arrow7 = 'down'; var color7 = 'red';
				}else if(result['avg_orderVal_percentage_organic'] > 0){
					var replace_ecom_orderVal_organic = result['avg_orderVal_percentage_organic'];
					var arrow7 = 'up'; var color7 = 'green';
				}else{
					var replace_ecom_orderVal_organic = result['avg_orderVal_percentage_organic'];
					var arrow7 = 'up'; var color7 = 'green';
				}
				$('.ecom-orderValue-organic-percentage').html('<cite class='+color7+'><span uk-icon="icon: arrow-'+arrow7+'"></span>'+replace_ecom_orderVal_organic+'%</cite>');

			}else{
				$('.ecom-chart-box').removeClass('ecom-compare-section');
				$('.ecom-conversion-rate-users-percentage').html('');
				$('.ecom-conversion-rate-organic-percentage').html('');
				$('.ecom-transaction-users-percentage').html('');
				$('.ecom-transaction-organic-percentage').html('');
				$('.ecom-revenue-users-percentage').html('');
				$('.ecom-revenue-organic-percentage').html('');
				$('.ecom-orderValue-users-percentage').html('');
				$('.ecom-orderValue-organic-percentage').html('');
			}
			$('.compare').removeClass('ajax-loader');
			$('.ecom_conversion_percentage').removeClass('ajax-loader');
			$('.ecom_conversion_percentage_organic').removeClass('ajax-loader');
			$('.ecom_transaction_percentage_users').removeClass('ajax-loader');
			$('.ecom_transaction_percentage_organic').removeClass('ajax-loader');
			$('.ecom_revenue_users_percentage').removeClass('ajax-loader');
			$('.ecom_revenue_organic_percentage').removeClass('ajax-loader');
			$('.ecom_orderValue_users_percentage').removeClass('ajax-loader');
			$('.ecom_orderValue_organic_percentage').removeClass('ajax-loader');


		}
	});
}


function ecom_product_list_vk(value,compare_value,campaignId,key,type,page){
	$('#ecom_product tr td').addClass('ajax-loader');
	$('.ecom-product-viewkey').addClass('ajax-loader');
	$('#ecom_showing_pagination').addClass('ajax-loader');
	$.ajax({
		type:'GET',
		data:{value,compare_value,campaign_id:campaignId,key,type,page},
		url:BASE_URL +'/ajax_ecom_product_viewkey',
		success:function(response){
			$('#ecom_product tbody').html(response);
			$('#ecom_product tr').removeClass('ajax-loader');
			$('#ecom_product tr td').removeClass('ajax-loader');
		}
	});

	$.ajax({
		type:'GET',
		data:{value,compare_value,campaign_id:campaignId,key,type,page},
		url:BASE_URL +'/ajax_ecom_product_pagination_viewkey',
		success:function(response){
			$('.ecom_product-foot').html('');
			$('.ecom_product-foot').html(response);
			$('#ecom_showing_pagination').removeClass('ajax-loader');
			$('.ecom-product-viewkey').removeClass('ajax-loader');
			
		}
	});
}

$(document).on('click','.ecom-product-viewkey a',function(e){
	e.preventDefault();
	$('ecom-product-viewkey ul li').removeClass('active');
	$(this).parent().addClass('active');
	var page = $(this).attr('href').split('page=')[1];
	var selected = $("a",'.view-sidebar .active').attr('href');
	
	if(selected == '#goals'){
		var compare_status = $('.analyticsGraphCompare_view').prop('checked');
		if(compare_status == true){
			var compare_value = 1;
		}else{
			var compare_value = 0;
		}
		ecom_product_list_vk($('.graph_range_view_goal.active').attr('data-value'),compare_value,$('.campaign_id').val(),$('#encriptkey').val(),$('.traffic_display_type_goal.blue-btn').attr('data-type'),page);
	}else{
		ecom_product_list_vk($('.graph_range_viewkey').val(),$('.analyticsGraphCompare_goal').val(),$('.campaign_id').val(),$('#encriptkey').val(),$('.traffic_display_type_goal.blue-btn').attr('data-type'),page);
	}
});


function ecom_goalsChart_vk(value,compare_value,campaignId,key,type){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_ecom_goal_completion_chart_viewkey",
		data:{value,compare_value,campaign_id:campaignId,key,type},
		dataType:'json',
		success:function(result){
			if(result['status'] == 0){	
				$('#ecom_analytics_data_goal').css('display','none');	
			}
			if(result['status'] == 1){
				ecom_goals_graph(result);
				$('#ecom_analytics_data_goal').css('display','block');
			}

			$('.ecom-goal-completion-graph').removeClass('ajax-loader');
			
		}
	});
}

function ecom_goals_graph(result){
	if (window.myLineEcomGoalCompletion) {
		window.myLineEcomGoalCompletion.destroy();
	}
	var EcomGoalCompletion = document.getElementById('ecom-canvas-goals').getContext('2d');
	window.myLineEcomGoalCompletion = new Chart(EcomGoalCompletion, ecomconfigGoalCompletion);

	ecomconfigGoalCompletion.data.labels =  result['from_datelabel'];
	ecomconfigGoalCompletion.data.datasets[0].data = result['users'];
	ecomconfigGoalCompletion.data.datasets[0].labels = result['from_datelabels'];
	ecomconfigGoalCompletion.data.datasets[1].data = result['organic'];
	ecomconfigGoalCompletion.data.datasets[1].labelString = result['from_datelabels'];

	if(result['compare_status'] == 1){
		ecomconfigGoalCompletion.data.datasets.splice(2,2);
		ecomconfigGoalCompletion.data.datasets.splice(3,3);

		var dataset_1 = {
			label: " Ecommerce Conversion Rate (All Users)",
			fill: false,
			backgroundColor: window.chartColors.orange,
			borderColor: window.chartColors.orange,
			data: result['previous_users'],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2,
			labels: result['prev_from_datelabels']
		};
		var dataset_2 = {
			label: " Ecommerce Conversion Rate (Organic Traffic)",
			fill: false,
			backgroundColor: window.chartColors.pink,
			borderColor: window.chartColors.pink,
			data: result['previous_organic'],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2,
			labels: result['prev_from_datelabels']
		}

		ecomconfigGoalCompletion.data.datasets.push(dataset_1);
		ecomconfigGoalCompletion.data.datasets.push(dataset_2);

	} else{	
		ecomconfigGoalCompletion.data.datasets.splice(2,2);
		ecomconfigGoalCompletion.data.datasets.splice(3,3);

	}

	window.myLineEcomGoalCompletion.update();
}

/*September01*/

function ecom_goalsChart_tab(campaignId){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_ecom_goal_completion_chart_viewkey",
		data:{campaign_id:campaignId},
		dataType:'json',
		success:function(result){
			if(result['status'] == 0){	
				$('#ecom_analytics_data_goal').css('display','none');	
			}
			if(result['status'] == 1){ 
				ecom_goals_graph_tab(result);
				$('#ecom_analytics_data_goal').css('display','block');
			}

			$('.ecom-goal-completion-graph').removeClass('ajax-loader');
			
		}
	});
}

function ecom_goals_graph_tab(result){
	if (window.myLineEcomGoalCompletion) {
		window.myLineEcomGoalCompletion.destroy();
	}
	var EcomGoalCompletion = document.getElementById('ecom-canvas-goals-tab').getContext('2d');
	window.myLineEcomGoalCompletion = new Chart(EcomGoalCompletion, ecomconfigGoalCompletion);

	ecomconfigGoalCompletion.data.labels =  result['from_datelabel'];
	ecomconfigGoalCompletion.data.datasets[0].data = result['users'];
	ecomconfigGoalCompletion.data.datasets[0].labels = result['from_datelabels'];
	ecomconfigGoalCompletion.data.datasets[1].data = result['organic'];
	ecomconfigGoalCompletion.data.datasets[1].labelString = result['from_datelabels'];

	if(result['compare_status'] == 1){
		ecomconfigGoalCompletion.data.datasets.splice(2,2);
		ecomconfigGoalCompletion.data.datasets.splice(3,3);

		var dataset_1 = {
			label: " Ecommerce Conversion Rate (All Users)",
			fill: false,
			backgroundColor: window.chartColors.orange,
			borderColor: window.chartColors.orange,
			data: result['previous_users'],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2,
			labels: result['prev_from_datelabels']
		};
		var dataset_2 = {
			label: " Ecommerce Conversion Rate (Organic Traffic)",
			fill: false,
			backgroundColor: window.chartColors.pink,
			borderColor: window.chartColors.pink,
			data: result['previous_organic'],
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2,
			labels: result['prev_from_datelabels']
		}

		ecomconfigGoalCompletion.data.datasets.push(dataset_1);
		ecomconfigGoalCompletion.data.datasets.push(dataset_2);

	} else{	
		ecomconfigGoalCompletion.data.datasets.splice(2,2);
		ecomconfigGoalCompletion.data.datasets.splice(3,3);

	}

	window.myLineEcomGoalCompletion.update();
}

function ecom_conversion_rate_users_goals(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_conversion_rate_users",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-conversion-rate-graph-users-goals').getContext('2d');
				window.ecom_conversionRate_users = new Chart(ctx, configEcom_conversionRate_users);


				configEcom_conversionRate_users.data.labels = result['from_datelabel'];
				configEcom_conversionRate_users.data.datasets[0].data = result['data'];

				window.ecom_conversionRate_users.update();

				$('.ecom_conversion').removeClass('ajax-loader');
				$('.ecom_conversion').hide();
			}
		}
	});
}

function ecom_conversionRate_organic_goals(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_conversion_rate_organic",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-conversion-rate-graph-organic-goals').getContext('2d');
				window.ecom_conversion_rate_organic = new Chart(ctx, configEcom_conversionRate_organic);


				configEcom_conversionRate_organic.data.labels = result['from_datelabel'];
				configEcom_conversionRate_organic.data.datasets[0].data = result['data'];

				window.ecom_conversion_rate_organic.update();

				$('.ecom_conversionOrganic').removeClass('ajax-loader');
				$('.ecom_conversionOrganic').hide();
			}
		}
	});
}

function ecom_transactionUsers_goals(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_transaction_users",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-transaction-users-goals').getContext('2d');
				window.ecom_transaction_users = new Chart(ctx, configEcom_transaction_users);


				configEcom_transaction_users.data.labels = result['from_datelabel'];
				configEcom_transaction_users.data.datasets[0].data = result['data'];

				window.ecom_transaction_users.update();

				$('.ecom_transactionUsers').removeClass('ajax-loader');
				$('.ecom_transactionUsers').hide();
			}
		}
	});
}

function ecom_transactionOrganic_goals(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_transaction_organic",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-transaction-organic-goals').getContext('2d');
				window.ecom_transaction_organic = new Chart(ctx, configEcom_transaction_organic);


				configEcom_transaction_organic.data.labels = result['from_datelabel'];
				configEcom_transaction_organic.data.datasets[0].data = result['data'];

				window.ecom_transaction_organic.update();

				$('.ecom_transactionOrganic').removeClass('ajax-loader');
				$('.ecom_transactionOrganic').hide();
			}
		}
	});
}

function ecom_revenueUsers_goals(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_revenue_users",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-revenue-users-goals').getContext('2d');
				window.ecom_revenue_users = new Chart(ctx, configEcom_revenue_users);


				configEcom_revenue_users.data.labels = result['from_datelabel'];
				configEcom_revenue_users.data.datasets[0].data = result['data'];

				window.ecom_revenue_users.update();

				$('.ecom_RevenueUsers').removeClass('ajax-loader');
				$('.ecom_RevenueUsers').hide();
			}
		}
	});
}

function ecom_revenueOrganic_goals(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_revenue_organic",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-revenue-organic-goals').getContext('2d');
				window.ecom_revenue_organic = new Chart(ctx, configEcom_revenue_organic);


				configEcom_revenue_organic.data.labels = result['from_datelabel'];
				configEcom_revenue_organic.data.datasets[0].data = result['data'];

				window.ecom_revenue_organic.update();

				$('.ecom_RevenueOrganic').removeClass('ajax-loader');
				$('.ecom_RevenueOrganic').hide();
			}
		}
	});
}

function ecom_avg_orderValue_users_goals(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_avg_orderValue_users",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-orderValue-users-goals').getContext('2d');
				window.ecom_orderValue_users = new Chart(ctx, configEcom_avg_orderValue_users);


				configEcom_avg_orderValue_users.data.labels = result['from_datelabel'];
				configEcom_avg_orderValue_users.data.datasets[0].data = result['data'];

				window.ecom_orderValue_users.update();

				$('.ecom_avgorderValue_users').removeClass('ajax-loader');
				$('.ecom_avgorderValue_users').hide();
			}
		}
	});
}

function ecom_avg_orderValue_organic_goals(campaign_id){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_ecom_avg_orderValue_organic",
		data: {campaign_id},
		dataType: 'json',
		success: function(result) {
			if(result['status'] == 1){
				var ctx = document.getElementById('ecom-orderValue-organic-goals').getContext('2d');
				window.ecom_orderValue_organic = new Chart(ctx, configEcom_avg_orderValue_organic);


				configEcom_avg_orderValue_organic.data.labels = result['from_datelabel'];
				configEcom_avg_orderValue_organic.data.datasets[0].data = result['data'];

				window.ecom_orderValue_organic.update();

				$('.ecom_avgorderValue_organic').removeClass('ajax-loader');
				$('.ecom_avgorderValue_organic').hide();
			}
		}
	});
}

function ecom_goalsChart_tab_viewkey(value,compare_value,campaignId,key,type){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_ecom_goal_completion_chart_viewkey",
		data:{value,compare_value,campaign_id:campaignId,key,type},
		dataType:'json',
		success:function(result){
			if(result['status'] == 0){	
				$('#ecom_analytics_data_goal').css('display','none');	
			}
			if(result['status'] == 1){ 
				ecom_goals_graph_tab(result);
				$('#ecom_analytics_data_goal').css('display','block');
			}

			$('.ecom-goal-completion-graph').removeClass('ajax-loader');
			
		}
	});
}


function ecom_goalCompletionStatsGoals(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_ecom_goal_completion_overview",
		data:{campaign_id},
		dataType:'json',
		success:function(result){
			//overview field
			$('#ecom-conversionRate-users-sidebar').html(result['current_conversionRate']+'%');
			$('#ecom-conversionRate-organic-sidebar').html(result['current_conversionRate_organic']+'%');
			$('#ecom_transction_users-sidebar').html(result['current_transactions']);
			$('#ecom_transction_organic-sidebar').html(result['current_transactions_organic']);
			$('#ecom_revenue_users-sidebar').html(result['current_revenue']);
			$('#ecom_revenue_organic-sidebar').html(result['current_revenue_organic']);
			$('#ecom_avg_orderValue_users-sidebar').html(result['current_avg_orderVal']);
			$('#ecom_avg_orderValue_organic-sidebar').html(result['current_avg_orderVal_organic']);
			
			if(result['compare_status'] == 1){
				$('.ecom-chart-box').addClass('ecom-compare-section');
				//conversion rate  users
				$('#ecom-conversionRate-users-sidebar').append('<span><cite>vs</cite> '+result['previous_conversionRate']+ '% </span>');
				if(result['conversionRate_percentage'] < 0){
					var string_ecom_conversionRate = result['conversionRate_percentage'].toString();
					var replace_ecom_conversionRate = string_ecom_conversionRate.replace('-', '');
					var arrow = 'down'; var color = 'red';
				}else if(result['conversionRate_percentage'] > 0){
					var replace_ecom_conversionRate = result['conversionRate_percentage'];
					var arrow = 'up'; var color = 'green';
				}else {
					var replace_ecom_conversionRate = result['conversionRate_percentage'];
					var arrow = ''; var color = '';
				}
				$('.ecom-conversion-rate-users-percentage').html('<cite class='+color+'><span uk-icon="icon: arrow-'+arrow+'"></span>'+ replace_ecom_conversionRate +'%</cite>');

				//conversion rate  organic
				$('#ecom-conversionRate-organic-sidebar').append('<span><cite>vs</cite> '+result['previous_conversionRate_organic']+ '% </span>');
				if(result['conversionRate_percentage_organic'] < 0){
					var string_ecom_conversionRate_organic = result['conversionRate_percentage_organic'].toString();
					var replace_ecom_conversionRate_organic = string_ecom_conversionRate_organic.replace('-', '');
					var arrow1 = 'down'; var color1 = 'red';
				}else if(result['conversionRate_percentage_organic'] > 0){
					var replace_ecom_conversionRate_organic = result['conversionRate_percentage_organic'];
					var arrow1 = 'up'; var color1 = 'green';
				}else{
					var replace_ecom_conversionRate_organic = result['conversionRate_percentage_organic'];
					var arrow1 = ''; var color1 = '';
				}
				$('.ecom-conversion-rate-organic-percentage').html('<cite class='+color1+'><span uk-icon="icon: arrow-'+arrow1+'"></span>'+ replace_ecom_conversionRate_organic +'%</cite>');

				//transactions  users
				$('#ecom_transction_users-sidebar').append('<span><cite>vs</cite> '+result['previous_transactions']+ '</span>');
				if(result['transactions_percentage'] < 0){
					var string_ecom_transaction = result['transactions_percentage'].toString();
					var replace_ecom_transactions = string_ecom_transaction.replace('-', '');
					var arrow2 = 'down'; var color2 = 'red';
				}else if(result['transactions_percentage'] > 0){
					var replace_ecom_transactions = result['transactions_percentage'];
					var arrow2 = 'up'; var color2 = 'green';
				}else{
					var replace_ecom_transactions = result['transactions_percentage'];
					var arrow2 = ''; var color2 = '';
				}
				$('.ecom-transaction-users-percentage').html('<cite class='+color2+'><span uk-icon="icon: arrow-'+arrow2+'"></span>'+ replace_ecom_transactions +'%</cite>');

				//transactions  organic
				$('#ecom_transction_organic-sidebar').append('<span><cite>vs</cite> '+result['previous_transactions_organic']+ '</span>');
				if(result['transactions_percentage_organic'] < 0){
					var string_ecom_transaction_organic = result['transactions_percentage_organic'].toString();
					var replace_ecom_transactions_organic = string_ecom_transaction_organic.replace('-', '');
					var arrow3 = 'down'; var color3 = 'red';
				}else if(result['transactions_percentage_organic'] > 0){
					var replace_ecom_transactions_organic = result['transactions_percentage_organic'];
					var arrow3 = 'up'; var color3 = 'green';
				}else{
					var replace_ecom_transactions_organic = result['transactions_percentage_organic'];
					var arrow3 = ''; var color3 = '';
				}
				$('.ecom-transaction-organic-percentage').html('<cite class='+color3+'><span uk-icon="icon: arrow-'+arrow3+'"></span>'+replace_ecom_transactions_organic+'%</cite>');
				
				//revenue  users
				$('#ecom_revenue_users-sidebar').append('<span><cite>vs</cite> '+result['previous_revenue']+ '</span>');
				if(result['revenue_percentage'] < 0){
					var string_ecom_revnue = result['revenue_percentage'].toString();
					var replace_ecom_revenue = string_ecom_revnue.replace('-', '');
					var arrow4 = 'down'; var color4 = 'red';
				}else if(result['revenue_percentage'] > 0){
					var replace_ecom_revenue = result['revenue_percentage'];
					var arrow4 = 'up'; var color4 = 'green';
				}else{
					var replace_ecom_revenue = result['revenue_percentage'];
					var arrow4 = ''; var color4 = '';
				}
				$('.ecom-revenue-users-percentage').html('<cite class='+color4+'><span uk-icon="icon: arrow-'+arrow4+'"></span>'+replace_ecom_revenue+'%</cite>');

				//revenue  organic
				$('#ecom_revenue_organic-sidebar').append('<span><cite>vs</cite> '+result['previous_revenue_organic']+ '</span>');
				if(result['revenue_percentage_organic'] < 0){
					var string_ecom_revnue_organic = result['revenue_percentage_organic'].toString();
					var replace_ecom_revenue_organic = string_ecom_revnue_organic.replace('-', '');
					var arrow5 = 'down'; var color5 = 'red';
				}else if(result['revenue_percentage_organic'] > 0){
					var replace_ecom_revenue_organic = result['revenue_percentage_organic'];
					var arrow5 = 'up'; var color5 = 'green';
				}else{
					var replace_ecom_revenue_organic = result['revenue_percentage_organic'];
					var arrow5 = ''; var color5 = '';
				}
				$('.ecom-revenue-organic-percentage').html('<cite class='+color5+'><span uk-icon="icon: arrow-'+arrow5+'"></span>'+replace_ecom_revenue_organic+'%</cite>');

				//order value  users
				$('#ecom_avg_orderValue_users-sidebar').append('<span><cite>vs</cite> '+result['previous_avg_orderVal']+ '</span>');
				if(result['avg_orderVal_percentage'] < 0){
					var string_ecom_orderVal = result['avg_orderVal_percentage'].toString();
					var replace_ecom_orderVal = string_ecom_orderVal.replace('-', '');
					var arrow6 = 'down'; var color6 = 'red';
				}else if(result['avg_orderVal_percentage'] > 0){
					var replace_ecom_orderVal = result['avg_orderVal_percentage'];
					var arrow6 = 'up'; var color6 = 'green';
				}else{
					var replace_ecom_orderVal = result['avg_orderVal_percentage'];
					var arrow6 = ''; var color6 = '';
				}
				$('.ecom-orderValue-users-percentage').html('<cite class='+color6+'><span uk-icon="icon: arrow-'+arrow6+'"></span>'+replace_ecom_orderVal+'%</cite>');

				//order value  organic
				$('#ecom_avg_orderValue_organic-sidebar').append('<span><cite>vs</cite> '+result['previous_avg_orderVal_organic']+ '</span>');
				if(result['avg_orderVal_percentage_organic'] < 0){
					var string_ecom_orderVal_organic = result['avg_orderVal_percentage_organic'].toString();
					var replace_ecom_orderVal_organic = string_ecom_orderVal_organic.replace('-', '');
					var arrow7 = 'down'; var color7 = 'red';
				}else if(result['avg_orderVal_percentage_organic'] > 0){
					var replace_ecom_orderVal_organic = result['avg_orderVal_percentage_organic'];
					var arrow7 = 'up'; var color7 = 'green';
				}else{
					var replace_ecom_orderVal_organic = result['avg_orderVal_percentage_organic'];
					var arrow7 = 'up'; var color7 = 'green';
				}
				$('.ecom-orderValue-organic-percentage').html('<cite class='+color7+'><span uk-icon="icon: arrow-'+arrow7+'"></span>'+replace_ecom_orderVal_organic+'%</cite>');

			}else{
				$('.ecom-chart-box').removeClass('ecom-compare-section');
				$('.ecom-conversion-rate-users-percentage').html('');
				$('.ecom-conversion-rate-organic-percentage').html('');
				$('.ecom-transaction-users-percentage').html('');
				$('.ecom-transaction-organic-percentage').html('');
				$('.ecom-revenue-users-percentage').html('');
				$('.ecom-revenue-organic-percentage').html('');
				$('.ecom-orderValue-users-percentage').html('');
				$('.ecom-orderValue-organic-percentage').html('');
			}
			$('.compare').removeClass('ajax-loader');
			$('.ecom_conversion_percentage').removeClass('ajax-loader');
			$('.ecom_conversion_percentage_organic').removeClass('ajax-loader');
			$('.ecom_transaction_percentage_users').removeClass('ajax-loader');
			$('.ecom_transaction_percentage_organic').removeClass('ajax-loader');
			$('.ecom_revenue_users_percentage').removeClass('ajax-loader');
			$('.ecom_revenue_organic_percentage').removeClass('ajax-loader');
			$('.ecom_orderValue_users_percentage').removeClass('ajax-loader');
			$('.ecom_orderValue_organic_percentage').removeClass('ajax-loader');


		}
	});
}


function ecom_goalCompletionStatsGoals_vk(value,compare_value,campaignId,key,type){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_ecom_goal_completion_overview_viewkey",
		data:{value,compare_value,campaign_id:campaignId,key,type},
		dataType:'json',
		success:function(result){
			//overview field
			$('#ecom-conversionRate-users-sidebar').html(result['current_conversionRate']+'%');
			$('#ecom-conversionRate-organic-sidebar').html(result['current_conversionRate_organic']+'%');
			$('#ecom_transction_users-sidebar').html(result['current_transactions']);
			$('#ecom_transction_organic-sidebar').html(result['current_transactions_organic']);
			$('#ecom_revenue_users-sidebar').html(result['current_revenue']);
			$('#ecom_revenue_organic-sidebar').html(result['current_revenue_organic']);
			$('#ecom_avg_orderValue_users-sidebar').html(result['current_avg_orderVal']);
			$('#ecom_avg_orderValue_organic-sidebar').html(result['current_avg_orderVal_organic']);
			
			if(result['compare_status'] == 1){

				$('.ecom-chart-box').addClass('ecom-compare-section');
				//conversion rate  users
				$('#ecom-conversionRate-users-sidebar').append('<span><cite>vs</cite> '+result['previous_conversionRate']+ '% </span>');
				if(result['conversionRate_percentage'] < 0){
					var string_ecom_conversionRate = result['conversionRate_percentage'].toString();
					var replace_ecom_conversionRate = string_ecom_conversionRate.replace('-', '');
					var arrow = 'down'; var color = 'red';
				}else if(result['conversionRate_percentage'] > 0){
					var replace_ecom_conversionRate = result['conversionRate_percentage'];
					var arrow = 'up'; var color = 'green';
				}else {
					var replace_ecom_conversionRate = result['conversionRate_percentage'];
					var arrow = ''; var color = '';
				}
				$('.ecom-conversion-rate-users-percentage').html('<cite class='+color+'><span uk-icon="icon: arrow-'+arrow+'"></span>'+ replace_ecom_conversionRate +'%</cite>');

				//conversion rate  organic
				$('#ecom-conversionRate-organic-sidebar').append('<span><cite>vs</cite> '+result['previous_conversionRate_organic']+ '% </span>');
				if(result['conversionRate_percentage_organic'] < 0){
					var string_ecom_conversionRate_organic = result['conversionRate_percentage_organic'].toString();
					var replace_ecom_conversionRate_organic = string_ecom_conversionRate_organic.replace('-', '');
					var arrow1 = 'down'; var color1 = 'red';
				}else if(result['conversionRate_percentage_organic'] > 0){
					var replace_ecom_conversionRate_organic = result['conversionRate_percentage_organic'];
					var arrow1 = 'up'; var color1 = 'green';
				}else{
					var replace_ecom_conversionRate_organic = result['conversionRate_percentage_organic'];
					var arrow1 = ''; var color1 = '';
				}
				$('.ecom-conversion-rate-organic-percentage').html('<cite class='+color1+'><span uk-icon="icon: arrow-'+arrow1+'"></span>'+ replace_ecom_conversionRate_organic +'%</cite>');

				//transactions  users
				$('#ecom_transction_users-sidebar').append('<span><cite>vs</cite> '+result['previous_transactions']+ '</span>');
				if(result['transactions_percentage'] < 0){
					var string_ecom_transaction = result['transactions_percentage'].toString();
					var replace_ecom_transactions = string_ecom_transaction.replace('-', '');
					var arrow2 = 'down'; var color2 = 'red';
				}else if(result['transactions_percentage'] > 0){
					var replace_ecom_transactions = result['transactions_percentage'];
					var arrow2 = 'up'; var color2 = 'green';
				}else{
					var replace_ecom_transactions = result['transactions_percentage'];
					var arrow2 = ''; var color2 = '';
				}
				$('.ecom-transaction-users-percentage').html('<cite class='+color2+'><span uk-icon="icon: arrow-'+arrow2+'"></span>'+ replace_ecom_transactions +'%</cite>');

				//transactions  organic
				$('#ecom_transction_organic-sidebar').append('<span><cite>vs</cite> '+result['previous_transactions_organic']+ '</span>');
				if(result['transactions_percentage_organic'] < 0){
					var string_ecom_transaction_organic = result['transactions_percentage_organic'].toString();
					var replace_ecom_transactions_organic = string_ecom_transaction_organic.replace('-', '');
					var arrow3 = 'down'; var color3 = 'red';
				}else if(result['transactions_percentage_organic'] > 0){
					var replace_ecom_transactions_organic = result['transactions_percentage_organic'];
					var arrow3 = 'up'; var color3 = 'green';
				}else{
					var replace_ecom_transactions_organic = result['transactions_percentage_organic'];
					var arrow3 = ''; var color3 = '';
				}
				$('.ecom-transaction-organic-percentage').html('<cite class='+color3+'><span uk-icon="icon: arrow-'+arrow3+'"></span>'+replace_ecom_transactions_organic+'%</cite>');
				
				//revenue  users
				$('#ecom_revenue_users-sidebar').append('<span><cite>vs</cite> '+result['previous_revenue']+ '</span>');
				if(result['revenue_percentage'] < 0){
					var string_ecom_revnue = result['revenue_percentage'].toString();
					var replace_ecom_revenue = string_ecom_revnue.replace('-', '');
					var arrow4 = 'down'; var color4 = 'red';
				}else if(result['revenue_percentage'] > 0){
					var replace_ecom_revenue = result['revenue_percentage'];
					var arrow4 = 'up'; var color4 = 'green';
				}else{
					var replace_ecom_revenue = result['revenue_percentage'];
					var arrow4 = ''; var color4 = '';
				}
				$('.ecom-revenue-users-percentage').html('<cite class='+color4+'><span uk-icon="icon: arrow-'+arrow4+'"></span>'+replace_ecom_revenue+'%</cite>');

				//revenue  organic
				$('#ecom_revenue_organic-sidebar').append('<span><cite>vs</cite> '+result['previous_revenue_organic']+ '</span>');
				if(result['revenue_percentage_organic'] < 0){
					var string_ecom_revnue_organic = result['revenue_percentage_organic'].toString();
					var replace_ecom_revenue_organic = string_ecom_revnue_organic.replace('-', '');
					var arrow5 = 'down'; var color5 = 'red';
				}else if(result['revenue_percentage_organic'] > 0){
					var replace_ecom_revenue_organic = result['revenue_percentage_organic'];
					var arrow5 = 'up'; var color5 = 'green';
				}else{
					var replace_ecom_revenue_organic = result['revenue_percentage_organic'];
					var arrow5 = ''; var color5 = '';
				}
				$('.ecom-revenue-organic-percentage').html('<cite class='+color5+'><span uk-icon="icon: arrow-'+arrow5+'"></span>'+replace_ecom_revenue_organic+'%</cite>');

				//order value  users
				$('#ecom_avg_orderValue_users-sidebar').append('<span><cite>vs</cite> '+result['previous_avg_orderVal']+ '</span>');
				if(result['avg_orderVal_percentage'] < 0){
					var string_ecom_orderVal = result['avg_orderVal_percentage'].toString();
					var replace_ecom_orderVal = string_ecom_orderVal.replace('-', '');
					var arrow6 = 'down'; var color6 = 'red';
				}else if(result['avg_orderVal_percentage'] > 0){
					var replace_ecom_orderVal = result['avg_orderVal_percentage'];
					var arrow6 = 'up'; var color6 = 'green';
				}else{
					var replace_ecom_orderVal = result['avg_orderVal_percentage'];
					var arrow6 = ''; var color6 = '';
				}
				$('.ecom-orderValue-users-percentage').html('<cite class='+color6+'><span uk-icon="icon: arrow-'+arrow6+'"></span>'+replace_ecom_orderVal+'%</cite>');

				//order value  organic
				$('#ecom_avg_orderValue_organic-sidebar').append('<span><cite>vs</cite> '+result['previous_avg_orderVal_organic']+ '</span>');
				if(result['avg_orderVal_percentage_organic'] < 0){
					var string_ecom_orderVal_organic = result['avg_orderVal_percentage_organic'].toString();
					var replace_ecom_orderVal_organic = string_ecom_orderVal_organic.replace('-', '');
					var arrow7 = 'down'; var color7 = 'red';
				}else if(result['avg_orderVal_percentage_organic'] > 0){
					var replace_ecom_orderVal_organic = result['avg_orderVal_percentage_organic'];
					var arrow7 = 'up'; var color7 = 'green';
				}else{
					var replace_ecom_orderVal_organic = result['avg_orderVal_percentage_organic'];
					var arrow7 = 'up'; var color7 = 'green';
				}
				$('.ecom-orderValue-organic-percentage').html('<cite class='+color7+'><span uk-icon="icon: arrow-'+arrow7+'"></span>'+replace_ecom_orderVal_organic+'%</cite>');

			}else{
				$('.ecom-chart-box').removeClass('ecom-compare-section');
				$('.ecom-conversion-rate-users-percentage').html('');
				$('.ecom-conversion-rate-organic-percentage').html('');
				$('.ecom-transaction-users-percentage').html('');
				$('.ecom-transaction-organic-percentage').html('');
				$('.ecom-revenue-users-percentage').html('');
				$('.ecom-revenue-organic-percentage').html('');
				$('.ecom-orderValue-users-percentage').html('');
				$('.ecom-orderValue-organic-percentage').html('');
			}
			$('.compare').removeClass('ajax-loader');
			$('.ecom_conversion_percentage').removeClass('ajax-loader');
			$('.ecom_conversion_percentage_organic').removeClass('ajax-loader');
			$('.ecom_transaction_percentage_users').removeClass('ajax-loader');
			$('.ecom_transaction_percentage_organic').removeClass('ajax-loader');
			$('.ecom_revenue_users_percentage').removeClass('ajax-loader');
			$('.ecom_revenue_organic_percentage').removeClass('ajax-loader');
			$('.ecom_orderValue_users_percentage').removeClass('ajax-loader');
			$('.ecom_orderValue_organic_percentage').removeClass('ajax-loader');


		}
	});
}