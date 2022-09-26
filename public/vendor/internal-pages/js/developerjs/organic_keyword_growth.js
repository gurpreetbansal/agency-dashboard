var BASE_URL = $('.base_url').val();
var color = Chart.helpers.color;
var Keywordconfig = {
	type: 'bar',
	data: {
		labels: [],
		datasets: [
		{
			label: "Top 3 ",
			backgroundColor: color(window.chartColors.yellow).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:25
		}, 
		{
			label: "4-10 ",
			backgroundColor: color(window.chartColors.lightSkyBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:25
		},
		{
			label: "11-20 ",
			backgroundColor: color(window.chartColors.steelBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:25
		},
		{
			label: "21-50 ",
			backgroundColor: color(window.chartColors.royalBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:25
		},
		{
			label: "51-100 ",
			backgroundColor: color(window.chartColors.darkBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:25
		}
   ]
},
options: {
	title: {
		display: false,
		text: 'Extra Organic Keywords'
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
			,
			label: function(tooltipItem, data) {
				var corporation = data.datasets[tooltipItem.datasetIndex].label;
				var valor = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
				var total = 0;
				for (var i = 0; i < data.datasets.length; i++)
					total += data.datasets[i].data[tooltipItem.index];
				if (tooltipItem.datasetIndex != data.datasets.length - 1) {
					return corporation + " : " + valor;
				} else {
					return [corporation + " : " + valor, "Total : " + total];
				}
			}
		}
	},
	maintainAspectRatio: false,
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
		}
	}
};

var KeywordconfigRank = {
	type: 'bar',
	data: {
		labels: [],
		datasets: [
		{
			label: "Top 3 ",
			backgroundColor: color(window.chartColors.yellow).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:25
		}, 
		{
			label: "4-10 ",
			backgroundColor: color(window.chartColors.lightSkyBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:25
		},
		{
			label: "11-20 ",
			backgroundColor: color(window.chartColors.steelBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:25
		},
		{
			label: "21-50 ",
			backgroundColor: color(window.chartColors.royalBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:25
		},
		{
			label: "51-100 ",
			backgroundColor: color(window.chartColors.darkBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:25
		}
   ]
},
options: {
	title: {
		display: false,
		text: 'Extra Organic Keywords'
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

			,label: function(tooltipItem, data) {
				var corporation = data.datasets[tooltipItem.datasetIndex].label;
				var valor = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
				var total = 0;
				for (var i = 0; i < data.datasets.length; i++)
					total += data.datasets[i].data[tooltipItem.index];
				if (tooltipItem.datasetIndex != data.datasets.length - 1) {
					return corporation + " : " + valor;
				} else {
					return [corporation + " : " + valor, "Total : " + total];
				}
			}
		}
	},
	maintainAspectRatio: false,
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
	}
}
};

var KeywordDetailconfig = {
	type: 'bar',
	data: {
		labels: [],
		datasets: [
		{
			label: "Top 3 ",
			backgroundColor: color(window.chartColors.yellow).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:30
		}, 
		{
			label: "4-10 ",
			backgroundColor: color(window.chartColors.lightSkyBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:30
		},
		{
			label: "11-20 ",
			backgroundColor: color(window.chartColors.steelBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:30
		},
		{
			label: "21-50 ",
			backgroundColor: color(window.chartColors.royalBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:30
		},
		{
			label: "51-100 ",
			backgroundColor: color(window.chartColors.darkBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:30
		}
   ]
},
options: {
	title: {
		display: false,
		text: 'Extra Organic Keywords'
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
			,
			label: function(tooltipItem, data) {
				var corporation = data.datasets[tooltipItem.datasetIndex].label;
				var valor = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
				var total = 0;
				for (var i = 0; i < data.datasets.length; i++)
					total += data.datasets[i].data[tooltipItem.index];
				if (tooltipItem.datasetIndex != data.datasets.length - 1) {
					return corporation + " : " + valor;
				} else {
					return [corporation + " : " + valor, "Total : " + total];
				}
			}
		}
	},
	maintainAspectRatio: false,
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
	legend:{
		position:'right',
		align:'end',
			// labels :{
			// 	boxWidth:100,
			// 	padding:20
			// }
		}
	}
};

var KeywordDetailconfigDetail = {
	type: 'bar',
	data: {
		labels: [],
		datasets: [
		{
			label: "Top 3 ",
			backgroundColor: color(window.chartColors.yellow).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:30
		}, 
		{
			label: "4-10 ",
			backgroundColor: color(window.chartColors.lightSkyBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:30
		},
		{
			label: "11-20 ",
			backgroundColor: color(window.chartColors.steelBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:30
		},
		{
			label: "21-50 ",
			backgroundColor: color(window.chartColors.royalBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:30
		},
		{
			label: "51-100 ",
			backgroundColor: color(window.chartColors.darkBlue).alpha(1.5).rgbString(),
			data: [],
			maxBarThickness:30
		}
   ]
},
options: {
	title: {
		display: false,
		text: 'Extra Organic Keywords'
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
			,label: function(tooltipItem, data) {
				var corporation = data.datasets[tooltipItem.datasetIndex].label;
				var valor = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
				var total = 0;
				for (var i = 0; i < data.datasets.length; i++)
					total += data.datasets[i].data[tooltipItem.index];
				if (tooltipItem.datasetIndex != data.datasets.length - 1) {
					return corporation + " : " + valor;
				} else {
					return [corporation + " : " + valor, "Total : " + total];
				}
			}
		}
	},
	maintainAspectRatio: false,
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
	legend:{
		position:'right',
		align:'end',
			// labels :{
			// 	boxWidth:100,
			// 	padding:20
			// }
		}
	}
};


function keywordsMetricBarChart(campaignId) {
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_extra_organic_bar_chart",
		data: { campaignId: campaignId },
		dataType: 'json',
		success: function(result) {
			if (window.myLineKeyword) {
				window.myLineKeyword.destroy();
			}

			var ctxs = document.getElementById('new-keywordsCanvas').getContext('2d');
			window.myLineKeyword = new Chart(ctxs, Keywordconfig);

			Keywordconfig.data.labels = JSON.parse(result['names']);
			Keywordconfig.data.datasets[0].data = JSON.parse(result['top_three']);
			Keywordconfig.data.datasets[1].data = JSON.parse(result['four_ten']);
			Keywordconfig.data.datasets[2].data = JSON.parse(result['eleven_twenty']);
			Keywordconfig.data.datasets[3].data = JSON.parse(result['twentyone_fifty']);
			Keywordconfig.data.datasets[4].data = JSON.parse(result['total']);
			// Keywordconfig.data.datasets[5].data = JSON.parse(result['total_count']);
			window.myLineKeyword.update();
			$('#keywords-canvas').removeClass('ajax-loader');
		}
	});
}

function keywordsMetricBarChartRank(campaignId) {
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_extra_organic_bar_chart",
		data: { campaignId: campaignId },
		dataType: 'json',
		success: function(result) {


			$('#keywords-canvasRank').removeClass('ajax-loader');
			if (window.myLineKeywordRank) {
				window.myLineKeywordRank.destroy();
			}

			var ctxs = document.getElementById('new-keywordsCanvasRank').getContext('2d');
			window.myLineKeywordRank = new Chart(ctxs, KeywordconfigRank);

			KeywordconfigRank.data.labels = JSON.parse(result['names']);
			KeywordconfigRank.data.datasets[0].data = JSON.parse(result['top_three']);
			KeywordconfigRank.data.datasets[1].data = JSON.parse(result['four_ten']);
			KeywordconfigRank.data.datasets[2].data = JSON.parse(result['eleven_twenty']);
			KeywordconfigRank.data.datasets[3].data = JSON.parse(result['twentyone_fifty']);
			KeywordconfigRank.data.datasets[4].data = JSON.parse(result['total']);
			// Keywordconfig.data.datasets[5].data = JSON.parse(result['total_count']);
			window.myLineKeywordRank.update();
			
		}
	});
}

function keywordsMetricBarDetailChart(campaignId) {
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_extra_organic_bar_chart",
		data: { campaignId: campaignId },
		dataType: 'json',
		success: function(result) {
			$('#keywords-canvas').removeClass('ajax-loader');
			if (window.myLineKeyword) {
				window.myLineKeyword.destroy();
			}

			var ctxs = document.getElementById('new-keywordsCanvas').getContext('2d');
			window.myLineKeyword = new Chart(ctxs, KeywordDetailconfig);

			KeywordDetailconfig.data.labels = JSON.parse(result['names']);
			KeywordDetailconfig.data.datasets[0].data = JSON.parse(result['top_three']);
			KeywordDetailconfig.data.datasets[1].data = JSON.parse(result['four_ten']);
			KeywordDetailconfig.data.datasets[2].data = JSON.parse(result['eleven_twenty']);
			KeywordDetailconfig.data.datasets[3].data = JSON.parse(result['twentyone_fifty']);
			KeywordDetailconfig.data.datasets[4].data = JSON.parse(result['total']);
			// KeywordDetailconfig.data.datasets[5].data = JSON.parse(result['total_count']);
			window.myLineKeyword.update();


		}
	});
}

function keywordsMetricBarDetailChartDetail(campaignId) {
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_extra_organic_bar_chart",
		data: { campaignId: campaignId },
		dataType: 'json',
		success: function(result) {
			$('#keywords-canvas').removeClass('ajax-loader');
			if (window.myLineKeyword) {
				window.myLineKeyword.destroy();
			}

			var ctxs = document.getElementById('new-keywordsCanvasDetail').getContext('2d');
			window.myLineKeyword = new Chart(ctxs, KeywordDetailconfigDetail);

			KeywordDetailconfigDetail.data.labels = JSON.parse(result['names']);
			KeywordDetailconfigDetail.data.datasets[0].data = JSON.parse(result['top_three']);
			KeywordDetailconfigDetail.data.datasets[1].data = JSON.parse(result['four_ten']);
			KeywordDetailconfigDetail.data.datasets[2].data = JSON.parse(result['eleven_twenty']);
			KeywordDetailconfigDetail.data.datasets[3].data = JSON.parse(result['twentyone_fifty']);
			KeywordDetailconfigDetail.data.datasets[4].data = JSON.parse(result['total']);
			// KeywordDetailconfig.data.datasets[5].data = JSON.parse(result['total_count']);
			window.myLineKeyword.update();


		}
	});
}

function ExtraOrganicKeywordList(campaignId){
	$.ajax({
		url:BASE_URL +'/ajax_extra_organic_keywords',
		type:'GET',
		data:{campaignId},
		success:function(response){
			$('#extra_organic_keywords tbody').html('');
			$('#extra_organic_keywords tbody').html(response);
			$('#extra_organic_keywords tr th').removeClass('ajax-loader');
		}
	});
}

function ExtraOrganicKeywordListViewKey(campaignId){
	var page = $('#hidden_page').val();
	var limit = $('#extraOrganiclimit').val();
	var query = $('.organic-keyword-search').val();

	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();

	fetch_extra_organic_keywords(page,column_name,order_type,campaignId,query,limit);
}

function ExtraOrganicKeywordCount(campaignId){
	$.ajax({
		url:BASE_URL +'/ajax_extra_organic_keywords_count',
		type:'GET',
		data:{campaignId},
		success:function(response){
			$('.top-key-organic	').removeClass('ajax-loader');
			$('.total_count').html('('+response['count']+')');
		}
	});
}


$(document).on('click','.OrgnicDetail a',function(e){
	e.preventDefault();
	$('li').removeClass('active');
	$(this).parent().addClass('active');

	var page = $(this).attr('href').split('page=')[1];

	$('#hidden_page').val(page);
	
	var campaignID = $('.campaignID').val();
	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var limit =  $('#extraOrganiclimit').val();
	var query = $('.organic-keyword-search').val();
	
	fetch_extra_organic_keywords(page,column_name,order_type,campaignID,query,limit);

});

function fetch_extra_organic_keywords(page,column_name,reverse_order,campaignID,query,limit){
	$.ajax({
		url:BASE_URL +'/ajax_fetch_organic_keyword_data',
		type:'GET',
		data:{page,campaignID,column_name,reverse_order,query,limit},
		success:function(response){
			$('#extra-organix tbody').html('');
			$('#extra-organix tbody').html(response);
			$('#refresh-organicKeyword-search').css('display','none');
		}
	});

	$.ajax({
		url:BASE_URL +'/ajax_fetch_keyword_pagination',
		type:'GET',
		data:{page,campaignID,column_name,reverse_order,query,limit},
		success:function(response){
			$('.extra-organix-foot').html('');
			$('.extra-organix-foot').html(response);
		}
	});
}


$(document).on('click','.organic_sorting',function(e){
	e.preventDefault();
	var column_name = $(this).attr('data-column_name');
	var order_type = $(this).attr('data-sorting_type');


	if(order_type == 'asc')
	{
		$(this).attr('data-sorting_type', 'desc');
		reverse_order = 'desc';
		$('.asc').removeClass('asc');
		$('.desc').removeClass('desc');
		$(this).addClass('desc');
	}


	if(order_type == 'desc')
	{
		$(this).attr('data-sorting_type', 'asc');
		reverse_order = 'asc';
		$('.desc').removeClass('desc');
		$('.asc').removeClass('asc');
		$(this).addClass('asc');

	}


	$('#hidden_column_name').val(column_name);
	$('#hidden_sort_type').val(reverse_order);
	var page = $('#hidden_page').val();
	var campaignID = $('.campaignID').val();
	var limit = $('#extraOrganiclimit').val();
	var query = $('.organic-keyword-search').val();

	fetch_extra_organic_keywords(page,column_name,reverse_order,campaignID,query,limit);
});

function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}


$(document).on('keyup','.organic-keyword-search',function(){
	$('#refresh-organicKeyword-search').css('display','block');
});

$(document).on('keyup','.organic-keyword-search',delay(function(e){
  if($('.organic-keyword-search').val() != '' || $('.organic-keyword-search').val() != null){
    $('.organicKeyword-search-clear').css('display','block');
  }

  if($('.organic-keyword-search').val() == '' || $('.organic-keyword-search').val() == null){
    $('.organicKeyword-search-clear').css('display','none');
  }
  organicKeyword_data();
}, 1500));


$(document).on('click','.organicKeywordClear',function(e){
  e.preventDefault();
  $('.organic-keyword-search').val('');
  if($('.organic-keyword-search').val() == '' || $('.organic-keyword-search').val() == null){
    $('.organicKeyword-search-clear').css('display','none');
    organicKeyword_data();
  }
});

function organicKeyword_data(){
    var query = $('.organic-keyword-search').val();
	var page = $('#hidden_page').val();
	var limit = $('#extraOrganiclimit').val();
	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var campaignID = $('.campaignID').val();
	fetch_extra_organic_keywords(page,column_name,order_type,campaignID,query,limit);
}

$(document).on('change','#extra-organic-limit',function(e){
	e.preventDefault();
	var limit = $(this).val();
	$('#extraOrganiclimit').val(limit);
	var query = $('.organic-keyword-search').val();
	var page = 1;
	var column_name = $('#hidden_column_name').val();
	var order_type = $('#hidden_sort_type').val();
	var campaignID = $('.campaignID').val();
	fetch_extra_organic_keywords(page,column_name,order_type,campaignID,query,limit);
});

function organicKeywordTimeAgo(campaign_id){
	$.ajax({
		type: 'GET',
		url:  BASE_URL + '/ajax_get_organic_keyword_growth_time',
		data: {campaign_id},
		success: function(result) {
			if (result['status'] == 1) {
				$('.organic_keyword_time').html(result['time']);
			}
		}
	});
}

$(document).on('click','#refresh_organic_keyword_growth',function(){
	var campaign_id = $(this).attr('data-request-id');
	$(this).addClass('refresh-gif');
	$('.organicKeyword-progress-loader').css('display','block');
	$('#keywords-canvas').addClass('ajax-loader');
	$('#extra_organic_keywords tr td').addClass('ajax-loader');
	$('#extra-organix tr td').addClass('ajax-loader');
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_get_latest_organic_keyword_growth',
		data:{campaign_id},
		dataType:'json',
		success:function(response){
			var currentUrl = window.location.pathname;
			if(response['status'] == 1){
				if(currentUrl.search('extra-organic-keywords/*') == -1){
					organicKeywordsChart(campaign_id);
					ajaxOrganicKeywordRanking(campaign_id);
				}
				organicKeywordTimeAgo(campaign_id);
				keywordsMetricBarChart(campaign_id);
				ExtraOrganicKeywordCount(campaign_id);
				ExtraOrganicKeywordList(campaign_id);
			}
			else{
				Command: toastr["error"]('Error, please try again later.');
			}

			$('#refresh_organic_keyword_growth').removeClass('refresh-gif');
			$('.organicKeyword-progress-loader').css('display','none');
			$('#keywords-canvas').removeClass('ajax-loader');
			$('#extra_organic_keywords tr td').removeClass('ajax-loader');
			$('#extra-organix tr td').removeClass('ajax-loader');
		}
	});
});

function keywordsMetricChartStats(campaignId) {
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_extra_organic_chart_stats",
		data: { campaignId: campaignId },
		dataType: 'json',
		success: function(result) {
			$('.organic_keyword_top3').html(result['top_three']);
			$('.organic_keyword_4_10').html(result['four_ten']);
			$('.organic_keyword_11_20').html(result['eleven_twenty']);
			$('.organic_keyword_21_50').html(result['twentyone_fifty']);
			$('.organic_keyword_51_100').html(result['total']);
			$('.organic_keyword_total').html(result['total_count']);
		}
	});
}