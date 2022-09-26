var BASE_URL = $('.base_url').val();
var color = Chart.helpers.color;

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

$(document).on("click", '#AddKeywordsBtn, #AddKeywordsBtnClose', function() {
  $("body").find(".add-keywords-popup").toggleClass("open");
  $('#add_new_keywords_data').removeAttr('disabled');
  $('.keyword_field').val('');
});

$(document).on("click", function(e) {
  if ($(e.target).is(".add-keywords-popup .add-keywords-popup-inner, #AddKeywordsBtn, .add-keywords-popup .add-keywords-popup-inner *") === false) {
    document.getElementById("addNewKeyword").reset();
    $(".add-keywords-popup").removeClass("open");
  }
});

$(document).on("click", '#EditKeywordsBtn, #EditKeywordsBtnClose', function() {
   var checked =[];
  $("input[name='selected_keywords[]']:checked").each(function () {
    checked.push(parseInt($(this).val()));
  });
  
  if(checked.length == 0 ){
    Command: toastr["error"]('Please select keyword(s) before edit.');
    return false;
  }
  $("body").find(".edit-keywords-popup").toggleClass("open");
});

$(document).on("click", function(e) {
  if ($(e.target).is(".edit-keywords-popup .edit-keywords-popup-inner, #EditKeywordsBtn, .edit-keywords-popup .edit-keywords-popup-inner *") === false) {
    $(".edit-keywords-popup").removeClass("open");
  }
});


$(document).on("click", '#EditKeywordsFilters, #EditKeywordsFiltersClose', function() {
  $("body").find(".edit-keywordsFilters-popup").toggleClass("open");
});

$(document).on("click", function(e) {
  if ($(e.target).is(".edit-keywords-popup .edit-keywords-popup-inner, #EditKeywordsBtn, .edit-keywords-popup .edit-keywords-popup-inner *") === false) {
    $(".edit-keywords-popup").removeClass("open");
  }
});

$(document).on("click", function(e) {
  if ($(e.target).is(".edit-keywordsFilters-popup .edit-keywords-popup-inner, #EditKeywordsFilters, .edit-keywordsFilters-popup .edit-keywords-popup-inner *") === false) {
    $(".edit-keywordsFilters-popup").removeClass("open");
  }
});

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

function LiveKyewordTable(campaign_id){
  var column_name = $('#hidden_column_name_liveKeyword').val();
  var order_type = $('#hidden_sort_type_liveKeyword').val();
  var limit = $('#limit_liveKeyword').val();
  var page = $('.hidden_page_liveKeyword').val();
  var query = $('.live-keyword-search').val();
  var tag_id = $('#tag_id_value').val();
  var checked_id_value = $('#checked_id_value').val();
  var tracking_type = $('#tracking_type_val').val();
  var selected_type = $('#selected_type_val').val();


  $('#hidden_page_liveKeyword').val(page);
  LiveKeywordTrackingList($('.campaignID').val(), column_name, order_type, limit, page, query,checked_id_value,tag_id,tracking_type,selected_type);
}


function LiveKeywordTrackingList(campaign_id, column_name, order_type, limit, page, query,checked_id,tag_id,tracking_type,selected_type='') {
  $('.project-search').removeClass('ajax-loader');
  $.ajax({
    url: BASE_URL + '/ajax_live_keyword_list',
    type: 'GET',
    data: {campaign_id, column_name, order_type, limit, page, query, checked_id,tag_id,tracking_type,selected_type},
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
    data: {campaign_id, column_name, order_type, limit, page, query, query,tag_id,tracking_type,selected_type},
    success: function(response) {
      $('.liveKeywords-profile-foot').html('');
      $('.liveKeywords-profile-foot').html(response);
      $('.LiveKeywords').removeClass('ajax-loader');
      $('.project-entries').removeClass('ajax-loader');
    }
  });
}

$(document).on('click', '.LiveKeywords a', function(e) {
  e.preventDefault();


  $('li').removeClass('active');
  $(this).parent().addClass('active');


  $('#LiveKeywordTable_data tr td').addClass('ajax-loader');
  /*$('.project-entries').addClass('ajax-loader');*/
  $('.LiveKeywords ').addClass('ajax-loader');

  var column_name = $('#hidden_column_name_liveKeyword').val();
  var order_type = $('#hidden_sort_type_liveKeyword').val();
  var limit = $('#limit_liveKeyword').val();
  var page = $(this).attr('href').split('page=')[1];
  var query = $('.live-keyword-search').val();
  var tag_id = $('#tag_id_value').val();
  var tracking_type = $('#tracking_type_val').val();
  var selected_type = $('#selected_type_val').val();

  $('#hidden_page_liveKeyword').val(page);
  LiveKeywordTrackingList($('.campaignID').val(), column_name, order_type, limit, page, query,'',tag_id,tracking_type,selected_type);
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
 var tag_id = $('#tag_id_value').val();
 var selected_type = $('#selected_type_val').val();
 var tracking_type =  $('#tracking_type_val').val();

 LiveKeywordTrackingList($('.campaignID').val(), column_name, reverse_order, limit, page, query,'',tag_id,tracking_type,selected_type);

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
});

$(document).on('keyup','.live-keyword-search',delay(function (e) {
  if($('.live-keyword-search').val() != '' || $('.live-keyword-search').val() != null){
    $('.liveKeyword-search-clear').css('display','block');
    live_tracking_search();
  }
  if($('.live-keyword-search').val() == '' || $('.live-keyword-search').val() == null){
     $('.liveKeyword-search-clear').css('display','none');
  }
}, 1500));

function live_tracking_search(){
  $('#LiveKeywordTable_data tr td').addClass('ajax-loader');
  $('.LiveKeywords ').addClass('ajax-loader');

  var query = $('.live-keyword-search').val();
  var column_name =  $('#hidden_column_name_liveKeyword').val();
  var order_type = $('#hidden_sort_type_liveKeyword').val();
  var limit = $('#limit_liveKeyword').val();
  var tag_id = $('#tag_id_value').val();
  var page =1; 
  var tracking_type = $('#tracking_type_val').val();
  var selected_type = $('#selected_type_val').val();

  LiveKeywordTrackingList($('.campaignID').val(), column_name, order_type, limit, page, query,'',tag_id,tracking_type,selected_type);
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

  $('#LiveKeywordTable_data tr td').addClass('ajax-loader');
  /*$('.project-entries').addClass('ajax-loader');*/
  $('.LiveKeywords ').addClass('ajax-loader');

  var limit = $(this).val();
  $('#limit_liveKeyword').val(limit);
  // var page = $('#hidden_page_liveKeyword').val();
  var page = 1;
  var query = $('.live-keyword-search').val();

  var column_name =  $('#hidden_column_name_liveKeyword').val();
  var order_type = $('#hidden_sort_type_liveKeyword').val();
  var tag_id = $('#tag_id_value').val();
  var tracking_type = $('#tracking_type_val').val();
  var selected_type = $('#selected_type_val').val();

  LiveKeywordTrackingList($('.campaignID').val(), column_name, order_type, limit, page, query,'',tag_id,tracking_type,selected_type);
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
      if(result['status'] == '1'){  
        LiveKeywordTrackingList($('.campaignID').val(), $('#hidden_column_name_liveKeyword').val(), $('#hidden_sort_type_liveKeyword').val(), $('#limit_liveKeyword').val(), $('.hidden_page_liveKeyword').val(), $('.live-keyword-search').val(),'','',$('#tracking_type_val').val(),$('#selected_type_val').val());
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

  inputObj.focusout(function() {
   var text = $(this).val();

   if(preText.trim() != text){
    updateStartRankPosition(text,data_id);
    tdObj.html(text);
  }else{
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
      LiveKeywordStats($('.campaignID').val());
      LiveKeywordTrackingList($('.campaignID').val(),$('#hidden_column_name_liveKeyword').val(),$('#hidden_sort_type_liveKeyword').val(), $('#limit_liveKeyword').val(), $('.hidden_page_liveKeyword').val(),$('.live-keyword-search').val(),'','',$('#tracking_type_val').val(),$('#selected_type_val').val());
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

$(document).on('click', '#LiveKeywordTable_data tbody td,#LiveKeywordTable_data  thead th:first-child', function(e){
  $(this).parent().find('input[type="checkbox"]').trigger('click');
});

$(document).on('click','thead #selectAllKeywords', function(e){
  if(this.checked){
   $('#LiveKeywordTable_data tbody input[type="checkbox"]:not(:checked)').trigger('click');
 } else {
   $('#LiveKeywordTable_data tbody input[type="checkbox"]:checked').trigger('click');
 }

      // Prevent click event from propagating to parent
      e.stopPropagation();
    });


 // Handle click on checkbox
 $(document).on('click', '#LiveKeywordTable_data tbody input[type="checkbox"]', function(e){
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

 $(document).on('click','#delete_multiple_keywords', function(e){
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
    $('.progress-loader').css('display','block');
    $.ajax({
      type: "POST",
      url: BASE_URL+ "/ajax_remove_multiple_keywords",
      data: {selected_ids:checked, request_id: $('.campaignID').val(),_token: $('meta[name="csrf-token"]').attr('content')},
      dataType: 'json',
      success: function(result) {
        if (result['status'] == '1') {
          $('.progress-loader').css('display','none');
          LiveKeywordStats($('.campaignID').val());
          LiveKeywordTrackingList($('.campaignID').val(), $('#hidden_column_name_liveKeyword').val(), $('#hidden_sort_type_liveKeyword').val(), $('#limit_liveKeyword').val(), $('.hidden_page_liveKeyword').val(), $('.live-keyword-search').val(),'','',$('#tracking_type_val').val(),$('#selected_type_val').val());

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
    Command: toastr["error"]('Select Keyword(s) to refresh!');
    return false;
  }

  $('.refresh-progress').removeClass('hidden');
  $('#js-progressbar').val(0);
  $('#start').html('0/');
  $('#total_keywords').html(keyword);
  $('.total_keywords_val').val(keyword);
  $('#refresh_multiple_keywords').addClass('refresh-gif');


  if(checked.length > 0){
   $('#checked_id_value').val(checked);

    var i,j,temparray,chunk = 50;
    for (i=0,j=checked.length; i<j; i+=chunk) {
    temparray = checked.slice(i,i+chunk);
      update_keywords(temparray,$('.campaignID').val(),i);
    }
  }else{
    $('.refresh-progress').addClass('hidden');
  }


});

function update_keywords(chunked_ids,campaignID,counter){
  $.ajax({
      type: "POST",
      url: BASE_URL + "/ajax_update_live_keyword_tracking",
      data: {selected_ids:chunked_ids, request_id: campaignID,_token:$('meta[name="csrf-token"]').attr('content')},
      dataType: 'json',
      success: function(result) {
        $('.progress-loader').css('display','block');
        getUpdateRow();
        if(counter == 0){
           if(result['status'] == '1'){
            Command: toastr["success"](result['message']);
          } else if(result['status'] == '2'){
            Command: toastr["error"]('Please try again!');
          } else{
            Command: toastr["error"](result['message']);
          }
        }
      }
    });
}

function getUpdateRow(){
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $.ajax({
    type: "POST",
    url: BASE_URL + '/ajaxgetLatestKeyword',
    data: {request_id: $('.campaignID').val()},
    dataType: ' json',
    success: function(result) {
      if (result['status'] == '1') {
        if(result['sync'] == 0){

          var pPos = parseInt($('#total_keywords').text());

          var pEarned = parseInt(result['sync']);

          var perc=0;
          if(isNaN(pPos) || isNaN(pEarned)){
            perc=0;
          }else{
            perc = (((pPos-pEarned)/pPos) * 100).toFixed(0);
          }

          $('#start').html(pPos - pEarned+'/');

          $('#js-progressbar').val(perc);

          setTimeout(function(){
            $('.progress-loader').addClass('complete');
            setTimeout(function(){
              $('.progress-loader').css('display','none');
              $('.progress-loader').removeClass('complete');
            }, 1000);
            updateTimeAgo();
            LiveKeywordStats($('.campaignID').val());
            LiveKeywordTrackingList($('.campaignID').val(), $('#hidden_column_name_liveKeyword').val(), $('#hidden_sort_type_liveKeyword').val(), $('#limit_liveKeyword').val(), $('.hidden_page_liveKeyword').val(), $('.live-keyword-search').val(), $('#checked_id_value').val(),$('#tag_id_value').val(),$('#tracking_type_val').val(),$('#selected_type_val').val());
            $('.refresh-progress').addClass('hidden');
            $('#refresh_multiple_keywords').removeClass('refresh-gif');
          }, 1000);


        }else{
          var pPos = parseInt($('#total_keywords').text());
          var pEarned = parseInt(result['sync']);
          var perc=0;
          if(isNaN(pPos) || isNaN(pEarned)){
            perc=0;
          }else{
            perc = (((pPos-pEarned)/pPos) * 100).toFixed(0);
          }

          $('#start').html(pPos - pEarned+'/');

          $('#js-progressbar').val(perc);

          setTimeout(function(){
            getUpdateRow();
            LiveKeywordStats($('.campaignID').val());
            LiveKeywordTrackingList($('.campaignID').val(), $('#hidden_column_name_liveKeyword').val(), $('#hidden_sort_type_liveKeyword').val(), $('#limit_liveKeyword').val(), $('.hidden_page_liveKeyword').val(), $('.live-keyword-search').val(),'',$('#tag_id_value').val(),$('#tracking_type_val').val(),$('#selected_type_val').val());
          },2000);
        }

      } else{
        $(".keyword_time").hide();
        $("#LiveKeywordTable_data"). load(" #LiveKeywordTable_data > *");
        $("#LiveKeywordTable_foot"). load(" #LiveKeywordTable_foot > *");
      }
    }
  });

}


google.maps.event.addDomListener(window, 'load', initializeAdd);
function initializeAdd() {
  var input = document.getElementById('add_keyword_location');
  var autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.addListener('place_changed', function () {
    var place = autocomplete.getPlace();
    $('#lat').val(place.geometry['location'].lat());
    $('#long').val(place.geometry['location'].lng());
  });
}

google.maps.event.addDomListener(window, 'load', initializeUpdate);
function initializeUpdate() {
  var input1 = document.getElementById('edit_keyword_location');
  var autocomplete = new google.maps.places.Autocomplete(input1);
  autocomplete.addListener('place_changed', function () {
    var place = autocomplete.getPlace();
    $('#latUpdate').val(place.geometry['location'].lat());
    $('#longUpdate').val(place.geometry['location'].lng());
  });
}

$(document).on('keyup','#edit_keywords input',function(e){
  e.preventDefault();
  var update_domain_url = $('.update_domain_url').val();
  var update_region = $('#update_region').val();
  var update_tracking_options = $(".tracking_options:checked").val();
  var update_language = $('#update_language').val();
  var update_location = $('#edit_keyword_location').val();
  var lat = $('#latUpdate').val();
  var long = $('#longUpdate').val();

  document.getElementById('update_domain_url_error').innerHTML = '';
  if (update_domain_url == '') {
    $('#update_domain_url_error').parent().css('display','block');
    document.getElementById('update_domain_url_error').innerHTML = 'Field is required.';
     return false;
  }else{
    $('#update_domain_url_error').parent().css('display','none');
  }


  document.getElementById('update_regions_error').innerHTML = '';
  if (update_region == '') {
    $('#update_regions_error').parent().css('display','block');
    document.getElementById('update_regions_error').innerHTML = 'Field is required.';
     return false;
  }else{
    $('#update_regions_error').parent().css('display','none');
  }

  document.getElementById('update_tracking_options_error').innerHTML = '';
  if (update_tracking_options == '') {
    $('#update_tracking_options_error').parent().css('display','block');
    document.getElementById('update_tracking_options_error').innerHTML = 'Field is required.';
     return false;
  }else{
    $('#update_tracking_options_error').parent().css('display','none');
  }

  document.getElementById('update_language_error').innerHTML = '';
  if (update_language == '') {
    $('#update_language_error').parent().css('display','block');
    document.getElementById('update_language_error').innerHTML = 'Field is required.';
    return false;
  }else{
    $('#update_language_error').parent().css('display','none');
  }

  document.getElementById('update_dfs_locations_error').innerHTML = '';
  if (update_location == '' || update_location == null) {
    $('#update_dfs_locations_error').parent().css('display','block');
    document.getElementById('update_dfs_locations_error').innerHTML = 'Field is required.';
    return false;
  }else{
    $('#update_dfs_locations_error').parent().css('display','none');
  }
});

$(document).on('click','#update_keyword_locations',function(e){
  e.preventDefault();

  var checked =[];

  $("input[name='selected_keywords[]']:checked").each(function () {
    checked.push(parseInt($(this).val()));
  });


  if(checked.length == 0 ){
    Command: toastr["error"]('Please select keywords before update');
    return false;
  }

  var update_domain_url = $('.update_domain_url').val();
  var update_region = $('#update_region').val();
  var update_tracking_options = $(".tracking_options:checked").val();
  var update_language = $('#update_language').val();
  var update_location = $('#edit_keyword_location').val();
  var lat = $('#latUpdate').val();
  var long = $('#longUpdate').val();
  var domain_type = $('.update_keyword_domain_type').text();
  var local_listing = $('.update_ignore_local_listing').prop('checked');


  document.getElementById('update_domain_url_error').innerHTML = '';
  if (update_domain_url == '') {
    $('#update_domain_url_error').parent().css('display','block');
    document.getElementById('update_domain_url_error').innerHTML = 'Field is required.';
    return false;
  }else{
    $('#update_domain_url_error').parent().css('display','none');
  }

  document.getElementById('update_regions_error').innerHTML = '';
  if (update_region == '') {
    $('#update_regions_error').parent().css('display','block');
    document.getElementById('update_regions_error').innerHTML = 'Region is required.';
     return false;
  }else{
    $('#update_regions_error').parent().css('display','none');
  }

  document.getElementById('update_tracking_options_error').innerHTML = '';
  if (update_tracking_options == '') {
    $('#update_tracking_options_error').parent().css('display','block');
    document.getElementById('update_tracking_options_error').innerHTML = 'Tracking Option is required.';
     return false;
  }else{
    $('#update_tracking_options_error').parent().css('display','none');
  }

  document.getElementById('update_language_error').innerHTML = '';
  if (update_language == '') {
    $('#update_language_error').parent().css('display','block');
    document.getElementById('update_language_error').innerHTML = 'Language is required.';
    return false;
  }else{
    $('#update_language_error').parent().css('display','none');
  }

  document.getElementById('update_dfs_locations_error').innerHTML = '';
  if (update_location == '' || update_location == null) {
    $('#update_dfs_locations_error').parent().css('display','block');
    document.getElementById('update_dfs_locations_error').innerHTML = 'Location is required.';
    return false;
  }else{
    $('#update_dfs_locations_error').parent().css('display','none');
  }


  if(checked.length > 0){
    $('#update_keyword_locations').attr("disabled", "disabled");
    $.ajax({
      type: "POST",
      url: BASE_URL + '/ajax_update_live_keywords_location',
      data: {update_region, update_tracking_options,update_language, update_location,checked,_token:$('meta[name="csrf-token"]').attr('content'),lat,long,domain_type,local_listing,update_domain_url},
      dataType: 'json',
      success: function(result) {

        if(result.status == 1){
          Command: toastr["success"](result['message']);
          $(".edit-keywords-popup").removeClass("open");
          document.getElementById("edit_keywords").reset();
        }
        if(result.status == 0){
          Command: toastr["warning"](result['message']);
        }
        $('#update_keyword_locations').removeAttr("disabled", "disabled");
        LiveKeywordTrackingList($('.campaignID').val(), $('#hidden_column_name_liveKeyword').val(), $('#hidden_sort_type_liveKeyword').val(), $('#limit_liveKeyword').val(), $('.hidden_page_liveKeyword').val(), $('.live-keyword-search').val(),'','',$('#tracking_type_val').val(),$('#selected_type_val').val());


      }
    });
  }
});

$(document).on("keyup", '#addNewKeyword input, #addNewKeyword textarea', function(e) {

     var campaign_id = $('.campaign_id').val();
      var domain_url = $('.domain_url').val();
      var keyword_ranking = $('.keyword_field').val();
      var regions = $('#add_region').val();
      var tracking_options = $('.tracking_options').val();
      var language = $('#add_language').val();
      var dfs_locations = $('.dfs_locations').val();
      var lat = $('#lat').val();
      var long = $('#long').val();
      var lines  = $('.keyword_field').val().split(/\n/);

    if (domain_url == '') {
      $('#domain_url_error').html('<p>Field is required</p>');
      $('#domain_url_error').show();
      // document.getElementById('domain_url_error').innerHTML = 'Field is required.';
    }else{
      $('#domain_url_error').hide();
    }

    if (keyword_ranking == '') {
      $('#keywords_error').html('<p>Field is required</p>');
      $('#keywords_error').show();
      // document.getElementById('keywords_error').innerHTML = 'Field is required.';
    }else{
      $('#keywords_error').hide();
    }
    if (regions == '') {
      $('#regions_error').html('<p>Field is required</p>');
      $('#regions_error').show();
      // document.getElementById('regions_error').innerHTML = 'Field is required.';
    }else{
      $('#regions_error').hide();
    }
    if (tracking_options == '') {
      $('#tracking_options_error').html('<p>Field is required</p>');
      $('#tracking_options_error').show();

      // document.getElementById('tracking_options_error').innerHTML = 'Field is required.';
    }else{
      $('#tracking_options_error').hide();
    }

    if (language == '') {
      $('#language_error').html('<p>Field is required</p>');
      $('#language_error').show();
      // document.getElementById('language_error').innerHTML = 'Field is required.';
    }else{
      $('#language_error').hide();
    }

    if (dfs_locations == '' || dfs_locations == null) {
      $('#locations_error').html('<p>Invalid location</p>');
      $('#locations_error').show();
      // document.getElementById('locations_error').innerHTML = 'Field is required.';
    }else{
      $('#locations_error').hide();
    }
});

$(document).on('click','#add_new_keywords_data', function(){

  var campaign_id = $('.campaign_id').val();
  var domain_url = $('.domain_url').val();
  var keyword_ranking = $('.keyword_field').val();
  var regions = $('#add_region').val();
  var tracking_options = $('.tracking_options').val();
  var language = $('#add_language').val();
  var dfs_locations = $('.dfs_locations').val();
  var lat = $('#lat').val();
  var long = $('#long').val();
  var lines  = $('.keyword_field').val().split(/\n/);
  var keyword_domain_type = $('.keyword_domain_type').text();
  var ignore_local_listing = $('.ignore_local_listing').prop('checked');

  /*error section start */
  document.getElementById('domain_url_error').innerHTML = '';
  document.getElementById('keywords_error').innerHTML = '';
  document.getElementById('regions_error').innerHTML = '';
  document.getElementById('tracking_options_error').innerHTML = '';
  document.getElementById('language_error').innerHTML = '';
  document.getElementById('locations_error').innerHTML = '';


  if (domain_url == '') {
    $('#domain_url_error').html('<p>Field is required</p>');
    $('#domain_url_error').show();
  }else{
    $('#domain_url_error').hide();
  }

  if (keyword_ranking == '') {
    $('#keywords_error').html('<p>Field is required</p>');
    $('#keywords_error').show();
  }else{
    $('#keywords_error').hide();
  }
  if (regions == '') {
    $('#regions_error').html('<p>Field is required</p>');
    $('#regions_error').show();
  }else{
    $('#regions_error').hide();
  }
  if (tracking_options == '') {
    $('#tracking_options_error').html('<p>Field is required</p>');
    $('#tracking_options_error').show();

  }else{
    $('#tracking_options_error').hide();
  }

  if (language == '') {
    $('#language_error').html('<p>Field is required</p>');
    $('#language_error').show();
  }else{
    $('#language_error').hide();
  }

  if (dfs_locations == '' || dfs_locations == null) {
    $('#locations_error').html('<p>Field is required</p>');
    $('#locations_error').show();
  }else{
    $('#locations_error').hide();
  }

  if (lat == '' || lat == null) {
    $('#locations_error').html('<p>Invalid location </p>');
    $('#locations_error').show();
  }else{
    $('#locations_error').hide();
  }

  if (long == '' || long == null) {
    $('#locations_error').html('<p>Invalid location </p>');
    $('#locations_error').show();
  }else{
    $('#locations_error').hide();
  }

  /*error section end */
  
  if (domain_url != '' && keyword_ranking !== '' && regions != '' && tracking_options!='' && language!='' && dfs_locations!='' && lat !='' && long !='') {

    $('#add_new_keywords_data').attr("disabled", "disabled");

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var form_data  = $('form#addNewKeyword').serializeArray();


    $.ajax({
      type: "POST",
      url: BASE_URL + '/ajax_add_keywords_data',
      data: form_data,
      dataType: 'json',
      success: function(result) {
        if (result['status'] == '2') {
          Command: toastr["error"](result['message']);
          return false;
        }

        if (result['status'] == '1') {
          $('.progress-loader').css('display','block');
          var today = result['today'];
          $(".add-keywords-popup").removeClass("open");
          document.getElementById("addNewKeyword").reset();
          Command: toastr["success"](result['message']);
          var keyword = 0;
          $('.no_keyword').html('');
          var filtered_keywords = result['newKeywords']; 
          $.each(filtered_keywords, function(i, line){
            var lines_new  = line.split(/\n/);
            var table_list = '<tr class="odd"><td><div class="flex"><i class="fa fa-star"></i><figure class="keyword-flag-icon"><a href="#"></a></figure><figure class="location-icon"><a href="#"></a></figure><h6 uk-tooltip="title: '+lines_new+'; pos: top-left"><a href="#">'+lines_new+'</a></h6><div class="icons-list fixed"><a href="#" uk-tooltip="title:Unfavorite this keyword; pos: top-center"><i class="fa fa-star-o"></i></a><a href="#" class="downArrow" uk-tooltip="title:Show Historical Chart; pos: top-center" data-toggle="collapse" ><i class="fa fa-area-chart"></i></a><a href="#" uk-tooltip="title:See the keyword in search results; pos: top-center"><i class="fa fa-search"></i></a></div></div></td><td class="grey">??</td><td class="grey">??</td><td class="grey">??</td><td class="grey">??</td><td class="grey">??</td><td class="grey">??</td><td class="grey">??</td><td class="grey">??</td><td class="grey">??</td><td class="grey">'+today+'</td><td class="grey">??</td><td></td></tr>';
            
              $('#LiveKeywordTable_data tbody').prepend(table_list);
            
            keyword++;
          });
          
          $('.refresh-progress').removeClass('hidden');
          $('#js-progressbar').val(0);
          $('#start').html('0/');
          $('#total_keywords').html(keyword);
          $('#total_keywords').css('display','inline-block');
        

          $.ajax({
            type: "POST",
            url: BASE_URL + '/ajax_send_dfs_request',
            data: {campaign_id,domain_url,filtered_keywords,regions,language,dfs_locations,lat,long,tracking_options,keyword_domain_type,ignore_local_listing},
            dataType: 'json',
            success:function(response){
             setTimeout(function(){
              getUpdateRow();
              updateTimeAgo();
            }, 1000);
           }
         });          
        } else {
          Command: toastr["error"](result['message']);
          $('#add_new_keywords_data').removeAttr('disabled','disabled');
        }
      }
    });
  }
});




$(document).on('click','#live_keyword_excel',function(e){
  e.preventDefault();
  var campaign_id = $('.campaignID').val();
  var checked =[];

  $("input[name='selected_keywords[]']:checked").each(function () {
    checked.push(parseInt($(this).val()));
  });


  // if(checked.length == 0 ){
  //   Command: toastr["error"]('Please select keywords to export.');
  //   return false;
  // }

  //if(checked.length > 0){
    $("#live_keyword_excel a").attr("target","_blank");
    var url = BASE_URL +"/ajax_export_live_keywords?checked=" +checked+"&request_id="+campaign_id;
    // window.location = url;
    window.open(url, '_blank');

  //}

});


$(document).on('click','#show_keyword_popup',function(e){
  e.preventDefault();
  $('#showLiveKeywordCountPopup').trigger('click');
  $('#showLiveKeywordCountPopup').css('display', 'block');
  $('body').addClass('popup-open');
});


  
/*mark multiple fav/unfav*/ 
$(document).on('click','#mark_multiple_favourite',function(e){  
  e.preventDefault(); 
  var checked = []; 
  $("input[name='selected_keywords[]']:checked").each(function () { 
    checked.push(parseInt($(this).val()));  
  }); 
  if(checked.length == 0){  
    Command: toastr["error"]('Select Keyword(s) to mark favourite/unfavourite!'); 
    return false; 
  } 
  if(checked.length > 0 ){  
    $('#checked_id_value').val(checked);  
    $.ajax({  
      type: "POST", 
      url: BASE_URL+ "/ajax_multiple_keyword_fav_unfav",  
      data: {selected_ids:checked, request_id: $('.campaignID').val(),_token: $('meta[name="csrf-token"]').attr('content')},  
      dataType: 'json', 
      success: function(result) { 
        if (result['status'] == '1') {  
          LiveKeywordStats($('.campaignID').val()); 
          LiveKeywordTrackingList($('.campaignID').val(), $('#hidden_column_name_liveKeyword').val(), $('#hidden_sort_type_liveKeyword').val(), $('#limit_liveKeyword').val(), $('.hidden_page_liveKeyword').val(), $('.live-keyword-search').val(),'','',$('#tracking_type_val').val(),$('#selected_type_val').val()); 
          Command: toastr["success"](result['message']);  
        } else {  
          Command: toastr["error"](result['message']);  
        } 
      } 
    }); 
  } 
}); 


/* manage tag section (10 april)*/  
$(document).on("click", '#KeywordTagBtn, #KeywordTagBtnClose', function() {
  $('.tag-progress-loader').css('display','block');
  if(!$(".manage-tags-popup").hasClass('open')){
    fetch_existing_keyword_tags('',$('.campaignID').val());
  }
  $('.search_keyword_tag').val('');
  $("body").find(".manage-tags-popup").toggleClass("open");
});

$(document).on("click", function(e) { 
  if ($(e.target).is(".manage-tags-popup .manage-tags-popup-inner, #KeywordTagBtn, .manage-tags-popup .manage-tags-popup-inner *") === false) { 
    $(".manage-tags-popup").removeClass("open");  
  } 
}); 
var tagTypingTimer;       
var TagdoneTypingInterval = 5000;   
var $inputTag = $('.search_keyword_tag'); 
//on keyup, start the countdown 
$inputTag.on('keyup', function () { 
  clearTimeout(tagTypingTimer); 
  var query = $(this).val();  
  $('#display_type_tag').text(query); 
  tagTypingTimer = setTimeout(fetch_existing_keyword_tags(query,$('.campaign_id').val()), TagdoneTypingInterval); 
}); 
//on keydown, clear the countdown   
$inputTag.on('keydown', function () { 
  clearTimeout(tagTypingTimer); 
}); 
function fetch_existing_keyword_tags(search_tag,campaign_id){ 
  $.ajax({  
    url:BASE_URL +'/ajax_fetch_existing_keyword_tags',  
    type:'GET', 
    data:{search_tag,campaign_id},  
    dataType:'json',  
    success:function(response){ 
      $('#append_tag_div').html(response['html']);  
      $('#display_type_tag').text('');  
      $('#display_type_tag').text(response['searched_tag']);  
      $('.tag-progress-loader').css('display','none');
    } 
  }); 
} 
$(document).on('click','.existing_tag',function(){  
    var checked = []; 
  $("input[name='existing_tag[]']:checked").each(function () {  
    checked.push(parseInt($(this).val()));  
  }); 
  if(checked.length > 0){ 
    $('#apply_tag').removeAttr('disabled'); 
  }else{  
     $('#apply_tag').attr('disabled','disabled'); 
  } 
}); 
$(document).on('click','#create_keyword_tag',function(e){ 
 e.preventDefault();  
  var new_tag = $('.search_keyword_tag').val(); 
  var selected_keywordss =[]; 
  var request_id = $('.campaign_id').val(); 
  $("input[name='selected_keywords[]']:checked").each(function () { 
    selected_keywordss.push(parseInt($(this).val())); 
  }); 
  if(selected_keywordss.length == 0 ){  
    Command: toastr["error"]('Please select keywords.');  
    return false; 
  } 
  if(selected_keywordss.length != 0){ 
    $('#create_keyword_tag').attr('disabled','disabled'); 
    $.ajax({  
      type: "POST", 
      url: BASE_URL + '/ajax_create_keyword_tag', 
      data: {selected_keywordss,new_tag,_token:$('meta[name="csrf-token"]').attr('content'),request_id},  
      dataType: 'json', 
      success: function(result) { 
        if(result['status'] == 1){  
          $(".manage-tags-popup").removeClass("open");  
          show_existing_tags($('.campaign_id').val());  
          Command: toastr["success"](result['message']);  
          return false; 
        } 
         if(result['status'] == 0){ 
          $(".manage-tags-popup").removeClass("open");  
          Command: toastr["error"](result['message']);  
          return false; 
        } 
          
      } 
    }); 
  } 
}); 
// $(document).ready(function(){ 
//   show_existing_tags($('.campaign_id').val());  
// }); 
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
        $('#filter-tags').selectpicker('refresh');  
      } 
      if(response['status'] == 0){  
        $("#fitler-tags-div").html(''); 
      } 
    } 
  }); 
} 
$(document).on('click','#apply_tag',function(e){  
  e.preventDefault(); 
  var selected_keywordss = [];  
  var existing_tag = [];  
  var request_id = $('.campaign_id').val(); 
  $("input[name='selected_keywords[]']:checked").each(function () { 
    selected_keywordss.push(parseInt($(this).val())); 
  }); 
  $("input[name='existing_tag[]']:checked").each(function () {  
    existing_tag.push(parseInt($(this).val())); 
  }); 
  if(selected_keywordss.length == 0 ){  
    Command: toastr["error"]('Please select keywords.');  
    return false; 
  } 
  if(selected_keywordss.length != 0){ 
    $('#apply_tag').attr('disabled','disabled');  
    $.ajax({  
      type: "POST", 
      url: BASE_URL + '/ajax_apply_existing_tags',  
      data: {selected_keywordss,request_id,existing_tag,_token:$('meta[name="csrf-token"]').attr('content')}, 
      dataType: 'json', 
      success: function(result) { 
        if(result['status'] == 1){  
          $(".manage-tags-popup").removeClass("open");  
          show_existing_tags($('.campaign_id').val());  
          Command: toastr["success"](result['message']);  
          return false; 
        } 
         if(result['status'] == 0){ 
          $(".manage-tags-popup").removeClass("open");  
          Command: toastr["error"](result['message']);  
          return false; 
        } 
          
      } 
    }); 
  } 
}); 
// $(document).on('change','#filter-tags',function(e){ 
//   var tag_val = $(this).val();  
//   $('#tag_id_value').val(tag_val);  
//   $('#LiveKeywordTable_data tr th').addClass('ajax-loader');  
//       $('#LiveKeywordTable_data tr td').addClass('ajax-loader');  
//   LiveKeywordTrackingList($('.campaignID').val(), $('#hidden_column_name_liveKeyword').val(), $('#hidden_sort_type_liveKeyword').val(), $('#limit_liveKeyword').val(), $('.hidden_page_liveKeyword').val(), $('.live-keyword-search').val(),'',$('#tag_id_value').val()); 
// }); 
$(document).on('click','.delete_keyword_tag',function(e){ 
  e.preventDefault(); 
  var keyword_tag_id = $(this).attr('data-tag-id'); 
  var request_id = $(this).attr('data-request-id'); 
  $.ajax({  
    type:'POST',  
    url:BASE_URL + '/ajax_delete_keyword_tag',  
    data:{keyword_tag_id,request_id,_token:$('meta[name="csrf-token"]').attr('content')}, 
    dataType:'json',  
    success:function(response){ 
      if(response['status'] == 1){  
        Command: toastr["success"](response['message']);  
        fetch_existing_keyword_tags('',$('.campaign_id').val());  
        show_existing_tags($('.campaign_id').val());  
        return false; 
      } 
      if(response['status'] == 0){  
        Command: toastr["error"](response['message']);  
        return false; 
      } 
    } 
  }); 
});

function RegionalDatabase(campaign_id){
  $.ajax({
    type:'GET',
    url:BASE_URL +'/ajax_get_regional_database',
    dataType:'json',
    data:{campaign_id},
    success:function(response){
      if(response['status'] == 1){
        $('#update_region').html(response['records']);
        $('.selectpicker').selectpicker('refresh');

        $('#add_region').html(response['records']);
        $('.selectpicker').selectpicker('refresh');
      }
    }
  });
}

function languages(campaign_id){
  $.ajax({
    type:'GET',
    url:BASE_URL +'/ajax_get_languages',
    dataType:'json',
    data:{campaign_id},
    success:function(response){
      if(response['status'] == 1){
        $('#update_language').html(response['records']);
        $('.selectpicker').selectpicker('refresh');

        $('#add_language').html(response['records']);
        $('.selectpicker').selectpicker('refresh');
      }
    }
  });
}


function checkLiveKeywordCount(){
  $.ajax({
    type:'GET',
    url:BASE_URL+'/ajax_check_keyword_count',
    dataType:'json',
    success:function(response){
      $('.addlivekeywordBtn').attr('id',response['id']);
    }
  });
}



$(".domain-dropDownBox>button").on("click", function(){
  $(".domain-dropDownMenu").toggleClass("show");
});

$(document).on('click','.domain-type-list',function(e){
  var selected = $(this).find('h6').text();
  $('.keyword_domain_type').text(selected);
  $('#keyword-domain-dropDownMenu').removeClass('show');
  $('.domain-type-ul li').removeClass('active');
  $(this).addClass('active');
  e.stopPropagation();
});

$(document).on('click','.update-domain-type-list',function(e){
  var selected = $(this).find('h6').text();
  $('.update_keyword_domain_type').text(selected);
  $('#update-keyword-domain-dropDownMenu').removeClass('show');
  $('.update-domain-type-ul li').removeClass('active');
  $(this).addClass('active');
  e.stopPropagation();
});


function checkDomainType(campaign_id){
  $.ajax({
    type:'GET',
    url:BASE_URL +'/ajax_get_domainType',
    dataType:'json',
    data:{campaign_id},
    success:function(response){
      /*adding*/
      $('.keyword_domain_type').text(response);
      $('.domain-type-ul li').removeClass('active');
      jQuery('h6:contains('+response+')').closest('.domain-type-list').addClass('active');
      /*update*/
      $('.update_keyword_domain_type').text(response);
      $('.update-domain-type-ul li').removeClass('active');
      jQuery('h6:contains('+response+')').closest('.update-domain-type-list').addClass('active');
    }
  });
}

$(document).on('change','#tracking_type',function(e){
  e.preventDefault();
  $('#LiveKeywordTable_data tr td').addClass('ajax-loader');
  $('.LiveKeywords').addClass('ajax-loader');

  var tracking_type = $(this).val();
  var limit = $('#live-keyword-limit').val();
  var page = 1;
  var query = $('.live-keyword-search').val();
  var column_name =  $('#hidden_column_name_liveKeyword').val();
  var order_type = $('#hidden_sort_type_liveKeyword').val();
  var tag_id = $('#tag_id_value').val();

  LiveKeywordTrackingList($('.campaignID').val(), column_name, order_type, limit, page, query,'',tag_id,tracking_type);
});


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

  $('#tag_id_value').val(tag_id);
  $('#selected_type_val').val(selected_type);
  $('#tracking_type_val').val(tracking_type);

  setTimeout(function(){
    $('.filter-progress-loader').addClass('complete');
    setTimeout(function(){
      $('.filter-progress-loader').css('display','none');
      $('.filter-progress-loader').removeClass('complete');
    }, 100);
   LiveKeywordTrackingList($('.campaignID').val(), column_name, order_type, limit, page, query,'',tag_id,tracking_type,selected_type);
   $('#EditKeywordsFiltersClose').trigger('click');
  }, 1000);


});