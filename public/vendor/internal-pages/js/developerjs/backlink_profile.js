var BASE_URL = $('.base_url').val();

var color = Chart.helpers.color;

var backlinkconfig = {
    type: 'line',
    data: {
        datasets: [{
            label: '',
            yAxisID: 'lineId',
            backgroundColor: color(window.chartColors.orange).alpha(0.25).rgbString(),
            borderColor: window.chartColors.orange,
            data:[],
            pointRadius: 0,
            fill: false,
            lineTension: 0,
            borderWidth: 1
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
                ,
                gridLines: {
                    color: "rgba(0, 0, 0, 0)"
                }
            }],
            yAxes: [
            {
                id: 'lineId',
                gridLines: {
                    drawBorder: true
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Referring Domain'
                },
                ticks: {
                    beginAtZero: true
                },
                position:'left'
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
                },
                labelTextColor: function(context) {
					return '#000';
				}
            },
            backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000'
		
        },
        legend:{
        	display:false
        }
    }
};

var backlinkconfigBack = {
    type: 'line',
    data: {
        datasets: [{
            label: '',
            yAxisID: 'lineId',
            backgroundColor: color(window.chartColors.orange).alpha(0.25).rgbString(),
            borderColor: window.chartColors.orange,
            data:[],
            pointRadius: 0,
            fill: false,
            lineTension: 0,
            borderWidth: 1
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
                }
            }],
            yAxes: [
            {
                id: 'lineId',
                gridLines: {
                    drawBorder: true
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Clicks'
                },
                ticks: {
                    beginAtZero: true
                },
                position:'left'
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
                },
                labelTextColor: function(context) {
					return '#000';
				}
            },
            backgroundColor:'rgb(255, 255, 255)',
			titleFontColor:'#000'
		
        },
        legend:{
        	display:false
        }
    }
};

function backlinkProfileData(campaign_id){
	var column_name = $('#hidden_column_name_backlink').val();
	var order_type = $('#hidden_sort_type_backlink').val();
	var limit = $('#limit_backlink').val();

	$.ajax({
		url:BASE_URL + '/ajax_fetch_backlink_data',
		type:'GET',
		data:{column_name,order_type,campaign_id,limit},
		success:function(response){
			$('#backlink_data tbody').html('');
   			$('#backlink_data tbody').html(response);
   			$("#backlink_data tr th").removeClass('ajax-loader');
   			$("#backlink_data tr td").removeClass('ajax-loader');
		}
	});

	$.ajax({
		url:BASE_URL + '/ajax_fetch_backlink_pagination',
		type:'GET',
		data:{column_name,order_type,campaign_id,limit},
		success:function(response){
			$('.backlink-profile-foot').html('');
   			$('.backlink-profile-foot').html(response);
		}
	});
}


function backlinkProfileData_new(page,column_name,order_type,campaign_id,limit,query){
	$.ajax({
		url:BASE_URL + '/ajax_fetch_backlink_data',
		type:'GET',
		data:{page,column_name,order_type,campaign_id,limit,query},
		success:function(response){
			$('#backlink_data tbody').html('');
   			$('#backlink_data tbody').html(response);

   			$("#backlink_data tr th").removeClass('ajax-loader');
   			$("#backlink_data tr td").removeClass('ajax-loader');

            $('#refresh-backlinks-search').css('display','none');
            if($('.sideDashboardView.active a').attr("href") == '#backlinks'){
             $('#backlinks #refresh-backlinks-search').css('display','none');
         }
		}
	});

	$.ajax({
		url:BASE_URL + '/ajax_fetch_backlink_pagination',
		type:'GET',
		data:{page,column_name,order_type,campaign_id,limit,query},
		success:function(response){
			$('.backlink-profile-foot').html('');
   			$('.backlink-profile-foot').html(response);

		}
	});
}

$(document).on('click','.Backlinks a',function(e){
	e.preventDefault();
	$('li').removeClass('active');
	$(this).parent().addClass('active');
	var page = $(this).attr('href').split('page=')[1];

	$("#backlink_data tr td").addClass('ajax-loader');

	$('#hidden_page_backlink').val(page);
	
	var campaign_id = $('.campaignID').val();
	var column_name = $('#hidden_column_name_backlink').val();
	var order_type = $('#hidden_sort_type_backlink').val();
	var limit = $('#limit_backlink').val();
	var query = $('.backlink_search').val();

	backlinkProfileData_new(page,column_name,order_type,campaign_id,limit,query);
});

$(document).on('click','.backlink_sorting',function(e){
	e.preventDefault();
	var column_name = $(this).attr('data-column_name');
  	var order_type = $(this).attr('data-sorting_type');

	$("#backlink_data tr td").addClass('ajax-loader');

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
	 

	$('#hidden_column_name_backlink').val(column_name);
	$('#hidden_sort_type_backlink').val(reverse_order);

	var page = $('#hidden_page_backlink').val();
	var campaign_id = $('.campaignID').val();
	var limit = $('#limit_backlink').val();

	var query = $('.backlink_search').val();
  	backlinkProfileData_new(page,column_name,reverse_order,campaign_id,limit,query);
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

$(document).on('keyup','.backlink_search',function (e) {
    $('#refresh-backlinks-search').css('display','block');
    if($('.sideDashboardView.active a').attr("href") == '#backlinks'){
        $('#backlinks #refresh-backlinks-search').css('display','block');
    }
});

$(document).on('keyup','.backlink_search',delay(function(e){
  if($(this).val() != '' || $(this).val() != null){
    $('.backLink-search-clear').css('display','block');
     if($('.sideDashboardView.active a').attr("href") == '#backlinks'){
      $('#backlinks .backLink-search-clear').css('display','block');
  }
  }

  if($(this).val() == '' || $(this).val() == null){
    $('.backLink-search-clear').css('display','none');
    if($('.sideDashboardView.active a').attr("href") == '#backlinks'){
      $('#backlinks .backLink-search-clear').css('display','none');
  }
  }
  backlink();
}, 1500));


$(document).on('click','.backLinkClear',function(e){
  e.preventDefault();
  $('.backlink_search').val('');
  if($('.backlink_search').val() == '' || $('.backlink_search').val() == null){
    $('.backLink-search-clear').css('display','none');
    backlink();
  }
});

function backlink(){
    var page =1;
    var query = $('.backlink_search').val();
    if($('.sideDashboardView.active a').attr("href") == '#backlinks'){
      var query = $('#backlinks .backlink_search').val();
    }

    var limit = $('#limit_backlink').val();
    var column_name = $('#hidden_column_name_backlink').val();
    var order_type = $('#hidden_sort_type_backlink').val();
    var campaign_id = $('.campaignID').val();

    $("#backlink_data tr td").addClass('ajax-loader');

    backlinkProfileData_new(page,column_name,order_type,campaign_id,limit,query);
}

$(document).on('change','#backlink_limit',function(e){
	e.preventDefault();
	
	$("#backlink_data tr td").addClass('ajax-loader');

	var limit = $(this).val();
	$('#limit_backlink').val(limit);
	// var page = $('#hidden_page_backlink').val();
	var page =1;
	var query = $('.backlink_search').val();
    if($('.sideDashboardView.active a').attr("href") == '#backlinks'){
        var query = $('#backlinks .backlink_search').val();
    } 

	var column_name = $('#hidden_column_name_backlink').val();
	var order_type = $('#hidden_sort_type_backlink').val();
	var campaign_id = $('.campaignID').val();

	backlinkProfileData_new(page,column_name,order_type,campaign_id,limit,query);
});




function backlinkProfileChart(campaign_id,value){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_backlink_chart_data",
		data: {campaign_id,value},
		dataType: 'json',
		success: function(result) {
			
			if (window.myLineBacklink) {
				window.myLineBacklink.destroy();
			}

			var ctxs = document.getElementById('chart-referring-domains').getContext('2d');
			window.myLineBacklink = new Chart(ctxs, backlinkconfig);

			backlinkconfig.data.datasets[0].data = result['referringDomains'];
			window.myLineBacklink.update();
            $('.backlink-profile-graph').removeClass('ajax-loader');
		}
	});
}

function backlinkProfileChartBack(campaign_id,value){
	$.ajax({
		type: "GET",
		url: BASE_URL + "/ajax_backlink_chart_data",
		data: {campaign_id,value},
		dataType: 'json',
		success: function(result) {
			
			if (window.myLineBacklinkBack) {
				window.myLineBacklinkBack.destroy();
			}

			var ctxs = document.getElementById('chart-referring-domains-back').getContext('2d');
			window.myLineBacklinkBack = new Chart(ctxs, backlinkconfigBack);

			backlinkconfigBack.data.datasets[0].data = result['referringDomains'];
			window.myLineBacklinkBack.update();
            $('.backlink-profile-graph').removeClass('ajax-loader');
		}
	});
}

$(document).on('click','.backlinkChart',function(e){
	e.preventDefault();
	var value = $(this).attr('data-value');
	var campaign_id = $('.campaignID').val();


	$('.backlinkChart').removeClass('active');
	$(this).addClass('active');
	backlinkProfileChart(campaign_id,value);

});

$(document).on('click','.backlinkChart_View',function(e){
    e.preventDefault();
    var value = $(this).attr('data-value');
    var campaign_id = $('.campaignID').val();


    $('.backlinkChart_View').removeClass('active');
    $(this).addClass('active');
    backlinkProfileChartBack(campaign_id,value);

});

function backlinkProfileList(campaign_id){
    $.ajax({
        url:BASE_URL +'/ajax_backlink_profile_list',
        type:'GET',
        data:{campaign_id},
        success:function(response){
            $('#bp_list tbody').html('');
            $('#bp_list tbody').html(response);
            $('#bp_list tr td').removeClass('ajax-loader');
        }
    });
}

function backlinkProfileTimeAgo(campaign_id){
  $.ajax({
    type: 'GET',
    url:  BASE_URL + '/ajax_get_backlinkProfile_time',
    data: {campaign_id},
    success: function(result) {
      if (result['status'] == 1) {
        $('.backlink_profile_time').html(result['time']);
      }
    }
  });
}


$(document).on('click','#refresh_backlink_profile',function(e){
  e.preventDefault();
  var campaign_id = $(this).attr('data-request-id');
  $(this).addClass('refresh-gif');
  $('.backlinkProfile-progress-loader').css('display','block');
  $('.backlink-profile-graph').addClass('ajax-loader');
  $('#bp_list tr td').addClass('ajax-loader');
  $("#backlink_data tr th").addClass('ajax-loader');
  $("#backlink_data tr td").addClass('ajax-loader');
  $(".backlink-profile-foot").addClass('ajax-loader');

  $.ajax({
    type:'GET',
    url:BASE_URL+'/ajax_get_latest_backlinks',
    data:{campaign_id},
    success:function(response){
      backlinkProfileTimeAgo(campaign_id);
      backlinkProfileChart(campaign_id,$('.backlinkSelectdChart').val());
      backlinkProfileList(campaign_id);
      backlinkProfileData(campaign_id);
      $('#refresh_backlink_profile').removeClass('refresh-gif');
      $('.backlinkProfile-progress-loader').css('display','none');
      $('.backlink-profile-graph').removeClass('ajax-loader');
      $('#bp_list tr td').removeClass('ajax-loader');
      $("#backlink_data tr th").removeClass('ajax-loader');
      $("#backlink_data tr td").removeClass('ajax-loader');
      $(".backlink-profile-foot").removeClass('ajax-loader');
    }
  });

});