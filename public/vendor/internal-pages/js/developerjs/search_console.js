// $(document).on("click", function (e) {
//     if ($(e.target).is(".dateRange-popup, .dateRange-popup *") === false) {
//         $(".dateRange-popup").removeClass("show");0
//     }
// });
var configSearchConsoleVisibility = {
  type: 'line',
  data: {
    labels: [],
    datasets: [
    {
      label: 'Clicks',
      labels: [],
      backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
      borderColor: window.chartColors.brightBLue,
      data:[],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      yAxisID: "y-axis-clicks"
    },{
      label: 'Impressions',
      backgroundColor: color(window.chartColors.orange).alpha(0.15).rgbString(),
      borderColor: window.chartColors.orange,
      data: [],
      fill: false,
      lineTension:0.0001,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      yAxisID: "y-axis-impressions"
    }
    ]
  },
  options: {
    maintainAspectRatio:false,
    scales: {
      xAxes: [{
        ticks: {
          major: {
            enabled: true
          },
          source: 'data',
          maxRotation: 0,
          minRotation: 0,
          autoSkip: true,
          maxTicksLimit: 10
        },
        gridLines: {
          color: "rgba(0, 0, 0, 0)"
        }
      }],
      yAxes: [
      {
        type:"linear",
        position: "left",
        id: "y-axis-clicks",
        scaleLabel: {
          display: true,
          labelString: 'Clicks'
        }
      },
      {
        type:"linear",
        position: "right",
        id: "y-axis-impressions",
        gridLines: {
          color: "rgba(0, 0, 0, 0)"
        },
        scaleLabel: {
          display: true,
          labelString: 'Impression'
        }
      }
      ]
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
        }
      }
    },
    legend: {
      align: 'center',
      display:false
    },
    elements: {
      point:{
        radius: 0,
        hitRadius :1
      },

    },
  }
};

$(document).on("click","#dateRange_console_section", function (e) {
  $(".dateRange-popup").toggleClass("show");
  setTimeout(function(){
    var start_date = $('.sc_start_date').val();
    var end_date = $('.sc_end_date').val();
    var start = moment(start_date);
    var end = moment(end_date);
    var label = $('.current_label').val();

    $('#sc_current_range').daterangepicker({
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
   }, current_picker);
    current_picker(start, end,label);
  },100);
});

function current_picker(start, end,label = null) { 
  var new_start = start.format('YYYY-MM-DD');
  var new_end = end.format('YYYY-MM-DD');
  $('.sc_start_date').val(new_start);
  $('.sc_end_date').val(new_end);
  if(label !== null){
    $('.current_label').val(label);
  }else{
    $('.current_label').val('');
  }
  $('#sc_current_range p').html(new_start + ' - ' + new_end);
  var days = date_diff_indays(new_start, new_end);
  $('.comparison_days').val(days);
  if($('#sc_comparison').val() === 'previous_period'){
    var prev_start_date = getdate(new_start, (days-1));
    var prev_end_date = getdate(new_start, -1);
    var prev_days = date_diff_indays(prev_start_date, prev_end_date);
    $('.sc_prev_start_date').val(prev_start_date);
    $('.sc_prev_end_date').val(prev_end_date);
    $('.prev_comparison_days').val(prev_days);
    initialisePreviousCalendar(prev_start_date,prev_end_date);
  }else if($('#sc_comparison').val() === 'previous_year'){
    console.log('new_start '+new_start);
    console.log('new_end '+new_end);
    var prev_sd = createPreviousYear(new_start);
    var prev_ed = createPreviousYear(new_end);
    $('.sc_prev_start_date').val(prev_sd);
    $('.sc_prev_end_date').val(prev_ed);
    //  $('.prev_comparison_days').val(days);
    initialisePreviousCalendar(prev_sd,prev_ed);
  }
}

$(document).on('change','.sc_compare',function(e){
  e.preventDefault();
  var compare_status = $(this).is(':checked');
  if(compare_status === true){
    $('#previous-section').removeClass('hidden-previous-datepicker');
    $('#sc_comparison').removeAttr('readonly','readonly');
    $('#sc_comparison').removeAttr('disabled','disabled');
  }else{
    $('#previous-section').addClass('hidden-previous-datepicker');
    $('#sc_comparison').attr('readonly','readonly');
    $('#sc_comparison').attr('disabled','disabled');
  }
});


function previous_picker(start, end) {
  var prev_sd = getdate(start,0);
  var prev_ed = getdate(end,0);
  $('.sc_prev_start_date').val(prev_sd);
  $('.sc_prev_end_date').val(prev_ed);
  var days = date_diff_indays(prev_sd, prev_ed);
  $('.prev_comparison_days').val(days);
  $('#sc_previous_range p').html(prev_sd + ' - ' + prev_ed);
}

function initialisePreviousCalendar(prev_start,prev_end){
  previous_picker(prev_start, prev_end);
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
  var dd =  newdate.getDate().toString().padStart(2, "0");
  var mm = (newdate.getMonth() + 1).toString().padStart(2, "0");
  var y = newdate.getFullYear();
  var someFormattedDate = y + '-' + mm + '-' + dd;
  return someFormattedDate;
}

$(document).on('change','#sc_comparison',function(e){
  e.preventDefault();
  var new_start = $('.sc_start_date').val();
  var new_end = $('.sc_end_date').val();
  if($('#sc_comparison').val() === 'previous_period'){
    var days = date_diff_indays(new_start, new_end);
    var prev_start_date = getdate(new_start, (days-1));
    var prev_end_date = getdate(new_start, -1);
    $('.sc_prev_start_date').val(prev_start_date);
    $('.sc_prev_end_date').val(prev_end_date);
    $('.comparison_days').val(days);

    initialisePreviousCalendar(prev_start_date,prev_end_date);
  }else if($('#sc_comparison').val() === 'previous_year'){
    var prev_sd = createPreviousYear(new_start);
    var prev_ed = createPreviousYear(new_end);
    var days = date_diff_indays(prev_sd, prev_ed);
    $('.sc_prev_start_date').val(prev_sd);
    $('.sc_prev_end_date').val(prev_ed);
    $('.prev_comparison_days').val(days);
    initialisePreviousCalendar(prev_sd,prev_ed);
  } 
});


function createPreviousYear(ndate){
 var date = new Date(ndate);
 var newdate = new Date(date);
 var dd =  newdate.getDate().toString().padStart(2, "0");
 var mm = (newdate.getMonth() + 1).toString().padStart(2, "0");
 var y = newdate.getFullYear()-1;

 var someFormattedDate = y + '-' + mm + '-' + dd;
 return someFormattedDate;
} 

function removeDisabled(){
  $('.sc_apply_btn').removeAttr('disabled','disabled');
  $('.sc_cancel_btn input').removeAttr('disabled','disabled');
}

$(document).on('click','.sc_apply_btn',function(e){
  var selected_label = $('.current_label').val();
  var current_comparison = $('.comparison_days').val();
  var previous_comparison = $('.prev_comparison_days').val();
  if(current_comparison !== previous_comparison){
    $('#sc_previous_range').addClass('error');
    Command: toastr["error"]('Select equal number of days for comparison');
  }else{
    var current_start = $('.sc_start_date').val();
    var current_end = $('.sc_end_date').val();
    var previous_start = $('.sc_prev_start_date').val();
    var previous_end = $('.sc_prev_end_date').val();
    if($('.sc_compare').is(':checked') === true){
      var comparison = 1;
    }else{
      var comparison = 0;
    }    
    var comparison_selected = $('#sc_comparison').val();

    var campaignId = $('.campaignID').val();
    var userid = $('#user_id').val();
    var key = $('#encriptkey').val();
    if(key !== undefined){
      $('.datepicker_selection').val(2);
    }
    $('.queries tr td').addClass('ajax-loader');
    $('.pages tr td').addClass('ajax-loader');
    $('.countries tr td').addClass('ajax-loader');
    $('.search-console-graph').addClass('ajax-loader');
    var selection_type = $('.datepicker_selection').val();
    var sidebar_selection = $('.vk-sidebar-selected').val();
    
    $.ajax({
      type:"GET",
      url:BASE_URL+"/ajax_new_search_console",
      data:{module:'search_console',campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
      dataType:'json',
      success:function(result){
        displayStats(result);
        if(sidebar_selection === 'visibility'){
          newconsoleChartVisibility(result);
         // console_listing_visibility(campaignId,result['start_date'],result['end_date'],result['duration'],selection_type,sidebar_selection);
       }else{
        newconsoleChart(result);
        console_listing(campaignId,result['start_date'],result['end_date'],result['duration'],selection_type,sidebar_selection);
      }

      $('.search-console-graph').removeClass('ajax-loader');
      $(".dateRange-popup").removeClass("show");
    }
  });
  }
});

var color = Chart.helpers.color;

var configSearchConsole = {
  type: 'line',
  data: {
    labels: [],
    datasets: [
    {
      label: 'Clicks',
      labels: [],
      backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
      borderColor: window.chartColors.brightBLue,
      data:[],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      yAxisID: "y-axis-clicks"
    },{
      label: 'Impressions',
      backgroundColor: color(window.chartColors.orange).alpha(0.15).rgbString(),
      borderColor: window.chartColors.orange,
      data: [],
      fill: false,
      lineTension:0.0001,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      yAxisID: "y-axis-impressions"
    }
    ]
  },
  options: {
    maintainAspectRatio:false,
    scales: {
      xAxes: [{
        ticks: {
          major: {
            enabled: true
          },
          source: 'data',
          maxRotation: 0,
          minRotation: 0,
          autoSkip: true,
          maxTicksLimit: 10
        },
        gridLines: {
          color: "rgba(0, 0, 0, 0)"
        }
      }],
      yAxes: [
      {
        type:"linear",
        position: "left",
        id: "y-axis-clicks",
        scaleLabel: {
          display: true,
          labelString: 'Clicks'
        }
      },
      {
        type:"linear",
        position: "right",
        id: "y-axis-impressions",

        gridLines: {
          color: "rgba(0, 0, 0, 0)"
        },
        scaleLabel: {
          display: true,
          labelString: 'Impression'
        }
      }
      ]
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
        }
      }
    },
    legend: {
      align: 'center',
      display:false
    },
    elements: {
      point:{
        radius: 0,
        hitRadius :1
      },

    },
  }
};

function newconsoleChart(result){
  if(window.myLineSearchConsolee){
    window.myLineSearchConsolee.destroy();
  }

  var ctxSearchConsole = document.getElementById('new-canvas-search-console').getContext('2d');
  window.myLineSearchConsolee = new Chart(ctxSearchConsole, configSearchConsole);

  configSearchConsole.data.labels =  result['from_datelabel'];
  configSearchConsole.data.datasets[0].data = result['clicks'];
  configSearchConsole.data.datasets[0].labels = result['current_labels'];
  configSearchConsole.data.datasets[1].data = result['impressions'];

  if(result['comparison'] == 1 || result['comparison'] == '1'){
   configSearchConsole.data.datasets.splice(2,2);
   configSearchConsole.data.datasets.splice(3,3);

   var dataset_1 = {
     label: 'Clicks',
     labels: result['previous_labels'],
     fill: false,
     borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
     backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
     data: result['previous_clicks'],
     borderWidth:2,
     borderDash: [5,5],
     yAxisID: "y-axis-clicks"
   };
   var dataset_2 = {
     label: 'Impressions',
     fill: false,
     borderColor: color(window.chartColors.orange).alpha(1.0).rgbString(),
     backgroundColor: color(window.chartColors.orange).alpha(0.15).rgbString(),
     data: result['previous_impressions'],
     borderWidth:2,
     borderDash: [5,5],
     yAxisID: "y-axis-impressions"
   };

   configSearchConsole.data.datasets.push(dataset_1);
   configSearchConsole.data.datasets.push(dataset_2);

 } else{  
   configSearchConsole.data.datasets.splice(2,2);
   configSearchConsole.data.datasets.splice(3,3);
 }

 window.myLineSearchConsolee.update();
}

$(document).on('click','.sc_cancel_btn',function(e){
  e.preventDefault();
  $(".dateRange-popup").toggleClass("show");
});

function console_listing(campaignId,start_date,end_date,duration,selection_type,sidebar_selection){
  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_fetch_listing",
    data:{campaignId,start_date,end_date,duration,selection_type,sidebar_selection},
    dataType:'json',
    success:function(result){
      $('.sc_duration').val(result['duration']);

      $('.query_table').html(result['query_data']);
      $('.queries tr th').removeClass('ajax-loader');
      $('.queries tr td').removeClass('ajax-loader');
      
      $('.pages_table').html(result['pages_data']);
      $('.pages tr th').removeClass('ajax-loader');
      $('.pages tr td').removeClass('ajax-loader');

      $('.country_table').html(result['country_data']);
      $('.countries tr th').removeClass('ajax-loader');
      $('.countries tr td').removeClass('ajax-loader');
    }
  });
}

function search_console_graph(campaign_id){
  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_display_search_console_graph",
    data:{campaign_id},
    dataType:'json',
    success:function(result){
      if(result['status'] == 0){
        $('#console_add').css('display','block');
        $('#console_data').css('display','none');
      } 

      if(result['status'] == 1){
        displayStats(result);
        newconsoleChart(result);
        search_console_listing(campaign_id);
        $('.search-console-graph').removeClass('ajax-loader');
        $('#console_add').css('display','none');
        $('#console_data').css('display','block');
      } 

    }
  });
}

function displayStats(result){
 /*current*/
 $('.current_click').html(result['current_clicks_count']);
 $('.current_impressions').html(result['current_impressions_count']);
 $('.current_ctr').html(result['current_ctr_count']);
 $('.current_position').html(result['current_position_count']);
 $('.current_click_dates,.current_impressions_dates').empty().append(result['start_date']+' - '+result['end_date']);
 $('.current_ctr_dates,.current_position_dates').empty().append(result['start_date']+' - '+result['end_date']);
 /*previous*/
 if(result['comparison'] == 1 || result['comparison'] == '1'){
  $('.show_previous_click,.show_previous_impressions,.show_previous_ctr,.show_previous_position').css('display','block');
  $('.previous_click').html(result['previous_clicks_count']);
  $('.previous_impressions').html(result['previous_impressions_count']);
  $('.previous_ctr').html(result['previous_ctr_count']);
  $('.previous_position').html(result['previous_position_count']);
  $('.previous_click_dates,.previous_impressions_dates').empty().append(result['previous_start_date']+' - '+result['previous_end_date']); 
  $('.previous_ctr_dates,.previous_position_dates').empty().append(result['previous_start_date']+' - '+result['previous_end_date']);
}else{
  $('.show_previous_click,.show_previous_impressions,.show_previous_ctr,.show_previous_position').css('display','none');
  $('.previous_click,.previous_impressions,.previous_ctr,.previous_position').html('');
}
$('.single').removeClass('ajax-loader');
}

function search_console_listing(campaign_id){
  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_fetch_list_data",
    data:{campaign_id},
    dataType:'json',
    success:function(result){
      $('.console-nav-bar').removeClass('ajax-loader');
      $('.sc_duration').val(result['duration']);

      $('.query_table').html(result['query_data']);
      $('.queries tr th').removeClass('ajax-loader');
      $('.queries tr td').removeClass('ajax-loader');
      
      $('.pages_table').html(result['pages_data']);
      $('.pages tr th').removeClass('ajax-loader');
      $('.pages tr td').removeClass('ajax-loader');

      $('.country_table').html(result['country_data']);
      $('.countries tr th').removeClass('ajax-loader');
      $('.countries tr td').removeClass('ajax-loader');
    }
  });
}

$(document).on('click','.queries-pagination a',function(e){
  e.preventDefault();
  $('.queries-pagination ul li').removeClass('active');
  $(this).parent().addClass('active');
  var page = $(this).attr('href').split('page=')[1];
  var duration = $('.sc_duration').val();
  var selection_type = $('.datepicker_selection').val();
  var selected = '';
  search_console_queries($('.campaign_id').val(),page,duration,selection_type,selected);
});

function search_console_queries(campaign_id,page,duration,selection_type,selected){
  $('.queries tr td').addClass('ajax-loader');
  $.ajax({
    type:'GET',
    data:{campaign_id,page,duration,selection_type,selected},
    url:BASE_URL +'/ajax_search_console_queries',
    success:function(response){
      if(response['success'] === true){
        if(selected === 'visibility'){
          $('.vk-query_table').html(response['query_data']);
        }else{
          $('.query_table').html(response['query_data']);
        }
        $('.queries tr th').removeClass('ajax-loader');
        $('.queries tr td').removeClass('ajax-loader');
      }
    }
  });
}

$(document).on('click','.pages-pagination a',function(e){
  e.preventDefault();
  $('.pages-pagination ul li').removeClass('active');
  $(this).parent().addClass('active');
  var page = $(this).attr('href').split('page=')[1];
  var duration = $('.sc_duration').val();
  var selection_type = $('.datepicker_selection').val();
  var selected = '';
  search_console_pages($('.campaign_id').val(),page,duration,selection_type,selected);
});

function search_console_pages(campaign_id,page,duration,selection_type,selected){
  $('.pages tr td').addClass('ajax-loader');
  $.ajax({
    type:'GET',
    data:{campaign_id,page,duration,selection_type,selected},
    url:BASE_URL +'/ajax_search_console_pages',
    success:function(response){
     if(selected === 'visibility'){
      $('.vk-pages_table').html(response['pages_data']);
    }else{
      $('.pages_table').html(response['pages_data']);
    }
    $('.pages tr th').removeClass('ajax-loader');
    $('.pages tr td').removeClass('ajax-loader');
  }
});
}

$(document).on('click','.countries-pagination a',function(e){
  e.preventDefault();
  $('.countries-pagination ul li').removeClass('active');
  $(this).parent().addClass('active');
  var page = $(this).attr('href').split('page=')[1];
  var duration = $('.sc_duration').val();
  var selection_type = $('.datepicker_selection').val();
  var selected = '';
  search_console_countries($('.campaign_id').val(),page,duration,selection_type,selected);
});

function search_console_countries(campaign_id,page,duration,selection_type,selected){
  console.log(selected);
  $('.countries tr td').addClass('ajax-loader');
  $.ajax({
    type:'GET',
    data:{campaign_id,page,duration,selection_type,selected},
    url:BASE_URL +'/ajax_search_console_countries',
    success:function(response){
      if(selected === 'visibility'){
        $('.vk-country_table').html(response['country_data']);
      }else{
        $('.country_table').html(response['country_data']);
      }
      $('.countries tr th').removeClass('ajax-loader');
      $('.countries tr td').removeClass('ajax-loader');
    }
  });
}

$(document).on('click','#refresh_search_console_section',function(e){
 e.preventDefault();
 var campaign_id = $(this).attr('data-request-id');
 $(this).addClass('refresh-gif');
 $('.queries tr td').addClass('ajax-loader');
 $('.pages tr td').addClass('ajax-loader');
 $('.countries tr td').addClass('ajax-loader');
 $('.search-console-graph').addClass('ajax-loader');

 $.ajax({
   type:'GET',
   url:BASE_URL+'/ajax_get_latest_console_data',
   data:{campaign_id},
   dataType:'json',
   success:function(response){
     if(response['status'] == 'success'){
       GoogleUpdateTimeAgo('search_console');
       search_console_graph(campaign_id);
     }
     else if(response['status'] == 'google-error'){
       $('.queries tr td').removeClass('ajax-loader');
       $('.pages tr td').removeClass('ajax-loader');
       $('.countries tr td').removeClass('ajax-loader');
       $('.search-console-graph').removeClass('ajax-loader');
       $('#searchconsoleHeading').html('');
       $('#searchconsoleHeading').append('<div class="alert alert-danger"><span><i class="fa fa-exclamation-triangle"></i>Search Console: '+response['message']+' Try reconnecting your account.</span></div>');
       setTimeout(function(){displayFloatingDiv();},100);
       $('html,body').animate({scrollTop: $("#searchconsoleHeading").offset().top},'slow');
     } else if(response['status'] == 'crawled'){
       $('.queries tr td').removeClass('ajax-loader');
       $('.pages tr td').removeClass('ajax-loader');
       $('.countries tr td').removeClass('ajax-loader');
       $('.search-console-graph').removeClass('ajax-loader');
       Command: toastr["info"](response['message']);
     }
     else{
       $('.queries tr td').removeClass('ajax-loader');
       $('.pages tr td').removeClass('ajax-loader');
       $('.countries tr td').removeClass('ajax-loader');
       $('.search-console-graph').removeClass('ajax-loader');
       Command: toastr["error"]('Error, please try again later.');
     }
     $('#refresh_search_console_section').removeClass('refresh-gif');
   }
 });
});


/*Viewkey visibility section*/

function search_console_graph_visibility(campaign_id){
  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_display_search_console_graph",
    data:{campaign_id},
    dataType:'json',
    success:function(result){
      if(result['status'] == 0){
        $('.search-console-graph').removeClass('ajax-loader');
        $('#console_data_vk').css('display','none');
        $('#console_data_visibility').css('display','block');
      } 

      if(result['status'] == 1){
        displayStats(result);
        newconsoleChartVisibility(result);
        search_console_listing_visibility(campaign_id);
        $('.search-console-graph').removeClass('ajax-loader');
        $('#console_data_vk').css('display','block');
        $('#console_data_visibility').css('display','none');
      } 

    }
  });
}

function newconsoleChartVisibility(result){
  if(window.myLineSearchConsoleVisibility){
    window.myLineSearchConsoleVisibility.destroy();
  }

  var ctxSearchConsoleVisibility = document.getElementById('new-canvas-search-console-visibility').getContext('2d');
  window.myLineSearchConsoleVisibility = new Chart(ctxSearchConsoleVisibility, configSearchConsoleVisibility);

  configSearchConsoleVisibility.data.labels =  result['from_datelabel'];
  configSearchConsoleVisibility.data.datasets[0].data = result['clicks'];
  configSearchConsoleVisibility.data.datasets[0].labels = result['current_labels'];
  configSearchConsoleVisibility.data.datasets[1].data = result['impressions'];

  if(result['comparison'] == 1 || result['comparison'] == '1'){
   configSearchConsoleVisibility.data.datasets.splice(2,2);
   configSearchConsoleVisibility.data.datasets.splice(3,3);

   var dataset_1 = {
     label: 'Clicks',
     labels: result['previous_labels'],
     fill: false,
     borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
     backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
     data: result['previous_clicks'],
     borderWidth:2,
     borderDash: [5,5],
     yAxisID: "y-axis-clicks"
   };
   var dataset_2 = {
     label: 'Impressions',
     fill: false,
     borderColor: color(window.chartColors.orange).alpha(1.0).rgbString(),
     backgroundColor: color(window.chartColors.orange).alpha(0.15).rgbString(),
     data: result['previous_impressions'],
     borderWidth:2,
     borderDash: [5,5],
     yAxisID: "y-axis-impressions"
   };

   configSearchConsoleVisibility.data.datasets.push(dataset_1);
   configSearchConsoleVisibility.data.datasets.push(dataset_2);

 } else{  
   configSearchConsoleVisibility.data.datasets.splice(2,2);
   configSearchConsoleVisibility.data.datasets.splice(3,3);
 }

 window.myLineSearchConsoleVisibility.update();
}




function search_console_listing_visibility(campaign_id){
  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_fetch_list_data_visibility",
    data:{campaign_id},
    dataType:'json',
    success:function(result){
      $('.console-nav-bar').removeClass('ajax-loader');
      $('.sc_duration').val(result['duration']);

      $('.vk-query_table').html(result['query_data']);
      $('.queries tr th').removeClass('ajax-loader');
      $('.queries tr td').removeClass('ajax-loader');
      
      $('.vk-pages_table').html(result['pages_data']);
      $('.pages tr th').removeClass('ajax-loader');
      $('.pages tr td').removeClass('ajax-loader');

      $('.vk-country_table').html(result['country_data']);
      $('.countries tr th').removeClass('ajax-loader');
      $('.countries tr td').removeClass('ajax-loader');
    }
  });
}

$(document).on('click','.vk-queries-pagination a',function(e){
  e.preventDefault();
  $('.vk-queries-pagination ul li').removeClass('active');
  $(this).parent().addClass('active');
  var page = $(this).attr('href').split('page=')[1];
  var duration = $('.sc_duration').val();
  var selection_type = $('.datepicker_selection').val();
  var selected = 'visibility';
  search_console_queries($('.campaign_id').val(),page,duration,selection_type,selected);
});

$(document).on('click','.vk-pages-pagination a',function(e){
  e.preventDefault();
  $('.vk-pages-pagination ul li').removeClass('active');
  $(this).parent().addClass('active');
  var page = $(this).attr('href').split('page=')[1];
  var duration = $('.sc_duration').val();
  var selection_type = $('.datepicker_selection').val();
  var selected = 'visibility';
  search_console_pages($('.campaign_id').val(),page,duration,selection_type,selected);
});

$(document).on('click','.vk-countries-pagination a',function(e){
  e.preventDefault();
  $('.vk-countries-pagination ul li').removeClass('active');
  $(this).parent().addClass('active');
  var page = $(this).attr('href').split('page=')[1];
  var duration = $('.sc_duration').val();
  var selection_type = $('.datepicker_selection').val();
  var selected = 'visibility';
  search_console_countries($('.campaign_id').val(),page,duration,selection_type,selected);
});


function console_listing_visibility(campaignId,start_date,end_date,duration,selection_type,sidebar_selection){
  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_fetch_listing",
    data:{campaignId,start_date,end_date,duration,selection_type,sidebar_selection},
    dataType:'json',
    success:function(result){
      $('.sc_duration').val(result['duration']);

      $('.vk-query_table').html(result['query_data']);
      $('.queries tr th').removeClass('ajax-loader');
      $('.queries tr td').removeClass('ajax-loader');
      
      $('.vk-pages_table').html(result['pages_data']);
      $('.pages tr th').removeClass('ajax-loader');
      $('.pages tr td').removeClass('ajax-loader');

      $('.vk-country_table').html(result['country_data']);
      $('.countries tr th').removeClass('ajax-loader');
      $('.countries tr td').removeClass('ajax-loader');
    }
  });
}

/*visibility*/
$(document).on("click","#visibility_dateRange_console_section", function (e) {
  $(".visibility-dateRange-popup").toggleClass("show");
  setTimeout(function(){
    var start_date = $('.sc_visibility_start_date').val();
    var end_date = $('.sc_visibility_end_date').val();
    var start = moment(start_date);
    var end = moment(end_date);
    var label = $('.current_visibility_label').val();

    $('#sc_visibility_current_range').daterangepicker({
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
   }, current_picker_visibilty);
    current_picker_visibilty(start, end,label);
  },100);
});

function current_picker_visibilty(start, end,label = null) { 
  var new_start = start.format('YYYY-MM-DD');
  var new_end = end.format('YYYY-MM-DD');
  $('.sc_visibility_start_date').val(new_start);
  $('.sc_visibility_end_date').val(new_end);
  if(label !== null){
    $('.current_visibility_label').val(label);
  }else{
    $('.current_visibility_label').val('');
  }
  $('#sc_visibility_current_range p').html(new_start + ' - ' + new_end);
  var days = date_diff_indays(new_start, new_end);
  $('.visibility_comparison_days').val(days);
  if($('#sc_visibility_comparison').val() === 'previous_period'){
    var prev_start_date = getdate(new_start, (days-1));
    var prev_end_date = getdate(new_start, -1);
    var prev_days = date_diff_indays(prev_start_date, prev_end_date);
    $('.sc_visibility_prev_start_date').val(prev_start_date);
    $('.sc_visibility_prev_end_date').val(prev_end_date);
    $('.visibility_prev_comparison_days').val(prev_days);
    VisibilityInitialisePreviousCalendar(prev_start_date,prev_end_date);
  }else if($('#sc_visibility_comparison').val() === 'previous_year'){
    var prev_sd = createPreviousYear(new_start);
    var prev_ed = createPreviousYear(new_end);
    $('.sc_visibility_prev_start_date').val(prev_sd);
    $('.sc_visibility_prev_end_date').val(prev_ed);
    VisibilityInitialisePreviousCalendar(prev_sd,prev_ed);
  }
}

function visibility_previous_picker(start, end) {
  var prev_sd = getdate(start,0);
  var prev_ed = getdate(end,0);
  $('.sc_visibility_prev_start_date').val(prev_sd);
  $('.sc_visibility_prev_end_date').val(prev_ed);
  var days = date_diff_indays(prev_sd, prev_ed);
  $('.visibility_prev_comparison_days').val(days);
  $('#sc_visibility_previous_range p').html(prev_sd + ' - ' + prev_ed);
}

function VisibilityInitialisePreviousCalendar(prev_start,prev_end){
  visibility_previous_picker(prev_start, prev_end);
}

$(document).on('change','.sc_visibility_compare',function(e){
  e.preventDefault();
  var compare_status = $(this).is(':checked');
  if(compare_status === true){
    $('#visibility-previous-section').removeClass('hidden-previous-datepicker');
    $('#sc_visibility_comparison').removeAttr('readonly','readonly');
    $('#sc_visibility_comparison').removeAttr('disabled','disabled');
  }else{
    $('#visibility-previous-section').addClass('hidden-previous-datepicker');
    $('#sc_visibility_comparison').attr('readonly','readonly');
    $('#sc_visibility_comparison').attr('disabled','disabled');
  }
});


$(document).on('change','#sc_visibility_comparison',function(e){
  e.preventDefault();
  var new_start = $('.sc_start_date').val();
  var new_end = $('.sc_end_date').val();
  if($('#sc_visibility_comparison').val() === 'previous_period'){
    var days = date_diff_indays(new_start, new_end);
    var prev_start_date = getdate(new_start, (days-1));
    var prev_end_date = getdate(new_start, -1);
    $('.sc_visibility_prev_start_date').val(prev_start_date);
    $('.sc_visibility_prev_end_date').val(prev_end_date);
    $('.visibility_comparison_days').val(days);

    VisibilityInitialisePreviousCalendar(prev_start_date,prev_end_date);
  }else if($('#sc_visibility_comparison').val() === 'previous_year'){
    var prev_sd = createPreviousYear(new_start);
    var prev_ed = createPreviousYear(new_end);
    var days = date_diff_indays(prev_sd, prev_ed);
    $('.sc_visibility_prev_start_date').val(prev_sd);
    $('.sc_visibility_prev_end_date').val(prev_ed);
    $('.visibility_prev_comparison_days').val(days);
    VisibilityInitialisePreviousCalendar(prev_sd,prev_ed);
  } 
});

$(document).on('click','.sc_visibility_apply_btn',function(e){
  var selected_label = $('.current_label').val();
  var current_comparison = $('.visibility_comparison_days').val();
  var previous_comparison = $('.visibility_prev_comparison_days').val();
  if(current_comparison !== previous_comparison){
    $('#sc_visibility_previous_range').addClass('error');
    Command: toastr["error"]('Select equal number of days for comparison');
  }else{
    var current_start = $('.sc_visibility_start_date').val();
    var current_end = $('.sc_visibility_end_date').val();
    var previous_start = $('.sc_visibility_prev_start_date').val();
    var previous_end = $('.sc_visibility_prev_end_date').val();
    if($('.sc_visibility_compare').is(':checked') === true){
      var comparison = 1;
    }else{
      var comparison = 0;
    }    
    var comparison_selected = $('#sc_visibility_comparison').val();

    var campaignId = $('.campaignID').val();
    var userid = $('#user_id').val();
    var key = $('#encriptkey').val();
    if(key !== undefined){
      $('.datepicker_selection').val(2);
    }
    $('.queries tr td').addClass('ajax-loader');
    $('.pages tr td').addClass('ajax-loader');
    $('.countries tr td').addClass('ajax-loader');
    $('.search-console-graph').addClass('ajax-loader');
    var selection_type = $('.datepicker_selection').val();
    var sidebar_selection = $('.vk-sidebar-selected').val();
    
    $.ajax({
      type:"GET",
      url:BASE_URL+"/ajax_new_search_console",
      data:{module:'search_console',campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
      dataType:'json',
      success:function(result){
        displayStats(result);
        newconsoleChartVisibility(result);
        console_listing_visibility(campaignId,result['start_date'],result['end_date'],result['duration'],selection_type,sidebar_selection);
        $('.search-console-graph').removeClass('ajax-loader');
        $(".visibility-dateRange-popup").removeClass("show");
      }
    });
  }
});


$(document).on('click','.sc_visibility_cancel_btn',function(e){
  e.preventDefault();
  $(".visibility-dateRange-popup").toggleClass("show");
});


/*pdf view*/
function search_console_graph_pdf(campaign_id){
  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_display_search_console_graph",
    data:{campaign_id},
    dataType:'json',
    success:function(result){
      if(result['status'] == 0){
        $('#console_add').css('display','block');
        $('#console_data').css('display','none');
      } 

      if(result['status'] == 1){
        displayStats(result);
        newconsoleChart(result);
        search_console_listing_pdf(campaign_id);
        $('.search-console-graph').removeClass('ajax-loader');
        $('#console_add').css('display','none');
        $('#console_data').css('display','block');
      } 
    }
  });
}


function search_console_listing_pdf(campaign_id){
  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_fetch_list_data_pdf",
    data:{campaign_id},
    dataType:'json',
    success:function(result){
      $('.console-nav-bar').removeClass('ajax-loader');
      $('.sc_duration').val(result['duration']);

      $('.query_table').html(result['query_data']);
      $('.queries tr th').removeClass('ajax-loader');
      $('.queries tr td').removeClass('ajax-loader');
      
      $('.pages_table').html(result['pages_data']);
      $('.pages tr th').removeClass('ajax-loader');
      $('.pages tr td').removeClass('ajax-loader');

      $('.country_table').html(result['country_data']);
      $('.countries tr th').removeClass('ajax-loader');
      $('.countries tr td').removeClass('ajax-loader');
    }
  });
}