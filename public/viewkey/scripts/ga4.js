var BASE_URL = $('.base_url').val();

function ajaxAcquisitionOverview(campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label){
  $.ajax({
   type:"GET",
   url:BASE_URL+"/ajax_acquisition_overview",
   data:{campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
   dataType:'json',
   success:function(result){
    acquisitionDisplayStats(result);

    acquisitionOverview(result);
    $('.ott-allUser-ga4-pdf').removeClass('ajax-loader');

    // acquisitionNewUser(result);
    // $('.traffic-growth-graph-newUser-ga4').removeClass('ajax-loader');
  }
});
}

function acquisitionDisplayStats(result){
  $('.ga4-range').html(result['display_range']);
  $('.active-users-pdf').html(result['current_activeUsers_count']);
  $('.new-users-pdf').html(result['current_newUsers_count']);
  $('.active-users-comparison ,.new-users-comparison').removeClass('green , red');

  if(result['comparison'] == 1 || result['comparison'] == '1'){
    if(result['previous_percentage'] > 0){
      var previous_percentage = result['previous_percentage'] +'%';
      var active_user_arrow = 'ion-arrow-up-a'; var active_user_class = 'green';
    }else if(result['previous_percentage'] < 0){
      var total_activeUserPercentage = result['previous_percentage'].toString();
      var previous_percentage = total_activeUserPercentage.replace('-', '') +'%';
      var active_user_arrow = 'ion-arrow-down-a'; var active_user_class = 'red';
    }else if(result['previous_percentage'] == 0){
     var previous_percentage = '-';
      var active_user_class = ''; var active_user_arrow = '';
    }else{
      var previous_percentage = result['previous_percentage'] +'%';
      var active_user_class = ''; var active_user_arrow = '';
    }

    $('.active-users-comparison-pdf').addClass(active_user_class);
    $('.active-users-comparison-pdf').html('<i class="icon '+ active_user_arrow +'"></i><span class="active-users-comparison-value">'+ previous_percentage +' </span>');

    if(result['previous_newUser_percentage'] > 0){
      var previous_newUser_percentage = result['previous_newUser_percentage'] +'%';
      var new_user_arrow = 'ion-arrow-up-a'; var new_user_class = 'green';
    }else if(result['previous_newUser_percentage'] < 0){
      var total_newUserPercentage = result['previous_newUser_percentage'].toString();
      var previous_newUser_percentage = total_newUserPercentage.replace('-', '') +'%';
      var new_user_arrow = 'ion-arrow-down-a'; var new_user_class = 'red';
    }else if(result['previous_newUser_percentage'] == 0){
      var previous_newUser_percentage = '-';
      var new_user_arrow = ''; var new_user_class = '';
    }else{
      var previous_newUser_percentage = result['previous_newUser_percentage'] +'%';
      var new_user_arrow = ''; var new_user_class = '';
    }

    $('.new-users-comparison-pdf').addClass(new_user_class);
    $('.new-users-comparison-pdf').html('<i class="icon '+ new_user_arrow +'"></i><span class="new-users-comparison-value">'+ previous_newUser_percentage +' </span>');
  }

  $('.organic-user-box .single').removeClass('ajax-loader');
}


function acquisitionOverview(result) {   
  if (window.trafficGa4) {
    window.trafficGa4.destroy();
  }
  var trafficGrowthGa4 = document.getElementById('ott-ga4-pdf').getContext('2d');
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
      borderDash: [5,2],
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
              return ' Users:     '+ data.datasets[0].data[tooltipItem.index]+ '  vs   '+ data.datasets[1].data[tooltipItem.index];
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

function ajaxTrafficAcquisition(campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label){
  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_traffic_acquisition",
    data:{campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label},
    dataType:'json',
    success:function(result){
      $('.ga4-range').html(result['display_range']);
      UserbySession_overTime(result);
      $('.usersBySession-defaultChannel-overTime-pdf').removeClass('ajax-loader');

      UserbySession(result);
      $('.usersBySession-defaultChannel-pdf').removeClass('ajax-loader');
    }
  });
}

function UserbySession_overTime(result) {   
  if (window.user_by_session_overTime) {
    window.user_by_session_overTime.destroy();
  }
  var UserbySessionOverTime = document.getElementById('usersBySession_defaultChannel_overTime_pdf').getContext('2d');
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
  var UserbySessionBar = document.getElementById('usersBySession_defaultChannel_pdf').getContext('2d');
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

function ajaxGoalsListingTrafficAcquisition(campaign_id,key,current_start,current_end,previous_start,previous_end,comparison,comparison_selected,selected_label){
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

function ga4_overview_allUser_Chart(campaign_id){
  $.ajax({
    url:BASE_URL +'/ajax_ga4_au_chart',
    dataType:'json',
    data:{campaign_id},
    success:function(response){
      if(window.line_au_allUsers_chart){
        window.line_au_allUsers_chart.destroy();
      }

      var ctxGa4_AllUser = document.getElementById('canvas-ga4-allUser').getContext('2d');
      window.line_au_allUsers_chart = new Chart(ctxGa4_AllUser, config_allUser_ga4);
      var gradient = gradientColor(ctxGa4_AllUser);

      config_allUser_ga4.data.labels =  response['labels'];
      config_allUser_ga4.data.datasets[0].data = response['active_users'];
      config_allUser_ga4.data.datasets[0].backgroundColor = gradient;
      window.line_au_allUsers_chart.update();

      $('.au-graph').removeClass('ajax-loader');
    }
  });
}

var config_allUser_ga4 = {
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

function ga4_overview_allUser_stats(campaign_id){
  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_alluser_statistics",
    data:{campaign_id},
    dataType:'json',
    success:function(result){
      if(result['total_users'] != '??'){
        var total_users_string = result['total_users'].toString();
        total_users_string = total_users_string.replace(/,/g, "");
        if(total_users_string > 0 ){
          $('.allUsers-count').html(result['current_active_users']);
          $('.allUsers_growth').addClass("green");
          $('.allUsers_growth').html('<img src="/public/vendor/internal-pages/images/up-stats-arrow.png" alt="up-stats-arrow"><span>'+result['total_users']+'% </span>Since Start');
        }else if(total_users_string < 0 ){
          var replace_total_users = total_users_string.replace('-', '');
          $('.allUsers-count').html(result['current_active_users']);
          $('.allUsers_growth').addClass("red");
          $('.allUsers_growth').html('<img src="/public/vendor/internal-pages/images/down-stats-arrow.png" alt="down-stats-arrow"><span>'+replace_total_users+'% </span>Since Start');
        }else{
          $('.allUsers-count').html(result['current_active_users']);
        }
      }else{
        $('.allUsers-count').html(result['current_active_users']);
      }

      $('.au-total').removeClass("ajax-loader");
    }
  });
}

function ga4_overview_conversions_stats(campaign_id){
  $.ajax({
    type:"GET",
    url:BASE_URL+"/ajax_conversions_statstics",
    data:{campaign_id},
    dataType:'json',
    success:function(result){
      if(result['total_conversions'] != '??'){
        var total_conversions_string = result['total_conversions'].toString();
        total_conversions_string = total_conversions_string.replace(/,/g, "");
        if(total_conversions_string > 0 ){
          $('.Google-analytics4-conversions').html(result['current_conversions']);
          $('.conversions-result').addClass("green");
          $('.conversions-result').html('<img src="/public/vendor/internal-pages/images/up-stats-arrow.png" alt="up-stats-arrow"><span>'+result['total_conversions']+'% </span>Since Start');
        }else if(total_conversions_string < 0 ){
          var replace_total_conversions = total_conversions_string.replace('-', '');
          $('.Google-analytics4-conversions').html(result['current_conversions']);
          $('.conversions-result').addClass("red");
          $('.conversions-result').html('<img src="/public/vendor/internal-pages/images/down-stats-arrow.png" alt="down-stats-arrow"><span>'+replace_total_conversions+'% </span>Since Start');
        }else{
          $('.Google-analytics4-conversions').html(result['current_conversions']);
        }
      }else{
        $('.Google-analytics4-conversions').html(result['current_conversions']);
      }

      $('.ga4-conversions').removeClass("ajax-loader");
    }
  });
}

function ga4_overview_conversions_Chart(campaign_id){
  $.ajax({
    url:BASE_URL +'/ajax_ga4_conversions_chart',
    dataType:'json',
    data:{campaign_id},
    success:function(response){
      if(window.line_nu_allUsers_chart){
        window.line_nu_allUsers_chart.destroy();
      }

      var ctxGa4_NewUser = document.getElementById('canvas-ga4-conversions').getContext('2d');
      window.line_nu_allUsers_chart = new Chart(ctxGa4_NewUser, config_newUser_ga4);
      var gradient = gradientColor(ctxGa4_NewUser);

      config_newUser_ga4.data.labels =  response['labels'];
      config_newUser_ga4.data.datasets[0].data = response['conversions'];
      config_newUser_ga4.data.datasets[0].backgroundColor = gradient;
      window.line_nu_allUsers_chart.update();

      $('.ga4-overview-conversions').removeClass('ajax-loader');
    }
  });
}

var config_newUser_ga4 = {
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