var BASE_URL = $('.base_url').val();

/*keyword filter*/
$(document).on("click", '#EditKeywordsFilters, #EditKeywordsFiltersClose', function() {
  $("body").find(".edit-keywordsFilters-popup").toggleClass("open");
});

$(document).on("click", function(e) {
  if ($(e.target).is(".edit-keywordsFilters-popup .edit-keywords-popup-inner, #EditKeywordsFilters, .edit-keywordsFilters-popup .edit-keywords-popup-inner *") === false) {
    $(".edit-keywordsFilters-popup").removeClass("open");
  }
});

$(document).on("click", '#EditKeywordsFiltersRankings, #EditKeywordsFiltersRankingsClose', function() {
  $("body").find(".edit-keywordsFilters-popup-rankings").toggleClass("open");
});

$(document).on("click", function(e) {
  if ($(e.target).is(".edit-keywordsFilters-popup-rankings .edit-keywords-popup-inner, #EditKeywordsFiltersRankings, .edit-keywordsFilters-popup-rankings .edit-keywords-popup-inner *") === false) {
    $(".edit-keywordsFilters-popup-rankings").removeClass("open");
  }
});

var KeywordconfigChart = {
  type: 'line',
  data: {
    datasets: [
    {
      label: '',
      yAxisID: 'lineId',
      backgroundColor: color(window.chartColors.orange).alpha(1.5).rgbString(),
      borderColor: window.chartColors.orange,
      data:[],
      pointRadius: 4,
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
            enabled: true,
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
          labelString: 'Rank'
        },
        ticks: {
          beginAtZero: false,
          reverse:true,
          suggestedMin: 0,
          // suggestedMax: 100,
          maxTicksLimit:4
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


function LiveKeywordStats(campaign_id) {
  $.ajax({
    url: BASE_URL + '/ajax_live_keyword_stats',
    type: 'GET',
    data: {
      campaign_id
    },
    dataType: 'json',
    success: function(response) {
      var since_three = since_ten = since_twenty = since_thirty = since_hundred = '';
      if (response['since_three'] > 0) {
        var since_three = '<i class="icon ion-arrow-up-a"></i>';
      } else if (response['since_three'] < 0) {
        var since_three = '<i class="icon ion-arrow-down-a"></i>';
      }

      if (response['since_ten'] > 0) {
        var since_ten = '<i class="icon ion-arrow-up-a"></i>';
      } else if (response['since_ten'] < 0) {
        var since_ten = '<i class="icon ion-arrow-down-a"></i>';
      }

      if (response['since_twenty'] > 0) {
        var since_twenty = '<i class="icon ion-arrow-up-a"></i>';
      } else if (response['since_twenty'] < 0) {
        var since_twenty = '<i class="icon ion-arrow-down-a"></i>';
      }

      if (response['since_thirty'] > 0) {
        var since_thirty = '<i class="icon ion-arrow-up-a"></i>';
      } else if (response['since_thirty'] < 0) {
        var since_thirty = '<i class="icon ion-arrow-down-a"></i>';
      }
      if (response['since_hundred'] > 0) {
        var since_hundred = '<i class="icon ion-arrow-up-a"></i>';
      } else if (response['since_hundred'] < 0) {
        var since_hundred = '<i class="icon ion-arrow-down-a"></i>';
      }
      $('.keywords_up').html(response['lifetime']);
      $('.top-three').html(response['three'] + '<small>/' + response['total']+'</small>');
      $('.active_keywords_count').html(response['total'] +' active keywords');

      if(response['since_three'] > 0){
        $('.top-three').addClass('green');
        $('.top-three').removeClass('red');
      }else if(response['since_three'] < 0){
        $('.top-three').addClass('red');
        $('.top-three').removeClass('green');
      }else{
        $('.top-three').removeClass('red');
        $('.top-three').removeClass('green');
      }
      $('.top-three-since').html(since_three + '<strong>' + response['since_three'] + '</strong> since start');
      $('.top-ten').html(response['ten'] + '<small>/' + response['total']+'</small>');

      if(response['since_ten'] > 0){
        $('.top-ten').addClass('green');
        $('.top-ten').removeClass('red');
      }else if(response['since_ten'] < 0){
        $('.top-ten').addClass('red');
        $('.top-ten').removeClass('green');
      }else{
        $('.top-ten').removeClass('red');
        $('.top-ten').removeClass('green');
      }
      $('.top-ten-since').html(since_ten + '<strong>' + response['since_ten'] + '</strong> since start');
      $('.top-twenty').html(response['twenty']+ '<small>/' + response['total']+'</small>');
      if(response['since_twenty'] > 0){
        $('.top-twenty').addClass('green');
        $('.top-twenty').removeClass('red');
      }else if(response['since_twenty'] < 0){
        $('.top-twenty').addClass('red');
        $('.top-twenty').removeClass('green');
      }else{
        $('.top-twenty').removeClass('red');
        $('.top-twenty').removeClass('green');
      }
      $('.top-twenty-since').html(since_twenty + '<strong>' + response['since_twenty'] + '</strong> since start');
      $('.top-thirty').html(response['thirty']+ '<small>/' + response['total']+'</small>');
      if(response['since_thirty'] > 0){
        $('.top-thirty').addClass('green');
        $('.top-thirty').removeClass('red');
      }else if(response['since_thirty'] < 0){
        $('.top-thirty').addClass('red');
        $('.top-thirty').removeClass('green');
      }else{
        $('.top-thirty').removeClass('red');
        $('.top-thirty').removeClass('green');
      }
      $('.top-thirty-since').html(since_thirty + '<strong>' + response['since_thirty'] + '</strong> since start');
      $('.top-hundred').html(response['hundred']+ '<small>/' + response['total']+'</small>');
      if(response['since_hundred'] > 0){
        $('.top-hundred').addClass('green');
        $('.top-hundred').removeClass('red');
      }else if(response['since_hundred'] < 0){
        $('.top-hundred').addClass('red');
        $('.top-hundred').removeClass('green');
      }else{
        $('.top-hundred').removeClass('red');
        $('.top-hundred').removeClass('green');
      }
      $('.top-hundred-since').html(since_hundred + '<strong>' + response['since_hundred'] + '</strong> since start');
      $('.keywords_up').removeClass('ajax-loader');
      $('.top-three').removeClass('ajax-loader');
      $('.top-ten').removeClass('ajax-loader');
      $('.top-twenty').removeClass('ajax-loader');
      $('.top-thirty').removeClass('ajax-loader');
      $('.top-hundred').removeClass('ajax-loader');

      $('.top-all-since').removeClass('ajax-loader');
      $('.top-three-since').removeClass('ajax-loader');
      $('.top-ten-since').removeClass('ajax-loader');
      $('.top-twenty-since').removeClass('ajax-loader');
      $('.top-thirty-since').removeClass('ajax-loader');
      $('.top-hundred-since').removeClass('ajax-loader');

    }
  });
}


function LiveKeywordTrackingList(campaign_id, column_name, order_type, limit, page, query,key,tag_id,tracking_type,selected_type ='') {
 $('.project-search').removeClass('ajax-loader');
 if($('.sideDashboardView.active a').attr("href") == '#rankings'){
  $.ajax({
    url: BASE_URL + '/ajax_live_keyword_list',
    type: 'GET',
    data: {campaign_id, column_name, order_type, limit, page, query,key,tag_id,tracking_type,selected_type},
    success: function(response) {
      $('#rankings #LiveKeywordTable_data tbody').html('');
      $('#rankings #LiveKeywordTable_data tbody').html(response);
      $('#rankings #LiveKeywordTable_data tr th').removeClass('ajax-loader');
      $('#rankings #LiveKeywordTable_data tr td').removeClass('ajax-loader');
      $('#rankings #refresh-liveKeyword-search').css('display','none');
    }
  });

  $.ajax({
    url: BASE_URL + '/ajax_live_keyword_pagination',
    type: 'GET',
    data: {campaign_id, column_name, order_type, limit, page, query,tag_id,tracking_type,selected_type},
    success: function(response) {
      $('#rankings .liveKeywords-profile-foot').html('');
      $('#rankings .liveKeywords-profile-foot').html(response);
      $('#rankings .LiveKeywords').removeClass('ajax-loader');
      $('#rankings .project-entries').removeClass('ajax-loader');
    }
  });
}else{
  $.ajax({
    url: BASE_URL + '/ajax_live_keyword_list',
    type: 'GET',
    data: {campaign_id, column_name, order_type, limit, page, query,key,tag_id,tracking_type,selected_type},
    success: function(response) {
      $('#LiveKeywordTable_data tbody').html('');
      $('#LiveKeywordTable_data tbody').html(response);
      $('#LiveKeywordTable_data tr th').removeClass('ajax-loader');
      $('#LiveKeywordTable_data tr td').removeClass('ajax-loader');
      $('#refresh-liveKeyword-search').css('display','none');
    }
  });


  $.ajax({
    url: BASE_URL + '/ajax_live_keyword_pagination',
    type: 'GET',
    data: {campaign_id, column_name, order_type, limit, page, query,tag_id,tracking_type,selected_type},
    success: function(response) {
      $('.liveKeywords-profile-foot').html('');
      $('.liveKeywords-profile-foot').html(response);
      $('.LiveKeywords').removeClass('ajax-loader');
      $('.project-entries').removeClass('ajax-loader');
    }
  });
}
}

$(document).on('click', '.LiveKeywords a', function(e) {
  e.preventDefault();

  if($('.sideDashboardView.active a').attr("href") == '#rankings'){
    $('li').removeClass('active');
    $(this).parent().addClass('active');
    $('#rankings #LiveKeywordTable_data tr td').addClass('ajax-loader');
    $('#rankings .LiveKeywords ').addClass('ajax-loader');

    var column_name = $('#rankings #hidden_column_name_liveKeyword').val();
    var order_type = $('#rankings #hidden_sort_type_liveKeyword').val();
    var limit = $('#rankings #limit_liveKeyword').val();
    var page = $(this).attr('href').split('page=')[1];
    var query = $('#rankings .live-keyword-search').val();
    var key = $('#encriptkey').val();
    //var tracking_type = $('#rankings #tracking_type').val();
    var tag_id = $('#rankings #tag_id_value').val();
    $('#rankings #hidden_page_liveKeyword').val(page);
    var tracking_type = $('#rankings #tracking_type_val').val();
    var selected_type = $('#rankings #selected_type_val').val();
  }else{
   $('li').removeClass('active');
   $(this).parent().addClass('active');
   $('#LiveKeywordTable_data tr td').addClass('ajax-loader');
   $('.LiveKeywords ').addClass('ajax-loader');
   var column_name = $('#hidden_column_name_liveKeyword').val();
   var order_type = $('#hidden_sort_type_liveKeyword').val();
   var limit = $('#limit_liveKeyword').val();
   var page = $(this).attr('href').split('page=')[1];
   var query = $('.live-keyword-search').val();
   var key = $('#encriptkey').val();
  //  var tracking_type = $('#tracking_type').val();
  var tag_id = $('#tag_id_value').val();
  $('#hidden_page_liveKeyword').val(page);

  var tracking_type = $('#tracking_type_val').val();
  var selected_type = $('#selected_type_val').val();
}

LiveKeywordTrackingList($('.campaignID').val(), column_name, order_type, limit, page, query,key,tag_id,tracking_type,selected_type);
});

$(document).on('click','.liveKeyword_sorting',function(e){
  e.preventDefault();
  var column_name = $(this).attr('data-column_name');
  var order_type = $(this).attr('data-sorting_type');


  $('#LiveKeywordTable_data tr td').addClass('ajax-loader');
  /*$('.project-entries').addClass('ajax-loader');*/
  $('.LiveKeywords ').addClass('ajax-loader');

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

 $('#hidden_column_name_liveKeyword').val(column_name);
 $('#hidden_sort_type_liveKeyword').val(reverse_order);
 var limit = $('#limit_liveKeyword').val();
 var page = $('.hidden_page_liveKeyword').val();
 var query = $('.live-keyword-search').val();
 var key = $('#encriptkey').val();
 var tracking_type = $('#tracking_type_val').val();
 var selected_type = $('#selected_type_val').val();

 LiveKeywordTrackingList($('.campaignID').val(), column_name, reverse_order, limit, page, query,key,$('#tag_id_value').val(),tracking_type,selected_type);
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

$(document).on('keyup','.live-keyword-search',function (e) {
  $('#refresh-liveKeyword-search').css('display','block');
  if($('.sideDashboardView.active a').attr("href") == '#rankings'){
    $('#rankings #refresh-liveKeyword-search').css('display','block');
  }
});

$(document).on('keyup','.live-keyword-search',delay(function (e) {
  if($(this).val() != '' || $(this).val() != null){
    $('.liveKeyword-search-clear').css('display','block');
    live_tracking_search();
    if($('.sideDashboardView.active a').attr("href") == '#rankings'){
      $('#rankings .liveKeyword-search-clear').css('display','block');
    }
  }
  if($(this).val() == '' || $(this).val() == null){
   $('.liveKeyword-search-clear').css('display','none');
   if($('.sideDashboardView.active a').attr("href") == '#rankings'){
    $('#rankings .liveKeyword-search-clear').css('display','none');
  }
}
}, 1500));

function live_tracking_search(){
  var key = $('#encriptkey').val();
  if($('.sideDashboardView.active a').attr("href") == '#rankings'){
    $('#rankings #LiveKeywordTable_data tr td').addClass('ajax-loader');
    $('#rankings .LiveKeywords ').addClass('ajax-loader');
    var query =  $('#rankings .live-keyword-search').val();
    var column_name =  $('#rankings #hidden_column_name_liveKeyword').val();
    var order_type = $('#rankings #hidden_sort_type_liveKeyword').val();
    var limit = $('#rankings #limit_liveKeyword').val();
    var page = 1;
    var tracking_type = $('#rankings #tracking_type_val').val();
    var tag_id = $('#rankings #tag_id_value').val();
    var selected_type = $('#rankings #selected_type_val').val();


  }else{
    $('#LiveKeywordTable_data tr td').addClass('ajax-loader');
    $('.LiveKeywords ').addClass('ajax-loader');
    var query =  $('.live-keyword-search').val();
    var column_name =  $('#hidden_column_name_liveKeyword').val();
    var order_type = $('#hidden_sort_type_liveKeyword').val();
    var limit = $('#limit_liveKeyword').val();
    var page = 1;
    var tag_id = $('#tag_id_value').val();
    // var tracking_type = $('#tracking_type').val();
    var tracking_type = $('#tracking_type_val').val();
    var selected_type = $('#selected_type_val').val();


  }

  LiveKeywordTrackingList($('.campaignID').val(), column_name, order_type, limit, page, query,key,tag_id,tracking_type,selected_type);
}

$(document).on('click','.LiveKeywordClear',function(e){
  e.preventDefault();
  $('.live-keyword-search').val('');
  if($('.live-keyword-search').val() == '' || $('.live-keyword-search').val() == null){
    $('.liveKeyword-search-clear').css('display','none');
    live_tracking_search();
  }
}); 



$(document).on('change','#live-keyword-limit',function(e){
  e.preventDefault();
  var key = $('#encriptkey').val();
  var limit = $(this).val();
  if($('.sideDashboardView.active a').attr("href") == '#rankings'){
    $('#rankings #limit_liveKeyword').val(limit);
    $('#rankings #LiveKeywordTable_data tr td').addClass('ajax-loader');
    $('#rankings .LiveKeywords').addClass('ajax-loader');
    var tracking_type = $('#rankings #tracking_type').val();
    var limit = $('#rankings #live-keyword-limit').val();
    var page = 1;
    var query = $('#rankings .live-keyword-search').val();
    var column_name =  $('#rankings #hidden_column_name_liveKeyword').val();
    var order_type = $('#rankings #hidden_sort_type_liveKeyword').val();
    var tag_id = $('#rankings #tag_id_value').val();
  }else{
    $('#limit_liveKeyword').val(limit);
    $('#LiveKeywordTable_data tr td').addClass('ajax-loader');
    $('.LiveKeywords').addClass('ajax-loader');
    // var tracking_type = $('#tracking_type').val();
    var limit = $('#live-keyword-limit').val();
    var page = 1;
    var query = $('.live-keyword-search').val();
    var column_name =  $('#hidden_column_name_liveKeyword').val();
    var order_type = $('#hidden_sort_type_liveKeyword').val();
    var tag_id = $('#tag_id_value').val();
    var tracking_type = $('#tracking_type_val').val();
    var selected_type = $('#selected_type_val').val();
  }

  LiveKeywordTrackingList($('.campaignID').val(), column_name, order_type, limit, page, query,key,tag_id,tracking_type,selected_type);
});



$(document).on('click','.mark_favorite',function(e){
  e.preventDefault();
  var keyword_id = $(this).attr("data-id");
  var request_id = $(this).attr("data-request_id");
  $.ajax({
    type: "POST",
    url: BASE_URL + "/ajax_mark_live_keyword_favorite",
    data: {keyword_id, request_id,_token:$('meta[name="csrf-token"]').attr('content')},
    dataType: 'json',
    success: function(result) {
      if(result['status'] == 1){
        $("#LiveKeywordTable_data").load(location.href + " #LiveKeywordTable_data");
        $("#LiveKeywordTable_foot").load(location.href + " #LiveKeywordTable_foot");
        Command: toastr["success"](result['message']);
      }else {
        Command: toastr["warning"](result['message']);
      }
    }
  });
});




$(document).on("click", ".chart-icon", function(e){
  e.preventDefault();
  var keyword_id =  $(this).data('id');
  var request_id =  $(this).data('request_id');
  var row_id =  $(this).data('row_id');
  var duration =  '-60 day';

  $('.icons-list').removeClass('active');
  $('#LiveKeywordTable_data').find('tr').removeClass('show');

  $(this).parent().toggleClass("active");
  $(this).find(".fa").toggleClass("fa-area-chart");
  $(this).find(".fa").toggleClass("fa-times");

  drawChart(keyword_id,request_id, duration,row_id);
});

function drawChart(keyword_id, request_id, duration,row_id ) {
  $.ajax({
    type: "POST",
    url: BASE_URL + "/ajax_live_keyword_chart_data",
    data: { keyword_id,request_id,duration,_token:$('meta[name="csrf-token"]').attr('content')},
    success: function(result) {

     if (window.myLineKeywordChart) {
      window.myLineKeywordChart.destroy();
    }

    var ctxs = document.getElementById('livekeywordchart'+row_id).getContext('2d');
    window.myLineKeywordChart = new Chart(ctxs, KeywordconfigChart);

    KeywordconfigChart.data.datasets[0].data = result['keyword'];
    window.myLineKeywordChart.update();
  }
});
}

$(document).on('click','.liveKeywordBtns',function(e){
  e.preventDefault();
  var duration = $(this).attr('data-value');
  var keyword_id = $(this).attr('data-id');
  var request_id = $(this).attr('data-request_id');
  var row_id = $(this).attr('data-row_id');

  $('.liveKeywordBtns').removeClass('active');
  $(this).addClass('active');

  drawChart(keyword_id,request_id, duration,row_id);
});


$(document).on('click',".editPosition",function(event){
  if($(this).children("input").length > 0)
    return false;

  var tdObj = $(this);
  var preText = tdObj.html();

  var inputObj = $("<input type='text'/>");
  tdObj.html("");

  inputObj.width(tdObj.width())
  .height(tdObj.height())
  .css({border:"0px",fontSize:"17px"})
  .val(preText.trim())
  .appendTo(tdObj)
  .trigger("focus")
  .trigger("select");


  var data_id = $(this).attr('data-id');

  inputObj.keyup(function(event){
      if(13 == event.which) { // press ENTER-key
        var text = $(this).val();
        if(preText != text){
          updateStartRankPosition(text,data_id);
          tdObj.html(text);
        }
      }
      else if(27 == event.which) {  // press ESC-key
        tdObj.html(preText);
      }
    });

  inputObj.click(function(){
    return false;
  });
});

function updateStartRankPosition(updated_val,request_id){

  $.ajax({
    type: "POST",
    url: BASE_URL + "/ajax_update_keyword_startRanking",
    data: {start_ranking: updated_val, request_id,_token: $('meta[name="csrf-token"]').attr('content')},
    success: function(result) {
      $("#LiveKeywordTable_data").load(location.href + " #LiveKeywordTable_data");
      $("#LiveKeywordTable_foot").load(location.href + " #LiveKeywordTable_foot");
      if(result['status'] == '1'){
        Command: toastr["success"](result['message']);
      }else{
        Command: toastr["warning"](result['message']);
      }
    }
  });
}




// $('#LiveKeywordTable_data #selectAllKeywords').click(function() {
//   if ($("#LiveKeywordTable_data #selectAllKeywords").is(':checked')) {
//     $('#LiveKeywordTable_data input[type=checkbox]').prop('checked', true);
//   } else {
//     $('#LiveKeywordTable_data input[type=checkbox]').prop('checked', false);
//   }
// });

$('#LiveKeywordTable_data input[type=checkbox]').on('change',function(){
  if($('.selected_keywords:checked').length == $('.selected_keywords').length){
   $('#selectAllKeywords').prop('checked',true);
 }else{
   $('#selectAllKeywords').prop('checked',false);
 }
});

$('#LiveKeywordTable_data').on('click', 'tbody td, thead th:first-child', function(e){
  $(this).parent().find('input[type="checkbox"]').trigger('click');
});

$('thead #selectAllKeywords').on('click', function(e){
  if(this.checked){
   $('#LiveKeywordTable_data tbody input[type="checkbox"]:not(:checked)').trigger('click');
 } else {
   $('#LiveKeywordTable_data tbody input[type="checkbox"]:checked').trigger('click');
 }

      // Prevent click event from propagating to parent
      e.stopPropagation();
    });


 // Handle click on checkbox
 $('#LiveKeywordTable_data tbody').on('click', 'input[type="checkbox"]', function(e){
   var rows_selected = [];
   var $row = $(this).closest('tr');

      // Get row data
      var data = $row.data();

      // Get row ID
      var rowId = data[0];

      // Determine whether row ID is in the list of selected row IDs 
      var index = $.inArray(rowId, rows_selected);

      // If checkbox is checked and row ID is not in list of selected row IDs
      if(this.checked && index === -1){
       rows_selected.push(rowId);

      // Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
    } else if (!this.checked && index !== -1){
     rows_selected.splice(index, 1);
   }

   if(this.checked){
     $row.addClass('selected');
   } else {
     $row.removeClass('selected');
   }

      // Prevent click event from propagating to parent
      e.stopPropagation();
    });

 $('#delete_multiple_keywords').on('click', function(e){
  e.preventDefault();

  var checked = [];
  $("input[name='selected_keywords[]']:checked").each(function () {
    checked.push(parseInt($(this).val()));
  });

  if(checked.length == 0){
    Command: toastr["error"]('Select Keyword(s) to delete!');
    return false;
  }

  if(!confirm("Are you sure you want to delete this?")){
    return false;
  }


  if(checked.length > 0 ){
    $.ajax({
      type: "POST",
      url: BASE_URL+ "/ajax_remove_multiple_keywords",
      data: {selected_ids:checked, request_id: $('.campaignID').val(),_token: $('meta[name="csrf-token"]').attr('content')},
      dataType: 'json',
      success: function(result) {
        if (result['status'] == '1') {
          $("#LiveKeywordTable_data").load(location.href + " #LiveKeywordTable_data");
          $("#LiveKeywordTable_foot").load(location.href + " #LiveKeywordTable_foot");

          Command: toastr["success"](result['message']);
        } else {
          Command: toastr["error"](result['message']);
        }
      }
    });
  }



});


 function updateTimeAgo(){
  $.ajax({
    type: 'POST',
    url:  BASE_URL + '/ajax_get_keywords_time',
    data: {request_id:$('.campaignID').val(),_token:$('meta[name="csrf-token"]').attr('content')},
    dataType: 'json',
    success: function(result) {
      if (result['status'] == '1') {
        $('.keyword_time').html(result['time']);
      }
    }
  });
}

$(document).on('click','#refresh_multiple_keywords',function(e){
  e.preventDefault();
  var checked = []
  var keyword = 0;
  $("input[name='selected_keywords[]']:checked").each(function () {
    checked.push(parseInt($(this).val()));
    keyword++;
  });

  if(checked.length == 0){
    // $('#refresh_multiple_keywords').addClass('refresh-gif');
    Command: toastr["error"]('Select Keyword(s) to refresh!');
    return false;
  }

  $('.refresh-progress').removeClass('hidden');
  $('#js-progressbar').val(0);
  $('#start').html('0/');
  $('#total_keywords').html(keyword);
  $('.total_keywords_val').val(keyword);
  // $('#total_keywords').css('display','none');
  $('#refresh_multiple_keywords').addClass('refresh-gif');


  if(checked.length > 0){
    $.ajax({
      type: "POST",
      url: BASE_URL + "/ajax_update_live_keyword_tracking",
      data: {selected_ids:checked, request_id: $('.campaignID').val(),_token:$('meta[name="csrf-token"]').attr('content')},
      dataType: 'json',
      success: function(result) {
        getUpdateRow();
        if(result['status'] == '1'){
          Command: toastr["success"](result['message']);
        } else if(result['status'] == '2'){
          Command: toastr["error"]('Please try again!');
        } else{
          Command: toastr["error"](result['message']);
        }
      }
    });
  }else{
    $('.refresh-progress').addClass('hidden');
  }


});



/*
tags functionality*/
$(document).ready(function(){
  show_existing_tags($('.campaignID').val());
});


function show_existing_tags(campaign_id){
  $.ajax({
    url:BASE_URL +'/ajax_list_existing_tags',
    type:'GET',
    data:{campaign_id}, 
    dataType:'json',
    success:function(response){
      if(response['status'] == 1){

        $("#fitler-tags-div").html('');
        $("#fitler-tags-div").html(response['html']);
        $("#fitler-tags-div-rankings").html('');
        $("#fitler-tags-div-rankings").html(response['html']);
        $('#filter-tags').selectpicker('refresh');
        $('.selectpicker').selectpicker('refresh');

      }
      if(response['status'] == 0){
        $("#fitler-tags-div").html('');
        $("#fitler-tags-div-rankings").html('');
      }
    }
  });
}
// $(document).on('change','#filter-tags',function(e){
//   var tag_val = $(this).val();
//   $('#tag_id_value').val(tag_val);
//   $('#LiveKeywordTable_data tr th').addClass('ajax-loader');
//   $('#LiveKeywordTable_data tr td').addClass('ajax-loader');
//   LiveKeywordTrackingList($('.campaignID').val(), $('#hidden_column_name_liveKeyword').val(), $('#hidden_sort_type_liveKeyword').val(), $('#limit_liveKeyword').val(), $('.hidden_page_liveKeyword').val(), $('.live-keyword-search').val(),'',$('#tag_id_value').val());
// });


$(document).on('click','#update_keyword_filters',function(e){
  e.preventDefault();
  $('.filter-progress-loader').css('display','block');
  $('.filter-progress-loader').addClass('complete');
  var selected_type = $('#selected_type').val();
  var tracking_type = document.querySelector('input[name = "tracking_type"]:checked').value;;
  var tag_id = $('#filter-tags').val();
  var limit = $('#live-keyword-limit').val();
  var page = 1;
  var query = $('.live-keyword-search').val();
  var column_name =  $('#hidden_column_name_liveKeyword').val();
  var order_type = $('#hidden_sort_type_liveKeyword').val();
  var key = $('#encriptkey').val();

  $('#tag_id_value').val(tag_id);
  $('#selected_type_val').val(selected_type);
  $('#tracking_type_val').val(tracking_type);

  setTimeout(function(){
    $('.filter-progress-loader').addClass('complete');
    setTimeout(function(){
      $('.filter-progress-loader').css('display','none');
      $('.filter-progress-loader').removeClass('complete');
    }, 100);
    LiveKeywordTrackingList($('.campaignID').val(), column_name, order_type, limit, page, query,key,tag_id,tracking_type,selected_type);
    $('#EditKeywordsFiltersClose').trigger('click');
  }, 1000);


});


$(document).on('click','#update_keyword_filters_rankings',function(e){
  e.preventDefault();
  $('.filter-progress-loader').css('display','block');
  $('.filter-progress-loader').addClass('complete');
  var selected_type = $('#keywords_Filter_rankings #selected_type').val();
  var tracking_type = $('#keywords_Filter_rankings  input[name=tracking_type]:checked').val();

  var tag_id = $('#keywords_Filter_rankings #filter-tags').val();
  var limit = $('#rankings #live-keyword-limit').val();
  var page = 1;
  var query = $('#rankings .live-keyword-search').val();
  var column_name =  $('#rankings #hidden_column_name_liveKeyword').val();
  var order_type = $('#rankings #hidden_sort_type_liveKeyword').val();

  $('#rankings #tag_id_value').val(tag_id);
  $('#rankings #selected_type_val').val(selected_type);
  $('#rankings #tracking_type_val').val(tracking_type);
  var key = $('#encriptkey').val();

  setTimeout(function(){
    $('.filter-progress-loader').addClass('complete');
    setTimeout(function(){
      $('.filter-progress-loader').css('display','none');
      $('.filter-progress-loader').removeClass('complete');
    }, 100);
    LiveKeywordTrackingList($('.campaignID').val(), column_name, order_type, limit, page, query,key,tag_id,tracking_type,selected_type);

    $('#EditKeywordsFiltersRankingsClose').trigger('click');
  }, 1000);


});