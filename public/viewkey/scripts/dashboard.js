	var BASE_URL = $('.base_url').val();
	var color = Chart.helpers.color;
	var configTrafficGrowth = {
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

	var configSearchConsole = {
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

	

	var KeywordChartConfig = {
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

	var Keywordconfig = {
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
					}
					,
					{
						backgroundColor: window.chartColors.fuschiapink,
						borderColor: window.chartColors.fuschiapink,
						data: [],
						label: 'Conversions:Previous',
						fill: false,
						radius:5
					}
					,
					{
						backgroundColor: window.chartColors.lightGreen,
						borderColor: window.chartColors.lightGreen,
						data: [],
						label: 'Impressions',
						fill: false,
						radius:5
					}
					, 
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

	$(document).ready(function(){
			if(window.location.hash != ''){
				var dashboard_active = window.location.hash;
			}else{
				var dashboard_active = $('.newDashboard li a.active').attr('href');
			}
			
			// console.log('dashboard_active'+dashboard_active);

			if((dashboard_active != null) && dashboard_active != undefined){
				$("html, body").offset().top;
				if (dashboard_active.match('#')) {
					$('li.nav-item .active').removeClass('active');
					$('a[href="' + dashboard_active + '"]').addClass('active');


					$('.mainDashboardSection').removeClass("active");
					$("#"+dashboard_active.split('#')[1]).addClass('in show active');

					$('li.seoSidebar .mm-active').removeClass('mm-active');
				} 

				if(dashboard_active == '#SEO'){
					$('#SEO').load('/view/seo_content/'+$('.campaignID').val());
					setTimeout(function(){								
						ajaxOrganicKeywordRanking($('.campaignID').val());
						updateTimeAgo();
						ajaxReferringDomains($('.campaignID').val());
						ajaxGoogleAnalyticsGoal($('.campaignID').val(),$('.user_id').val());
						ajaxGoogleTrafficGrowth($('.campaignID').val());
						ajaxGoogleSearchConsole($('.campaignID').val());
						keywordsMetricBarChart($('.campaignID').val());
						keywordsMetricPieChart($('.campaignID').val());
						seo_page_scripts($('.campaignID').val());
						getAccountActivity($('.campaignID').val());		
					}, 2000);
				}

				if(dashboard_active == '#PPC'){
					 $('#PPC').load('/view/ppc_content/'+$('.campaignID').val());
					setTimeout(function(){
						chart();
						daterange();
						custom_switches();
						ppc_page_scripts();
						ppc_datatables();
					}, 2000);
				}
				
			}
	});

	function chart(){
		var ctx = document.getElementById('canvasppcsummary').getContext('2d');
		window.myLine = new Chart(ctx, configSummary);

		var ctxPerformance = document.getElementById('canvasperformance').getContext('2d');
		window.myLinePerformance = new Chart(ctxPerformance, configPerformance);
	}

	$('.nav-link').on('click',function(){

		var headerHeight = $('.app-header').outerHeight(),
	        appInnerHeight = $('.app-inner-layout__header-boxed').outerHeight(),
	        newDashboardHeight = $('ul.newDashboard').outerHeight(),
	        finalHeight = headerHeight + appInnerHeight + newDashboardHeight;


	    if ($(this).length) {
	    	
		        $('html,body').stop().animate({
		            scrollTop: $($(this).attr('href')).offset().top - finalHeight - 70
		        });
	
		}
			window.location.hash = $(this).attr('href');
			var url = document.location.toString(); 

			var href = $(this).attr('href');
			$("html, body").offset().top; 

			if (url.match('#')) {
				$('li.nav-item .active').removeClass('active');
				$(this).addClass('active');

				$('.mainDashboardSection').removeClass("active");
				$("#"+url.split('#')[1]).addClass('in show active');

			
				} 

			if(href == '#SEO'){
				if($('#SEO').find('.tabs-animation').length == 0){
					$('#SEO').load('/view/seo_content/'+$('.campaignID').val());
					setTimeout(function(){					
						ajaxOrganicKeywordRanking($('.campaignID').val());
						updateTimeAgo();
						ajaxReferringDomains($('.campaignID').val());
						ajaxGoogleAnalyticsGoal($('.campaignID').val(),$('.user_id').val());
						ajaxGoogleTrafficGrowth($('.campaignID').val());
						ajaxGoogleSearchConsole($('.campaignID').val());
						keywordsMetricBarChart($('.campaignID').val());
						keywordsMetricPieChart($('.campaignID').val());
						seo_page_scripts($('.campaignID').val());
						getAccountActivity($('.campaignID').val());		
					}, 2000);
				}else{
					$('.mainDashboardSection').removeClass('active');
					$('#SEO').addClass('in show active');
				}

					
			}

			if(href == '#PPC'){
				if($('#PPC').find('.tabs-animation').length == 0){
					$('#PPC').load('/view/ppc_content/'+$('.campaignID').val());
					setTimeout(function(){
						chart();
						daterange();
						custom_switches();
						ppc_page_scripts();
						ppc_datatables();     
					}, 2000); 
						
				}else{
					$('.mainDashboardSection').removeClass('active');
					$('#PPC').addClass('in show active');
				}
					
			}

		});


	






	$(document).on('click','.load-more',function(){
			$('.account_activity').show();
			$('.load-more').hide();
			var limit = $(this).attr('data-value');
		    var requestId = $('.campaignID').val();
		    var lastDate =  $('.account-timeline-date').last().html();
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					}
				});

			    $.ajax({
		           type: "POST",
		            url: BASE_URL+"/view/get_account_activity",
		            data: {request_id:requestId,limit:limit,lastDate:lastDate},
		            dataType: 'json',
		            success: function(result){

		                $('.account-timeline').mCustomScrollbar("destroy");
		                $('#activity-timeline').append(result['html']);
		                $('.account-timeline').mCustomScrollbar();
		                $('.load-more').attr('data-value',result['limit']);
		                $('.account_activity').hide();
		                $('.load-more').show();

		            }
		     	});
	});


	function getAccountActivity(campaignId){
		$('#accountActivityLoader').show();
			var lastDate = '';
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$.ajax({
					type: "POST",
					url: BASE_URL+"/view/get_account_activity",
					data: {request_id:campaignId,lastDate:lastDate},
					dataType: 'json',
					success: function(result){
						$('#accountActivityLoader').hide();
						if(result != ''){
							$('#activity-timeline').html(result['html']);
	                		$('.load-more').attr('data-value',result['limit']);
						}
					}		
			});
	}

	function ajaxOrganicKeywordRanking(campaignId){
			$.ajax({
					type:"GET",
					url: BASE_URL+"/view/ajax_organicKeywordRanking",
					data:{campaignId: campaignId},
					dataType:'json',
					success:function(result){
						var position = document.getElementsByClassName("googleRankPosition");
						$('.GoogleRanking').text(result['totalCount']);
						$(position).text(result['organic_keywords']+'%');
						
						if(result['organic_keywords'] > 0 ){
							$(position).parent().find('i').addClass("fa-angle-up");	
							$(position).parent().addClass("text-success");
						}else{
							$(position).parent().find('i').addClass("fa-angle-down");
							$(position).parent().addClass("text-danger");
						}
						
					}
				});
	}

	function ajaxGoogleTrafficGrowth(campaignId){
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
					
						$('#traffic_loader').hide();
						highChartMapload(result);

	                    $('#analatic_add').css('display','none');
					 }
					}
				});
	}

	function ajaxGoogleSearchConsole(campaignId){
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
	    					if(result['page'] !=''){
	    							$('.country_table').html(result['country']);
	    							$('.device_table').html(result['device']);
	    							$('.pages_table').html(result['page']);
	    							$('.query_table').html(result['query']);
	    							
	    							consoleChart(result['clicks'],result['impressions']);
	                                $('#console_add').css('display','none');
	    					}		
	                    }
	                    $('#console_loader').hide();			
					}
				});
	}

	function keywordsMetricBarChart(campaignId){
			$.ajax({
				type: "GET",
				url: BASE_URL+"/view/keywordsMetricBarChart",
				data: {campaignId:campaignId},
				dataType: 'json',
				success: function(result){

					if(window.myLineKeyword){
						window.myLineKeyword.destroy();
					}

					var ctxs = document.getElementById('keywordsCanvas').getContext('2d');
					window.myLineKeyword = new Chart(ctxs, Keywordconfig);

					Keywordconfig.data.labels =  JSON.parse(result['names']);
					Keywordconfig.data.datasets[0].data = JSON.parse(result['values']);
					window.myLineKeyword.update();
				}
			});
	}

	function keywordsMetricPieChart(campaignId){
			$.ajax({
				type: "GET",
				url: BASE_URL+"/view/keywordsMetricPieChart",
				data: {campaignId:campaignId},
				dataType: 'json',
				success: function(result){

					if(window.myLinePie){
						window.myLinePie.destroy();
					}

					var ctxPie = document.getElementById('keywordsCanvasChartArea').getContext('2d');
					window.myLinePie = new Chart(ctxPie, KeywordChartConfig);

					KeywordChartConfig.data.labels =  JSON.parse(result['names']);
					KeywordChartConfig.data.datasets[0].data = JSON.parse(result['values']);
					window.myLinePie.update();
				}
			});
	}

	function seo_page_scripts(campaignId){

			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});

			$('#google_organic_keywords').DataTable({
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

			$('#LiveKeywordTrackingTable').DataTable({
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

			$('#backlink_profile').DataTable({
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

			$('#googleAnalyticsGoalCompletion').DataTable({
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

	function ajaxGoogleAnalyticsGoal(campaignId,user_id){
		$.ajax({
				type:"GET",
				url: BASE_URL+"/view/ajax_googleAnalyticsGoals",
				data:{campaignId: campaignId,user_id:user_id},
				dataType:'json',
				success:function(result){
					if(result != ''){
						$('.analyticsTotalGoal').text(result['total']);
						$('.analyticsgoalResult').text(result['goal_result']);
						
						var goal_result = document.getElementsByClassName("analyticsgoalResult");
						if(result['goal_result'] > 0 ){
							$(goal_result).parent().find('i').addClass("fa-angle-up");	
							$(goal_result).parent().addClass("text-success");
						}else{
							$(goal_result).parent().find('i').addClass("fa-angle-down");
							$(goal_result).parent().addClass("text-danger");
						}
					}					
				}
			});
	}

	function ajaxReferringDomains(campaignId){
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
			$.ajax({
					type:"GET",
					url:BASE_URL+"/view/ajax_referring_domains",
					data:{campaign_id: campaignId},
					dataType:'json',
					success:function(result){
						if(result != ''){
							$('.backlinks_total').text(result['total']);
							$('.backlinks_avg').text(result['avg']);
							
							var avg = document.getElementsByClassName("backlinks_avg");
							if(result['avg'] > 0 ){
								$(avg).parent().find('i').addClass("fa-angle-up");	
								$(avg).parent().addClass("text-success");
							}else{
								$(avg).parent().find('i').addClass("fa-angle-down");
								$(avg).parent().addClass("text-danger");
							}
						}
					}
				});
	}

	function  drawChartGraph(requestId, days) {
	        var keywordId = localStorage.getItem("keywordId");		
	       drawChart(keywordId, requestId, days)

	}

	function updateTimeAgo(){
	    $.ajax({
	        type: 'GET',
	        url:  BASE_URL + '/view/ajaxUpdateTimeAgo',
	        data: {request_id:$('.campaignID').val()},
	        dataType: 'json',
	        success: function(result) {
	            if (result['status'] == '1') {
	                $('#yeskws_txt').html(result['time']);
	                $("#yeskws_txt").show();
	            }
	        }
	    });
	}

	$(document).on('click','.graph_range',function(){
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
						highChartMap(result);
						$('#traffic_loader').hide();					
						$('.graph-loader.organic_traffic').css('display','none');
					}
				});
	});

	$(document).on('click','.searchConsole',function(){
			var value = $(this).attr('data-value');
			var module = $(this).attr('data-module');
			var campaignId = $('.campaignID').val();
			
			$('.sc_section').removeClass('active');
			$(this).addClass('active');
			$('.graph-loader.searchConsole').css('display','block');
			$('#console_loader').show();
			
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
						$('.country_table').html(result['country']);
						$('.device_table').html(result['device']);
						$('.pages_table').html(result['page']);
						$('.query_table').html(result['query']);
						

						consoleChart(result['clicks'],result['impressions']);
						$('.graph-loader.searchConsole').css('display','none');
	                    $('#console_add').css('display','none');
					}
				
					
				}
			});			
	});

	


	$("#LiveKeywordTrackingTable").on("click", ".chart-icon", function(){
		// alert('here');
	    var keyword_id =  $(this).data('id');
	    var request_id =  $(this).data('index');
	    var duration =  '-30 day';
	    $('#liveKeywordTrackingChart').removeClass('hide');
	    $('html, body').animate({
	        scrollTop: $("#keywordchartConatiner").offset().top
	    }, 100);
		
	    localStorage.setItem("keywordId", keyword_id);
	    drawChart(keyword_id,request_id, duration);
	});

	$(document).on('click','#close-graph',function(){
		$('#liveKeywordTrackingChart').addClass('hide');
	});

	function drawChart(keyword_id, request_id, duration ) {
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
	            createAnalyticsCharts(series, result['month'], result['keyword']);
	        }
	    });
	}

	function createAnalyticsCharts(seriesData, month, keyword) {
		Highcharts.chart('keywordchartConatiner', {
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

	function highChartMapload(result) {	  
		if (window.myLineTraffic) {
			window.myLineTraffic.destroy();
		}
		var ctxTrafficGrowth = document.getElementById('canvas-traffic-growth').getContext('2d');
		window.myLineTraffic = new Chart(ctxTrafficGrowth, configTrafficGrowth);

		configTrafficGrowth.data.labels =  result['from_datelabel'];
		configTrafficGrowth.data.datasets[0].label = 'Current Period: '+result['current_period'];
		configTrafficGrowth.data.datasets[0].data = result['count_session'];

		if(result['compare_status'] == '1'){
			var newDataset = {
			  label: 'Previous Period: '+result['previous_period'],
			  borderColor: color(window.chartColors.red).alpha(1.0).rgbString(),
			  backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
			  data: result['combine_session'],
			};
			configTrafficGrowth.data.datasets.push(newDataset);

		}
		else{
			configTrafficGrowth.data.datasets.splice(1, 1);

		}
		window.myLineTraffic.update();

	  }

	function highChartMap(result){
			window.myLineTraffic.data.labels =  result['from_datelabel'];
			window.myLineTraffic.data.datasets[0].label = 'Current Period: '+result['current_period'];
			window.myLineTraffic.data.datasets[0].data = result['count_session'];

			 if(result['compare_status'] == '1'){

				window.myLineTraffic.data.datasets[1].label = 'Previous Period: '+result['previous_period'];
				window.myLineTraffic.data.datasets[1].data = result['combine_session'];
			 }

		   window.myLineTraffic.update();
	}

	function consoleChart(clicks,impressions){
	        if(window.myLineSearchConsole){
	            window.myLineSearchConsole.destroy();
	        }
	        
	         var ctxSearchConsole = document.getElementById('canvas-search-console').getContext('2d');
	         window.myLineSearchConsole = new Chart(ctxSearchConsole, configSearchConsole);
	             
	        configSearchConsole.data.datasets[0].data = clicks;
	        configSearchConsole.data.datasets[1].data = impressions;
	        window.myLineSearchConsole.update();
	}

	function openCity(evt, cityName) {
	   
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

	/*ppc script*/
	

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

	function daterange(){
		if($('.csd').val()!=''){
			$('.firstdaterange').daterangepicker({
				startDate:getdate($('.sd').val(),0),
				endDate:getdate($('.ed').val(),0)
			});


			$('.seconddaterange').daterangepicker({
				startDate:getdate($('.csd').val(),0),
				endDate:getdate($('.ced').val(),0)
			});
		}
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

	}

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

	function custom_switches(){
		$('#customSwitchesppc').on('change', function() {
			if ($(this).is(':checked')) {
				$(this).prop('checked', true);
				$('.compareSection').css('display','block');   
			}
			else {
				$(this).prop('checked', false); 
				$('.compareSection').css('display','none');
			}
		});
	}

	$(function() {
		$(document).on('click','#submitPpcDateRange',function(){
			var account_id = $('.account_id').val();	
			var start_date =  $(".sd").val();
			var end_date =  $(".ed").val(); 
			var cmp_start_date =  $(".csd").val();
			var cmp_end_date =  $(".ced").val(); 


			if(start_date!='' && end_date!=''){

				if($('#customSwitchesppc').prop("checked") == true){
					var compare = true;
				}

				$.ajax({
					type: "GET",
					url: BASE_URL+"/view/ppc_date_range_data",
					data: {start_date,end_date,account_id,compare,cmp_start_date,cmp_end_date},
					dataType: 'json',
					success: function (result) {
						configSummaryData(result);
						configPerformanceData(result);
					}
				});


				$.ajax({
					type: "GET",
					url: BASE_URL+"/view/summary_stats",
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
							Command: toastr["error"](result['message']);
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


			} else{
				Command: toastr["error"]('Please select dates first !');
			}
		});
	});	

	function ppc_page_scripts(){
		$(document).ready(function(){
			var account_id = $('.account_id').val();
			var campaign_id = $('.campaignID').val();
			$.ajax({
				type: "GET",
				url: BASE_URL+"/view/ppc_date_range_data",
				data: {account_id:account_id,campaign_id:campaign_id},
				dataType: 'json',
				success: function(result){
					configSummaryData(result);
					configPerformanceData(result);
					}
			});


			$.ajax({
				type: "GET",
				url: BASE_URL +"/view/summary_statistics",
				data: {account_id,campaign_id},
				dataType: 'json',
				beforeSend: function() {
					$(".summaryloader").show();
					$("#myOverlay").show();
				},
				success: function(response){    
					$(".summaryloader").hide(); 
					$("#myOverlay").hide(); 

					var prev_imp = prev_clicks = previous_cost  =previous_conversions = previous_cost_per_conversion = previous_ctr = previous_conversion_rate = previous_average_cpc = compare_date = ''; 
					

						if(response['previous_impressions']!="" && response['previous_impressions']!="0"){
							var prev_imp =  ' vs ' + response['previous_impressions'];
						}
						if(response['previous_clicks']!="" && response['previous_clicks']!="0"){
							var prev_clicks =  ' vs ' + response['previous_clicks'];
						}
						if(response['previous_cost']!="" && response['previous_cost']!="0"){
							var previous_cost =  ' vs ' + response['previous_cost'];
						}
						if(response['previous_conversions']!="" && response['previous_conversions']!="0"){
							var previous_conversions =  ' vs ' + response['previous_conversions'];
						}
						if(response['previous_cost_per_conversion']!="" && response['previous_cost_per_conversion']!="0"){
							var previous_cost_per_conversion =  ' vs ' + response['previous_cost_per_conversion'];
						}
						if(response['previous_ctr']!="" && response['previous_ctr']!="0"){
							var previous_ctr =  ' vs ' + response['previous_ctr'];
						}
						if(response['previous_conversion_rate']!="" && response['previous_conversion_rate']!="0"){
							var previous_conversion_rate =  ' vs ' + response['previous_conversion_rate'];
						}
						if(response['previous_average_cpc']!="" && response['previous_average_cpc']!="0"){
							var previous_average_cpc =  ' vs ' + response['previous_average_cpc'];
						}

						if(response['compare_date']!="" && response['compare_date']!="0"){
							var compare_date =  ' (compared to  ' + response['compare_date'] + ' )';
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
		});
	}

	function configSummaryData(result) {
			// if (window.myLine) {
			// 	window.myLine.destroy();
			// }


		

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

	function ppc_datatables(){
		$('#google_ads_campaigns').DataTable({
			processing: true,
			serverSide: true,
			async: false,
			"deferRender": true,
			'ajax': {
				'url': BASE_URL + '/view/ajaxAdsCampaign',
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
				'url': BASE_URL + '/view/ajaxAdsKeywords',
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
				'url': BASE_URL + '/view/ajaxAdsData',
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
				'url': BASE_URL + '/view/ajaxAdGroupsData',
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
				'url': BASE_URL + '/view/ajaxAdPerformanceNetwork',
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
				'url': BASE_URL + '/view/ajaxAdPerformanceDevice',
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
				'url': BASE_URL + '/view/ajaxAdPerformanceClickTypes',
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
				'url': BASE_URL + '/view/ajaxAdPerformanceSlots',
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

	}