<script>
	  var base_url = "<?php echo url('/') ?>";
        $(function () {
            var table = $('#google_ads_campaigns').DataTable({
                processing: true,
                serverSide: true,
				async: false,
                "deferRender": true,
                'ajax': {
                    'url': base_url + '/ajaxAdsCampaign',
					 'data': function (data) {
                        data.today = $('.today').val();
                        data.account_id = $('.account_id').val();
                        data.currency_code = $('.currency_code').val();

                    }
                },
                columns: [
                    {data: 'campaign_name', name: 'campaign_name', "orderable": false},
                    {data: 'impressions', name: 'impressions', "orderable": false},
                    {data: 'clicks', name: 'clicks', "orderable": false},
                    {data: 'ctr', name: 'ctr', "orderable": false},
                    {data: 'cost', name: 'cost', "orderable": false},
                    {data: 'conversions', name: 'conversions', "orderable": false}
                ]
            });

		
		$('#google_ads_keywords').DataTable({
			"destroy": true,
		  "processing":true,
		  "serverSide":true,
		  async: false,
			"ajax":{
				'url': base_url + '/ajaxAdsKeywords',
				type:"GET",
				'data': function (data) {
					data.today = $('.today').val();
					data.account_id = $('.account_id').val();
					data.currency_code = $('.currency_code').val();

				}
			},
			columns: [
                    {data: 'keywords', name: 'keywords', "orderable": false},
                    {data: 'impressions', name: 'impressions', "orderable": false},
                    {data: 'clicks', name: 'clicks', "orderable": false},
                    {data: 'ctr', name: 'ctr', "orderable": false},
                    {data: 'cost', name: 'cost', "orderable": false},
                    {data: 'conversions', name: 'conversions', "orderable": false}
                ]
		});	
		
	
       
		$('#google_ads').DataTable({
		  "processing":true,
		  "serverSide":true,
		  async: false,
			"ajax":{
				'url': base_url + '/ajaxAdsData',
				type:"GET",
				'data': function (data) {
					data.today = $('.today').val();
					data.account_id = $('.account_id').val();
					data.currency_code = $('.currency_code').val();

				}
			},
			columns: [
                   {data: 'ad', name: 'ad', "orderable": false},
                    {data: 'ad_type', name: 'ad_type', "orderable": false},
                    {data: 'impressions', name: 'impressions', "orderable": false},
                    {data: 'clicks', name: 'clicks', "orderable": false},
                    {data: 'ctr', name: 'ctr', "orderable": false},
                    {data: 'cost', name: 'cost', "orderable": false},
                    {data: 'conversions', name: 'conversions', "orderable": false}
                ]
		});	
		
		
		$('#google_ad_groups').DataTable({
		  "processing":true,
		  "serverSide":true,
		  async: false,
			"ajax":{
				'url': base_url + '/ajaxAdGroupsData',
				type:"GET",
				'data': function (data) {
					data.today = $('.today').val();
					data.account_id = $('.account_id').val();
					data.currency_code = $('.currency_code').val();

				}
			},
			columns: [
                    {data: 'ad_group', name: 'ad_group', "orderable": false},
                    {data: 'impressions', name: 'impressions', "orderable": false},
                    {data: 'clicks', name: 'clicks', "orderable": false},
                    {data: 'ctr', name: 'ctr', "orderable": false},
                    {data: 'cost', name: 'cost', "orderable": false},
                    {data: 'conversions', name: 'conversions', "orderable": false}
                ]
		});	
		
		$('#google_ad_performance_network').DataTable({
		  "processing":true,
		  "serverSide":true,
		  async: false,
			"ajax":{
				'url': base_url + '/ajaxAdPerformanceNetwork',
				type:"GET",
				'data': function (data) {
					data.today = $('.today').val();
					data.account_id = $('.account_id').val();
					data.currency_code = $('.currency_code').val();

				}
			},
			columns: [
                    {data: 'publisher_by_network', "orderable": false},
                    {data: 'impressions', "orderable": false},
                    {data: 'clicks', "orderable": false},
                    {data: 'ctr', "orderable": false},
                    {data: 'cost', "orderable": false},
                    {data: 'conversions', "orderable": false}
                ]
		});	
		
		
		
		$('#google_ad_performance_device').DataTable({
		  "processing":true,
		  "serverSide":true,
		  async: false,
			"ajax":{
				'url': base_url + '/ajaxAdPerformanceDevice',
				type:"GET",
				'data': function (data) {
					data.today = $('.today').val();
					data.account_id = $('.account_id').val();
					data.currency_code = $('.currency_code').val();

				}
			},
			columns: [
                    {data: 'device', "orderable": false},
                    {data: 'impressions', "orderable": false},
                    {data: 'clicks', "orderable": false},
                    {data: 'ctr', "orderable": false},
                    {data: 'cost', "orderable": false},
                    {data: 'conversions', "orderable": false}
                ]
		});	
		
		
		$('#google_ad_click_types').DataTable({
		  "processing":true,
		  "serverSide":true,
		  async: false,
			"ajax":{
				'url': base_url + '/ajaxAdPerformanceClickTypes',
				type:"GET",
				'data': function (data) {
					data.today = $('.today').val();
					data.account_id = $('.account_id').val();
					data.currency_code = $('.currency_code').val();

				}
			},
			columns: [
                    {data: 'click_type', "orderable": false},
                    {data: 'impressions', "orderable": false},
                    {data: 'clicks', "orderable": false},
                    {data: 'ctr', "orderable": false},
                    {data: 'cost', "orderable": false},
                    {data: 'conversions', "orderable": false}
                ]
		});	
		
		
		$('#google_ad_slots').DataTable({
		  "processing":true,
		  "serverSide":true,
		  async: false,
			"ajax":{
				'url': base_url + '/ajaxAdPerformanceSlots',
				type:"GET",
				'data': function (data) {
					data.today = $('.today').val();
					data.account_id = $('.account_id').val();
					data.currency_code = $('.currency_code').val();

				}
			},
			columns: [
                    {data: 'ad_slot', "orderable": false},
                    {data: 'impressions', "orderable": false},
                    {data: 'clicks', "orderable": false},
                    {data: 'ctr', "orderable": false},
                    {data: 'cost', "orderable": false},
                    {data: 'conversions', "orderable": false}
                ]
		});	


	

        });
		
	$(document).ready(function(){
			var campaign_id = $('.campaign_id').val();
			var account_id = $('.account_id').val();
			setTimeout(function(){
			 // $.ajax({
    //             url:  BASE_URL + '/ajaxSaveInCsv',
				// data:{campaign_id:campaign_id,account_id:account_id},
    //             type: 'get',
    //             success: function (response) {
				// 	console.log(response);
    //             }
    //         });
			}, 5000);
	});






   
		  

</script>


<script>

var color = Chart.helpers.color;

var configSummary = {
	type: 'line',
	data:{
			labels: [],
			datasets: [
			{
				backgroundColor: window.chartColors.orange,
				borderColor: window.chartColors.orange,
				data: [],
				label: 'Clicks',
				fill: false,
				radius:5
			},
			{
				backgroundColor: window.chartColors.mauve,
				borderColor: window.chartColors.mauve,
				data: [],
				label: 'Clicks:Previous',
				fill: false,
				radius:5
			},
			{
				backgroundColor: window.chartColors.greyBlue,
				borderColor: window.chartColors.greyBlue,
				data: [],
				label: 'Conversions',
				fill: false,
				radius:5
			},
			{
				backgroundColor: window.chartColors.fuschiapink,
				borderColor: window.chartColors.fuschiapink,
				data: [],
				label: 'Conversions:Previous',
				fill: false,
				radius:5
			},
			{
				backgroundColor: window.chartColors.lightGreen,
				borderColor: window.chartColors.lightGreen,
				data: [],
				label: 'Impressions',
				fill: false,
				radius:5
			}, 
			{
				backgroundColor: window.chartColors.pink,
				borderColor: window.chartColors.pink,
				data: [],
				label: 'Impressions:Previous',
				fill: false,
				radius:5
			}	
			]
	},
	options:{
			maintainAspectRatio: false,
			spanGaps: false,
			elements: {
				line: {
					tension: 0.000001
				}
			},
			scales: {
				yAxes: [{
				//	stacked: true
				}],
				xAxes: [{
				    //type: 'time',
					// distribution: 'series',
					type: 'time',
					time: {
						displayFormats: {
                        day: 'MM/DD/Y'
                    }
					},
					offset: false,
					ticks: {
					  major: {
						enabled: true
					  },
					  // source: 'data',
					   autoSkip: true,
					   autoSkipPadding: 40,
					  // maxRotation: 0,
					  // sampleSize: 10
					}
				
			  }],
			},
			tooltips: {
			mode: 'index',
			intersect: false,
		}
		
	}
		};
			
var configPerformance = {
	type: 'line',
	data:{
			labels: [],
			datasets: [
			{
				backgroundColor: window.chartColors.orange,
				borderColor: window.chartColors.orange,
				data: [0],
				label: 'Cost',
				fill: false,
				radius:5,
				trendlineLinear:{style:  window.chartColors.orange, lineStyle: "dotted", width: 1}
			},
			
			{
				backgroundColor: window.chartColors.mauve,
				borderColor: window.chartColors.mauve,
				data: [0],
				label: 'Cost:Previous',
				fill: false,
				radius:5,
				trendlineLinear:{style:  window.chartColors.mauve, lineStyle: "dotted", width: 1}
				
			},
			{
				backgroundColor: window.chartColors.greyBlue,
				borderColor: window.chartColors.greyBlue,
				data: [0],
				label: 'Cost per Click',
				fill: false,
				radius:5,
				trendlineLinear:{style:  window.chartColors.greyBlue, lineStyle: "dotted", width: 1}
			},
			{
				backgroundColor: window.chartColors.fuschiapink,
				borderColor: window.chartColors.fuschiapink,
				data: [0],
				label: 'Cost per Click:Previous',
				fill: false,
				radius:5,
				trendlineLinear:{style:  window.chartColors.fuschiapink, lineStyle: "dotted", width: 1}
			},
			{
				backgroundColor: window.chartColors.lightGreen,
				borderColor: window.chartColors.lightGreen,
				data: [0],
				label: 'Cost per 1000 Impressions',
				fill: false,
				radius:5,
				trendlineLinear:{style:  window.chartColors.lightGreen, lineStyle: "dotted", width: 1}
			},
			{
				backgroundColor: window.chartColors.pink,
				borderColor: window.chartColors.pink,
				data: [0],
				label: 'Cost per 1000 Impressions:Previous',
				fill: false,
				radius:5,
				trendlineLinear:{style:  window.chartColors.pink, lineStyle: "dotted", width: 1}
			},
			{
				backgroundColor: window.chartColors.lightPurple,
				borderColor: window.chartColors.lightPurple,
				data: [0],
				label: 'Revenue Per Click',
				fill: false,
				radius:5,
				trendlineLinear:{style:  window.chartColors.lightPurple, lineStyle: "dotted", width: 1}
			},
			{
				backgroundColor: window.chartColors.darkBlue,
				borderColor: window.chartColors.darkBlue,
				data: [0],
				label: 'Revenue Per Click:Previous',
				fill: false,
				radius:5,
				trendlineLinear:{style:  window.chartColors.darkBlue, lineStyle: "dotted", width: 1}
			},
			{
				backgroundColor: window.chartColors.bottleGreen,
				borderColor: window.chartColors.bottleGreen,
				data: [0],
				label: 'Total Value',
				fill: false,
				radius:5,
				trendlineLinear:{style:  window.chartColors.bottleGreen, lineStyle: "dotted", width: 1}
			},
			{
				backgroundColor: window.chartColors.pearGreen,
				borderColor: window.chartColors.pearGreen,
				data: [0],
				label: 'Total Value:Previous',
				fill: false,
				radius:5,
				trendlineLinear:{style:  window.chartColors.pearGreen, lineStyle: "dotted", width: 1}
			}
			]
	},
	options:{
			maintainAspectRatio: false,
			spanGaps: false,
			elements: {
				line: {
					tension: 0.000001
				}
			},
			scales: {
				yAxes: [{
				//	stacked: true
				}],
				xAxes: [{
					type: 'time',
					time: {
						displayFormats: {
                        day: 'MM/DD/Y'
                    }
					},
					offset: false,
					ticks: {
					  major: {
						enabled: true
					  },
					   autoSkip: true,
					   autoSkipPadding: 40
					}
				
			  }],
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			}
		
		}
	};

window.onload = function() {
	var ctx = document.getElementById('canvas').getContext('2d');
	window.myLine = new Chart(ctx, configSummary);
	
	
	var ctxPerformance = document.getElementById('canvasperformance').getContext('2d');
	window.myLinePerformance = new Chart(ctxPerformance, configPerformance);
};		
		

$(document).ready(function(){
	
		var account_id = $('.account_id').val();
		  $.ajax({
				type: "GET",
				url: $('.base_url').val()+"/ppc_date_range_data",
				data: {account_id:account_id},
				dataType: 'json',
				success: function(result){
					//console.log(result);
					configSummaryData(result);
					configPerformanceData(result);
				}
			});
			
			
			$.ajax({
				type: "GET",
				url: $('.base_url').val()+"/summary_statistics",
				data: {account_id},
				dataType: 'json',
				beforeSend: function() {
				  $(".summaryloader").show();
				  $("#myOverlay").show();
				},
				success: function(response){	
					$(".summaryloader").hide();	
					$("#myOverlay").hide();	
		
					$('.dateSection').html(response['date']);
					$('#impressions').html(response['impressions']);
					$('#cost').html(response['cost']);
					$('#clicks').html(response['clicks']);
					$('#average_cpc').html(response['average_cpc']);
					$('#ctr').html(response['ctr']);
					$('#conversions').html(response['conversions']);
					$('#conversion_rate').html(response['conversion_rate']);
					$('#cost_per_conversion').html(response['cost_per_conversion']);
				}
				
			});
 });
	  
function configSummaryData(result) {
	  if (window.myLine) {
        window.myLine.destroy();
      }
	  
	   
	var ctx = document.getElementById('canvas').getContext('2d');
	window.myLine = new Chart(ctx, configSummary);

	configSummary.data.labels =  result['date_range'];
	configSummary.data.datasets[0].data = result['clicks'];
	configSummary.data.datasets[1].data = result['clicks_previous'];	
	configSummary.data.datasets[2].data = result['conversions'];	
	configSummary.data.datasets[3].data = result['conversions_previous'];
	configSummary.data.datasets[4].data = result['impressions'];
	configSummary.data.datasets[5].data = result['impressions_previous'];

	window.myLine.update();
	
}  

function configPerformanceData(result) {
	if (window.myLinePerformance) {
        window.myLinePerformance.destroy();
      }
	var ctxPerformance = document.getElementById('canvasperformance').getContext('2d');
	window.myLinePerformance = new Chart(ctxPerformance, configPerformance);
				
	configPerformance.data.labels =  result['date_range'];
	
	configPerformance.data.datasets[0].data = result['cost'];
	configPerformance.data.datasets[1].data = result['cost_previous'];
	configPerformance.data.datasets[2].data = result['cpc'];
	configPerformance.data.datasets[3].data = result['cpc_previous'];
	configPerformance.data.datasets[4].data = result['averagecpm'];
	configPerformance.data.datasets[5].data = result['averagecpm_previous'];
	configPerformance.data.datasets[6].data = result['revenue_per_click'];
	configPerformance.data.datasets[7].data = result['revenue_per_click_previous'];
	configPerformance.data.datasets[8].data = result['total_value'];
	configPerformance.data.datasets[9].data = result['total_value_previous'];

	window.myLinePerformance.update();
	
} 

$("#customSwitches").on('change', function() {
    if ($(this).is(':checked')) {
     $('.compareSection').css('display','block');   
    }
    else {
      $('.compareSection').css('display','none');
    }
});
	  
$(function() {
  var account_id = $('.account_id').val();
   $(".startDate").datepicker({
	onSelect: function(dateStr) 
            {         
                $(".endDate").val(dateStr);
                $(".endDate").datepicker("option",{ minDate: new Date(dateStr)});

				$(".CmpendDate").val(dateStr);
                $(".CmpendDate").datepicker("option",{ maxDate: new Date(dateStr)});
            }
	});
  $(".endDate").datepicker({
	onSelect: function(dateStr) 
            {         
// console.log($(".startDate").val());
				var days = date_diff_indays( $(".startDate").val(),  $(".endDate").val());
				var date = getdate($(".startDate").val(),days);

				$(".CmpstartDate").val(date);
				$(".CmpstartDate").datepicker("option",{ maxDate: new Date(date)});
            }
	});





 
  $(".CmpstartDate").datepicker();
  $(".CmpendDate").datepicker();

  $('#submitPpcDateRange').on('click',function(){
  // var start_date =  $(".startDate").val();
  // var end_date =  $(".endDate").val(); 
  // var cmp_start_date =  $(".CmpstartDate").val();
  // var cmp_end_date =  $(".CmpendDate").val(); 

  var start_date =  $(".sd").val();
  var end_date =  $(".ed").val(); 
  var cmp_start_date =  $(".csd").val();
  var cmp_end_date =  $(".ced").val(); 

  
  if(start_date!='' && end_date!=''){
	 
	 if($('#customSwitches').prop("checked") == true){
	   var compare = true;
	}
	
	  $.ajax({
			type: "GET",
			url: $('.base_url').val()+"/ppc_date_range_data",
			data: {start_date,end_date,account_id,compare,cmp_start_date,cmp_end_date},
			dataType: 'json',
			success: function (result) {
			  configSummaryData(result);
			  configPerformanceData(result);
			}
		});
		
		
	  $.ajax({
			type: "GET",
			url: $('.base_url').val()+"/summary_stats",
			data: {start_date,end_date,account_id,compare,cmp_start_date,cmp_end_date},
			dataType: 'json',
			beforeSend: function() {
				  $(".summaryloader").show();
				  $("#myOverlay").show();
				},
			success: function(response){
				$(".summaryloader").hide();	
				$("#myOverlay").hide();

				if(response['status'] == false){
					alert(response['message']);
					return false;
				}

				var prev_imp = prev_clicks = previous_cost  =previous_conversions = previous_cost_per_conversion = previous_ctr = previous_conversion_rate = previous_average_cpc = compare_date = ''; 
				if(response['compare']==true){
					
					if(response['previous_impressions']!=""){
						var prev_imp =  ' vs ' + response['previous_impressions'];
					}
					if(response['previous_clicks']!=""){
						var prev_clicks =  ' vs ' + response['previous_clicks'];
					}
					if(response['previous_cost']!=""){
						var previous_cost =  ' vs ' + response['previous_cost'];
					}
					if(response['previous_conversions']!=""){
						var previous_conversions =  ' vs ' + response['previous_conversions'];
					}
					if(response['previous_cost_per_conversion']!=""){
						var previous_cost_per_conversion =  ' vs ' + response['previous_cost_per_conversion'];
					}
					if(response['previous_ctr']!=""){
						var previous_ctr =  ' vs ' + response['previous_ctr'];
					}
					if(response['previous_conversion_rate']!=""){
						var previous_conversion_rate =  ' vs ' + response['previous_conversion_rate'];
					}
					if(response['previous_average_cpc']!=""){
						var previous_average_cpc =  ' vs ' + response['previous_average_cpc'];
					}
					
					if(response['compare_date']!=""){
					var compare_date =  ' (compared to  ' + response['compare_date'] + ' )';
					}
				}
				
				$('.dateSection').html(response['date']+compare_date);
				$('#impressions').html(response['impressions']+ prev_imp);
				$('#cost').html(response['cost']+previous_cost);
				$('#clicks').html(response['clicks']+prev_clicks);
				$('#average_cpc').html(response['average_cpc']+previous_average_cpc);
				$('#ctr').html(response['ctr']+previous_ctr);
				$('#conversions').html(response['conversions']+previous_conversions);
				$('#conversion_rate').html(response['conversion_rate']+previous_conversion_rate);
				$('#cost_per_conversion').html(response['cost_per_conversion']+previous_cost_per_conversion);
			}
			
		});
	  
	  
  }else{
	  alert('Please select dates first !');
  }
  });
});	

function date_diff_indays(date1, date2) {
var x = new Date(date1);
var y = new Date(date2);
return diffInDays = Math.floor((x - y) / (1000 * 60 * 60 * 24)); 
}

function getdate(ndate, days) {

    var date = new Date(ndate);
    var newdate = new Date(date);

    newdate.setDate(newdate.getDate() + days);
    
    var dd = newdate.getDate();
    var mm = newdate.getMonth() + 1;
    var y = newdate.getFullYear();

    var someFormattedDate = mm + '/' + dd + '/' + y;
	return someFormattedDate;
    
}

$(function() {
	  $('input[name="dateranges"]').daterangepicker({
		opens: 'left'
	  }, function(start, end, label) {
			var new_start = start.format('MM/DD/YYYY');
			var new_end = end.format('MM/DD/YYYY');
			var days = date_diff_indays(new_start,new_end);

			var date = getdate(new_start,days);

			$(".sd").val(new_start);
			$(".ed").val(new_end);
			$(".csd").val(date);
			$(".ced").val(start.format('MM/DD/YYYY'));

	$('input[name="dateranges1"]').daterangepicker({
		opens: 'left',
		startDate: date,
        endDate: start.format('MM/DD/YYYY'),
          maxDate: start.format('MM/DD/YYYY'),
	  }, function(start, end, label) {
		  var start_date = start.format('MM/DD/YYYY');
		  var end_date = end.format('MM/DD/YYYY');
			$(".csd").val(start_date);
			$(".ced").val(end_date);
	  });
	});	





	
	  });


   
		  
		</script>