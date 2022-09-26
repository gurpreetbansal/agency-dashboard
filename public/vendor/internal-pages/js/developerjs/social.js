//Graph Implementation start 
$(document).ready(function(){
	var campaignId = $('.campaignID').val();
	localStorage.removeItem('facebook_'+campaignId);
	$(document).on('click','.social_module',function(e){
		e.preventDefault();
		var socialselected = $("a",this).attr('href');
		if($('.view-ajax-tabs').find('.viewkeypdf').length == 1){
			var urlpdf =  $('.viewkeypdf').attr('href');
			var pdfClass =  'viewkeypdf';
		}else{
			var urlpdf =  $('.generate-pdf').attr('href');
			var pdfClass =  'generate-pdf';
		}

		var finalurlpdf = urlpdf.substring(0, urlpdf.lastIndexOf('/'));
		
		if(socialselected == '#facebook'){
			$(".sideDashboardView,.social_module").removeClass('uk-active active');
			$(this).addClass('uk-active active');
			if($('#facebook').find('.social-body').length == 0){
				$('#facebook').load('/facebook-view/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
					if(statusTxt == "success")
						$('#facebook').show();
						$('.facebook-view-common').show(); //use for view key
						$('.facebook_view').addClass('uk-active active'); //use for view key

						// $('.social_module,.common_class').removeClass('uk-active');
						// $('#facebook').addClass('uk-active');
						$('#nav_facebook').addClass('uk-active');
						$('#overview').hide();
						
						if($(this).hasClass('inactive') != true){
							var checkQueryhit = localStorage.getItem("facebook_"+campaignId);
							if(checkQueryhit != 1){
								facebookScripts(campaignId);
								// $('#nav_facebook').addClass('uk-active');
								stopScroller();
							}
							localStorage.setItem("facebook_"+campaignId, "1");
							$('.overviewFilter').hide();
							$('.facebookPageFilter,#ShareKey').show();
							$('.'+pdfClass).show();
							$('.'+pdfClass).attr('href',finalurlpdf+'/facebook');
						}

					if(statusTxt == "error")
						console.log("Error: " + xhr.status + ": " + xhr.statusText);
				});
			}else{
				$('#facebook').show();
				$('#overview').hide();
				$('.facebook-view-common').show(); //use for view key
				$('.facebook_view').addClass('uk-active active'); //use for view key
				$('#nav_facebook').addClass('uk-active');
				$('.social-view-data').show();
				$('.overviewFilter').hide();
				$('.facebookPageFilter').show();
				$('.'+pdfClass).show();
				$('.'+pdfClass).attr('href',finalurlpdf+'/facebook');
				$('#facebookviewlikes,#facebookviewreach,#facebookviewpostreviews,#facebookviewreviews').hide();
				$(function() {
					$(window).scroll(sticksSocial_nav);
					sticksSocial_nav();
				});
				
			}

			
		}

		if(socialselected == '#overview'){
			$(".sideDashboardView,.social_module").removeClass('uk-active active');
			$(this).addClass('uk-active active');
			$('.overview_view').addClass('uk-active active'); //use for view key
			$('#facebook').hide();
			$('#overview').show();
			$('#nav_overview').addClass('uk-active active');
			$('.facebook-view-common').hide(); //use for view key
			$('.overviewFilter').show();
			$('.facebookPageFilter').hide();
			$('.'+pdfClass).hide();
			$('.social-view-data').show();
			$('#facebookviewlikes,#facebookviewreach,#facebookviewpostreviews,#facebookviewreviews').hide();
		}
	});
});

function facebookScripts(campaignId){
	socialDateRangeFilters(campaignId);
	getFbLikes(campaignId);
	getFbOrganicPaidLikes(campaignId);
	getFbGenderLikes(campaignId);
	getFbCountryLikes(campaignId);
	getFbCityLikes(campaignId);
	getFbLanguageLikes(campaignId);
	getFbReach(campaignId);
	getFbOrganicPaidReach(campaignId);
	getFbOrganicPaidVideoReach(campaignId);
	getFbGenderReach(campaignId);
	getFbCountryReach(campaignId);
	getFbCityReach(campaignId);
	getFbLanguageReach(campaignId);
	getFbPosts(campaignId);
	getFbReviews(campaignId);
	GoogleUpdateTimeAgo('facebook');
}

function sendFbAjax(request,campaignData){
		var pdfStatus = 0;
		if($('.pdf_status').val() == 1){
			var pdfStatus = 1;
		}

		$.ajax({
			type: 'GET',
			url:  BASE_URL + '/'+request,
			data: {id:$('.campaignID').val(),campaignData,pdfStatus},
			dataType: 'json',
			success: function(response) {
				
				if(request == 'social_date_range_filters'){
					if(campaignData.selected == '#facebookviewlikes'){
						$('.facebookLikePageFilter').html(response);
					}else if(campaignData.selected == '#facebookviewreach'){
						$('.facebookReachPageFilter').html(response);
					}else{
						$('.facebookPageFilter').html(response);
					}

					
					if($('.view-ajax-tabs').find('.viewkeypdf').length == 1){
						$('#refreshFacebookData').hide();
					}

				}

				if(request == 'getfblikes'){
					if(response.status != 0){
						if($('#Social').find('.main-data-view').hasClass('facebookviewlikes')){ 
						//use for view key
							$('.likes-count-view').html(response.pagelikes);
							if(response.comparison == 1){
								if(response.pagelikespercent != "0%"){
									$('.likes-percent-view').html(response.pagelikespercent);
									$('.likepercenticon-view').removeClass('ion-arrow-up-a green ion-arrow-down-a red');
									$('.likepercenticon-view').addClass(response.fbLikeclass);
								}else{
									$('.likes-percent-view').html(response.pagelikespercent);
									$('.likepercenticon-view').removeClass('ion-arrow-up-a green ion-arrow-down-a red');
								}
							}else{
								$('.likes-percent-view').html('');
								$('.likepercenticon-view').removeClass('ion-arrow-up-a green ion-arrow-down-a red');
							}
							$('.social_range_view').html(response.social_range);
						}else{
							$('.likes-count').html(response.pagelikes);
							if(response.comparison == 1){
								if(response.pagelikespercent != "0%"){
									$('.likes-percent').html(response.pagelikespercent);
									$('.likepercenticon').removeClass('ion-arrow-up-a green ion-arrow-down-a red');
									$('.likepercenticon').addClass(response.fbLikeclass);
								}else{
									$('.likes-percent').html(response.pagelikespercent);
									$('.likepercenticon').removeClass('ion-arrow-up-a green ion-arrow-down-a red');
								}
							}else{
								$('.likes-percent').html('');
								$('.likepercenticon').removeClass('ion-arrow-up-a green ion-arrow-down-a red');
							}
							

							$('.social_range').html(response.social_range);
						}
						$('.likes-count,.likes-percent-loader,.likes-count-view,.total-likes').removeClass('ajax-loader');
					}
				}

				if(request == 'getfborganicpaidlikes'){
					if($('#Social').find('.main-data-view').hasClass('facebookviewlikes')){
						organicPaidFbViewlikesGraph(response);
						organicPaidLikesFbDoughnutView(response);
						$("#organicPaidFblikestotalview div p").html("Total "+ "</br>"+ (response.total).toLocaleString("en-US"));
						$('#organicPaidFblikestotalview').attr('data-value',(response.total).toLocaleString("en-US"));
					}else{
						organicPaidFblikesGraph(response);
						organicPaidLikesFbDoughnut(response);
						$("#organicPaidFblikestotal div p").html("Total "+ "</br>"+ (response.total).toLocaleString("en-US"));
						$('#organicPaidFblikestotal').attr('data-value',(response.total).toLocaleString("en-US"));
					}
					$('.facebook_organicpaidlikes_loader').removeClass('ajax-loader');
				}

				if(request == 'getfbgenderlikes'){
					if($('#Social').find('.main-data-view').hasClass('facebookviewlikes')){
						genderLikesFbDoughnutview(response);
						genderLikesFbBarChartview(response);
						$("#fbgenderlikesDonaughttotalview div p").html("Total "+ "</br>"+ (response.total).toLocaleString("en-US"));
						$('#fbgenderlikesDonaughttotalview').attr('data-value',(response.total).toLocaleString("en-US"));
					}else{
						genderLikesFbDoughnut(response);
						genderLikesFbBarChart(response);
						$("#fbgenderlikesDonaughttotal div p").html("Total "+ "</br>"+ (response.total).toLocaleString("en-US"));
						$('#fbgenderlikesDonaughttotal').attr('data-value',(response.total).toLocaleString("en-US"));
					}
					$('.facebook_genderlikes_loader,.facebook_agelikes_loader').removeClass('ajax-loader');
				}

				if(request == 'getfbcountrylikes'){
					if($('#Social').find('.main-data-view').hasClass('facebookviewlikes')){
						$('#fbViewCountryLikesTable').html(response.data.data);
					}else{
						$('#fbCountryLikesTable').html(response.data.data);
					}
					
					$('#fbCountryLikesTable').html(response.data.data);
					$('.facebook_countrylikes_loader,.table-data').removeClass('ajax-loader');
					$('#fbCountryLikesTable,#fbViewCountryLikesTable').removeClass('ajax-loader');
				}

				if(request == 'getfbcitylikes'){
					$('.fbCitiesLikesTable').attr('data-fblikecity-count',response.data.count);
					if($('#Social').find('.main-data-view').hasClass('facebookviewlikes')){
						$('#fbViewCitiesLikesTable').html(response.data.data);
					}else{
						$('#fbCitiesLikesTable').html(response.data.data);
					}
					
					$('.facebook_citylikes_loader,.table-data').removeClass('ajax-loader');
					$('#fbCitiesLikesTable,#fbViewCitiesLikesTable').removeClass('ajax-loader');
				}

				if(request == 'getfblanguagelikes'){
					$('.fbLanguageLikesTable').attr('data-fblikelang-count',response.data.count);
					if($('#Social').find('.main-data-view').hasClass('facebookviewlikes')){
						$('#fbViewLanguageLikesTable').html(response.data.data);
					}else{
						$('#fbLanguageLikesTable').html(response.data.data);
					}
					
					$('.facebook_languagelikes_loader,.table-data').removeClass('ajax-loader');
					$('#fbLanguageLikesTable,#fbViewLanguageLikesTable').removeClass('ajax-loader');
				}

				if(request == 'getfbreach'){
					if($('#Social').find('.main-data-view').hasClass('facebookviewreach')){
						$('#reach_count_view').html(response.count);
						reachFbGraphView(response);
						$('.social_range_viewreach').html(response.social_range);
					}else{
						$('#reach_count').html(response.count);
						reachFbGraph(response);
					}
					
					$('#reach_count,#reach_count_view').removeClass('ajax-loader');
					$('.facebook_totalreach_loader').removeClass('ajax-loader');
				}

				if(request == 'getfborganicpaidreach'){
					if($('#Social').find('.main-data-view').hasClass('facebookviewreach')){
						organicPaidReachFbDoughnutview(response);
						$("#organicPaidFbreachtotalview div p").html("Total "+ "</br>"+ (response.total).toLocaleString("en-US"));
						$('#organicPaidFbreachtotalview').attr('data-value',(response.total).toLocaleString("en-US"));
					}else{
						organicPaidReachFbDoughnut(response);
						$("#organicPaidFbreachtotal div p").html("Total "+ "</br>"+ (response.total).toLocaleString("en-US"));
						$('#organicPaidFbreachtotal').attr('data-value',(response.total).toLocaleString("en-US"));
					}
					$('.facebook_organicpaidreach_loader').removeClass('ajax-loader');
				}

				if(request == 'getfborganicpaidvideoreach'){
					if($('#Social').find('.main-data-view').hasClass('facebookviewreach')){
						organicPaidVideoReachFbDoughnutview(response);
						$("#organicPaidFbvideoreachtotalview div p").html("Total "+ "</br>"+ (response.total).toLocaleString("en-US"));
						$('#organicPaidFbvideoreachtotalview').attr('data-value',(response.total).toLocaleString("en-US"));
					}else{
						organicPaidVideoReachFbDoughnut(response);
						$("#organicPaidFbvideoreachtotal div p").html("Total "+ "</br>"+ (response.total).toLocaleString("en-US"));
						$('#organicPaidFbvideoreachtotal').attr('data-value',(response.total).toLocaleString("en-US"));
					}
					$('.facebook_organicpaidvideoreach_loader').removeClass('ajax-loader');
				}

				if(request == 'getfbgenderreach'){
					if($('#Social').find('.main-data-view').hasClass('facebookviewreach')){
						genderReachFbBarChartview(response);
						genderReachFbStackedChartview(response);
					}else{
						genderReachFbBarChart(response);
						genderReachFbStackedChart(response);
					}
					$('.facebook_genderreach_loader,.facebook_agereach_loader').removeClass('ajax-loader');
				}

				if(request == 'getfbcountryreach'){
					if($('#Social').find('.main-data-view').hasClass('facebookviewreach')){
						$('#fbCountryReachTableview').html(response.data.data);
					}else{
						$('#fbCountryReachTable').html(response.data.data);
					}
					$('.facebook_countryreach_loader,.table-data').removeClass('ajax-loader');
					$('#fbCountryReachTable,#fbCountryReachTableview').removeClass('ajax-loader');
				}

				if(request == 'getfbcityreach'){
					$('.fbCitiesReachTable').attr('data-fbreachcity-count',response.data.count);
					if($('#Social').find('.main-data-view').hasClass('facebookviewreach')){
						$('#fbCitiesReachTableview').html(response.data.data);
					}else{
						$('#fbCitiesReachTable').html(response.data.data);
					}
					$('.facebook_cityreach_loader,.table-data').removeClass('ajax-loader');
					$('#fbCitiesReachTable,#fbCitiesReachTableview').removeClass('ajax-loader');
				}

				if(request == 'getfblanguagereach'){
					$('.fbLanguageReachTable').attr('data-fbreachlang-count',response.data.count);
					if($('#Social').find('.main-data-view').hasClass('facebookviewreach')){
						$('#fbLanguageReachTableview').html(response.data.data);
					}else{
						$('#fbLanguageReachTable').html(response.data.data);
					}
					$('.facebook_languagereach_loader,.table-data').removeClass('ajax-loader');
					$('#fbLanguageReachTable,#fbLanguageReachTableview').removeClass('ajax-loader');
				}

				if($('.fbCitiesLikesTable').attr('data-fblikecity-count') == 0 && $('.fbLanguageLikesTable').attr('data-fblikelang-count') == 0){
					$('.fbCitiesLikesTable,.fbLanguageLikesTable').removeClass('BreakBefore');
				}
				
				if($('.fbCitiesReachTable').attr('data-fbreachcity-count') == 0 && $('.fbLanguageReachTable').attr('data-fbreachlang-count') == 0){
					$('.fbCitiesReachTable,.fbLanguageReachTable').removeClass('BreakBefore');
				}
			}
		});
}

function sendFbListingAjax(request,campaignData){
	var pdfStatus = 0;
	if($('.pdf_status').val() == 1){
		var pdfStatus = 1;
	}

	var str2 = '_true';
	var page = 1;

	if(typeof campaignData === 'string') {
		if(campaignData.indexOf(str2) != -1){
			var page = campaignData.split(str2)[0];
		}
	}

	$('.post-empty,.review-empty').addClass('ajax-loader');

	$.ajax({
		type: 'GET',
		url:  BASE_URL + '/'+request,
		data: {id:$('.campaignID').val(),campaignData,pdfStatus,page},
		success: function(response) {
			if(request == 'getfbposts'){
				if($('#Social').find('.main-data-view').hasClass('facebookviewpostreviews')){
					//use for view key
					$('#postDataTableview').html(response);
				}else{
					$('#postDataTable').html(response);
				}
				$('.full_picture,.fromImage,.fromName,.datePost,.fb_post_ul,.postMessage,.post-empty').removeClass('ajax-loader');
			}


			if(request == 'getfbpostspagination'){
				$('.facebook_post_pagination').html(response);
			}


			if(request == 'getfbreviews'){
				if($('#Social').find('.main-data-view').hasClass('facebookviewreviews')){
					//use for view key
					$('#reviewDataTableview').html(response);
				}else{
					$('#reviewDataTable').html(response);
				}
				$('.reviewerName,.reviewDate,.review-rating,.reviewText,.review-empty,.review_image,.reviewerImage').removeClass('ajax-loader');
			}

			if(request == 'getfbreviewspagination'){
				$('.facebook_reviews_pagination').html(response);
			}
		}
	});
}

$(document).on('click','.fb_post_pagination a',function(e){
	e.preventDefault();
	$('fb_post_pagination ul li').removeClass('active');
	$(this).parent().addClass('active');
	var page = $(this).attr('href').split('page=')[1];
	$('.full_picture,.fromImage,.fromName,.datePost,.postMessage,.fb_post_ul,.postMessage,.post-empty').addClass('ajax-loader');
	sendFbListingAjax('getfbposts',page+'_true');
	sendFbListingAjax('getfbpostspagination',page+'_true');
});


$(document).on('click','.fb_reviews_pagination a',function(e){
	e.preventDefault();
	$('fb_post_pagination ul li').removeClass('active');
	$(this).parent().addClass('active');
	var page = $(this).attr('href').split('page=')[1];
	$('.reviewerName,.reviewDate,.review-rating,.reviewText,.review-empty,.review_image,.reviewerImage').addClass('ajax-loader');
	sendFbListingAjax('getfbreviews',page+'_true');
	sendFbListingAjax('getfbreviewspagination',page+'_true');
});

/*Filter Ajax*/
function socialDateRangeFilters(campaignData){
	sendFbAjax('social_date_range_filters',campaignData);
}


/*Likes*/
function getFbLikes(campaignData){
	sendFbAjax('getfblikes',campaignData);
}

function getFbOrganicPaidLikes(campaignData){
	sendFbAjax('getfborganicpaidlikes',campaignData);
}

function getFbGenderLikes(campaignData){
	sendFbAjax('getfbgenderlikes',campaignData);
}

function getFbCountryLikes(campaignData){
	sendFbAjax('getfbcountrylikes',campaignData);
}

function getFbCityLikes(campaignData){
	sendFbAjax('getfbcitylikes',campaignData);
}

function getFbLanguageLikes(campaignData){
	sendFbAjax('getfblanguagelikes',campaignData);
}

/*Reach*/
function getFbReach(campaignData){
	sendFbAjax('getfbreach',campaignData);
}

function getFbOrganicPaidReach(campaignData){
	sendFbAjax('getfborganicpaidreach',campaignData);
}

function getFbOrganicPaidVideoReach(campaignData){
	sendFbAjax('getfborganicpaidvideoreach',campaignData);
}

function getFbGenderReach(campaignData){
	sendFbAjax('getfbgenderreach',campaignData);
}

function getFbCountryReach(campaignData){
	sendFbAjax('getfbcountryreach',campaignData);
}

function getFbCityReach(campaignData){
	sendFbAjax('getfbcityreach',campaignData);
}

function getFbLanguageReach(campaignData){
	sendFbAjax('getfblanguagereach',campaignData);
}

/*Posts*/
function getFbPosts(campaignData){
	sendFbListingAjax('getfbposts',campaignData);
	sendFbListingAjax('getfbpostspagination',campaignData);
}

/*Reviews*/
function getFbReviews(campaignData){
	sendFbListingAjax('getfbreviews',campaignData);
	sendFbListingAjax('getfbreviewspagination',campaignData);
}

/*View Key functions*/
function facebook_SideBar_view(campaign_id,selected){
	var jsonData = {campaign_id,selected}; 
	if(selected == '#facebookviewlikes'){
		sendFbAjax('social_date_range_filters',jsonData);
		sendFbAjax('getfblikes',jsonData);
		sendFbAjax('getfborganicpaidlikes',jsonData);
		sendFbAjax('getfbgenderlikes',jsonData);
		sendFbAjax('getfbcountrylikes',jsonData);
		sendFbAjax('getfbcitylikes',jsonData);
		sendFbAjax('getfblanguagelikes',jsonData);
	}

	if(selected == '#facebookviewreach'){
		sendFbAjax('social_date_range_filters',jsonData);
		sendFbAjax('getfbreach',jsonData);
		sendFbAjax('getfborganicpaidreach',jsonData);
		sendFbAjax('getfborganicpaidvideoreach',jsonData);
		sendFbAjax('getfbgenderreach',jsonData);
		sendFbAjax('getfbcountryreach',jsonData);
		sendFbAjax('getfbcityreach',jsonData);
		sendFbAjax('getfblanguagereach',jsonData);
	}

	if(selected == '#facebookviewpostreviews'){
		sendFbListingAjax('getfbposts',jsonData);
		sendFbListingAjax('getfbpostspagination',jsonData);
	}

	if(selected == '#facebookviewreviews'){
		sendFbListingAjax('getfbreviews',jsonData);
		sendFbListingAjax('getfbreviewspagination',jsonData);
	}
}



var organicPaidFblikesGraphConfig = {
	type: 'line',
	data: {
		labels: [],
		datasets: [
		{
			labels: [],
		            label: 'Organic', // Name the series
		            data: [], // Specify the data values array
		            fill: true,
		            borderColor: '#369cfd', // Add custom color border (Line)
		            backgroundColor: '#d7ebff', // Add custom color background (Points and Fill)
		            borderWidth: 2// Specif y bar border width
		        },
		        {
		            label: 'Paid', // Name the series
		            data: [], // Specify the data values array
		            fill: true,
		            borderColor: '#f5c633', // Add custom color border (Line)
		            backgroundColor: '#fdf3d5', // Add custom color background (Points and Fill)
		            borderWidth: 2 // Specify bar border width
		        }
		        ]
		    },
		    options: {
		    	responsive: true,
		    	maintainAspectRatio:false,
		    	scales: {
		    		xAxes: [{
		    			ticks: {
		    				autoSkip: true,
		    				maxRotation: 0,
		    				minRotation: 0,
		    				maxTicksLimit:5
		    			},
		    			gridLines: {
		    				color: "rgba(0, 0, 0, 0)"
		    			}
		    		}],
		    		yAxes:[{
		    			beforeBuildTicks: function(axis) { 
		    				var stepSize = parseInt(axis.max/5);    
		    				if(stepSize >= 100 && stepSize <= 1000){
		    					axis.options.ticks.stepSize =  Math.ceil(stepSize/100)*100;
		    				}else if(stepSize >= 1000 && stepSize <= 10000){
		    					axis.options.ticks.stepSize =  Math.ceil(stepSize/1000)*1000;
		    				}else if(stepSize >= 10000 && stepSize <= 100000){
		    					axis.options.ticks.stepSize =  Math.ceil(stepSize/10000)*10000;
		    				}else{
		    					axis.options.ticks.stepSize =  stepSize;
		    				}
		    			}
		    		}]
		    	},
		    	tooltips: {
		    		mode: 'index',
		    		intersect: false,
		    		backgroundColor:'rgb(255, 255, 255)',
		    		titleFontColor:'#000',
		    		bodyFontStyle: 'bold',  
		    		callbacks: {
		    			labelTextColor: function(context) {
		    				return '#000';
		    			},
		    			title: function() {}
		    			,
		    			beforeLabel: function(tooltipItem, data) {
		    				if(tooltipItem.datasetIndex === 0){
		    					return data.datasets[0].labels[tooltipItem.index];
		    				}
		    				else if(tooltipItem.datasetIndex === 2){
		    					return data.datasets[2].labels[tooltipItem.index];  
		    				}
		    			},
		    			label: function(tooltipItem, data) {
	    					const label = data.datasets[tooltipItem.datasetIndex].label;
					        const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
					        const sign = value >= 0 ? '' : '';
					        return `${label}: ${sign}${value.toLocaleString("en-US")}`;
				        }
		    		}
		    	},
		    	legend: {
		    		align: 'center',
		    		display:true,
		    		labels: {
		    			boxWidth: 10
		    		}
		    	},
		    	elements: {
		    		point:{
		    			radius: 0,
		    			hitRadius :1
		    		},

		    	},
		    }
		};



		function organicPaidFblikesGraph(response){

			if(window.LineOverviewFacebooks){
				window.LineOverviewFacebooks.destroy();
			} 
			var ctxfacebookOverviewLineChart = document.getElementById('organicPaidFblikes').getContext('2d');
			window.LineOverviewFacebooks = new Chart(ctxfacebookOverviewLineChart, organicPaidFblikesGraphConfig);
			var gradient = gradientColor(ctxfacebookOverviewLineChart);
			organicPaidFblikesGraphConfig.data.labels =  response.labels;
			organicPaidFblikesGraphConfig.data.datasets[0].labels = response.labels;
			organicPaidFblikesGraphConfig.data.datasets[0].data = response.organic;

			organicPaidFblikesGraphConfig.data.datasets[1].data = response.paid;



			if(response.comparison == 1 || response.comparison == '1'){
				organicPaidFblikesGraphConfig.data.datasets.splice(2,2);
				organicPaidFblikesGraphConfig.data.datasets.splice(3,3);

				var dataset_1 = {
					label: 'Organic',
					labels: response.previous_labels,
					fill: true,
					borderColor: '#369cfd',
					backgroundColor: '#d7ebff',
					data: response.previous_organic,
					borderWidth:2,
					borderDash: [5,5]

				};
				var dataset_2 = {
					label: 'Paid',
					fill: true,
					borderColor: '#f5c633',
					backgroundColor: '#fdf3d5',
					data: response.previous_paid,
					borderWidth:2,
					borderDash: [5,5]

				};

				organicPaidFblikesGraphConfig.data.datasets.push(dataset_1);
				organicPaidFblikesGraphConfig.data.datasets.push(dataset_2);

			}else{
				organicPaidFblikesGraphConfig.data.datasets.splice(2,2);
				organicPaidFblikesGraphConfig.data.datasets.splice(3,3);
			}

			window.LineOverviewFacebooks.update();
		}



	var organicPaidFbViewlikesGraphConfig = {
	type: 'line',
	data: {
		labels: [],
		datasets: [
		{
			labels: [],
		            label: 'Organic', // Name the series
		            data: [], // Specify the data values array
		            fill: true,
		            borderColor: '#369cfd', // Add custom color border (Line)
		            backgroundColor: '#d7ebff', // Add custom color background (Points and Fill)
		            borderWidth: 2// Specif y bar border width
		        },
		        {
		            label: 'Paid', // Name the series
		            data: [], // Specify the data values array
		            fill: true,
		            borderColor: '#f5c633', // Add custom color border (Line)
		            backgroundColor: '#fdf3d5', // Add custom color background (Points and Fill)
		            borderWidth: 2 // Specify bar border width
		        }
		        ]
		    },
		    options: {
		    	responsive: true,
		    	maintainAspectRatio:false,
		    	scales: {
		    		xAxes: [{
		    			ticks: {
		    				autoSkip: true,
		    				maxRotation: 0,
		    				minRotation: 0,
		    				maxTicksLimit:5
		    			},
		    			gridLines: {
		    				color: "rgba(0, 0, 0, 0)"
		    			}
		    		}],
		    		yAxes:[{
		    			beforeBuildTicks: function(axis) { 
		    				var stepSize = parseInt(axis.max/5);    
		    				if(stepSize >= 100 && stepSize <= 1000){
		    					axis.options.ticks.stepSize =  Math.ceil(stepSize/100)*100;
		    				}else if(stepSize >= 1000 && stepSize <= 10000){
		    					axis.options.ticks.stepSize =  Math.ceil(stepSize/1000)*1000;
		    				}else if(stepSize >= 10000 && stepSize <= 100000){
		    					axis.options.ticks.stepSize =  Math.ceil(stepSize/10000)*10000;
		    				}else{
		    					axis.options.ticks.stepSize =  stepSize;
		    				}
		    			}
		    		}]
		    	},
		    	tooltips: {
		    		mode: 'index',
		    		intersect: false,
		    		backgroundColor:'rgb(255, 255, 255)',
		    		titleFontColor:'#000',
		    		bodyFontStyle: 'bold',  
		    		callbacks: {
		    			labelTextColor: function(context) {
		    				return '#000';
		    			},
		    			title: function() {}
		    			,
		    			beforeLabel: function(tooltipItem, data) {
		    				if(tooltipItem.datasetIndex === 0){
		    					return data.datasets[0].labels[tooltipItem.index];
		    				}
		    				else if(tooltipItem.datasetIndex === 2){
		    					return data.datasets[2].labels[tooltipItem.index];  
		    				}
		    			},
		    			label: function(tooltipItem, data) {
	    					const label = data.datasets[tooltipItem.datasetIndex].label;
					        const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
					        const sign = value >= 0 ? '' : '';
					        return `${label}: ${sign}${value.toLocaleString("en-US")}`;
				        }
		    		}
		    	},
		    	legend: {
		    		align: 'center',
		    		display:true,
		    		labels: {
		    			boxWidth: 10
		    		}
		    	},
		    	elements: {
		    		point:{
		    			radius: 0,
		    			hitRadius :1
		    		},

		    	},
		    }
		};



		function organicPaidFbViewlikesGraph(response){

			if(window.LineOverviewFacebooksView){
				window.LineOverviewFacebooksView.destroy();
			} 
			var ctxfacebookOverviewLineChartView = document.getElementById('organicPaidFbViewlikes').getContext('2d');
			window.LineOverviewFacebooksView = new Chart(ctxfacebookOverviewLineChartView, organicPaidFbViewlikesGraphConfig);
			var gradient = gradientColor(ctxfacebookOverviewLineChartView);
			organicPaidFbViewlikesGraphConfig.data.labels =  response.labels;
			organicPaidFbViewlikesGraphConfig.data.datasets[0].labels = response.labels;
			organicPaidFbViewlikesGraphConfig.data.datasets[0].data = response.organic;

			organicPaidFbViewlikesGraphConfig.data.datasets[1].data = response.paid;



			if(response.comparison == 1 || response.comparison == '1'){
				organicPaidFbViewlikesGraphConfig.data.datasets.splice(2,2);
				organicPaidFbViewlikesGraphConfig.data.datasets.splice(3,3);

				var dataset_1 = {
					label: 'Organic',
					labels: response.previous_labels,
					fill: true,
					borderColor: '#369cfd',
					backgroundColor: '#d7ebff',
					data: response.previous_organic,
					borderWidth:2,
					borderDash: [5,5]

				};
				var dataset_2 = {
					label: 'Paid',
					fill: true,
					borderColor: '#f5c633',
					backgroundColor: '#fdf3d5',
					data: response.previous_paid,
					borderWidth:2,
					borderDash: [5,5]

				};

				organicPaidFbViewlikesGraphConfig.data.datasets.push(dataset_1);
				organicPaidFbViewlikesGraphConfig.data.datasets.push(dataset_2);

			}else{
				organicPaidFbViewlikesGraphConfig.data.datasets.splice(2,2);
				organicPaidFbViewlikesGraphConfig.data.datasets.splice(3,3);
			}

			window.LineOverviewFacebooksView.update();
		}



		function organicPaidLikesFbDoughnut(response){
			if(window.myDoughnutorganicPaidLikes){
				window.myDoughnutorganicPaidLikes.destroy();
			} 

			var organicPaid = document.getElementById('organicPaidFblikesdonaught').getContext('2d');
			window.myDoughnutorganicPaidLikes = new Chart(organicPaid, organicPaidLikesFbDoughnutconfig);
			if(response.organicCount == 0 && response.paidCount == 0){
				organicPaidLikesFbDoughnutconfig.data.datasets[0].backgroundColor = [];
				organicPaidLikesFbDoughnutconfig.data.datasets[0].borderColor = [];
			}else{
				organicPaidLikesFbDoughnutconfig.data.datasets[0].backgroundColor = ['#369cfd','#f5c633'];
				organicPaidLikesFbDoughnutconfig.data.datasets[0].borderColor = ['#369cfd','#f5c633'];
			}
			organicPaidLikesFbDoughnutconfig.data.datasets[0].data = response.data;
			window.myDoughnutorganicPaidLikes.update();
		}

		var organicPaidLikesFbDoughnutconfig = {
			type: 'doughnut',
			data: {
				datasets: [{
					data: [],
					backgroundColor: [],
					borderColor: [],
					borderWidth: 1
				}],
				labels: ["Organic", "Paid"]
			},
			options: {
				legend: {
					display: false
				},
				cutoutPercentage: 75,
				animation: {
					animateScale: true,
					animateRotate: true
				},
				circumference: 2 * Math.PI,
				responsive: true,
				maintainAspectRatio: false,
				
				tooltips: {
					enabled: false,
					custom: function(tooltip) {
						var tooltipEl = document.getElementById('organicPaidFblikestotal');

				        // Hide if no tooltip
				        if (tooltip.opacity === 0) {
				        	tooltipEl.style.color = "#464950";
				        	$('#organicPaidFblikestotal div p').html("Total "+ "</br>"+ $('#organicPaidFblikestotal').attr('data-value'));
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
				        		var dataToPercent = (dataValNum / sumOfDataVal(organicPaidLikesFbDoughnutconfig) * 100).toFixed(2) + '%';
				        		
				        		var numberFormat =  shortNumber(dataValNum);
				        		innerHtml += textVal+'</br>'+numberFormat+'</br>'+dataToPercent;
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

		//view graph
		function organicPaidLikesFbDoughnutView(response){
			if(window.myDoughnutorganicPaidLikesView){
				window.myDoughnutorganicPaidLikesView.destroy();
			} 

			var organicPaidView = document.getElementById('organicPaidFblikesdonaughtview').getContext('2d');
			window.myDoughnutorganicPaidLikesView = new Chart(organicPaidView, organicPaidLikesFbDoughnutconfigview);
			if(response.organicCount == 0 && response.paidCount == 0){
				organicPaidLikesFbDoughnutconfigview.data.datasets[0].backgroundColor = [];
				organicPaidLikesFbDoughnutconfigview.data.datasets[0].borderColor = [];
			}else{
				organicPaidLikesFbDoughnutconfigview.data.datasets[0].backgroundColor = ['#369cfd','#f5c633'];
				organicPaidLikesFbDoughnutconfigview.data.datasets[0].borderColor = ['#369cfd','#f5c633'];
			}
			organicPaidLikesFbDoughnutconfigview.data.datasets[0].data = response.data;
			window.myDoughnutorganicPaidLikesView.update();
		}

		var organicPaidLikesFbDoughnutconfigview = {
			type: 'doughnut',
			data: {
				datasets: [{
					data: [],
					backgroundColor: [],
					borderColor: [],
					borderWidth: 1
				}],
				labels: ["Organic", "Paid"]
			},
			options: {
				legend: {
					display: false
				},
				cutoutPercentage: 75,
				animation: {
					animateScale: true,
					animateRotate: true
				},
				circumference: 2 * Math.PI,
				responsive: true,
				maintainAspectRatio: false,
				
				tooltips: {
					enabled: false,
					custom: function(tooltip) {
						var tooltipEl = document.getElementById('organicPaidFblikestotalview');

				        // Hide if no tooltip
				        if (tooltip.opacity === 0) {
				        	tooltipEl.style.color = "#464950";
				        	$('#organicPaidFblikestotalview div p').html("Total "+ "</br>"+ $('#organicPaidFblikestotalview').attr('data-value'));
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
				        		var dataToPercent = (dataValNum / sumOfDataVal(organicPaidLikesFbDoughnutconfigview) * 100).toFixed(2) + '%';
				        		
				        		var numberFormat =  shortNumber(dataValNum);
				        		innerHtml += textVal+'</br>'+numberFormat+'</br>'+dataToPercent;
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


		function genderLikesFbDoughnut(response){
			if(window.myDoughnutgenderLikes){
				window.myDoughnutgenderLikes.destroy();
			}

			var gender = document.getElementById('likesFbGender').getContext('2d');
			window.myDoughnutgenderLikes = new Chart(gender, genderLikesFbDoughnutconfig);

			if(response.femaleCount == 0 && response.maleCount == 0 && response.otherCount == 0){
				genderLikesFbDoughnutconfig.data.datasets[0].backgroundColor = [];
				genderLikesFbDoughnutconfig.data.datasets[0].borderColor = [];
			}else{
				genderLikesFbDoughnutconfig.data.datasets[0].backgroundColor = ['#369cfd','#f3b0d0','#cac9c9'];
				genderLikesFbDoughnutconfig.data.datasets[0].borderColor = ['#369cfd','#f3b0d0','#cac9c9'];
			}

			genderLikesFbDoughnutconfig.data.datasets[0].data = response.data;
			window.myDoughnutgenderLikes.update();
		}


		var genderLikesFbDoughnutconfig = {
			type: 'doughnut',
			data: {
				datasets: [{
					data: [],
					backgroundColor: [],
					borderColor: [],
					borderWidth: 1
				}],
				labels: ["Male", "Female", "Unspecified"]
			},
			options: {
				legend: {
					display: false
				},
				cutoutPercentage: 75,
				animation: {
					animateScale: true,
					animateRotate: true
				},
				circumference: 2 * Math.PI,
				responsive: true,
				maintainAspectRatio: false,
				
				tooltips: {
					enabled: false,
					custom: function(tooltip) {
						var tooltipEl = document.getElementById('fbgenderlikesDonaughttotal');


				        // Hide if no tooltip
				        if (tooltip.opacity === 0) {
				        	tooltipEl.style.color = "#464950";
				        	$('#fbgenderlikesDonaughttotal div p').html("Total "+ "</br>"+ $('#fbgenderlikesDonaughttotal').attr('data-value'));
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
				        		var dataToPercent = (dataValNum / sumOfDataVal(genderLikesFbDoughnutconfig) * 100).toFixed(2) + '%';
				        		
				        		var numberFormat =  shortNumber(dataValNum);
				        		innerHtml += textVal+'</br>'+numberFormat+'</br>'+dataToPercent;
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


		//view key graph
		function genderLikesFbDoughnutview(response){
			if(window.myDoughnutgenderLikesview){
				window.myDoughnutgenderLikesview.destroy();
			}

			var genderview = document.getElementById('likesFbGenderview').getContext('2d');
			window.myDoughnutgenderLikesview = new Chart(genderview, genderLikesFbDoughnutconfigview);

			if(response.femaleCount == 0 && response.maleCount == 0 && response.otherCount == 0){
				genderLikesFbDoughnutconfigview.data.datasets[0].backgroundColor = [];
				genderLikesFbDoughnutconfigview.data.datasets[0].borderColor = [];
			}else{
				genderLikesFbDoughnutconfigview.data.datasets[0].backgroundColor = ['#369cfd','#f3b0d0','#cac9c9'];
				genderLikesFbDoughnutconfigview.data.datasets[0].borderColor = ['#369cfd','#f3b0d0','#cac9c9'];
			}

			genderLikesFbDoughnutconfigview.data.datasets[0].data = response.data;
			window.myDoughnutgenderLikesview.update();
		}


		var genderLikesFbDoughnutconfigview = {
			type: 'doughnut',
			data: {
				datasets: [{
					data: [],
					backgroundColor: [],
					borderColor: [],
					borderWidth: 1
				}],
				labels: ["Male", "Female", "Unspecified"]
			},
			options: {
				legend: {
					display: false
				},
				cutoutPercentage: 75,
				animation: {
					animateScale: true,
					animateRotate: true
				},
				circumference: 2 * Math.PI,
				responsive: true,
				maintainAspectRatio: false,
				
				tooltips: {
					enabled: false,
					custom: function(tooltip) {
						var tooltipEl = document.getElementById('fbgenderlikesDonaughttotalview');


				        // Hide if no tooltip
				        if (tooltip.opacity === 0) {
				        	tooltipEl.style.color = "#464950";
				        	$('#fbgenderlikesDonaughttotalview div p').html("Total "+ "</br>"+ $('#fbgenderlikesDonaughttotalview').attr('data-value'));
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
				        		var dataToPercent = (dataValNum / sumOfDataVal(genderLikesFbDoughnutconfigview) * 100).toFixed(2) + '%';
				        		
				        		var numberFormat =  shortNumber(dataValNum);
				        		innerHtml += textVal+'</br>'+numberFormat+'</br>'+dataToPercent;
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





		var genderLikesFbBarChartConfig = {
			type: 'bar',
			data: {
				labels: ['13-17', '18-24', '25-34', '35-44', '45-54', '55-64', '65'],
				datasets: [{
					label: 'Current ',
					data: [],
					backgroundColor: [
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd'
					],
					borderColor: [
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd'
					],
					borderWidth: 1,
					maxBarThickness:25
				}]
			},
			options: {
				maintainAspectRatio: false,
				scales: {
					xAxes: [{
						gridLines:{
							display:false
						},
						ticks: {
		                min: 0, // minimum value
		                max: 10 // maximum value
		            }
		        }],
		        yAxes: [{
		        	ticks: {
		        		beginAtZero: true
		        	},
		        	beforeBuildTicks: function(axis) { 
		        		var stepSize = parseInt(axis.max/5);    
		        		if(stepSize >= 100 && stepSize <= 1000){
		        			axis.options.ticks.stepSize =  Math.ceil(stepSize/100)*100;
		        		}else if(stepSize >= 1000 && stepSize <= 10000){
		        			axis.options.ticks.stepSize =  Math.ceil(stepSize/1000)*1000;
		        		}else if(stepSize >= 10000 && stepSize <= 100000){
		        			axis.options.ticks.stepSize =  Math.ceil(stepSize/10000)*10000;
		        		}else{
		        			axis.options.ticks.stepSize =  stepSize;
		        		}
		        	}
		        }]
		    },
		    legend: {
		    	display: false,
		    	labels: {
		    		boxWidth: 10
		    	}
		    },
		    tooltips:{
		    	backgroundColor:'rgb(255, 255, 255)',
		    	titleFontColor:'#000',
		    	bodyFontStyle: 'bold',
		    	callbacks: {
		    		labelTextColor: function(context) {
		    			return '#000';
		    		},
	    			label: function(tooltipItem, data) {
    					const label = data.datasets[tooltipItem.datasetIndex].label;
				        const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
				        const sign = value >= 0 ? '' : '';
				        return `${label}: ${sign}${value.toLocaleString("en-US")}`;
			        }
		    	}
		    }
		}
	}


	function genderLikesFbBarChart(response){
		if(window.bargenderGraphFacebook){
			window.bargenderGraphFacebook.destroy();
		}

		var ctxgenderLikesFbBarChart = document.getElementById('likesFbGenderBar').getContext('2d');
		window.bargenderGraphFacebook = new Chart(ctxgenderLikesFbBarChart, genderLikesFbBarChartConfig);
		genderLikesFbBarChartConfig.data.datasets[0].data = response.age;

		if(response.comparison == 1 || response.comparison == '1'){
			genderLikesFbBarChartConfig.data.datasets.splice(1,1);

			var dataset_1 = {
				label: 'Previous ',
				data: response.previousage,
				backgroundColor: [
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633'
				],
				orderColor: [
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633'
				],
				borderWidth: 1,
				maxBarThickness:25
			}
			genderLikesFbBarChartConfig.data.datasets.push(dataset_1);
		}else{
			genderLikesFbBarChartConfig.data.datasets.splice(1,1);
		}

		window.bargenderGraphFacebook.update();
	}


	//view key graph
	var genderLikesFbBarChartConfigview = {
			type: 'bar',
			data: {
				labels: ['13-17', '18-24', '25-34', '35-44', '45-54', '55-64', '65'],
				datasets: [{
					label: 'Current ',
					data: [],
					backgroundColor: [
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd'
					],
					borderColor: [
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd'
					],
					borderWidth: 1,
					maxBarThickness:25
				}]
			},
			options: {
				maintainAspectRatio: false,
				scales: {
					xAxes: [{
						gridLines:{
							display:false
						},
						ticks: {
		                min: 0, // minimum value
		                max: 10 // maximum value
		            }
		        }],
		        yAxes: [{
		        	ticks: {
		        		beginAtZero: true
		        	},
		        	beforeBuildTicks: function(axis) { 
		        		var stepSize = parseInt(axis.max/5);    
		        		if(stepSize >= 100 && stepSize <= 1000){
		        			axis.options.ticks.stepSize =  Math.ceil(stepSize/100)*100;
		        		}else if(stepSize >= 1000 && stepSize <= 10000){
		        			axis.options.ticks.stepSize =  Math.ceil(stepSize/1000)*1000;
		        		}else if(stepSize >= 10000 && stepSize <= 100000){
		        			axis.options.ticks.stepSize =  Math.ceil(stepSize/10000)*10000;
		        		}else{
		        			axis.options.ticks.stepSize =  stepSize;
		        		}
		        	}
		        }]
		    },
		    legend: {
		    	display: false,
		    	labels: {
		    		boxWidth: 10
		    	}
		    },
		    tooltips:{
		    	backgroundColor:'rgb(255, 255, 255)',
		    	titleFontColor:'#000',
		    	bodyFontStyle: 'bold',
		    	callbacks: {
		    		labelTextColor: function(context) {
		    			return '#000';
		    		},
	    			label: function(tooltipItem, data) {
    					const label = data.datasets[tooltipItem.datasetIndex].label;
				        const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
				        const sign = value >= 0 ? '' : '';
				        return `${label}: ${sign}${value.toLocaleString("en-US")}`;
			        }
		    	}
		    }
		}
	}


	function genderLikesFbBarChartview(response){
		if(window.bargenderGraphFacebookview){
			window.bargenderGraphFacebookview.destroy();
		}

		var ctxgenderLikesFbBarChartview = document.getElementById('likesFbGenderBarview').getContext('2d');
		window.bargenderGraphFacebookview = new Chart(ctxgenderLikesFbBarChartview, genderLikesFbBarChartConfigview);
		genderLikesFbBarChartConfigview.data.datasets[0].data = response.age;

		if(response.comparison == 1 || response.comparison == '1'){
			genderLikesFbBarChartConfigview.data.datasets.splice(1,1);

			var dataset_1 = {
				label: 'Previous ',
				data: response.previousage,
				backgroundColor: [
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633'
				],
				orderColor: [
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633',
				'#f5c633'
				],
				borderWidth: 1,
				maxBarThickness:25
			}
			genderLikesFbBarChartConfigview.data.datasets.push(dataset_1);
		}else{
			genderLikesFbBarChartConfigview.data.datasets.splice(1,1);
		}

		window.bargenderGraphFacebookview.update();
	}


	
	function organicPaidReachFbDoughnut(response){
		if(window.myDoughnutorganicPaidReach){
			window.myDoughnutorganicPaidReach.destroy();
		}
		var doughnutorganicPaidReach = document.getElementById('organicPaidFbreach').getContext('2d');
		window.myDoughnutorganicPaidReach = new Chart(doughnutorganicPaidReach, organicPaidReachFbDoughnutconfig);

		if(response.countOrganic == 0 && response.countPaid == 0){
			organicPaidReachFbDoughnutconfig.data.datasets[0].backgroundColor = [];
			organicPaidReachFbDoughnutconfig.data.datasets[0].borderColor = [];
		}else{
			organicPaidReachFbDoughnutconfig.data.datasets[0].backgroundColor = ['#369cfd','#f5c633'];
			organicPaidReachFbDoughnutconfig.data.datasets[0].borderColor = ['#369cfd','#f5c633'];
		}

		organicPaidReachFbDoughnutconfig.data.datasets[0].data = response.data;
		window.myDoughnutorganicPaidReach.update();
	}


	function shortNumber(dataValNum){
		if (dataValNum > 1000000000000){
			var numberFormat =  (dataValNum/1000000000000).toFixed(2)+' T';
		} 
        else if (dataValNum > 1000000000){
         var numberFormat =  (dataValNum/1000000000).toFixed(2)+' B';
        }
        else if (dataValNum > 1000000){
        	var numberFormat =  (dataValNum/1000000).toFixed(2)+' M';
        } 
        else if (dataValNum > 1000){
        	var numberFormat =  (dataValNum/1000).toFixed(2)+' K';
        }else{
        	var numberFormat = dataValNum;
        }
        
        return numberFormat; 
	}


	var organicPaidReachFbDoughnutconfig = {
		type: 'doughnut',
		data: {
			datasets: [{
				data: [],
				backgroundColor: [],
				borderColor: [],
				borderWidth: 1
			}],
			labels: ["Organic", "Paid"]
		},
		options: {
			legend: {
				display: false
			},
			cutoutPercentage: 75,
			animation: {
				animateScale: true,
				animateRotate: true
			},
			circumference: 2 * Math.PI,
			responsive: true,
			maintainAspectRatio: false,

			tooltips: {
				enabled: false,
				custom: function(tooltip) {
					var tooltipEl = document.getElementById('organicPaidFbreachtotal');


				        // Hide if no tooltip
				        if (tooltip.opacity === 0) {
				        	tooltipEl.style.color = "#464950";
				        	$('#organicPaidFbreachtotal div p').html("Total "+ "</br>"+ $('#organicPaidFbreachtotal').attr('data-value'));
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
				        		var dataToPercent = (dataValNum / sumOfDataVal(organicPaidReachFbDoughnutconfig) * 100).toFixed(2) + '%';
				        		
				        		var numberFormat =  shortNumber(dataValNum);
				        		innerHtml += textVal+'</br>'+numberFormat+'</br>'+dataToPercent;
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
		}


		// view key graph
		function organicPaidReachFbDoughnutview(response){
		if(window.myDoughnutorganicPaidReachView){
			window.myDoughnutorganicPaidReachView.destroy();
		}
		var doughnutorganicPaidReachView = document.getElementById('organicPaidFbreachview').getContext('2d');
		window.myDoughnutorganicPaidReachView = new Chart(doughnutorganicPaidReachView, organicPaidReachFbDoughnutconfigview);

		if(response.countOrganic == 0 && response.countPaid == 0){
			organicPaidReachFbDoughnutconfigview.data.datasets[0].backgroundColor = [];
			organicPaidReachFbDoughnutconfigview.data.datasets[0].borderColor = [];
		}else{
			organicPaidReachFbDoughnutconfigview.data.datasets[0].backgroundColor = ['#369cfd','#f5c633'];
			organicPaidReachFbDoughnutconfigview.data.datasets[0].borderColor = ['#369cfd','#f5c633'];
		}

		organicPaidReachFbDoughnutconfigview.data.datasets[0].data = response.data;
		window.myDoughnutorganicPaidReachView.update();
	}

	var organicPaidReachFbDoughnutconfigview = {
		type: 'doughnut',
		data: {
			datasets: [{
				data: [],
				backgroundColor: [],
				borderColor: [],
				borderWidth: 1
			}],
			labels: ["Organic", "Paid"]
		},
		options: {
			legend: {
				display: false
			},
			cutoutPercentage: 75,
			animation: {
				animateScale: true,
				animateRotate: true
			},
			circumference: 2 * Math.PI,
			responsive: true,
			maintainAspectRatio: false,

			tooltips: {
				enabled: false,
				custom: function(tooltip) {
					var tooltipEl = document.getElementById('organicPaidFbreachtotalview');


				        // Hide if no tooltip
				        if (tooltip.opacity === 0) {
				        	tooltipEl.style.color = "#464950";
				        	$('#organicPaidFbreachtotalview div p').html("Total "+ "</br>"+ $('#organicPaidFbreachtotalview').attr('data-value'));
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
				        		var dataToPercent = (dataValNum / sumOfDataVal(organicPaidReachFbDoughnutconfigview) * 100).toFixed(2) + '%';
				        		
				        		var numberFormat =  shortNumber(dataValNum);
				        		innerHtml += textVal+'</br>'+numberFormat+'</br>'+dataToPercent;
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
		}


		function organicPaidVideoReachFbDoughnut(response){
			if(window.myDoughnutorganicPaidvideoReach){
				window.myDoughnutorganicPaidvideoReach.destroy();
			}
			var doughnutorganicPaidvideoReach = document.getElementById('organicPaidFbvideoreach').getContext('2d');
			window.myDoughnutorganicPaidvideoReach = new Chart(doughnutorganicPaidvideoReach, organicPaidVideoReachFbDoughnutconfig);

			if(response.countOrganic == 0 && response.countPaid == 0){
				organicPaidVideoReachFbDoughnutconfig.data.datasets[0].backgroundColor = [];
				organicPaidVideoReachFbDoughnutconfig.data.datasets[0].borderColor = [];
			}else{
				organicPaidVideoReachFbDoughnutconfig.data.datasets[0].backgroundColor = ['#369cfd','#f5c633'];
				organicPaidVideoReachFbDoughnutconfig.data.datasets[0].borderColor = ['#369cfd','#f5c633'];
			}

			organicPaidVideoReachFbDoughnutconfig.data.datasets[0].data = response.data;
			window.myDoughnutorganicPaidvideoReach.update();
		}

		var organicPaidVideoReachFbDoughnutconfig = {
			type: 'doughnut',
			data: {
				datasets: [{
					data: [],
					backgroundColor: [],
					borderColor: [],
					borderWidth: 1
				}],
				labels: ["Organic", "Paid"]
			},
			options: {
				legend: {
					display: false
				},
				cutoutPercentage: 75,
				animation: {
					animateScale: true,
					animateRotate: true
				},
				circumference: 2 * Math.PI,
				responsive: true,
				maintainAspectRatio: false,
				
				tooltips: {
					enabled: false,
					custom: function(tooltip) {
						var tooltipEl = document.getElementById('organicPaidFbvideoreachtotal');


				        // Hide if no tooltip
				        if (tooltip.opacity === 0) {
				        	tooltipEl.style.color = "#464950";
				        	$('#organicPaidFbvideoreachtotal div p').html("Total "+ "</br>"+ $('#organicPaidFbvideoreachtotal').attr('data-value'));
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
				        		var dataToPercent = (dataValNum / sumOfDataVal(organicPaidVideoReachFbDoughnutconfig) * 100).toFixed(2) + '%';
				        		
				        		var numberFormat =  shortNumber(dataValNum);
				        		innerHtml += textVal+'</br>'+numberFormat+'</br>'+dataToPercent;
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
		}

		//view key graph
		function organicPaidVideoReachFbDoughnutview(response){
			if(window.myDoughnutorganicPaidvideoReachview){
				window.myDoughnutorganicPaidvideoReachview.destroy();
			}
			var doughnutorganicPaidvideoReachview = document.getElementById('organicPaidFbvideoreachview').getContext('2d');
			window.myDoughnutorganicPaidvideoReachview = new Chart(doughnutorganicPaidvideoReachview, organicPaidVideoReachFbDoughnutconfigview);

			if(response.countOrganic == 0 && response.countPaid == 0){
				organicPaidVideoReachFbDoughnutconfigview.data.datasets[0].backgroundColor = [];
				organicPaidVideoReachFbDoughnutconfigview.data.datasets[0].borderColor = [];
			}else{
				organicPaidVideoReachFbDoughnutconfigview.data.datasets[0].backgroundColor = ['#369cfd','#f5c633'];
				organicPaidVideoReachFbDoughnutconfigview.data.datasets[0].borderColor = ['#369cfd','#f5c633'];
			}

			organicPaidVideoReachFbDoughnutconfigview.data.datasets[0].data = response.data;
			window.myDoughnutorganicPaidvideoReachview.update();
		}

		var organicPaidVideoReachFbDoughnutconfigview = {
			type: 'doughnut',
			data: {
				datasets: [{
					data: [],
					backgroundColor: [],
					borderColor: [],
					borderWidth: 1
				}],
				labels: ["Organic", "Paid"]
			},
			options: {
				legend: {
					display: false
				},
				cutoutPercentage: 75,
				animation: {
					animateScale: true,
					animateRotate: true
				},
				circumference: 2 * Math.PI,
				responsive: true,
				maintainAspectRatio: false,
				
				tooltips: {
					enabled: false,
					custom: function(tooltip) {
						var tooltipEl = document.getElementById('organicPaidFbvideoreachtotalview');


				        // Hide if no tooltip
				        if (tooltip.opacity === 0) {
				        	tooltipEl.style.color = "#464950";
				        	$('#organicPaidFbvideoreachtotalview div p').html("Total "+ "</br>"+ $('#organicPaidFbvideoreachtotalview').attr('data-value'));
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
				        		var dataToPercent = (dataValNum / sumOfDataVal(organicPaidVideoReachFbDoughnutconfigview) * 100).toFixed(2) + '%';
				        		
				        		var numberFormat =  shortNumber(dataValNum);
				        		innerHtml += textVal+'</br>'+numberFormat+'</br>'+dataToPercent;
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
		}


		var genderReachFbBarChartConfig = {
			type: 'bar',
			data: {
				labels: ['13-17', '18-24', '25-34', '35-44', '45-54', '55-64', '65'],
				datasets: [{
					label: 'Current',
					data: [],
					backgroundColor: [
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd'
					],
					borderColor: [
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd'
					],
					borderWidth: 1,
					maxBarThickness:25
				}]
			},
			options: {
				maintainAspectRatio: false,
				scales: {
					xAxes: [{
						gridLines:{
							display:false
						}
					}],
					yAxes: [{
						ticks: {
							beginAtZero: true
						},
						beforeBuildTicks: function(axis) { 
							var stepSize = parseInt(axis.max/5);    
							if(stepSize >= 100 && stepSize <= 1000){
								axis.options.ticks.stepSize =  Math.ceil(stepSize/100)*100;
							}else if(stepSize >= 1000 && stepSize <= 10000){
								axis.options.ticks.stepSize =  Math.ceil(stepSize/1000)*1000;
							}else if(stepSize >= 10000 && stepSize <= 100000){
								axis.options.ticks.stepSize =  Math.ceil(stepSize/10000)*10000;
							}else{
								axis.options.ticks.stepSize =  stepSize;
							}
						}
					}]
				},
				legend: {
					display: false,
					labels: {
						boxWidth: 10
					}
				},
				tooltips:{
					backgroundColor:'rgb(255, 255, 255)',
					titleFontColor:'#000',
					bodyFontStyle: 'bold',
					callbacks: {
						labelTextColor: function(context) {
							return '#000';
						},
		    			label: function(tooltipItem, data) {
	    					const label = data.datasets[tooltipItem.datasetIndex].label;
					        const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
					        const sign = value >= 0 ? '' : '';
					        return `${label}: ${sign}${value.toLocaleString("en-US")}`;
				        }
					}
				}
			}
		}


		function genderReachFbBarChart(response){
			if(window.barReachgenderGraphFacebook){
				window.barReachgenderGraphFacebook.destroy();
			}

			var ctxgenderReachFbBarChart = document.getElementById('genderReachData').getContext('2d');
			window.barReachgenderGraphFacebook = new Chart(ctxgenderReachFbBarChart, genderReachFbBarChartConfig);
			genderReachFbBarChartConfig.data.datasets[0].data = response.age;

			if(response.comparison == 1 || response.comparison == '1'){
				genderReachFbBarChartConfig.data.datasets.splice(1,1);

				var dataset_1 = {
					label: 'Previous',
					data: response.previousage,
					backgroundColor: [
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633'
					],
					orderColor: [
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633'
					],
					borderWidth: 1,
					maxBarThickness:25
				}
				genderReachFbBarChartConfig.data.datasets.push(dataset_1);
			}else{
				genderReachFbBarChartConfig.data.datasets.splice(1,1);
			}

			window.barReachgenderGraphFacebook.update();
		}


		//view key graph
		var genderReachFbBarChartConfigView = {
			type: 'bar',
			data: {
				labels: ['13-17', '18-24', '25-34', '35-44', '45-54', '55-64', '65'],
				datasets: [{
					label: 'Current',
					data: [],
					backgroundColor: [
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd'
					],
					borderColor: [
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd',
					'#369cfd'
					],
					borderWidth: 1,
					maxBarThickness:25
				}]
			},
			options: {
				maintainAspectRatio: false,
				scales: {
					xAxes: [{
						gridLines:{
							display:false
						}
					}],
					yAxes: [{
						ticks: {
							beginAtZero: true
						},
						beforeBuildTicks: function(axis) { 
							var stepSize = parseInt(axis.max/5);    
							if(stepSize >= 100 && stepSize <= 1000){
								axis.options.ticks.stepSize =  Math.ceil(stepSize/100)*100;
							}else if(stepSize >= 1000 && stepSize <= 10000){
								axis.options.ticks.stepSize =  Math.ceil(stepSize/1000)*1000;
							}else if(stepSize >= 10000 && stepSize <= 100000){
								axis.options.ticks.stepSize =  Math.ceil(stepSize/10000)*10000;
							}else{
								axis.options.ticks.stepSize =  stepSize;
							}
						}
					}]
				},
				legend: {
					display: false,
					labels: {
						boxWidth: 10
					}
				},
				tooltips:{
					backgroundColor:'rgb(255, 255, 255)',
					titleFontColor:'#000',
					bodyFontStyle: 'bold',
					callbacks: {
						labelTextColor: function(context) {
							return '#000';
						},
		    			label: function(tooltipItem, data) {
	    					const label = data.datasets[tooltipItem.datasetIndex].label;
					        const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
					        const sign = value >= 0 ? '' : '';
					        return `${label}: ${sign}${value.toLocaleString("en-US")}`;
				        }
					}
				}
			}
		}


		function genderReachFbBarChartview(response){
			if(window.barReachgenderGraphFacebookview){
				window.barReachgenderGraphFacebookview.destroy();
			}

			var ctxgenderReachFbBarChartview = document.getElementById('genderReachDataview').getContext('2d');
			window.barReachgenderGraphFacebookview = new Chart(ctxgenderReachFbBarChartview, genderReachFbBarChartConfigView);
			genderReachFbBarChartConfigView.data.datasets[0].data = response.age;

			if(response.comparison == 1 || response.comparison == '1'){
				genderReachFbBarChartConfigView.data.datasets.splice(1,1);

				var dataset_1 = {
					label: 'Previous',
					data: response.previousage,
					backgroundColor: [
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633'
					],
					orderColor: [
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633',
					'#f5c633'
					],
					borderWidth: 1,
					maxBarThickness:25
				}
				genderReachFbBarChartConfigView.data.datasets.push(dataset_1);
			}else{
				genderReachFbBarChartConfigView.data.datasets.splice(1,1);
			}

			window.barReachgenderGraphFacebookview.update();
		}


		var reachFbGraphConfig = {
			type: 'line',
			data: {
				labels: [],
				datasets: [{
					label: 'Current',
	            labels: [], // Name the series
	            data: [], // Specify the data values array
	            fill: true,
	            borderColor: '#369cfd', // Add custom color border (Line)
	            backgroundColor: '#d7ebff', // Add custom color background (Points and Fill)
	            borderWidth: 2 // Specify bar border width
	        }]
	    },
	    options: {
	    	responsive: true,
	    	maintainAspectRatio:false,
	    	scales: {
	    		xAxes: [{
	    			ticks: {
	    				autoSkip: true,
	    				maxRotation: 0,
	    				minRotation: 0,
	    				maxTicksLimit: 4
	    			},
	    			gridLines: {
	    				color: "rgba(0, 0, 0, 0)"
	    			}
	    		}],
	    		yAxes:[{
	    			beforeBuildTicks: function(axis) { 
	    				var stepSize = parseInt(axis.max/5);    
	    				if(stepSize >= 100 && stepSize <= 1000){
	    					axis.options.ticks.stepSize =  Math.ceil(stepSize/100)*100;
	    				}else if(stepSize >= 1000 && stepSize <= 10000){
	    					axis.options.ticks.stepSize =  Math.ceil(stepSize/1000)*1000;
	    				}else if(stepSize >= 10000 && stepSize <= 100000){
	    					axis.options.ticks.stepSize =  Math.ceil(stepSize/10000)*10000;
	    				}else{
	    					axis.options.ticks.stepSize =  stepSize;
	    				}
	    			}
	    		}]
	    	},
	    	tooltips: {
	    		mode: 'index',
	    		intersect: false,
	    		backgroundColor:'rgb(255, 255, 255)',
	    		titleFontColor:'#000',
	    		bodyFontStyle: 'bold',
	    		callbacks: {
	    			labelTextColor: function(context) {
	    				return '#000';
	    			},
	    			label: function(tooltipItem, data) {
    					const label = data.datasets[tooltipItem.datasetIndex].label;
				        const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
				        const sign = value >= 0 ? '' : '';
				        return `${label}: ${sign}${value.toLocaleString("en-US")}`;
			        }
	    		}
	    	},
	    	legend: {
	    		align: 'center',
	    		display:false,
	    		labels: {
	    			boxWidth: 10
	    		}
	    	},
	    	elements: {
	    		point:{
	    			radius: 0,
	    			hitRadius :1
	    		},

	    	},
	    }
	}

	function reachFbGraph(response){
		if(window.reachFbGraphFacebook){
			window.reachFbGraphFacebook.destroy();
		}

		var ctxreachFbGraph = document.getElementById('reachGraph').getContext('2d');
		window.reachFbGraphFacebook = new Chart(ctxreachFbGraph, reachFbGraphConfig);
		reachFbGraphConfig.data.labels =  response.labels;
		reachFbGraphConfig.data.datasets[0].labels = response.labels;
		reachFbGraphConfig.data.datasets[0].data = response.data;

		if(response.comparison == 1 || response.comparison == '1'){
			reachFbGraphConfig.data.datasets.splice(1,1);

			var dataset_1 = {
				label: 'Previous',
				labels: response.previous_labels,
				data: response.previous_data, 
				fill: true,
				borderColor: '#f5c633', 
				backgroundColor: '#fdf3d5', 
				borderWidth: 2
			}

			reachFbGraphConfig.data.datasets.push(dataset_1);
		}else{
			reachFbGraphConfig.data.datasets.splice(1,1);
		}
		window.reachFbGraphFacebook.update();
	}

	//view key graph
	var reachFbGraphConfigView = {
			type: 'line',
			data: {
				labels: [],
				datasets: [{
				label: 'Current',
	            labels: [], // Name the series
	            data: [], // Specify the data values array
	            fill: true,
	            borderColor: '#369cfd', // Add custom color border (Line)
	            backgroundColor: '#d7ebff', // Add custom color background (Points and Fill)
	            borderWidth: 2 // Specify bar border width
	        }]
	    },
	    options: {
	    	responsive: true,
	    	maintainAspectRatio:false,
	    	scales: {
	    		xAxes: [{
	    			ticks: {
	    				autoSkip: true,
	    				maxRotation: 0,
	    				minRotation: 0,
	    				maxTicksLimit: 4
	    			},
	    			gridLines: {
	    				color: "rgba(0, 0, 0, 0)"
	    			}
	    		}],
	    		yAxes:[{
	    			beforeBuildTicks: function(axis) { 
	    				var stepSize = parseInt(axis.max/5);    
	    				if(stepSize >= 100 && stepSize <= 1000){
	    					axis.options.ticks.stepSize =  Math.ceil(stepSize/100)*100;
	    				}else if(stepSize >= 1000 && stepSize <= 10000){
	    					axis.options.ticks.stepSize =  Math.ceil(stepSize/1000)*1000;
	    				}else if(stepSize >= 10000 && stepSize <= 100000){
	    					axis.options.ticks.stepSize =  Math.ceil(stepSize/10000)*10000;
	    				}else{
	    					axis.options.ticks.stepSize =  stepSize;
	    				}
	    			}
	    		}]
	    	},
	    	tooltips: {
	    		mode: 'index',
	    		intersect: false,
	    		backgroundColor:'rgb(255, 255, 255)',
	    		titleFontColor:'#000',
	    		bodyFontStyle: 'bold',
	    		callbacks: {
	    			labelTextColor: function(context) {
	    				return '#000';
	    			},
	    			label: function(tooltipItem, data) {
    					const label = data.datasets[tooltipItem.datasetIndex].label;
				        const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
				        const sign = value >= 0 ? '' : '';
				        return `${label}: ${sign}${value.toLocaleString("en-US")}`;
			        }
	    		}
	    	},
	    	legend: {
	    		align: 'center',
	    		display:false,
	    		labels: {
	    			boxWidth: 10
	    		}
	    	},
	    	elements: {
	    		point:{
	    			radius: 0,
	    			hitRadius :1
	    		},

	    	},
	    }
	}

	function reachFbGraphView(response){
		if(window.reachFbGraphFacebookView){
			window.reachFbGraphFacebookView.destroy();
		}

		var ctxreachFbGraphView = document.getElementById('reachFbGraphView').getContext('2d');
		window.reachFbGraphFacebookView = new Chart(ctxreachFbGraphView, reachFbGraphConfigView);
		reachFbGraphConfigView.data.labels =  response.labels;
		reachFbGraphConfigView.data.datasets[0].labels = response.labels;
		reachFbGraphConfigView.data.datasets[0].data = response.data;

		if(response.comparison == 1 || response.comparison == '1'){
			reachFbGraphConfigView.data.datasets.splice(1,1);

			var dataset_1 = {
				label: 'Previous',
				labels: response.previous_labels,
				data: response.previous_data, 
				fill: true,
				borderColor: '#f5c633', 
				backgroundColor: '#fdf3d5', 
				borderWidth: 2
			}

			reachFbGraphConfigView.data.datasets.push(dataset_1);
		}else{
			reachFbGraphConfigView.data.datasets.splice(1,1);
		}
		window.reachFbGraphFacebookView.update();
	}

	

	var genderReachFbStackedChartConfig = {
		type: 'line',
		data: {
			labels: [],
			datasets: [
			{
				labels: [],
		            label: 'Male', // Name the series
		            data: [], // Specify the data values array
		            fill: true,
		            borderColor: '#369cfd', // Add custom color border (Line)
		            backgroundColor: '#d7ebff', // Add custom color background (Points and Fill)
		            borderWidth: 2// Specif y bar border width
		        },
		        {
		            label: 'Female', // Name the series
		            data: [], // Specify the data values array
		            fill: true,
		            borderColor: '#f3b0d0', // Add custom color border (Line)
		            backgroundColor: '#f3b0d0', // Add custom color background (Points and Fill)
		            borderWidth: 2 // Specify bar border width
		        },
		        {
		            label: 'Unspecified', // Name the series
		            data: [], // Specify the data values array
		            fill: true,
		            borderColor: '#cac9c9', // Add custom color border (Line)
		            backgroundColor: '#cac9c9', // Add custom color background (Points and Fill)
		            borderWidth: 2 // Specify bar border width
		        }
		        ]
		    },
		    options: {
		    	responsive: true,
		    	maintainAspectRatio:false,
		    	scales: {
		    		xAxes: [{
		    			ticks: {
		    				autoSkip: true,
		    				maxRotation: 0,
		    				minRotation: 0,
		    				maxTicksLimit: 4
		    			},
		    			gridLines: {
		    				color: "rgba(0, 0, 0, 0)"
		    			}
		    		}],
		    		yAxes:[{
		    			ticks: {
		    				beginAtZero:true,
		    			},
		    			beforeBuildTicks: function(axis) { 
		    				var stepSize = parseInt(axis.max/5);    
		    				if(stepSize >= 100 && stepSize <= 1000){
		    					axis.options.ticks.stepSize =  Math.ceil(stepSize/100)*100;
		    				}else if(stepSize >= 1000 && stepSize <= 10000){
		    					axis.options.ticks.stepSize =  Math.ceil(stepSize/1000)*1000;
		    				}else if(stepSize >= 10000 && stepSize <= 100000){
		    					axis.options.ticks.stepSize =  Math.ceil(stepSize/10000)*10000;
		    				}else{
		    					axis.options.ticks.stepSize =  stepSize;
		    				}
		    			}
		    		}]
		    	},
		    	tooltips: {
		    		mode: 'index',
		    		intersect: false,
		    		backgroundColor:'rgb(255, 255, 255)',
		    		titleFontColor:'#000',
		    		bodyFontStyle: 'bold',  
		    		callbacks: {
		    			labelTextColor: function(context) {
		    				return '#000';
		    			},
		    			title: function() {},
		    			beforeLabel: function(tooltipItem, data) {
		    				if(tooltipItem.datasetIndex === 0){
		    					return data.datasets[0].labels[tooltipItem.index];
		    				}
		    				if(tooltipItem.datasetIndex === 3){
		    					return data.datasets[3].labels[tooltipItem.index];  
		    				}
		    			},
		    			label: function(tooltipItem, data) {
	    					const label = data.datasets[tooltipItem.datasetIndex].label;
					        const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
					        const sign = value >= 0 ? '' : '';
					        return `${label}: ${sign}${value.toLocaleString("en-US")}`;
				        }
		    		}
		    	},
		    	legend: {
		    		align: 'center',
		    		display:true,
		    		labels: {
		    			boxWidth: 10
		    		}
		    	},
		    	elements: {
		    		point:{
		    			radius: 0,
		    			hitRadius :1
		    		},

		    	},
		    }

		}


		function genderReachFbStackedChart(response){
			if(window.genderReachFbStackedCharts){
				window.genderReachFbStackedCharts.destroy();
			}

			var ctxrgenderReachFbStackedChar = document.getElementById('reachFbstacked').getContext('2d');
			window.genderReachFbStackedCharts = new Chart(ctxrgenderReachFbStackedChar, genderReachFbStackedChartConfig);
			var gradient = gradientColor(ctxrgenderReachFbStackedChar);
			genderReachFbStackedChartConfig.data.labels =  response.labels;
			genderReachFbStackedChartConfig.data.datasets[0].labels = response.labels;
			genderReachFbStackedChartConfig.data.datasets[0].data = response.male;
			genderReachFbStackedChartConfig.data.datasets[1].data = response.female;
			genderReachFbStackedChartConfig.data.datasets[2].data = response.other;

			if(response.comparison == 1 || response.comparison == '1'){
				genderReachFbStackedChartConfig.data.datasets.splice(3,3);
				genderReachFbStackedChartConfig.data.datasets.splice(4,4);
				genderReachFbStackedChartConfig.data.datasets.splice(5,5);

				var dataset_1 = {
					label: 'Male',
					labels: response.previous_labels,
					fill: true,
					borderColor: '#369cfd',
					backgroundColor: '#d7ebff',
					data: response.previous_male,
					borderWidth:2,
					borderDash: [5,5]

				};
				var dataset_2 = {
					label: 'Female',
					fill: true,
					borderColor: '#f3b0d0',
					backgroundColor: '#FDF7FA',
					data: response.previous_female,
					borderWidth:2,
					borderDash: [5,5]

				};

				var dataset_3 = {
					label: 'Unsepecified',
					fill: true,
					borderColor: color(window.chartColors.grey).alpha(1.0).rgbString(),
					backgroundColor: color(window.chartColors.grey).alpha(0.15).rgbString(),
					data: response.previous_other,
					borderWidth:2,
					borderDash: [5,5]

				};

				genderReachFbStackedChartConfig.data.datasets.push(dataset_1);
				genderReachFbStackedChartConfig.data.datasets.push(dataset_2);
				genderReachFbStackedChartConfig.data.datasets.push(dataset_3);
			}else{
				genderReachFbStackedChartConfig.data.datasets.splice(3,3);
				genderReachFbStackedChartConfig.data.datasets.splice(4,4);
				genderReachFbStackedChartConfig.data.datasets.splice(5,5);
			}

			window.genderReachFbStackedCharts.update();
		}

		//view key graph
		var genderReachFbStackedChartConfigview = {
		type: 'line',
		data: {
			labels: [],
			datasets: [
			{
				labels: [],
		            label: 'Male', // Name the series
		            data: [], // Specify the data values array
		            fill: true,
		            borderColor: '#369cfd', // Add custom color border (Line)
		            backgroundColor: '#d7ebff', // Add custom color background (Points and Fill)
		            borderWidth: 2// Specif y bar border width
		        },
		        {
		            label: 'Female', // Name the series
		            data: [], // Specify the data values array
		            fill: true,
		            borderColor: '#f3b0d0', // Add custom color border (Line)
		            backgroundColor: '#f3b0d0', // Add custom color background (Points and Fill)
		            borderWidth: 2 // Specify bar border width
		        },
		        {
		            label: 'Unspecified', // Name the series
		            data: [], // Specify the data values array
		            fill: true,
		            borderColor: '#cac9c9', // Add custom color border (Line)
		            backgroundColor: '#cac9c9', // Add custom color background (Points and Fill)
		            borderWidth: 2 // Specify bar border width
		        }
		        ]
		    },
		    options: {
		    	responsive: true,
		    	maintainAspectRatio:false,
		    	scales: {
		    		xAxes: [{
		    			ticks: {
		    				autoSkip: true,
		    				maxRotation: 0,
		    				minRotation: 0,
		    				maxTicksLimit: 4
		    			},
		    			gridLines: {
		    				color: "rgba(0, 0, 0, 0)"
		    			}
		    		}],
		    		yAxes:[{
		    			ticks: {
		    				beginAtZero:true,
		    			},
		    			beforeBuildTicks: function(axis) { 
		    				var stepSize = parseInt(axis.max/5);    
		    				if(stepSize >= 100 && stepSize <= 1000){
		    					axis.options.ticks.stepSize =  Math.ceil(stepSize/100)*100;
		    				}else if(stepSize >= 1000 && stepSize <= 10000){
		    					axis.options.ticks.stepSize =  Math.ceil(stepSize/1000)*1000;
		    				}else if(stepSize >= 10000 && stepSize <= 100000){
		    					axis.options.ticks.stepSize =  Math.ceil(stepSize/10000)*10000;
		    				}else{
		    					axis.options.ticks.stepSize =  stepSize;
		    				}
		    			}
		    		}]
		    	},
		    	tooltips: {
		    		mode: 'index',
		    		intersect: false,
		    		backgroundColor:'rgb(255, 255, 255)',
		    		titleFontColor:'#000',
		    		bodyFontStyle: 'bold',  
		    		callbacks: {
		    			labelTextColor: function(context) {
		    				return '#000';
		    			},
		    			title: function() {}
		    			,
		    			beforeLabel: function(tooltipItem, data) {
		    				if(tooltipItem.datasetIndex === 0){
		    					return data.datasets[0].labels[tooltipItem.index];
		    				}
		    				if(tooltipItem.datasetIndex === 3){
		    					return data.datasets[3].labels[tooltipItem.index];  
		    				}
		    			},
		    			label: function(tooltipItem, data) {
	    					const label = data.datasets[tooltipItem.datasetIndex].label;
					        const value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
					        const sign = value >= 0 ? '' : '';
					        return `${label}: ${sign}${value.toLocaleString("en-US")}`;
				        }
		    		}
		    	},
		    	legend: {
		    		align: 'center',
		    		display:true,
		    		labels: {
		    			boxWidth: 10
		    		}
		    	},
		    	elements: {
		    		point:{
		    			radius: 0,
		    			hitRadius :1
		    		},

		    	},
		    }

		}


		function genderReachFbStackedChartview(response){
			if(window.genderReachFbStackedChartsview){
				window.genderReachFbStackedChartsview.destroy();
			}

			var ctxrgenderReachFbStackedCharview = document.getElementById('reachFbstackedview').getContext('2d');
			window.genderReachFbStackedChartsview = new Chart(ctxrgenderReachFbStackedCharview, genderReachFbStackedChartConfigview);
			var gradient = gradientColor(ctxrgenderReachFbStackedCharview);
			genderReachFbStackedChartConfigview.data.labels =  response.labels;
			genderReachFbStackedChartConfigview.data.datasets[0].labels = response.labels;
			genderReachFbStackedChartConfigview.data.datasets[0].data = response.male;
			genderReachFbStackedChartConfigview.data.datasets[1].data = response.female;
			genderReachFbStackedChartConfigview.data.datasets[2].data = response.other;

			if(response.comparison == 1 || response.comparison == '1'){
				genderReachFbStackedChartConfigview.data.datasets.splice(3,3);
				genderReachFbStackedChartConfigview.data.datasets.splice(4,4);
				genderReachFbStackedChartConfigview.data.datasets.splice(5,5);

				var dataset_1 = {
					label: 'Male',
					labels: response.previous_labels,
					fill: true,
					borderColor: '#369cfd',
					backgroundColor: '#369cfd',
					data: response.previous_male,
					borderWidth:2,
					borderDash: [5,5]

				};
				var dataset_2 = {
					label: 'Female',
					fill: true,
					borderColor: '#f3b0d0',
					backgroundColor: '#f3b0d0',
					data: response.previous_female,
					borderWidth:2,
					borderDash: [5,5]

				};

				var dataset_3 = {
					label: 'Unsepecified',
					fill: true,
					borderColor: color(window.chartColors.grey).alpha(1.0).rgbString(),
					backgroundColor: color(window.chartColors.grey).alpha(0.15).rgbString(),
					data: response.previous_other,
					borderWidth:2,
					borderDash: [5,5]

				};

				genderReachFbStackedChartConfigview.data.datasets.push(dataset_1);
				genderReachFbStackedChartConfigview.data.datasets.push(dataset_2);
				genderReachFbStackedChartConfigview.data.datasets.push(dataset_3);
			}else{
				genderReachFbStackedChartConfigview.data.datasets.splice(3,3);
				genderReachFbStackedChartConfigview.data.datasets.splice(4,4);
				genderReachFbStackedChartConfigview.data.datasets.splice(5,5);
			}

			window.genderReachFbStackedChartsview.update();
		}


		/*DateRange Filter*/
		$(document).on("click","#facebook_dateRange", function (e) {
			$("#facebook-dateRange-popup").toggleClass("show");
			if($('.facebook_compare').is(':not(:checked)')){
				$('#facebook-previous-section').hide();
				$('#facebook_comparison').attr('disabled','disabled');
			}

			setTimeout(function(){
				var start_date = $('.facebook_start_date').val();
				var end_date = $('.facebook_end_date').val();
				var start = moment(start_date);
				var end = moment(end_date);
				var label = $('.facebook_current_label').val();

				$('#facebook_current_range').daterangepicker({
					minDays : 2,
					startDate: start,
					endDate: end,
					alwaysShowCalendars:true,
					autoApply:true,
					minDate: moment().subtract(2, 'year').subtract(1, 'days'),
					maxDate: new Date(),
					ranges: {
						'One Month': [moment().subtract(1, 'month').subtract(1, 'days'), moment().subtract(1, 'days')],
						'Three Month': [moment().subtract(3, 'month').subtract(1, 'days'), moment().subtract(1, 'days')],
						'Six Month': [moment().subtract(6, 'month').subtract(1, 'days'), moment().subtract(1, 'days')],
						'Nine Month': [moment().subtract(9, 'month').subtract(1, 'days'), moment().subtract(1, 'days')],
						'One Year': [moment().subtract(1, 'year').subtract(1, 'days'), moment().subtract(1, 'days')],
						'Two Year': [moment().subtract(2, 'year').subtract(1, 'days'), moment().subtract(1, 'days')]
					}
				}, facebook_current_picker);
				facebook_current_picker(start, end,label);
			},100);
		});


		function facebook_current_picker(start, end,label = null) { 
			var new_start = start.format('YYYY-MM-DD');
			var new_end = end.format('YYYY-MM-DD');
			$('.facebook_start_date').val(new_start);
			$('.facebook_end_date').val(new_end);
			if(label !== null){
				$('.facebook_current_label').val(label);
			}else{
				$('.facebook_current_label').val('');
			}
			$('#facebook_current_range p').html(new_start + ' - ' + new_end);

			var days = date_diff_indays(new_start, new_end);
			$('.facebook_comparison_days').val(days);
			if($('#facebook_comparison').val() === 'previous_period'){
				var prev_start_date = getdate(new_start, (days-1));
				var prev_end_date = getdate(new_start, -1);
				var prev_days = date_diff_indays(prev_start_date, prev_end_date);
				$('.facebook_prev_start_date').val(prev_start_date);
				$('.facebook_prev_end_date').val(prev_end_date);
				$('.facebook_prev_comparison_days').val(prev_days);
				fbinitialisePreviousCalendar(prev_start_date,prev_end_date);
			}else if($('#facebook_comparison').val() === 'previous_year'){
				var prev_sd = createPreviousYear(new_start);
				var prev_ed = createPreviousYear(new_end);
				$('.facebook_prev_start_date').val(prev_sd);
				$('.facebook_prev_end_date').val(prev_ed);
				fbinitialisePreviousCalendar(prev_sd,prev_ed);
			}
		}


		function fbinitialisePreviousCalendar(prev_start,prev_end){
			facebook_previous_picker(prev_start, prev_end);
		}


		function facebook_previous_picker(start, end) {
			var prev_sd = getdate(start,0);
			var prev_ed = getdate(end,0);
			$('.facebook_prev_start_date').val(prev_sd);
			$('.facebook_prev_end_date').val(prev_ed);
			var days = date_diff_indays(prev_sd, prev_ed);
			$('.facebook_prev_comparison_days').val(days);
			$('#facebook_previous_range p').html(prev_sd + ' - ' + prev_ed);
		}


		$(document).on('change','.facebook_compare',function(e){
			e.preventDefault();
			var compare_status = $(this).is(':checked');
			if(compare_status === true){
				$('#facebook-previous-section').show();
				$('#facebook_comparison').removeAttr('disabled','disabled');
			}else{
				$('#facebook-previous-section').hide();
				$('#facebook_comparison').attr('disabled','disabled');
			}
		});


		$(document).on('change','#facebook_comparison',function(e){
			e.preventDefault();
			var new_start = $('.facebook_start_date').val();
			var new_end = $('.facebook_end_date').val();
			if($('#facebook_comparison').val() === 'previous_period'){
				var days = date_diff_indays(new_start, new_end);
				var prev_start_date = getdate(new_start, (days-1));
				var prev_end_date = getdate(new_start, -1);
				$('.facebook_prev_start_date').val(prev_start_date);
				$('.facebook_prev_end_date').val(prev_end_date);
				$('.facebook_comparison_days').val(days);

				fbinitialisePreviousCalendar(prev_start_date,prev_end_date);
			}else if($('#facebook_comparison').val() === 'previous_year'){
				var prev_sd = createPreviousYear(new_start);
				var prev_ed = createPreviousYear(new_end);
				var days = date_diff_indays(prev_sd, prev_ed);
				$('.facebook_prev_start_date').val(prev_sd);
				$('.facebook_prev_end_date').val(prev_ed);
				$('.facebook_prev_comparison_days').val(days);
				fbinitialisePreviousCalendar(prev_sd,prev_ed);
			} 
		});

		$(document).on('click','.facebook_cancel_btn',function(e){
			e.preventDefault();
			$("#facebook-dateRange-popup").toggleClass("show");
		});

		$(document).on('click','.facebook_apply_btn',function(e){
			var selected_label = $('.facebook_current_label').val();
			var current_comparison = $('.facebook_comparison_days').val();
			var previous_comparison = $('.facebook_prev_comparison_days').val();
			if(current_comparison !== previous_comparison){
				$('#facebook_previous_range').addClass('error');
				Command: toastr["error"]('Select equal number of days for comparison');
			}else{
				var current_start = $('.facebook_start_date').val();
				var current_end = $('.facebook_end_date').val();
				var previous_start = $('.facebook_prev_start_date').val();
				var previous_end = $('.facebook_prev_end_date').val();
				if($('.facebook_compare').is(':checked') === true){
					var comparison = 1;
				}else{
					var comparison = 0;
				}

				var comparison_selected = $('#facebook_comparison').val();

				var campaignId = $('.campaignID').val();
				var viewkey = 0;
				if($('#Social').find('.main-data-view').hasClass('social-view-data')){
					var viewkey = 1;
				}

				var jsonData = {module:'facebook',campaignId,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label,_token:$('meta[name="csrf-token"]').attr('content'),viewkey}; 
				$('.likes-count,.likes-percent-loader,.facebook_organicpaidlikes_loader,.facebook_genderlikes_loader,.facebook_agelikes_loader,.facebook_countrylikes_loader,.facebook_citylikes_loader,.facebook_languagelikes_loader,.facebook_totalreach_loader,.facebook_organicpaidreach_loader,.facebook_organicpaidvideoreach_loader,.facebook_genderreach_loader,.facebook_agereach_loader,.facebook_countryreach_loader,.facebook_cityreach_loader,.facebook_languagereach_loader,.full_picture,.fromImage,.fromName,.datePost,.postMessage,.fb_post_ul,.postMessage,.reviewerName,.reviewDate,.review-rating,.reviewText,.post-empty,.review-empty,.review_image,.reviewerImage,.table-data').addClass('ajax-loader');
				$('#reach_count,#reach_count_view,#fbCountryLikesTable,#fbCitiesLikesTable,#fbLanguageLikesTable,#fbCountryReachTable,#fbCitiesReachTable,#fbLanguageReachTable,#fbViewCountryLikesTable,#fbViewCitiesLikesTable,#fbViewLanguageLikesTable').addClass('ajax-loader');
				$.ajax({
					type:"POST",
					url:BASE_URL+"/facebook_search",
					data:jsonData,
					dataType:'json',
					success:function(response){
						if(response.status == 0){
							Command: toastr["error"]('Please try again getting error');
						}else{
							facebookScripts(jsonData);
						}
					}
				});
			}
		});

		$(document).on('click','#refreshFacebookData',function(e){
			e.preventDefault();
			var campaignId = $('.campaignID').val();
			$(this).addClass('refresh-gif');

			$('#refreshFacebookData').attr('style', 'cursor:not-allowed');
			$('.likes-count,.likes-percent-loader,.facebook_organicpaidlikes_loader,.facebook_genderlikes_loader,.facebook_agelikes_loader,.facebook_countrylikes_loader,.facebook_citylikes_loader,.facebook_languagelikes_loader,.facebook_totalreach_loader,.facebook_organicpaidreach_loader,.facebook_organicpaidvideoreach_loader,.facebook_genderreach_loader,.facebook_agereach_loader,.facebook_countryreach_loader,.facebook_cityreach_loader,.facebook_languagereach_loader,.full_picture,.fromImage,.fromName,.datePost,.postMessage,.fb_post_ul,.postMessage,.reviewerName,.reviewDate,.review-rating,.reviewText,.post-empty,.review-empty,.review_image,.reviewerImage,.table-data').addClass('ajax-loader');
			$('#reach_count,#reach_count_view,#fbCountryLikesTable,#fbCitiesLikesTable,#fbLanguageLikesTable,#fbCountryReachTable,#fbCitiesReachTable,#fbLanguageReachTable,#fbViewCountryLikesTable,#fbViewCitiesLikesTable,#fbViewLanguageLikesTable').addClass('ajax-loader');
			$.ajax({
				type:'GET',
				url:BASE_URL+'/log_facebook_data',
				data:{campaign_id:campaignId},
				success:function(logresponse){
					$('#refreshFacebookData').prop('disabled', false);
					$('.likes-count,.likes-percent-loader,.facebook_organicpaidlikes_loader,.facebook_genderlikes_loader,.facebook_agelikes_loader,.facebook_countrylikes_loader,.facebook_citylikes_loader,.facebook_languagelikes_loader,.facebook_totalreach_loader,.facebook_organicpaidreach_loader,.facebook_organicpaidvideoreach_loader,.facebook_genderreach_loader,.facebook_agereach_loader,.facebook_countryreach_loader,.facebook_cityreach_loader,.facebook_languagereach_loader,.full_picture,.fromImage,.fromName,.datePost,.postMessage,.fb_post_ul,.postMessage,.reviewerName,.reviewDate,.review-rating,.reviewText,.post-empty,.review-empty,.review_image,.reviewerImage,.table-data').removeClass('ajax-loader');
					$('#reach_count,#reach_count_view,#fbCountryLikesTable,#fbCitiesLikesTable,#fbLanguageLikesTable,#fbCountryReachTable,#fbCitiesReachTable,#fbLanguageReachTable,#fbViewCountryLikesTable,#fbViewCitiesLikesTable,#fbViewLanguageLikesTable').removeClass('ajax-loader');
					if(logresponse.status == 'success'){
						facebookScripts(campaignId);
					}else{
						Command: toastr["error"](logresponse.message);
					}
					$('#refreshFacebookData').removeClass('refresh-gif');
					$('#refreshFacebookData').attr('style', '');
				}
			});

		});


		function sticksSocial_nav() {
			var window_top = $(window).scrollTop();
			var social_nav = $('.tab-head');
			var siteHeader_height = $('header').outerHeight();
			if(social_nav.length > 0){
				var social_nav_offset_top = social_nav.offset().top; 

				if($('#Social').find('.main-data-view').hasClass('social-view-data')){
					var social_nav_offset_percent = social_nav_offset_top; 
					social_nav.css('top', '0px');
				}else{
					var social_nav_offset_percent = social_nav_offset_top - siteHeader_height; 
					social_nav.css('top', ''+siteHeader_height+'px');
				}

				if (window_top >= social_nav_offset_percent) {
					social_nav.addClass('active');
				} else {
					social_nav.removeClass('active');
				}
			}
		}

		$(function() {
			$(window).scroll(sticksSocial_nav);
			sticksSocial_nav();
		});


		
//View key likes daterange picker
$(document).on("click","#facebookLikes_dateRange", function (e) {
	$("#facebook-likes-dateRange-popup").toggleClass("show");
	if($('.facebook_likes_compare').is(':not(:checked)')){
		$('#facebook_likes_previous-section').hide();
		$('#facebook_likes_comparison').attr('disabled','disabled');
	}
	setTimeout(function(){
		var start_date_view = $('.facebook_likes_start_date').val();
		var end_date_view = $('.facebook_likes_end_date').val();
		var start_view = moment(start_date_view);
		var end_view = moment(end_date_view);
		var label_view = $('.facebook_likes_current_label').val();

		$('#facebook_likes_current_range').daterangepicker({
			minDays : 2,
			startDate: start_view,
			endDate: end_view,
			alwaysShowCalendars:true,
			autoApply:true,
			minDate: moment().subtract(2, 'year').subtract(1, 'days'),
			maxDate: new Date(),
			ranges: {
				'One Month': [moment().subtract(1, 'month').subtract(1, 'days'), moment().subtract(1, 'days')],
				'Three Month': [moment().subtract(3, 'month').subtract(1, 'days'), moment().subtract(1, 'days')],
				'Six Month': [moment().subtract(6, 'month').subtract(1, 'days'), moment().subtract(1, 'days')],
				'Nine Month': [moment().subtract(9, 'month').subtract(1, 'days'), moment().subtract(1, 'days')],
				'One Year': [moment().subtract(1, 'year').subtract(1, 'days'), moment().subtract(1, 'days')],
				'Two Year': [moment().subtract(2, 'year').subtract(1, 'days'), moment().subtract(1, 'days')]
			}
		}, facebook_likes_current_picker);
		facebook_likes_current_picker(start_view, end_view,label_view);
	},100);

});


function facebook_likes_current_picker(start_view, end_view,label_view = null) { 
	var new_start_view = start_view.format('YYYY-MM-DD');
	var new_end_view = end_view.format('YYYY-MM-DD');
	$('.facebook_likes_start_date').val(new_start_view);
	$('.facebook_likes_end_date').val(new_end_view);
	if(label_view !== null){
		$('.facebook_likes_current_label').val(label_view);
	}else{
		$('.facebook_likes_current_label').val('');
	}
	$('#facebook_likes_current_range p').html(new_start_view + ' - ' + new_end_view);

	var days_view = date_diff_indays(new_start_view, new_end_view);
	$('.facebook_likes_comparison_days').val(days_view);
	if($('#facebook_likes_comparison').val() === 'previous_period'){
		var prev_start_date_view = getdate(new_start_view, (days_view-1));
		var prev_end_date_view = getdate(new_start_view, -1);
		var prev_days_view = date_diff_indays(prev_start_date_view, prev_end_date_view);
		$('.facebook_likes_prev_start_date').val(prev_start_date_view);
		$('.facebook_likes_prev_end_date').val(prev_end_date_view);
		$('.facebook_likes_prev_comparison_days').val(prev_days_view);
		fblikesinitialisePreviousCalendar(prev_start_date_view,prev_end_date_view);
	}else if($('#facebook_likes_comparison').val() === 'previous_year'){
		var prev_sd_view = createPreviousYear(new_start_view);
		var prev_ed_view = createPreviousYear(new_end_view);
		$('.facebook_likes_prev_start_date').val(prev_sd_view);
		$('.facebook_likes_prev_end_date').val(prev_ed_view);
		fblikesinitialisePreviousCalendar(prev_sd_view,prev_ed_view);
	}
}

function fblikesinitialisePreviousCalendar(prev_start,prev_end){
	facebook_likes_previous_picker(prev_start, prev_end);
}


function facebook_likes_previous_picker(start, end) {
	var prev_sd = getdate(start,0);
	var prev_ed = getdate(end,0);
	$('.facebook_likes_prev_start_date').val(prev_sd);
	$('.facebook_likes_prev_end_date').val(prev_ed);
	var days = date_diff_indays(prev_sd, prev_ed);
	$('.facebook_likes_prev_comparison_days').val(days);
	$('#facebook_likes_previous_range p').html(prev_sd + ' - ' + prev_ed);
}

$(document).on('change','.facebook_likes_compare',function(e){
	e.preventDefault();
	var compare_status = $(this).is(':checked');
	if(compare_status === true){
		$('#facebook_likes_previous-section').show();
		$('#facebook_likes_comparison').removeAttr('disabled','disabled');
	}else{
		$('#facebook_likes_previous-section').hide();
		$('#facebook_likes_comparison').attr('disabled','disabled');
	}
});



$(document).on('change','#facebook_likes_comparison',function(e){
	e.preventDefault();
	var new_start = $('.facebook_likes_start_date').val();
	var new_end = $('.facebook_likes_end_date').val();
	if($('#facebook_likes_comparison').val() === 'previous_period'){
		var days = date_diff_indays(new_start, new_end);
		var prev_start_date = getdate(new_start, (days-1));
		var prev_end_date = getdate(new_start, -1);
		$('.facebook_likes_prev_start_date').val(prev_start_date);
		$('.facebook_likes_prev_end_date').val(prev_end_date);
		$('.facebook_likes_comparison_days').val(days);

		fblikesinitialisePreviousCalendar(prev_start_date,prev_end_date);
	}else if($('#facebook_likes_comparison').val() === 'previous_year'){
		var prev_sd = createPreviousYear(new_start);
		var prev_ed = createPreviousYear(new_end);
		var days = date_diff_indays(prev_sd, prev_ed);
		$('.facebook_likes_prev_start_date').val(prev_sd);
		$('.facebook_likes_prev_end_date').val(prev_ed);
		$('.facebook_likes_prev_comparison_days').val(days);
		fblikesinitialisePreviousCalendar(prev_sd,prev_ed);
	} 
});

$(document).on('click','.facebook_likes_cancel_btn',function(e){
	e.preventDefault();
	$("#facebook-likes-dateRange-popup").toggleClass("show");
});

$(document).on('click','.facebook_likes_apply_btn',function(e){
	var selected_label = $('.facebook_likes_current_label').val();
	var current_comparison = $('.facebook_likes_comparison_days').val();
	var previous_comparison = $('.facebook_likes_prev_comparison_days').val();
	if(current_comparison !== previous_comparison){
		$('#facebook_likes_previous_range').addClass('error');
		Command: toastr["error"]('Select equal number of days for comparison');
	}else{
		var current_start = $('.facebook_likes_start_date').val();
		var current_end = $('.facebook_likes_end_date').val();
		var previous_start = $('.facebook_likes_prev_start_date').val();
		var previous_end = $('.facebook_likes_prev_end_date').val();
		if($('.facebook_likes_compare').is(':checked') === true){
			var comparison = 1;
		}else{
			var comparison = 0;
		}

		var comparison_selected = $('#facebook_likes_comparison').val();

		var campaignId = $('.campaignID').val();
		var viewkey = 0;
		var selected = '#facebookviewlikes';
		if($('#Social').find('.main-data-view').hasClass('social-view-data')){
			var viewkey = 1;
		}

		var jsonData = {module:'facebook',campaignId,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label,_token:$('meta[name="csrf-token"]').attr('content'),viewkey,selected};

		$('.likes-count-view,.likes-percent-loader,.facebook_organicpaidlikes_loader,.facebook_genderlikes_loader,.facebook_agelikes_loader,.facebook_countrylikes_loader,.facebook_citylikes_loader,.facebook_languagelikes_loader,.table-data').addClass('ajax-loader');
		$('#fbViewCountryLikesTable,#fbViewCitiesLikesTable,#fbViewLanguageLikesTable').addClass('ajax-loader');
		$.ajax({
			type:"POST",
			url:BASE_URL+"/facebook_search",
			data:jsonData,
			dataType:'json',
			success:function(response){
				if(response.status == 0){
					Command: toastr["error"]('Please try again getting error');
				}else{
					sendFbAjax('social_date_range_filters',jsonData);
					sendFbAjax('getfblikes',jsonData);
					sendFbAjax('getfborganicpaidlikes',jsonData);
					sendFbAjax('getfbgenderlikes',jsonData);
					sendFbAjax('getfbcountrylikes',jsonData);
					sendFbAjax('getfbcitylikes',jsonData);
					sendFbAjax('getfblanguagelikes',jsonData);
				}
			}
		});
	}
});


//View key reach daterange picker
$(document).on("click","#facebookReach_dateRange", function (e) {
	$("#facebook-reach-dateRange-popup").toggleClass("show");
	if($('.facebook_reach_compare').is(':not(:checked)')){
		$('#facebook_reach_previous-section').hide();
		$('#facebook_reach_comparison').attr('disabled','disabled');
	}
	setTimeout(function(){
		var start_date_view = $('.facebook_reach_start_date').val();
		var end_date_view = $('.facebook_reach_end_date').val();
		var start_view = moment(start_date_view);
		var end_view = moment(end_date_view);
		var label_view = $('.facebook_reach_current_label').val();

		$('#facebook_reach_current_range').daterangepicker({
			minDays : 2,
			startDate: start_view,
			endDate: end_view,
			alwaysShowCalendars:true,
			autoApply:true,
			minDate: moment().subtract(2, 'year').subtract(1, 'days'),
			maxDate: new Date(),
			ranges: {
				'One Month': [moment().subtract(1, 'month').subtract(1, 'days'), moment().subtract(1, 'days')],
				'Three Month': [moment().subtract(3, 'month').subtract(1, 'days'), moment().subtract(1, 'days')],
				'Six Month': [moment().subtract(6, 'month').subtract(1, 'days'), moment().subtract(1, 'days')],
				'Nine Month': [moment().subtract(9, 'month').subtract(1, 'days'), moment().subtract(1, 'days')],
				'One Year': [moment().subtract(1, 'year').subtract(1, 'days'), moment().subtract(1, 'days')],
				'Two Year': [moment().subtract(2, 'year').subtract(1, 'days'), moment().subtract(1, 'days')]
			}
		}, facebook_reach_current_picker);
		facebook_reach_current_picker(start_view, end_view,label_view);
	},100);

});


function facebook_reach_current_picker(start_view, end_view,label_view = null) { 
	var new_start_view = start_view.format('YYYY-MM-DD');
	var new_end_view = end_view.format('YYYY-MM-DD');
	$('.facebook_reach_start_date').val(new_start_view);
	$('.facebook_reach_end_date').val(new_end_view);
	if(label_view !== null){
		$('.facebook_reach_current_label').val(label_view);
	}else{
		$('.facebook_reach_current_label').val('');
	}
	$('#facebook_reach_current_range p').html(new_start_view + ' - ' + new_end_view);

	var days_view = date_diff_indays(new_start_view, new_end_view);
	$('.facebook_reach_comparison_days').val(days_view);
	if($('#facebook_reach_comparison').val() === 'previous_period'){
		var prev_start_date_view = getdate(new_start_view, (days_view-1));
		var prev_end_date_view = getdate(new_start_view, -1);
		var prev_days_view = date_diff_indays(prev_start_date_view, prev_end_date_view);
		$('.facebook_reach_prev_start_date').val(prev_start_date_view);
		$('.facebook_reach_prev_end_date').val(prev_end_date_view);
		$('.facebook_reach_prev_comparison_days').val(prev_days_view);
		fbreachinitialisePreviousCalendar(prev_start_date_view,prev_end_date_view);
	}else if($('#facebook_reach_comparison').val() === 'previous_year'){
		var prev_sd_view = createPreviousYear(new_start_view);
		var prev_ed_view = createPreviousYear(new_end_view);
		$('.facebook_reach_prev_start_date').val(prev_sd_view);
		$('.facebook_reach_prev_end_date').val(prev_ed_view);
		fbreachinitialisePreviousCalendar(prev_sd_view,prev_ed_view);
	}
}

function fbreachinitialisePreviousCalendar(prev_start,prev_end){
	facebook_reach_previous_picker(prev_start, prev_end);
}


function facebook_reach_previous_picker(start, end) {
	var prev_sd = getdate(start,0);
	var prev_ed = getdate(end,0);
	$('.facebook_reach_prev_start_date').val(prev_sd);
	$('.facebook_reach_prev_end_date').val(prev_ed);
	var days = date_diff_indays(prev_sd, prev_ed);
	$('.facebook_reach_prev_comparison_days').val(days);
	$('#facebook_reach_previous_range p').html(prev_sd + ' - ' + prev_ed);
}

$(document).on('change','.facebook_reach_compare',function(e){
	e.preventDefault();
	var compare_status = $(this).is(':checked');
	if(compare_status === true){
		$('#facebook_reach_previous-section').show();
		$('#facebook_reach_comparison').removeAttr('disabled','disabled');
	}else{
		$('#facebook_reach_previous-section').hide();
		$('#facebook_reach_comparison').attr('disabled','disabled');
	}
});



$(document).on('change','#facebook_reach_comparison',function(e){
	e.preventDefault();
	var new_start = $('.facebook_reach_start_date').val();
	var new_end = $('.facebook_reach_end_date').val();
	if($('#facebook_reach_comparison').val() === 'previous_period'){
		var days = date_diff_indays(new_start, new_end);
		var prev_start_date = getdate(new_start, (days-1));
		var prev_end_date = getdate(new_start, -1);
		$('.facebook_reach_prev_start_date').val(prev_start_date);
		$('.facebook_reach_prev_end_date').val(prev_end_date);
		$('.facebook_reach_comparison_days').val(days);

		fbreachinitialisePreviousCalendar(prev_start_date,prev_end_date);
	}else if($('#facebook_reach_comparison').val() === 'previous_year'){
		var prev_sd = createPreviousYear(new_start);
		var prev_ed = createPreviousYear(new_end);
		var days = date_diff_indays(prev_sd, prev_ed);
		$('.facebook_reach_prev_start_date').val(prev_sd);
		$('.facebook_reach_prev_end_date').val(prev_ed);
		$('.facebook_reach_prev_comparison_days').val(days);
		fbreachinitialisePreviousCalendar(prev_sd,prev_ed);
	} 
});

$(document).on('click','.facebook_reach_cancel_btn',function(e){
	e.preventDefault();
	$("#facebook-reach-dateRange-popup").toggleClass("show");
});

$(document).on('click','.facebook_reach_apply_btn',function(e){
	var selected_label = $('.facebook_reach_current_label').val();
	var current_comparison = $('.facebook_reach_comparison_days').val();
	var previous_comparison = $('.facebook_reach_prev_comparison_days').val();
	if(current_comparison !== previous_comparison){
		$('#facebook_reach_previous_range').addClass('error');
		Command: toastr["error"]('Select equal number of days for comparison');
	}else{
		var current_start = $('.facebook_reach_start_date').val();
		var current_end = $('.facebook_reach_end_date').val();
		var previous_start = $('.facebook_reach_prev_start_date').val();
		var previous_end = $('.facebook_reach_prev_end_date').val();
		if($('.facebook_reach_compare').is(':checked') === true){
			var comparison = 1;
		}else{
			var comparison = 0;
		}

		var comparison_selected = $('#facebook_reach_comparison').val();

		var campaignId = $('.campaignID').val();
		var viewkey = 0;
		var selected = '#facebookviewreach';
		if($('#Social').find('.main-data-view').hasClass('social-view-data')){
			var viewkey = 1;
		}

		var jsonData = {module:'facebook',campaignId,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label,_token:$('meta[name="csrf-token"]').attr('content'),viewkey,selected};

		$('.facebook_totalreach_loader,.facebook_organicpaidreach_loader,.facebook_organicpaidvideoreach_loader,.facebook_genderreach_loader,.facebook_agereach_loader,.facebook_countryreach_loader,.facebook_cityreach_loader,.facebook_languagereach_loader,.table-data').addClass('ajax-loader');
		$('#reach_count,#reach_count_view,#fbCountryReachTableview,#fbCitiesReachTableview,#fbLanguageReachTableview').addClass('ajax-loader');
		$.ajax({
			type:"POST",
			url:BASE_URL+"/facebook_search",
			data:jsonData,
			dataType:'json',
			success:function(response){
				if(response.status == 0){
					Command: toastr["error"]('Please try again getting error');
				}else{
					sendFbAjax('social_date_range_filters',jsonData);
					sendFbAjax('getfbreach',jsonData);
					sendFbAjax('getfborganicpaidreach',jsonData);
					sendFbAjax('getfborganicpaidvideoreach',jsonData);
					sendFbAjax('getfbgenderreach',jsonData);
					sendFbAjax('getfbcountryreach',jsonData);
					sendFbAjax('getfbcityreach',jsonData);
					sendFbAjax('getfblanguagereach',jsonData);
				}
			}
		});
	}
});