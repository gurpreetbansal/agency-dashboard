var BASE_URL = $('.base_url').val();
var color = Chart.helpers.color;
function connectPopup(myURL, title, myWidth, myHeight) {
  var left = (screen.width - myWidth) / 2;
  var top = (screen.height - myHeight) / 4;
  window.open(myURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + myWidth + ', height=' + myHeight + ', top=' + top + ', left=' + left);
}


$(document).on('click','.settings_ga4_AddBtn',function(e){
  e.preventDefault();
  var campaignId = $('.campaign_id').val();
  var currentRoute = $('.currentRoute').val();
$('#ga4_existing_emails').addClass('addAnalytics4Detail');
var link = BASE_URL +'/connect_google_analytics_4?campaignId='+campaignId+'&provider=ga4&redirectPage='+currentRoute;
connectPopup(link,"web","500","500");
});

$('#ga4_existing_emails').on('show.bs.select', function (e, clickedIndex, isSelected, previousValue) {  
  $('#ga4_existing_emails').removeClass('addAnalytics4Detail');
});

setInterval(function(){
  if($('#ga4_existing_emails').hasClass('addAnalytics4Detail')){
    getAnalytics4Emails();
  }
}, 3000);

function getAnalytics4Emails(){
  $.ajax({
    url:BASE_URL+'/ajax_get_ga4_emails',
    data:{user_id:$('.user_id').val()},
    type:'GET',
    success:function(response){
      $('#ga4_existing_emails').html(response);
      $('.selectpicker').selectpicker('refresh');
    }
  });
}

$(document).on('change','#ga4_existing_emails',function(e){
  e.preventDefault();
  $('#ga4_existing_emails').removeClass('addAppend');
  var email = $(this).val();
  var campaign_id = $('.campaign_id').val();

  fetch_last_updated(email,campaign_id,'ga4');
  $('.ga4_refresh_div').css('display','block');
  disableSelectPicker('#ga4_accounts','.ga4-account-loader');

  $.ajax({
   type:'GET',
   url:BASE_URL +'/ajax_get_ga4_accounts',
   data:{email,campaign_id},
   dataType:'json',
   success:function(response){      
    enableSelectPicker('#ga4_accounts','.ga4-account-loader');
    $('#ga4_accounts').html(response);
    $('#ga4_property').html('<option value="">Select Property</option>');
    $('.selectpicker').selectpicker('refresh');
  }
});
});


$(document).on('change','#ga4_accounts',function(e){
  e.preventDefault();
  var account_id = $(this).val();
  var campaign_id = $('.campaign_id').val();

  disableSelectPicker('#ga4_property','.ga4-property-loader');

  $.ajax({
   type:'GET',
   url:BASE_URL +'/ajax_get_ga4_properties',
   data:{account_id,campaign_id},
   dataType:'json',
   success:function(response){      
    enableSelectPicker('#ga4_property','.ga4-property-loader');
    $('#ga4_property').html(response);
    $('.selectpicker').selectpicker('refresh');
  }
});
});


$(document).on('click','#save_ga4',function(e){
  e.preventDefault();
  var campaign_id = $('.campaign_id').val();
  var email = $('#ga4_existing_emails').val();
  var account = $('#ga4_accounts').val();
  var property = $('#ga4_property').val();

  if(email == ''){
   $('#ga4_existing_emails').parent().addClass('error');
 }else{
   $('#ga4_existing_emails').parent().removeClass('error');
 }

 if(account == ''){
   $('#ga4_accounts').parent().addClass('error');
 }else{
   $('#ga4_accounts').parent().removeClass('error');
 }

 if(property == ''){
   $('#ga4_property').parent().addClass('error');
 }else{
   $('#ga4_property').parent().removeClass('error');
 }


 if(email != '' && account !=''){
   $('.ga4-progress-loader').css('display','block');
   $('.popup-inner').css('overflow','hidden');
   $(this).attr('disabled','disabled');
   $.ajax({
    type:'POST',
    url:BASE_URL + '/ajax_update_ga4_data',
    data:{campaign_id,email,account,property,_token:$('meta[name="csrf-token"]').attr('content')},
    dataType:'json',
    success:function(response){
     $('.ga4-progress-loader').addClass('complete');
     if (response['status'] == 'success') {
      Command: toastr["success"](response['message']);
      $("#projectSettingsga4Popup_close").trigger("click");
      $("body").removeClass("popup-open");

      // $('#integrationTab').load(location.href + ' #project-integration-list');

      $('.default-analytics').css('display','none');
      $('#ProjectSettings-analytics4').css('display','flex');
      $('#analytics4_connectedEmail').html(response['email']);
      $('#analytics4_connectedAccount').html(response['account']);
      $('#analytics4_connectedProperty').html(response['property']);

    } else if (response['status'] == 'google-error') {
      Command: toastr["error"](response['message'] +' Try reconnecting your account.');
    } 
    else {
      Command: toastr["error"]('Please try again getting error');
    }

    $('#save_ga4').removeAttr('disabled','disabled');

    setTimeout(function(){
      $('#show_ga4_last_time').parent().removeClass('error yellow green');
      $('#show_ga4_last_time').parent().css('display','none');

      $('.ga4-progress-loader').css('display','none');
      $('.ga4-progress-loader').removeClass('complete');
      $('.popup-inner').css('overflow','auto');
    }, 100);
  }
});
 }
});

$(document).on('click','#disconnectga4',function(e){
  e.preventDefault();
  if($('.request_id').val() != ''){
   $.ajax({
    type:'POST',
    url:BASE_URL +'/ajax_disconnect_ga4',
    data:{request_id:$('.request_id').val(),_token:$('meta[name="csrf-token"]').attr('content')},
    dataType:'json',
    success:function(response){
     if(response['status'] == 'success'){
      $('#ga4_existing_emails , #ga4_accounts ,  #ga4_property').selectpicker('refresh');
     // $('#integrationTab').load(location.href + ' #project-integration-list');
      $('.default-analytics').css('display','flex');
      $('#ProjectSettings-analytics4').css('display','none');
    }else{
      Command: toastr["error"]('Error!! Please try again!');
    }
  }
});
 }
});

$(document).on('click','.ga4_cancel_btn',function(e){
  e.preventDefault();
  $("#ga4-dateRange-popup").toggleClass("show");
});

$(document).on("click","#ga4_range_section", function (e) {
  $("#ga4-dateRange-popup").toggleClass("show");
  setTimeout(function(){
    var start_date = $('.ga4_start_date').val();
    var end_date = $('.ga4_end_date').val();
    var start = moment(start_date);
    var end = moment(end_date);
    var label = $('.ga4_current_label').val();

    $('#ga4_current_range').daterangepicker({
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
   }, ga4_current_picker);
    ga4_current_picker(start, end,label);
  },100);
});

function ga4_current_picker(start, end,label = null) { 
  var new_start = start.format('YYYY-MM-DD');
  var new_end = end.format('YYYY-MM-DD');
  $('.ga4_start_date').val(new_start);
  $('.ga4_end_date').val(new_end);
  if(label !== null){
    $('.ga4_current_label').val(label);
  }else{
    $('.ga4_current_label').val('');
  }
  $('#ga4_current_range p').html(new_start + ' - ' + new_end);
  var days = date_diff_indays(new_start, new_end);
  $('.ga4_comparison_days').val(days);
  if($('#ga4_comparison').val() === 'previous_period'){
    var prev_start_date = getdate(new_start, (days-1));
    var prev_end_date = getdate(new_start, -1);
    var prev_days = date_diff_indays(prev_start_date, prev_end_date);
    $('.ga4_prev_start_date').val(prev_start_date);
    $('.ga4_prev_end_date').val(prev_end_date);
    $('.ga4_prev_comparison_days').val(prev_days);
    initialiseGa4PreviousCalendar(prev_start_date,prev_end_date);
  }else if($('#ga4_comparison').val() === 'previous_year'){
    var prev_sd = createPreviousYear(new_start);
    var prev_ed = createPreviousYear(new_end);
    $('.ga4_prev_start_date').val(prev_sd);
    $('.ga4_prev_end_date').val(prev_ed);
    initialiseGa4PreviousCalendar(prev_sd,prev_ed);
  }
}

function initialiseGa4PreviousCalendar(prev_start,prev_end){
  var prev_sd = getdate(prev_start,0);
  var prev_ed = getdate(prev_end,0);
  $('.ga4_prev_start_date').val(prev_sd);
  $('.ga4_prev_end_date').val(prev_ed);
  var days = date_diff_indays(prev_sd, prev_ed);
  $('.ga4_prev_comparison_days').val(days);
  $('#ga4_previous_range p').html(prev_sd + ' - ' + prev_ed);
}

$(document).on('change','.ga4_compare',function(e){
  e.preventDefault();
  var compare_status = $(this).is(':checked');
  if(compare_status === true){
    $('#ga4-previous-section , #ga4-goals-previous-section').removeClass('ga4-hidden-previous-datepicker');
    $('#ga4_comparison').removeAttr('readonly','readonly');
    $('#ga4_comparison').removeAttr('disabled','disabled');
  }else{
    $('#ga4-previous-section , #ga4-goals-previous-section').addClass('ga4-hidden-previous-datepicker');
    $('#ga4_comparison').attr('readonly','readonly');
    $('#ga4_comparison').attr('disabled','disabled');
  }
});

$(document).on('change','#ga4_comparison',function(e){
  e.preventDefault();
  var new_start = $('.ga4_start_date').val();
  var new_end = $('.ga4_end_date').val();
  if($('#ga4_comparison').val() === 'previous_period'){
    var days = date_diff_indays(new_start, new_end);
    var prev_start_date = getdate(new_start, (days-1));
    var prev_end_date = getdate(new_start, -1);
    $('.ga4_prev_start_date').val(prev_start_date);
    $('.ga4_prev_end_date').val(prev_end_date);
    $('.ga4_comparison_days').val(days);

    initialiseGa4PreviousCalendar(prev_start_date,prev_end_date);
  }else if($('#ga4_comparison').val() === 'previous_year'){
    var prev_sd = createPreviousYear(new_start);
    var prev_ed = createPreviousYear(new_end);
    var days = date_diff_indays(prev_sd, prev_ed);
    $('.ga4_prev_start_date').val(prev_sd);
    $('.ga4_prev_end_date').val(prev_ed);
    $('.ga4_prev_comparison_days').val(days);
    initialiseGa4PreviousCalendar(prev_sd,prev_ed);
  } 
});

$(document).on('click','.ga4_apply_btn',function(e){
  var selected_label = $('.ga4_current_label').val();
  
    var current_start = $('.ga4_start_date').val();
    var current_end = $('.ga4_end_date').val();
    var previous_start = $('.ga4_prev_start_date').val();
    var previous_end = $('.ga4_prev_end_date').val();

    $('.ga4_goals_current_label').val(selected_label);
    $('.ga4_goals_start_date').val(current_start);
    $('.ga4_goals_end_date').val(current_end);
    $('.ga4_goals_prev_start_date').val(previous_start);
    $('.ga4_goals_prev_end_date').val(previous_end);


    if($('.ga4_compare').is(':checked') === true){
      var comparison = 1;
      $('#ga4_goals_comparison').removeAttr('readonly','readonly');
      $('#ga4_goals_comparison').removeAttr('disabled','disabled');
      $('#ga4-previous-section ,#ga4-goals-previous-section').removeClass('ga4-hidden-previous-datepicker');
      $('.ga4_goals_compare').prop('checked',true);
    }else{
      var comparison = 0;
      $('#ga4_goals_comparison').attr('readonly','readonly');
      $('#ga4_goals_comparison').attr('disabled','disabled');
      $('#ga4-previous-section ,#ga4-goals-previous-section').addClass('ga4-hidden-previous-datepicker');
      $('.ga4_goals_compare').prop('checked',false);
    }    
    var comparison_selected = $('#ga4_comparison').val();

    var campaignId = $('.campaignID').val();
    var userid = $('#user_id').val();
    var key = $('#encriptkey').val();
    if(key !== undefined){
      $('.ga4_datepicker_selection').val(2);
    }

  $('.ga4_goals_comparison').val(comparison_selected);

  $('#all-user-box, #new-user-box, .traffic-growth-graph-allUser-ga4, .traffic-growth-graph-newUser-ga4, .usersBySession-defaultChannel-overTime, .usersBySession-defaultChannel, .ga-compare-result').addClass('ajax-loader');

  var selection_type = $('.ga4_datepicker_selection').val();

  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_google_analytics_overview",
    data:{module:'ga4',campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
    dataType:'json',
    success:function(result){
      ajax_acquisition_overview(campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label);
      ajax_traffic_acquisition(campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label);
      ajax_goals_listing_traffic_acquisition(campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label);
      $("#ga4-dateRange-popup").removeClass("show");
    }
  });
});

function ajax_acquisition_overview(campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label){
  $.ajax({
     type:"GET",
     url:BASE_URL+"/ajax_acquisition_overview",
     data:{campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
     dataType:'json',
     success:function(result){
      GoogleUpdateTimeAgo('ga4');
      acquisitionDisplayStats(result);
      $('#all-user-box, #new-user-box').removeClass('ajax-loader');

      acquisitionOverview(result);
      $('.traffic-growth-graph-allUser-ga4').removeClass('ajax-loader');

      acquisitionNewUser(result);
      $('.traffic-growth-graph-newUser-ga4').removeClass('ajax-loader');
    }
  });
}

function acquisitionDisplayStats(result){
  $('.ga4_range').html(result['display_range']);
  $('.active-users').html(result['current_activeUsers_count']);
  $('.new-users').html(result['current_newUsers_count']);

  $('.active-users-comparison ,.new-users-comparison').removeClass('green , red');
  if(result['comparison'] == 1 || result['comparison'] == '1'){
    if(result['previous_percentage'] > 0){
      var previous_percentage = result['previous_percentage'] + '%';
      var active_user_arrow = 'ion-arrow-up-a'; var active_user_class = 'green';
    }else if(result['previous_percentage'] < 0){
      var total_activeUserPercentage = result['previous_percentage'].toString();
      var previous_percentage = total_activeUserPercentage.replace('-', '') + '%';
      var active_user_arrow = 'ion-arrow-down-a'; var active_user_class = 'red';
    }else if(result['previous_percentage'] == 0){
     var previous_percentage = '-';
      var active_user_class = ''; var active_user_arrow = '';
    }else{
      var previous_percentage = result['previous_percentage'] + '%';
      var active_user_class = ''; var active_user_arrow = '';
    }

    $('.active-users-comparison').addClass(active_user_class);
    $('.active-users-comparison').html('<i class="icon '+ active_user_arrow +'"></i><span class="active-users-comparison-value">'+ previous_percentage +' </span>');

    if(result['previous_newUser_percentage'] > 0){
      var previous_newUser_percentage = result['previous_newUser_percentage'] + '%';
      var new_user_arrow = 'ion-arrow-up-a'; var new_user_class = 'green';
    }else if(result['previous_newUser_percentage'] < 0){
      var total_newUserPercentage = result['previous_newUser_percentage'].toString();
      var previous_newUser_percentage = total_newUserPercentage.replace('-', '') + '%';
      var new_user_arrow = 'ion-arrow-down-a'; var new_user_class = 'red';
    }else if(result['previous_newUser_percentage'] == 0){
      var previous_newUser_percentage = '-';
      var new_user_arrow = ''; var new_user_class = '';
    }else{
      var previous_newUser_percentage = result['previous_newUser_percentage'] + '%';
      var new_user_arrow = ''; var new_user_class = '';
    }

    $('.new-users-comparison').addClass(new_user_class);
    $('.new-users-comparison').html('<i class="icon '+ new_user_arrow +'"></i><span class="new-users-comparison-value">'+ previous_newUser_percentage +' </span>');
  }else{
    $('.active-users-comparison ,.new-users-comparison').html('');
    $('.active-users-comparison ,.new-users-comparison').removeClass('green , red');
  }

  $('.organic-user-box .single').removeClass('ajax-loader');
}

function acquisitionOverview(result) {   
  if (window.trafficGa4) {
    window.trafficGa4.destroy();
  }
  var trafficGrowthGa4 = document.getElementById('ott-ga4').getContext('2d');
  window.trafficGa4 = new Chart(trafficGrowthGa4, configTrafficGa4);

  configTrafficGa4.data.labels =  result['dates'];
  configTrafficGa4.data.datasets[0].labels = result['dates'];
  configTrafficGa4.data.datasets[0].data = result['active_users'];

  if(result['compare_status'] == 1){
    configTrafficGa4.data.datasets.splice(1, 1);
    var newDataset = {
      label: 'Users (Previous)',
      labels: result['previous_dates'],
      borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
      backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
      data: result['previous_active_users'],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      borderDash: [5,3],
      pointStyle:'dash'
    };

    configTrafficGa4.data.datasets.push(newDataset);
  } else{
    configTrafficGa4.data.datasets.splice(1, 1);
  }

  window.trafficGa4.update();
}

var configTrafficGa4 = {
  type: 'line',
  data: {
    labels: [],
    datasets: [{
      label: "Users (Current)",
      labels: [],
      fill: false,
      backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
      borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
      data: [],
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      pointStyle:'line'
    }]
  },
  options: {
    maintainAspectRatio: false,
    elements: {
      line: {
        tension: 0.000001
      },
      point:{
        radius: 0,
        hitRadius :1

      }
    },
    tooltips: {
      intersect: false,
      mode: 'index',
      backgroundColor:'rgb(255, 255, 255)',
      titleFontColor:'#000',
      bodyFontStyle: 'normal',  
      titleMarginBottom : 10,
      titleFontStyle:'normal',
      bodySpacing: 5,
      callbacks: {
        labelColor: function(tooltipItem, chart) {
            return {
                borderWidth:0
            }
        },
        labelTextColor: function(context) {  return '#000';} ,
        title: function() {} ,
        beforeLabel: function(tooltipItem, data) { 
          if(data.datasets.length > 1){
            if(tooltipItem.datasetIndex == 0){
              return data.datasets[0].labels[tooltipItem.index] + '  vs   '+ data.datasets[1].labels[tooltipItem.index];  
            }
          }else{
            return data.datasets[0].labels[tooltipItem.index];
          }
        },
        label: function(tooltipItem, data) {
          if(data.datasets.length > 1){
            if(tooltipItem.datasetIndex == 1){
              return ' Users:     '+ (data.datasets[0].data[tooltipItem.index]).toLocaleString("en-US")+ '  vs  '+  (data.datasets[1].data[tooltipItem.index]).toLocaleString("en-US");
            }
          }else{
            return ' Users:     '+ (data.datasets[tooltipItem['datasetIndex']].data[tooltipItem.index]).toLocaleString("en-US");
          }
        }
      }
    },
    scales: {
      xAxes: [{
        display: true,
        gridLines: {
          color: "rgba(0, 0, 0, 0)",
          display: false,
        },
        ticks: {
          maxRotation: 0,
          minRotation: 0,
          autoSkip: true,
          maxTicksLimit: 10
        }
      }],
      yAxes: [{
        position:'right',
        gridLines: {
          drawBorder: false,
        },
        display: true,
        ticks: {
          min: 0,
          beginAtZero:true,
        }
      }]
    },
    legend: { 
      align: 'center',
      padding:10,
      labels: {
       usePointStyle: true
      }
    }
  }
};

$(document).on('click','.refresh-ott-ga4',function(e){
  e.preventDefault();
  var campaign_id = $(this).attr('data-request-id');
  $(this).addClass('refresh-gif');
  $('.organic-user-box .single').addClass('ajax-loader');
  $('.au-total, .au-graph , .ga4-conversions, .ga4-overview-conversions, .traffic-growth-graph-allUser-ga4, .traffic-growth-graph-newUser-ga4, .usersBySession-defaultChannel-overTime, .usersBySession-defaultChannel, .ga-compare-result').addClass('ajax-loader');

  $.ajax({
     type:'GET',
     url:BASE_URL+'/ajax_get_latest_googleAnalytics4',
     data:{campaign_id},
     dataType:'json',
     success:function(response){
       if(response['status'] == 'success'){
        ga4_overview_allUser_Chart(campaign_id);
        ga4_overview_allUser_stats(campaign_id);
        ga4_overview_conversions_Chart(campaign_id);
        ga4_overview_conversions_stats(campaign_id);
        ajax_acquisition_overview(campaign_id,'','','','','','','','');
        ajax_traffic_acquisition(campaign_id,'','','','','','','','');
        ajax_goals_listing_traffic_acquisition(campaign_id,'','','','','','','','');
      }
      if(response['status'] == 'google-error'){
        $('.googleAnalytics4Heading').html('');
        $('.googleAnalytics4Heading').append('<div class="alert alert-danger"><span><i class="fa fa-exclamation-triangle"></i>Google Analytics: '+response['message']+' Try reconnecting your account.</span></div>');
        setTimeout(function(){displayFloatingDiv();},100);  
        $('html,body').animate({scrollTop: $(".googleAnalytics4Heading").offset().top},'slow');
      }
      if(response['status'] == 'error'){
        Command: toastr["error"](response['message']);
      }
      $('.refresh-ott-ga4').removeClass('refresh-gif');
      $('.organic-user-box .single').removeClass('ajax-loader');
      $('.traffic-growth-graph-allUser-ga4').removeClass('ajax-loader');
    }
  });
});

$(document).on('click','#new-user-box',function(e){
  e.preventDefault();
  var campaign_id = $('.campaign-id').val();
  $('#all-user-box').removeClass('selected');
  $('#new-user-box').addClass('selected');
  $('.traffic-growth-graph-newUser-ga4').addClass('ajax-loader');
  $('.traffic-growth-graph-allUser-ga4').css('display','none');
  $('.traffic-growth-graph-newUser-ga4').css('display','block');
  setTimeout(function(){
    $('.traffic-growth-graph-newUser-ga4').removeClass('ajax-loader');
  },1000);    
});

$(document).on('click','#all-user-box',function(e){
  e.preventDefault();
  var campaign_id = $('.campaign-id').val();
  $('.traffic-growth-graph-allUser-ga4').addClass('ajax-loader');
  $('#all-user-box').addClass('selected');
  $('#new-user-box').removeClass('selected');
  $('.traffic-growth-graph-allUser-ga4').css('display','block');
  $('.traffic-growth-graph-newUser-ga4').css('display','none');
  setTimeout(function(){
    $('.traffic-growth-graph-allUser-ga4').removeClass('ajax-loader');
  },1000);  
});


function acquisitionNewUser(result) {   
  if (window.trafficNewUserGa4) {
    window.trafficNewUserGa4.destroy();
  }
  var trafficGrowthNewUserGa4 = document.getElementById('ott-newUser-ga4').getContext('2d');
  window.trafficNewUserGa4 = new Chart(trafficGrowthNewUserGa4, configTrafficNewUserGa4);

  configTrafficNewUserGa4.data.labels =  result['dates'];
  configTrafficNewUserGa4.data.datasets[0].labels = result['dates'];
  configTrafficNewUserGa4.data.datasets[0].data = result['new_users'];

  if(result['compare_status'] == 1){
    configTrafficNewUserGa4.data.datasets.splice(1, 1);
    var newDataset = {
      label: 'New Users (Previous)',
      labels: result['previous_dates'],
      borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
      backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
      data: result['previous_new_users'],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      borderDash: [5,2],
       pointStyle:'dash'
    };

    configTrafficNewUserGa4.data.datasets.push(newDataset);
  } else{
    configTrafficNewUserGa4.data.datasets.splice(1, 1);
  }

  window.trafficNewUserGa4.update();
}

var configTrafficNewUserGa4 = {
  type: 'line',
  data: {
    labels: [],
    datasets: [{
      label: "New Users (Current)",
      labels: [],
      fill: false,
      backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
      borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
      data: [],
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
       pointStyle:'line'
    }]
  },
  options: {
    maintainAspectRatio: false,
    elements: {
      line: {
        tension: 0.000001
      },
      point:{
        radius: 0,
        hitRadius :1

      }
    },
    tooltips: {
      intersect: false,
      mode: 'index',
      backgroundColor:'rgb(248, 249, 250)',
      titleFontColor:'#000',
      bodyFontStyle: 'normal',  
      titleMarginBottom : 10,
      titleFontStyle:'normal',
      bodySpacing: 5,
      callbacks: {
        labelColor: function(tooltipItem, chart) {
            return {
                borderWidth:0
            }
        },
        labelTextColor: function(context) {  return '#000';} ,
        title: function() {} ,
        beforeLabel: function(tooltipItem, data) { 
          if(data.datasets.length > 1){
            if(tooltipItem.datasetIndex == 0){
              return data.datasets[0].labels[tooltipItem.index] + '  vs   '+ data.datasets[1].labels[tooltipItem.index];  
            }
          }else{
            return data.datasets[0].labels[tooltipItem.index];
          }
        },
        label: function(tooltipItem, data) {
          if(data.datasets.length > 1){
          
            if(tooltipItem.datasetIndex == 1){
              return ' Users:     '+ (data.datasets[0].data[tooltipItem.index]).toLocaleString("en-US")+ '  vs   '+ (data.datasets[1].data[tooltipItem.index]).toLocaleString("en-US");
            }
          }else{
            return ' Users:     '+ (data.datasets[tooltipItem['datasetIndex']].data[tooltipItem.index]).toLocaleString("en-US");
          }
        }
      }
    },
    scales: {
      xAxes: [{
        display: true,
        gridLines: {
          color: "rgba(0, 0, 0, 0)",
          display: false,
        },
        ticks: {
          maxRotation: 0,
          minRotation: 0,
          autoSkip: true,
          maxTicksLimit: 10
        }
      }],
      yAxes: [{
        position:'right',
        gridLines: {
          drawBorder: false,
        },
        display: true,
        ticks: {
          min: 0,
        }
      }]
    },
    legend: { 
      align: 'center',
      padding:10,
      labels:{
        usePointStyle:true
      }
    }
  }
};


function ajax_traffic_acquisition(campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label){
  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_traffic_acquisition",
    data:{campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
    dataType:'json',
    success:function(result){
     // $('.ga4_range').html('<small><span uk-icon="clock"></span> '+result['display_range'] + '</small>');
      $('.ga4_range').html(result['display_range']);
      UserbySession_overTime(result);
      $('.usersBySession-defaultChannel-overTime').removeClass('ajax-loader');

      UserbySession(result);
      $('.usersBySession-defaultChannel').removeClass('ajax-loader');
    }
  });
}

function UserbySession_overTime(result) {   
  if (window.user_by_session_overTime) {
    window.user_by_session_overTime.destroy();
  }
  var UserbySessionOverTime = document.getElementById('usersBySession_defaultChannel_overTime').getContext('2d');
  window.user_by_session_overTime = new Chart(UserbySessionOverTime, configSessionByUserOverTime);

  configSessionByUserOverTime.data.labels =  result['dates'];
  configSessionByUserOverTime.data.datasets[0].labels = result['dates'];

  configSessionByUserOverTime.data.datasets[0].data = result['organic_social'];
  configSessionByUserOverTime.data.datasets[1].data = result['organic_search'];
  configSessionByUserOverTime.data.datasets[2].data = result['paid_social'];
  configSessionByUserOverTime.data.datasets[3].data = result['paid_search'];
  configSessionByUserOverTime.data.datasets[4].data = result['direct'];

  if(result['compare_status'] == 1){
    configSessionByUserOverTime.data.datasets.splice(5, 5);
    configSessionByUserOverTime.data.datasets.splice(6, 6);
    configSessionByUserOverTime.data.datasets.splice(7, 7);
    configSessionByUserOverTime.data.datasets.splice(8, 8);
    configSessionByUserOverTime.data.datasets.splice(9, 9);
    var newDataset = 
    {
      label: 'Previous Organic Social',
      labels: result['previous_dates'],
      borderColor: color(window.chartColors.purple).alpha(1.0).rgbString(),
      backgroundColor: color(window.chartColors.purple).alpha(0.15).rgbString(),
      data: result['previous_organic_social'],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      borderDash: [5,2]
    };

    var newDataset1 = {
      label: 'Previous Organic Search',
      labels: result['previous_dates'],
      borderColor: color(window.chartColors.blue).alpha(1.0).rgbString(),
      backgroundColor: color(window.chartColors.blue).alpha(0.15).rgbString(),
      data: result['previous_organic_search'],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      borderDash: [5,2]
    }; 
    var newDataset2 = {
      label: 'Previous Paid Social',
      labels: result['previous_dates'],
      borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
      backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
      data: result['previous_paid_social'],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      borderDash: [5,2]
    };
    var newDataset3 =  {
      label: 'Previous Paid Search',
      labels: result['previous_dates'],
      borderColor: color(window.chartColors.green).alpha(1.0).rgbString(),
      backgroundColor: color(window.chartColors.green).alpha(0.15).rgbString(),
      data: result['previous_paid_search'],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      borderDash: [5,2]
    };
    var newDataset4 = {
      label: 'Previous Direct',
      labels: result['previous_dates'],
      borderColor: color(window.chartColors.darkBlue).alpha(1.0).rgbString(),
      backgroundColor: color(window.chartColors.darkBlue).alpha(0.15).rgbString(),
      data: result['previous_direct'],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      borderDash: [5,2]
    };

    configSessionByUserOverTime.data.datasets.push(newDataset);
    configSessionByUserOverTime.data.datasets.push(newDataset1);
    configSessionByUserOverTime.data.datasets.push(newDataset2);
    configSessionByUserOverTime.data.datasets.push(newDataset3);
    configSessionByUserOverTime.data.datasets.push(newDataset4);
  } else{
    configSessionByUserOverTime.data.datasets.splice(5, 5);
    configSessionByUserOverTime.data.datasets.splice(6, 6);
    configSessionByUserOverTime.data.datasets.splice(7, 7);
    configSessionByUserOverTime.data.datasets.splice(8, 8);
    configSessionByUserOverTime.data.datasets.splice(9, 9);
  }

  window.user_by_session_overTime.update();
}

var configSessionByUserOverTime = {
  type: 'line',
  data: {
    labels: [],
    datasets: [
    {
      label: "Organic Social",
      labels: [],
      fill: false,
      backgroundColor: color(window.chartColors.purple).alpha(0.15).rgbString(),
      borderColor: color(window.chartColors.purple).alpha(1.0).rgbString(),
      data: [],
      pointHoverRadius: 3,
      pointHoverBackgroundColor: color(window.chartColors.purple).alpha(1.0).rgbString(),
      borderWidth:2
    },{
      label: "Organic Search",
      labels: [],
      fill: false,
      backgroundColor: color(window.chartColors.blue).alpha(0.15).rgbString(),
      borderColor: color(window.chartColors.blue).alpha(1.0).rgbString(),
      data: [],
      pointHoverRadius: 3,
      pointHoverBackgroundColor: color(window.chartColors.blue).alpha(1.0).rgbString(),
      borderWidth:2
    },{
      label: "Paid Social",
      labels: [],
      fill: false,
      backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
      borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
      data: [],
      pointHoverRadius: 3,
      pointHoverBackgroundColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
      borderWidth:2
    },{
      label: "Paid Search",
      labels: [],
      fill: false,
      backgroundColor: color(window.chartColors.green).alpha(0.15).rgbString(),
      borderColor: color(window.chartColors.green).alpha(1.0).rgbString(),
      data: [],
      pointHoverRadius: 3,
      pointHoverBackgroundColor: color(window.chartColors.green).alpha(1.0).rgbString(),
      borderWidth:2
    },{
      label: "Direct",
      labels: [],
      fill: false,
      backgroundColor: color(window.chartColors.darkBlue).alpha(0.15).rgbString(),
      borderColor: color(window.chartColors.darkBlue).alpha(1.0).rgbString(),
      data: [],
      pointHoverRadius: 3,
      pointHoverBackgroundColor: color(window.chartColors.darkBlue).alpha(1.0).rgbString(),
      borderWidth:2
    },
    ]
  },
  options: {
    maintainAspectRatio: false,
    elements: {
      line: {
        tension: 0.000001
      },
      point:{
        radius: 0,
        hitRadius :1

      }
    },
    tooltips: {
      intersect: false,
      mode: 'index',
      backgroundColor:'rgb(255, 255, 255)',
      titleFontColor:'#000',
      bodyFontStyle: 'normal',  
      titleMarginBottom : 10,
      titleFontStyle:'normal',
      bodySpacing: 5,
      callbacks: {
         labelColor: function(tooltipItem, chart) {
            return {
                borderWidth:0
            }
        },
        labelTextColor: function(context) { return '#000'; },
        title: function(tooltipItem, data) { },
        afterLabel: function(tooltipItem, data) { },
        beforeLabel: function(tooltipItem, data) { 
            if(tooltipItem.datasetIndex == 0){
               if(data.datasets.length > 5){
                return data.datasets[0].labels[tooltipItem.index] + '  vs   '+ data.datasets[5].labels[tooltipItem.index];  
              }else{
                return data.datasets[0].labels[tooltipItem.index];
              }
            }
        },
        label: function(tooltipItem, data) {
          if(data.datasets.length > 5){
            if(tooltipItem.datasetIndex == 0){
              return data['datasets'][tooltipItem['datasetIndex']]['label'] + '     '+ (data.datasets[0].data[tooltipItem.index]).toLocaleString("en-US")+ ' vs '+ (data.datasets[5].data[tooltipItem.index]).toLocaleString("en-US");
            }
            if(tooltipItem.datasetIndex == 1){
              return data['datasets'][tooltipItem['datasetIndex']]['label'] + '     '+ (data.datasets[1].data[tooltipItem.index]).toLocaleString("en-US")+ ' vs '+ (data.datasets[6].data[tooltipItem.index]).toLocaleString("en-US");
            }
            if(tooltipItem.datasetIndex == 2){
              return data['datasets'][tooltipItem['datasetIndex']]['label'] + '     '+ (data.datasets[2].data[tooltipItem.index]).toLocaleString("en-US")+ ' vs '+ (data.datasets[7].data[tooltipItem.index]).toLocaleString("en-US");
            }
            if(tooltipItem.datasetIndex == 3){
              return data['datasets'][tooltipItem['datasetIndex']]['label'] + '     '+ (data.datasets[3].data[tooltipItem.index]).toLocaleString("en-US")+ ' vs '+ (data.datasets[8].data[tooltipItem.index]).toLocaleString("en-US");
            }
            if(tooltipItem.datasetIndex == 4){
              return data['datasets'][tooltipItem['datasetIndex']]['label'] + '     '+ (data.datasets[4].data[tooltipItem.index]).toLocaleString("en-US")+ ' vs '+ (data.datasets[9].data[tooltipItem.index]).toLocaleString("en-US");
            }
          }else{
             return data['datasets'][tooltipItem['datasetIndex']]['label'] + '      '+ (data.datasets[tooltipItem['datasetIndex']].data[tooltipItem.index]).toLocaleString("en-US");
          }
        }
      }
    },
    scales: {
      xAxes: [{
        display: true,
        gridLines: {
          color: "rgba(0, 0, 0, 0)",
          display: false,
        },
        ticks: {
          maxRotation: 0,
          minRotation: 0,
          autoSkip: true,
          maxTicksLimit: 6
        }
      }],
      yAxes: [{
        position:'right',
        gridLines: {
          drawBorder: false,
        },
        display: true,
        ticks: {
          min: 0,
        }
      }]
    },
    legend: { 
    // align: 'left',
    position: 'bottom',
    padding:10,
    labels:{
      usePointStyle : true,
      boxWidth: 6,
      filter: function(item, chart) {
        if(!item.text.includes('Previous Organic Search') && !item.text.includes('Previous Organic Social') && !item.text.includes('Previous Paid Search') && !item.text.includes('Previous Paid Social') && !item.text.includes('Previous Direct')){
          return true;
        }
      }
    }
  }
}
};

function UserbySession(result) {   
  if (window.UserBySession) {
    window.UserBySession.destroy();
  }
  var UserbySessionBar = document.getElementById('usersBySession_defaultChannel').getContext('2d');
  window.UserBySession = new Chart(UserbySessionBar, configSessionByUser);

  configSessionByUser.data.datasets[0].label = result['current_label'];
  configSessionByUser.data.datasets[0].data = [result['organic_social_count'],result['organic_search_count'],result['paid_social_count'],result['paid_search_count'],result['direct_count']];

  if(result['compare_status'] == 1){
    configSessionByUser.data.datasets.splice(1, 1);
    var newDataset = {
      borderWidth: 2,
      borderColor: '#90b3f6',
      backgroundColor: '#90b3f6',
      data: [result['previous_organic_social_count'],result['previous_organic_search_count'],result['previous_paid_social_count'],result['previous_paid_search_count'],result['previous_direct_count']],
      label: result['previous_label']
    };
    configSessionByUser.data.datasets.push(newDataset);
  } else{
    configSessionByUser.data.datasets.splice(1, 1);
  }

  window.UserBySession.update();
}

var configSessionByUser = {
  type: 'horizontalBar',
  data: {
    labels: ["Organic Social", "Organic Search", "Paid Social", "Paid Search", "Direct"],
    datasets: [{
      label: '',
      borderWidth: 2,
      backgroundColor: '#3366ff',
      borderColor: '#3366ff',
      data: []
    }
    ]
  },
  options: {
    legend:false,
    maintainAspectRatio: false,
    tooltips: {
      intersect: false,
      mode: 'index',
      backgroundColor:'rgb(255, 255, 255)',
      titleFontColor:'#000',
      bodyFontStyle: 'normal',  
      titleMarginBottom : 10,
      titleFontStyle:'normal',
      bodySpacing: 5,
      yAlign: 'top',
      callbacks: {
        labelTextColor: function(context) { return '#000'; },
        title: function(tooltipItem, data) {
         if(tooltipItem.length == 2){ 
           return data.datasets[0].label + ' vs. '+ data.datasets[1].label;
         }else{
          return data.datasets[0].label;
        }
      },
      afterLabel: function(tooltipItem, data) {
        if(data.datasets.length == 2){
          if(tooltipItem.datasetIndex === 0){
            return data.labels[tooltipItem.index] + '     '+ (data.datasets[0].data[tooltipItem.index]).toLocaleString("en-US")+ '  vs  '+ (data.datasets[1].data[tooltipItem.index]).toLocaleString("en-US");  
          }
        }else{
          return data.labels[tooltipItem.index] + '     '+ (data.datasets[0].data[tooltipItem.index]).toLocaleString("en-US");
        }
      },
      beforeLabel: function(tooltipItem, data) {
        if(tooltipItem.datasetIndex === 1){ 
          if(tooltipItem.datasetIndex === 0){
            return "Users ";
          }
        }else{
          return "Users ";
        }
      },
      label: function(tooltipItem) {}
    }
  },
  scales: {
    yAxes: [{
      gridLines: {
        display: false
      }
    }],
    xAxes: [{
      ticks: {
        maxRotation: 0,
        minRotation: 0,
        autoSkip: true,
        maxTicksLimit: 4
      }
    }],
  }
}
};

function ajax_goals_listing_traffic_acquisition(campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label){
 $.ajax({
  type:"GET",
  url:BASE_URL+"/ajax_goals_listing_traffic_acquisition",
  data:{campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
  success:function(result){
    $('.ga-compare tbody').html(result);
    $('.ga-compare-result').removeClass('ajax-loader');     
  }
});
}

$(document).on('click','#refresh_ga4_account',function(e){
  e.preventDefault();
  $(this).addClass('refresh-gif');

  $('#save_ga4').attr('disabled','disabled');
  $('.popup-inner').css('overflow','hidden');

  var email = $('#ga4_existing_emails').val();
  var campaign_id = $('.campaign_id').val();

  $('.ga4-progress-loader').css('display','block');

  $('#show_ga4_last_time').parent().removeClass('error ,green');
  $('#show_ga4_last_time').parent().addClass('yellow');
  $('#show_ga4_last_time').parent().css('display','block');

  document.getElementById('show_ga4_last_time').innerHTML = 'Fetching list of accounts.';

  $.ajax({
    type:'GET',
    url:BASE_URL+'/ajax_refresh_ga4_list',
    data:{email,campaign_id},
    dataType:'json',
    success:function(response){

      if(response['status'] == 1){
        $.ajax({
          type:'GET',
          url:BASE_URL +'/ajax_get_ga4_accounts',
          data:{email},
          success:function(result){
            $('#ga4_accounts').html(result);
            $('.selectpicker').selectpicker('refresh');
            var li = '<option value="">Select Property</option>';
            $('#ga4_addNew_property').html(li);
          }
        });

        $('.ga4-progress-loader').addClass('complete');

        $('#refresh_ga4_account').removeClass('refresh-gif');
        $('#show_ga4_last_time').parent().removeClass('error , yellow');
        $('#show_ga4_last_time').parent().addClass('green');
        $('#show_ga4_last_time').parent().css('display','block');
        document.getElementById('show_ga4_last_time').innerHTML = response['message'];
      }

      if(response['status'] == 0){
        $('#refresh_ga4_account').removeClass('refresh-gif');        
        $('#show_ga4_last_time').parent().removeClass('yellow , green');
        $('#show_ga4_last_time').parent().addClass('error');
        $('#show_ga4_last_time').parent().css('display','block');
        document.getElementById('show_ga4_last_time').innerHTML = response['message'];
      }

      if(response['status'] == 2){
        $('#refresh_ga4_account').removeClass('refresh-gif');
        $('#show_ga4_last_time').parent().removeClass('yellow ,green');
        $('#show_ga4_last_time').parent().addClass('error');
        $('#show_ga4_last_time').parent().css('display','block');
        document.getElementById('show_ga4_last_time').innerHTML = response['message'];
      }

      setTimeout(function(){
        $('.ga4-progress-loader').css('display','none');
        $('.ga4-progress-loader').removeClass('complete');
        $('#save_ga4').removeAttr('disabled','disabled');
        $('.popup-inner').css('overflow','auto');
      }, 500);
    }
  });
});


$(document).on('click','#connect_ua ,#connect_ga4',function(e){
  e.preventDefault();
  $('#googleAnalytics_popup_close').trigger('click');
});

/*viewkey*/
function ajax_SeoTraffic_acquisition_overview(campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label){
  $.ajax({
   type:"GET",
   url:BASE_URL+"/ajax_acquisition_overview",
   data:{campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
   dataType:'json',
   success:function(result){
    viewkey_acquisitionDisplayStats(result);

    viewkey_acquisitionOverview(result);
    $('.seoTraffic-growth-graph-allUser-ga4').removeClass('ajax-loader');

    viewkey_acquisitionNewUser(result);
    $('.seoTraffic-growth-graph-newUser-ga4').removeClass('ajax-loader');
  }
});
}

function viewkey_acquisitionDisplayStats(result){
  $('.traffic-ga4-range').html(result['display_range']);
  $('.traffic-active-users').html(result['current_activeUsers_count']);
  $('.traffic-new-users').html(result['current_newUsers_count']);
  
  $('.traffic-active-users-comparison ,.traffic-new-users-comparison').removeClass('green , red');

  if(result['comparison'] == 1 || result['comparison'] == '1'){
    if(result['previous_percentage'] > 0){
      var previous_percentage = result['previous_percentage']+'%';
      var active_user_arrow = 'ion-arrow-up-a'; var active_user_class = 'green';
    }else if(result['previous_percentage'] < 0){
      var total_activeUserPercentage = result['previous_percentage'].toString();
      var previous_percentage = total_activeUserPercentage.replace('-', '')+'%';
      var active_user_arrow = 'ion-arrow-down-a'; var active_user_class = 'red';
    }else if(result['previous_percentage'] == 0){
      var previous_percentage = '-';
      var active_user_class = ''; var active_user_arrow = '';
    }else{
      var previous_percentage = result['previous_percentage'];
      var active_user_class = ''; var active_user_arrow = '';
    }

    $('.traffic-active-users-comparison').addClass(active_user_class);
    $('.traffic-active-users-comparison').html('<i class="icon '+ active_user_arrow +'"></i><span class="traffic-active-users-comparison-value">'+ previous_percentage +' </span>');

    if(result['previous_newUser_percentage'] > 0){
      var previous_newUser_percentage = result['previous_newUser_percentage']+'%';
      var new_user_arrow = 'ion-arrow-up-a'; var new_user_class = 'green';
    }else if(result['previous_newUser_percentage'] < 0){
      var total_newUserPercentage = result['previous_newUser_percentage'].toString();
      var previous_newUser_percentage = total_newUserPercentage.replace('-', '')+'%';
      var new_user_arrow = 'ion-arrow-down-a'; var new_user_class = 'red';
    }else if(result['previous_newUser_percentage'] == 0){
      var previous_newUser_percentage = '-';
      var new_user_arrow = ''; var new_user_class = '';
    }else{
      var previous_newUser_percentage = result['previous_newUser_percentage']+'%';
      var new_user_arrow = ''; var new_user_class = '';
    }

    $('.traffic-new-users-comparison').addClass(new_user_class);
    $('.traffic-new-users-comparison').html('<i class="icon '+ new_user_arrow +'"></i><span class="traffic-new-users-comparison-value">'+ previous_newUser_percentage +' </span>');
  }else{
    $('.traffic-active-users-comparison, .traffic-new-users-comparison').html('');
    $('.traffic-active-users-comparison, .traffic-new-users-comparison').removeClass('green , red');
  }

  $('.traffic-organic-user-box .single').removeClass('ajax-loader');
}

function viewkey_acquisitionOverview(result) {   
  if (window.seoTrafficGa4) {
    window.seoTrafficGa4.destroy();
  }
  var seotrafficGrowthGa4 = document.getElementById('traffic-ott-ga4').getContext('2d');
  window.seoTrafficGa4 = new Chart(seotrafficGrowthGa4, configSeoTrafficGa4);

  configSeoTrafficGa4.data.labels =  result['dates'];
  configSeoTrafficGa4.data.datasets[0].labels = result['dates'];
  configSeoTrafficGa4.data.datasets[0].data = result['active_users'];

  if(result['compare_status'] == 1){
    configSeoTrafficGa4.data.datasets.splice(1, 1);
    var newDataset = {
      label: 'Users (Previous)',
      labels: result['previous_dates'],
      borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
      backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
      data: result['previous_active_users'],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      borderDash: [5,2],
      pointStyle:'dash'
    };

    configSeoTrafficGa4.data.datasets.push(newDataset);
  } else{
    configSeoTrafficGa4.data.datasets.splice(1, 1);
  }

  window.seoTrafficGa4.update();
}

var configSeoTrafficGa4 = {
  type: 'line',
  data: {
    labels: [],
    datasets: [{
      label: "Users (Current)",
      labels: [],
      fill: false,
      backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
      borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
      data: [],
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      pointStyle:'dash'
    }]
  },
  options: {
    maintainAspectRatio: false,
    elements: {
      line: {
        tension: 0.000001
      },
      point:{
        radius: 0,
        hitRadius :1

      }
    },
    tooltips: {
      intersect: false,
      mode: 'index',
      backgroundColor:'rgb(255, 255, 255)',
      titleFontColor:'#000',
      bodyFontStyle: 'normal',  
      titleMarginBottom : 10,
      titleFontStyle:'normal',
      bodySpacing: 5,
      callbacks: {
        labelColor: function(tooltipItem, chart) {
            return {
                borderWidth:0
            }
        },
        labelTextColor: function(context) {  return '#000';} ,
        title: function() {} ,
        beforeLabel: function(tooltipItem, data) { 
          if(data.datasets.length > 1){
             if(tooltipItem.datasetIndex == 0){
              return data.datasets[0].labels[tooltipItem.index] + '  vs   '+ data.datasets[1].labels[tooltipItem.index];  
            }
          }else{
            return data.datasets[0].labels[tooltipItem.index];
          }
        },
        label: function(tooltipItem, data) {
          if(data.datasets.length > 1){
            if(tooltipItem.datasetIndex == 1){
              return ' Users:     '+ data.datasets[0].data[tooltipItem.index]+ '   vs  '+ data.datasets[1].data[tooltipItem.index];
            }
          }else{
            return ' Users:     '+ data.datasets[tooltipItem['datasetIndex']].data[tooltipItem.index];
          }
        }
      }
    },
    scales: {
      xAxes: [{
        display: true,
        gridLines: {
          color: "rgba(0, 0, 0, 0)",
          display: false,
        },
        ticks: {
          maxRotation: 0,
          minRotation: 0,
          autoSkip: true,
          maxTicksLimit: 10
        }
      }],
      yAxes: [{
        position:'right',
        gridLines: {
          drawBorder: false,
        },
        display: true,
        ticks: {
          min: 0,
        }
      }]
    },
    legend: { 
      align: 'center',
      padding:10,
      labels: {
       usePointStyle: true
      }
    }
  }
};

function viewkey_acquisitionNewUser(result) {   
  if (window.SeoTrafficNewUserGa4) {
    window.SeoTrafficNewUserGa4.destroy();
  }
  var SeoTrafficGrowthNewUserGa4 = document.getElementById('traffic-ott-newUser-ga4').getContext('2d');
  window.SeoTrafficNewUserGa4 = new Chart(SeoTrafficGrowthNewUserGa4, configSeoTrafficNewUserGa4);

  configSeoTrafficNewUserGa4.data.labels =  result['dates'];
  configSeoTrafficNewUserGa4.data.datasets[0].labels = result['dates'];
  configSeoTrafficNewUserGa4.data.datasets[0].data = result['new_users'];

  if(result['compare_status'] == 1){
    configSeoTrafficNewUserGa4.data.datasets.splice(1, 1);
    var newDataset = {
      label: 'New Users (Previous)',  
      labels: result['previous_dates'],
      borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
      backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
      data: result['previous_new_users'],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      borderDash: [5,2],
      pointStyle:'dash'
    };

    configSeoTrafficNewUserGa4.data.datasets.push(newDataset);
  } else{
    configSeoTrafficNewUserGa4.data.datasets.splice(1, 1);
  }

  window.SeoTrafficNewUserGa4.update();
}

var configSeoTrafficNewUserGa4 = {
  type: 'line',
  data: {
    labels: [],
    datasets: [{
      label: "New Users (Current)",
      labels: [],
      fill: false,
      backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
      borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
      data: [],
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      pointStyle:'line'
    }]
  },
  options: {
    maintainAspectRatio: false,
    elements: {
      line: {
        tension: 0.000001
      },
      point:{
        radius: 0,
        hitRadius :1

      }
    },
    tooltips: {
      intersect: false,
      mode: 'index',
      backgroundColor:'rgb(255, 255, 255)',
      titleFontColor:'#000',
      bodyFontStyle: 'normal',  
      titleMarginBottom : 10,
      titleFontStyle:'normal',
      bodySpacing: 5,
      callbacks: {
        labelColor: function(tooltipItem, chart) {
            return {
                borderWidth:0
            }
        },
        labelTextColor: function(context) {  return '#000';} ,
        title: function() {} ,
        beforeLabel: function(tooltipItem, data) { 
          if(data.datasets.length > 1){
            if(tooltipItem.datasetIndex == 0){
              return data.datasets[0].labels[tooltipItem.index] + '  vs   '+ data.datasets[1].labels[tooltipItem.index];  
            }
          }else{
            return data.datasets[0].labels[tooltipItem.index];
          }
        },
        label: function(tooltipItem, data) {
          if(data.datasets.length > 1){
             if(data.datasets[1].data[tooltipItem.index] != 0){
              var previous_data = data.datasets[1].data[tooltipItem.index];
            }else{
              var previous_data = '-';
            }

            if(tooltipItem.datasetIndex == 1){
              return 'Users:     '+ data.datasets[0].data[tooltipItem.index]+ '     '+ previous_data;
            }
          }else{
            return 'Users:     '+ data.datasets[tooltipItem['datasetIndex']].data[tooltipItem.index];
          }
        }
      }
    },
    scales: {
      xAxes: [{
        display: true,
        gridLines: {
          color: "rgba(0, 0, 0, 0)",
          display: false,
        },
        ticks: {
          maxRotation: 0,
          minRotation: 0,
          autoSkip: true,
          maxTicksLimit: 10
        }
      }],
      yAxes: [{
        position:'right',
        gridLines: {
          drawBorder: false,
        },
        display: true,
        ticks: {
          min: 0,
        }
      }]
    },
    legend: { 
      align: 'center',
      padding:10,
      labels:{
        usePointStyle:true
      }
    }
  }
};

$(document).on('click','.ga4_seoTraffic_cancel_btn',function(e){
  e.preventDefault();
  $("#ga4-seoTraffic-dateRange-popup").toggleClass("show");
});

$(document).on("click","#ga4_seoTraffic_range_section", function (e) {
  $("#ga4-seoTraffic-dateRange-popup").toggleClass("show");
  setTimeout(function(){
    var start_date = $('.ga4_seoTraffic_start_date').val();
    var end_date = $('.ga4_seoTraffic_end_date').val();
    var start = moment(start_date);
    var end = moment(end_date);
    var label = $('.ga4_seoTraffic_current_label').val();

    $('#ga4_seoTraffic_current_range').daterangepicker({
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
   }, ga4_seoTraffic_current_picker);
    ga4_seoTraffic_current_picker(start, end,label);
  },100);
});

function ga4_seoTraffic_current_picker(start, end,label = null) { 
  var new_start = start.format('YYYY-MM-DD');
  var new_end = end.format('YYYY-MM-DD');
  $('.ga4_seoTraffic_start_date').val(new_start);
  $('.ga4_seoTraffic_end_date').val(new_end);
  if(label !== null){
    $('.ga4_seoTraffic_current_label').val(label);
  }else{
    $('.ga4_seoTraffic_current_label').val('');
  }
  $('#ga4_seoTraffic_current_range p').html(new_start + ' - ' + new_end);
  var days = date_diff_indays(new_start, new_end);
  $('.ga4_seoTraffic_comparison_days').val(days);
  if($('#ga4_seoTraffic_comparison').val() === 'previous_period'){
    var prev_start_date = getdate(new_start, (days-1));
    var prev_end_date = getdate(new_start, -1);
    var prev_days = date_diff_indays(prev_start_date, prev_end_date);
    $('.ga4_seoTraffic_prev_start_date').val(prev_start_date);
    $('.ga4_seoTraffic_prev_end_date').val(prev_end_date);
    $('.ga4_seoTraffic_prev_comparison_days').val(prev_days);
    initialiseSeoTrafficGa4PreviousCalendar(prev_start_date,prev_end_date);
  }else if($('#ga4_seoTraffic_comparison').val() === 'previous_year'){
    var prev_sd = createPreviousYear(new_start);
    var prev_ed = createPreviousYear(new_end);
    $('.ga4_seoTraffic_prev_start_date').val(prev_sd);
    $('.ga4_seoTraffic_prev_end_date').val(prev_ed);
    initialiseSeoTrafficGa4PreviousCalendar(prev_sd,prev_ed);
  }
}

function initialiseSeoTrafficGa4PreviousCalendar(prev_start,prev_end){
  var prev_sd = getdate(prev_start,0);
  var prev_ed = getdate(prev_end,0);
  $('.ga4_seoTraffic_prev_start_date').val(prev_sd);
  $('.ga4_seoTraffic_prev_end_date').val(prev_ed);
  var days = date_diff_indays(prev_sd, prev_ed);
  $('.ga4_seoTraffic_prev_comparison_days').val(days);
  $('#ga4_seoTraffic_previous_range p').html(prev_sd + ' - ' + prev_ed);
}

$(document).on('click','#seoTraffic-all-user-box',function(e){
  e.preventDefault();
  var campaign_id = $('.campaign-id').val();
  $('.seoTraffic-growth-graph-allUser-ga4').addClass('ajax-loader');
  $('#seoTraffic-all-user-box').addClass('selected');
  $('#seoTraffic-new-user-box').removeClass('selected');
  $('.seoTraffic-growth-graph-allUser-ga4').css('display','block');
  $('.seoTraffic-growth-graph-newUser-ga4').css('display','none');
  setTimeout(function(){
    $('.seoTraffic-growth-graph-allUser-ga4').removeClass('ajax-loader');
  },1000);  
});

$(document).on('click','#seoTraffic-new-user-box',function(e){
  e.preventDefault();
  var campaign_id = $('.campaign-id').val();
  $('#seoTraffic-all-user-box').removeClass('selected');
  $('#seoTraffic-new-user-box').addClass('selected');
  $('.seoTraffic-growth-graph-newUser-ga4').addClass('ajax-loader');
  $('.seoTraffic-growth-graph-allUser-ga4').css('display','none');
  $('.seoTraffic-growth-graph-newUser-ga4').css('display','block');
  setTimeout(function(){
    $('.seoTraffic-growth-graph-newUser-ga4').removeClass('ajax-loader');
  },1000);    
});

$(document).on('click','.ga4_seoTraffic_apply_btn',function(e){
  console.log('clicked');
  var selected_label = $('.ga4_seoTraffic_current_label').val();
  
    var current_start = $('.ga4_seoTraffic_start_date').val();
    var current_end = $('.ga4_seoTraffic_end_date').val();
    var previous_start = $('.ga4_seoTraffic_prev_start_date').val();
    var previous_end = $('.ga4_seoTraffic_prev_end_date').val();
    if($('.ga4_seoTraffic_compare').is(':checked') === true){
      var comparison = 1;
    }else{
      var comparison = 0;
    }    
    var comparison_selected = $('#ga4_seoTraffic_comparison').val();

    var campaignId = $('.campaignID').val();
    var userid = $('#user_id').val();
    var key = $('#encriptkey').val();
    if(key !== undefined){
      $('.ga4_datepicker_selection').val(2);
    }

$('#seoTraffic-all-user-box, #seoTraffic-new-user-box, .seoTraffic-growth-graph-allUser-ga4, .seoTraffic-growth-graph-newUser-ga4').addClass('ajax-loader');

var selection_type = $('.ga4_datepicker_selection').val();

$.ajax({
  type:"GET",
  url:BASE_URL+"/ajax_google_analytics_overview",
  data:{module:'ga4',campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
  dataType:'json',
  success:function(result){
  ajax_SeoTraffic_acquisition_overview(campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label);
  $("#ga4-seoTraffic-dateRange-popup").removeClass("show");
}
});
});

$(document).on('change','.ga4_seoTraffic_compare',function(e){
  e.preventDefault();
  var compare_status = $(this).is(':checked');
  if(compare_status === true){
    $('#seoTraffic-ga4-previous-section').removeClass('ga4-hidden-previous-datepicker');
    $('#ga4_seoTraffic_comparison').removeAttr('readonly','readonly');
    $('#ga4_seoTraffic_comparison').removeAttr('disabled','disabled');
  }else{
    $('#seoTraffic-ga4-previous-section').addClass('ga4-hidden-previous-datepicker');
    $('#ga4_seoTraffic_comparison').attr('readonly','readonly');
    $('#ga4_seoTraffic_comparison').attr('disabled','disabled');
  }
});

$(document).on('change','#ga4_seoTraffic_comparison',function(e){
  e.preventDefault();
  var new_start = $('.ga4_seoTraffic_start_date').val();
  var new_end = $('.ga4_seoTraffic_end_date').val();
  if($('#ga4_seoTraffic_comparison').val() === 'previous_period'){
    var days = date_diff_indays(new_start, new_end);
    var prev_start_date = getdate(new_start, (days-1));
    var prev_end_date = getdate(new_start, -1);
    $('.ga4_seoTraffic_prev_start_date').val(prev_start_date);
    $('.ga4_seoTraffic_prev_end_date').val(prev_end_date);
    $('.ga4_seoTraffic_comparison_days').val(days);

    initialiseSeoTrafficGa4PreviousCalendar(prev_start_date,prev_end_date);
  }else if($('#ga4_seoTraffic_comparison').val() === 'previous_year'){
    var prev_sd = createPreviousYear(new_start);
    var prev_ed = createPreviousYear(new_end);
    var days = date_diff_indays(prev_sd, prev_ed);
    $('.ga4_seoTraffic_prev_start_date').val(prev_sd);
    $('.ga4_seoTraffic_prev_end_date').val(prev_ed);
    $('.ga4_seoTraffic_prev_comparison_days').val(days);
    initialiseSeoTrafficGa4PreviousCalendar(prev_sd,prev_ed);
  } 
});

function ajax_goals_traffic_acquisition(campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label){
  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_traffic_acquisition",
    data:{campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
    dataType:'json',
    success:function(result){
      $('.ga4Goals-ga4-range').html(result['display_range']);
      UserbySession_overTime_goals(result);
      $('.goals-usersBySession-defaultChannel-overTime').removeClass('ajax-loader');

      UserbySession_goals(result);
      $('.goals-usersBySession-defaultChannel').removeClass('ajax-loader');
    }
  });
}


function UserbySession_overTime_goals(result) {   
  if (window.user_by_session_overTime_goals) {
    window.user_by_session_overTime_goals.destroy();
  }
  var UserbySessionOverTime_goals = document.getElementById('usersBySession_defaultChannel_overTime_goals').getContext('2d');
  window.user_by_session_overTime_goals = new Chart(UserbySessionOverTime_goals, configSessionByUserOverTime_goals);

  configSessionByUserOverTime_goals.data.labels =  result['dates'];
  configSessionByUserOverTime_goals.data.datasets[0].labels = result['dates'];

  configSessionByUserOverTime_goals.data.datasets[0].data = result['organic_social'];
  configSessionByUserOverTime_goals.data.datasets[1].data = result['organic_search'];
  configSessionByUserOverTime_goals.data.datasets[2].data = result['paid_social'];
  configSessionByUserOverTime_goals.data.datasets[3].data = result['paid_search'];
  configSessionByUserOverTime_goals.data.datasets[4].data = result['direct'];

  if(result['compare_status'] == 1){
    configSessionByUserOverTime_goals.data.datasets.splice(5, 5);
    configSessionByUserOverTime_goals.data.datasets.splice(6, 6);
    configSessionByUserOverTime_goals.data.datasets.splice(7, 7);
    configSessionByUserOverTime_goals.data.datasets.splice(8, 8);
    configSessionByUserOverTime_goals.data.datasets.splice(9, 9);
    var newDataset = 
    {
      label: 'Previous Organic Social',
      labels: result['previous_dates'],
      borderColor: color(window.chartColors.purple).alpha(1.0).rgbString(),
      backgroundColor: color(window.chartColors.purple).alpha(0.15).rgbString(),
      data: result['previous_organic_social'],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      borderDash: [5,2]
    };

    var newDataset1 = {
      label: 'Previous Organic Search',
      labels: result['previous_dates'],
      borderColor: color(window.chartColors.blue).alpha(1.0).rgbString(),
      backgroundColor: color(window.chartColors.blue).alpha(0.15).rgbString(),
      data: result['previous_organic_search'],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      borderDash: [5,2]
    }; 
    var newDataset2 = {
      label: 'Previous Paid Social',
      labels: result['previous_dates'],
      borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
      backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
      data: result['previous_paid_social'],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      borderDash: [5,2]
    };
    var newDataset3 =  {
      label: 'Previous Paid Search',
      labels: result['previous_dates'],
      borderColor: color(window.chartColors.green).alpha(1.0).rgbString(),
      backgroundColor: color(window.chartColors.green).alpha(0.15).rgbString(),
      data: result['previous_paid_search'],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      borderDash: [5,2]
    };
    var newDataset4 = {
      label: 'Previous Direct',
      labels: result['previous_dates'],
      borderColor: color(window.chartColors.darkBlue).alpha(1.0).rgbString(),
      backgroundColor: color(window.chartColors.darkBlue).alpha(0.15).rgbString(),
      data: result['previous_direct'],
      fill: false,
      pointHoverRadius: 5,
      pointHoverBackgroundColor: 'white',
      borderWidth:2,
      borderDash: [5,2]
    };

    configSessionByUserOverTime_goals.data.datasets.push(newDataset);
    configSessionByUserOverTime_goals.data.datasets.push(newDataset1);
    configSessionByUserOverTime_goals.data.datasets.push(newDataset2);
    configSessionByUserOverTime_goals.data.datasets.push(newDataset3);
    configSessionByUserOverTime_goals.data.datasets.push(newDataset4);
  } else{
    configSessionByUserOverTime_goals.data.datasets.splice(5, 5);
    configSessionByUserOverTime_goals.data.datasets.splice(6, 6);
    configSessionByUserOverTime_goals.data.datasets.splice(7, 7);
    configSessionByUserOverTime_goals.data.datasets.splice(8, 8);
    configSessionByUserOverTime_goals.data.datasets.splice(9, 9);
  }

  window.user_by_session_overTime_goals.update();
}

var configSessionByUserOverTime_goals = {
  type: 'line',
  data: {
    labels: [],
    datasets: [
    {
      label: "Organic Social",
      labels: [],
      fill: false,
      backgroundColor: color(window.chartColors.purple).alpha(0.15).rgbString(),
      borderColor: color(window.chartColors.purple).alpha(1.0).rgbString(),
      data: [],
      pointHoverRadius: 3,
      pointHoverBackgroundColor: color(window.chartColors.purple).alpha(1.0).rgbString(),
      borderWidth:2
    },{
      label: "Organic Search",
      labels: [],
      fill: false,
      backgroundColor: color(window.chartColors.blue).alpha(0.15).rgbString(),
      borderColor: color(window.chartColors.blue).alpha(1.0).rgbString(),
      data: [],
      pointHoverRadius: 3,
      pointHoverBackgroundColor: color(window.chartColors.blue).alpha(1.0).rgbString(),
      borderWidth:2
    },{
      label: "Paid Social",
      labels: [],
      fill: false,
      backgroundColor: color(window.chartColors.brightBLue).alpha(0.15).rgbString(),
      borderColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
      data: [],
      pointHoverRadius: 3,
      pointHoverBackgroundColor: color(window.chartColors.brightBLue).alpha(1.0).rgbString(),
      borderWidth:2
    },{
      label: "Paid Search",
      labels: [],
      fill: false,
      backgroundColor: color(window.chartColors.green).alpha(0.15).rgbString(),
      borderColor: color(window.chartColors.green).alpha(1.0).rgbString(),
      data: [],
      pointHoverRadius: 3,
      pointHoverBackgroundColor: color(window.chartColors.green).alpha(1.0).rgbString(),
      borderWidth:2
    },{
      label: "Direct",
      labels: [],
      fill: false,
      backgroundColor: color(window.chartColors.darkBlue).alpha(0.15).rgbString(),
      borderColor: color(window.chartColors.darkBlue).alpha(1.0).rgbString(),
      data: [],
      pointHoverRadius: 3,
      pointHoverBackgroundColor: color(window.chartColors.darkBlue).alpha(1.0).rgbString(),
      borderWidth:2
    },
    ]
  },
  options: {
    maintainAspectRatio: false,
    elements: {
      line: {
        tension: 0.000001
      },
      point:{
        radius: 0,
        hitRadius :1

      }
    },
    tooltips: {
      intersect: false,
      mode: 'index',
      backgroundColor:'rgb(255, 255, 255)',
      titleFontColor:'#000',
      bodyFontStyle: 'normal',  
      titleMarginBottom : 10,
      titleFontStyle:'normal',
      bodySpacing: 5,
      caretSize : 5,
      callbacks: {
        labelColor: function(tooltipItem, chart) {
            return {
                borderWidth:0
            }
        },
        labelTextColor: function(context) { return '#000'; },
        title: function(tooltipItem, data) { },
        afterLabel: function(tooltipItem, data) { },
        beforeLabel: function(tooltipItem, data) { 
            if(tooltipItem.datasetIndex == 0){
               if(data.datasets.length > 5){
                return data.datasets[0].labels[tooltipItem.index] + '  vs   '+ data.datasets[5].labels[tooltipItem.index];  
              }else{
                return data.datasets[0].labels[tooltipItem.index];
              }
            }
        },
        label: function(tooltipItem, data) {
          if(data.datasets.length > 5){
            if(tooltipItem.datasetIndex == 0){
              return data['datasets'][tooltipItem['datasetIndex']]['label'] + '     '+ data.datasets[0].data[tooltipItem.index]+ ' vs '+ data.datasets[5].data[tooltipItem.index];
            }
            if(tooltipItem.datasetIndex == 1){
              return data['datasets'][tooltipItem['datasetIndex']]['label'] + '     '+ data.datasets[1].data[tooltipItem.index]+ ' vs '+ data.datasets[6].data[tooltipItem.index];
            }
            if(tooltipItem.datasetIndex == 2){
              return data['datasets'][tooltipItem['datasetIndex']]['label'] + '     '+ data.datasets[2].data[tooltipItem.index]+ ' vs '+ data.datasets[7].data[tooltipItem.index];
            }
            if(tooltipItem.datasetIndex == 3){
              return data['datasets'][tooltipItem['datasetIndex']]['label'] + '     '+ data.datasets[3].data[tooltipItem.index]+ ' vs '+ data.datasets[8].data[tooltipItem.index];
            }
            if(tooltipItem.datasetIndex == 4){
              return data['datasets'][tooltipItem['datasetIndex']]['label'] + '     '+ data.datasets[4].data[tooltipItem.index]+ ' vs '+ data.datasets[9].data[tooltipItem.index];
            }
          }else{
            return data['datasets'][tooltipItem['datasetIndex']]['label'] + '     '+ data.datasets[tooltipItem['datasetIndex']].data[tooltipItem.index];
          }
        }
      }
    },
    scales: {
      xAxes: [{
        display: true,
        gridLines: {
          color: "rgba(0, 0, 0, 0)",
          display: false,
        },
        ticks: {
          maxRotation: 0,
          minRotation: 0,
          autoSkip: true,
          maxTicksLimit: 6
        }
      }],
      yAxes: [{
        position:'right',
        gridLines: {
          drawBorder: false,
        },
        display: true,
        ticks: {
          min: 0,
        }
      }]
    },
    legend: { 
    // align: 'left',
    position: 'bottom',
    padding:10,
    labels:{
      usePointStyle : true,
      boxWidth: 6,
      filter: function(item, chart) {
        if(!item.text.includes('Previous Organic Search') && !item.text.includes('Previous Organic Social') && !item.text.includes('Previous Paid Search') && !item.text.includes('Previous Paid Social') && !item.text.includes('Previous Direct')){
          return true;
        }
      }
    }
  }
}
};

function UserbySession_goals(result) {   
  if (window.UserbySessionGoals) {
    window.UserbySessionGoals.destroy();
  }
  var UserbySessionBar_goals = document.getElementById('usersBySession_defaultChannel_goals').getContext('2d');
  window.UserbySessionGoals = new Chart(UserbySessionBar_goals, configSessionByUser_goals);

  configSessionByUser_goals.data.datasets[0].label = result['current_label'];
  configSessionByUser_goals.data.datasets[0].data = [result['organic_social_count'],result['organic_search_count'],result['paid_social_count'],result['paid_search_count'],result['direct_count']];

  if(result['compare_status'] == 1){
    configSessionByUser_goals.data.datasets.splice(1, 1);
    var newDataset = {
      borderWidth: 2,
      borderColor: '#90b3f6',
      backgroundColor: '#90b3f6',
      data: [result['previous_organic_social_count'],result['previous_organic_search_count'],result['previous_paid_social_count'],result['previous_paid_search_count'],result['previous_direct_count']],
      label: result['previous_label']
    };
    configSessionByUser_goals.data.datasets.push(newDataset);
  } else{
    configSessionByUser_goals.data.datasets.splice(1, 1);
  }

  window.UserbySessionGoals.update();
}

var configSessionByUser_goals = {
  type: 'horizontalBar',
  data: {
    labels: ["Organic Social", "Organic Search", "Paid Social", "Paid Search", "Direct"],
    datasets: [{
      label: '',
      borderWidth: 2,
      backgroundColor: '#3366ff',
      borderColor: '#3366ff',
      data: []
    }
    ]
  },
  options: {
    legend:false,
    maintainAspectRatio: false,
    tooltips: {
      intersect: false,
      mode: 'index',
      backgroundColor:'rgb(255, 255, 255)',
      titleFontColor:'#000',
      bodyFontStyle: 'normal',  
      titleMarginBottom : 10,
      titleFontStyle:'normal',
      bodySpacing: 5,
      yAlign: 'top',
      callbacks: {
        labelTextColor: function(context) { return '#000'; },
        title: function(tooltipItem, data) {
         if(tooltipItem.length == 2){ 
           return data.datasets[0].label + ' vs. '+ data.datasets[1].label;
         }else{
          return data.datasets[0].label;
        }
      },
      afterLabel: function(tooltipItem, data) {
        if(data.datasets.length == 2){
          if(tooltipItem.datasetIndex === 0){
            return data.labels[tooltipItem.index] + '     '+ data.datasets[0].data[tooltipItem.index]+ '  vs  '+ data.datasets[1].data[tooltipItem.index];  
          }
        }else{
          return data.labels[tooltipItem.index] + '     '+ data.datasets[0].data[tooltipItem.index];
        }
      },
      beforeLabel: function(tooltipItem, data) {
        if(tooltipItem.datasetIndex === 1){ 
          if(tooltipItem.datasetIndex === 0){
            return "Users ";
          }
        }else{
          return "Users ";
        }
      },
      label: function(tooltipItem) {}
    }
  },
  scales: {
    yAxes: [{
      gridLines: {
        display: false
      }
    }],
    xAxes: [{
      ticks: {
        maxRotation: 0,
        minRotation: 0,
        autoSkip: true,
        maxTicksLimit: 4
      }
    }],
  }
}
};

function ajax_goals_listing_acquisition(campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label){
 $.ajax({
  type:"GET",
  url:BASE_URL+"/ajax_goals_listing_traffic_acquisition",
  data:{campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
  success:function(result){
    $('.ga-compare-goals tbody').html(result);
    $('.ga-compare-goals-result').removeClass('ajax-loader');     
  }
});
}


$(document).on('click','.ga4_goals_cancel_btn',function(e){
  e.preventDefault();
  $("#ga4-goals-dateRange-popup").toggleClass("show");
});

$(document).on("click","#ga4_goals_range_section", function (e) {
  $("#ga4-goals-dateRange-popup").toggleClass("show");
  setTimeout(function(){
    var start_date = $('.ga4_goals_start_date').val();
    var end_date = $('.ga4_goals_end_date').val();
    var start = moment(start_date);
    var end = moment(end_date);
    var label = $('.ga4_goals_current_label').val();

    $('#ga4_goals_current_range').daterangepicker({
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
   }, ga4_goals_current_picker);
    ga4_goals_current_picker(start, end,label);
  },100);
});

function ga4_goals_current_picker(start, end,label = null) { 
  var new_start = start.format('YYYY-MM-DD');
  var new_end = end.format('YYYY-MM-DD');
  $('.ga4_goals_start_date').val(new_start);
  $('.ga4_goals_end_date').val(new_end);
  if(label !== null){
    $('.ga4_goals_current_label').val(label);
  }else{
    $('.ga4_goals_current_label').val('');
  }
  $('#ga4_goals_current_range p').html(new_start + ' - ' + new_end);
  var days = date_diff_indays(new_start, new_end);
  $('.ga4_goals_comparison_days').val(days);
  if($('#ga4_goals_comparison').val() === 'previous_period'){
    var prev_start_date = getdate(new_start, (days-1));
    var prev_end_date = getdate(new_start, -1);
    var prev_days = date_diff_indays(prev_start_date, prev_end_date);
    $('.ga4_goals_prev_start_date').val(prev_start_date);
    $('.ga4_goals_prev_end_date').val(prev_end_date);
    $('.ga4_goals_prev_comparison_days').val(prev_days);
    initialiseGoalsGa4PreviousCalendar(prev_start_date,prev_end_date);
  }else if($('#ga4_goals_comparison').val() === 'previous_year'){
    var prev_sd = createPreviousYear(new_start);
    var prev_ed = createPreviousYear(new_end);
    $('.ga4_goals_prev_start_date').val(prev_sd);
    $('.ga4_goals_prev_end_date').val(prev_ed);
    initialiseGoalsGa4PreviousCalendar(prev_sd,prev_ed);
  }
}

function initialiseGoalsGa4PreviousCalendar(prev_start,prev_end){
  var prev_sd = getdate(prev_start,0);
  var prev_ed = getdate(prev_end,0);
  $('.ga4_goals_prev_start_date').val(prev_sd);
  $('.ga4_goals_prev_end_date').val(prev_ed);
  var days = date_diff_indays(prev_sd, prev_ed);
  $('.ga4_goals_prev_comparison_days').val(days);
  $('#ga4_goals_previous_range p').html(prev_sd + ' - ' + prev_ed);
}

$(document).on('change','.ga4_goals_compare',function(e){
  e.preventDefault();
  var compare_status = $(this).is(':checked');
  if(compare_status === true){
    $('#ga4-goals-previous-section , #ga4-previous-section').removeClass('ga4-hidden-previous-datepicker');
    $('#ga4_goals_comparison').removeAttr('readonly','readonly');
    $('#ga4_goals_comparison').removeAttr('disabled','disabled');
  }else{
    $('#ga4-goals-previous-section , #ga4-previous-section').addClass('ga4-hidden-previous-datepicker');
    $('#ga4_goals_comparison').attr('readonly','readonly');
    $('#ga4_goals_comparison').attr('disabled','disabled');
  }
});

$(document).on('change','#ga4_goals_comparison',function(e){
  e.preventDefault();
  var new_start = $('.ga4_goals_start_date').val();
  var new_end = $('.ga4_goals_end_date').val();
  if($('#ga4_goals_comparison').val() === 'previous_period'){
    var days = date_diff_indays(new_start, new_end);
    var prev_start_date = getdate(new_start, (days-1));
    var prev_end_date = getdate(new_start, -1);
    $('.ga4_goals_prev_start_date').val(prev_start_date);
    $('.ga4_goals_prev_end_date').val(prev_end_date);
    $('.ga4_goals_comparison_days').val(days);

    initialiseGoalsGa4PreviousCalendar(prev_start_date,prev_end_date);
  }else if($('#ga4_goals_comparison').val() === 'previous_year'){
    var prev_sd = createPreviousYear(new_start);
    var prev_ed = createPreviousYear(new_end);
    var days = date_diff_indays(prev_sd, prev_ed);
    $('.ga4_goals_prev_start_date').val(prev_sd);
    $('.ga4_goals_prev_end_date').val(prev_ed);
    $('.ga4_goals_prev_comparison_days').val(days);
    initialiseGoalsGa4PreviousCalendar(prev_sd,prev_ed);
  } 
});


$(document).on('click','.ga4_goals_apply_btn',function(e){
    var selected_label = $('.ga4_goals_current_label').val();
    var current_start = $('.ga4_goals_start_date').val();
    var current_end = $('.ga4_goals_end_date').val();
    var previous_start = $('.ga4_goals_prev_start_date').val();
    var previous_end = $('.ga4_goals_prev_end_date').val();

    $('.ga4_current_label').val(selected_label);
    $('.ga4_start_date').val(current_start);
    $('.ga4_end_date').val(current_end);
    $('.ga4_prev_start_date').val(previous_start);
    $('.ga4_prev_end_date').val(previous_end);

    if($('.ga4_goals_compare').is(':checked') === true){
      var comparison = 1;
    }else{
      var comparison = 0;
    }    
    var comparison_selected = $('#ga4_goals_comparison').val();

    var campaignId = $('.campaignID').val();
    var userid = $('#user_id').val();
    var key = $('#encriptkey').val();
    if(key !== undefined){
      $('.ga4_datepicker_selection').val(2);
    }

    $('.ga4_comparison').val(comparison_selected);


$('.goals-usersBySession-defaultChannel-overTime, .goals-usersBySession-defaultChannel').addClass('ajax-loader');

var selection_type = $('.ga4_datepicker_selection').val();

  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_google_analytics_overview",
    data:{module:'ga4',campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
    dataType:'json',
    success:function(result){
      ajax_goals_traffic_acquisition(campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label);
      ajax_goals_listing_acquisition(campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label);
      $("#ga4-goals-dateRange-popup").removeClass("show");
    }
  });
});


$(document).on('click','.ga4_goals_applyBtn',function(e){
    var selected_label = $('.ga4_goals_current_label').val();
    var current_start = $('.ga4_goals_start_date').val();
    var current_end = $('.ga4_goals_end_date').val();
    var previous_start = $('.ga4_goals_prev_start_date').val();
    var previous_end = $('.ga4_goals_prev_end_date').val();

    $('.ga4_current_label').val(selected_label);
    $('.ga4_start_date').val(current_start);
    $('.ga4_end_date').val(current_end);
    $('.ga4_prev_start_date').val(previous_start);
    $('.ga4_prev_end_date').val(previous_end);

    if($('.ga4_goals_compare').is(':checked') === true){
      var comparison = 1;
      $('#ga4_comparison').removeAttr('readonly','readonly');
      $('#ga4_comparison').removeAttr('disabled','disabled');
      $('#ga4-previous-section , #ga4-goals-previous-section').removeClass('ga4-hidden-previous-datepicker');
      $('.ga4_compare').prop('checked',true);
    }else{
      var comparison = 0;
      $('#ga4_comparison').attr('readonly','readonly');
      $('#ga4_comparison').attr('disabled','disabled');
      $('#ga4-previous-section , #ga4-goals-previous-section').addClass('ga4-hidden-previous-datepicker');
      $('.ga4_compare').prop('checked',false);
    }    
    var comparison_selected = $('#ga4_goals_comparison').val();

    var campaignId = $('.campaignID').val();
    var userid = $('#user_id').val();
    var key = $('#encriptkey').val();
    if(key !== undefined){
      $('.ga4_datepicker_selection').val(2);
    }

   $('.ga4_comparison').val(comparison_selected);


$('#all-user-box, #new-user-box,.traffic-growth-graph-allUser-ga4, .traffic-growth-graph-newUser-ga4, .usersBySession-defaultChannel-overTime, .usersBySession-defaultChannel, .ga-compare-result').addClass('ajax-loader');

var selection_type = $('.ga4_datepicker_selection').val();

  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_google_analytics_overview",
    data:{module:'ga4',campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
    dataType:'json',
    success:function(result){
      ajax_acquisition_overview(campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label);
      ajax_traffic_acquisition(campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label);
      ajax_goals_listing_traffic_acquisition(campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label);
      $("#ga4-goals-dateRange-popup").removeClass("show");
    }
  });
});

$(document).on("click","#ga4_SeoGoals_range_section", function (e) {
  $("#ga4-SeoGoals-dateRange-popup").toggleClass("show");
  setTimeout(function(){
    var start_date = $('.ga4_SeoGoals_start_date').val();
    var end_date = $('.ga4_SeoGoals_end_date').val();
    var start = moment(start_date);
    var end = moment(end_date);
    var label = $('.ga4_SeoGoals_current_label').val();

    $('#ga4_SeoGoals_current_range').daterangepicker({
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
   }, ga4_SeoGoals_current_picker);
    ga4_SeoGoals_current_picker(start, end,label);
  },100);
});

function ga4_SeoGoals_current_picker(start, end,label = null) { 
  var new_start = start.format('YYYY-MM-DD');
  var new_end = end.format('YYYY-MM-DD');
  $('.ga4_SeoGoals_start_date').val(new_start);
  $('.ga4_SeoGoals_end_date').val(new_end);
  if(label !== null){
    $('.ga4_SeoGoals_current_label').val(label);
  }else{
    $('.ga4_SeoGoals_current_label').val('');
  }
  $('#ga4_SeoGoals_current_range p').html(new_start + ' - ' + new_end);
  var days = date_diff_indays(new_start, new_end);
  $('.ga4_SeoGoals_comparison_days').val(days);
  if($('#ga4_SeoGoals_comparison').val() === 'previous_period'){
    var prev_start_date = getdate(new_start, (days-1));
    var prev_end_date = getdate(new_start, -1);
    var prev_days = date_diff_indays(prev_start_date, prev_end_date);
    $('.ga4_SeoGoals_prev_start_date').val(prev_start_date);
    $('.ga4_SeoGoals_prev_end_date').val(prev_end_date);
    $('.ga4_SeoGoals_prev_comparison_days').val(prev_days);
    initialiseSeoGoalsGa4PreviousCalendar(prev_start_date,prev_end_date);
  }else if($('#ga4_SeoGoals_comparison').val() === 'previous_year'){
    var prev_sd = createPreviousYear(new_start);
    var prev_ed = createPreviousYear(new_end);
    $('.ga4_SeoGoals_prev_start_date').val(prev_sd);
    $('.ga4_SeoGoals_prev_end_date').val(prev_ed);
    initialiseSeoGoalsGa4PreviousCalendar(prev_sd,prev_ed);
  }
}

function initialiseSeoGoalsGa4PreviousCalendar(prev_start,prev_end){
  var prev_sd = getdate(prev_start,0);
  var prev_ed = getdate(prev_end,0);
  $('.ga4_SeoGoals_prev_start_date').val(prev_sd);
  $('.ga4_SeoGoals_prev_end_date').val(prev_ed);
  var days = date_diff_indays(prev_sd, prev_ed);
  $('.ga4_SeoGoals_prev_comparison_days').val(days);
  $('#ga4_SeoGoals_previous_range p').html(prev_sd + ' - ' + prev_ed);
}

$(document).on('change','.ga4_SeoGoals_compare',function(e){
  e.preventDefault();
  var compare_status = $(this).is(':checked');
  if(compare_status === true){
    $('#SeoGoals-ga4-previous-section').removeClass('ga4-hidden-previous-datepicker');
    $('#ga4_SeoGoals_comparison').removeAttr('readonly','readonly');
    $('#ga4_SeoGoals_comparison').removeAttr('disabled','disabled');
  }else{
    $('#SeoGoals-ga4-previous-section').addClass('ga4-hidden-previous-datepicker');
    $('#ga4_SeoGoals_comparison').attr('readonly','readonly');
    $('#ga4_SeoGoals_comparison').attr('disabled','disabled');
  }
});

$(document).on('click','.ga4_SeoGoals_apply_btn',function(e){
    var selected_label = $('.ga4_SeoGoals_current_label').val();
    var current_start = $('.ga4_SeoGoals_start_date').val();
    var current_end = $('.ga4_SeoGoals_end_date').val();
    var previous_start = $('.ga4_SeoGoals_prev_start_date').val();
    var previous_end = $('.ga4_SeoGoals_prev_end_date').val();

    $('.ga4_SeoGoals_current_label').val(selected_label);
    $('.ga4_SeoGoals_start_date').val(current_start);
    $('.ga4_SeoGoals_end_date').val(current_end);
    $('.ga4_SeoGoals_prev_start_date').val(previous_start);
    $('.ga4_SeoGoals_prev_end_date').val(previous_end);

    if($('.ga4_SeoGoals_compare').is(':checked') === true){
      var comparison = 1;
    }else{
      var comparison = 0;
    }    
    var comparison_selected = $('#ga4_SeoGoals_comparison').val();

    var campaignId = $('.campaignID').val();
    var userid = $('#user_id').val();
    var key = $('#encriptkey').val();
    if(key !== undefined){
      $('.ga4_datepicker_selection').val(2);
    }

    $('.ga4_comparison').val(comparison_selected);


    $('.goals-usersBySession-defaultChannel-overTime, .goals-usersBySession-defaultChannel,.ga-compare-goals-result').addClass('ajax-loader');

    var selection_type = $('.ga4_datepicker_selection').val();

  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_google_analytics_overview",
    data:{module:'ga4',campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
    dataType:'json',
    success:function(result){
      ajax_SeoGoals_traffic_acquisition(campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label);
      ajax_SeoGoals_listing_acquisition(campaignId,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label);
      $("#ga4-SeoGoals-dateRange-popup").removeClass("show");
    }
  });
});

function ajax_SeoGoals_traffic_acquisition(campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label){
  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_traffic_acquisition",
    data:{campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
    dataType:'json',
    success:function(result){
      $('.ga4Goals-ga4-range').html(result['display_range']);
      UserbySession_overTime_goals(result);
      $('.goals-usersBySession-defaultChannel-overTime').removeClass('ajax-loader');

      UserbySession_goals(result);
      $('.goals-usersBySession-defaultChannel').removeClass('ajax-loader');
    }
  });
}


function ajax_SeoGoals_listing_acquisition(campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label){
 $.ajax({
  type:"GET",
  url:BASE_URL+"/ajax_goals_listing_traffic_acquisition",
  data:{campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
  success:function(result){
    $('.ga-compare-goals tbody').html(result);
    $('.ga-compare-goals-result').removeClass('ajax-loader');     
  }
});
}

$(document).on('click','.ga4_SeoGoals_cancel_btn',function(e){
  e.preventDefault();
  $("#ga4-SeoGoals-dateRange-popup").toggleClass("show");
});