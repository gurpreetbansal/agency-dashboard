var BASE_URL = $('.base_url').val();
var APP_URL = $('.app_url').val();
var color = Chart.helpers.color;

function gradientColor(chartId){
	var gradient = chartId.createLinearGradient(0, 0, 0,160);
	gradient.addColorStop(0, 'rgba(114,167, 253,0.8)');
	gradient.addColorStop(0.8, 'rgba(202, 222, 255,0.5)');
	gradient.addColorStop(1, 'rgba(218, 232, 255,0.2)');
	return gradient;
}

var configSearchConsole = {
	type: 'line',
	data: {
		datasets: [{
			label: 'Clicks',
			yAxisID: 'lineId',
			backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: false,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		},{
			label: 'Impressions',
			yAxisID: 'barId',
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
					color: "rgba(0, 0, 0, 0)"
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
        	align: 'end'
        },
        elements: {
        	point:{
        		radius: 0,
        		hitRadius	:1

        	}
        },
    }
};

var configSearchConsoleRank = {
	type: 'line',
	data: {
		datasets: [{
			label: 'Clicks',
			yAxisID: 'lineId',
			backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: false,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		},{
			label: 'Impressions',
			yAxisID: 'barId',
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
					color: "rgba(0, 0, 0, 0)"
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
        	align: 'end'
        },
        elements: {
        	point:{
        		radius: 0,
        		hitRadius	:1

        	}
        },
    }
};

var configPageAuthority = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			borderColor: window.chartColors.brightBLue,
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
		maintainAspectRatio: false,
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

var configDomainAuthority = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			borderColor: window.chartColors.brightBLue,
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
		maintainAspectRatio: false,
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

var configOrganicKeyword = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			borderColor: window.chartColors.brightBLue,
			data:[],
			fill: false,
			borderWidth:2
		}]
	},
	options: {
		elements: {
			point:{
				radius: 0,
				hoverRadius:5
			}
		},
		// responsive: true,
		maintainAspectRatio: false,
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

var configOrganicVisitor = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			borderColor: window.chartColors.brightBLue,
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
		maintainAspectRatio: false,
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
				offset: true
			}]
		},
		tooltips: {
			intersect: false,
			mode: 'label',
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

var configReferringDomain = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: '',
			borderColor: window.chartColors.brightBLue,
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

var configTrafficGrowth = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: "Users",
			labels: [],
			fill: true,
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
			display: false,
			text: 'Chart.js Line Chart'
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
					else if(tooltipItem.datasetIndex === 1){
						return data.datasets[1].labels[tooltipItem.index];	
					}
				}
				,label: function(tooltipItem, data) {
					return "Users (Organic Traffic): " + tooltipItem.yLabel;
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
				scaleLabel: {
					display: true,
					labelString: 'Users'
				}
				, ticks: {
					min: 0,
				}
			}]
		},
		legend: { 
			align: 'center',
			padding:10
		}
	}
};

var configTrafficGrowthRank = {
	type: 'line',
	data: {
		labels: [],
		datasets: [{
			label: "Users",
			labels: [],
			fill: true,
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
			display: false,
			text: 'Chart.js Line Chart'
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
					else if(tooltipItem.datasetIndex === 1){
						return data.datasets[1].labels[tooltipItem.index];	
					}
				}
				,label: function(tooltipItem, data) {
					return "Users (Organic Traffic): " + tooltipItem.yLabel;
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
				scaleLabel: {
					display: true,
					labelString: 'Users'
				}
				, ticks: {
					min: 0,
				}
			}]
		},
		legend: {
			align: 'center',
			padding:10 
		}
	}
};

$(document).on("click",'#ShareKey', function (e) {
	e.preventDefault();
	$(".share-key-popup").addClass("open");
	if($(this).attr('data-share-key') !== ''){
		var getValue = APP_URL+'project-detail/'+$(this).attr('data-share-key');
		$('.copy_share_key_value').val(getValue);
		$('.project-id').val($(this).attr('data-id'));
	}else{
		var project_id = $(this).attr('data-id');
		reset_share_key(project_id);
	}
});


$(document).on("click",'.close-share-key', function () {
	$('.share-key-btn').val("Click to copy");
	$(".share-key-popup").removeClass("open");
});

new ClipboardJS('.btn.share-key-btn');

$(document).on("click", ".share-key-btn", function () {
	$(this).val("Copied!");
});

$(document).ready(function(e){
	$(document).on('click','.selectedDashboard',function(e){

		e.preventDefault();
		var selected = $("a",this).attr('href');

		var urlpdf =  $('.generate-pdf').attr('href');
		var finalurlpdf = urlpdf.substring(0, urlpdf.lastIndexOf('/'));

		if(selected == '#SEO'){
			$('.site-audit-icon').css('display','block');
			$('#ShareKey, .generate-pdf').show();
			$('.addGmbRefresh').html('');
			$('.generate-pdf').attr('href',finalurlpdf+'/seo');
			if ($('.addActivityImg').is(':empty')){
				var activity_img = $('.base_url').val()+'/public/vendor/internal-pages/images/activity-img.png';
				$('.addActivityImg').append('<a href="'+BASE_URL+'/activity/create/'+$('.campaign_id').val()+'" target="_blank" id="new-add-activities" class="btn icon-btn color-blue" uk-tooltip="title:Add Activities; pos: top-center"><img src="'+activity_img+'"></a>');
			}
			var value = '';
			var currentUrl = window.location.pathname;
			if ($('#SEO').find('.main-data').length == '0') {

				$("#SEO").load('/campaign_seo_content/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
					if(statusTxt == "success")
						if ($('#SEO').find('.main-data').find('#seo_add').length == 0) {
							seo_Scripts($('.campaignID').val(),currentUrl,value,$('.backlinkSelectdChart').val());
						}
						
						
						if(statusTxt == "error")
							console.log("Error: " + xhr.status + ": " + xhr.statusText);
					});
			}
		}
		if(selected == '#PPC'){
			$('.site-audit-icon').css('display','none');
			$('#ShareKey, .generate-pdf').show();
			$('.generate-pdf').attr('href',finalurlpdf+'/ppc');
			$('.addActivityImg').html('');
			$('.addGmbRefresh').html('');
			var refresh_img = $('.base_url').val()+'/public/vendor/internal-pages/images/refresh-icon.png';
			
			if ($('#PPC').find('.main-data').length == 0) {
				$('.generate-pdf').show();
				$("#PPC").load('/campaign_ppc_content/' + $('.campaign_id').val(), function(responseTxt, statusTxt, xhr){
					if(statusTxt == "success")
						$('.generate-pdf').show();
						$('.addGmbRefresh').html('<a href="javascript:;" data-request-id="'+$('.campaignID').val()+'" id="refresh_ppc_section" class="btn icon-btn color-purple" uk-tooltip="title: Refresh PPC data; pos: top-center" title="" aria-expanded="false"><img src="'+refresh_img+'"></a>');
						if ($('#PPC').find('.main-data').find('#adwords_add').length == 0) {
							ppc_Scripts($('.account_id').val(),$('.campaign_id').val());
						}else{
							$('.generate-pdf').hide();
							$('.addGmbRefresh').html('');
							getAdwordsEmailAccounts();
						}
						if(statusTxt == "error")
							console.log("Error: " + xhr.status + ": " + xhr.statusText);
				});
			}
			if($('#PPC .main-data').find('#adwords_add').length == 0){
				$('.addGmbRefresh').html('<a href="javascript:;" data-request-id="'+$('.campaignID').val()+'" id="refresh_ppc_section" class="btn icon-btn color-purple" uk-tooltip="title: Refresh PPC data; pos: top-center" title="" aria-expanded="false"><img src="'+refresh_img+'"></a>');
			}
		}
		if(selected == '#GMB'){
			$('.site-audit-icon').css('display','none');
			$('#ShareKey, .generate-pdf').show();
			$('.generate-pdf').attr('href',finalurlpdf+'/gmb');
			$('.addGmbRefresh').html('');

			var refresh_img = $('.base_url').val()+'/public/vendor/internal-pages/images/refresh-icon.png';
			
			$('.addActivityImg').html('');
			if ($('#GMB').find('.main-data').length == 0) {				
				$("#GMB").load('/campaign_gmb_content/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
					if(statusTxt == "success")
						if ($('#GMB').find('.main-data').find('#gmb_add').length == 0) {
							$('.generate-pdf').show();
							$('.addGmbRefresh').html('<a href="javascript:;" data-request-id="'+$('.campaignID').val()+'" id="refresh_gmb_section" class="btn icon-btn color-purple" uk-tooltip="title: Refresh Google My Business data; pos: top-center" title="" aria-expanded="false"><img src="'+refresh_img+'"></a>');
							gmb_scripts($('.campaign_id').val());
						}else{
							$('.generate-pdf').hide();
							$('.addGmbRefresh').html('');
							getGmbAccounts();
						}
						if(statusTxt == "error")
							console.log("Error: " + xhr.status + ": " + xhr.statusText);
					});
			}
			if($('#GMB .main-data').find('#gmb_add').length == 0){
				$('.addGmbRefresh').html('<a href="javascript:;" data-request-id="'+$('.campaignID').val()+'" id="refresh_gmb_section" class="btn icon-btn color-purple" uk-tooltip="title: Refresh Google My Business data; pos: top-center" title="" aria-expanded="false"><img src="'+refresh_img+'"></a>');

			}
		}

		if(selected == '#Social'){
			$('.site-audit-icon').css('display','none');
			// $('.generate-pdf').attr('href',finalurlpdf+'/social');
			$('.generate-pdf').hide();
			$('.addGmbRefresh').html('');
			$('.addActivityImg').html('');
			$('.social_module, .common_class').removeClass('uk-active');
			$('#nav_overview, .common_class').addClass('uk-active');
			$('.facebookPageFilter').hide();
			if ($('#Social').find('.main-data').length == 0) {
				$("#Social").load('/campaign_social_content/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
					if(statusTxt == "success")
						social_overview_scripts($('.campaign_id').val());
					if(statusTxt == "error")
						console.log("Error: " + xhr.status + ": " + xhr.statusText);
				});			
			}else{
				$('#facebook').hide();
				$('#overview').show();
			}
		}


	});

$(document).on('click','.selectedDashboardView',function(e){
	e.preventDefault();
	var selected = $("a",this).attr('href');
	var urlpdf =  $('.viewkeypdf').attr('href');
	var finalurlpdf = urlpdf.substring(0, urlpdf.lastIndexOf('/'));

	if(selected == '#SEO'){
		var value = '';
		var currentUrl = window.location.pathname;

		$('.viewkeypdf').attr('href',finalurlpdf+'/seo');
		if ($('#SEO').find('.main-data-view').length == '0') { 
			sidebar('seo');

			$("#SEO").load('/campaign_seo_content/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){

				$('.uk-active').removeClass('uk-active');
				$('#SEO,#SEO_tab').addClass('uk-active');
				$('.main-data-view').hide();
				$('#seoDashboard').show();
				if(statusTxt == "success")
					
					if ($('#SEO').find('.main-data-view').length == '1') {
						if($('#SEO').find('.main-data-viewDeactive').length == 1){
							$('.viewkeypdf').hide();
						}else{
							$('.viewkeypdf').show();
						}
						sidebar('seo');
						seo_Scripts_view($('.campaignID').val(),currentUrl,value,$('.backlinkSelectdChart').val());		
						$('#seoDashboardMore').load('/campaign_seo_content_viewmore/' + $('.campaign_id').val()+'/'+$('#encriptkey').val());
						seo_Scripts_viewmore($('.campaignID').val(),currentUrl,value,$('.backlinkSelectdChart').val());
					}else{
						$('.viewkeypdf').hide();
						sidebar('seo','diable');
					}
					if(statusTxt == "error")
						console.log("Error: " + xhr.status + ": " + xhr.statusText);
				});	

		}else{
			$('.uk-active').removeClass('uk-active');
			$('#SEO,#SEO_tab').addClass('uk-active');
			$('.main-data-view').hide();
			$('#seoDashboard').show();
			sidebar('seo');
		}
		
	}

	if(selected == '#PPC'){

		$('.viewkeypdf').attr('href',finalurlpdf+'/ppc');
		if ($('#PPC').find('.main-data-view').length == '0') {

			$('#PPC').load('/campaign_ppc_content/' + $('.campaign_id').val(), function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					$('.uk-active').removeClass('uk-active');
					$('#PPC, #PPC_tab').addClass('uk-active');
					$('.main-data-view').hide();
					$('#ppcDashboard').show();
					if ($('#PPC').find('.main-data-view').length > '0') {
						
						if($('#PPC').find('.main-data-viewDeactive').length == 1){
							$('.viewkeypdf').hide();
						}else{
							$('.viewkeypdf').show();
						}
						sidebar('ppc');
						
						ppc_Scripts_view($('.account_id').val(),$('.campaign_id').val());		
						$('#ppcDashboard').append('<div id="ppcDashboardMore"></div>');
						$('#ppcDashboardMore').load('/campaign_ppc_content_viewmore/' + $('.campaign_id').val(), function(responseTxt, statusTxt, xhr){
							ppc_Scripts_viewmore($('.account_id').val()); 
						});
	         				// $('#ppcDashboardMore').load('/campaign_ppc_content_viewmore/' + $('.campaign_id').val());
	         				
         			}else{
         				$('.viewkeypdf').hide();
         				sidebar('ppc','diable');
         			}
     			if(statusTxt == "error")
         			console.log("Error: " + xhr.status + ": " + xhr.statusText);
	        });

		}else{
			$('.uk-active').removeClass('uk-active');
			$('#PPC, #PPC_tab').addClass('uk-active');
			$('.main-data-view').hide();
			$('#ppcDashboard').show();
			$('.viewkeypdf').show();
			sidebar('ppc');
		}
			/*if($('#PPC').find('.main-data-viewDeactive').length == 1){
				$('.viewkeypdf').hide();
			}else{
				$('.viewkeypdf').show();
			}*/
	}

	if(selected == '#GMB'){
		
		/*if($('#GMB').find('.main-data-viewDeactive').length == 1){
			$('.viewkeypdf').hide();
		}else{
			$('.viewkeypdf').show();
		}*/

		$('.viewkeypdf').attr('href',finalurlpdf+'/gmb');
		if ($('#GMB').find('.main-data-view').length == '0') {
			
			$("#GMB").load('/campaign_gmb_content/' + $('.campaign_id').val(), function(responseTxt, statusTxt, xhr){
				
				$('.uk-active').removeClass('uk-active');
				$('#GMB, #GMB_tab').addClass('uk-active');
				$('.main-data-view').hide();
				$('#GmbDashboard,#GmbDashboardDeactive').show();

				
					if(statusTxt == "success"){
						if($('#GMB').find('#GmbDashboardDeactive').length == 1){
							$('.viewkeypdf').hide();
						}else{
							$('.viewkeypdf').show();
						}
						sidebar('gmb');
						if($('#GMB').find('.main-data-view').find('#gmb-view').length == 0){
							gmb_scripts_viewkey($('.campaign_id').val());
						}
					}else{
						sidebar('gmb');
					}
				
				
			});
			
		}else{
			$('.uk-active').removeClass('uk-active');
			$('#GMB, #GMB_tab').addClass('uk-active');
			$('.main-data-view').hide();
			$('#GmbDashboard,#GmbDashboardDeactive').show();
			$('.viewkeypdf').show();
			sidebar('gmb');
		}
		/*if($('#GMB').find('.main-data-viewDeactive').length == 1){
			$('.viewkeypdf').hide();
		}else{
			$('.viewkeypdf').show();
		}*/
	}

	if(selected == '#Social'){
			$('.viewkeypdf').hide();
			// sidebar('social');
			if ($('#Social').find('.main-data-view').length == '0') {
				$("#Social").load('/campaign_fb_content/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
					$('.uk-active').removeClass('uk-active');
					$('#Social,#Social_tab').addClass('uk-active');
					$('.main-data-view').hide();
					$('.social-view-data, .overviewFilter,#SocialDashboardDeactive').show();
					if(statusTxt == "success"){
						stopScroller();
						$('.social_module, .common_class').removeClass('uk-active');
						$('#nav_overview, .common_class').addClass('uk-active');
						if($('#overview').find('.popup-inner').hasClass('dashboard_not_active')){
							sidebar('social','facebook');
						}else{
							sidebar('social');
							social_overview_scripts($('.campaign_id').val());
						}
						
					}else{
						sidebar('social');
					}
					
				});
			}else{
				$('.uk-active').removeClass('uk-active');
				$('.social_module, .common_class').removeClass('uk-active');
				$('#Social,#Social_tab').addClass('uk-active');
				$('#nav_overview, .common_class').addClass('uk-active');
				$('.main-data-view, .facebookPageFilter').hide();
				$('.social-view-data, .overviewFilter,#SocialDashboardDeactive').show();
				$('#facebook').hide();
				$('#overview').show();
				sidebar('social');
			}
	}
});
});

$(document).on('click','.sideDashboardView',function(e){
	e.preventDefault();
	var selected = $("a",this).attr('href');
	$(".sideDashboardView").removeClass('active');
	$(this).addClass('active');
	// $('.main-data-view').hide();
	
	var value = '';
	var currentUrl = window.location.pathname;

	$('html,body').animate({
		scrollTop: $(".tab-content").offset().top - 85},
		'slow');

	var urlpdf =  $('.viewkeypdf').attr('href');
	var finalurlpdf = urlpdf.substring(0, urlpdf.lastIndexOf('/'));

	if(selected == '#seo_dashboard'){
		$('.main-data-view').hide();
		$('#seoDashboard').show();
		$('#viewactivityload').val('viewload');
		$('.vk-sidebar-selected').val('dashboard');
		$('.viewkeypdf').show();
		$('.viewkeypdf').attr('href',finalurlpdf+'/seo');
	}


	if(selected == '#visibility'){
		if ($('#SEO').find('.projectNavContainerSeoVisibility').length == '0') {
			$('#SEO').append('<div class="projectNavContainerSeoVisibility"></div>');
		}

		if ($('.projectNavContainerSeoVisibility').find('#visibility').length == '0') {
			
			$('.projectNavContainerSeoVisibility').load('/seo-visibility/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					removeLoaders();
				$('.main-data-view').hide();
				$('#visibility').show();
				seo_SideBar_view($('.campaignID').val(),currentUrl,value,null,selected);		

				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});
		}else{
			$('.main-data-view').hide();
			$('#visibility').show();
		}
		$('.viewkeypdf').show();
		$('.viewkeypdf').attr('href',finalurlpdf+'/seo');
		$('.vk-sidebar-selected').val('visibility');
	}

	if(selected == '#extraKeywords'){
		if ($('#SEO').find('.projectNavContainerSeoExtraKeywords').length == '0') {
			$('#SEO').append('<div class="projectNavContainerSeoExtraKeywords"></div>');
		}
		if ($('.projectNavContainerSeoExtraKeywords').find('#extra_organic_viewKey').length == '0') {
			
			$('.projectNavContainerSeoExtraKeywords').load('/extra-organic-keywords/' + $('#encriptkey').val(), function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					$('.main-data-view').hide();
					$('#extra_organic_viewKey').show();
					removeLoaders();
				keywordsMetricBarDetailChartDetail($('.campaignID').val());
				ExtraOrganicKeywordListViewKey($('.campaignID').val());
				$('#keywords-canvas-detail').removeClass('ajax-loader');

				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});

		}else{
			$('.main-data-view').hide();
			$('#extra_organic_viewKey').show();
		}
		$('.viewkeypdf').show();
		$('.viewkeypdf').attr('href',finalurlpdf+'/seo');
		
	}

	if(selected == '#rankings'){
		if ($('#SEO').find('.projectNavContainerSeoRank').length == '0') {
			$('#SEO').append('<div class="projectNavContainerSeoRank"></div>');
		}
		if ($('.projectNavContainerSeoRank').find('#rankings').length == '0') {
			
			$('.projectNavContainerSeoRank').load('/seo-rankings/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					$('.main-data-view').hide();
					$('#rankings').show();
					removeLoaders();
				seo_SideBar_view($('.campaignID').val(),currentUrl,value,null,selected);		

				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});

		}else{
			$('.main-data-view').hide();
			$('#rankings').show();
		}
		$('.viewkeypdf').show();
		$('.viewkeypdf').attr('href',finalurlpdf+'/seo');
	}

	if(selected == '#traffic'){
		if ($('#SEO').find('.projectNavContainerSeoTraffic').length == '0') {
			$('#SEO').append('<div class="projectNavContainerSeoTraffic"></div>');
		}
		
		
		if ($('.projectNavContainerSeoTraffic').find('#traffic').length == '0') {
			

			$('.projectNavContainerSeoTraffic').load('/seo-traffic/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					$('.main-data-view').hide();
					$('#traffic').show();
					removeLoaders();
				seo_SideBar_view($('.campaignID').val(),currentUrl,value,null,selected);		

				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});

		}else{
			$('.main-data-view').hide();
			$('#traffic').show();
		}
		$('.viewkeypdf').show();
		$('.viewkeypdf').attr('href',finalurlpdf+'/seo');
	}

	if(selected == '#backlinks'){
		if ($('#SEO').find('.projectNavContainerSeoBack').length == '0') {
			$('#SEO').append('<div class="projectNavContainerSeoBack"></div>');
		}
		
		if ($('.projectNavContainerSeoBack').find('#backlinks').length == '0') {
			
			$('.projectNavContainerSeoBack').load('/seo-backlinks/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					$('.main-data-view').hide();
					$('#backlinks').show();
					removeLoaders();
				seo_SideBar_view($('.campaignID').val(),currentUrl,value,null,selected);		

				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});

		}else{
			$('.main-data-view').hide();
			$('#backlinks').show();
		}
		$('.viewkeypdf').show();
		$('.viewkeypdf').attr('href',finalurlpdf+'/seo');
	}

	if(selected == '#goals'){
		if ($('#SEO').find('.projectNavContainerSeoGoal').length == '0') {
			$('#SEO').append('<div class="projectNavContainerSeoGoal"></div>');
		}
		
		if ($('.projectNavContainerSeoGoal').find('#goals').length == '0') {
			
			$('.projectNavContainerSeoGoal').load('/seo-goals/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					$('.main-data-view').hide();
					$('#goals').show();
					removeLoaders();
				seo_SideBar_view($('.campaignID').val(),currentUrl,value,null,selected);		

				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});

		}else{
			$('.main-data-view').hide();
			$('#goals').show();
		}
		$('.viewkeypdf').show();
		$('.viewkeypdf').attr('href',finalurlpdf+'/seo');
	}

	if(selected == '#activity'){
		if ($('#SEO').find('.projectNavContainerSeoActivity').length == '0') {
			$('#SEO').append('<div class="projectNavContainerSeoActivity"></div>');
		}
		
		if ($('.projectNavContainerSeoActivity').find('#activity').length == '0') {
			
			$('.projectNavContainerSeoActivity').load('/seo-activity/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					$('.main-data-view').hide();
					$('#activity').show();
					$('#viewactivityload').val('sidebaractivity');
				removeLoaders();
				seo_SideBar_view($('.campaignID').val(),currentUrl,value,null,selected);	
				loadActivities();
				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});

		}else{
			$('.main-data-view').hide();
			$('#activity').show();
			$('#viewactivityload').val('sidebaractivity');
		}
		$('.viewkeypdf').show();
		$('.viewkeypdf').attr('href',finalurlpdf+'/seo');
	}

	if(selected == '#keywordExplorer'){
		$('.viewkeypdf').hide();
		if ($('#SEO').find('.projectNavContainerKeywordExplorer').length == '0') {
			$('#SEO').append('<div class="projectNavContainerKeywordExplorer"><div  class="main-data-view" id="keywordExplorer"> </div></div>');
		}
		
		if ($('.projectNavContainerKeywordExplorer').find('#keywordExplorer').length == '1') {
			$('.projectNavContainerKeywordExplorer').load('/seo-keyword-explorer/' + $('#user_id').val(), function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					$('.main-data-view').hide();
					$('#keywordExplorer').show();
					getDfsLanguages();
					getDfsLocations('','','search-section');
					removeLoaders();
					seo_SideBar_view($('.campaignID').val(),currentUrl,value,null,selected);	
					if(statusTxt == "error")
						console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});
		}else{
			$('.main-data-view').hide();
			$('#keywordExplorer').show();
		}
	}

	if(selected == '#audit'){
		$("#audit-tab").addClass('active');
		if ($('#SEO').find('.projectNavContainerSeoAudit').length == '0') {
			$('#SEO').append('<div class="projectNavContainerSeoAudit"> <div class="main-data-view sa-audit-overview" id="audit"> </div> <div class="main-data-view sa-audit-details" id="audit-detail"> </div> </div>');
		}
		
		if ($('.projectNavContainerSeoAudit').find('#audit').length == '1') {
			$('.main-data-view').hide();
			$('#audit').show();
			if($('.audit-id').val() == ''){
				viewKeyConnect();
			}else{
				$('#audit').load('/audit/loader/overview', function(responseTxt, statusTxt, xhr){
					var audit_id = $('.audit-id').val();
					if(statusTxt == "success")
						siteAuditSummaryUpdate(audit_id);
					if(statusTxt == "error")
						console.log("Error: " + xhr.status + ": " + xhr.statusText);
				});
			}
			
		}else{
			$('.main-data-view').hide();
			$('#audit').show();
		}
		$('.viewkeypdf').attr('href',finalurlpdf+'/audit');
	}

	if(selected == '#ppcDashboard'){
		$('.main-data-view').hide();
		$('#ppcDashboard').show();
	}

	if(selected == '#campaignAdGroups'){
		if ($('#PPC').find('.projectNavContainerPpcAdGroups').length == '0') {
			$('#PPC').append('<div class="projectNavContainerPpcAdGroups"></div>');
		}
		
		if ($('.projectNavContainerPpcAdGroups').find('#ppcAdGroups').length == '0') {
			$('.projectNavContainerPpcAdGroups').load('/ppc-campaign/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					$('.main-data-view').hide();
					$('#ppcAdGroups').show();
					ppc_SideBar_view($('.account_id').val(),$('.campaign_id').val(),selected);		

				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});
			
		}else{
			$('.main-data-view').hide();
			$('#ppcAdGroups').show();
		}
		
	}

	if(selected == '#keywords'){
		if ($('#PPC').find('.projectNavContainerPpcKeywords').length == '0') {
			$('#PPC').append('<div class="projectNavContainerPpcKeywords"></div>');
		}
		
		if ($('.projectNavContainerPpcKeywords').find('#keywords').length == '0') {
			
			$('.projectNavContainerPpcKeywords').load('/ppc-keywords/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					$('.main-data-view').hide();
					$('#keywords').show();
					ppc_SideBar_view($('.account_id').val(),$('.campaign_id').val(),selected);		

				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});

		}else{
			$('.main-data-view').hide();
			$('#keywords').show();
		}
	}

	if(selected == '#ads'){
		if ($('#PPC').find('.projectNavContainerPpcAds').length == '0') {
			$('#PPC').append('<div class="projectNavContainerPpcAds"></div>');
		}
		
		if ($('.projectNavContainerPpcAds').find('#ads').length == '0') {
			
			$('.projectNavContainerPpcAds').load('/ppc-ads/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					$('.main-data-view').hide();
					$('#ads').show();
					ppc_SideBar_view($('.account_id').val(),$('.campaign_id').val(),selected);

				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});
			
		}else{
			$('.main-data-view').hide();
			$('#ads').show();
		}
		
	}

	if(selected == '#performance'){
		if ($('#PPC').find('.projectNavContainerPpcPerformance').length == '0') {
			$('#PPC').append('<div class="projectNavContainerPpcPerformance"></div>');
		}
		
		if ($('.projectNavContainerPpcPerformance').find('#performance').length == '0') {
			
			$('.projectNavContainerPpcPerformance').load('/ppc-performance/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					$('.main-data-view').hide();
					$('#performance').show();
					ppc_SideBar_view($('.account_id').val(),$('.campaign_id').val(),selected);	

				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});
			
		}else{
			$('.main-data-view').hide();
			$('#performance').show();
		}
	}

	
	$('.social_module').removeClass('uk-active active');
	if(selected == '#facebookviewlikes'){
		if ($('#Social').find('.projectNavContainerFacebookLikes').length == '0') {
			$('#Social').append('<div class="projectNavContainerFacebookLikes"></div>');
		}
		if ($('.projectNavContainerFacebookLikes').find('#facebookviewlikes').length == '0') {
			$('.projectNavContainerFacebookLikes').load('/facebook-view-likes/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){

				if(statusTxt == "success")
					stopScroller();
					$('.main-data-view').hide();
					$('#facebookviewlikes').show();
					facebook_SideBar_view($('.campaign_id').val(),selected);	

				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});
		}else{
			$('.main-data-view').hide();
			$('#facebookviewlikes').show();
		}
	}

	if(selected == '#facebookviewreach'){
		$('#ShareKey').hide();
		if ($('#Social').find('.projectNavContainerFacebookReach').length == '0') {
			$('#Social').append('<div class="projectNavContainerFacebookReach"></div>');
		}
		if ($('.projectNavContainerFacebookReach').find('#facebookviewreach').length == '0') {
			$('.projectNavContainerFacebookReach').load('/facebook-view-reach/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){

				if(statusTxt == "success")
						stopScroller();
						$('.main-data-view').hide();
						$('#facebookviewreach').show();
						facebook_SideBar_view($('.campaign_id').val(),selected);
				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});
		}else{
			$('.main-data-view').hide();
			$('#facebookviewreach').show();
		}
	}

	if(selected == '#facebookviewpostreviews'){
		$('#ShareKey').hide();
		if ($('#Social').find('.projectNavContainerFacebookPostReviews').length == '0') {
			$('#Social').append('<div class="projectNavContainerFacebookPostReviews"></div>');
		}
		if ($('.projectNavContainerFacebookPostReviews').find('#facebookviewpostreviews').length == '0') {
			$('.projectNavContainerFacebookPostReviews').load('/facebook-view-postreviews/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){

				if(statusTxt == "success")
					$('.main-data-view').hide();
					$('#facebookviewpostreviews').show();
					facebook_SideBar_view($('.campaign_id').val(),selected);	

				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});
		}else{
			$('.main-data-view').hide();
			$('#facebookviewpostreviews').show();
		}
	}

	if(selected == '#facebookviewreviews'){
		$('#ShareKey').hide();
		if ($('#Social').find('.projectNavContainerFacebookReviews').length == '0') {
			$('#Social').append('<div class="projectNavContainerFacebookReviews"></div>');
		}
		if ($('.projectNavContainerFacebookReviews').find('#facebookviewreviews').length == '0') {
			$('.projectNavContainerFacebookReviews').load('/facebook-view-reviews/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){

				if(statusTxt == "success")
					$('.main-data-view').hide();
					$('#facebookviewreviews').show();
					facebook_SideBar_view($('.campaign_id').val(),selected);	

				if(statusTxt == "error")
					console.log("Error: " + xhr.status + ": " + xhr.statusText);
			});
		}else{
			$('.main-data-view').hide();
			$('#facebookviewreviews').show();
		}


	}
	
});

function gmb_scripts(campaign_id){	
	getGMBErrorMessages(campaign_id);
	GoogleUpdateTimeAgo('gmb');
	customerViewOnload(campaign_id,'','');	
	customerActionOnload(campaign_id,'','');	
	phoneCallsBar(campaign_id,'','');	
	ajaxGooglePhotoView(campaign_id,'','');	
	CustomerSearch(campaign_id,'','');	
	DirectionRequest(campaign_id,'','');	
	Reviews(campaign_id,1);	
	Media(campaign_id);	
}


function seo_Scripts(campaign_id,currentUrl,value,backlinkSelectdChart){
	seo_custom_js();
	if(currentUrl.search('extra-organic-keywords/*')!=-1){
		organicKeywordTimeAgo(campaign_id);
		keywordsMetricBarDetailChart(campaign_id);
		ExtraOrganicKeywordList(campaign_id);
	}else if(currentUrl.search('serp/*')!=-1){
		checkDomainType(campaign_id);
		languages(campaign_id);
		RegionalDatabase(campaign_id);
		updateTimeAgo();
		show_existing_tags(campaign_id);
		LiveKeywordStats(campaign_id);
		LiveKyewordTable(campaign_id);
	}else{
		/*overview section*/
		siteAuditOverview(campaign_id);
		organicKeywordsChart(campaign_id);
		ajaxOrganicKeywordRanking(campaign_id);
		pageAuthorityChart(campaign_id);
		pageAuthorityStats(campaign_id);
		referringDomainChart(campaign_id);
		ajaxReferringDomains(campaign_id);
		DomainAuthorityChart(campaign_id);
		domainAuthorityStats(campaign_id);	

		if($('.ua-overview').length == 1){
			organicVisitorsChart(campaign_id);
			ajaxGoogleTrafficGrowth_data(campaign_id);
			goal_completion_chart_overview(campaign_id);
			goal_completion_stats_overview(campaign_id);
		}
		if($('.ga4-overview').length == 1){
			ga4_overview_allUser_Chart(campaign_id);
			ga4_overview_allUser_stats(campaign_id);
			ga4_overview_conversions_Chart(campaign_id);
			ga4_overview_conversions_stats(campaign_id);
		}

		GoogleUpdateTimeAgo();
		/*search console*/
		if($('#console_add').css('display') == 'block'){
			getSearchConsoleEmailAccounts();
		}else{
			search_console_graph(campaign_id);
		}
		
		/*analytics*/
		if($('#analytics_add').css('display') == 'block' || $('#analytics4_add').css('display') == 'block'){
			getAnalyticsEmailAccounts();
		}else if($('#analytics_data').css('display') == 'block'){
			get_analytics_range_heading(campaign_id);
			ajaxGoogleTrafficGrowthMetrics(campaign_id);
			ajaxGoogleTrafficGrowthGraph(campaign_id);
		}else if($('#analytics4_data').css('display') == 'block'){
			ajax_acquisition_overview(campaign_id,'','','','','','','','');
		}

		getConsoleErrorMessages(campaign_id);
		getAnalyticsErrorMessages(campaign_id);

		seo_second_script(campaign_id);	
		seo_third_script(campaign_id,backlinkSelectdChart);			
	}

}

function getAnalyticsErrorMessages(campaign_id){
	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_error_messages',
		data:{campaign_id,moduleType:1},
		dataType:'json',
		success:function(response){
			if(response != ''){
				$('.organicTrafficGrowthHeading').html('');
				$('.organicTrafficGrowthHeading').append('<div class="alert alert-danger"><span><i class="fa fa-exclamation-triangle"></i>Google Analytics: '+response+' Try reconnecting your account.</span></div>');
			}else{
				$('.organicTrafficGrowthHeading').html('');
			}
			setTimeout(function(){removeFloatingDiv();},100);	
		}
	});
}

function getConsoleErrorMessages(campaign_id){
	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_error_messages',
		data:{campaign_id,moduleType:2},
		dataType:'json',
		success:function(response){
			if(response != ''){
				$('#searchconsoleHeading').html('');
				$('#searchconsoleHeading').append('<div class="alert alert-danger"><span><i class="fa fa-exclamation-triangle"></i>Search Console: '+response+' Try reconnecting your account.</span></div>');
				setTimeout(function(){removeFloatingDiv();},100);
			}else{
				$('.searchconsoleHeading').html('');
			}
		}
	});
}

function getGMBErrorMessages(campaign_id){
	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_error_messages',
		data:{campaign_id,moduleType:4},
		dataType:'json',
		success:function(response){
			if(response != ''){
				$('html,body').animate({scrollTop: $(".GmbErrorHeading").offset().top},'slow');
				$('.GmbErrorHeading').append('');
				$('.GmbErrorHeading').append('<div class="alert alert-danger"><span><i class="fa fa-exclamation-triangle"></i>'+response+' Try reconnecting your account.</span></div>');
			}else{
				$('.GmbErrorHeading').html('');
			}
		}
	});
}


function getAdwordsEmailAccounts(){
	$.ajax({
		url:BASE_URL+'/ajax_adwords_accounts',
		data:{user_id:$('.user_id').val()},
		type:'GET',
		success:function(response){
			$('#detail_adwords_existing_emails').html(response);
			$('#detail_adwords_existing_emails').selectpicker('refresh');
		}
	})
}


function getAnalyticsEmailAccounts(){
	$.ajax({
		url:BASE_URL+'/ajax_google_analytics_accounts',
		data:{user_id:$('.user_id').val()},
		type:'GET',
		success:function(response){
			$('#detail_analytics_existing_emails').html(response);
			$('.selectpicker').selectpicker('refresh');
		}
	});
}

function getSearchConsoleEmailAccounts(){
	$.ajax({
		url:BASE_URL+'/ajax_google_cnsole_accounts',
		data:{user_id:$('.user_id').val()},
		type:'GET',
		success:function(response){
			$('#detail_search_console_existing_emails').html(response);
			$('.selectpicker').selectpicker('refresh');
		}
	})
}

function get_console_range_heading(campaign_id){
	$.ajax({
		type:'GET',
		url: BASE_URL+'/ajax_console_range_data',
		data:{campaign_id},
		dataType:'json',
		success:function(response){
			$('.searchConsole[data-value="'+response['selected']+'"]').addClass('active');
		}
	});
}

function get_analytics_range_heading(campaign_id){
	$.ajax({
		type:'GET',
		url: BASE_URL+'/ajax_analytics_range_data',
		data:{campaign_id},
		dataType:'json',
		success:function(response){

			if(response['comparison'] == 1){
				$('.analyticsGraphCompare').attr("checked", "checked");
			}else{
				$('.analyticsGraphCompare').removeAttr('checked');
			}

			$('.graph_range[data-value="'+response['selected']+'"]').addClass('active');
			$('.organic_traffic_displayType[data-type="'+response['display_type']+'"]').addClass('blue-btn');

		}
	});
}

function seo_second_script(campaign_id){
	seo_custom_js();
	/*organic keyword growth*/
	organicKeywordTimeAgo(campaign_id);
	keywordsMetricBarChart(campaign_id);
	ExtraOrganicKeywordCount(campaign_id);
	ExtraOrganicKeywordList(campaign_id);
	/*live keyword tracking*/
	checkLiveKeywordCount();
	checkDomainType(campaign_id);
	languages(campaign_id);
	RegionalDatabase(campaign_id);

	updateTimeAgo();
	show_existing_tags(campaign_id);
	LiveKeywordStats(campaign_id);
	LiveKyewordTable(campaign_id);
}

function seo_third_script(campaign_id,backlinkSelectdChart){
	seo_custom_js();
	GoogleUpdateTimeAgo();
	backlinkProfileTimeAgo(campaign_id);
	backlinkProfileChart(campaign_id,backlinkSelectdChart);
	backlinkProfileList(campaign_id);
	backlinkProfileData(campaign_id);
	get_analytics_range_heading(campaign_id);

	if($('#analytics_add').css('display') == 'block' || $('#analytics4_add').css('display') == 'block'){
		getAnalyticsEmailAccounts();
	}else if($('#analytics_data').css('display') == 'block'){
		goalcompletion_data(campaign_id);
		if($('#ecommerce-google-goals-div').length == 1){
			ecommerce_goalcompletion(campaign_id);
		}
	}else if($('#analytics4_data').css('display') == 'block'){
		ajax_traffic_acquisition(campaign_id,'','','','','','','','');
		ajax_goals_listing_traffic_acquisition(campaign_id,'','','','','','','','');
	}

	loadActivities();
}

function summarysection(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_summary_data",
		data:{campaign_id},
		dataType:'json',
		success:function(response){
			if(response['status'] == 2){
				$('#summaryDiv').remove();
			}

			if(response['status'] == 1){
				document.getElementById("summaryText").innerHTML = response['message'];
			}
			$('#summaryContent').removeClass('ajax-loader');
			$('.summary-text').removeClass('ajax-loader');
		}
	});
}


function seo_SideBar_view(campaign_id,currentUrl,value,backlinkSelectdChart,tabName){

	seo_custom_js();
	if(tabName == '#visibility'){
		GoogleUpdateTimeAgo();
		search_console_graph_visibility(campaign_id);
	}

	if(tabName == '#rankings'){
		updateTimeAgo();
		keywordsMetricBarChartRank(campaign_id);
		ExtraOrganicKeywordList(campaign_id);
		ExtraOrganicKeywordCount(campaign_id);
		LiveKeywordTrackingList(campaign_id,'currentPosition', 'asc', 20, 1, '','viewkey');
		LiveKeywordStats(campaign_id);
		show_existing_tags(campaign_id);
	}

	if(tabName == '#traffic'){
		if($('#analytics_data_traffic').css('display') == 'block'){
			GoogleUpdateTimeAgo('analytics');
			ajaxGoogleTrafficGrowthMetrics(campaign_id);
			ajaxGoogleTrafficGrowthGraphRank(campaign_id);
		}

		if($('#analytics4_data_traffic').css('display') == 'block'){
			GoogleUpdateTimeAgo('ga4');
			ajax_SeoTraffic_acquisition_overview(campaign_id,'','','','','','','','');
		}

	}

	if(tabName == '#backlinks'){
		backlinkProfileData(campaign_id);
		backlinkProfileChartBack(campaign_id,backlinkSelectdChart);
		$(".top-organic-keyword-table .table-responsive").mCustomScrollbar({
			axis: "y",
			mouseWheel: {
				enable: false
			},
			contentTouchScroll: true,
			advanced: {
				releaseDraggableSelectors: "table tr td"
			}
		});
	}

	if(tabName == '#goals'){

		if($('#analytics_data_goalmore').css('display') == 'block'){
			GoogleUpdateTimeAgo('analytics');
			goalcompletion_dataGoals(campaign_id);
			ecommerce_goals_tab(campaign_id);
		}

		if($('#ga4_goals_data').css('display') == 'block'){
			GoogleUpdateTimeAgo('ga4');
			ajax_goals_traffic_acquisition(campaign_id,'','','','','','','','');
			ajax_goals_listing_acquisition(campaign_id,'','','','','','','','');
		}

	}
}

function seo_Scripts_view(campaign_id,currentUrl,value,backlinkSelectdChart){
	seo_custom_js();
	siteAuditOverview(campaign_id);
	organicKeywordsChart(campaign_id);
	ajaxOrganicKeywordRanking(campaign_id);
	pageAuthorityStats(campaign_id);
	pageAuthorityChart(campaign_id);
	referringDomainChart(campaign_id);
	ajaxReferringDomains(campaign_id);
	DomainAuthorityChart(campaign_id);
	domainAuthorityStats(campaign_id);	
	if($('.ua-overview').length == 1){	
		organicVisitorsChart(campaign_id);
		ajaxGoogleTrafficGrowth_data(campaign_id);
		goal_completion_chart_overview(campaign_id);
		goal_completion_stats_overview(campaign_id);
	}

	if($('.ga4-overview').length == 1){
		ga4_overview_allUser_Chart(campaign_id);
		ga4_overview_allUser_stats(campaign_id);
		ga4_overview_conversions_Chart(campaign_id);
		ga4_overview_conversions_stats(campaign_id);
	}
	updateTimeAgo();
	GoogleUpdateTimeAgo();
	search_console_graph(campaign_id);
	$('#sc-dateRange-popup').addClass('viewkey-popups');
	if($('#analytics_data').css('display') == 'block'){
		ajaxGoogleTrafficGrowthMetrics(campaign_id);
		ajaxGoogleTrafficGrowthGraph(campaign_id);
	}
	if($('#analytics4_data').css('display') == 'block'){
		ajax_acquisition_overview(campaign_id,'','','','','','','','');
	}
}

	function seo_Scripts_pdf(campaign_id,currentUrl,value,backlinkSelectdChart){


		pageAuthorityChart(campaign_id);
		DomainAuthorityChart(campaign_id);
		organicKeywordsChart(campaign_id);
		organicVisitorsChart(campaign_id);
		referringDomainChart(campaign_id);
		ajaxReferringDomains(campaign_id);
		ajaxOrganicKeywordRanking(campaign_id);

		goal_completion_stats_overview(campaign_id);
		goal_completion_chart_overview(campaign_id)

		domainAuthorityStats(campaign_id);
		pageAuthorityStats(campaign_id);

		if($('.ua-overview').length == 1){	
			organicVisitorsChart(campaign_id);
			ajaxGoogleTrafficGrowth_data(campaign_id);
			goal_completion_chart_overview(campaign_id);
			goal_completion_stats_overview(campaign_id);
		}

		if($('.ga4-overview').length == 1){
			ga4_overview_allUser_Chart(campaign_id);
			ga4_overview_allUser_stats(campaign_id);
			ga4_overview_conversions_Chart(campaign_id);
			ga4_overview_conversions_stats(campaign_id);
		}
		
		updateTimeAgo();
		ajaxGoogleTrafficGrowth_data(campaign_id);

		search_console_graph_pdf(campaign_id);

		if($('#analytics_data').css('display') == 'block'){
			ajaxGoogleTrafficGrowthMetrics(campaign_id);
			ajaxGoogleTrafficGrowthGraph(campaign_id);
		}
		if($('#analytics4_data').css('display') == 'block'){
			ajaxAcquisitionOverview(campaign_id,'','','','','','','','');
		}
		GoogleUpdateTimeAgo();
		organicKeywordTimeAgo(campaign_id);
		keywordsMetricBarChart(campaign_id);
		keywordsMetricChartStats(campaign_id);
		fetch_extra_organic_keywords(1,'position','asc',campaign_id,'',50)
		LiveKeywordStats(campaign_id);
		ExtraOrganicKeywordCount(campaign_id);
		backlinkProfileTimeAgo(campaign_id);
		backlinkProfileData(campaign_id);
		backlinkProfileChart(campaign_id,backlinkSelectdChart);
		

		if($('#analytics_data_goal').css('display') == 'block'){
			goalcompletion_data(campaign_id);
		}

		if($('#ecom_analytics_data_goal').css('display') == 'block'){
			ecommerce_goalcompletion(campaign_id);
		}

		if($('#ga4_goals_data').css('display') == 'block'){
			ajaxTrafficAcquisition(campaign_id,'','','','','','','','');
			ajaxGoalsListingTrafficAcquisition(campaign_id,'','','','','','','','');
		}

	}

	function seo_Scripts_viewmore(campaign_id,currentUrl,value,backlinkSelectdChart){

		setTimeout(function() {
			seo_custom_js();
			$('.top-key-organic').removeClass('ajax-loader');
			$('#keywords-canvas').removeClass('ajax-loader');
			updateTimeAgo();
			keywordsMetricBarChart(campaign_id);
			ExtraOrganicKeywordList(campaign_id);
			ExtraOrganicKeywordCount(campaign_id);
			backlinkProfileData(campaign_id);
			backlinkProfileChart(campaign_id,backlinkSelectdChart);
			LiveKeywordStats(campaign_id);
			LiveKeywordTrackingList(campaign_id,'currentPosition', 'asc', 20, 1, '','viewkey');
			show_existing_tags(campaign_id);
			GoogleUpdateTimeAgo();

			if($('#analytics_data').css('display') == 'block'){
				goalCompletionChart(campaign_id);
				goalCompletionStats(campaign_id);
				goalcompletion_data(campaign_id);
				ecommerce_goalcompletion(campaign_id);
			}

			if($('#analytics4_data').css('display') == 'block'){
				ajax_traffic_acquisition(campaign_id,'','','','','','','','');
				ajax_goals_listing_traffic_acquisition(campaign_id,'','','','','','','','');
			}

			loadActivities();
			$('.loader').fadeOut(600, function () {
				$('.loader').remove();
			});
		}, 1000);
	}


	function ppc_Scripts(account_id,campaign_id){
		$('.heading, .project-entries, .project-search').removeClass('ajax-loader');
	custom_js(); //loading custom js on click
	if(account_id != undefined  && account_id != ''){

		GoogleUpdateTimeAgo('adwords');

		impressions_graph(account_id,campaign_id);
		ads_campaign_list(account_id);
		ads_keywords_list(account_id);
		ads_list(account_id);
		ads_groups_list(account_id);
		ads_performance_network_list(account_id);
		ads_performance_device_list(account_id);
		ads_performance_clickType_list(account_id);
		ads_performance_adSlot_list(account_id);
	}
}

function ppc_Scripts_view(account_id,campaign_id){
	
	setTimeout(function () {
		custom_js(); //loading custom js on click
		if(account_id != undefined  && account_id != ''){
			sidebar('ppc');
			impressions_graph(account_id,campaign_id);
			

		}
		$('#PPC').find('.heading').removeClass('ajax-loader');
		$('#PPC').find('.project-entries').removeClass('ajax-loader');
		$('#PPC').find('.project-search').removeClass('ajax-loader');
	}, 1000);
}

function ppc_Scripts_pdf(account_id,campaign_id){

	impressions_graph(account_id,campaign_id);
	ads_campaign_list_pdf(account_id);
	ads_keywords_list_pdf(account_id);
	ads_list(account_id);
	ads_groups_list(account_id);
	ads_performance_network_list(account_id);
	ads_performance_device_list(account_id);
	ads_performance_clickType_list(account_id);
	ads_performance_adSlot_list(account_id);
}

function ppc_Scripts_viewmore(account_id){
	
	setTimeout(function () {
		// custom_js(); //loading custom js on click
		if(account_id != undefined  && account_id != ''){
			
			ads_campaign_list(account_id);
			ads_keywords_list(account_id);
			ads_list(account_id);
			ads_groups_list(account_id);
			ads_performance_network_list(account_id);
			ads_performance_device_list(account_id);
			ads_performance_clickType_list(account_id);
			ads_performance_adSlot_list(account_id);

		}
		$('#PPC').find('.heading').removeClass('ajax-loader');
		$('#PPC').find('.project-entries').removeClass('ajax-loader');
		$('#PPC').find('.project-search').removeClass('ajax-loader');
	}, 1000);
}

function ppc_SideBar_view(account_id,campaign_id,tabName){


	if(tabName == '#campaignAdGroups'){
		ads_campaign_list(account_id);
		ads_groups_list(account_id);
	}

	if(tabName == '#keywords'){
		summary_chart_keyword(account_id,campaign_id);
		ads_keywords_list(account_id);
	}

	if(tabName == '#ads'){
		ads_list(account_id);
	}

	if(tabName == '#performance'){
		performance_chartData(account_id,campaign_id);
		ads_performance_network_list(account_id);
		ads_performance_device_list(account_id);
		ads_performance_clickType_list(account_id);
		ads_performance_adSlot_list(account_id);
	}
	/*$('#PPC').find('.ajax-loader').removeClass('ajax-loader');*/
	$('#PPC').find('.heading').removeClass('ajax-loader');
	$('#PPC').find('.project-entries').removeClass('ajax-loader');
	$('#PPC').find('.project-search').removeClass('ajax-loader');
	
}


function seo_custom_js(){
	$('.loader').fadeOut(600, function () {
		$('.loader').remove();
	});

	var LiveKeywordTable = $("body").find(".LiveKeywordTable table"),
	LiveKeywordTableRow = LiveKeywordTable.find("tr"),
	iconsList = LiveKeywordTableRow.find(".icons-list"),
	downArrow = iconsList.find(".downArrow");
	
	$('.loader').fadeOut(600, function () {
		//$(this).removeClass('loader');
	});
	$('.hiddenOnLoad').fadeIn(600, function () {
		//$('div').removeClass("hiddenOnLoad");
	});


	$(".top-organic-keyword-table .table-responsive").mCustomScrollbar({
		axis: "y",
		mouseWheel: {
			enable: false
		},
		contentTouchScroll: true,
		advanced: {
			releaseDraggableSelectors: "table tr td"
		}
	});

	// $(".sidebar nav ul.uk-nav-default:last-of-type .uk-nav-sub").mCustomScrollbar({
	// 	axis: "y",
	// 	mouseWheel: {
	// 		enable: false
	// 	},
	// 	contentTouchScroll: true,
	// 	advanced: {
	// 		releaseDraggableSelectors: "table tr td"
	// 	}
	// });

	// $(".project-table-body").mCustomScrollbar({
	// 	axis: "x",
	// 	setLeft: "-100px",
	// 	mouseWheel: {
	// 		enable: false
	// 	},
	// 	contentTouchScroll: true,
	// 	advanced: {
	// 		releaseDraggableSelectors: "table tr td"
	// 	}
	// });

	// setTimeout(function () {
	// 	$(".project-table-body .mCSB_container").css("left", 0);
	// }, 2000);



	LiveKeywordTableRow.each(function () {
		downArrow.on("click", function () {
			$(this).parent().toggleClass("active");
			$(this).find(".fa").toggleClass("fa-area-chart");
			$(this).find(".fa").toggleClass("fa-times");
		})
	});



	$(".file-group input[type=file]").change(function () {
		var names = [];
		for (var i = 0; i < $(this).get(0).files.length; ++i) {
			names.push($(this).get(0).files[i].name);
		}

		if ($(".file-group input[type=file]").val()) {
			$(".file-group .form-control").addClass("selected");
			$(".file-group .form-control span").html(names);
		} else {
			$(".file-group .form-control").removeClass("selected");
			$(".file-group .form-control span").html("Profile Image");
		}

	});


	

}


function custom_js(){
	$('.loader').fadeOut(600, function () {
		$('.loader').remove();
	});


	// $(".project-table-body").mCustomScrollbar({
	// 	axis: "x",
	// 	setLeft: "-100px",
	// 	mouseWheel: {
	// 		enable: false
	// 	},
	// 	contentTouchScroll: true,
	// 	advanced: {
	// 		releaseDraggableSelectors: "table tr td"
	// 	}
	// });

	setTimeout(function () {
		$(".project-table-body .mCSB_container").css("left", 0);
	}, 2000);

	var  compareDateForm = $("body").find(".compare-date-form"),
	compareDateRangeBtn = $("body").find("#compareDateRangeBtn"),
	compareDateToggle = $("body").find("#compareDateToggle"),
	compareDateInput = $("body").find("#compareDateInput");

	compareDateRangeBtn.on("click", function () {
		$(this).toggleClass("active");
		compareDateForm.toggleClass("open");
	})

	compareDateToggle.on("click", function () {
		if ($(this).is(":checked")) {
			compareDateInput.show(300);
		} else {
			compareDateInput.hide(200);
		}
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

$(window).load(function() { 
	var value = '';
	if ($('#SEO').find('.main-data-pdf').length == 1) { 
		var currentUrl = window.location.pathname;
		seo_Scripts_pdf($('.campaignID').val(),currentUrl,value,null);		
	}
	if ($('#PPC').find('.main-data-pdf').length == 1) { 
		var currentUrl = window.location.pathname;
		ppc_Scripts_pdf($('.account_id').val(),$('.campaign_id').val());		
	}

	if ($('#GMB').find('.main-data-pdf').length == 1) { 
		var currentUrl = window.location.pathname;
		gmb_scripts_pdf($('.campaign_id').val());
	}
});


$(document).ready(function(){
	var currentUrl = window.location.pathname;
	var value = finalurlpdf = '';
	if(currentUrl.search('download/*')!==1 && currentUrl.search('serp/*')!==1 && currentUrl.search('extra-organic-keywords/*')!==1 && currentUrl.search('activities-details/*')!==1 && currentUrl.search('pdf/audit/*')!==1 && currentUrl.search('audit-share/*')!==1 && currentUrl.search('audit/page/detail/*')!==1){
		var urlpdf =  $('.generate-pdf').attr('href');
		var finalurlpdf = urlpdf.substring(0, urlpdf.lastIndexOf('/'));
	}
	setTimeout(function() {
		seo_custom_js();
		if(currentUrl.search('extra-organic-keywords/*')!=-1){
			seo_Scripts($('.campaignID').val(),currentUrl,value,'');	
		}
	},1000);

	setTimeout(function() {
		seo_custom_js();
		if(currentUrl.search('serp/*')!=-1){
			seo_Scripts($('.campaignID').val(),currentUrl,value,'');	
		}
	},1000);

	if ($('#SEO').find('.main-data').length == 1) { 
		$('.site-audit-icon').css('display','block');
		var activity_img = $('.base_url').val()+'/public/vendor/internal-pages/images/activity-img-small.png';
		$('.addActivityImg').append('<a href="'+BASE_URL+'/activity/create/'+$('.campaign_id').val()+'" target="_blank" id="new-add-activities" class="btn icon-btn color-blue" uk-tooltip="title:Add Activities; pos: top-center"><img src="'+activity_img+'"></a>');
		var currentUrl = window.location.pathname;

		//	setTimeout(function () {
			seo_Scripts($('.campaign_id').val(),currentUrl,value,$('.backlinkSelectdChart').val());		
		//	},1000);
	}

	if ($('#SEO').find('.main-data-view').length == 1) { 
		var currentUrl = window.location.pathname;
		sidebar('seo');
		setTimeout(function () {
			seo_Scripts_view($('.campaignID').val(),currentUrl,value,null);		
			$('#seoDashboardMore').load('/campaign_seo_content_viewmore/' + $('.campaign_id').val()+'/'+$('#encriptkey').val(), function(responseTxt, statusTxt, xhr){
				if(statusTxt == "success")
					seo_Scripts_viewmore($('.campaignID').val(),currentUrl,value,null);  
			});
		},1000);
	}

	setTimeout(function() {
		seo_custom_js();
		if(currentUrl.search('activities-details/*')!=-1){
			loadActivities();
		}
	},1000);

	if ($('#PPC').find('.main-data').length == 1) {
		$('.site-audit-icon').css('display','none');
		setTimeout(function () {
			$('.generate-pdf').attr('href',finalurlpdf+'/ppc');
			var refresh_img = $('.base_url').val()+'/public/vendor/internal-pages/images/refresh-icon.png';
			$('.addGmbRefresh').append('<a href="javascript:;" data-request-id="'+$('.campaignID').val()+'" id="refresh_ppc_section" class="btn icon-btn color-purple" uk-tooltip="title: Refresh PPC data; pos: top-center" title="" aria-expanded="false"><img src="'+refresh_img+'"></a>');

			ppc_Scripts($('.account_id').val(),$('.campaign_id').val());		
		},1000);
	}

	if ($('#PPC').find('.main-data-view').length == 1) {
		sidebar('ppc');
		ppc_Scripts_view($('.account_id').val(),$('.campaign_id').val());	
		$('#ppcDashboard').append('<div id="ppcDashboardMore"></div>');
		$('#ppcDashboardMore').load('/campaign_ppc_content_viewmore/' + $('.campaign_id').val(), function(responseTxt, statusTxt, xhr){
			if(statusTxt == "success")
				$('.generate-pdf').attr('href',finalurlpdf+'/ppc');
				ppc_Scripts_viewmore($('.account_id').val()); 
		});
	}

	if ($('#PPC').find('.main-data-viewDeactive').length == 1) {
		sidebar('ppc','diable');
		$('view-ajax-tabs').removeClass('ajax-loader');

	}

	if ($('#GMB').find('.main-data').length == 1) { 
		$('.site-audit-icon').css('display','none');
		if ($('#GMB').find('.main-data').find('#gmb_add').css('display') == 'block') {
			getGmbAccounts();
		}else{
			$('.generate-pdf').attr('href',finalurlpdf+'/gmb');
			var refresh_img = $('.base_url').val()+'/public/vendor/internal-pages/images/refresh-icon.png';
			$('.addGmbRefresh').append('<a href="javascript:;" data-request-id="'+$('.campaignID').val()+'" id="refresh_gmb_section" class="btn icon-btn color-purple" uk-tooltip="title: Refresh Google My Business data; pos: top-center" title="" aria-expanded="false"><img src="'+refresh_img+'"></a>');
			gmb_scripts($('.campaign_id').val());	
		}
	}

	if ($('#GMB').find('.main-data-view').length == 1) { 
		sidebar('gmb');
		if ($('#GMB').find('.main-data-view').find('#gmb-view').length == 0) {
			$('.generate-pdf').attr('href',finalurlpdf+'/gmb');
			gmb_scripts($('.campaignID').val());	
		} 
	}

	if ($('#GMB').find('.main-data-viewDeactive').length == 1) { 
		$('.viewkeypdf').hide();
		sidebar('gmb','diable');
	}

	if($('#Social').find('.main-data').find('.social_module').hasClass('uk-active')){
		$('.viewkeypdf').hide();
		// $('#ShareKey').hide();
		social_overview_scripts($('.campaign_id').val());
	}

	if($('#Social').find('#facebook').hasClass('pdf')){
		facebookScripts($('.campaign_id').val());
	}

	if ($('#Social').find('.main-data-view').length == 1) {
		$("#Social").load('/campaign_fb_content/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
			if(statusTxt == "success"){
				if($('#overview').find('.popup-inner').hasClass('dashboard_not_active')){
					sidebar('social','facebook');
				}else{
					sidebar('social');
					social_overview_scripts($('.campaign_id').val());
				}
			}else{
				sidebar('social');
			}
			$('#Social').find('.ajax-loader').removeClass('ajax-loader');
		});
		
	}

	$('.elem-left, .elem-right, .header-nav').removeClass('ajax-loader');
	
});

/*overview chart section*/

function pageAuthorityChart(campaignId){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_page_authority_chart",
		data:{campaignId},
		dataType:'json',
		success:function(result){
			if(window.myLinePageAuthority){
				window.myLinePageAuthority.destroy();
			}

			var ctxPageAuthority = document.getElementById('canvas-page-authority').getContext('2d');
			window.myLinePageAuthority = new Chart(ctxPageAuthority, configPageAuthority);
			var gradient = gradientColor(ctxPageAuthority);

			configPageAuthority.data.labels =  result['from_datelabel'];
			configPageAuthority.data.datasets[0].data = result['page'];
			configPageAuthority.data.datasets[0].backgroundColor = gradient;
			window.myLinePageAuthority.update();

			$('.page-authority').removeClass('ajax-loader');
		}
	});
}

function DomainAuthorityChart(campaignId){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_domain_authority_chart",
		data:{campaignId},
		dataType:'json',
		success:function(result){

			if(window.myLinedomainAuthority){
				window.myLinedomainAuthority.destroy();
			}

			var ctxdomainAuthority = document.getElementById('canvas-domain-authority').getContext('2d');
			window.myLinedomainAuthority = new Chart(ctxdomainAuthority, configDomainAuthority);
			var gradient = gradientColor(ctxdomainAuthority);

			configDomainAuthority.data.labels =  result['from_datelabel'];
			configDomainAuthority.data.datasets[0].data = result['domain'];
			configDomainAuthority.data.datasets[0].backgroundColor = gradient;
			window.myLinedomainAuthority.update();

			$('.domain_authority').removeClass('ajax-loader');
		}
	});
}


function organicKeywordsChart(campaignId){
	$.ajax({
		url:BASE_URL +'/ajax_organic_keyword_chart',
		dataType:'json',
		data:{campaignId},
		success:function(response){
			if(window.LineoragnicKeyword){
				window.LineoragnicKeyword.destroy();
			}

			var ctxOrganicKeyword = document.getElementById('canvas-organic-keyword').getContext('2d');
			window.LineoragnicKeyword = new Chart(ctxOrganicKeyword, configOrganicKeyword);
			var gradient = gradientColor(ctxOrganicKeyword);

			configOrganicKeyword.data.labels =  response['from_datelabel'];
			configOrganicKeyword.data.datasets[0].data = response['total_count'];
			configOrganicKeyword.data.datasets[0].backgroundColor = gradient;
			window.LineoragnicKeyword.update();

			$('.ok-graph').removeClass('ajax-loader');
		}
	});
}

function organicVisitorsChart(campaignId){
	$.ajax({
		url:BASE_URL +'/ajax_organic_visitors_chart',
		dataType:'json',
		data:{campaignId},
		success:function(response){
			if(window.LineoragnicVisitors){
				window.LineoragnicVisitors.destroy();
			}

			var ctxOrganicVisitor = document.getElementById('canvas-organic-visitor').getContext('2d');
			window.LineoragnicVisitors = new Chart(ctxOrganicVisitor, configOrganicVisitor);
			var gradient = gradientColor(ctxOrganicVisitor);

			configOrganicVisitor.data.labels =  response['labels'];
			configOrganicVisitor.data.datasets[0].data = response['visitors'];
			configOrganicVisitor.data.datasets[0].backgroundColor = gradient;
			window.LineoragnicVisitors.update();

			$('.ov-graph').removeClass('ajax-loader');
			
		}
	});
}


function referringDomainChart(campaignId){
	$.ajax({
		url:BASE_URL +'/ajax_referring_domain_chart',
		dataType:'json',
		data:{campaignId},
		success:function(response){
			if(window.LineReferringDomain){
				window.LineReferringDomain.destroy();
			}

			var ctxReferringDomain = document.getElementById('canvas-referring-domains').getContext('2d');
			window.LineReferringDomain = new Chart(ctxReferringDomain, configReferringDomain);
			var gradient = gradientColor(ctxReferringDomain);

			configReferringDomain.data.labels =  response['labels'];
			configReferringDomain.data.datasets[0].data = response['referringDomains'];
			configReferringDomain.data.datasets[0].backgroundColor = gradient;
			window.LineReferringDomain.update();

			$('.rd-graph').removeClass('ajax-loader');
		}
	});
}

/*overview chart section*/

// function ajaxReferringDomains(campaignId){
// 	$.ajax({
// 		type:"GET",
// 		url:BASE_URL+"/ajaxreferringdomains",
// 		data:{campaign_id: campaignId},
// 		dataType:'json',
// 		success:function(result){
// 			if(result != ''){
// 				if(result['avg'] != '??'){
// 					var rd_string = result['avg'].toString();
// 					rd_string = rd_string.replace(/,/g, "");
// 					if(rd_string > 0 ){
// 						$('.backlink_total').html(result['total']+'<cite class="backlink_avg "><span uk-icon="icon: triangle-up"></span>'+result['avg']+'<span class="dateFrom">Since Start</span></cite>');
// 						$('.backlink_avg').addClass("green");
// 					}else if(rd_string < 0 ){
// 						var replace_rd = rd_string.replace('-', '');
// 						$('.backlink_total').html(result['total']+'<cite class="backlink_avg "><span uk-icon="icon: triangle-down"></span>'+replace_rd+'<span class="dateFrom">Since Start</span></cite>');
// 						$('.backlink_avg').addClass("red");
// 					}else{
// 						$('.backlink_total').html(result['total']);
// 					}
// 				}else{
// 					$('.backlink_total').html(result['total']);
// 				}
				
// 				$('.rd-total').removeClass("ajax-loader");
// 				$('.rd-avg').removeClass("ajax-loader");
// 			}
// 		}
// 	});
// }


function ajaxOrganicKeywordRanking_bkp(campaignId){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajaxorganicKeywordRanking",
		data:{campaignId: campaignId},
		dataType:'json',
		success:function(result){	
			if(result['organic_keywords'] != '??'){
				var organic_keywords_string = result['organic_keywords'].toString();
				organic_keywords_string = organic_keywords_string.replace(/,/g, "");
				if(organic_keywords_string > 0 ){
					$('.organic-keyword-total').html(result['totalCount']+'<cite class="organic_keywords "><span uk-icon="icon: triangle-up"></span>'+result['organic_keywords']+'<span class="dateFrom">Since Start</span></cite>');
					$('.organic_keywords').addClass("green");
				}else if(organic_keywords_string < 0 ){
					var replace_organic_keywords = organic_keywords_string.replace('-', '');
					$('.organic-keyword-total').html(result['totalCount']+'<cite class="organic_keywords "><span uk-icon="icon: triangle-down"></span>'+replace_organic_keywords+'<span class="dateFrom">Since Start</span></cite>');
					$('.organic_keywords').addClass("red");
				}else{
					$('.organic-keyword-total').html(result['totalCount']);
				}
			}else{
				$('.organic-keyword-total').html(result['totalCount']);
			}

			$('.ok-total').removeClass("ajax-loader");
			$('.ok-avg').removeClass("ajax-loader");
		}
	});
}

function ajaxOrganicKeywordRanking(campaignId){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajaxorganicKeywordRanking",
		data:{campaignId: campaignId},
		dataType:'json',
		success:function(result){
			if(result['organic_keywords'] != '??'){
				var organic_keywords_string = result['organic_keywords'].toString();
				organic_keywords_string = organic_keywords_string.replace(/,/g, "");
				if(organic_keywords_string > 0 ){
					$('.organic-keyword-total').html(result['totalCount']);
					$('.organic_keywords').addClass("green");
					$('.organic_keywords').html('<img src="/public/vendor/internal-pages/images/up-stats-arrow.png" alt="up-stats-arrow"><span>'+result['organic_keywords']+'</span>Since Start');
				}else if(organic_keywords_string < 0 ){
					var replace_ok = organic_keywords_string.replace('-', '');
					$('.organic-keyword-total').html(result['totalCount']);
					$('.organic_keywords').addClass("red");
					$('.organic_keywords').html('<img src="/public/vendor/internal-pages/images/down-stats-arrow.png" alt="down-stats-arrow"><span>'+replace_ok+'</span>Since Start');
				}else{
					$('.organic-keyword-total').html(result['totalCount']);
					$('.organic_keywords').html('<span>'+result['organic_keywords']+'</span>Since Start');
				}
			}else{
				$('.organic-keyword-total').html(result['totalCount']);
			}

			$('.ok-total').removeClass("ajax-loader");
			$('.ok-avg').removeClass("ajax-loader");
		}
	});
}

// function ajaxGoogleTrafficGrowth_data(campaignId){
// 	$.ajax({
// 		type:"GET",
// 		url:$('.base_url').val()+"/ajax_organic_visitors",
// 		data:{campaignId: campaignId},
// 		dataType:'json',
// 		success:function(result){
// 			if(result['traffic_growth'] != '??'){
// 				var traffic_growth_string = result['traffic_growth'].toString();
// 				traffic_growth_string = traffic_growth_string.replace(/,/g, "");
// 				if(traffic_growth_string > 0 ){
// 					$('.organic-visitors-count').html(result['current_users']+'<cite class="organic_visitor_growth "><span uk-icon="icon: triangle-up"></span>'+result['traffic_growth']+'% <span class="dateFrom">Since Start</span></cite>');
// 					$('.organic_visitor_growth').addClass("green");
// 				}else if(traffic_growth_string < 0 ){
// 					var replace_traffic_growth = traffic_growth_string.replace('-', '');
// 					$('.organic-visitors-count').html(result['current_users']+'<cite class="organic_visitor_growth "><span uk-icon="icon: triangle-down"></span>'+replace_traffic_growth+'% <span class="dateFrom">Since Start</span></cite>');
// 					$('.organic_visitor_growth').addClass("red");
// 				}else{
// 					$('.organic-visitors-count').html(result['current_users']);
// 				}
// 			}else{
// 				$('.organic-visitors-count').html(result['current_users']);
// 			}

// 			$('.ov-total').removeClass("ajax-loader");
// 			$('.ov-avg').removeClass("ajax-loader");
// 		}
// 	});
// }



function console_query(campaignId,value){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_search_console_queries",
		data:{campaignId: campaignId,value:value},
		dataType:'json',
		success:function(result){
			if(result['query'] != ''){
				$('.query_table').html(result['query']);
			}

			$('.queries tr th').removeClass('ajax-loader');
			$('.queries tr td').removeClass('ajax-loader');
			$('.console-nav-bar').removeClass('ajax-loader');
		}
	});
}

function console_pages(campaignId,value){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_search_console_pages",
		data:{campaignId: campaignId,value:value},
		dataType:'json',
		success:function(result){
			if(result['page'] != ''){
				$('.pages_table').html(result['page']);
				
			}

			$('.pages tr th').removeClass('ajax-loader');
			$('.pages tr td').removeClass('ajax-loader');
		}
	});
}

function console_countries(campaignId,value){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_search_console_country",
		data:{campaignId: campaignId,value:value},
		dataType:'json',
		success:function(result){
			if(result['country'] != ''){
				$('.country_table').html(result['country']);
				
			}

			$('.countries tr th').removeClass('ajax-loader');
			$('.countries tr td').removeClass('ajax-loader');
		}
	});
}

function console_devices(campaignId,value){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_search_console_devices",
		data:{campaignId: campaignId,value:value},
		dataType:'json',
		success:function(result){
			if(result['device'] != ''){
				$('.device_table').html(result['device']);
				
			}

			$('.devices tr th').removeClass('ajax-loader');
			$('.devices tr td').removeClass('ajax-loader');
		}
	});
}

function console_graph(campaignId){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_search_console_graph",
		data:{campaignId: campaignId},
		dataType:'json',
		success:function(result){
			if(result['status'] == 0){
				$('#console_add').css('display','block');
				$('#console_data').css('display','none');
			} 

			if(result['status'] == 1){
				consoleChart(result['clicks'],result['impressions']);
				$('.search-console-graph').removeClass('ajax-loader');
				$('#console_add').css('display','none');
				$('#console_data').css('display','block');
			}	

		}
	});
}

function console_graphRank(campaignId){
	$('#console_data_rank-view').css('display','none');
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_search_console_graph",
		data:{campaignId: campaignId},
		dataType:'json',
		success:function(result){
			if(result['status'] == 0){
				$('#console_add').css('display','block');
				$('#console_data_rank').css('display','none');
				$('#console_data_rank-view').css('display','block');

			} 

			if(result['status'] == 1){
				consoleChartRank(result['clicks'],result['impressions']);
				$('.search-console-graph').removeClass('ajax-loader');
				$('#console_add').css('display','none');
				$('#console_data_rank-view').css('display','none');
				$('#console_data').css('display','block');
			}	

		}
	});
}



function consoleChart(clicks,impressions){
	if(window.myLineSearchConsole){
		window.myLineSearchConsole.destroy();
	}

	var ctxSearchConsole = document.getElementById('new-canvas-search-console').getContext('2d');
	window.myLineSearchConsole = new Chart(ctxSearchConsole, configSearchConsole);

	configSearchConsole.data.datasets[0].data = clicks;
	configSearchConsole.data.datasets[1].data = impressions;
	window.myLineSearchConsole.update();
}

function consoleChartRank(clicks,impressions){
	if(window.myLineSearchConsoleRank){
		window.myLineSearchConsoleRank.destroy();
	}

	var ctxSearchConsole = document.getElementById('new-canvas-search-console-rank').getContext('2d');
	window.myLineSearchConsoleRank = new Chart(ctxSearchConsole, configSearchConsoleRank);

	configSearchConsoleRank.data.datasets[0].data = clicks;
	configSearchConsoleRank.data.datasets[1].data = impressions;
	window.myLineSearchConsoleRank.update();

	$('.search-console-graph').removeClass('ajax-loader');
}


$(document).on('click','.searchConsole',function(){
	var value = $(this).attr('data-value');
	var module = $(this).attr('data-module');
	var campaignId = $('.campaignID').val();

	var userid = $('#user_id').val();
	var key = $('#encriptkey').val();

	$('.searchConsole').removeClass('active');
	$(this).addClass('active');
	
	$('.queries tr td').addClass('ajax-loader');
	$('.pages tr td').addClass('ajax-loader');
	$('.countries tr td').addClass('ajax-loader');
	$('.search-console-graph').addClass('ajax-loader');

	
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_search_console_graph_date_range",
		data:{value: value,module:module,campaignId:campaignId,key},
		dataType:'json',
		success:function(result){
			
			console_query(campaignId,value);
			console_pages(campaignId,value);
			
			console_countries(campaignId,value);
			consoleChart(result['clicks'],result['impressions']);

			$('.search-console-graph').removeClass('ajax-loader');
			$('.white-box-tab-head').removeClass('ajax-loader');
			$('.uk-subnav').removeClass('ajax-loader');
		}
	});


});

$(document).on('click','.searchConsole_view',function(){
	var value = $(this).attr('data-value');
	var module = $(this).attr('data-module');
	var campaignId = $('.campaignID').val();

	var userid = $('#user_id').val();

	$('.searchConsole_view').removeClass('active');
	$(this).addClass('active');

	$('.queries tr td').addClass('ajax-loader');
	$('.pages tr td').addClass('ajax-loader');
	$('.countries tr td').addClass('ajax-loader');
	$('.search-console-graph').addClass('ajax-loader');

	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_search_console_graph_date_range",
		data:{value: value,module:module,campaignId:campaignId},
		dataType:'json',
		success:function(result){

			console_query(campaignId,value);
			console_pages(campaignId,value);

			console_countries(campaignId,value);
			consoleChartRank(result['clicks'],result['impressions']);

			$('.search-console-graph').removeClass('ajax-loader');
			$('.white-box-tab-head').removeClass('ajax-loader');
			$('.uk-subnav').removeClass('ajax-loader');
		}
	});




});

function ajaxGoogleTrafficGrowthMetrics(campaignId){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_traffic_metrics",
		data:{campaignId: campaignId},
		dataType:'json',
		success:function(result){
			//session data
			var remove_total_sessions = result['total_sessions'].toString();
			var check_total_sessions = remove_total_sessions.replace('%', '');
			
			if(check_total_sessions > 0){
				var replace_total_sessions = result['total_sessions'];
				var session_arrow = 'up'; var session_class = 'green';
			}else if(check_total_sessions < 0){
				var string_total_sessions = result['total_sessions'].toString();
				var replace_total_sessions = string_total_sessions.replace('-', '');
				var session_arrow = 'down'; var session_class = 'red';
			}else{
				var replace_total_sessions = result['total_sessions'];
				var session_arrow = ''; var session_class = '';
			}
			$('.session-count').html('<span class="'+session_class+'" uk-icon="icon: arrow-'+session_arrow+'"></span>'+ replace_total_sessions);

			$('.compare-session').text(result['final_session']+ ' Organic Traffic');
			
			//user data
			var remove_total_users = result['total_users'].toString();
			var check_total_users = remove_total_users.replace('%', '');

			if(check_total_users > 0){
				var replace_total_users = result['total_users'];
				var users_arrow = 'up'; var users_class = 'green';
			}else if(check_total_users < 0){
				var string_total_users = result['total_users'].toString();
				var replace_total_users = string_total_users.replace('-', '');
				var users_arrow = 'down'; var users_class = 'red';
			}else{
				var replace_total_users = result['total_users'];
				var users_arrow = ''; var users_class = '';
			}
			$('.user-count').html('<span class="'+users_class+'" uk-icon="icon: arrow-'+users_arrow+'"></span>'+ replace_total_users);

			$('.compare-user').text(result['final_users']+ ' Organic Traffic');

			//pageviews data
			var remove_total_pageviews = result['total_pageviews'].toString();
			var check_total_pageviews = remove_total_pageviews.replace('%', '');

			if(check_total_pageviews > 0){
				var replace_total_pageviews = result['total_pageviews'];
				var pageviews_arrow = 'up'; var pageviews_class = 'green';
			}else if(check_total_pageviews < 0){
				var string_total_pageviews = result['total_pageviews'].toString();
				var replace_total_pageviews = string_total_pageviews.replace('-', '');
				var pageviews_arrow = 'down'; var pageviews_class = 'red';
			}else{
				var replace_total_pageviews = result['total_pageviews'];
				var pageviews_arrow = ''; var pageviews_class = '';
			}
			$('.pageview-count').html('<span class="'+pageviews_class+'" uk-icon="icon: arrow-'+pageviews_arrow+'"></span>'+ replace_total_pageviews);

			$('.compare-pageview').text(result['final_pageviews']+ ' Organic Traffic');

			$('.compare-session').removeClass('ajax-loader');
			$('.session-count').removeClass('ajax-loader');

			$('.user-count').removeClass('ajax-loader');
			$('.compare-user').removeClass('ajax-loader');
			$('.pageview-count').removeClass('ajax-loader');
			$('.compare-pageview').removeClass('ajax-loader');
		}
	});
}


function ajaxGoogleTrafficGrowthGraph(campaignId){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_traffic_data",
		data:{campaignId: campaignId},
		dataType:'json',
		success:function(result){


			if(result['status'] == 0){
				$('#analytics_add').css('display','block');
				$('#analytics_data').css('display','none');
			} 

			if(result['status'] == 1){
				highChartMapload(result);
				$('#analytics_add').css('display','none');
				$('#analytics_data').css('display','block');
			}	
			$('.traffic-growth-graph').removeClass('ajax-loader');
			$('.top-key-organic	').removeClass('ajax-loader');
		}
	});
}

function ajaxGoogleTrafficGrowthGraphRank(campaignId){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_traffic_data",
		data:{campaignId: campaignId},
		dataType:'json',
		success:function(result){


			if(result['status'] == 0){
				$('#analytics_add').css('display','block');
				$('#analytics_data').css('display','none');
				$('#analytics_data_traffic').css('display','none');
				$('#analytics_data-contact').css('display','block');
			} 

			if(result['status'] == 1){
				highChartMaploadRank(result);
				$('#analytics_add').css('display','none');
				$('#analytics_data').css('display','block');
				$('#analytics_data_traffic').css('display','block');
				$('#analytics_data-contact').css('display','none');
			}	
			$('.traffic-growth-graph').removeClass('ajax-loader');
			$('.top-key-organic	').removeClass('ajax-loader');
		}
	});
}


function highChartMaploadRank(result) {   
	if (window.myLineTrafficRank) {
		window.myLineTrafficRank.destroy();
	}
	var ctxTrafficGrowth = document.getElementById('new-canvas-traffic-growth-rank').getContext('2d');
	window.myLineTrafficRank = new Chart(ctxTrafficGrowth, configTrafficGrowthRank);

	configTrafficGrowthRank.data.labels =  result['from_datelabel'];
	configTrafficGrowthRank.data.datasets[0].label = result['current_period']+': Users';
	configTrafficGrowthRank.data.datasets[0].labels = result['from_datelabels'];
	configTrafficGrowthRank.data.datasets[0].data = result['count_session'];

	if(result['compare_status'] == 1){
		configTrafficGrowthRank.data.datasets.splice(1, 1);
		var newDataset = {
			label: result['previous_period']+': Users',
			labels: result['prev_from_datelabels'],
			borderColor: color(window.chartColors.orange).alpha(1.0).rgbString(),
			backgroundColor: color(window.chartColors.orange).alpha(0.15).rgbString(),
			data: result['combine_session'],
			fill: false,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		};

		configTrafficGrowthRank.data.datasets.push(newDataset);

	} else{
		configTrafficGrowthRank.data.datasets.splice(1, 1);

	}

	window.myLineTrafficRank.update();
}

function highChartMapload(result) {   
	if (window.myLineTraffic) {
		window.myLineTraffic.destroy();
	}
	var ctxTrafficGrowth = document.getElementById('new-canvas-traffic-growth').getContext('2d');
	window.myLineTraffic = new Chart(ctxTrafficGrowth, configTrafficGrowth);

	configTrafficGrowth.data.labels =  result['from_datelabel'];
	configTrafficGrowth.data.datasets[0].label = result['current_period'] +': Users';
	configTrafficGrowth.data.datasets[0].labels = result['from_datelabels'];
	configTrafficGrowth.data.datasets[0].data = result['count_session'];

	if(result['compare_status'] == 1){
		configTrafficGrowth.data.datasets.splice(1, 1);
		var newDataset = {
			label: result['previous_period'] +': Users',
			labels: result['prev_from_datelabels'],
			borderColor: color(window.chartColors.orange).alpha(1.0).rgbString(),
			backgroundColor: color(window.chartColors.orange).alpha(0.15).rgbString(),
			data: result['combine_session'],
			fill: false,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		};

		configTrafficGrowth.data.datasets.push(newDataset);

	} else{
		configTrafficGrowth.data.datasets.splice(1, 1);

	}

	window.myLineTraffic.update();
}


$(document).on('click','.graph_range_rank,.graph_range_view',function(){

	$('.compare-session').addClass('ajax-loader');
	$('.session-count').addClass('ajax-loader');
	$('.user-count').addClass('ajax-loader');
	$('.compare-user').addClass('ajax-loader');
	$('.pageview-count').addClass('ajax-loader');
	$('.compare-pageview').addClass('ajax-loader');
	$('.traffic-growth-graph').addClass('ajax-loader');

	var value = $(this).attr('data-value');
	var module = $(this).attr('data-module');
	var campaignId = $('.campaignID').val();
	var key = $('#encriptkey').val();
	var type = $('.organic_traffic_displayType_rank.blue-btn').attr('data-type');

	$('.graph_range_rank').removeClass('active');
	$('.graph_range_view').removeClass('active');

	$('.graph_range_rank[data-value="' + value + '"]').addClass('active');
	$('.graph_range_view[data-value="' + value + '"]').addClass('active');
	$(this).addClass('active');

	var compare_status = $('.analyticsGraphCompareRank').prop('checked');
	if(compare_status == true){
		var compare_value = 1;
	}else{
		var compare_value = 0;
	}
	

	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_analytics_daterange_data",
		data:{value: value,module:module,campaignId:campaignId,key,type,compare_value},
		dataType:'json',
		success:function(result){

			if(result['compare_status'] == '1'){
				$(".analyticsGraphCompareRank").prop("checked", true);
			}

			highChartMapload(result);
			highChartMaploadRank(result);
		}
	});

	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_traffic_date_range_metrics",
		data:{value: value,module:module,campaignId:campaignId},
		dataType:'json',
		success:function(result){
			var remove_total_sessions = result['total_sessions'].toString();
			var check_total_sessions = remove_total_sessions.replace('%', '');

			if(check_total_sessions > 0){
				var replace_total_sessions = result['total_sessions'];
				var session_arrow = 'up'; var session_class = 'green';
			}else if(check_total_sessions < 0){
				var string_total_sessions = result['total_sessions'].toString();
				var replace_total_sessions = string_total_sessions.replace('-', '');
				var session_arrow = 'down'; var session_class = 'red';
			}else{
				var replace_total_sessions = result['total_sessions'];
				var session_arrow = ''; var session_class = '';
			}
			$('.session-count').html('<span class="'+session_class+'" uk-icon="icon: arrow-'+session_arrow+'"></span>'+ replace_total_sessions);

			$('.compare-session').text(result['final_session']+ ' Organic Traffic');

				//user data
				var remove_total_users = result['total_users'].toString();
				var check_total_users = remove_total_users.replace('%', '');

				if(check_total_users > 0){
					var replace_total_users = result['total_users'];
					var users_arrow = 'up'; var users_class = 'green';
				}else if(check_total_users < 0){
					var string_total_users = result['total_users'].toString();
					var replace_total_users = string_total_users.replace('-', '');
					var users_arrow = 'down'; var users_class = 'red';
				}else{
					var replace_total_users = result['total_users'];
					var users_arrow = ''; var users_class = '';
				}
				$('.user-count').html('<span class="'+users_class+'" uk-icon="icon: arrow-'+users_arrow+'"></span>'+ replace_total_users);

				$('.compare-user').text(result['final_users']+ ' Organic Traffic');

				//pageviews data
				var remove_total_pageviews = result['total_pageviews'].toString();
				var check_total_pageviews = remove_total_pageviews.replace('%', '');

				if(check_total_pageviews > 0){
					var replace_total_pageviews = result['total_pageviews'];
					var pageviews_arrow = 'up'; var pageviews_class = 'green';
				}else if(check_total_pageviews < 0){
					var string_total_pageviews = result['total_pageviews'].toString();
					var replace_total_pageviews = string_total_pageviews.replace('-', '');
					var pageviews_arrow = 'down'; var pageviews_class = 'red';
				}else{
					var replace_total_pageviews = result['total_pageviews'];
					var pageviews_arrow = ''; var pageviews_class = '';
				}
				$('.pageview-count').html('<span class="'+pageviews_class+'" uk-icon="icon: arrow-'+pageviews_arrow+'"></span>'+ replace_total_pageviews);


				$('.compare-pageview').text(result['final_pageviews']+ ' Organic Traffic');

				$('.traffic-growth-graph').removeClass('ajax-loader');
				$('.compare-session').removeClass('ajax-loader');
				$('.session-count').removeClass('ajax-loader');

				$('.user-count').removeClass('ajax-loader');
				$('.compare-user').removeClass('ajax-loader');
				$('.pageview-count').removeClass('ajax-loader');
				$('.compare-pageview').removeClass('ajax-loader');

				$('.goal-completion-graph').addClass('ajax-loader');
				$('.compare').addClass('ajax-loader');
				$('.goal_completion_percentage').addClass('ajax-loader');
				// goalcompletion_data(campaignId);
				// ajaxGoogleTrafficGrowth_data(campaignId);
				// organicVisitorsChart(campaignId);

				/*goalCompletionChartGoals(campaignId);
				goalCompletionStatsGoals(campaignId);*/
				goalcompletion_dataGoals(campaignId);

			}
		});
	
});



$(document).on('click','.graph_range_viewkey',function(){

	$('.compare-session').addClass('ajax-loader');
	$('.session-count').addClass('ajax-loader');
	$('.user-count').addClass('ajax-loader');
	$('.compare-user').addClass('ajax-loader');
	$('.pageview-count').addClass('ajax-loader');
	$('.compare-pageview').addClass('ajax-loader');
	$('.traffic-growth-graph').addClass('ajax-loader');

	var type = $('.organic_traffic_displayType.blue-btn').attr('data-type');
	var value = $(this).attr('data-value');
	var module = $(this).attr('data-module');
	var campaignId = $('.campaignID').val();
	var key = $('#encriptkey').val();

	var compare_status = $('.analyticsGraphCompare').prop('checked');
	if(compare_status == true){
		var compare_value = 1;
	}else{
		var compare_value = 0;
	}


	$('.graph_range_viewkey').removeClass('active');
	$('.graph_range_viewkey[data-value="' + value + '"]').addClass('active');

	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_analytics_daterange_data",
		data:{value: value,compare_value,campaignId:campaignId,key,type:type},
		dataType:'json',
		success:function(result){

			/*if(key == 'undefined'){
				$("#analyticsFilters").load(location.href + " #analytic-filter-list");
				$("#GoalCompletionFilters").load(location.href + " #goal-filter-list");
			}*/
			$('.loader').remove();
			if(result['compare_status'] == '1'){
				$(".analyticsGraphCompare").prop("checked", true);
			}

			highChartMap(result);
		}
	});

	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_traffic_date_range_metrics",
		data:{value: value,module:module,campaignId:campaignId,key},
		dataType:'json',
		success:function(result){
			var remove_total_sessions = result['total_sessions'].toString();
			var check_total_sessions = remove_total_sessions.replace('%', '');
			
			if(check_total_sessions > 0){
				var replace_total_sessions = result['total_sessions'];
				var session_arrow = 'up'; var session_class = 'green';
			}else if(check_total_sessions < 0){
				var string_total_sessions = result['total_sessions'].toString();
				var replace_total_sessions = string_total_sessions.replace('-', '');
				var session_arrow = 'down'; var session_class = 'red';
			}else{
				var replace_total_sessions = result['total_sessions'];
				var session_arrow = ''; var session_class = '';
			}
			$('.session-count').html('<span class="'+session_class+'" uk-icon="icon: arrow-'+session_arrow+'"></span>'+ replace_total_sessions);
			$('.compare-session').text(result['final_session']+ ' Organic Traffic');

			//user data
			var remove_total_users = result['total_users'].toString();
			var check_total_users = remove_total_users.replace('%', '');

			if(check_total_users > 0){
				var replace_total_users = result['total_users'];
				var users_arrow = 'up'; var users_class = 'green';
			}else if(check_total_users < 0){
				var string_total_users = result['total_users'].toString();
				var replace_total_users = string_total_users.replace('-', '');
				var users_arrow = 'down'; var users_class = 'red';
			}else{
				var replace_total_users = result['total_users'];
				var users_arrow = ''; var users_class = '';
			}
			$('.user-count').html('<span class="'+users_class+'" uk-icon="icon: arrow-'+users_arrow+'"></span>'+ replace_total_users);

			$('.compare-user').text(result['final_users']+ ' Organic Traffic');

			//pageviews data
			var remove_total_pageviews = result['total_pageviews'].toString();
			var check_total_pageviews = remove_total_pageviews.replace('%', '');

			if(check_total_pageviews > 0){
				var replace_total_pageviews = result['total_pageviews'];
				var pageviews_arrow = 'up'; var pageviews_class = 'green';
			}else if(check_total_pageviews < 0){
				var string_total_pageviews = result['total_pageviews'].toString();
				var replace_total_pageviews = string_total_pageviews.replace('-', '');
				var pageviews_arrow = 'down'; var pageviews_class = 'red';
			}else{
				var replace_total_pageviews = result['total_pageviews'];
				var pageviews_arrow = ''; var pageviews_class = '';
			}
			$('.pageview-count').html('<span class="'+pageviews_class+'" uk-icon="icon: arrow-'+pageviews_arrow+'"></span>'+ replace_total_pageviews);


			$('.compare-pageview').text(result['final_pageviews']+ ' Organic Traffic');

			$('.traffic-growth-graph').removeClass('ajax-loader');
			$('.compare-session').removeClass('ajax-loader');
			$('.session-count').removeClass('ajax-loader');

			$('.user-count').removeClass('ajax-loader');
			$('.compare-user').removeClass('ajax-loader');
			$('.pageview-count').removeClass('ajax-loader');
			$('.compare-pageview').removeClass('ajax-loader');

			//goal completion section

			$('.goal-completion-graph').addClass('ajax-loader');
			$('.compare').addClass('ajax-loader');
			$('.goal_completion_percentage').addClass('ajax-loader');

			if($('#seoDashboardMore').length > 0){
				// goalcompletion_data(campaignId);
				goalcompletion_data_viewkey(value,compare_value,campaignId,key,type);
				ecommerce_goalcompletion_viewkey(value,compare_value,campaignId,key,type);
			}
			
			ajaxGoogleTrafficGrowth_data(campaignId);
			organicVisitorsChart(campaignId);

		}
	});
});	

$(document).on('click','.graph_range',function(){

	$('.compare-session').addClass('ajax-loader');
	$('.session-count').addClass('ajax-loader');
	$('.user-count').addClass('ajax-loader');
	$('.compare-user').addClass('ajax-loader');
	$('.pageview-count').addClass('ajax-loader');
	$('.compare-pageview').addClass('ajax-loader');
	$('.traffic-growth-graph').addClass('ajax-loader');

	var value = $(this).attr('data-value');
	var module = $(this).attr('data-module');
	var campaignId = $('.campaignID').val();
	var key = $('#encriptkey').val();

	var type = $('.organic_traffic_displayType.blue-btn').attr('data-type');
	var compare_status = $('.analyticsGraphCompare').prop('checked');
	if(compare_status == true){
		var compare_value = 1;
	}else{
		var compare_value = 0;
	}

	$('.graph_range').removeClass('active');
	$('.graph_range[data-value="' + value + '"]').addClass('active');	

	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_analytics_daterange_data",
		data:{value: value,compare_value,campaignId:campaignId,key,type:type},
		dataType:'json',
		success:function(result){
			if(key == undefined){
				//$("#analyticsFilters").load(location.href + " #analytic-filter-list");
				//$("#GoalCompletionFilters").load(location.href + " #goal-filter-list");
			}
			$('.loader').remove();
			if(result['compare_status'] == 1){
				$(".analyticsGraphCompare").prop("checked", true);
			}

			highChartMap(result);
		}
	});

	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_get_traffic_date_range_metrics",
		data:{value: value,module:module,campaignId:campaignId},
		dataType:'json',
		success:function(result){
			var remove_total_sessions = result['total_sessions'].toString();
			var check_total_sessions = remove_total_sessions.replace('%', '');
			
			if(check_total_sessions > 0){
				var replace_total_sessions = result['total_sessions'];
				var session_arrow = 'up'; var session_class = 'green';
			}else if(check_total_sessions < 0){
				var string_total_sessions = result['total_sessions'].toString();
				var replace_total_sessions = string_total_sessions.replace('-', '');
				var session_arrow = 'down'; var session_class = 'red';
			}else{
				var replace_total_sessions = result['total_sessions'];
				var session_arrow = ''; var session_class = '';
			}
			$('.session-count').html('<span class="'+session_class+'" uk-icon="icon: arrow-'+session_arrow+'"></span>'+ replace_total_sessions);
			
			$('.compare-session').text(result['final_session']+ ' Organic Traffic');

			//user data
			var remove_total_users = result['total_users'].toString();
			var check_total_users = remove_total_users.replace('%', '');

			if(check_total_users > 0){
				var replace_total_users = result['total_users'];
				var users_arrow = 'up'; var users_class = 'green';
			}else if(check_total_users < 0){
				var string_total_users = result['total_users'].toString();
				var replace_total_users = string_total_users.replace('-', '');
				var users_arrow = 'down'; var users_class = 'red';
			}else{
				var replace_total_users = result['total_users'];
				var users_arrow = ''; var users_class = '';
			}
			$('.user-count').html('<span class="'+users_class+'" uk-icon="icon: arrow-'+users_arrow+'"></span>'+ replace_total_users);

			$('.compare-user').text(result['final_users']+ ' Organic Traffic');

			//pageviews data
			var remove_total_pageviews = result['total_pageviews'].toString();
			var check_total_pageviews = remove_total_pageviews.replace('%', '');

			if(check_total_pageviews > 0){
				var replace_total_pageviews = result['total_pageviews'];
				var pageviews_arrow = 'up'; var pageviews_class = 'green';
			}else if(check_total_pageviews < 0){
				var string_total_pageviews = result['total_pageviews'].toString();
				var replace_total_pageviews = string_total_pageviews.replace('-', '');
				var pageviews_arrow = 'down'; var pageviews_class = 'red';
			}else{
				var replace_total_pageviews = result['total_pageviews'];
				var pageviews_arrow = ''; var pageviews_class = '';
			}
			$('.pageview-count').html('<span class="'+pageviews_class+'" uk-icon="icon: arrow-'+pageviews_arrow+'"></span>'+ replace_total_pageviews);


			$('.compare-pageview').text(result['final_pageviews']+ ' Organic Traffic');

			$('.traffic-growth-graph').removeClass('ajax-loader');
			$('.compare-session').removeClass('ajax-loader');
			$('.session-count').removeClass('ajax-loader');

			$('.user-count').removeClass('ajax-loader');
			$('.compare-user').removeClass('ajax-loader');
			$('.pageview-count').removeClass('ajax-loader');
			$('.compare-pageview').removeClass('ajax-loader');

			//goal completion section

			$('.goal-completion-graph').addClass('ajax-loader');
			$('.compare').addClass('ajax-loader');
			$('.goal_completion_percentage').addClass('ajax-loader');
			if($('#seoDashboardMore').length > 0){
				goalcompletion_dataGoals(campaignId);
			}else{
				goalcompletion_data(campaignId);
				ecommerce_goalcompletion(campaignId);
			}
			
			// ajaxGoogleTrafficGrowth_data(campaignId);
			// organicVisitorsChart(campaignId);

		}
	});
});

function goalcompletion_data(campaignId){
	$('.goal-completion-graph').addClass('ajax-loader');
	goalCompletionChart(campaignId);
	$('.compare').addClass('ajax-loader');
	$('.goal_completion_percentage').addClass('ajax-loader');
	goalCompletionStats(campaignId);
	$('#goal_completion_location tr').addClass('ajax-loader');
	goal_completion_location(campaignId,1);
	$('#goal_completion_location tr').addClass('ajax-loader');
	goal_completion_sourcemedium(campaignId,1);
	$('.allUserGraph').addClass('ajax-loader');
	$('.allUserGraph').show();
	$('#goal-completion-all-users').hide();
	all_users_chart(campaignId);
	$('.goalValueGraph').addClass('ajax-loader');
	$('.goalValueGraph').show();
	$('#goal-value-all-users').hide();
	goal_value_chart(campaignId);
	$('.goalConversionRateGraph').addClass('ajax-loader');
	$('.goalConversionRateGraph').show();
	$('#goal-conversion-all-users').hide();
	goal_conversion_rate_chart(campaignId);
	$('.goalAbandonRateGraph').addClass('ajax-loader');
	$('.goalAbandonRateGraph').show();
	$('#goal-abondon-all-users').hide();
	goal_abondon_rate_chart(campaignId);
	$('.OrganicGraph').addClass('ajax-loader');
	$('.OrganicGraph').show();
	$('#goal-completion-organic').hide();
	goal_completion_chart_organic(campaignId);
	$('.ValueOrganicGraph').addClass('ajax-loader');
	$('.ValueOrganicGraph').show();
	$('#goal-value-organic-chart').hide();
	goal_value_chart_organic(campaignId);
	$('.ConversionRateOrganicGraph').addClass('ajax-loader');
	$('.ConversionRateOrganicGraph').show();
	$('#goal-conversionRate-organic-chart').hide();
	goal_conversionRate_chart_organic(campaignId);
	$('.AbondonRateOrganicGraph').addClass('ajax-loader');
	$('.AbondonRateOrganicGraph').show();
	$('#goal-abondonRate-organic-chart').hide();
	goal_abondonRate_chart_organic(campaignId);
}

function ecommerce_goalcompletion(campaignId){
	$('.ecom-goal-completion-graph').addClass('ajax-loader');
	ecom_goalCompletionChart(campaignId);
	ecom_goalCompletionStats(campaignId);
	ecom_conversion_rate_users(campaignId);
	ecom_conversionRate_organic(campaignId);
	ecom_transactionUsers(campaignId);
	ecom_transactionOrganic(campaignId);
	ecom_revenueUsers(campaignId);
	ecom_revenueOrganic(campaignId);
	ecom_avg_orderValue_users(campaignId);
	ecom_avg_orderValue_organic(campaignId);

	$('#ecom_product tr').addClass('ajax-loader');
	ecom_product_list(campaignId,1);
}

function goalcompletion_dataGoals(campaignId){
	$('.goal-completion-graph').addClass('ajax-loader');
	goalCompletionChartGoals(campaignId);
	$('.compare').addClass('ajax-loader');
	$('.goal_completion_percentage').addClass('ajax-loader');
	goalCompletionStatsGoals(campaignId);
	$('#goal_completion_location tr').addClass('ajax-loader');
	goal_completion_location(campaignId,1);
	$('#goal_completion_location tr').addClass('ajax-loader');
	goal_completion_sourcemedium(campaignId,1);
	$('.allUserGraph').addClass('ajax-loader');
	$('.allUserGraph').show(); 
	$('#goal-completion-all-users').hide();
	all_users_chartGoals(campaignId);
	$('.goalValueGraph').addClass('ajax-loader');
	$('.goalValueGraph').show();
	$('#goal-value-all-users').hide();
	goal_value_chartGoals(campaignId);
	$('.goalConversionRateGraph').addClass('ajax-loader');
	$('.goalConversionRateGraph').show();
	$('#goal-conversion-all-users').hide();
	goal_conversion_rate_chartGoals(campaignId);
	$('.goalAbandonRateGraph').addClass('ajax-loader');
	$('.goalAbandonRateGraph').show();
	$('#goal-abondon-all-users').hide();
	goal_abondon_rate_chartGoals(campaignId); 
	$('.OrganicGraph').addClass('ajax-loader');
	$('.OrganicGraph').show();
	$('#goal-completion-organic').hide();
	goal_completion_chart_organicGoals(campaignId);
	$('.ValueOrganicGraph').addClass('ajax-loader');
	$('.ValueOrganicGraph').show();
	$('#goal-value-organic-chart').hide();
	goal_value_chart_organicGoals(campaignId); 
	$('.ConversionRateOrganicGraph').addClass('ajax-loader');
	$('.ConversionRateOrganicGraph').show();
	$('#goal-conversionRate-organic-chart').hide();
	goal_conversionRate_chart_organicGoals(campaignId);
	$('.AbondonRateOrganicGraph').addClass('ajax-loader');
	$('.AbondonRateOrganicGraph').show();
	$('#goal-abondonRate-organic-chart').hide();
	goal_abondonRate_chart_organicGoals(campaignId);
}


function highChartMap(result){
	if (window.myLineTraffic) {
		window.myLineTraffic.destroy();
	}
	var ctxTrafficGrowth = document.getElementById('new-canvas-traffic-growth').getContext('2d');
	window.myLineTraffic = new Chart(ctxTrafficGrowth, configTrafficGrowth);

	window.myLineTraffic.data.labels =  result['from_datelabel'];
	window.myLineTraffic.data.datasets[0].label = result['current_period']+': Users';
	window.myLineTraffic.data.datasets[0].labels = result['from_datelabels'];
	window.myLineTraffic.data.datasets[0].data = result['count_session'];

	// if(result['compare_status'] == '1'){
	// 	window.myLineTraffic.data.datasets[1].borderColor =  color(window.chartColors.orange).alpha(1.0).rgbString();
	// 	window.myLineTraffic.data.datasets[1].backgroundColor =  color(window.chartColors.orange).alpha(0.15).rgbString();
	// 	window.myLineTraffic.data.datasets[1].fill =  false;
	// 	window.myLineTraffic.data.datasets[1].label = result['previous_period']+': Users';
	// 	window.myLineTraffic.data.datasets[1].labels = result['prev_from_datelabels'];
	// 	window.myLineTraffic.data.datasets[1].data = result['combine_session'];
	// }


	if(result['compare_status'] == 1){
		window.myLineTraffic.data.datasets.splice(1, 1);
		var newDataset = {
			label: result['previous_period'] +': Users',
			labels: result['prev_from_datelabels'],
			borderColor: color(window.chartColors.orange).alpha(1.0).rgbString(),
			backgroundColor: color(window.chartColors.orange).alpha(0.15).rgbString(),
			data: result['combine_session'],
			fill: false,
			pointHoverRadius: 5,
			pointHoverBackgroundColor: 'white',
			borderWidth:2
		};

		window.myLineTraffic.data.datasets.push(newDataset);

	} else{
		window.myLineTraffic.data.datasets.splice(1, 1);

	}







	window.myLineTraffic.update();

}


$(document).on('change','.analyticsGraphCompare',function(e){
	e.preventDefault();

	var key = $('#encriptkey').val();
	$('.compare-session').addClass('ajax-loader');
	$('.session-count').addClass('ajax-loader');
	$('.user-count').addClass('ajax-loader');
	$('.compare-user').addClass('ajax-loader');
	$('.pageview-count').addClass('ajax-loader');
	$('.compare-pageview').addClass('ajax-loader');
	$('.traffic-growth-graph').addClass('ajax-loader');

	var request_id  = $('.campaignID').val();
	var compare_status = $(this).is(':checked');
	if(compare_status == true){
		var compare_value = 1;
	}else{
		var compare_value = 0;
	}

	var type = $('.organic_traffic_displayType.blue-btn').attr('data-type');

	if(key !== undefined){
		var range = $('.graph_range_viewkey.active').attr('data-value');
	}else{
		var range = $('.graph_range.active').attr('data-value');
	}

	$.ajax({
		type:'GET',
		dataType:'json',
		data:{value: range,campaignId:request_id,key,type,compare_value},
		url:BASE_URL+"/ajax_get_analytics_daterange_data",
		success:function(result){
			highChartMapload(result);
			if(result['compare_status'] == 1){
				$('.analyticsGraphCompare').prop('checked',true);
			}
			if(key == undefined){
				// $("#analyticsFilters").load(location.href + " #analytic-filter-list");
				// $("#GoalCompletionFilters").load(location.href + " #goal-filter-list");
				goalcompletion_data(request_id);
				ecommerce_goalcompletion(request_id);
			}


			
			$('.loader').remove();

			if($('#seoDashboardMore').length > 0){
				goalcompletion_data(request_id);
			}
			
			

			$('.compare-session').removeClass('ajax-loader');
			$('.session-count').removeClass('ajax-loader');
			$('.user-count').removeClass('ajax-loader');
			$('.compare-user').removeClass('ajax-loader');
			$('.pageview-count').removeClass('ajax-loader');
			$('.compare-pageview').removeClass('ajax-loader');
			$('.traffic-growth-graph').removeClass('ajax-loader');

		},
		error:function(error){
			console.log('error: '+error);
		}
	});



});


$(document).on('change','.analyticsGraphCompareRank',function(e){
	e.preventDefault();

	$('.compare-session').addClass('ajax-loader');
	$('.session-count').addClass('ajax-loader');
	$('.user-count').addClass('ajax-loader');
	$('.compare-user').addClass('ajax-loader');
	$('.pageview-count').addClass('ajax-loader');
	$('.compare-pageview').addClass('ajax-loader');
	$('.traffic-growth-graph').addClass('ajax-loader');

	var request_id  = $('.campaignID').val();
	var compare_status = $(this).is(':checked');
	if(compare_status == true){
		var compare_value = 1;
	}else{
		var compare_value = 0;
	}

	var key = $('#encriptkey').val();
	var range = $('.graph_range_rank.active').attr('data-value');
	var type = $('.organic_traffic_displayType_rank.blue-btn').attr('data-type');

	$.ajax({
		type:'GET',
		dataType:'json',
		// data:{request_id:request_id,compare_value:compare_value},
		// url:BASE_URL+"/ajax_get_compare_traffic_data",
		url:BASE_URL+"/ajax_get_analytics_daterange_data",
		data:{value: range,campaignId:request_id,key,type,compare_value},
		success:function(result){
			highChartMaploadRank(result);

			$("#analyticsFiltersRank").load(location.href + " #analytic-filter-list-rank");
			$('.loader').remove();
			

			$('.compare-session').removeClass('ajax-loader');
			$('.session-count').removeClass('ajax-loader');
			$('.user-count').removeClass('ajax-loader');
			$('.compare-user').removeClass('ajax-loader');
			$('.pageview-count').removeClass('ajax-loader');
			$('.compare-pageview').removeClass('ajax-loader');
			$('.traffic-growth-graph').removeClass('ajax-loader');

		},
		error:function(error){
			console.log('error: '+error);
		}
	});



});


//if not connected pop-ups (Adwords)
$('#detail_adwords_existing_emails').on('show.bs.select', function (e, clickedIndex, isSelected, previousValue) {	
	$('#detail_adwords_existing_emails').removeClass('addAdsDetail');
});
$(document).on('change','#detail_adwords_existing_emails',function(e){
	e.preventDefault();
	$(this).removeClass('addAdsDetail');
	var email = $(this).val();
	var campaign_id = $('.campaign_id').val();

	fetch_last_updated(email,campaign_id,'ppc');
	$('.ppc_refresh_div').css('display','block');
	$('.detail-adwords-loader').css('display','block');

	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_adwords_accounts',
		data:{email,campaign_id},
		success:function(response){
			$('#detail_adwords_accounts').html(response);
			$('.selectpicker').selectpicker('refresh');
			$('.detail-adwords-loader').css('display','none');
		}
	});
});


$(document).on('click','#refresh_ppc_account_detail',function(e){
	$(this).addClass('refresh-gif');
	$('#detail_save_adwords_account').attr('disabled','disabled');
	$('#detail_add_new_adwords_account').attr('disabled','disabled');

	$('.popup-inner').css('overflow','hidden');

	var email = $('#detail_adwords_existing_emails').val();
	var campaign_id = $('.campaign_id').val();
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_refresh_ppc_acccount_list',
		data:{email,campaign_id},
		dataType:'json',
		success:function(response){
			$('.ppc-progress-loader').css('display','block');

			$('#show_ppc_last_time').parent().removeClass('error');
			$('#show_ppc_last_time').parent().removeClass('green');
			$('#show_ppc_last_time').parent().addClass('yellow');
			$('#show_ppc_last_time').parent().css('display','block');
			document.getElementById('show_ppc_last_time').innerHTML = '<p>Fetching list of accounts.</p>';

			if(response['status'] == 1){
				$.ajax({
					type:'GET',
					url:BASE_URL +'/ajax_get_adwords_accounts',
					data:{email:$('#detail_adwords_existing_emails').val(),campaign_id:$('.campaign_id').val()},
					success:function(response){
						$('#settings_adwords_accounts').html(response);
						$('.selectpicker').selectpicker('refresh');
					}
				});


				$('.ppc-progress-loader').addClass('complete');
				
				$('#show_ppc_last_time').parent().removeClass('error');
				$('#show_ppc_last_time').parent().removeClass('yellow');
				$('#show_ppc_last_time').parent().addClass('green');
				$('#show_ppc_last_time').parent().css('display','block');
				document.getElementById('show_ppc_last_time').innerHTML = response['time'];
				
			}

			if(response['status'] == 0){

				$('#show_ppc_last_time').parent().removeClass('green');
				$('#show_ppc_last_time').parent().removeClass('yellow');
				$('#show_ppc_last_time').parent().addClass('error');
				$('#show_ppc_last_time').parent().css('display','block');
				document.getElementById('show_ppc_last_time').innerHTML = response['time'];				
			}

			if(response['status'] == 2){
				
				$('#show_ppc_last_time').parent().removeClass('green');
				$('#show_ppc_last_time').parent().removeClass('yellow');
				$('#show_ppc_last_time').parent().addClass('error');
				$('#show_ppc_last_time').parent().css('display','block');
				document.getElementById('show_ppc_last_time').innerHTML = response['time'];
			}

			setTimeout(function(){
				$('#refresh_ppc_account_detail').removeClass('refresh-gif');
				$('.ppc-progress-loader').css('display','none');
				$('.ppc-progress-loader').removeClass('complete');
				$('.popup-inner').css('overflow','auto');
				$('#detail_save_adwords_account').removeAttr('disabled','disabled');
				$('#detail_add_new_adwords_account').removeAttr('disabled','disabled');
			}, 1000);
		}
	});
});

$(document).on('click','#detail_save_adwords_account',function(e){
	e.preventDefault();	
	var email = $('#detail_adwords_existing_emails').val();
	var account = $('#detail_adwords_accounts').val();
	var campaign_id = $('.campaign_id').val();

	

	if(email == ''){
		$('#detail_adwords_existing_emails').parent().addClass('error');
	}else{
		$('#detail_adwords_existing_emails').parent().removeClass('error');
	}
	if(account == ''){
		$('#detail_adwords_accounts').parent().addClass('error');
	}else{
		$('#detail_adwords_accounts').parent().removeClass('error');
	}

	if(email != '' && account !=''){
		$(this).attr('disabled','disabled');
		$('.ppc-progress-loader').css('display','block');	
		$('.popup-inner').css('overflow','hidden');
		$.ajax({
			type:'POST',
			url:BASE_URL + '/ajax_update_adwords_data',
			data:{campaign_id,email,account,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				
				if (response['status'] == 'success') {
					$('.ppc-progress-loader').addClass('complete');
					Command: toastr["success"]('Adwords Account Connected successfully!');
					$("#detail_adwords_close").trigger("click");
					$("body").removeClass("popup-open");

					//displaying preparing pop-up
					//$('#preparingPPCDashboard').trigger('click');
					$('#preparingPPCDashboard').css('display', 'block');
					
					$.ajax({
						type:'POST',
						url:BASE_URL + '/ajax_update_adwords_data',
						data:{campaign_id,email,account,_token:$('meta[name="csrf-token"]').attr('content')},
						dataType:'json',
						success:function(response){
							console.log("success");
						}
					});
					
					// $('body').addClass('popup-open');
					setTimeout(function() {
						/*$("#preparingDashboard_close").trigger("click");
						$("body").removeClass("popup-open");*/
						$('#preparingPPCDashboard').css('display', 'none');
						loadPPCSection(campaign_id);
						$('#PPC').load('/campaign_ppc_content/' + campaign_id);
						setTimeout(function () {
							ppc_Scripts($('.account_id').val(),campaign_id);		
						},1000);
					},20000);

				} else {
					Command: toastr["error"]('Please try again getting error');
				}

				setTimeout(function(){
					$('.ppc-progress-loader').css('display','none');
					$('.ppc-progress-loader').removeClass('complete');
					$('.popup-inner').css('overflow','auto');
				}, 1000);
			}
		});
	}
});

$(document).on('click','.detail_AdwordsAddBtn',function(e){
	e.preventDefault();
	var campaignId = $('.campaign_id').val();
	var currentRoute = $('.currentRoute').val();
	$('#detail_adwords_existing_emails').addClass('addAdsDetail');
	// var link = BASE_URL +'/connect_google_ads?campaignId='+campaignId+'&redirectPage='+currentRoute;
	var link = BASE_URL +'/ppc/connect?campaignId='+campaignId+'&redirectPage='+currentRoute;
	myDetailPopup(link,"web","500","500");
});

function loadPPCSection(campaign_id){
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_get_adwords_account_id',
		data:{campaign_id},
		dataType:'json',
		success:function(response){
			$('.account_id').val(response);
		}
	});
}


setInterval(function(){
	if($('#detail_adwords_existing_emails').hasClass('addAdsDetail')){
		setTimeout(function(){
			getAdwordsAccounts();
		}, 5000);
	}
}, 1000);

function getAdwordsAccounts(){
	$.ajax({
		url:BASE_URL+'/ajax_adwords_accounts',
		data:{user_id:$('.user_id').val()},
		type:'GET',
		success:function(response){
			$('#detail_adwords_existing_emails').html(response);
			$('.selectpicker').selectpicker('refresh');
		}
	})
}




//if not connected pop-ups (Analytics)

$(document).on('change','#detail_analytics_existing_emails',function(e){
	e.preventDefault();
	$('#detail_analytics_existing_emails').removeClass('addAnalyticsDetail');
	var email = $(this).val();
	var campaign_id = $('.campaign_id').val();

	fetch_last_updated(email,campaign_id,'google_analytics');
	$('.analytics_refresh_div').css('display','block');
	$('.analytic-account-detail-loader').css('display','block');
	disableSelectPicker('#detail_analytics_accounts');
	$('.detail_ecommerce_goals').prop('checked',false);

	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_analytics_accounts',
		data:{email,campaign_id},
		success:function(response){
			enableSelectPicker('#detail_analytics_accounts');
			$('#detail_analytics_accounts').html(response);
			$('#detail_analytics_property').html('<option value="">Select Property</option>');
			$('#detail_analytics_view').html('<option value="">Select View</option>');
			$('.selectpicker').selectpicker('refresh');
			$('.analytic-account-detail-loader').css('display','none');
		}
	});
});

$(document).on('change','#detail_analytics_accounts',function(e){

	e.preventDefault();
	var account_id = $(this).val();
	var campaign_id = $('.campaign_id').val();
	$('.analytic-property-detail-loader').css('display','block');
	disableSelectPicker('#detail_analytics_property');

	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_analytics_property',
		data:{account_id,campaign_id},
		success:function(response){
			enableSelectPicker('#detail_analytics_property');
			$('#detail_analytics_property').html(response);
			$('.selectpicker').selectpicker('refresh');
			var li    = '<option value="">Select View</option>';
			$('#detail_analytics_view').html(li);
			$('.analytic-property-detail-loader').css('display','none');
		}
	});
});

$(document).on('change','#detail_analytics_property',function(e){
	e.preventDefault();
	var property_id = $(this).val();
	var campaign_id = $('.campaign_id').val();
	$('.analytic-view-detail-loader').css('display','block');
	disableSelectPicker('#detail_analytics_view');

	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_analytics_view',
		data:{property_id,campaign_id},
		success:function(response){
			enableSelectPicker('#detail_analytics_view');
			$('#detail_analytics_view').html(response);
			$('.selectpicker').selectpicker('refresh');
			$('.analytic-view-detail-loader').css('display','none');
		}
	});
});

$(document).on('click','#refresh_analytics_account_detail',function(e){
	e.preventDefault();
	$(this).addClass('refresh-gif');
	$('#save_detail_analytics_account').attr('disabled','disabled');
	$('#detail_add_new_analytics_account').attr('disabled','disabled');
	$('.popup-inner').css('overflow','hidden');

	var email = $('#detail_analytics_existing_emails').val();
	var campaign_id = $('.campaign_id').val();

	$('.analytics-progress-loader').css('display','block');
	
	$('#show_analytics_last_time').parent().removeClass('error');
	$('#show_analytics_last_time').parent().removeClass('green');
	$('#show_analytics_last_time').parent().addClass('yellow');
	$('#show_analytics_last_time').parent().css('display','block');
	document.getElementById('show_analytics_last_time').innerHTML = 'Fetching list of accounts.';
	$('.detail_ecommerce_goals').prop('checked',false);
	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_refresh_analytics_acccount_list',
		data:{email,campaign_id},
		dataType:'json',
		success:function(response){

			if(response['status'] == 1){
				$.ajax({
					type:'GET',
					url:BASE_URL +'/ajax_get_analytics_accounts',
					data:{email:$('#detail_analytics_existing_emails').val(),campaign_id:$('.campaign_id').val()},
					success:function(result){
						$('#settings_analytics_accounts').html(result);
						$('.selectpicker').selectpicker('refresh');
						var li    = '<option value="">Select Property</option>';
						$('#settings_analytics_property').html(li);
						var li    = '<option value="">Select View</option>';
						$('#settings_analytics_view').html(li);
					}
				});


				$('.analytics-progress-loader').addClass('complete');

				$('#refresh_analytics_account_detail').removeClass('refresh-gif');
				$('#save_detail_analytics_account').removeAttr('disabled','disabled');
				$('#detail_add_new_analytics_account').removeAttr('disabled','disabled');

				$('#show_analytics_last_time').parent().removeClass('error');
				$('#show_analytics_last_time').parent().removeClass('yellow');
				$('#show_analytics_last_time').parent().addClass('green');
				$('#show_analytics_last_time').parent().css('display','block');
				document.getElementById('show_analytics_last_time').innerHTML = response['message'];
				
			}

			if(response['status'] == 0){
				$('#refresh_analytics_account_detail').removeClass('refresh-gif');
				$('#save_detail_analytics_account').removeAttr('disabled','disabled');
				$('#detail_add_new_analytics_account').removeAttr('disabled','disabled');
				
				$('#show_analytics_last_time').parent().removeClass('yellow');
				$('#show_analytics_last_time').parent().removeClass('green');
				$('#show_analytics_last_time').parent().addClass('error');
				$('#show_analytics_last_time').parent().css('display','block');
				document.getElementById('show_analytics_last_time').innerHTML = response['message'];
			}

			if(response['status'] == 2){
				$('#refresh_analytics_account_detail').removeClass('refresh-gif');
				$('#save_detail_analytics_account').removeAttr('disabled','disabled');
				$('#detail_add_new_analytics_account').removeAttr('disabled','disabled');

				$('#show_analytics_last_time').parent().removeClass('yellow');
				$('#show_analytics_last_time').parent().removeClass('green');
				$('#show_analytics_last_time').parent().addClass('error');
				$('#show_analytics_last_time').parent().css('display','block');
				document.getElementById('show_analytics_last_time').innerHTML = response['message'];

			}

			setTimeout(function(){
				$('.analytics-progress-loader').css('display','none');
				$('.analytics-progress-loader').removeClass('complete');
				$('.popup-inner').css('overflow','auto');
			}, 1000);
		}
	});

});

$(document).on('click','#save_detail_analytics_account',function(e){
	var backlinkSelectdChart = $('.backlinkSelectdChart').val();
	var currentUrl = $('.currentRoute').val();
	var campaign_id = $('.campaign_id').val();
	var email = $('#detail_analytics_existing_emails').val();
	var account = $('#detail_analytics_accounts').val();
	var property = $('#detail_analytics_property').val();
	var view = $('#detail_analytics_view').val();
	var e_com = $('.detail_ecommerce_goals').prop('checked');
	

	if(email == ''){
		$('#detail_analytics_existing_emails').parent().addClass('error');
	}else{
		$('#detail_analytics_existing_emails').parent().removeClass('error');
	}
	if(account == ''){
		$('#detail_analytics_accounts').parent().addClass('error');
	}else{
		$('#detail_analytics_accounts').parent().removeClass('error');
	}
	if(property == ''){
		$('#detail_analytics_property').parent().addClass('error');
	}else{
		$('#detail_analytics_property').parent().removeClass('error');
	}
	if(view == ''){
		$('#detail_analytics_view').parent().addClass('error');
	}else{
		$('#detail_analytics_view').parent().removeClass('error');
	}
	

	if(email != '' && account !='' && property !='' && view !=''){
		$(this).attr('disabled','disabled');
		$('.analytics-progress-loader').css('display','block');
		$('.popup-inner').css('overflow','hidden');
		$.ajax({
			type:'POST',
			url:BASE_URL + '/ajax_update_analytics_data',
			data:{campaign_id,email,account,property,view,_token:$('meta[name="csrf-token"]').attr('content'),e_com},
			success:function(response){
				if(response['status'] == 'success'){
					$('.analytics-progress-loader').addClass('complete');
					Command: toastr["success"]('Google Analytics connected successfully');
					$("#detail_analytics_close").trigger("click");
					$("body").removeClass("popup-open");
					//displaying preparing pop-up
					$('#preparingAnalytics').trigger('click');
					$('#preparingAnalytics').css('display', 'block');
					$('body').addClass('popup-open');
					setTimeout(function() {
						// $("#preparingAnalytics_close").trigger("click");
						$('#preparingAnalytics').css('display', 'none');
						$("body").removeClass("popup-open");
						$('#SEO').load('/campaign_seo_content/' + campaign_id);
						setTimeout(function () {
							seo_Scripts(campaign_id,currentUrl,'',backlinkSelectdChart);		
						},1000);
					},10000);

				} 


				if(response['status'] == 'google-error') {
					Command: toastr["error"](response['message']);
				}


				if(response['status'] == 'error') {
					Command: toastr["error"]('Please try again getting error');
				}

				$('#save_detail_analytics_account').removeAttr('disabled','disabled');

				setTimeout(function(){
					$('.analytics-progress-loader').css('display','none');
					$('.analytics-progress-loader').removeClass('complete');
					$('.popup-inner').css('overflow','auto');
				}, 1000);
			}
		});
	}
});



$('#detail_analytics_existing_emails').on('show.bs.select', function (e, clickedIndex, isSelected, previousValue) {	
	$('#detail_analytics_existing_emails').removeClass('addAnalyticsDetail');
});

setInterval(function(){
	if($('#detail_analytics_existing_emails').hasClass('addAnalyticsDetail')){
		getAnalyticsAccounts();
	}
}, 3000);

function getAnalyticsAccounts(){
	$.ajax({
		url:BASE_URL+'/ajax_google_analytics_accounts',
		data:{user_id:$('.user_id').val()},
		type:'GET',
		success:function(response){
			$('#detail_analytics_existing_emails').html(response);
			$('.selectpicker').selectpicker('refresh');
		}
	})
}


$(document).on('click','.detail_analyticsAddBtn',function(){
	var campaignId = $('.campaign_id').val();
	var currentRoute = $('.currentRoute').val();
	$('#detail_analytics_existing_emails').addClass('addAnalyticsDetail');
	var link = BASE_URL +'/connect_google_analytics?campaignId='+campaignId+'&provider=google&redirectPage='+currentRoute;
	myDetailPopup(link,"web","500","500");
});



//if not connected pop-ups (Search Console)
$(document).on('change','#detail_search_console_existing_emails',function(e){
	e.preventDefault();
	$('#detail_search_console_existing_emails').removeClass('addSearchAppend');
	var backlinkSelectdChart = $('.backlinkSelectdChart').val();
	var email = $(this).val();
	var campaign_id = $('.campaign_id').val();

	fetch_last_updated(email,campaign_id,'search_console');
	$('.search_console_refresh_div').css('display','block');
	$('.sc-detail-loader').css('display','block');

	$.ajax({
		type:'GET',
		url:BASE_URL +'/ajax_get_console_urls',
		data:{email,campaign_id},
		success:function(response){
			$('#detail_search_console_urlaccounts').html(response);
			$('.selectpicker').selectpicker('refresh');
			$('.sc-detail-loader').css('display','none');
		}
	});
});


$(document).on('click','#refresh_search_console_detail',function(e){
	e.preventDefault();
	$(this).addClass('refresh-gif');
	$('#detail_save_console_account').attr('disabled','disabled');
	$('#detail_add_new_console_account').attr('disabled','disabled');
	$('.popup-inner').css('overflow','hidden');

	var email = $('#detail_search_console_existing_emails').val();
	var campaign_id = $('.campaign_id').val();
	$('.searchConsole-progress-loader').css('display','block');
	
	$('#show_search_console_last_time').parent().removeClass('error');
	$('#show_search_console_last_time').parent().removeClass('green');
	$('#show_search_console_last_time').parent().addClass('yellow');
	$('#show_search_console_last_time').parent().css('display','block');
	document.getElementById('show_search_console_last_time').innerHTML = 'Fetching list of accounts.';

	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_refresh_search_console_urls',
		data:{email,campaign_id},
		dataType:'json',
		success:function(response){

			if(response['status'] == 1){
				$.ajax({
					type:'GET',
					url:BASE_URL +'/ajax_get_console_urls',
					data:{email:$('#detail_search_console_existing_emails').val(),campaign_id:$('.campaign_id').val()},
					success:function(result){
						$('#settings_search_console_urlaccounts').html(result);
						$('.selectpicker').selectpicker('refresh');
					}
				});


				$('.searchConsole-progress-loader').addClass('complete');

				$('#refresh_search_console_detail').removeClass('refresh-gif');
				$('#detail_save_console_account').removeAttr('disabled','disabled');
				$('#detail_add_new_console_account').removeAttr('disabled','disabled');

				$('#show_search_console_last_time').parent().removeClass('error');
				$('#show_search_console_last_time').parent().removeClass('yellow');
				$('#show_search_console_last_time').parent().addClass('green');
				$('#show_search_console_last_time').parent().css('display','block');
				document.getElementById('show_search_console_last_time').innerHTML = response['message'];
				
			}

			if(response['status'] == 0){
				$('#refresh_search_console_detail').removeClass('refresh-gif');
				$('#detail_save_console_account').removeAttr('disabled','disabled');
				$('#detail_add_new_console_account').removeAttr('disabled','disabled');
				
				$('#show_search_console_last_time').parent().removeClass('yellow');
				$('#show_search_console_last_time').parent().removeClass('green');
				$('#show_search_console_last_time').parent().addClass('error');
				$('#show_search_console_last_time').parent().css('display','block');
				document.getElementById('show_search_console_last_time').innerHTML = response['message'];
			}

			if(response['status'] == 2){
				$('#refresh_search_console_detail').removeClass('refresh-gif');
				$('#detail_save_console_account').removeAttr('disabled','disabled');
				$('#detail_add_new_console_account').removeAttr('disabled','disabled');

				$('#show_search_console_last_time').parent().removeClass('yellow');
				$('#show_search_console_last_time').parent().removeClass('green');
				$('#show_search_console_last_time').parent().addClass('error');
				$('#show_search_console_last_time').parent().css('display','block');
				document.getElementById('show_search_console_last_time').innerHTML = response['message'];

			}

			setTimeout(function(){
				$('.searchConsole-progress-loader').css('display','none');
				$('.searchConsole-progress-loader').removeClass('complete');
				$('.popup-inner').css('overflow','auto');
			}, 1000);
		}
	});
});

$(document).on('click','#detail_save_console_account',function(e){
	e.preventDefault();
	var currentUrl = $('.currentRoute').val();
	var campaign_id = $('.campaign_id').val();
	var email = $('#detail_search_console_existing_emails').val();
	var account = $('#detail_search_console_urlaccounts').val();

	
	
	if(email == ''){
		$('#detail_search_console_existing_emails').parent().addClass('error');
	}else{
		$('#detail_search_console_existing_emails').parent().removeClass('error');
	}
	if(account == ''){
		$('#detail_search_console_urlaccounts').parent().addClass('error');
	}else{
		$('#detail_search_console_urlaccounts').parent().removeClass('error');
	}



	if(email != '' && account !=''){
		$(this).attr('disabled','disabled');
		$('.searchConsole-progress-loader').css('display','block');
		$('.popup-inner').css('overflow','hidden');
		$.ajax({
			type:'POST',
			url:BASE_URL + '/ajax_update_console_data',
			data:{campaign_id,email,account,_token:$('meta[name="csrf-token"]').attr('content')},
			dataType:'json',
			success:function(response){
				if (response['status'] == 'success') {
					$('.searchConsole-progress-loader').addClass('complete');
					Command: toastr["success"]('Console Account Connected successfully!');
					$("#CampaignDetailConsole_close").trigger("click");
					$("body").removeClass("popup-open");

					//displaying preparing pop-up
					$('#preparingConsoleDashboard').trigger('click');
					$('#preparingConsoleDashboard').css('display', 'block');
					$('body').addClass('popup-open');
					setTimeout(function() {
						// $("#preparingConsoleDashboard_close").trigger("click");
						$('#preparingConsoleDashboard').css('display', 'none');
						$("body").removeClass("popup-open");
						$('#SEO').load('/campaign_seo_content/' + campaign_id);
						setTimeout(function () {
							seo_Scripts(campaign_id,currentUrl,'',$('.backlinkSelectdChart').val());			
						},1000);
					},10000);
					

				} else if (response['status'] == 'google-error') {
					Command: toastr["error"](response['message']);
				} else {
					Command: toastr["error"]('Please try again getting error');
				}

				$('#detail_save_console_account').removeAttr('disabled');

				setTimeout(function(){
					$('#show_search_console_last_time').parent().removeClass('error');
					$('#show_search_console_last_time').parent().removeClass('yellow');
					$('#show_search_console_last_time').parent().removeClass('green');
					$('#show_search_console_last_time').parent().css('display','none');

					$('.searchConsole-progress-loader').css('display','none');
					$('.searchConsole-progress-loader').removeClass('complete');
					$('.popup-inner').css('overflow','auto');
				}, 1000);
			}
		});
	}
});


$(document).on('click','.detail_searchConsoleAddBtn',function(){
	var campaignId = $('.campaign_id').val();
	var currentRoute = $('.currentRoute').val();
	$('#detail_search_console_existing_emails').addClass('addSearchAppend');
	var link = BASE_URL +'/connect_search_console?campaignId='+campaignId+'&redirectPage='+currentRoute;
	myDetailPopup(link,"web","500","500");
});

$('#detail_search_console_existing_emails').on('show.bs.select', function (e, clickedIndex, isSelected, previousValue) {	
	$('#detail_search_console_existing_emails').removeClass('addSearchAppend');
});

setInterval(function(){
	if($('#detail_search_console_existing_emails').hasClass('addSearchAppend')){
		// setTimeout(function(){
			getSearchConsoleAccounts();
		// }, 5000);
	}
}, 1000);


function getSearchConsoleAccounts(){
	$.ajax({
		url:BASE_URL+'/ajax_google_cnsole_accounts',
		data:{user_id:$('.user_id').val()},
		type:'GET',
		success:function(response){
			$('.selectpicker').selectpicker('refresh');
			$('#detail_search_console_existing_emails').html(response);
			$('.selectpicker').selectpicker('refresh');
		}
	})
}


function myDetailPopup(myURL, title, myWidth, myHeight) {
	var left = (screen.width - myWidth) / 2;
	var top = (screen.height - myHeight) / 4;
	window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
}

function sidebar(selected,active) {
	
	var dashType = selected;
	var active = active;
	$('.view-sidebar').load('/project-detail/sidebar/' + $('#encriptkey').val()+'/'+dashType+'/'+active);
	$('.view-ajax-tabs').removeClass('ajax-loader');
	$('.view-sidebar').find('ul li a').removeClass('ajax-loader');

}

function removeLoaders(){
	$('.heading').removeClass('ajax-loader');
	$('.filter-list, .top-key-organic').removeClass('ajax-loader');
	$('h6,figure,p').removeClass('ajax-loader');


}

$(document).on('click','.dashboardActivate',function(e){

	var dashboard = $(this).data("type");
	var request_id = $(this).data("id");

	$.ajax({
		type:'POST',
		url:BASE_URL + '/dashboard-activate',
		data:{request_id,dashboard,_token:$('meta[name="csrf-token"]').attr('content')},
		dataType:'json',
		success:function(response){
			if(dashboard == 'SEO'){
				$("#SEO").load('/campaign_seo_content/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
					if(statusTxt == "success")
						if ($('#SEO').find('.main-data').find('#seo_add').length == 0) {
							var value = '';
							var currentUrl = window.location.pathname;
							seo_Scripts($('.campaignID').val(),currentUrl,value,$('.backlinkSelectdChart').val());
						}
						
						if(statusTxt == "error")
							console.log("Error: " + xhr.status + ": " + xhr.statusText);
					});
			}

			if(dashboard == 'PPC'){
				$("#PPC").load('/campaign_ppc_content/' + $('.campaign_id').val(), function(responseTxt, statusTxt, xhr){
					if(statusTxt == "success")
						if ($('#PPC').find('.main-data').find('#adwords_add').length == 0) {
							ppc_Scripts($('.account_id').val(),$('.campaign_id').val());
						}
						if(statusTxt == "error")
							console.log("Error: " + xhr.status + ": " + xhr.statusText);
					});
			}

			if(dashboard == 'GMB'){
				$("#GMB").load('/campaign_gmb_content/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
					if(statusTxt == "success")
						if ($('#GMB').find('.main-data').find('#gmb_add').length == 0) {
							gmb_scripts($('.campaign_id').val());
						}
						if(statusTxt == "error")
							console.log("Error: " + xhr.status + ": " + xhr.statusText);
					});
			}

			if(dashboard == 'Social'){
				$("#Social").load('/campaign_social_content/' + $('.campaignID').val(), function(responseTxt, statusTxt, xhr){
					if(statusTxt == "success")
						if($('#Social').find('.main-data').find('#social_accounts').length == 0){
							social_overview_scripts($('.campaign_id').val());
						}
						if(statusTxt == "error")
							console.log("Error: " + xhr.status + ": " + xhr.statusText);
				});
			}

		}
	});
	
});

/*$(window).scroll(function() {
  if($(window).scrollTop() + $(window).height() >= $(document).height()){
     var selected = $("a",this).attr('href');
     $('#seoDashboard').append('<div id="seoDashboardMore"></div>');
     $('#seoDashboardMore').load('/campaign_seo_content_viewmore/' + $('.campaign_id').val());
     value ='';
     var currentUrl = window.location.pathname;
		setTimeout(function () {
			
			seo_Scripts_viewmore($('.campaignID').val(),currentUrl,value,null);	
		},1000);
			
     
     console.log(selected);

  }
});*/


// $(document).on('click','#refresh_search_console_section',function(e){
// 	e.preventDefault();
// 	var campaign_id = $(this).attr('data-request-id');
// 	$(this).addClass('refresh-gif');


// 	$('.queries tr td').addClass('ajax-loader');
// 	$('.pages tr td').addClass('ajax-loader');
// 	$('.countries tr td').addClass('ajax-loader');
// 	$('.search-console-graph').addClass('ajax-loader');

// 	$.ajax({
// 		type:'GET',
// 		url:BASE_URL+'/ajax_get_latest_console_data',
// 		data:{campaign_id},
// 		dataType:'json',
// 		success:function(response){
// 			if(response['status'] == 'success'){
// 				//setTimeout(function () {
// 					GoogleUpdateTimeAgo('search_console');
// 					console_graph(campaign_id);		
// 					console_query(campaign_id,'');
// 					console_pages(campaign_id,'');
// 					console_countries(campaign_id,'');
// 					getConsoleErrorMessages(campaign_id);
// 				//},1000);
// 			}
// 			else if(response['status'] == 'google-error'){
// 				$('html,body').animate({scrollTop: $("#searchconsoleHeading").offset().top},'slow');
// 				$('#searchconsoleHeading').html('');
// 				$('#searchconsoleHeading').append('<div class="alert alert-danger"><span><i class="fa fa-exclamation-triangle"></i>Search Console: '+response['message']+' Try reconnecting your account.</span></div>');
// 			}
// 			else{
// 				Command: toastr["error"]('Error, please try again later.');

// 			}

// 			$('#refresh_search_console_section').removeClass('refresh-gif');
// 		}
// 	});
// });


$(document).on('click','#refresh_organicTraffic_section',function(e){
	e.preventDefault();
	var campaign_id = $(this).attr('data-request-id');
	$(this).addClass('refresh-gif');
	/*show loaders for summary section data*/
	add_analytics_loaders();
	

	$.ajax({
		type:'GET',
		url:BASE_URL+'/ajax_get_latest_organic_traffic_trend',
		data:{campaign_id},
		dataType:'json',
		success:function(response){
			if(response['status'] == 'success'){
				GoogleUpdateTimeAgo('analytics');
				organicVisitorsChart(campaign_id);
				ajaxGoogleTrafficGrowth_data(campaign_id);
				goal_completion_chart_overview(campaign_id);
				goal_completion_stats_overview(campaign_id);
				ajaxGoogleTrafficGrowthMetrics(campaign_id);
				ajaxGoogleTrafficGrowthGraph(campaign_id);
				goalcompletion_data(campaign_id);
				getAnalyticsErrorMessages(campaign_id);
				ecommerce_goalcompletion(campaign_id);
			}
			if(response['status'] == 'google-error'){
				$('html,body').animate({scrollTop: $(".organicTrafficGrowthHeading").offset().top},'slow');
				$('.organicTrafficGrowthHeading').html('');
				$('.organicTrafficGrowthHeading').append('<div class="alert alert-danger"><span><i class="fa fa-exclamation-triangle"></i>Google Analytics: '+response+' Try reconnecting your account.</span></div>');
			}
			if(response['status'] == 'error'){
				Command: toastr["error"]('Error, please try again later.');
			}
			$('#refresh_organicTraffic_section').removeClass('refresh-gif');
			remove_analytics_loaders();
		}
	});
});

function add_analytics_loaders(){
	$('.ov-total').addClass("ajax-loader");
	$('.ov-avg').addClass("ajax-loader");
	$('.ov-graph').addClass('ajax-loader');
	$('#canvas-organic-visitor').hide();
	$('.goalToal').addClass("ajax-loader");
	$('.goal').addClass("ajax-loader");
	$('.gc-organic').addClass('ajax-loader');
	$('.gc-organic').show();
	$('.gc-overview-organic').addClass('ajax-loader');
	$('.gc-overview-organic').show();
	$('#google-goal-completion').hide();

	/*show loaders for google analytics section */
	$('.compare-session').addClass('ajax-loader');
	$('.session-count').addClass('ajax-loader');
	$('.user-count').addClass('ajax-loader');
	$('.compare-user').addClass('ajax-loader');
	$('.pageview-count').addClass('ajax-loader');
	$('.compare-pageview').addClass('ajax-loader');
	$('.traffic-growth-graph').addClass('ajax-loader');

	/*show loaders for goal completion section*/
	$('.goal-completion-graph').addClass('ajax-loader');
	$('.compare').addClass('ajax-loader');
	$('.goal_completion_percentage').addClass('ajax-loader');
	$('#goal_completion_location tr').addClass('ajax-loader');
	$('#goal_completion_location tr').addClass('ajax-loader');
	$('.allUserGraph').addClass('ajax-loader');
	$('.allUserGraph').show();
	$('#goal-completion-all-users-new').hide();
	$('.goalValueGraph').addClass('ajax-loader');
	$('.goalValueGraph').show();
	$('#goal-value-all-users-new').hide();
	$('.goalConversionRateGraph').addClass('ajax-loader');
	$('.goalConversionRateGraph').show();
	$('#goal-conversion-all-users-new').hide();
	$('.goalAbandonRateGraph').addClass('ajax-loader');
	$('.goalAbandonRateGraph').show();
	$('#goal-abondon-all-users-new').hide();
	$('.OrganicGraph').addClass('ajax-loader');
	$('.OrganicGraph').show();
	$('#goal-completion-organic-new').hide();
	$('.ValueOrganicGraph').addClass('ajax-loader');
	$('.ValueOrganicGraph').show();
	$('#goal-value-organic-chart-new').hide();
	$('.ConversionRateOrganicGraph').addClass('ajax-loader');
	$('.ConversionRateOrganicGraph').show();
	$('#goal-conversionRate-organic-chart-new').hide();
	$('.AbondonRateOrganicGraph').addClass('ajax-loader');
	$('.AbondonRateOrganicGraph').show();
	$('#goal-abondonRate-organic-chart-new').hide();
	$('.goalCompletion-location-foot').html('');
	$('.GoalComp-Location').addClass('ajax-loader');

	/*show loaders for ecommerce goal section*/
	$('.ecom-goal-completion-graph').addClass('ajax-loader');
	$('.ecom_conversion_percentage').addClass('ajax-loader');
	$('.ecom_conversion_percentage_organic').addClass('ajax-loader');
	$('.ecom_transaction_percentage_users').addClass('ajax-loader');
	$('.ecom_transaction_percentage_organic').addClass('ajax-loader');
	$('.ecom_revenue_users_percentage').addClass('ajax-loader');
	$('.ecom_revenue_organic_percentage').addClass('ajax-loader');
	$('.ecom_orderValue_users_percentage').addClass('ajax-loader');
	$('.ecom_orderValue_organic_percentage').addClass('ajax-loader');
	$('.ecom_conversion').addClass('ajax-loader');
	$('.ecom_conversion').show();
	$('#ecom-conversion-rate-graph-users').hide();
	$('.ecom_transactionUsers').addClass('ajax-loader');
	$('.ecom_transactionUsers').show();
	$('#ecom-transaction-users').hide();
	$('.ecom_RevenueUsers').addClass('ajax-loader');
	$('.ecom_RevenueUsers').show();
	$('#ecom-revenue-users').hide();
	$('.ecom_avgorderValue_users').addClass('ajax-loader');
	$('.ecom_avgorderValue_users').show();
	$('#ecom-orderValue-users').hide();
	$('.ecom_conversionOrganic').addClass('ajax-loader');
	$('.ecom_conversionOrganic').show();
	$('#ecom-conversion-rate-graph-organic').hide();
	$('.ecom_transactionOrganic').addClass('ajax-loader');
	$('.ecom_transactionOrganic').show();
	$('#ecom-transaction-organic').hide();
	$('.ecom_RevenueOrganic').addClass('ajax-loader');
	$('.ecom_RevenueOrganic').show();
	$('#ecom-revenue-organic').hide();
	$('.ecom_avgorderValue_organic').addClass('ajax-loader');
	$('.ecom_avgorderValue_organic').show();
	$('#ecom-orderValue-organic').hide();

	$('#ecom_product tr td').addClass('ajax-loader');
	$('.ecom-product').addClass('ajax-loader');
	$('#ecom_showing_pagination').addClass('ajax-loader');
}

function remove_analytics_loaders(){
	$('.ov-total').removeClass("ajax-loader");
	$('.ov-avg').removeClass("ajax-loader");
	$('.ov-graph').removeClass('ajax-loader');
	$('#canvas-organic-visitor').show();
	$('.goalToal').removeClass("ajax-loader");
	$('.goal').removeClass("ajax-loader");
	$('.gc-organic').removeClass('ajax-loader');
	$('.gc-organic').hide();
	$('.gc-overview-organic').removeClass('ajax-loader');
	$('.gc-overview-organic').hide();
	$('#google-goal-completion').show();

	/*show loaders for google analytics section */
	$('.compare-session').removeClass('ajax-loader');
	$('.session-count').removeClass('ajax-loader');
	$('.user-count').removeClass('ajax-loader');
	$('.compare-user').removeClass('ajax-loader');
	$('.pageview-count').removeClass('ajax-loader');
	$('.compare-pageview').removeClass('ajax-loader');
	$('.traffic-growth-graph').removeClass('ajax-loader');

	/*show loaders for goal completion section*/
	$('.goal-completion-graph').removeClass('ajax-loader');
	$('.compare').removeClass('ajax-loader');
	$('.goal_completion_percentage').removeClass('ajax-loader');
	$('#goal_completion_location tr').removeClass('ajax-loader');
	$('#goal_completion_location tr').removeClass('ajax-loader');
	$('.allUserGraph').removeClass('ajax-loader');
	$('.allUserGraph').hide();
	$('#goal-completion-all-users-new').show();
	$('.goalValueGraph').removeClass('ajax-loader');
	$('.goalValueGraph').hide();
	$('#goal-value-all-users-new').show();
	$('.goalConversionRateGraph').removeClass('ajax-loader');
	$('.goalConversionRateGraph').hide();
	$('#goal-conversion-all-users-new').show();
	$('.goalAbandonRateGraph').removeClass('ajax-loader');
	$('.goalAbandonRateGraph').hide();
	$('#goal-abondon-all-users-new').show();
	$('.OrganicGraph').removeClass('ajax-loader');
	$('.OrganicGraph').hide();
	$('#goal-completion-organic-new').show();
	$('.ValueOrganicGraph').removeClass('ajax-loader');
	$('.ValueOrganicGraph').hide();
	$('#goal-value-organic-chart-new').show();
	$('.ConversionRateOrganicGraph').removeClass('ajax-loader');
	$('.ConversionRateOrganicGraph').hide();
	$('#goal-conversionRate-organic-chart-new').show();
	$('.AbondonRateOrganicGraph').removeClass('ajax-loader');
	$('.AbondonRateOrganicGraph').hide();
	$('#goal-abondonRate-organic-chart-new').show();
	$('.goalCompletion-location-foot').html('');
	$('.GoalComp-Location').removeClass('ajax-loader');

	/*show loaders for ecommerce goal section*/
	$('.ecom-goal-completion-graph').removeClass('ajax-loader');
	$('.ecom_conversion_percentage').removeClass('ajax-loader');
	$('.ecom_conversion_percentage_organic').removeClass('ajax-loader');
	$('.ecom_transaction_percentage_users').removeClass('ajax-loader');
	$('.ecom_transaction_percentage_organic').removeClass('ajax-loader');
	$('.ecom_revenue_users_percentage').removeClass('ajax-loader');
	$('.ecom_revenue_organic_percentage').removeClass('ajax-loader');
	$('.ecom_orderValue_users_percentage').removeClass('ajax-loader');
	$('.ecom_orderValue_organic_percentage').removeClass('ajax-loader');
	$('.ecom_conversion').removeClass('ajax-loader');
	$('.ecom_conversion').hide();
	$('#ecom-conversion-rate-graph-users').show();
	$('.ecom_transactionUsers').removeClass('ajax-loader');
	$('.ecom_transactionUsers').hide();
	$('#ecom-transaction-users').show();
	$('.ecom_RevenueUsers').removeClass('ajax-loader');
	$('.ecom_RevenueUsers').hide();
	$('#ecom-revenue-users').show();
	$('.ecom_avgorderValue_users').removeClass('ajax-loader');
	$('.ecom_avgorderValue_users').hide();
	$('#ecom-orderValue-users').show();
	$('.ecom_conversionOrganic').removeClass('ajax-loader');
	$('.ecom_conversionOrganic').hide();
	$('#ecom-conversion-rate-graph-organic').show();
	$('.ecom_transactionOrganic').removeClass('ajax-loader');
	$('.ecom_transactionOrganic').hide();
	$('#ecom-transaction-organic').show();
	$('.ecom_RevenueOrganic').removeClass('ajax-loader');
	$('.ecom_RevenueOrganic').hide();
	$('#ecom-revenue-organic').show();
	$('.ecom_avgorderValue_organic').removeClass('ajax-loader');
	$('.ecom_avgorderValue_organic').hide();
	$('#ecom-orderValue-organic').show();

	$('#ecom_product tr td').removeClass('ajax-loader');
	$('.ecom-product').removeClass('ajax-loader');
	$('#ecom_showing_pagination').removeClass('ajax-loader');
}


function GoogleUpdateTimeAgo(moduleType){
	$.ajax({
		type: 'GET',
		url:  BASE_URL + '/ajax_get_google_updated_time',
		data: {request_id:$('.campaignID').val(),moduleType},
		dataType: 'json',
		success: function(response) {
			if(response['status'] == 1){
				if(response['search_console_time'] !=''){
					$('.search_console_time').html(response['search_console_time']);
				}

				if(response['analytics_time'] !=''){
					$('.analytics_time').html(response['analytics_time']);
				}

				if(response['gmb_time'] !=''){
					$('.gmb_time').html(response['gmb_time']);
				}

				if(response['adwords_time'] !=''){
					$('.adwords_time').html(response['adwords_time']);
				}

				if(response['ga4_time'] != ''){
					$('.ga4_time').html(response['ga4_time']);
				}

				if(response['facebook_time'] != ''){
					var arr = response['facebook_time'].split('_');
					$('.facebook_time').attr('uk-tooltip','title: Last Updated: '+arr[0]+arr[1]);
					$('.facebook_time').html('<span uk-icon="clock" ></span>'+ arr[0]);
				}
			}
		}
	});
}

$(document).on('click','.organic_traffic_displayType',function(e){
	e.preventDefault();
	var type = $(this).attr('data-type');
	
	$('.organic_traffic_displayType').removeClass('blue-btn');
	$('.organic_traffic_displayType[data-type="' + type + '"]').addClass('blue-btn');


	var campaignId = $('.campaignID').val();
	var key = $('#encriptkey').val();

	if(key !== undefined){
		var range = $('.graph_range_viewkey.active').attr('data-value');
	}else{
		var range = $('.graph_range.active').attr('data-value');
	}


	var compare_status = $('.analyticsGraphCompare').prop('checked');
	if(compare_status == true){
		var compare_value = 1;
	}else{
		var compare_value = 0;
	}

	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_analytics_daterange_data",
		data:{value: range,campaignId,key,type,compare_value},
		dataType:'json',
		success:function(result){
			$('.loader').remove();
			highChartMap(result);

			if(key == undefined){
				if(result['compare_status'] == 1){
					$(".analyticsGraphCompare").prop("checked", true);
				}else if(result['compare_status'] == 0){
					$(".analyticsGraphCompare").prop("checked", false);
				}
				goalcompletion_data(campaignId);
				ecommerce_goalcompletion(campaignId);

			}
		}
	});

});


$(document).on('click','.organic_traffic_displayType_rank',function(e){
	e.preventDefault();
	var type = $(this).attr('data-type');
	
	$('.organic_traffic_displayType_rank').removeClass('blue-btn');
	$('.organic_traffic_displayType_rank[data-type="' + type + '"]').addClass('blue-btn');


	var campaignId = $('.campaignID').val();
	var key = $('#encriptkey').val();
	var range = $('.graph_range_rank.active').attr('data-value');

	var compare_status = $('.analyticsGraphCompareRank').prop('checked');
	if(compare_status == true){
		var compare_value = 1;
	}else{
		var compare_value = 0;
	}

	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_analytics_daterange_data",
		data:{value: range,campaignId,key,type,compare_value},
		dataType:'json',
		success:function(result){


			if(result['status'] == 0){
				$('#analytics_add').css('display','block');
				$('#analytics_data').css('display','none');
			} 

			if(result['status'] == 1){
				highChartMaploadRank(result);
				$('#analytics_add').css('display','none');
				$('#analytics_data').css('display','block');
			}	
			$('.traffic-growth-graph').removeClass('ajax-loader');
			$('.top-key-organic	').removeClass('ajax-loader');
		}
	});

});


$(document).on('change','.analyticsGraphCompare_goal',function(e){
	e.preventDefault();

	var key = $('#encriptkey').val();
	$('.compare-session').addClass('ajax-loader');
	$('.session-count').addClass('ajax-loader');
	$('.user-count').addClass('ajax-loader');
	$('.compare-user').addClass('ajax-loader');
	$('.pageview-count').addClass('ajax-loader');
	$('.compare-pageview').addClass('ajax-loader');
	$('.traffic-growth-graph').addClass('ajax-loader');

	var request_id  = $('.campaignID').val();
	var compare_status = $(this).is(':checked');

	if(compare_status == true){
		var compare_value = 1;
	}else{
		var compare_value = 0;
	}

	var type = $('.traffic_display_typeGoal.blue-btn').attr('data-type');
	var range = $('.graph_range_viewkey.active').attr('data-value');

	$.ajax({
		type:'GET',
		dataType:'json',
		data:{value: range,campaignId:request_id,key,type,compare_value},
		url:BASE_URL+"/ajax_get_analytics_daterange_data",
		success:function(result){
			highChartMapload(result);
			
			if(result['compare_status'] == 1 || result['compare_status'] == '1'){
				$('.analyticsGraphCompare_goal').prop('checked');
				$('.analyticsGraphCompare').prop('checked');
			}else if(result['compare_status'] == 0){
				$('.analyticsGraphCompare_goal').removeProp('checked');
				$('.analyticsGraphCompare').removeProp('checked');
			}
			
			goalcompletion_data_viewkey(range,compare_value,request_id,key,type);
			ecommerce_goalcompletion_viewkey(range,compare_value,request_id,key,type);
			$('.loader').remove();	
			$('.compare-session').removeClass('ajax-loader');
			$('.session-count').removeClass('ajax-loader');
			$('.user-count').removeClass('ajax-loader');
			$('.compare-user').removeClass('ajax-loader');
			$('.pageview-count').removeClass('ajax-loader');
			$('.compare-pageview').removeClass('ajax-loader');
			$('.traffic-growth-graph').removeClass('ajax-loader');

		},
		error:function(error){
			console.log('error: '+error);
		}
	});
});

function goalcompletion_data_viewkey(value,compare_value,campaignId,key,type){
	$('.goal-completion-graph').addClass('ajax-loader');
	goalCompletionChart_vk(value,compare_value,campaignId,key,type);
	$('.compare').addClass('ajax-loader');
	$('.goal_completion_percentage').addClass('ajax-loader');
	goalCompletionStats_vk(value,compare_value,campaignId,key,type);
	$('#goal_completion_location tr').addClass('ajax-loader');
	goal_completion_location_vk(value,compare_value,campaignId,key,type,1);
	$('#goal_completion_location tr').addClass('ajax-loader');
	goal_completion_sourcemedium_vk(value,compare_value,campaignId,key,type,1);
	$('.allUserGraph').addClass('ajax-loader');
	$('.allUserGraph').show();
	$('#goal-completion-all-users').hide();
	all_users_chart(campaignId,value,'viewkey');
	$('.goalValueGraph').addClass('ajax-loader');
	$('.goalValueGraph').show();
	$('#goal-value-all-users').hide();
	goal_value_chart(campaignId,value,'viewkey');
	$('.goalConversionRateGraph').addClass('ajax-loader');
	$('.goalConversionRateGraph').show();
	$('#goal-conversion-all-users').hide();
	goal_conversion_rate_chart(campaignId,value,'viewkey');
	$('.goalAbandonRateGraph').addClass('ajax-loader');
	$('.goalAbandonRateGraph').show();
	$('#goal-abondon-all-users').hide();
	goal_abondon_rate_chart(campaignId,value,'viewkey');
	$('.OrganicGraph').addClass('ajax-loader');
	$('.OrganicGraph').show();
	$('#goal-completion-organic').hide();
	goal_completion_chart_organic(campaignId,value,'viewkey');
	$('.ValueOrganicGraph').addClass('ajax-loader');
	$('.ValueOrganicGraph').show();
	$('#goal-value-organic-chart').hide();
	goal_value_chart_organic(campaignId,value,'viewkey');
	$('.ConversionRateOrganicGraph').addClass('ajax-loader');
	$('.ConversionRateOrganicGraph').show();
	$('#goal-conversionRate-organic-chart').hide();
	goal_conversionRate_chart_organic(campaignId,value,'viewkey');
	$('.AbondonRateOrganicGraph').addClass('ajax-loader');
	$('.AbondonRateOrganicGraph').show();
	$('#goal-abondonRate-organic-chart').hide();
	goal_abondonRate_chart_organic(campaignId,value,'viewkey');
}

$(document).on('click','.graph_range_view_goal',function(){
	var type = $('.traffic_display_type_goal.blue-btn').attr('data-type');
	var value = $(this).attr('data-value');
	var module = $(this).attr('data-module');
	var campaignId = $('.campaignID').val();
	var key = $('#encriptkey').val();

	var compare_status = $('.analyticsGraphCompare_view').prop('checked');
	if(compare_status == true){
		var compare_value = 1;
	}else{
		var compare_value = 0;
	}

	$('.graph_range_view_goal').removeClass('active');
	$('.graph_range_view_goal[data-value="' + value + '"]').addClass('active');
	
	goalcompletion_dataGoals_vk(value,compare_value,campaignId,key,type);
	ecommerce_goals_tab_viewkey(value,compare_value,campaignId,key,type);
	
});	


$(document).on('click','.traffic_display_type_goal',function(e){
	e.preventDefault();
	var type = $(this).attr('data-type');
	$('.traffic_display_type_goal').removeClass('blue-btn');
	$('.traffic_display_type_goal[data-type="' + type + '"]').addClass('blue-btn');

	var campaignId = $('.campaignID').val();
	var key = $('#encriptkey').val();
	var value = $('.graph_range_view_goal.active').attr('data-value');

	var compare_status = $('.analyticsGraphCompare_view').prop('checked');
	if(compare_status == true){
		var compare_value = 1;
	}else{
		var compare_value = 0;
	}
	goalcompletion_dataGoals_vk(value,compare_value,campaignId,key,type);
	ecommerce_goals_tab_viewkey(value,compare_value,campaignId,key,type);
});

$(document).on('click','.traffic_display_typeGoal',function(e){
	e.preventDefault();
	var type = $(this).attr('data-type');
	$('.traffic_display_typeGoal').removeClass('blue-btn');
	$('.traffic_display_typeGoal[data-type="' + type + '"]').addClass('blue-btn');

	$('.organic_traffic_displayType').removeClass('blue-btn');
	$('.organic_traffic_displayType[data-type="' + type + '"]').addClass('blue-btn');


	var campaignId = $('.campaignID').val();
	var key = $('#encriptkey').val();
	var value = $('.graph_range_viewkey.active').attr('data-value');

	var compare_status = $('.analyticsGraphCompare_goal').prop('checked');
	if(compare_status == true){
		var compare_value = 1;
	}else{
		var compare_value = 0;
	}

	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_analytics_daterange_data",
		data:{value: value,campaignId,key,type,compare_value},
		dataType:'json',
		success:function(result){
			$('.loader').remove();
			highChartMap(result);
			if(result['compare_status'] == 1){
				$(".analyticsGraphCompare").prop("checked", true);
			}else if(result['compare_status'] == 0){
				$(".analyticsGraphCompare").prop("checked", false);
			}
		}
	});

	goalcompletion_data_viewkey(value,compare_value,campaignId,key,type);
	// ecommerce_goals_tab_viewkey(value,compare_value,campaignId,key,type);
	ecommerce_goalcompletion_viewkey(value,compare_value,campaignId,key,type);
});

$(document).on('change','.analyticsGraphCompare_view',function(e){
	e.preventDefault();
	var key = $('#encriptkey').val();
	var request_id  = $('.campaignID').val();
	var compare_status = $(this).is(':checked');
	if(compare_status == true){
		var compare_value = 1;
	}else{
		var compare_value = 0;
	}
	var type = $('.traffic_display_type_goal.blue-btn').attr('data-type');
	var range = $('.graph_range_view_goal.active').attr('data-value');

	goalcompletion_dataGoals_vk(range,compare_value,request_id,key,type);
	ecommerce_goals_tab_viewkey(range,compare_value,request_id,key,type);
});

function goalcompletion_dataGoals_vk(value,compare_value,campaignId,key,type){
	$('.goal-completion-graph').addClass('ajax-loader');
	goalCompletionChartGoals_vk(value,compare_value,campaignId,key,type);
	$('.compare').addClass('ajax-loader');
	$('.goal_completion_percentage').addClass('ajax-loader');
	goalCompletionStatsGoals_vk(value,compare_value,campaignId,key,type);
	$('#goal_completion_location tr').addClass('ajax-loader');
	goal_completion_location_vk(value,compare_value,campaignId,key,type,1);
	$('#goal_completion_location tr').addClass('ajax-loader');
	goal_completion_sourcemedium_vk(value,compare_value,campaignId,key,type,1);
	$('.allUserGraph').addClass('ajax-loader');
	$('.allUserGraph').show(); 
	$('#goal-completion-all-users').hide();
	all_users_chartGoals(campaignId,value,'viewkey');
	$('.goalValueGraph').addClass('ajax-loader');
	$('.goalValueGraph').show();
	$('#goal-value-all-users').hide();
	goal_value_chartGoals(campaignId,value,'viewkey');
	$('.goalConversionRateGraph').addClass('ajax-loader');
	$('.goalConversionRateGraph').show();
	$('#goal-conversion-all-users').hide();
	goal_conversion_rate_chartGoals(campaignId,value,'viewkey');
	$('.goalAbandonRateGraph').addClass('ajax-loader');
	$('.goalAbandonRateGraph').show();
	$('#goal-abondon-all-users').hide();
	goal_abondon_rate_chartGoals(campaignId,value,'viewkey'); 
	$('.OrganicGraph').addClass('ajax-loader');
	$('.OrganicGraph').show();
	$('#goal-completion-organic').hide();
	goal_completion_chart_organicGoals(campaignId,value,'viewkey');
	$('.ValueOrganicGraph').addClass('ajax-loader');
	$('.ValueOrganicGraph').show();
	$('#goal-value-organic-chart').hide();
	goal_value_chart_organicGoals(campaignId,value,'viewkey'); 
	$('.ConversionRateOrganicGraph').addClass('ajax-loader');
	$('.ConversionRateOrganicGraph').show();
	$('#goal-conversionRate-organic-chart').hide();
	goal_conversionRate_chart_organicGoals(campaignId,value,'viewkey');
	$('.AbondonRateOrganicGraph').addClass('ajax-loader');
	$('.AbondonRateOrganicGraph').show();
	$('#goal-abondonRate-organic-chart').hide();
	goal_abondonRate_chart_organicGoals(campaignId,value,'viewkey');
}


function disableSelectPicker(target){
	$(target).prop('disabled', true);
	$(target).selectpicker('refresh');
}

function enableSelectPicker(target){
	$(target).prop('disabled', false);
}

function gmb_scripts_pdf(campaign_id){
	customerViewOnload(campaign_id,'','');
	customerActionOnload(campaign_id,'','');
	phoneCallsBar(campaign_id,'','');
	ajaxGooglePhotoView(campaign_id,'','');
	CustomerSearch(campaign_id,'','');
	DirectionRequest(campaign_id,'','');
	Reviews(campaign_id,1);
	Media(campaign_id);
	//DirectionMap(campaign_id,$('.selected_direction_request').val());
	CustomerSearchData(campaign_id,'','');
}

/*ecommerce section*/
function ecommerce_goals_tab(campaignId){
	$('.ecom-goal-completion-graph').addClass('ajax-loader');
	ecom_goalsChart_tab(campaignId); 
	// ecom_goalCompletionStats(campaignId);
	ecom_goalCompletionStatsGoals(campaignId);
	ecom_conversion_rate_users_goals(campaignId);
	ecom_conversionRate_organic_goals(campaignId);
	ecom_transactionUsers_goals(campaignId);
	ecom_transactionOrganic_goals(campaignId);
	ecom_revenueUsers_goals(campaignId);
	ecom_revenueOrganic_goals(campaignId);
	ecom_avg_orderValue_users_goals(campaignId);
	ecom_avg_orderValue_organic_goals(campaignId);

	$('#ecom_product tr').addClass('ajax-loader');
	ecom_product_list(campaignId,1);
}

function ecommerce_goalcompletion(campaignId){
	$('.ecom-goal-completion-graph').addClass('ajax-loader');
	ecom_goalCompletionChart(campaignId);
	ecom_goalCompletionStats(campaignId);
	ecom_conversion_rate_users(campaignId);
	ecom_conversionRate_organic(campaignId);
	ecom_transactionUsers(campaignId);
	ecom_transactionOrganic(campaignId);
	ecom_revenueUsers(campaignId);
	ecom_revenueOrganic(campaignId);
	ecom_avg_orderValue_users(campaignId);
	ecom_avg_orderValue_organic(campaignId);

	$('#ecom_product tr').addClass('ajax-loader');
	ecom_product_list(campaignId,1);
}

function ecommerce_goalcompletion_viewkey(value,compare_value,campaignId,key,type){
	$('.ecom-goal-completion-graph').addClass('ajax-loader');
	ecom_goalCompletionChart_vk(value,compare_value,campaignId,key,type);
	ecom_goalCompletionStats_vk(value,compare_value,campaignId,key,type);
	ecom_conversion_rate_users(campaignId);
	ecom_conversionRate_organic(campaignId);
	ecom_transactionUsers(campaignId);
	ecom_transactionOrganic(campaignId);
	ecom_revenueUsers(campaignId);
	ecom_revenueOrganic(campaignId);
	ecom_avg_orderValue_users(campaignId);
	ecom_avg_orderValue_organic(campaignId);

	$('#ecom_product tr').addClass('ajax-loader');
	ecom_product_list_vk(value,compare_value,campaignId,key,type,1);
}

function ecommerce_goals_tab_viewkey(value,compare_value,campaignId,key,type){
	$('.ecom-goal-completion-graph').addClass('ajax-loader');
	ecom_goalsChart_tab_viewkey(value,compare_value,campaignId,key,type); 
	ecom_goalCompletionStats_vk(value,compare_value,campaignId,key,type);
	ecom_conversion_rate_users_goals(campaignId);
	ecom_conversionRate_organic_goals(campaignId);
	ecom_transactionUsers_goals(campaignId);
	ecom_transactionOrganic_goals(campaignId);
	ecom_revenueUsers_goals(campaignId);
	ecom_revenueOrganic_goals(campaignId);
	ecom_avg_orderValue_users_goals(campaignId);
	ecom_avg_orderValue_organic_goals(campaignId);

	$('#ecom_product tr').addClass('ajax-loader');
	ecom_product_list_vk(value,compare_value,campaignId,key,type,1);
}


function removeFloatingDiv(){
	if($('#searchconsoleHeading').find('.alert-danger').length == 0 && $('.organicTrafficGrowthHeading').find('.alert-danger').length == 0){
		$('.floatingDiv').css('display','none');
	}
}

function siteAuditOverview(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/campaign/audit/"+campaign_id,
		dataType:'json',
		success:function(result){

			$('#sa-overview').attr('disabled','disabled');
			
			if(result['status'] === 'error'){
				$('#sa-overview').text('Run Audit');
				$('.audit-domain-name').html(result['domain']);
				$('.audit-ip-address').html('<i class="fa fa-map-marker"></i> -');
				$('.audit-ssl-status').html('<i class="fa fa-unlock"></i> -');
				$('.crawled-pages').html('-');
				$('.audit-indexed-pages').html('-');
				$('.audit-site-status').html('-');
				$('.audit-domain-name').removeClass('ajax-loader');
				$('.audit-ip-address').removeClass('ajax-loader');
				$('.audit-ssl-status').removeClass('ajax-loader');
				$('.crawled-pages').removeClass('ajax-loader');
				$('.audit-indexed-pages').removeClass('ajax-loader');
				$('.audit-site-status').removeClass('ajax-loader');
				overviewSiteAuditChartData(0);
				return false;
			}else{
				displaySummary(result);
			}

		}
	});
}

function pageAuthorityStats(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_page_authority_stats",
		data:{campaign_id},
		dataType:'json',
		success:function(result){
			if(result['page_string'] != '??'){
				var pa_string = result['page_string'].toString();
				pa_string = pa_string.replace(/,/g, "");
				if(pa_string > 0 ){
					$('.pa_stats').html(result['page_authority']);
					$('.pageAuthority_avg').addClass("green");
					$('.pageAuthority_avg').html('<img src="/public/vendor/internal-pages/images/up-stats-arrow.png" alt="up-stats-arrow"><span>'+result['page_string']+' </span>Since Start');
				}else if(pa_string < 0 ){
					var replace_pa = pa_string.replace('-', '');
					$('.pa_stats').html(result['page_authority']);
					$('.pageAuthority_avg').addClass("red");
					$('.pageAuthority_avg').html('<img src="/public/vendor/internal-pages/images/down-stats-arrow.png" alt="down-stats-arrow"><span>'+replace_pa+' </span>Since Start');
				}else{
					$('.pa_stats').html(result['page_authority']);
				}
			}else{
				$('.pageAuthority_avg').html(result['page_authority']);
			}

			$('.pa-stats').removeClass("ajax-loader");
			$('.pa-avg').removeClass("ajax-loader");
		}
	});
}

function ajaxReferringDomains(campaignId){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajaxreferringdomains",
		data:{campaign_id: campaignId},
		dataType:'json',
		success:function(result){
			if(result != ''){
				if(result['avg'] != '??'){
					var rd_string = result['avg'].toString();
					rd_string = rd_string.replace(/,/g, "");
					if(rd_string > 0 ){
						$('.backlink_total').html(result['total']);
						$('.backlink_avg').addClass("green");
						$('.backlink_avg').html('<img src="/public/vendor/internal-pages/images/up-stats-arrow.png" alt="up-stats-arrow"><span>'+result['avg']+' </span>Since Start');
					}else if(rd_string < 0 ){
						var replace_rd = rd_string.replace('-', '');
						$('.backlink_total').html(result['total']);
						$('.backlink_avg').addClass("red");
						$('.backlink_avg').html('<img src="/public/vendor/internal-pages/images/down-stats-arrow.png" alt="down-stats-arrow"><span>'+replace_rd+' </span>Since Start');
					}else{
						$('.backlink_total').html(result['total']);
					}
				}else{
					$('.backlink_total').html(result['total']);
				}

				$('.rd-total').removeClass("ajax-loader");
				$('.rd-avg').removeClass("ajax-loader");
			}
		}
	});
}

function domainAuthorityStats(campaign_id){
	$.ajax({
		type:"GET",
		url:BASE_URL+"/ajax_get_domain_authority_stats",
		data:{campaign_id},
		dataType:'json',
		success:function(result){
			if(result['domain_string'] != '??'){
				var da_string = result['domain_string'].toString();
				da_string = da_string.replace(/,/g, "");
				if(da_string > 0 ){
					$('.da_stats').html(result['domain_authority']);
					$('.domainAuthority_avg').addClass("green");
					$('.domainAuthority_avg').html('<img src="/public/vendor/internal-pages/images/up-stats-arrow.png" alt="up-stats-arrow"><span>'+result['domain_string']+' </span>Since Start');
				}else if(da_string < 0 ){
					var replace_da = da_string.replace('-', '');
					$('.da_stats').html(result['domain_authority']);
					$('.domainAuthority_avg').addClass("red");
					$('.domainAuthority_avg').html('<img src="/public/vendor/internal-pages/images/down-stats-arrow.png" alt="down-stats-arrow"><span>'+replace_da+' </span>Since Start');
				}else{
					$('.da_stats').html(result['domain_authority']);
				}
			}else{
				$('.da_stats').html(result['domain_authority']);
			}

			$('.da-stats').removeClass("ajax-loader");
			$('.da-avg').removeClass("ajax-loader");
		}
	});

}

function ajaxGoogleTrafficGrowth_data(campaignId){
	$.ajax({
		type:"GET",
		url:$('.base_url').val()+"/ajax_organic_visitors",
		data:{campaignId: campaignId},
		dataType:'json',
		success:function(result){
			if(result['traffic_growth'] != '??'){
				var traffic_growth_string = result['traffic_growth'].toString();
				traffic_growth_string = traffic_growth_string.replace(/,/g, "");
				if(traffic_growth_string > 0 ){
					$('.organic-visitors-count').html(result['current_users']);
					$('.organic_visitor_growth').addClass("green");
					$('.organic_visitor_growth').html('<img src="/public/vendor/internal-pages/images/up-stats-arrow.png" alt="up-stats-arrow"><span>'+result['traffic_growth']+'% </span>Since Start');
				}else if(traffic_growth_string < 0 ){
					var replace_traffic_growth = traffic_growth_string.replace('-', '');
					$('.organic-visitors-count').html(result['current_users']);
					$('.organic_visitor_growth').addClass("red");
					$('.organic_visitor_growth').html('<img src="/public/vendor/internal-pages/images/down-stats-arrow.png" alt="down-stats-arrow"><span>'+replace_traffic_growth+'% </span>Since Start');
				}else{
					$('.organic-visitors-count').html(result['current_users']);
				}
			}else{
				$('.organic-visitors-count').html(result['current_users']);
			}

			$('.ov-total').removeClass("ajax-loader");
			$('.ov-avg').removeClass("ajax-loader");
		}
	});
}

function displaySummary(result){
	$('.audit-domain-name').html(result['project']);
	$('.audit-ip-address').html('<i class="fa fa-map-marker"></i> '+result['ip']);
	if(result['is_ssl'] == 1){
		$('.audit-ssl-status').html('<i class="fa fa-lock"></i> enabled');
	}else{
		$('.audit-ssl-status').html('<i class="fa fa-unlock"></i> not enabled');
	}
	$('.crawled-pages').html(result['crowled_pages'] +'/'+result['crowl_pages']);
	$('.audit-indexed-pages').html(parseInt(result['noindex']));
	if(result['is_ssl'] == 1){
		$('.audit-site-status').html('Site is safe');
	}else{
		$('.audit-site-status').html('Site is not safe');
	}
	$('.audit-domain-name').removeClass('ajax-loader');
	$('.audit-ip-address').removeClass('ajax-loader');
	$('.audit-ssl-status').removeClass('ajax-loader');
	$('.crawled-pages').removeClass('ajax-loader');
	$('.audit-indexed-pages').removeClass('ajax-loader');
	$('.audit-site-status').removeClass('ajax-loader');
	overviewSiteAuditChartData(result['result']);
}

function overviewSiteAuditChartData(data){
	var ctx = document.getElementById('detail-siteAudit-chart-data').getContext('2d');
	var gradient1 = ctx.createLinearGradient(0, 0, 0, 450);
    gradient1.addColorStop(0, 'rgba(250, 161,155, 1)'); //pink
    gradient1.addColorStop(0.3, 'rgba(253 ,198, 128, 1)'); //yellow
    gradient1.addColorStop(0.6, 'rgba(107 ,255 ,133, 1)');//green
    var siteAuditmyChart = new Chart(ctx, {
    	type: 'doughnut',
    	data: {
    		datasets: [{
    			label: '# of Votes',
    			data: [],
    			backgroundColor:[gradient1,'#eeeeee'],
    			borderColor:[gradient1,'#eeeeee'],
    			borderWidth: 1
    		}]
    	},
    	options: {
    		cutoutPercentage: 85,
    		maintainAspectRatio: this.maintainAspectRatio,
    		scales: {
    			y: {
    				beginAtZero: true
    			}
    		},
    		tooltips: {
    			enabled: false
    		}
    	}
    });
    var page_score = data.toString();
    var left = (100 - page_score).toFixed(2);
    siteAuditmyChart.data.datasets[0].data = [page_score,left];
    siteAuditmyChart.update();    
    $('.percent_text').html(parseInt(page_score));
    $('.percent_text').removeClass('ajax-loader');
    $('.circle-donut').removeClass('ajax-loader');
}