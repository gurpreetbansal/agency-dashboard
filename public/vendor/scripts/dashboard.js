	var BASE_URL = $('.base_url').val();
	var color = Chart.helpers.color;
	var Keywordconfig = {
	    type: 'bar',
	    data: {
	        labels: [],
	        datasets: [{
	            label: 'Total Keywords',
	            backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
	            borderColor: window.chartColors.red,
	            borderWidth: 1,
	            data: [],
	        }]
	    },
	    options: {
	        responsive: true,
	        title: {
	            display: false,
	            text: ''
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
	                radius: 5
	            },
	            {
	                backgroundColor: window.chartColors.mauve,
	                borderColor: window.chartColors.mauve,
	                data: [],
	                label: 'Clicks:Previous',
	                fill: false,
	                radius: 5
	            },
	            {
	                backgroundColor: window.chartColors.greyBlue,
	                borderColor: window.chartColors.greyBlue,
	                data: [],
	                label: 'Conversions',
	                fill: false,
	                radius: 5
	            },
	            {
	                backgroundColor: window.chartColors.fuschiapink,
	                borderColor: window.chartColors.fuschiapink,
	                data: [],
	                label: 'Conversions:Previous',
	                fill: false,
	                radius: 5
	            },
	            {
	                backgroundColor: window.chartColors.lightGreen,
	                borderColor: window.chartColors.lightGreen,
	                data: [],
	                label: 'Impressions',
	                fill: false,
	                radius: 5
	            },
	            {
	                backgroundColor: window.chartColors.pink,
	                borderColor: window.chartColors.pink,
	                data: [],
	                label: 'Impressions:Previous',
	                fill: false,
	                radius: 5
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
	            yAxes: [{
	                //  stacked: true
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
	    data: {
	        labels: [],
	        datasets: [{
	                backgroundColor: window.chartColors.orange,
	                borderColor: window.chartColors.orange,
	                data: [0],
	                label: 'Cost',
	                fill: false,
	                radius: 5,
	                trendlineLinear: { style: window.chartColors.orange, lineStyle: "dotted", width: 1 }
	            },

	            {
	                backgroundColor: window.chartColors.mauve,
	                borderColor: window.chartColors.mauve,
	                data: [0],
	                label: 'Cost:Previous',
	                fill: false,
	                radius: 5,
	                trendlineLinear: { style: window.chartColors.mauve, lineStyle: "dotted", width: 1 }

	            },
	            {
	                backgroundColor: window.chartColors.greyBlue,
	                borderColor: window.chartColors.greyBlue,
	                data: [0],
	                label: 'Cost per Click',
	                fill: false,
	                radius: 5,
	                trendlineLinear: { style: window.chartColors.greyBlue, lineStyle: "dotted", width: 1 }
	            },
	            {
	                backgroundColor: window.chartColors.fuschiapink,
	                borderColor: window.chartColors.fuschiapink,
	                data: [0],
	                label: 'Cost per Click:Previous',
	                fill: false,
	                radius: 5,
	                trendlineLinear: { style: window.chartColors.fuschiapink, lineStyle: "dotted", width: 1 }
	            },
	            {
	                backgroundColor: window.chartColors.lightGreen,
	                borderColor: window.chartColors.lightGreen,
	                data: [0],
	                label: 'Cost per 1000 Impressions',
	                fill: false,
	                radius: 5,
	                trendlineLinear: { style: window.chartColors.lightGreen, lineStyle: "dotted", width: 1 }
	            },
	            {
	                backgroundColor: window.chartColors.pink,
	                borderColor: window.chartColors.pink,
	                data: [0],
	                label: 'Cost per 1000 Impressions:Previous',
	                fill: false,
	                radius: 5,
	                trendlineLinear: { style: window.chartColors.pink, lineStyle: "dotted", width: 1 }
	            },
	            {
	                backgroundColor: window.chartColors.lightPurple,
	                borderColor: window.chartColors.lightPurple,
	                data: [0],
	                label: 'Revenue Per Click',
	                fill: false,
	                radius: 5,
	                trendlineLinear: { style: window.chartColors.lightPurple, lineStyle: "dotted", width: 1 }
	            },
	            {
	                backgroundColor: window.chartColors.darkBlue,
	                borderColor: window.chartColors.darkBlue,
	                data: [0],
	                label: 'Revenue Per Click:Previous',
	                fill: false,
	                radius: 5,
	                trendlineLinear: { style: window.chartColors.darkBlue, lineStyle: "dotted", width: 1 }
	            },
	            {
	                backgroundColor: window.chartColors.bottleGreen,
	                borderColor: window.chartColors.bottleGreen,
	                data: [0],
	                label: 'Total Value',
	                fill: false,
	                radius: 5,
	                trendlineLinear: { style: window.chartColors.bottleGreen, lineStyle: "dotted", width: 1 }
	            },
	            {
	                backgroundColor: window.chartColors.pearGreen,
	                borderColor: window.chartColors.pearGreen,
	                data: [0],
	                label: 'Total Value:Previous',
	                fill: false,
	                radius: 5,
	                trendlineLinear: { style: window.chartColors.pearGreen, lineStyle: "dotted", width: 1 }
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
	            yAxes: [{
	                //  stacked: true
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






	$(document).ready(function() {

	    if (window.location.hash != '') {
	        var dashboard_active = window.location.hash;
	    } else {
	        var dashboard_active = $('.newDashboard li a.active').attr('href');
	    }

	    if ((dashboard_active != null) && dashboard_active != undefined) {
	        if (dashboard_active.match('#')) {
	            $('li.nav-item .active').removeClass('active');
	            $('a[href="' + dashboard_active + '"]').addClass('active');


	            $('.mainDashboardSection').removeClass("active");
	            $("#" + dashboard_active.split('#')[1]).addClass('in show active');
	        }

	        if (dashboard_active == '#SEO') {
				// $('#SEO').load('/seo_content/'+$('.campaignID').val());
	            setTimeout(function() {
	                updateTimeAgo();
	                extraOrganicKeywords($('.campaignID').val());
	                ajaxSerpStatData($('.campaignID').val());
	                ajaxReferringDomains($('.campaignID').val());
	                ajaxOrganicKeywordRanking($('.campaignID').val());
	                ajaxGoogleAnalyticsGoal($('.campaignID').val());
	                ajaxGoogleTrafficGrowth($('.campaignID').val());
	                ajaxGoogleTrafficGrowth_data($('.campaignID').val());
	                ajaxGoogleSearchConsole($('.campaignID').val());
	                keywordsMetricBarChart($('.campaignID').val());
	                keywordsMetricPieChart($('.campaignID').val());
	                seo_page_scripts($('.campaignID').val());
	                getAccountActivity($('.campaignID').val());
	            }, 2000);
	        }

	        if (dashboard_active == '#PPC') {
	        	// $('#PPC').load('/ppc_content/'+$('.campaignID').val());
	            setTimeout(function() {
	                chart();
	                daterange();
	                custom_switches();
	                ppc_page_scripts();
	                ppc_datatables();  
	            }, 2000);
	        }
	    }
	});


	$('.newDashboard .nav-link').on('click', function() {
	    // $('html, body').offset().top;

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

	    if (url.match('#')) {
	        $('li.nav-item .active').removeClass('active');
	        $(this).addClass('active');

	        $('.mainDashboardSection').removeClass("active");
	        $("#" + url.split('#')[1]).addClass('in show active');
	    }

	    if (href == '#SEO') {
	        if ($('#SEO').find('.tabs-animation').length == 0) {
	            $('#SEO').load('/seo_content/' + $('.campaignID').val());

	            setTimeout(function() {
	                updateTimeAgo();
	                extraOrganicKeywords($('.campaignID').val());
	                ajaxSerpStatData($('.campaignID').val());
	                ajaxReferringDomains($('.campaignID').val());
	                ajaxOrganicKeywordRanking($('.campaignID').val());
	               ajaxGoogleAnalyticsGoal($('.campaignID').val());
	                ajaxGoogleTrafficGrowth($('.campaignID').val());
	                ajaxGoogleTrafficGrowth_data($('.campaignID').val());
	                ajaxGoogleSearchConsole($('.campaignID').val());
	                keywordsMetricBarChart($('.campaignID').val());
	                keywordsMetricPieChart($('.campaignID').val());
	               getAccountActivity($('.campaignID').val());
	            }, 2000);
	        } else {
	            $('.mainDashboardSection').removeClass('active');
	            $('#SEO').addClass('in show active');

	        }
	    }

	    if (href == '#PPC') {
	        if ($('#PPC').find('.tabs-animation').length == 0) {
	            $('#PPC').load('/ppc_content/' + $('.campaignID').val());
	            setTimeout(function() {
	                chart();
	                daterange();
	                custom_switches();
	                ppc_page_scripts();
	                ppc_datatables();
	            }, 2000);

	        } else {
	            $('.mainDashboardSection').removeClass('active');
	            $('#PPC').addClass('in show active');

	        }
	    }

	     if (href == '#GMB') {
	        if ($('#GMB').find('.tabs-animation').length == 0) {
	            $('#GMB').load('/gmb_content/' + $('.campaignID').val());
	        } else {
	            $('.mainDashboardSection').removeClass('active');
	            $('#GMB').addClass('in show active');

	        }
	    }

	});

	function keywordsMetricBarChart(campaignId) {
	    $.ajax({
	        type: "GET",
	        url: $('.base_url').val() + "/keywordsMetricBarChart",
	        data: { campaignId: campaignId },
	        dataType: 'json',
	        success: function(result) {

	            if (window.myLineKeyword) {
	                window.myLineKeyword.destroy();
	            }

	            var ctxs = document.getElementById('keywordsCanvas').getContext('2d');
	            window.myLineKeyword = new Chart(ctxs, Keywordconfig);

	            Keywordconfig.data.labels = JSON.parse(result['names']);
	            Keywordconfig.data.datasets[0].data = JSON.parse(result['values']);
	            window.myLineKeyword.update();
	        }
	    });
	}

	function keywordsMetricPieChart(campaignId) {
	    $.ajax({
	        type: "GET",
	        url: $('.base_url').val() + "/keywordsMetricPieChart",
	        data: { campaignId: campaignId },
	        dataType: 'json',
	        success: function(result) {

	            if (window.myLinePie) {
	                window.myLinePie.destroy();
	            }

	            var ctxPie = document.getElementById('keywordsCanvasChartArea').getContext('2d');
	            window.myLinePie = new Chart(ctxPie, KeywordChartConfig);

	            KeywordChartConfig.data.labels = JSON.parse(result['names']);
	            KeywordChartConfig.data.datasets[0].data = JSON.parse(result['values']);
	            window.myLinePie.update();
	        }
	    });
	}


	function seo_page_scripts(campaignId) {
	    // console.log('campaignId: '+campaignId);

	    $.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });

	    $('#google_organic_keywords').DataTable({
	        processing: true,
	        serverSide: true,
	        "deferRender": true,
	        "order": [
	            [1, "asc"]
	        ],
	        'ajax': {
	            'url': BASE_URL + '/ajaxOrganicKeywords',
	            'data': function(data) {
	                data.campaign_id = campaignId;

	            }
	        }
	    });

	    $('#LiveKeywordTrackingTable').DataTable({
	    	dom: 'Bfrtip',
	        processing: true,
	        serverSide: true,
	        'lengthChange': true,
            'pageLength': 10,
	        // lengthMenu: [
	        //     [ 10, 25, 50, -1 ],
	        //     [ '10 rows', '25 rows', '50 rows', 'Show all' ]
       		//  ],
	        order: [[2, "asc"]],
	        'ajax': {
	            'url': BASE_URL + '/ajaxLiveKeywordTrackingData',
	            'data': function(data) {
	                data.campaign_id = campaignId;
	            }
	        },
	        columnDefs: [
	            { "targets": [12], "orderable": false }
	        ],
	        select: true,
	        buttons: [
	        'pageLength',
		        {
		            extend: 'excelHtml5',
		            text: 'Export Keywords',
		            title:'Live Keyword Tracking'
		        }
		    ]
	    });

	    $('#backlink_profile').DataTable({
	        "processing": true,
	        "serverSide": true,
	        "ajax": {
	            'url': BASE_URL + "/ajax_backlink_profile_datatable",
	            'data': function(data) {
	                data.campaign_id = campaignId;
	            }
	        },

	        "order": [
	            [1, "asc"]
	        ]
	    });

	}

	function getAccountActivity(campaignId) {
		$('#accountActivityLoader').show();
	    var lastDate = '';
	    $.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });

	    $.ajax({
	        type: "POST",
	        url: BASE_URL + "/get_account_activity",
	        data: { request_id: campaignId, lastDate: lastDate },
	        dataType: 'json',
	        success: function(result) {
	        	$('#accountActivityLoader').hide();
	            if (result != '') {
	                $('#activity-timeline').html(result['html']);
	                $('.load-more').attr('data-value', result['limit']);
	            }
	        }
	    });
	}

	$(document).on('click', '.load-more', function() {
	    $('.account_activity').show();
	    $('.load-more').hide();
	    var limit = $(this).attr('data-value');
	    var requestId = $('.campaignID').val();
	    var lastDate = $('.account-timeline-date').last().html();
	    $.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });

	    $.ajax({
	        type: "POST",
	        url: BASE_URL + "/get_account_activity",
	        data: { request_id: requestId, limit: limit, lastDate: lastDate },
	        dataType: 'json',
	        success: function(result) {

	            $('.account-timeline').mCustomScrollbar("destroy");
	            $('#activity-timeline').append(result['html']);
	            $('.account-timeline').mCustomScrollbar();
	            $('.load-more').attr('data-value', result['limit']);
	            $('.account_activity').hide();
	            $('.load-more').show();

	        }
	    });
	});

	function ppc_datatables() {
	    $('#google_ads_campaigns').DataTable({
	        processing: true,
	        serverSide: true,
	        async: false,
	        "deferRender": true,
	        'ajax': {
	            'url': BASE_URL + '/ajaxAdsCampaign',
	            'data': function(data) {
	                data.today = $('.today').val();
	                data.account_id = $('.account_id').val();
	                data.currency_code = $('.currency_code').val();

	            }
	        },
	        columns: [
	            { data: 'campaign_name', name: 'campaign_name', "orderable": false },
	            { data: 'impressions', name: 'impressions', "orderable": false },
	            { data: 'clicks', name: 'clicks', "orderable": false },
	            { data: 'ctr', name: 'ctr', "orderable": false },
	            { data: 'cost', name: 'cost', "orderable": false },
	            { data: 'conversions', name: 'conversions', "orderable": false }
	        ]
	    });


	    $('#google_ads_keywords').DataTable({
	        "destroy": true,
	        "processing": true,
	        "serverSide": true,
	        async: false,
	        "ajax": {
	            'url': BASE_URL + '/ajaxAdsKeywords',
	            type: "GET",
	            'data': function(data) {
	                data.today = $('.today').val();
	                data.account_id = $('.account_id').val();
	                data.currency_code = $('.currency_code').val();

	            }
	        },
	        columns: [
	            { data: 'keywords', name: 'keywords', "orderable": false },
	            { data: 'impressions', name: 'impressions', "orderable": false },
	            { data: 'clicks', name: 'clicks', "orderable": false },
	            { data: 'ctr', name: 'ctr', "orderable": false },
	            { data: 'cost', name: 'cost', "orderable": false },
	            { data: 'conversions', name: 'conversions', "orderable": false }
	        ]
	    });



	    $('#google_ads').DataTable({
	        "processing": true,
	        "serverSide": true,
	        async: false,
	        "ajax": {
	            'url': BASE_URL + '/ajaxAdsData',
	            type: "GET",
	            'data': function(data) {
	                data.today = $('.today').val();
	                data.account_id = $('.account_id').val();
	                data.currency_code = $('.currency_code').val();

	            }
	        },
	        columns: [
	            { data: 'ad', name: 'ad', "orderable": false },
	            { data: 'ad_type', name: 'ad_type', "orderable": false },
	            { data: 'impressions', name: 'impressions', "orderable": false },
	            { data: 'clicks', name: 'clicks', "orderable": false },
	            { data: 'ctr', name: 'ctr', "orderable": false },
	            { data: 'cost', name: 'cost', "orderable": false },
	            { data: 'conversions', name: 'conversions', "orderable": false }
	        ]
	    });


	    $('#google_ad_groups').DataTable({
	        "processing": true,
	        "serverSide": true,
	        async: false,
	        "ajax": {
	            'url': BASE_URL + '/ajaxAdGroupsData',
	            type: "GET",
	            'data': function(data) {
	                data.today = $('.today').val();
	                data.account_id = $('.account_id').val();
	                data.currency_code = $('.currency_code').val();

	            }
	        },
	        columns: [
	            { data: 'ad_group', name: 'ad_group', "orderable": false },
	            { data: 'impressions', name: 'impressions', "orderable": false },
	            { data: 'clicks', name: 'clicks', "orderable": false },
	            { data: 'ctr', name: 'ctr', "orderable": false },
	            { data: 'cost', name: 'cost', "orderable": false },
	            { data: 'conversions', name: 'conversions', "orderable": false }
	        ]
	    });

	    $('#google_ad_performance_network').DataTable({
	        "processing": true,
	        "serverSide": true,
	        async: false,
	        "ajax": {
	            'url': BASE_URL + '/ajaxAdPerformanceNetwork',
	            type: "GET",
	            'data': function(data) {
	                data.today = $('.today').val();
	                data.account_id = $('.account_id').val();
	                data.currency_code = $('.currency_code').val();

	            }
	        },
	        columns: [
	            { data: 'publisher_by_network', "orderable": false },
	            { data: 'impressions', "orderable": false },
	            { data: 'clicks', "orderable": false },
	            { data: 'ctr', "orderable": false },
	            { data: 'cost', "orderable": false },
	            { data: 'conversions', "orderable": false }
	        ]
	    });



	    $('#google_ad_performance_device').DataTable({
	        "processing": true,
	        "serverSide": true,
	        async: false,
	        "ajax": {
	            'url': BASE_URL + '/ajaxAdPerformanceDevice',
	            type: "GET",
	            'data': function(data) {
	                data.today = $('.today').val();
	                data.account_id = $('.account_id').val();
	                data.currency_code = $('.currency_code').val();

	            }
	        },
	        columns: [
	            { data: 'device', "orderable": false },
	            { data: 'impressions', "orderable": false },
	            { data: 'clicks', "orderable": false },
	            { data: 'ctr', "orderable": false },
	            { data: 'cost', "orderable": false },
	            { data: 'conversions', "orderable": false }
	        ]
	    });


	    $('#google_ad_click_types').DataTable({
	        "processing": true,
	        "serverSide": true,
	        async: false,
	        "ajax": {
	            'url': BASE_URL + '/ajaxAdPerformanceClickTypes',
	            type: "GET",
	            'data': function(data) {
	                data.today = $('.today').val();
	                data.account_id = $('.account_id').val();
	                data.currency_code = $('.currency_code').val();

	            }
	        },
	        columns: [
	            { data: 'click_type', "orderable": false },
	            { data: 'impressions', "orderable": false },
	            { data: 'clicks', "orderable": false },
	            { data: 'ctr', "orderable": false },
	            { data: 'cost', "orderable": false },
	            { data: 'conversions', "orderable": false }
	        ]
	    });


	    $('#google_ad_slots').DataTable({
	        "processing": true,
	        "serverSide": true,
	        async: false,
	        "ajax": {
	            'url': BASE_URL + '/ajaxAdPerformanceSlots',
	            type: "GET",
	            'data': function(data) {
	                data.today = $('.today').val();
	                data.account_id = $('.account_id').val();
	                data.currency_code = $('.currency_code').val();

	            }
	        },
	        columns: [
	            { data: 'ad_slot', "orderable": false },
	            { data: 'impressions', "orderable": false },
	            { data: 'clicks', "orderable": false },
	            { data: 'ctr', "orderable": false },
	            { data: 'cost', "orderable": false },
	            { data: 'conversions', "orderable": false }
	        ]
	    });


	    $(document).ready(function() {
	        var campaign_id = $('.campaign_id').val();
	        var account_id = $('.account_id').val();
	        $.ajax({
	            url: BASE_URL + '/ajaxSaveInCsv',
	            data: { campaign_id: campaign_id, account_id: account_id },
	            type: 'get',
	            success: function(response) {
	                // console.log(response);
	            }
	        });
	    });
	}

	function chart() {
	    var ctx = document.getElementById('canvasppcsummary').getContext('2d');
	    window.myLine = new Chart(ctx, configSummary);

	    var ctxPerformance = document.getElementById('canvasperformance').getContext('2d');
	    window.myLinePerformance = new Chart(ctxPerformance, configPerformance);
	}

	function ppc_page_scripts() {
	    $(document).ready(function() {
	        var account_id = $('.account_id').val();
	        var campaign_id = $('.campaign_id').val();
	        $.ajax({
	            type: "GET",
	            url: BASE_URL + "/ppc_date_range_data",
	            data: { account_id: account_id, campaign_id: campaign_id },
	            dataType: 'json',
	            success: function(result) {
	                // console.log(result);
	                configSummaryData(result);
	                configPerformanceData(result);
	            }
	        });


	        $.ajax({
	            type: "GET",
	            url: BASE_URL + "/summary_statistics",
	            data: { account_id, campaign_id },
	            dataType: 'json',
	            beforeSend: function() {
	                $(".summaryloader").show();
	                $("#myOverlay").show();
	            },
	            success: function(response) {
	                $(".summaryloader").hide();
	                $("#myOverlay").hide();



	                var prev_imp = prev_clicks = previous_cost = previous_conversions = previous_cost_per_conversion = previous_ctr = previous_conversion_rate = previous_average_cpc = compare_date = '';


	                if (response['previous_impressions'] != "" && response['previous_impressions'] != "0") {
	                    var prev_imp = ' vs ' + response['previous_impressions'];
	                }
	                if (response['previous_clicks'] != "" && response['previous_clicks'] != "0") {
	                    var prev_clicks = ' vs ' + response['previous_clicks'];
	                }
	                if (response['previous_cost'] != "" && response['previous_cost'] != "0") {
	                    var previous_cost = ' vs ' + response['previous_cost'];
	                }
	                if (response['previous_conversions'] != "" && response['previous_conversions'] != "0") {
	                    var previous_conversions = ' vs ' + response['previous_conversions'];
	                }
	                if (response['previous_cost_per_conversion'] != "" && response['previous_cost_per_conversion'] != "0") {
	                    var previous_cost_per_conversion = ' vs ' + response['previous_cost_per_conversion'];
	                }
	                if (response['previous_ctr'] != "" && response['previous_ctr'] != "0") {
	                    var previous_ctr = ' vs ' + response['previous_ctr'];
	                }
	                if (response['previous_conversion_rate'] != "" && response['previous_conversion_rate'] != "0") {
	                    var previous_conversion_rate = ' vs ' + response['previous_conversion_rate'];
	                }
	                if (response['previous_average_cpc'] != "" && response['previous_average_cpc'] != "0") {
	                    var previous_average_cpc = ' vs ' + response['previous_average_cpc'];
	                }

	                if (response['compare_date'] != "" && response['compare_date'] != "0") {
	                    var compare_date = ' (compared to  ' + response['compare_date'] + ' )';
	                }


	                $('.dateSection').html(response['date'] + compare_date);
	                $('#impressions').html(response['impressions'] + prev_imp);
	                $('#cost').html(response['cost'] + previous_cost);
	                $('#clicks').html(response['clicks'] + prev_clicks);
	                $('#average_cpc').html(response['average_cpc'] + previous_average_cpc);
	                $('#ctr').html(response['ctr'] + previous_ctr);
	                $('#conversions').html(response['conversions'] + previous_conversions);
	                $('#conversion_rate').html(response['conversion_rate'] + previous_conversion_rate);
	                $('#cost_per_conversion').html(response['cost_per_conversion'] + previous_cost_per_conversion);

	            }

	        });
	    });
	}

	function configSummaryData(result) {
	    // if (window.myLine) {
	    // 	window.myLine.destroy();
	    // }


	    // var ctx = document.getElementById('canvas').getContext('2d');
	    // window.myLine = new Chart(ctx, configSummary);

	    configSummary.data.labels = result['date_range'];
	    configSummary.data.datasets[0].data = result['clicks'];
	    configSummary.data.datasets[1].data = result['clicks_previous'];
	    configSummary.data.datasets[2].data = result['conversions'];
	    configSummary.data.datasets[3].data = result['conversions_previous'];
	    configSummary.data.datasets[4].data = result['impressions'];
	    configSummary.data.datasets[5].data = result['impressions_previous'];

	    window.myLine.update();

	}

	function configPerformanceData(result) {
	    // if (window.myLinePerformance) {
	    // 	window.myLinePerformance.destroy();
	    // }
	    // var ctxPerformance = document.getElementById('canvasperformance').getContext('2d');
	    // window.myLinePerformance = new Chart(ctxPerformance, configPerformance);

	    configPerformance.data.labels = result['date_range'];

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

	/* Custom daterange for ppc*/

	function custom_switches() {
	    $('#customSwitchesppc').on('change', function() {
	        if ($(this).is(':checked')) {
	            $(this).prop('checked', true);
	            $('.compareSection').css('display', 'block');
	        } else {
	            $(this).prop('checked', false);
	            $('.compareSection').css('display', 'none');
	        }
	    });
	}
	$(function() {


	    $(document).on('click', '#submitPpcDateRange', function() {
	        var campaign_id = $('.campaignID').val();
	        var account_id = $('.account_id').val();
	        var start_date = $(".sd").val();
	        var end_date = $(".ed").val();
	        var cmp_start_date = $(".csd").val();
	        var cmp_end_date = $(".ced").val();


	        if (start_date != '' && end_date != '') {

	            if ($('#customSwitchesppc').prop("checked") == true) {
	                var compare = true;
	            }

	            $.ajax({
	                type: "GET",
	                url: $('.base_url').val() + "/ppc_date_range_data",
	                data: { start_date, end_date, account_id, compare, cmp_start_date, cmp_end_date, campaign_id },
	                dataType: 'json',
	                success: function(result) {
	                    configSummaryData(result);
	                    configPerformanceData(result);
	                }
	            });


	            $.ajax({
	                type: "GET",
	                url: $('.base_url').val() + "/summary_stats",
	                data: { start_date, end_date, account_id, compare, cmp_start_date, cmp_end_date, campaign_id },
	                dataType: 'json',
	                beforeSend: function() {
	                    $(".summaryloader").show();
	                    $("#myOverlay").show();
	                },
	                success: function(response) {
	                    $(".summaryloader").hide();
	                    $("#myOverlay").hide();

	                    if (response['status'] == false) {
	                        Command: toastr["warning"](response['message']);
	                        return false;
	                    }

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
	                    }

	                    $('.dateSection').html(response['date'] + compare_date);
	                    $('#impressions').html(response['impressions'] + prev_imp);
	                    $('#cost').html(response['cost'] + previous_cost);
	                    $('#clicks').html(response['clicks'] + prev_clicks);
	                    $('#average_cpc').html(response['average_cpc'] + previous_average_cpc);
	                    $('#ctr').html(response['ctr'] + previous_ctr);
	                    $('#conversions').html(response['conversions'] + previous_conversions);
	                    $('#conversion_rate').html(response['conversion_rate'] + previous_conversion_rate);
	                    $('#cost_per_conversion').html(response['cost_per_conversion'] + previous_cost_per_conversion);
	                }

	            });


	        } else {
	            Command: toastr["warning"]('Please select dates first !');
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

	function daterange() {
	    if ($('.csd').val() != '') {
	        $('.firstdaterange').daterangepicker({
	            startDate: getdate($('.sd').val(), 0),
	            endDate: getdate($('.ed').val(), 0)
	        });


	        $('.seconddaterange').daterangepicker({
	            startDate: getdate($('.csd').val(), 0),
	            endDate: getdate($('.ced').val(), 0)
	        });
	    }

	    $('input[name="dateranges"]').daterangepicker({
	        opens: 'left'
	    }, function(start, end, label) {
	        var new_start = start.format('MM/DD/YYYY');
	        var new_end = end.format('MM/DD/YYYY');
	        var days = date_diff_indays(new_start, new_end);

	        var date = getdate(new_start, days);

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


	$(document).on('click', '.newDashboardSkipBtn', function() {
	    $.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });

	    $.ajax({
	        url: BASE_URL + '/ajax_update_skip',
	        data: { campaign_id: $('.campaignId').val(), skipvalue: $('.skipvalue').val() },
	        type: 'POST',
	        success: function(response) {
	            if (response.status == 1) {
	                window.location.reload();
	            } else if (response.status == 0) {
	                Command: toastr["warning"]('Getting error, please try again.');
	            }
	        }
	    });

	});

$(document).on('click','#submitUpdateKeywords',function(e){
	e.preventDefault();

	var checked =[];

	$("input[name='check_list[]']:checked").each(function () {
        checked.push(parseInt($(this).val()));
    });

    if(checked.length == 0 ){
    	Command: toastr["error"]('Please select keywords before update');
      	return false;
    }


    var update_region = $('#update_region').val();
    var update_tracking_options = $('#update_tracking_options').val();
    var update_language = $('#update_language').val();
    var update_location = $('#update_location').val();
    var lat = $('#latUpdate').val();
    var long = $('#longUpdate').val();




    document.getElementById('update_regions_error').innerHTML = '';
    if (update_region == '') {
        document.getElementById('update_regions_error').innerHTML = 'Region is required.';
    }

    document.getElementById('update_tracking_options_error').innerHTML = '';
    if (update_tracking_options == '') {
        document.getElementById('update_tracking_options_error').innerHTML = 'Tracking Option is required.';
    }

    document.getElementById('update_language_error').innerHTML = '';
    if (update_language == '') {
        document.getElementById('update_language_error').innerHTML = 'Language is required.';
    }

    document.getElementById('update_dfs_locations_error').innerHTML = '';
    if (update_location == '' || update_location == null) {
        document.getElementById('update_dfs_locations_error').innerHTML = 'Location is required.';
    }

    if(checked.length > 0){
    	$.ajax({
	        type: "POST",
	        url: BASE_URL + '/ajax_update_keyword_data',
	        data: {update_region: update_region, update_tracking_options: update_tracking_options, 
	        	update_language: update_language, update_location: update_location,checked:checked,_token:$('meta[name="csrf-token"]').attr('content'),lat,long},
	        dataType: 'json',
	        success: function(result) {
	        	if(result.status == 1){
					Command: toastr["success"](result['message']);
	        	} 
	        	if(result.status == 0){
	        		Command: toastr["warning"](result['message']);
	        	}

	        	$(".modal-backdrop").remove();
	            $('body').removeClass('modal-open');
	            $('body').css('padding-right', '');
		        $("#LocationModalKeyword").hide();

	        	$('#LiveKeywordTrackingTable').DataTable().ajax.reload();
	        }
   		});
    } else{
    	console.log('else');
    }

});

google.maps.event.addDomListener(window, 'load', initialize);
function initialize() {
  var input = document.getElementById('autocomplete_search'); 
  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.addListener('place_changed', function () {
  var place = autocomplete.getPlace();
  $('#lat').val(place.geometry['location'].lat());
  $('#long').val(place.geometry['location'].lng());
});
}

google.maps.event.addDomListener(window, 'load', initializeUpdate);
function initializeUpdate() {
  	var input1 = document.getElementById('update_location'); 
  	var autocomplete = new google.maps.places.Autocomplete(input1);
  	autocomplete.addListener('place_changed', function () {
		var place = autocomplete.getPlace();
		$('#latUpdate').val(place.geometry['location'].lat());
		$('#longUpdate').val(place.geometry['location'].lng());
	});
}